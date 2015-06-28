<?php
Class MailsController extends MailManagerAppController{
		public $uses = array('MailManager.Mail');
	    public $paginate = array();
        public $id = null;
	
			function admin_index($search=null){
            $this->paginate = array();
            $condition = null;
            $this->paginate['limit']=20;
            if($this->request->is('post')){
				$this->redirect(array('plugin'=>'mail_manager','controller'=>'mails','action'=>'index' ,$this->request->data['search']));
            }
            $this->paginate['order']=array('Mail.ordering'=>'ASC');		
            
            if($search!=null){
                $search = urldecode($search);
                $condition['Mail.mail_title like'] = $search.'%';
            }
            $condition['Mail.status'] = 1;
            $mails=$this->paginate("Mail", $condition);	
            $this->breadcrumbs[] = array(
                'url'=>Router::url('/admin/home'),
                'name'=>'Home'
            );
            $this->breadcrumbs[] = array(
                'url'=>Router::url('/admin/mail_manager/mails'),
                'name'=>'Manage mail'
            );
            $this->set('mails',$mails);
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
                $mail= array();
                $mail['Mail']['id'] = $id;
                $mail['Mail']['ordering'] = $order;
                $this->Mail->create();
                $this->Mail->save($mail,array('validate' => false));
            }
           
        }
       
		 
        function admin_add($id=null){
			
			$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
            $this->breadcrumbs[] = array(
                    'url'=>Router::url('/admin/mail_manager/mails'),
                    'name'=>'Manage Mail'
            );
            $this->breadcrumbs[] = array(
                    'url'=>Router::url('/admin/mail_manager/mail/add'),
                    'name'=>($id==null)?'Add Mail':'Update Mail'
            );
            
            if(!empty($this->request->data)){
				 
                if(!$id){
				$this->request->data['Mail']['created_at']=date('Y-m-d H:i:s');
				}else{
				$this->request->data['Mail']['updated_at']=date('Y-m-d H:i:s');
				}
                $this->Mail->create();
                $this->Mail->save($this->request->data);
                if ($this->request->data['Mail']['id']) {
					$this->Session->setFlash(__('Mail has been updated successfully'));
					} 
					else {
						$this->Session->setFlash(__('Mail has been added successfully'));
					}
                $this->redirect(array('controller'=>'mails','action'=>'index'));
                
            }
            else{
                if($id!=null){
                    $this->request->data = $this->Mail->read(null,$id);
                }else{
                    $this->request->data = array();
                }
            }
            
            
            
            $this->set('url',Controller::referer());
        }
        
        
        function admin_delete($id=null){
            $this->autoRender = false;
           //print_r($this->request->data);die;
		    $data=$this->request->data['Mail']['id'];
		    $action = $this->request->data['Mail']['action'];
            $ans="0";
            foreach($data as $value){
                if($value!='0'){
                    if($action=='Publish'){
                        $mail['Mail']['id'] = $value;
                        $mail['Mail']['status']=1;
                        $this->Mail->create();
                        $this->Mail->save($mail);
                        $ans="1";
                    }
                    if($action=='Unpublish'){
                        $mail['Mail']['id'] = $value;
                        $mail['Mail']['status']=0;
                        $this->Mail->create();
                        $this->Mail->save($mail);
                        $ans="1";
                    }
                    if($action=='Delete'){
                        $this->Mail->delete($value);
                        $ans="2";
                    }
                }
            }
		if($ans=="1"){
			$this->Session->setFlash(__('Mail has been '.$this->data['Mail']['action'].'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Mail has been '.$this->data['Mail']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Mail', true),'default','','error');
		}
		$this->redirect($this->request->data['Mail']['redirect']);
                 
	}
	
	function home(){
		//$this->autoRender = false;
	}
	
	function validation(){
		  $this->autoRender = false;
		
		  $this->Mail->set($this->request->data);
		  $result = array();
		  if ($this->Mail->validates()) {
			  $result['error'] = 0;
		  }else{
			  $result['error'] = 1;
		  }
		  $result['errors'] = $this->Mail->validationErrors;
		  $errors = array();
		 
		  foreach($result['errors'] as $field => $data){
			  $errors['Mail'.Inflector::camelize($field)] = array_pop($data);
		  }
		 
		 $result['errors'] = $errors;
		 
		 if($this->request->is('ajax')) {
			  echo json_encode($result);
			  //<span class="error-message">Inserisci il nome del proprietario</span>
			  return;
		  } 
		  
		  
	  }
	
	function admin_view($id = null) {
    $this->layout = '';
     
    $criteria = array();
        $criteria['conditions'] = array('Mail.id'=>$id);
        $parent_mail =  $this->Mail->find('first', $criteria);
        $this->set('mail', $parent_mail);
         
      
  }
}


?>
