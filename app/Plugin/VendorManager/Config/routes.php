<?php
// vendors controller

Router::connect('/vendors/registration', array('controller' => 'vendors', 'action' => 'registration','plugin'=>'vendor_manager'));
Router::connect('/vendor/log_in', array('controller' => 'vendors', 'action' => 'log_in','plugin'=>'vendor_manager'));
Router::connect('/vendors/login', array('controller' => 'vendors', 'action' => 'login','plugin'=>'vendor_manager'));
Router::connect('/vendor/dashboard', array('controller' => 'vendors', 'action' => 'dashboard','plugin'=>'vendor_manager'));
Router::connect('/vendor/thankyou', array('controller' => 'vendors', 'action' => 'thankyou','plugin'=>'vendor_manager'));
Router::connect('/vendor/add_services', array('controller' => 'vendors', 'action' => 'add_services','plugin'=>'vendor_manager'));
Router::connect('/vendors/booking_listing', array('controller' => 'vendors', 'action' => 'booking_listing','plugin'=>'vendor_manager'));
Router::connect('/vendor/vendor_list/*', array('controller' => 'vendors', 'action' => 'vendor_list','plugin'=>'vendor_manager'));
Router::connect('/vendor/activities/*', array('controller' => 'vendors', 'action' => 'activities','plugin'=>'vendor_manager'));

// accounts controller

Router::connect('/accounts/editProfile', array('controller' => 'accounts', 'action' => 'editProfile','plugin'=>'vendor_manager'));
Router::connect('/accounts/changepassword', array('controller' => 'accounts', 'action' => 'changepassword','plugin'=>'vendor_manager'));
Router::connect('/accounts/resetpassword', array('controller' => 'accounts', 'action' => 'resetpassword','plugin'=>'vendor_manager'));
Router::connect('/accounts/passwordurl/*', array('controller' => 'accounts', 'action' => 'passwordurl','plugin'=>'vendor_manager'));

// bookings controller

Router::connect('/vendor/booking_list/*', array('controller' => 'bookings', 'action' => 'booking_list','plugin'=>'vendor_manager'));
Router::connect('/vendor/booking_request/*', array('controller' => 'bookings', 'action' => 'booking_request','plugin'=>'vendor_manager'));
Router::connect('/vendor/booking_details/*', array('controller' => 'bookings', 'action' => 'booking_details','plugin'=>'vendor_manager'));
Router::connect('/vendor/cancel_booking/*', array('controller' => 'bookings', 'action' => 'cancel_booking','plugin'=>'vendor_manager'));

// vendor_service_availabilities controller

Router::connect('/vendor/service_availability/index/*', array('controller' => 'vendor_service_availabilities', 'action' => 'index','plugin'=>'vendor_manager'));

// services controller

Router::connect('/services/add_services/*', array('controller' => 'services', 'action' => 'add_services','plugin'=>'vendor_manager'));

Router::connect('/services/my-services', array('controller' => 'services', 'action' => 'my_services','plugin'=>'vendor_manager'));

Router::connect('/services/add_service_slots/*', array('controller' => 'services', 'action' => 'add_service_slots','plugin'=>'vendor_manager'));
 
Router::connect('/services/add_slots/*', array('controller' => 'services', 'action' => 'add_slots','plugin'=>'vendor_manager'));

Router::connect('/services/ajax_end_time/*', array('controller' => 'services', 'action' => 'ajax_end_time','plugin'=>'vendor_manager'));

Router::connect('/vendor/messages/*', array('controller' => 'vendors', 'action' => 'messages','plugin'=>'vendor_manager'));

// reviews controller
 Router::connect('/vendor/services/reviews/*', array('controller' => 'service_reviews', 'action' => 'reviews','plugin'=>'vendor_manager'));
 

// payment controller

//Router::connect('/vendor/make-payment/*', array('plugin'=>'vendor_manager','controller' => 'payments', 'action' => 'make_payment'));
/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
//require CAKE . 'Config' . DS . 'routes.php';
