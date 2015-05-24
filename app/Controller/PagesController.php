<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';
	public $components = array('Email');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
        public function beforeFilter() {
           
            parent::beforeFilter();
        }
        
        public function admin_index(){
            $this->autoRender = false;
           
        }
        
       
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
	
	public function login(){
		$this->autoRender = false;
		
		if(!empty($this->request->data)){
			print_r($this->request->data);
		}
		
	}
	
	public function home(){
		$this->loadModel('MemberManager.Member'); 
		$member_reviews=array();
		$page=$this->Page->read(null,'1'); //fetch home page content
		
		$about_us=$this->Page->find('first',array('conditions'=>array('Page.id'=>3),'fields'=>array('Page.id','Page.url_key','Page.name','Page.page_shortdescription'))); //fetch about us content
		
		$criteria = array();
		$criteria['fields']= array('Member.first_name','Member.last_name','ServiceReviews.*');
		$criteria['joins'] = array(
			array(
				'table' => 'service_reviews',
				'alias' => 'ServiceReviews',
				'type' => 'INNER',
				'conditions' => array('ServiceReviews.member_id=Member.id')
			)
        );
		$criteria['conditions'] =array('Member.active'=>1,'ServiceReviews.status'=>1);
		$criteria['order'] =array('ServiceReviews.id'=>'DESC');
		$criteria['limit'] =6;
		$service_reviews=$this->Member->find('all', $criteria); //fetch member reviews 
		
		
		$this->current_page_id = 1;
		self::$scriptBlocks[] = "$(window).load(function(){
								$('.flexslider').flexslider({
								animation: 'fade',
									start: function(slider){
									$('body').removeClass('loading');
									}
								});
							});";	
		
		
		
		
		$this->header_modules[] = $this->requestAction(array('plugin'=>'slide_manager','controller'=>'slides','action'=>'show'),array('return')); //get slide show html
		$this->header_modules[] = $this->requestAction(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'show_on_top'),array('return'));
		//get all services slideshow html
		$service_type_list=Cache::read('cake_service_list');
		if(empty($service_type_list)){
			$this->loadModel('ServiceManager.ServiceType');
			$service_type_list = $this->ServiceType->find('list',array('fields'=>array('ServiceType.id','ServiceType.name'),'conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder ASC')));
			Cache::write('cake_service_list',$service_type_list);
		}
		$this->set('service_type_list',$service_type_list);
		$this->title_for_layout = $page['Page']['page_title'];
		$this->metakeyword = $page['Page']['page_metakeyword'];
		$this->metadescription = $page['Page']['page_metadescription'];
		$this->set('page',$page);
		$this->set('about_us',$about_us);
		$this->set('service_reviews',$service_reviews);
	}
	
	public function view($page_id=null){ 
		$page=$this->Page->read(null,$page_id);
		$this->current_page_id = $page_id;
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/home'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/content_manager/pages/'.$page['Page']['id']),
                    'name'=>$page['Page']['name']
		    );
		$this->set('page',$page);
		// set metakeyword and description 
		$this->title_for_layout = $page['Page']['page_title'];
		$this->metakeyword = $page['Page']['page_metakeyword'];
		$this->metadescription = $page['Page']['page_metadescription'];
	} 
	
	function captcha_image(){ 
		//header('Content-Type: image/png'); 
		$this->layout='';
		Configure::write('debug',2);
		$this->autoRender=false;
		App::import('Vendor', 'captcha/captcha');
		$captcha = new captcha();
		$captcha->show_captcha();
	}  
}
?>
