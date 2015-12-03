<script language="javascript">
function saveform()
{
	document.getElementById('ServicePublish').value=1;
	document.getElementById('Service').submit();
}
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

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
                <label>Private? <span style="color:red;">*</span></label>
            </dt>
            <dd>
				<?= $this->Form->checkbox('is_private', array('label' => false, 'div' => false)); ?>
                <?= $this->Form->error('is_private', null, array('wrap' => 'div', 'class' => 'error-message')); ?>          
            </dd>
            <div class="to-hide">
            <dt>
                <label>Has Minimum-To-Go? <span style="color:red;">*</span></label>
            </dt>
            <dd>
				<?= $this->Form->checkbox('is_minimum_to_go', array('label' => false, 'div' => false)); ?>
                <?= $this->Form->error('is_minimum_to_go', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd>
            <dt>
                <label>Minimum Participants:<span style="color:red;">*</span></label>
            </dt>
            <dd>
	<?= $this->Form->input('min_participants', array('type' => 'select', 'options' => $participants_num_list, 'label' => false, 'class' => '')); ?>
    <?= $this->Form->error('min_participants', null, array('wrap' => 'div', 'class' => 'error-message')); ?>           
            </dd>
            <dt>
                <label>Max Capacity per Timeslot:<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<?= $this->Form->input('no_person', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service')); ?>
        <?= $this->Form->error('no_person', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
            </dd>
                </div>
            <dt>
                <label>Price Per Slot:<span style="color:red;">*</span></label>
            </dt>
            <dd>
		<?= $this->Form->input('service_price', array('placeholder' => 'Per person', 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service edit')); ?>
                        <div id="service_price"></div>
        <?= $this->Form->error('service_price', null, array('wrap' => 'div', 'class' => 'error-message')); ?>            
            </dd>

            <dt>
                <label>Add videos by Youtube URL:<span style="color:red;">*</span></label>
            </dt>
            <dd>
                <div class="fieldbox video-urls">
                    <?php $count = 0; ?>
                    <?php if (isset($this->request->data['Service']['youtube_url'])):
                        foreach (unserialize($this->request->data['Service']['youtube_url']) as $youtube) :
                            ?>
                            <div data-target="<?php echo $count; ?>">
                                <input name="data[Service][youtube_url][]" data-inputid="<?php echo $count; ?>"
                                       class="add-service add-video-field" type="text" id="ServiceYoutubeUrl]["
                                       value="<?php echo $youtube; ?>">
                            </div>
                            <?= $this->Form->error('youtube_url', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                            <a class="delete-video" data-target="<?php echo $count; ?>" href="#" style="font-size:20px; font-weight:600; text-decoration:none;">-</a>
                            <?php
                            $count++;
                        endforeach;
                    endif;
                    ?>
                    <div
                        data-target="<?php echo $count; ?>"><?= $this->Form->input('youtube_url][', array('type' => 'text', 'data-inputId' => '<?php echo $count; ?>', 'label' => false, 'div' => false, 'class' => 'add-service add-video-field')); ?></div>
                    <?= $this->Form->error('youtube_url', null, array('wrap' => 'div', 'class' => 'error-message')); ?>

                    <a id="add-video" class="add-video" href="#" style="font-size:20px; font-weight:600; text-decoration:none;">+</a>
                </div>
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
                <label>Panorama Image (recommended 1600x680):<span style="color:red;">*</span></label>
            </dt>
            <dd>
                <div class="fieldbox">
                    <div id="panorama-image-container">
                        <?php
                        if (isset($this->request->data['Service']['panorama_image'])) {
                            echo '<img src="/img' . DS . 'service_images' . DS . $this->request->data['Service']['panorama_image'] . '" style="max-height: 200px; margin: auto; max-width: 500px;" >';
                        }
                        ?>
                    </div>
                    <input type="file" name="data[panorama]" id="panorama-input">
                    <input type="hidden" name="data[Service][panorama_image]" id="panorama-field"
                           value="<?php echo !empty($this->request->data['Service']['panorama_image']) ? $this->request->data['Service']['panorama_image'] : '' ?>">
                </div>
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
                
                <div class="fieldbox addservedit form" style="position: relative;">
                    <?= $this->Form->input('location_string', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service edit', 'placeholder' => 'Enter address...', 'style' => 'width: 100%;')); ?>
                    <?= $this->Form->error('location_string', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                    <img id="mapAjaxLoader" src="/img/admin/icons/ajax_loading_nested.gif" style="position: absolute; top: 5px; right: 5px; display: none;width: 20px; height: 20px;">
                </div>
                <br><br>

                <script src="https://maps.googleapis.com/maps/api/js"></script>
                <div id="map-canvas" style="height:400px; width:100%;"></div>
                <script>
                $(document).ready(function() {
                    var mapper = Object.create(Mapper);
                    mapper.previousLocation = "<?php          
                        $string = (!empty($service_detail['Service']['location_string'])?$service_detail['Service']['location_string']:'Singapore');
                        echo str_replace(' ','+',$string);
                    ?>"

                    mapper.init({
                        loaderIcon: $('#mapAjaxLoader')
                    });
                    
                    $('#ServiceLocationString').keyup(function(e){

                        var location = $('#ServiceLocationString').val().replace(' ','+');
                        if (location === "") return false;

                        mapper.mapping(location);
                    });
                });

                </script>

                <br><br>



                <div class="fieldbox">
                    <?= $this->Form->textarea('how_get_review', array('cols' => '60', 'rows' => '3', 'placeholder' => 'Please enter description here....'));
                    // echo $fck->load('Page.content');  ?>
                    <?= $this->Form->error('how_get_review', null, array('wrap' => 'div', 'class' => 'error-message')); ?>

                </div>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.left-area').height($('.right-area').height() + 610);
        
        var fieldCTR = <?php echo (isset($count)?$count:"0"); ?>;

        $('.video-urls').on('click', '#add-video', function (e) {
            $(this).remove();
            e.preventDefault();
            $('.video-urls').append('<a class="delete-video" data-target=' + fieldCTR + ' href="#" style="font-size:20px; font-weight:600; text-decoration:none;">-</a><div data-target="' + (fieldCTR + 1) + '"><?= $this->Form->input('Service][youtube_url][', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service add-video-field')); ?></div>')
            $('.video-urls').append('<a id="add-video" class="add-video" href="#" style="font-size:20px; font-weight:600; text-decoration:none;">+</a>');
            fieldCTR++;
        });

        $('.video-urls').on('click', 'a.delete-video', function (e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('div[data-target="' + target + '"]').remove();
            $(this).remove();

        });

        $('[name="data[Service][is_private]"]').change(function () {
            if (!$('[name="data[Service][is_private]').is(':checked')) {
                $('[name="data[Service][no_person]"]').val("1");
                $('.to-hide').animate({
                    opacity: 1,
                    height: "toggle",
                    "padding-bottom": 0
                }, 600, function () {
                    // Animation complete.
                    $('this').hide()
                });
            }
            else {
                $('.to-hide').animate({
                    "opacity": 0,
                    "height": "toggle",
                    "padding-bottom": 19
                }, 600, function () {
                    // Animation complete

                });
            }
        });

        var prevValue = $('#ServiceMinParticipants option[selected="selected"]').text();


        $('[name="data[Service][is_minimum_to_go]"]').change(function () {


            if ($('[name="data[Service][is_minimum_to_go]').is(':checked')) {

                $('select[name="data[Service][min_participants]"]').val(prevValue - 1);
                $('[data-id="ServiceMinParticipants"] .filter-option').text(prevValue);
                $('[data-id="ServiceMinParticipants"]').attr("disabled", false);
                $('.minimum-participants ul.dropdown-menu li[data-original-index="0"]').remove();
                $('[data-id="ServiceMinParticipants"] .filter-option').text("2");
                $('.minimum-participants').animate(
                    {
                        height: "toggle"
                    },
                    400,
                    function () {
                        //done
                    }
                );
            }
            else {
                $('#ServiceMinParticipants').val(0);
                $('[data-id="ServiceMinParticipants"] .filter-option').text("1");
                $('[data-id="ServiceMinParticipants"]').attr("disabled", true);
                $('.minimum-participants').animate(
                    {
                        height: "toggle"
                    },
                    400,
                    function () {
                        //done
                    }
                );

            }

        });


    });

    $(window).load(function () {
            if (!$('[name="data[Service][is_minimum_to_go]').is(':checked')) {

                $('[data-id="ServiceMinParticipants"]').attr("disabled", true);
                $('.minimum-participants').animate(
                    {
                        height: "toggle"
                    },
                    400,
                    function () {
                        //done
                    }
                );
            }
            else {
                $('.minimum-participants ul.dropdown-menu li[data-original-index="0"]').remove();
            }


            if ($('[name="data[Service][is_private]"]').is(':checked')) {

                $('.to-hide').animate(
                    {
                        height: "toggle"
                    },
                    400,
                    function () {
                        //done
                    }
                );
            }
            else {
                $('.minimum-participants ul.dropdown-menu li[data-original-index="0"]').remove();
            }


        }
    );

</script>

<script type="text/javascript">
    $('#panorama-input').change(function () {
        var formData = new FormData();
        formData.append('data[panorama]', this.files[0]);
        $.ajax({
            url: "<?=$path?>vendor_manager/services/panorama_image_handle",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                var image = '<img src="<?php echo '/img' . DS . 'service_images' . DS; ?>' + data + '" style="max-height: 200px; margin: auto; max-width: 500px;" >';
                $('#panorama-field').val(data);
                $('#panorama-image-container').html(image);
            }
        });
    });
</script>
