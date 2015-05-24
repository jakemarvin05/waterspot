<script language="javascript">
function saveform()
{
	document.getElementById('ServicePublish').value=1;
	document.getElementById('Service').submit();
}
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?=$this->Html->script('admin/ajax_upload.js');?>
	 
<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Service']['id']) && $this->request->data['Service']['id']):
                          echo  __('Update Service');
                          echo  '  ['.$this->request->data['Service']['service_title'].']';
                    else:
                          echo  __('Add Service ');
                           $this->request->data['Service']['status']=1;
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Service',array('name'=>'servicetype','id'=>'add_services','url'=>array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_services',$vendor_id,$service_id),'onsubmit'=>'//return validatefields();','type'=>'file','novalidate' => true));?>
    <?php echo $this->Form->input('id');?>
    <?=$this->Form->hidden('vendor_id', array('value' =>'')); ?>
     <?=$this->Form->hidden('status'); ?>
    <fieldset>
        <dl>
            <dt>
                <label>Select your services:<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<?=$this->Form->input('service_type_id',array('type' =>'select', 'options' => $service_types,'label'=>false,'class'=>'small'));?>
		<?=$this->Form->error('service_type_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd>
            <dt>
                <label>Title:<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<?=$this->Form->input('service_title',array('type'=>'text','label'=>false,'div'=>false,'class'=>'small'));?>
		<?=$this->Form->error('service_title',null,array('wrap' => 'div', 'class' => 'error-message')); ?>             
            </dd>
             <dt>
                <label>Description:</label>
            </dt>
            <dd>
		<?=$this->Form->textarea('description',array('class'=>'small','style'=>'height:100px;width:300px','required'=>false));?>
                <?=$this->Form->error('description',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                <div class="float_left">
			<a href="Javascript:void(0);" onclick="removeeditor(2)">hide editor</a> |
			<a href="Javascript:void(2);" onclick="addeditor(1,'ServiceDescription')">show editor</a>
                </div>
            </dd>
            <dt>  
                <label>Itinerary:</label>
            </dt>
            <dd>
		<?=$this->Form->textarea('itinerary',array('class'=>'small','style'=>'height:100px;width:300px','required'=>false));?>
                <?=$this->Form->error('itinerary',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                <div class="float_left">
			<a href="Javascript:void(0);" onclick="removeeditor(1)">hide editor</a> |
			<a href="Javascript:void(1);" onclick="addeditor(1,'ServiceItinerary')">show editor</a>
                </div>
            </dd>
            <dt>
                <label>How to get there:</label>
            </dt>
            <dd>
                <?php 	
                    echo $this->Form->textarea('how_get_review', array('cols' => '60', 'rows' => '3'));
                   // echo $fck->load('Page.content');
                ?>
                <?=$this->Form->error('how_get_review',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                <div class="float_left"><a href="Javascript:void(0);" onclick="removeeditor(0)">hide editor</a> |
                <a href="Javascript:void(0);" onclick="addeditor(0,'ServiceHowGetReview')">show editor</a>
                </div>
            </dd>
            <dt>
                <label>Price Per Slot:<span style="color:red;">*</span> <?=Configure::read('currency'); ?></label>
            </dt>
            <dd>
		<?=$this->Form->input('service_price',array('type'=>'text','label'=>false,'div'=>false,'class'=>'small'));?>
		&nbsp;Per Person
		<div id="service_price"></div>
		<?=$this->Form->error('service_price',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd>      
            <dt>
                <label>Full Day Price:<span style="color:red;">*</span> <?=Configure::read('currency'); ?></label>
            </dt>
            <dd>
		<?=$this->Form->input('full_day_amount',array('type'=>'text','label'=>false,'div'=>false,'class'=>'small'));?>
		&nbsp;Per Person
		<div id="full_day_amount"></div>
		<?=$this->Form->error('full_day_amount',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd> 
             <dt>
                <label>No of Person:<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<?=$this->Form->input('no_person',array('type'=>'text','label'=>false,'div'=>false,'class'=>'small'));?>
		<?=$this->Form->error('no_person',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd>
            <dt>
                <label>Images(Dimensions should be 600 X 400):<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<div class="files-images">
			<? if(!empty($this->request->data['ServiceImage'])) {
				foreach($this->request->data['ServiceImage'] as $key=>$image) { ?>
				<div class="service-image">
					<input class="radio_button" type="radio" value="<?=$image['image'];?>" name="data[ServiceImage][default_image]" <?=($image['image']==$image['default_image'])?'checked':'';?>>
					<span class="radio_button_status<?=($image['image']==$image['default_image'])?' selected':'';?>"></span>
					<? 
						$path=WWW_ROOT.'img'.DS.'service_images'.DS;
						$imgArr = array('source_path'=>$path,'img_name'=>$image['image'],'width'=>80,'height'=>80);
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0'));
					?>
					<input type="hidden" value="<?=$image['image'];?>" name="data[ServiceImage][images][]">
					<button class="close-image"></button>
				</div>
			<? } ?>
		<?php } ?>
		<div class="img-box"></div>
		<div class="clear"></div>
		</div>
		<div id="show_upload_image" style="display:none;"></div>            
            </dd>
            <dt>
                <label>Location:<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<?=$this->Form->input('location_id',array('type' =>'select', 'options' => $city_list,'label'=>false,'class'=>'small'));?>
		<?=$this->Form->error('location_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd>
            <dt class="form-row" id="p_scents">
                <label>Value Added Services:</label>
            </dt>
            <dd class="vas-data">
		<?php if(empty($this->request->data['ValueAddedService'])) { ?>
			<?=$this->Form->input('ValueAddedService.value_added_name.',array('label'=>false,'div'=>false,'class'=>'add-service value-added-service small'));?>
			<div style="float:left; padding:5px 5px 0 0;"><?=Configure::read('currency'); ?></div><?=$this->Form->input('ValueAddedService.value_added_price.',array('label'=>false,'div'=>false,'class'=>'enter-price small'));?>
			<div class="add-value"><a id="add_btn" class="vas-add-btn" href="#"> </a></div>
		<?php } else { ?>
			<?php
			 foreach($this->request->data['ValueAddedService'] as $key=>$value_added_service) { ?>
				<?php if($key==0) { ?>
					<? echo $this->Form->input('ValueAddedService.value_added_name.',array('label'=>false,'div'=>false,'class'=>'add-service value-added-service small','value'=>$value_added_service['value_added_name']));?>
					<div style="float:left; padding:5px 5px 0 0;"><?=Configure::read('currency'); ?></div>
					<?php echo $this->Form->input('ValueAddedService.value_added_price.',array('label'=>false,'div'=>false,'class'=>'enter-price small','value'=>$value_added_service['value_added_price']));?>
					<div class="add-value">
						<a id="add_btn" class="vas-add-btn" href="#"> </a>
						<?=$this->Html->link('Delete',array('controller'=>'services','action'=>'value_added_delete',$value_added_service['id'],$value_added_service['service_id']),array('class'=>'vas-delete-btn',"onclick"=>"return confirm('Are you sure you wish to delete this value added service?')"));?>
					</div>
				<? } 
				else {
					if($key==1) { ?>
						<div id="extender">
					 <? }?>	
					<div class="add-values" style="display: block;"><label></label>
						<?=$this->Form->input('ValueAddedService.value_added_name.',array('label'=>false,'div'=>false,'class'=>'add-service value-added-service small','value'=>$value_added_service['value_added_name']));?>
						<div style="float:left; padding:5px 5px 0 0;"><?=Configure::read('currency'); ?></div>
						<?=$this->Form->input('ValueAddedService.value_added_price.',array('label'=>false,'div'=>false,'class'=>'enter-price small','value'=>$value_added_service['value_added_price']));?>
						<?=$this->Html->link('Delete',array('controller'=>'services','action'=>'value_added_delete',$value_added_service['id'],$value_added_service['service_id']),array('class'=>'vas-delete-btn',"onclick"=>"return confirm('Are you sure you wish to delete this value added service?')"));?></div>
					</a>
					<? if($key==(count($this->request->data['ValueAddedService'])-1)) { ?>
						</div>
					<? }	
				}
			}
		}	
		?>
		<div id="extender"></div>	            
            </dd>
        </dl>
    </fieldset>
    <button type="submit">
	<?php 
	    if (isset($this->request->data['Service']['id']) && $this->request->data['Service']['id']):
		    echo __('Update');
	    else:
		    echo __('Add');
	    endif;								
	?>
    </button> or 
    <?php echo $this->Html->link('Cancel', array('controller'=>'services', 'action' => 'servicelist',$vendor_id));?>
    <?php echo $this->Form->end();?>
</div>

<script type="text/javascript">
	
	
	
	
	
	$(window).load(function(){
		/**** JS For  Value Added Services Add More Functionality***********/
		//set a counter
		var i = 0;
		i = Number($('.value-added-service:input').length)+1;
		
		//add input
		$('a#add_btn').click(function () {
			 
			$('<div class="add-values"><label></label><?=$this->Form->input('ValueAddedService.value_added_name.',array('label'=>false,'div'=>false,'class'=>'add-service value-added-service small'));?><div style="float:left; padding:5px 5px 0 0;color:#787878;font-size:16px;"><?=Configure::read('currency'); ?></div><?=$this->Form->input('ValueAddedService.value_added_price.',array('label'=>false,'div'=>false,'class'=>'enter-price small'));?>' +
				'<a class="dynamic-link remove_value_add vas-delete-btn" id="add_btn" href=""> </a></div>').fadeIn("slow").appendTo('#extender');
			i++; 
			return false;
		});
		//fadeout selected item and remove 
		$("#extender").on('click', '.dynamic-link', function () {
			$(this).parent('.add-values').fadeOut(300, function () {
				i--;
				$(this).remove();
				return false;
			});
			
			return false;
		});
		 
		/**** END JS For  Value Added Services Add More Functionality***********/
		
		/**** JS For  Slots Add More Functionality***********/
		var j=0; //start_slot
		j=Number($('select.start_slot').length) + 1;
		
		// select slot
		//add input
		$('a#add_slot_btn').click(function () {
			
			$('<div class="clear"></div><div class="input select"></div>' +'<a class="dynamic-link-2 remove_slots"  id="add_slot_btn" href="#step2"><img src="/img/remove.png" style="margin:7px 0 0 20px;" /></a></div>').fadeIn("slow").appendTo('#extender2');
			i++;
			
			return false;
		});

		
		$("#extender2").on('click', '.dynamic-link-2', function () {
			$(this).parent().fadeOut(300, function () {
				$(this).empty();
				return false;
			});
		});
		/**** JS For  Slots Add More Functionality***********/
	});
	
	
	
	
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		 
		 $('#add_services').AjaxUpload({
			div_image_class:'img-box',
			ajax_url: '<?=$path?>vendor_manager/services/images_handle',
			delete_image_url: '<?=$path?>vendor_manager/services/image_delete',
			file_input_name: 'data[ServiceImage][images][]'
		});
		
		 $('#add_services').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
          
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#add_services > span#for_owner_cms').show();
            $('#add_services > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/services/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                cache: false,
				contentType: false,
				processData: false,
                success: function(data) {
					if(data.error==1){
                        $.each(data.errors,function(i,v){
							if(i=="ServiceServicePrice"){
								i="service_price";
							}
							if(i=="ServiceFullDayAmount"){
								i="full_day_amount";
							}
							$('#'+i).addClass("invalid form-error").after('<div class="error-message" style="width:100%;">'+v+'</div>');
							$('#'+i).bind('click',function(){
								$(this).removeClass('invalid form-error');
								$(this).next().remove();
								});
                        });
                    }else{
                        status = 1;
                    }
                   
                 }
            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#add_services > button[type=submit]').attr({'disabled':false});
               $('#add_services > span#for_owner_cms').hide();
            }
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
 <script type="text/javascript">
     <?php $path = $this->Html->webroot; ?>
     var fckeditor = new Array;
     addeditor(0,'ServiceHowGetReview'); 
     addeditor(1,'ServiceItinerary'); 
     addeditor(2,'ServiceDescription'); 
      
     
     function removeeditor(id){
         fckeditor[id].destroy();
     }
     
     function addeditor(id,name){
         fckeditor[id] = CKEDITOR.replace(name,{
                                language : 'eng',
                                uiColor : '#e6e6e6',
                                toolbar : 'Basic',
                                customConfig : '',
                                filebrowserBrowseUrl : '<?=$path?>js/ckfinderckfinder.html',
                                filebrowserImageBrowseUrl : '<?=$path?>js/ckfinder/ckfinder.html',
                                filebrowserUploadUrl : '<?=$path?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                filebrowserImageUploadUrl : '<?=$path?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
                        });
     }
     
     
     
    </script>
    <script type="text/javascript">
	$(document).ready(function(){
		$('.service-image > img').bind('click',function(){
			$(this).parent().find('input[type=radio]').click();
			$(this).parent().find('.radio_button_status').addClass('selected');
			$(this).parent().siblings().find('.radio_button_status').removeClass('selected');
		});
    });
    </script>
