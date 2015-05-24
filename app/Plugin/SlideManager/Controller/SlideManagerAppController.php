<?php
Class SlideManagerAppController extends AppController{
	public function beforeFilter(){
		parent::beforeFilter();
		Configure::load('SlideManager.config');	
	}
}
?>
