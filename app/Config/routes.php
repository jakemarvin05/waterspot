<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
		Router::connect('/', array('controller'=>'pages','action'=>'home'));
		Router::connect('/admin', array('controller' => 'users', 'action' => 'login','prefix'=>'admin'));
        Router::connect('/admin/index', array('controller' => 'admin', 'action' => 'index'));
        Router::connect('/admin/logout', array('controller' => 'admin', 'action' => 'logout'));
        Router::connect('/activities/*', array('controller' => 'activity', 'action' => 'activities'));

        Router::connect('/activity/details/:slug/*',
            array('controller' => 'activity', 'action' => 'index'),
            array('pass'=>array('slug'))
        );
        Router::connect('/activity/book/:slug/:cart_id',
            array('controller' => 'activity', 'action' => 'index'),
            array('pass' => array('slug','cart_id'))
        );

        Router::connect('/admin/home', array('controller' => 'admin', 'action' => 'home'));
        Router::connect('/admin/settings/adminprofile/*', array('controller' => 'settings', 'action' => 'adminprofile'));
        Router::connect('/admin/resetpassword',array('controller'=>'admin', 'action'=>'resetpassword'));
		Router::connect('/admin/vendor_manager/settings/admin_fees', array('controller' => 'settings', 'action' => 'admin_fees'));
		Router::connect('/admin/settings/admin_paypalsetting/*', array('controller' => 'settings', 'action' => 'admin_paypalsetting'));
		Router::connect('/searches/search/*', array('controller' => 'searches', 'action' => 'search'));
		Router::connect('/admin/passwordurl/*',array('controller'=>'admin', 'action'=>'passwordurl'));
		Router::connect('/service-type-details/*', array('plugin'=>'service_manager','controller' => 'service_types', 'action' => 'service_type_detail'));
        Router::connect('/admin/coupon/*', array('controller' => 'admin', 'action' => 'coupon'));
        Router::connect('/admin/coupon_add', array('controller' => 'admin', 'action' => 'coupon_add'));
        Router::connect('/admin/coupon_close/*', array('controller' => 'admin', 'action' => 'coupon_close'));
        Router::connect('/admin/ajax_check_code/*', array('controller' => 'admin', 'action' => 'ajax_check_code'));
        Router::connect('/admin/ajax_generate_code/*', array('controller' => 'admin', 'action' => 'ajax_generate_code'));

	//$cmsPages = Cache::read('pages_routes');

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
