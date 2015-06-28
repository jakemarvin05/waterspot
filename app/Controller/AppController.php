<?php
//ob_start('ob_gzhandler');
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $helpers = array('Form','Html','ContentManager.Menu','LoginMenu','Format');
    public $title_for_layout = ""; /*use as a html title tag content for admin and front side*/
    public $breadcrumbs = array(); /*use as a breadcrumbs for admin and front side*/
    public $setting = array();
    public static $script_for_layout = array();
    public static $css_for_layout = array();
    public $metakeyword = "";
    public $metadescription = "";
    public static $scriptBlocks = array();
    public static $cssBlocks = array();
    public $heading = array();
    public $header_modules = array();
    public $current_page_id = null;
    public $components = array(
        'Session','DebugKit.Toolbar',
        'Auth' => array(
            'loginRedirect' => '/admin/home',
            'logoutRedirect' => '/admin/index',
            'authError' => 'Did you really think you are allowed to see that?'
        ),
    );
	public function beforeFilter() {
		Configure::load('config');	
		$this->loadModel('SubadminManager.User');
		$this->disableCache();
		$this->setPermissionAuth(Configure::read('Routing.auth_access'));
        $this->loadSettings();
        $path = explode('_',$this->params['action']);
        $prefixs = Configure::read('Routing.request_prefix');
        if(!$this->params['admin'] && !in_array($path[0],$prefixs)){
			
		}else{
			$this->loadAdminSettings();
		}
        self::setTheme();
        if(empty($this->params->requested)){
			self::load_permission();
		}
    }
    public function beforeRender(){
        $this->set('title_for_layout',$this->title_for_layout);
        $this->set('breadcrumbs',$this->breadcrumbs);
        $this->set('setting',$this->setting);
        $this->set('current_page_id',$this->current_page_id);
        $this->set('script_for_layout',self::$script_for_layout);
        $this->set('css_for_layout',self::$css_for_layout);
        $this->set('scriptBlocks',self::$scriptBlocks);
        $this->set('cssBlocks',self::$cssBlocks);
        $this->set('metakeyword',$this->metakeyword);
        $this->set('metadescription',$this->metadescription);
        $this->set('header_modules',$this->header_modules);
	}
    protected function loadFrontModule(){
	}
    protected function setPermissionAuth($prefixs){
        $this->Auth->deny('*');
        $path = explode('_',$this->params['action']);
        if(!in_array($path[0],$prefixs)){
            $this->Auth->allow($this->params['action']);
        }
    }
    private function load_permission() {
		$id = $this->Auth->user('id');
		if($id)
		{
			$this->loadModel('SubadminManager.User');
			$user = $this->User->read(null,$id);
			$module =  ucfirst($this->params['controller'].'Controller');
			$user_modules = json_decode($user['User']['permission'],true);
			if((!isset($user_modules[$module]) || $user_modules[$module] == '' || $user_modules[$module] == 0) || $user['User']['role']=='admin'  ){
				if(($this->params['action']!='home' && $this->params['action']!='admin_changepassword' && $this->params['action']!='adminprofile' ) && $this->params['action']!='logout' && $user['User']['role']!='admin'){
					$this->Session->setFlash('You have not permission to access this location','default','msg','error');
					$this->redirect('/admin/home');
				}
			}else{
		       $this->__setPermission($user_modules[$module]);  
			}
	    $this->set('ADMIN_PERMISSIONS',$user_modules);
	    $this->set('ADMIN_USERS',$user);
	    
		}
    }
    
    protected function loadSettings(){
		$result = Cache::read('cake_settings');
		if(empty($result)){
			$this->loadModel('Setting');
			$results = $this->Setting->find('all',array('fields'=>array('Setting.key','Setting.values','Setting.module'),'conditions'=>array('Setting.module'=>array('site','social','image','paypal'))));
			$settings = array();
			foreach($results as $result){
				$settings[$result['Setting']['module']][$result['Setting']['key']] = $result['Setting']['values'];
			}
			
			$settings['site']['site_url'] = $settings['site']['site_protocol'].$settings['site']['site_url'];
			$settings['site']['logo'] = $settings['site']['site_url'].$this->webroot.'img/site/'.$settings['site']['site_logo'];
			$settings['site']['jquery_plugin_url'] = $settings['site']['site_url'].$this->webroot.Configure::read('Folder.jquery_plugin').DS;
			Cache::write('cake_settings', $settings);
			$result = $settings;
		}
		// condition for request action 
		if(empty($this->params->requested)){
			$this->loadWithoutRequest();
        }
		// cart current activities
		$this->setting = $result;
		$this->title_for_layout = $this->setting['site']['site_title'];
		
	}
	private function loadWithoutRequest(){
		$this->loadModel('ContentManager.Page');
		$this->loadModel('Cart');
		$contact_data=array();
		$result_contact = Cache::read('cake_contact');
		if(empty($result_contact)){
			$result_contact=$this->Page->find('first',array('conditions'=>array('Page.id'=>6),'fields'=>array('Page.page_shortdescription')));
			Cache::write('cake_contact', $result_contact);
		}
		$totalcart=$this->Cart->find('count',array('conditions'=>array('Cart.session_id'=>$this->Session->id(),'Cart.status'=>1)));
		$this->set('totalcart',$totalcart);
		$this->set('contact_data',$result_contact);
	}
	
	protected function loadAdminSettings(){
		Configure::load('Custom/admin_config');
	}
	private function setTheme(){
		if($this->params['admin'] || $this->params['controller']=="admin"){
			$this->layout="admin";
			$this->title_for_layout = $this->setting['site']['site_name'];
		}
		$this->metakeyword = $this->setting['site']['site_metakeyword'];
		$this->metadescription = $this->setting['site']['site_metadescription'];
	}
	protected function RandomString(){
		$characters = '$&@!0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 15; $i++) {
			$arr1 = str_split($characters);
			$randstring .= $arr1[rand(0, $i)];
		}
		return $randstring;
	}
	protected function curPageURL() {
		$pageURL = 'http';
		if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return urlencode($pageURL);
	}
}
