<?php
Class Slide extends SlideManagerAppModel {
	public $name = "Slide";
	
	public $validate = array(
	
	'name' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Slide name should be less than 255 charcter(s).'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter slide name.'
            ) 
        ),
      /*
        'image' => array(
			      'checkupload' =>array(
				   'rule' => array('checkUpload', false),
				   'message' => 'Please upload an image file',//'Invalid file',
				   'on' => 'create'
				   ),
			
			      ), 
		*/
      'image' =>
        array(
            'rule1'=>
            array(
				'rule' =>array('extension', array('gif', 'jpeg', 'png', 'jpg')),
				'message' => 'Please upload a valid image.',
				'on' => 'create'
				 ),
			'size' => 
				array(
					'rule'    => array('check_size_image'),
					'message' => 'Please upload an image having dimension 1450x600.'			
				)	
                      
        ), 
        /*
        'bgimage' =>
           array(
				'rule2' =>array(
				'rule' =>array('extension', array('gif','jpeg','png','jpg')),
				'message' =>'Please upload a valid image',
				'on' =>'create'
				)
           ),
           * */
       
         'heading1' =>
		  array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Slide Heading 1 should be less than 255 charcter(s)'
				),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Slide Heading'
            ) 
        ),
          'heading2' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Slide Heading 2 should be less than 255 charcter(s)'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Slide Heading'
            ) 
        ),
          'link' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Link (URL) should be less than 255 charcter(s)'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Link Or URL'
            ) 
        ),
        /*'text1' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 52),
                'message' => 'Text1 should be less than 50 charcter(s).'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter text1.'
            ) 
        ), 
        
        'text2' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 75),
                'message' => 'Text2 should be less than 50 charcter(s).'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter text2.'
            ) 
        ),*/
         
    );
  
	public function check_size_image(){
		if((!empty($this->data['Slide']['id'])) && $this->data['Slide']['image']['tmp_name']=='') {
			return true;
		}else {
			if($this->data['Slide']['image']['error'] < 1){
				$imgSize = @getImageSize($this->data['Slide']['image']['tmp_name']);
				//if(($imgSize[0]==950 ) && ($imgSize[1]==384 ))
				if(($imgSize[0]>=1450 && $imgSize[0]<=1500) && ($imgSize[1]>=600 && $imgSize[1]<=650)){
					return true;
				}
			}
			return false;
		}
	}
	public function afterSave(){
		Cache::delete('cake_slider');
	}
	public function afterDelete(){
		Cache::delete('cake_slider');
		parent::afterDelete();
	}
	public function beforeSave(){
		Cache::delete('cake_slider');
		parent::beforeSave();
	}
}
?>
