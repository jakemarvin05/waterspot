<?php

class MenuHelper extends AppHelper {

    var $helpers = Array('Html');
    
    
    function top_menu($parent_id = 0,$current_id = null){
		
		$pages = Cache::read('cake_page_top_menu');
		if(empty($pages)){
			$pages = self::get_menu($parent_id,$current_id);
			Cache::write('cake_page_top_menu',$pages);
			//return $data;
		}
		
		$data = "";
		$class_name = "";
		if($parent_id==0){
			$class_name = "nav";
		}
		
		if(!empty($pages)){
			$data = "<ul class='$class_name'>";
			foreach($pages[$parent_id] as $page){
				$active='';
				//echo "<pre>";pr($page);
				if($page['Page']['id']==$current_id){
					$active = "onactive";
				}
				
				if($page['Page']['id']==1){
					$data .= "<li>".$this->Html->link($page['Page']['name'],'/',array('class'=>$active));
				}else if($page['Page']['id']==4){
					$data .= "<li>".$this->Html->link($page['Page']['name'],array('plugin'=>false,'controller'=>'activity','action'=>'activities'),array('class'=>$active));
				}
				else if($page['Page']['id']==5){
					$data .= "<li>".$this->Html->link($page['Page']['name'],array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'vendor_list'),array('class'=>$active));
				}else{
					$data .= "<li>".$this->Html->link($page['Page']['name'],array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',$page['Page']['id']),array('class'=>$active));
				
				}
				//$data .= $this->top_menu($page['Page']['id'],$current_id);
				$data .="</li>";
				
			}
			$data .= "</ul>";
		}
		return $data;
	}
	
	private function get_menu($parent_id = 0,$current_id = null){
		$Page =& ClassRegistry::init("Page");  
		$pages = $Page->find('all',array('conditions'=>array('Page.status'=>1,'Page.parent_id'=>$parent_id,'Page.show_top_menu'=>1),'fields'=>array('Page.id','Page.name','Page.page_seo_keyword','Page.parent_id')));
		$menus = array();
		if(!empty($pages)){
			foreach($pages as $page){
				$page['Page']['childern'] = self::get_menu($page['Page']['id']);
				$menus[$parent_id][] = $page; 
			}
		}
		
	    return $menus;
	}
	
	function footer_menu(){
		$data = Cache::read('cake_page_footer_menu');
		if($data!=""){
			return $data;
		}
		
		$data = "";
		
		$Page =& ClassRegistry::init("Page");  
		$pages = $Page->find('all',array('conditions'=>array('Page.status'=>1,'Page.show_footer_menu'=>1),'fields'=>array('Page.id','Page.name','Page.page_seo_keyword')));
		$data = "<ul>";
		foreach($pages as $page){
			
			if($page['Page']['id']==5){
					$data .= "<li>".$this->Html->link($page['Page']['name'],array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'vendor_list'));
					continue;
			}
			if($page['Page']['id']==4){
					$data .= "<li>".$this->Html->link($page['Page']['name'],array('plugin'=>false,'controller'=>'activity','action'=>'activities'));
					continue;
				}
			if($page['Page']['id']==1){
					$data .= "<li>".$this->Html->link($page['Page']['name'],'/');
				}else{
					$data .= "<li>".$this->Html->link($page['Page']['name'],array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',$page['Page']['id']));
				
				}
		
			
			$data .="</li>";
		}
		$data .= "</ul>";
		Cache::write('cake_page_footer_menu',$data);
		return $data;
	}
    
	function privacy_policy(){
		
		$data = "";
		$pages=Cache::read('cake_page_privacy_policy');
		if(empty($pages)){
			$Page =& ClassRegistry::init("Page");  
			$pages = $Page->find('all',array('conditions'=>array('Page.status'=>1,'Page.id'=>array(9,10,11)),'fields'=>array('Page.id','Page.name','Page.page_seo_keyword')));
			Cache::write('cake_page_privacy_policy',$pages);
		}	
		if(!empty($pages)):
			foreach($pages as $page){
				
				if($page['Page']['id']==10){
					$data .= ' | '.$this->Html->link($page['Page']['name'],array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',$page['Page']['id'])).' | ';
				}else{
					$data .= $this->Html->link($page['Page']['name'],array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',$page['Page']['id']));
				}
					
			}
			endif;
		return $data;
	}
    
    function listing($categories = array(),$options =array()){
		
		if($this->params['action']=='view'){
			$class='span3 width_01';
			$sub_class='dropdown-menu_01';
		}
		else {
			$class='span3 width_01';
			$sub_class='dropdown-menu_02';
		}
		
		$data = " <div class= '$class'>";
		if(!empty($categories)){
			$data .= "<ul class=\"dropdown-menu product-list\" role=\"menu\" aria-labelledby=\"dropdownMenu\">";
			$data .= "<li class=\"heading-list\">Categories</li>";
			foreach($categories as $key=> $category){
				if($key==@$options['current']){
					$active_class='active1';
				}else{
					$active_class='';
				}
				
				$data .= " <li class=\"acitve-list\">";
				$data .= $this->Html->link('<i class=" icon-chevron-right"></i>'.$category['name'],array('controller'=>'products','action'=>'sub_category','id'=>$key,'slug'=>str_replace('&amp;','and',$category['name'])),array('tabindex'=>'-1','escape'=>false,'class'=>$active_class));
				$data .= self::load_sub_category($category['sub_menu'],$options);
				$data .= " </li>";
			}
			$data.=" </ul>";
		}
		$data .= "</div>";
		return $data;
	}
	
	private function load_sub_category($sub_category = array(),$options = array()){
		$data = "";
		if(!empty($sub_category)){
			$data .= "<ul class=\"submenu submenu_$options[level]\" style=\"display: block;list-style: none outside none; margin-left: 0;\">";
			foreach($sub_category as $key => $category){
				$data .="<li>";
				if($key==@$options['current']){
					$active_class='active_sub';
				}else{
					$active_class='';
				}
				
				
				$data .= $this->Html->link($category['name'],array('controller'=>'products','action'=>'sub_category','id'=>$key,'slug'=>str_replace('&amp;','and',$category['name'])),array('tabindex'=>'-1','escape'=>false,'class'=>$active_class));
				$options['level'] = $options['level']+1; 
				$data .= self::load_sub_category($category['sub_menu'],$options);
				$data.="</li>";
				
			}
			$data .="</ul>";
		}
		
		return $data;
	}


}
?>
