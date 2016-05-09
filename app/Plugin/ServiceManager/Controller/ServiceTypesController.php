<?php
class ServiceTypesController extends ServiceManagerAppController {
	public $uses = array('ServiceManager.ServiceType');
	public $paginate = array();
	public $id = null;
	
	function admin_index($search=null){
		$this->paginate = array();
		$condition = null;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'index' ,$this->request->data['search']));
		}
		$this->paginate['order']=array('ServiceType.reorder'=>'ASC','ServiceType.id'=>'DESC');		
		if($search!=null){
			$search = urldecode($search);
			$condition['ServiceType.title like'] = $search.'%';
		}
		$service_type=$this->paginate("ServiceType", $condition);	
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/services_types'),
			'name'=>'Manage Services Type'
		);
		$this->set('services',$service_type);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}
	
	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$result= array();
			$result['ServiceType']['id'] = $id;
			$result['ServiceType']['reorder'] = $order;
			$this->ServiceType->create();
			$this->ServiceType->save($result);
		}
	}
	
	function admin_add_attribute($id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/service_types'),
			'name'=>'Manage ServiceType'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/mail/add_attribute'),
			'name'=>'Add Service Attribute'
		);
		$this->loadModel('VendorManager.Attribute');
		$attributes = $this->Attribute->find('all', ['conditions' => ['service_type_id' => $id]]);
		if($id!=null){
			$this->request->data = $this->ServiceType->read(null,$id);
		}else{
			$this->request->data = array();
		}
		$this->set('attributes', $attributes);
		$this->set('url',Controller::referer());
	}

	function admin_edit_attribute($id=null){
		if ($id == null) {
			return $this->redirect(Controller::referer());
		}
		$this->loadModel('VendorManager.Attribute');
		$this->request->data = $this->Attribute->read(null,$id);
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/service_types'),
			'name'=>'Manage ServiceType'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/service_types/add_attribute/'.$this->request->data['Attribute']['service_type_id']),
			'name'=>'Add Service Attribute'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/service_types/edit_attribute'),
			'name'=>'Edit Attribute'
		);
		$this->set('url',Controller::referer());
	}


	function slugify($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}

	function increment_slug($slug,$service_id){

		// check if slug exist on the table
		$slug_service_id = $this->ServiceType->getServiceTypeIdBySlug($slug);
		// check if the id is the same with the current service

		if($slug_service_id==$service_id || $slug_service_id==null){
			return $slug;

		}
		else{
			// explode the slug
			$slugified = explode('-',$slug);
			// get the last slug
			$lastSlug = $slugified[count($slugified)-1];


			// check if the last slug is a number
			if(is_numeric($lastSlug)){
				// if it is a number increment it
				$increment = $lastSlug+1;
				$slug = str_replace($lastSlug,'', $slug);
				$slug = substr_replace($slug,'',-1);
				$slug.=($increment!=''?'-'.$increment:'');
				// check if there are duplicates
				$another_slug_service_id = $this->ServiceType->getServiceTypeIdBySlug($slug);
				if (!is_numeric($another_slug_service_id)) {
					// if none return the slug
					return $slug;
				}
			}
			else {

				// if not a number it might be the same title with another 1 so add an increment -2
				$slug .= '-2';
				// check if there are duplicates
				$another_slug_service_id = $this->ServiceType->getServiceTypeIdBySlug($slug);
				// check if there is a result
				if (is_numeric($another_slug_service_id)) {

					// if there is a duplicate we need to increment again
					$slugified = explode('-',$slug);
					$lastSlug = $slugified[count($slugified)-1];
					$increment = $lastSlug+1;
					$slug = str_replace($lastSlug, '', $slug);
					$slug .= $increment;
					// check for duplicates
					$another_slug_service_id = $this->ServiceType->getServiceTypeIdBySlug($slug);

					if(!is_numeric($another_slug_service_id)){
						// return slug if there are no duplicates
						$slug = str_replace('--', '-', $slug);
						return $slug;
					}
					else{

						$this->increment_slug($slug,$service_id);
					}
				} else {
					// return the slug if there is no duplicate
					return $slug;
				}
			}

			$this->increment_slug($slug,$service_id);
		}


	}

	function admin_add($id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/service_types'),
			'name'=>'Manage ServiceType'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/mail/add'),
			'name'=>($id==null)?'Add Service':'Update ServiceType'
		);
		if(!empty($this->request->data) && !$this->validation()) {
			// image save and update
			$destination = WWW_ROOT."img/service_type/";
			if($this->request->data['ServiceType']['id']){
				$slide_image = $this->ServiceType->find('first',array('fields'=>array('ServiceType.image'),'conditions'=>array('ServiceType.id'=>$this->request->data['ServiceType']['id'])));
			}
			$image_name='';
			if($this->request->data['ServiceType']['image']['error'] < 1) {
				$image_name =self::_manage_image($this->request->data['ServiceType']['image']);
			}
			if($this->request->data['ServiceType']['id'] && $image_name!=''){
				@unlink(WWW_ROOT."img/service_type/".$slide_image['ServiceType']['image']);
			}else{
				if($this->request->data['ServiceType']['id']){
					$image_name = $slide_image['ServiceType']['image'];
				}
			}
			// if (strlen(trim($this->request->data['ServiceType']['youtube_url'])) == 0) {
			// 	$this->request->data['ServiceType']['youtube_url'] = '#';
			// }
			$this->request->data['ServiceType']['image'] = $image_name;
			$slug = $this->slugify($this->request->data['ServiceType']['page-title']);
			$slug = $this->increment_slug($slug,$this->request->data['ServiceType']['id']);
			$this->request->data['ServiceType']['slug'] = $slug;
			$this->ServiceType->create();
			$this->ServiceType->save($this->request->data,array('validate' => false));
		 	if ($this->request->data['ServiceType']['id']) {
				$this->Session->setFlash(__('Services type has been updated successfully'));
				} 
				else {
					$this->Session->setFlash(__('Service type has been added successfully'));
				}
			$this->redirect(array('controller'=>'service_types','action'=>'index'));
			
		}
		else{
			if($id!=null){
				$this->request->data = $this->ServiceType->read(null,$id);
			}else{
				$this->request->data = array();
			}
		}
		$this->set('url',Controller::referer());
	}
	
	function admin_delete($id=null){
		$this->autoRender = false;
		$data=$this->request->data['ServiceType']['id'];
		$action = $this->request->data['ServiceType']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Publish'){
					$service['ServiceType']['id'] = $value;
					$service['ServiceType']['status']=1;
					$this->ServiceType->create();
					$this->ServiceType->save($service);
					$ans="1";
				}
				if($action=='Unpublish'){
					$service['ServiceType']['id'] = $value;
					$service['ServiceType']['status']=0;
					$this->ServiceType->create();
					$this->ServiceType->save($service);
					$ans="1";
				}
				if($action=='Delete'){
					$service = $this->ServiceType->find('first', array('conditions'=> array('ServiceType.id' => $value),'fields' => array('ServiceType.image')));
						if (!empty($service['ServiceType']['image'])) {
							@unlink(WWW_ROOT."/img/service_type/". $service['ServiceType']['image']);
						}
					$this->ServiceType->delete($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Service type has been '.strtolower($this->data['ServiceType']['action']).'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Service type has been '.strtolower($this->data['ServiceType']['action']).'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Service', true),'default','','error');
		}
		$this->redirect($this->request->data['ServiceType']['redirect']);
	}
	
	function validation(){
		$this->autoRender = false;
		$this->ServiceType->set($this->request->data);
		$result = array();
		if ($this->ServiceType->validates()) {
			$result['error'] = 0;
		}else{
		  $result['error'] = 1;
		}
		$result['errors'] = $this->ServiceType->validationErrors;
		$errors = array();
		foreach($result['errors'] as $field => $data){
			$errors['ServiceType'.Inflector::camelize($field)] = array_pop($data);
		}
		$result['errors'] = $errors;
		if($this->request->is('ajax')) {
			echo json_encode($result);
			return;
		} 
	}
	
	function admin_view($id = null) {
		$this->layout = '';
		$criteria = array();
		$criteria['conditions'] = array('ServiceType.id'=>$id);
		$srevices =  $this->ServiceType->find('first', $criteria);
		$this->set('srevices', $srevices);
    }
    
    private function _manage_image($image = array()) {
        if ($image['error'] > 0) {
            return null;
        } else {
            $existing_image = array();
            if ($image['error'] > 0) {
                return $existing_image['ServiceType']['image'];
            } else {
                $destination = WWW_ROOT . "img/service_type/";
                $ext = explode('.', $image['name']);
                $image_name = time() . '_' . time() . '.' . array_pop($ext);
                move_uploaded_file($image['tmp_name'], $destination . $image_name);
                if (!empty($existing_image)) {
                    @unlink($destination . $existing_image['ServiceType']['image']);
                }
                return $image_name;
            }
        }
    }
    
    function show_on_top(){
		array_push(self::$script_for_layout,'ServiceManager.jquery.jcarousel.min.js','ServiceManager.jcarousel.responsive.js');
		array_push(self::$css_for_layout,'ServiceManager.jcarousel.responsive.css');
		// cache check for slider
		$slide_booking_details = Cache::read('cake_slide_booking_details');
		if(empty($slide_booking_details)){
			$slide_booking_details = $this->ServiceType->find('all',array('fields'=>array('ServiceType.image','ServiceType.id','ServiceType.name','ServiceType.title','ServiceType.short_description'),'conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder'=>'ASC','ServiceType.id'=>'DESC')));
			Cache::write('cake_slide_booking_details',$slide_booking_details);
		 }
		 $this->set('slide_booking_details',$slide_booking_details);
	}
    
    private function _load_serviceType_image($image = null,$width = null ,$height = null) {
        if (!is_null($image) && $image!='' && file_exists(WWW_ROOT . "img/service_type/" . $image)) {
            $thumb_name = $this->ImageResize->getThumbImage(WWW_ROOT . "img/service_type/", WWW_ROOT . "img/tmp/service_type/", $image, $width, $height);
        } else {
            $img_name = 'no-image.png';
            $thumb_name = $this->ImageResize->getThumbImage(WWW_ROOT."img/",WWW_ROOT."img/tmp/",$img_name,80,60);
        }
        return $thumb_name;
    }
    
    function service_type_detail($slug=null){



		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('VendorManager.ServiceReview');
		array_push(self::$script_for_layout,array('jquery.contenthover.min.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js'));
		array_push(self::$css_for_layout,array($this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css'));
		array_push(self::$css_for_layout,'pages.css');
		// searching list
		if ($slug) {
			if(is_numeric($slug)){
				$service_type_id = $slug;
			}
			else{
				$service_type_id = $this->ServiceType->getServiceTypeIdBySlug($slug);
			}
		}
		else{
			throw new NotFoundException('Could not find service type id or slug');
		}
		$service_name='';
		$conditions=array();
		$vendor_list=array();
		if ($service_type_id != null && $service_type_id != 'service_type') {
			$conditions['Service.service_type_id ='] = $service_type_id;
		}
		// get service type details
		$service_type_details=$this->ServiceType->getServiceTypeDetailsById($service_type_id);
		$conditions[]=array('AND'=>array('Vendor.active'=>1,'Service.status'=>1),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0));
		$this->paginate = array();
		$subQuery = "(SELECT AVG(ifnull((`ServiceReview`.`rating`), 0)) FROM service_reviews AS `ServiceReview` WHERE `ServiceReview`.`service_id` = `Service`.`id` and `ServiceReview`.`status` = 1 GROUP BY `ServiceReview`.`service_id`) AS \"rating\" ";
		$this->paginate['fields'] = array('Service.id','Service.slug','Service.service_title','Service.service_price','Service.description',$subQuery);
		$this->paginate['joins'] = array(
						array( 
							'table' => 'vendors',
							'alias' => 'Vendor',
							'type' => 'inner',
							'conditions' => array('Vendor.id = Service.vendor_id')
						),
						array(
							'table' => 'cities',
							'alias' => 'City',
							'type' => 'LEFT',
							'conditions' => array('City.id =Service.location_id')
						),
						array(
							'table' => 'vendor_service_availabilities',
							'alias' => 'VendorServiceAvailability',
							'type' => 'LEFT',
							'conditions' => array('VendorServiceAvailability.service_id =Service.id')
						),
					);
		$this->paginate['conditions'][] = $conditions;
		$this->paginate['limit'] =Configure::read('Activiy.Limit');
		$this->paginate['group'] = array('Service.id');
		$this->paginate['order'] = array('Service.id'=>'DESC');
		// order by rating
		$this->paginate['order'] = "rating DESC";
		$activity_service_list = $this->paginate('Service');
		$new_activity_service_list =array();
		foreach($activity_service_list as $key=>$service_list) {
			$service_list['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($service_list['Service']['id']);
			$service_list['rating']= (round($service_list[0]['rating']));
			$service_list['slug'] = $service_list['Service']['slug'];
			$new_activity_service_list[$key]=$service_list;
		}
		//all service type listing.
		$service_type_list=Cache::read('cake_service_list');
		if(empty($service_type_list)){
			$this->loadModel('ServiceManager.ServiceType');
			$service_type_list = $this->ServiceType->find('list',array('fields'=>array('ServiceType.id','ServiceType.name'),'conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder ASC')));
			Cache::write('cake_service_list',$service_type_list);
		}
		// set css and script
		$this->breadcrumbs[] = array(
                'url'=>Router::url('/'),
                'name'=>'Home'
            );
        $this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'service_type_detail',$service_type_id)),
			'name'=>$service_type_details['ServiceType']['name']
		);
		//set variable
		$this->set('service_type_details',$service_type_details); 
		$this->set('service_type_id',$service_type_id);
		$this->set('activity_service_list',$new_activity_service_list); 
		if($this->request->is('ajax')){
                $this->layout = '';
                $this->Render('ajax_service_type_detail');
        }
		$this->title_for_layout = $service_type_details['ServiceType']['name']." | ".$this->title_for_layout;
		$this->metakeyword = $service_type_details['ServiceType']['description'];
		$this->metadescription = $service_type_details['ServiceType']['description'];
	}

	public function admin_set_header($id = null) {
		if ($id == null) {
			$this->Session->setFlash(__('No service type id found!'));
			return $this->redirect(Controller::referer());
		}
		$data = $this->request->data;
		$this->ServiceType->id = $id;
		$this->ServiceType->save($data,array('validate' => false));
		$this->Session->setFlash(__('Service type header has been successfully updated'));
    	return $this->redirect(Controller::referer());
    }
}
?>
