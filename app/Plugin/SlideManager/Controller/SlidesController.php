<?php
ob_start();
Class SlidesController extends SlideManagerAppController{
	public $uses = array('SlideManager.Slide');
	public $helpers = array('Form','ImageResize');
	public $paginate = array();
	public $id = null;

	function admin_index($search=null){
		$this->paginate = array();
		$condition = null;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'slide_manager','controller'=>'slides','action'=>'index' ,$this->request->data['search']));
		}
		$this->paginate['order']=array('Slide.reorder'=>'ASC','Slide.id'=>'DESC');		
		if($search!=null){
			$search = urldecode($search);
			$condition['Slide.name like'] = $search.'%';
		}
		$slides=$this->paginate("Slide", $condition);	
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/slide_manager/slides'),
			'name'=>'Manage Slide'
		);
		$this->set('slides', $slides);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}
	
	function admin_add($id=null){
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/slide_manager/slides'),
			'name'=>'Manage Slide'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/slide_manager/slides/add'),
			'name'=>($id==null)?'Slide Content':'Update Slide'
		);
		if(!empty($this->request->data)){
			$destination = WWW_ROOT."img/slide/";
			if($this->request->data['Slide']['id']){
				$slide_image = $this->Slide->find('first',array('fields'=>array('Slide.image'),'conditions'=>array('Slide.id'=>$this->request->data['Slide']['id'])));
			}
			$image_name='';
		if($this->request->data['Slide']['image']['error'] < 1){
			$image_name =self::_manage_image($this->request->data['Slide']['image']);
		}
		if($this->request->data['Slide']['id'] && $image_name!=''){
			unlink(WWW_ROOT."img/slide/".$slide_image['Slide']['image']);
		}else{
			if($this->request->data['Slide']['id']){
			$image_name = $slide_image['Slide']['image'];
			}
		}
		$this->request->data['Slide']['image'] = $image_name;			
		if(!$id){
			$this->request->data['Slide']['created_at']=date('Y-m-d H:i:s');
			$this->request->data['Slide']['status'] = 1;
		}else{
			$this->request->data['Slide']['updated_at']=date('Y-m-d H:i:s');
		}
		$this->Slide->create();
		$this->Slide->save($this->request->data,array('validate' => false));
		if ($this->request->data['Slide']['id']) {
				$this->Session->setFlash(__('Slide has been updated successfully'));
			} 
			else {
				$this->Session->setFlash(__('Slide has been added successfully'));
			}
			$this->redirect(array('action'=>'admin_index')); 
		}
		else{
			if($id!=null){
				$this->request->data = $this->Slide->read(null,$id);
			}else{
				$this->request->data = array();
			}
		} 
		$this->set('url',Controller::referer());
	}

	function admin_delete($id=null){
		$this->autoRender = false;
		$data=$this->request->data['Slide']['id'];
		$action = $this->request->data['Slide']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Publish'){
					$slide['Slide']['id'] = $value;
					$slide['Slide']['status']=1;
					$this->Slide->create();
					$this->Slide->save($slide);
					$ans="1";
				}
				if($action=='Unpublish'){
					$slide['Slide']['id'] = $value;
					$slide['Slide']['status']=0;
					$this->Slide->create();
					$this->Slide->save($slide);
					$ans="1";
				}
				if($action=='Delete'){
					$slide = $this->Slide->find('first', array('conditions'=> array('Slide.id' => $value),'fields' => array('Slide.image')));
					 if (!empty($slide['Slide']['image'])) {
						   @unlink(WWW_ROOT."img/slide/". $slide['Slide']['image']);
						}
					$this->Slide->delete($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Slide has been '.$this->data['Slide']['action'].'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Slide has been '.$this->data['Slide']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Slide', true),'default','','error');
		}
		$this->redirect($this->request->data['Slide']['redirect']);
	}
	
	function show(){
		$this->loadModel('ServiceManager.ServiceType');
		array_push(self::$script_for_layout,'SlideManager.jquery.flexslider.js');
		array_push(self::$css_for_layout,'SlideManager.flexslider.css');
		// cache check for slider
		$result_slider = Cache::read('cake_slider');
		if(empty($result_slider)){
			$result_slider=$this->Slide->find('all',array('conditions'=>array('Slide.status'=>'1'),'order'=>'Slide.reorder asc'));
			Cache::write('cake_slider', $result_slider);
		}
		$this->set('slides',$result_slider);
	 	//return $slide;
	}
	
	function validation(){
		$this->autoRender = false;
		$this->Slide->set($this->request->data);
		$result = array();
		if ($this->Slide->validates()) {
		   $result['error'] = 0;
		}else{
		  $result['error'] = 1;
		}
		$result['errors'] = $this->Slide->validationErrors;
		$errors = array();
		foreach($result['errors'] as $field => $data){
		   $errors['Slide'.Inflector::camelize($field)] = array_pop($data);
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
        $criteria['conditions'] = array('Slide.id'=>$id);
        $parent_slide =  $this->Slide->find('first', $criteria);
        $this->set('slide', $parent_slide);
    }
    
	private function _manage_image($image = array()) {
        if ($image['error'] > 0) {
            return null;
        } else {
            $existing_image = array();
            if ($image['error'] > 0) {
                return $existing_image['Slider']['image'];
            } else {
               // $destination = WWW_ROOT . "img/slide/";
                $destination = Configure::read('Slide.SourcePath');
                $ext = explode('.', $image['name']);
                $image_name = time() . '_' . time() . '.' . array_pop($ext);
                move_uploaded_file($image['tmp_name'], $destination . $image_name);
                if (!empty($existing_image)) {
                    unlink($destination . $existing_image['Slider']['image']);
                }
                return $image_name;
            }
        }
    }
    
    private function _load_slider_image($image = null,$width = null ,$height = null) {
        if (!is_null($image) && $image!='' && file_exists(WWW_ROOT . "img/slide/" . $image)) {
            $thumb_name = $this->ImageResize->getThumbImage(WWW_ROOT . "img/slide/", WWW_ROOT . "img/tmp/slide/", $image, $width, $height);
        } else {
            $img_name = 'no-image.png';
            $thumb_name = $this->ImageResize->getThumbImage(WWW_ROOT."img/",WWW_ROOT."img/tmp/",$img_name,80,60);
        }
        return $thumb_name;
    }
	
	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$slide= array();
			$slide['Slide']['id'] = $id;
			$slide['Slide']['reorder'] = $order;
			$this->Slide->create();
			$this->Slide->save($slide);
		}
	}
}
?>
