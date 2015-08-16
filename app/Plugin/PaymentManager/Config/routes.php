<?php
 Router::connect('/payment/processing.php', array('plugin'=>'payment_manager','controller' => 'payments', 'action' => 'index'));
 Router::connect('/payment/payment_summary/*', array('plugin'=>'payment_manager','controller' => 'payments', 'action' => 'payment_summary'));
 Router::connect('/payment/cancelled_url/*', array('plugin'=>'payment_manager','controller' => 'payments', 'action' => 'cancelled_url'));
 Router::connect('/payment/failled_url/*', array('plugin'=>'payment_manager','controller' => 'payments', 'action' => 'failled_url'));
 Router::connect('/payment/failled_url/*', array('plugin'=>'payment_manager','controller' => 'payments', 'action' => 'failled_url'));

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
//require CAKE . 'Config' . DS . 'routes.php';
