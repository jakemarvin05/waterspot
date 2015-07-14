<?php
Class PagesController extends ContentManagerAppController{
	public $uses = array('ContentManager.Page');
	public $components=array('Email');
	public $paginate = array();
	public $id = null;
	
	function admin_index($parent_id = 0 , $search=null){
		$this->paginate = array();
		$parent_detail = array();
		$condition = null;
		$condition['Page.parent_id']= (int)$parent_id;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'index',$parent_id,$this->request->data['search']));
		}
		$this->paginate['order']=array('Page.page_order'=>'ASC','Page.id'=>'DESC');		
		if($search!=null){
			$search = urldecode($search);
			$condition['Page.name like'] = $search.'%';
		}
		$pages=$this->paginate("Page", $condition);	
		if($parent_id!=0){
			$parent_detail = $this->Page->read(null,$parent_id);
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/content_manager/pages'),
			'name'=>'Manage Content'
		);
		if(!empty($parent_detail)){
			$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/content_manager/pages/index/'.$parent_detail['Page']['id']),
			'name'=>$parent_detail['Page']['name']
			);
		}
		$this->set('parent_id',$parent_id);
		$this->set('parent_detail',$parent_detail);
		$this->set('pages',$pages);
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
			$page= array();
			$page['Page']['id'] = $id;
			$page['Page']['page_order'] = $order;
			$this->Page->create();
			$this->Page->save($page);
		}
	}
       
	function admin_add($parent_id=0,$id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/content_manager/pages'),
				'name'=>'Manage Content'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/content_manager/pages/add/'.$parent_id),
				'name'=>($id==null)?'Add Content':'Update Content'
		);
		if(!empty($this->request->data) && !$this->validation()){
			if(!$id){
			$this->request->data['Page']['created_at']=date('Y-m-d H:i:s');
			$this->request->data['Page']['status'] = 1;
			}else{
				$this->request->data['Page']['updated_at']=date('Y-m-d H:i:s');
			}
			$this->Page->create();
			$this->Page->save($this->request->data);
			if ($this->request->data['Page']['id']) {
				$this->Session->setFlash(__('Content has been updated successfully'));
				} else {
					$this->Session->setFlash(__('Content has been added successfully'));
				}
			$this->redirect($this->request->data['Page']['redirect']);
		}
		else{
			
			if($id!=null){
				$this->request->data = $this->Page->read(null,$id);
			}else{
				$this->request->data = array();
			}
		}
		$redirect_url=(Controller::referer()=="/")? Router::url('/admin/content_manager/pages') :Controller::referer();
		$this->set('parent_id',$parent_id);
		$this->set('url',$redirect_url);
	}
	
	function request_top_menu($parent_id = 0){
		$this->autoRender = false;
		$pages = $this->Page->find('all',array('conditions'=>array('Page.status'=>1,'Page.parent_id'=>$parent_id,'Page.show_top_menu'=>1),'fields'=>array('Page.id','Page.name','Page.page_seo_keyword')));
		$data = array();
		foreach($pages as $page){
			$data[] = array(
				'id'=>$page['Page']['id'],
				'title'=>$page['Page']['name'],
				'sub_menu'=>$this->request_top_menu($page['Page']['id'])
				);
		}
		return $data;
	}
	
	function admin_delete($id=null){
            $this->autoRender = false;
		    $data=$this->request->data['Page']['id'];
            $action = $this->request->data['Page']['action'];
            $ans="0";
            foreach($data as $value){
                if($value!='0'){
                    if($action=='Publish'){
                        $page['Page']['id'] = $value;
                        $page['Page']['status']=1;
                        $this->Page->create();
                        $this->Page->save($page);
                        $ans="1";
                    }
                    if($action=='Unpublish'){
                        $page['Page']['id'] = $value;
                        $page['Page']['status']=0;
                        $this->Page->create();
                        $this->Page->save($page);
                        $ans="1";
                    }
                    if($action=='Delete'){
                        $this->Page->delete($value);
                        $ans="2";
                    }
                }
            }
		if($ans=="1"){
			$this->Session->setFlash(__('Page has been '.$this->data['Page']['action'].'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Page has been '.$this->data['Page']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Page', true),'default','','error');
		}
		$this->redirect($this->request->data['Page']['redirect']);
                 
	}
	// this is not working
	/*function home(){
		//$this->autoRender = false;
		$criteria = array();
		$criteria['fields'] = array('Page.id','Page.name','Page.page_shortdescription');
		$criteria['conditions'] = array('Page.parent_id'=>7,'Page.status'=>1);
		$criteria['order'] = array('Page.page_order'=>'ASC');
		
		$pages = $this->Page->find('all',$criteria);
		
		
		
		array_push($this->script_for_layout,'slider/jquery.js','slider/jquery.placeholder.min.js','slider/plugins.min.js','slider/scripts.min.js');
		array_push($this->css_for_layout,'slider.css');
		
		$this->scriptBlocks[]="var tpj=jQuery;
								tpj.noConflict();
								var revapi7;
								tpj(document).ready(function() {
								if (tpj.fn.cssOriginal != undefined)
									tpj.fn.css = tpj.fn.cssOriginal;
								if(tpj(\"#rev_slider_7_1\").revolution == undefined)
									revslider_showDoubleJqueryError(\"#rev_slider_7_1\");
								else
								  revapi7 = tpj(\"#rev_slider_7_1\").show().revolution(	{
									delay:6000, 
									startwidth:1000,
									startheight:550,
									hideThumbs:200,
									
									thumbWidth:100,
									thumbHeight:50,
									thumbAmount:4,
									
									navigationType:\"bullet\",
									navigationArrows:\"verticalcentered\",
									navigationStyle:\"round\",
									
									touchenabled:\"on\",
									onHoverStop:\"off\",
									
									navOffsetHorizontal:0,
									navOffsetVertical:10,
									
									shadow:2,
									fullWidth:\"on\",
								  
									stopLoop:\"off\",
									stopAfterLoops:-1,
									stopAtSlide:-1,
								  
									shuffle:\"off\",
									
									hideSliderAtLimit:0,
									hideCaptionAtLimit:0,
									hideAllCaptionAtLilmit:0
								  });
								  tpj.restyleRevo(revapi7, tpj(\"#rev_slider_7_1\").parent().parent());
								});";
		$this->title_for_layout = $this->site_setting['site_title'];
		$this->metakeyword = $this->site_setting['site_metakeyword'];
		$this->metadescription = $this->site_setting['site_metadescription'];
		
		$this->set('pages',$pages);
		$this->set('id',null);
		$this->render('service');
		//echo '<pre>';print_r($this->site_setting);die;
		
		//$this->loadModel('ServiceManager.ServiceType');
		//$service_type_list = $this->ServiceType->find('list',array('conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder ASC')));
		//$this->set('service_type_list',$service_type_list);
		//echo"<pre>";print_r($service_type_list);die;
		
		//$this->loadModel('Slide'); 
		//$slides=$this->Slide->find('all',array('order'=>array('Slide.reorder'=>'asc'),'conditions'=>array('Slide.status'=>1)));
		//$this->set('slides',$slides);
			 
	}*/
	
	function request_sub_menus($parent_id = null){
		$criteria = array();
		$criteria['fields'] = array('Page.id','Page.name');
		$criteria['conditions'] = array('Page.parent_id'=>7,'Page.status'=>1);
		$criteria['order'] = array('Page.page_order'=>'ASC');
		$pages = $this->Page->find('list',$criteria);
		return $pages;
	}
	 
	function contactus(){
		if(!empty($this->request->data) && !$this->validation()){
		$this->loadModel('MailManager.Mail');
	
		// mail to admin
		$mail=$this->Mail->read(null,1);
		$body=str_replace('{NAME}',ucfirst($this->request->data['Page']['name']),$mail['Mail']['mail_body']);
        $body=str_replace('{EMAIL}',$this->request->data['Page']['email'],$body);
		$body=str_replace('{PHONE}',$this->request->data['Page']['phone'],$body);
		$body=str_replace('{MESSAGE}',$this->request->data['Page']['message'],$body);
		$email = new CakeEmail();
		$email->to($this->setting['site']['site_contact_email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->request->data['Page']['email'],$this->request->data['Page']['name']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
		
		/******Mail to User******/ 
		$mail=$this->Mail->read(null,2);
		$body=str_replace('{NAME}',ucfirst($this->request->data['Page']['name']),$mail['Mail']['mail_body']);      
		$email = new CakeEmail();
		$email->to($this->request->data['Page']['email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',12));
		}else{
			
			$this->Session->setFlash(__('Please fill all required fields.'),'default','','error');
			$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',6));
		}
	}
	
	public function view($page_id=null){ 
            array_push(self::$css_for_layout,'pages.css');
		$page=$this->Page->read(null,$page_id);
		$this->current_page_id = $page_id;
			
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/content_manager/pages/'.$page['Page']['id']),
                    'name'=>$page['Page']['name']
		    );
		$this->set('page',$page);
		$this->set('page_id',$page_id);
		// set metakeyword and description 
		if(!empty($page['Page']['page_title'])){
			$this->title_for_layout .= ": ". $page['Page']['page_title'];
		}
		
		if(!empty($page['Page']['page_metakeyword'])){
			$this->metakeyword = $page['Page']['page_metakeyword'];
		}
		
		if(!empty($page['Page']['page_metadescription'])){
			$this->metadescription = $page['Page']['page_metadescription'];
		}
	} 
	
	function validation(){
		
		if($this->request->data['Page']['form_name']=='ContactForm') {
			$this->Page->setValidation('ContactForm');
		}
		$this->Page->set($this->request->data);  
		$result = array();
		if ($this->Page->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->Page->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['Page'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return $result['error'];
		//return (int)($result['error'])?0:1;
	}
	
	function admin_view($id = null) {
		$this->layout = '';
     	$criteria = array();
        $criteria['conditions'] = array('Page.id'=>$id);
        $parent_page =  $this->Page->find('first', $criteria);
        $this->set('page', $parent_page);
    }
 }
?>
