<? if (empty($this->request->data['Service']['description'])) { ?>
    <? //$this->request->data['Service']['description']="Please enter description here";
} ?>
<div class="container-fluid vendor-panel">
    <div class="hr-line"></div>
    <div class="clear" style="margin-top:80px;"></div>
    <h2 class="page-title">
        <?php
        if (isset($this->request->data['Service']['id']) && $this->request->data['Service']['id']):
            echo __('Update Service');
        else:
            echo __('Add Service');
        endif;
        ?>
    </h2>
    <?= $this->element('VendorManager.left-vendor-panel'); ?>
    <div class="right-area col-sm-9 col-xs-12">
        <?= $this->element('message'); ?>
        <?= $this->Form->create('Service', array('class' => 'dashboard-edit-form', 'id' => 'add_services', 'action' => 'add_services', 'novalidate' => true));
        echo $this->Form->hidden('id');
        ?>
        <div class="dashboard-form-row">
            <div class="cont">
                <div class="labelbox">
                    <label>Select your services:</label>
                </div>
                <div class="fieldbox">
                    <?= $this->Form->input('service_type_id', array('type' => 'select', 'options' => $service_types, 'label' => false,'class'=>'selectpicker')); ?>
                    <?= $this->Form->error('service_type_id', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                </div>
            </div>
        </div>
        <div style="padding-top: 0" class="cont col-sm-9">

	<div class="dashboard-form-row row servcont">

		<div class="labelbox">
			<label>Title: <span style="color:#ff0000;">*</span></label>
		</div>
		<div class="fieldbox">
			<?=$this->Form->input('service_title',array('type'=>'text','label'=>false,'div'=>false,'class'=>'add-service'));?>
			<?=$this->Form->error('service_title',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
            <div class="dashboard-form-row row servcont">
                <div class="labelbox">
                    <label>Private? <span style="color:#ff0000;">*</span></label>

                    <div class="fieldbox">
                        <?= $this->Form->checkbox('is_private', array('label' => false, 'div' => false)); ?>
                        <?= $this->Form->error('is_private', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                    </div>
                </div>
            </div>
       <div class="to-hide">

           <div class="dashboard-form-row row servcont">
               <div class="labelbox">
                   <label>Has Minimum-To-Go? <span style="color:#ff0000;">*</span></label>

                   <div class="fieldbox">
                       <?= $this->Form->checkbox('is_minimum_to_go', array('label' => false, 'div' => false)); ?>
                       <?= $this->Form->error('is_minimum_to_go', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                   </div>
               </div>
           </div>

            <div class="dashboard-form-row   row servcont">

                    <div class="labelbox">
                        <label>Minimum Participants: <span style="color:#ff0000;">*</span></label>
                    </div>

                <div class="fieldbox addservedit">
                    <?= $this->Form->input('min_participants', array('type' => 'select', 'options' => $participants_num_list, 'label' => false,'class'=>'selectpicker')); ?>
                    <?= $this->Form->error('min_participants', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                </div>
            </div>
            <div class="dashboard-form-row  row servcont">
                    <div class="labelbox">
                        <label>Max Capacity:<span style="color:#ff0000;">*</span> </label>
                    </div>
                    <div class="fieldbox addservedit form">
                        <?= $this->Form->input('no_person', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service')); ?>
                        <?= $this->Form->error('no_person', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                    </div>
            </div>

       </div>
       
            <!--
            <div class="dashboard-form-row row servcont">
                <div id="p_scents" class="labelbox">
                    <label>Value Added Services: </label>
                </div>
                <div class="fieldbox">
                    <?php if (empty($this->request->data['ValueAddedService'])) { ?>
                        <span class="vas-name col-sm-1">Name</span>
                        <?= $this->Form->input('ValueAddedService.value_added_name.', array('label' => false, 'div' => false, 'class' => 'add-service col-sm-4 value-added-service')); ?>

                        <span class="currency-symbol col-sm-1"><?= Configure::read('currency'); ?></span>
                        <?= $this->Form->input('ValueAddedService.value_added_price.', array('label' => false, 'div' => false, 'class' => 'enter-price col-sm-4')); ?>

                        <div class="add-value col-sm-1"><a id="add_btn" class="vas-add-btn" href="#"><i
                                    class="fa fa-plus-square add-button"></i></a></div>

                    <?php } else { ?>
                        <?php
                        foreach ($this->request->data['ValueAddedService'] as $key => $value_added_service) { ?>
                            <?php if ($key == 0) { ?>
                                <? echo $this->Form->input('ValueAddedService.value_added_name.', array('label' => false, 'div' => false, 'class' => 'add-service value-added-service', 'value' => $value_added_service['value_added_name'])); ?>
                                <span class="currency-symbol"><?= Configure::read('currency'); ?></span>
                                <?php echo $this->Form->input('ValueAddedService.value_added_price.', array('label' => false, 'div' => false, 'class' => 'enter-price', 'value' => $value_added_service['value_added_price'])); ?>
                                <div class="add-value">
                                    <a id="add_btn" href="#" class="vas-add-btn"></a>
                                    <?= $this->Html->link('Delete', array('controller' => 'services', 'action' => 'value_added_delete', $value_added_service['id'], $value_added_service['service_id']), array('class' => 'vas-delete-btn', "onclick" => "return confirm('Are you sure you wish to delete this value added service?')")); ?>
                                </div>
                            <? } else {
                                if ($key == 1) { ?>
                                    <div id="extender">
                                <? } ?>
                                <div class="add-values" style="display: block;">
                                    <?= $this->Form->input('ValueAddedService.value_added_name.', array('label' => false, 'div' => false, 'class' => 'add-service value-added-service', 'value' => $value_added_service['value_added_name'])); ?>
                                    <span class="currency-symbol"><?= Configure::read('currency'); ?></span>
                                    <?= $this->Form->input('ValueAddedService.value_added_price.', array('label' => false, 'div' => false, 'class' => 'enter-price', 'value' => $value_added_service['value_added_price'])); ?>
                                    <a id="add_btn" class="dynamic-link remove_value_add" href="">
                                    <?= $this->Html->link('Delete', array('controller' => 'services', 'action' => 'value_added_delete', $value_added_service['id'], $value_added_service['service_id']), array('class' => 'vas-delete-btn', "onclick" => "return confirm('Are you sure you wish to delete this value added service?')")); ?>
                                </div>
                                </a>
                                <? if ($key == (count($this->request->data['ValueAddedService']) - 1)) { ?>
                                    </div>
                                <? }
                            }
                        }
                    }
                    ?>
                    <div id="extender"></div>
                </div>
            </div>

            -->



                <div class="dashboard-form-row adjustable row servcont">
                    <div class="labelbox">
                        <label>Price Per Slot:<span style="color:#ff0000;">*</span></label>
                    </div>
                    <div class="addservedit">
                        <div class="dollarsign">
                            <span class="currency-symbol"><?= Configure::read('currency'); ?></span>
                        </div>
                        <div class="addservedit form">
                            <?= $this->Form->input('service_price', array('placeholder' => 'Per person', 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service edit')); ?>
                            <div id="service_price"></div>
                            <?= $this->Form->error('service_price', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </div>
                    </div>
                </div>
                


            <div class="dashboard-form-row servcont">
                <div class="labelbox">
                    <label>Location: </label>
                </div>
                <div class="fieldbox">
                    <?= $this->Form->input('location_id', array('type' => 'select', 'options' => $city_list, 'class'=>'selectpicker', 'label' => false)); ?>
                    <?= $this->Form->error('location_id', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                </div>
            </div>
            <div class="dashboard-form-row row servcont">
                <div class="labelbox">
                    <label>Add videos by Youtube URL:<span style="color:#ff0000;"></span> </label>
                </div>
                <div class="fieldbox video-urls">
                    <div data-target="0"><?= $this->Form->input('youtube_url', array('type' => 'text','data-inputId'=>'0', 'label' => false, 'div' => false, 'class' => 'add-service add-video-field')); ?></div>
                    <?= $this->Form->error('youtube_url', null, array('wrap' => 'div', 'class' => 'error-message')); ?>

                    <a id="add-video" class="add-video" href="#"><i class="fa fa-plus-square"></i> </a>
                </div>
            </div>
            <div class="dashboard-form-row row servcont">
                <div class="labelbox">
                    <!-- city_id as location_id -->
                    <label>Images(Dimensions should be 600 X 400): </label>
                </div>
                <div class="fieldbox image-group">
                    <? if (!empty($this->request->data['ServiceImage'])) {
                        foreach ($this->request->data['ServiceImage'] as $key => $image) { ?>

                            <div class="dashboard-service-images col-sm-2 col-xs-4 service-image">
                                <input class="radio_button" type="radio" value="<?= $image['image']; ?>"
                                       name="data[ServiceImage][default_image]" <?= ($image['image'] == $image['default_image']) ? 'checked' : ''; ?>>

                                <span
                                    class="radio_button_status<?= ($image['image'] == $image['default_image']) ? ' selected' : ''; ?>"></span>
                                <?
                                $path = WWW_ROOT . 'img' . DS . 'service_images' . DS;
                                $imgArr = array('source_path' => $path, 'img_name' => $image['image'], 'width' => 80, 'height' => 80);
                                $resizedImg = $this->ImageResize->ResizeImage($imgArr);
                                echo $this->Html->image($resizedImg, array('border' => '0'));
                                ?>
                                <input type="hidden" value="<?= $image['image']; ?>"
                                       name="data[ServiceImage][images][]">
                                <button class="close-image"><i class="fa fa-times"></i></button>
                            </div>
                        <? } ?>
                    <?php } ?>

                    <div id="show_upload_image" style="display:none;"></div>
                </div>
            </div>
            <div class="dashboard-form-row servcont">
                <div class="labelbox">
                    <label>Description:</label>
                </div>
                <div class="fieldbox">
                    <?= $this->Form->textarea('description', array('cols' => '60', 'rows' => '3', 'id' => 'ServiceDescription', 'placeholder' => 'Please enter description here....'));
                    // echo $fck->load('Page.content'); ?>
                    <?= $this->Form->error('description', null, array('wrap' => 'div', 'class' => 'error-message')); ?>

                </div>
            </div>
            <div class="dashboard-form-row servcont">
                <div class="labelbox">
                    <label>Itinerary:</label>
                </div>
                <div class="fieldbox">
                    <?= $this->Form->textarea('itinerary', array('cols' => '60', 'rows' => '3', 'id' => 'ServiceItinerary', 'placeholder' => 'Please enter description here....'));
                    // echo $fck->load('Page.content'); ?>
                    <?= $this->Form->error('itinerary', null, array('wrap' => 'div', 'class' => 'error-message')); ?>

                </div>
            </div>

            <div class="dashboard-form-row servcont">
                <div class="labelbox">
                    <label>How to get there:</label>
                </div>
                <div class="fieldbox">
                    <?= $this->Form->textarea('how_get_review', array('cols' => '60', 'rows' => '3', 'placeholder' => 'Please enter description here....'));
                    // echo $fck->load('Page.content'); ?>
                    <?= $this->Form->error('how_get_review', null, array('wrap' => 'div', 'class' => 'error-message')); ?>

                </div>
            </div>


            <div class="dashboard-form-row servcont">
                <input class="dashboard-buttons" value="Submit" type="submit">
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('ServiceDescription', {
        removePlugins: 'bidi,div,font,forms,flash,horizontalrule,iframe,justify,table,tabletools,smiley',
        removeButtons: 'Anchor,Underline,Strike,Subscript,Superscript,Image',
        format_tags: 'p;h1;h2;h3;pre;address'
    });
    CKEDITOR.replace('ServiceItinerary', {
        removePlugins: 'bidi,div,font,forms,flash,horizontalrule,iframe,justify,table,tabletools,smiley',
        removeButtons: 'Anchor,Underline,Strike,Subscript,Superscript,Image',
        format_tags: 'p;h1;h2;h3;pre;address'
    });

    CKEDITOR.replace('ServiceHowGetReview', {
        removePlugins: 'bidi,div,font,forms,flash,horizontalrule,iframe,justify,About,table,tabletools,smiley',
        removeButtons: 'Anchor,Underline,Strike,Subscript,Superscript,Image',
        format_tags: 'p;h1;h2;h3;pre;address'
    });
</script>

<script type="text/javascript">
    $(window).load(function () {
        /**** JS For  Value Added Services Add More Functionality***********/
        //set a counter
        var i = 0;
        i = Number($('.value-added-service:input').length) + 1;

        //add input
        $('a#add_btn').click(function () {
            $('<div class="add-values"><label></label><?=$this->Form->input('ValueAddedService.value_added_name.',array('label'=>false,'div'=>false,'class'=>'add-service value-added-service'));?><span class="currency-symbol">$</span><div style="float:left; padding:5px 0 0 10px; margin-right:-20px;color:#787878;font-size:16px;"><?=Configure::read('currency'); ?></div><?=$this->Form->input('ValueAddedService.value_added_price.',array('label'=>false,'div'=>false,'class'=>'enter-price'));?>' +
            '<a class="dynamic-link remove_value_add vas-delete-btn" id="add_btn" href=""></a></div>').fadeIn("slow").appendTo('#extender');
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
        var j = 0; //start_slot
        j = Number($('select.start_slot').length) + 1;

        // select slot
        //add input
        $('a#add_slot_btn').click(function () {

            $('<div class="clear"></div><div class="input select"></div>' + '<a class="dynamic-link-2 remove_slots"  id="add_slot_btn" href="#step2"><img src="/img/remove.png" style="margin:7px 0 0 20px;" /></a></div>').fadeIn("slow").appendTo('#extender2');
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
    $(document).ready(function () {

        $('#add_services').AjaxUpload({
            div_image_class: 'image-group',
            ajax_url: '<?=$path?>vendor_manager/services/images_handle',
            delete_image_url: '<?=$path?>vendor_manager/services/image_delete',
            file_input_name: 'data[ServiceImage][images][]'
        });

        $('#add_services').submit(function () {

            //var data = $(this).serializeArray();
            var data = new FormData(this);
            var formData = $(this);
            var status = 0;

            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });
            $('.error-message').remove();
            $('#add_services > span#for_owner_cms').show();
            $('#add_services > button[type=submit]').attr({'disabled': true});

            $.ajax({
                url: '<?=$path?>vendor_manager/services/validation',
                async: false,
                data: data,
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data.error == 1) {
                        $.each(data.errors, function (i, v) {
                            if (i == "ServiceServicePrice") {
                                i = "service_price";
                            }
                            if (i == "ServiceFullDayAmount") {
                                i = "full_day_amount";
                            }
                            $('#' + i).addClass("invalid form-error").after('<div class="error-message add_services_error" style="width:100%;">' + v + '</div>');
                            $('#' + i).bind('click', function () {
                                $(this).removeClass('invalid form-error');
                                $(this).next().remove();
                            });
                        });
                    } else {
                        status = 1;
                    }

                }
            });
            if (status == 0) {
                $("html, body").animate({scrollTop: 0}, "slow");
                $('#add_services > button[type=submit]').attr({'disabled': false});
                $('#add_services > span#for_owner_cms').hide();
            }

            return (status === 1) ? true : false;

        });


    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.service-image > img').bind('click', function () {
            $(this).parent().find('input[type=radio]').click();
            $(this).parent().find('.radio_button_status').addClass('selected');
            $(this).parent().siblings().find('.radio_button_status').removeClass('selected');
        });


    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.left-area').height($('.right-area').height() + 610);
        if ($(window).width() > 768) {
            $('.left-area').theiaStickySidebar({
                // Settings
                additionalMarginTop: 0,
                additionalMarginBottom: 0,
                scrollThrough: ['vendor-panel']
            });
        }
        var fieldCTR = 0;

        $('.video-urls').on('click','#add-video',function(e){
            $(this).remove();
            e.preventDefault();
            $('.video-urls').append('<a class="delete-video" data-target='+fieldCTR+' href="#"><i class="fa fa-minus-square"></i> </a><div data-target="'+(fieldCTR+1)+'"><?= $this->Form->input('youtube_url', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'add-service add-video-field')); ?></div>')
            $('.video-urls').append('<a id="add-video" class="add-video" href="#"><i class="fa fa-plus-square"></i> </a>');
            fieldCTR++;
        });

        $('.video-urls').on('click','a.delete-video',function(e) {
            e.preventDefault();
           var target = $(this).data('target');
            $('div[data-target="'+target+'"]').remove();
            $(this).remove();

        });

        $('[name="data[Service][is_private]"]').change(function(){
            if($('[name="data[Service][is_private]').is(':checked')){
                $('.to-hide').animate({
                    opacity: 0,
                    height: "toggle",
                    "padding-bottom": 0
                }, 600, function() {
                    // Animation complete.
                    $('this').hide()
                });
            }
            else{

                        $('.to-hide').animate({
                            "opacity": 1,
                            "height": "toggle",
                            "padding-bottom": 19
                        }, 600, function () {
                            // Animation complete
                            
                        });
            }
        });
        $('[name="data[Service][is_minimum_to_go]"]').change(function(){

            if($('[name="data[Service][is_minimum_to_go]').is(':checked')) {

                $('select[name="data[Service][min_participants]"]').val(1);
                $('[data-id="ServiceMinParticipants"] .filter-option').text("1");
                $('[data-id="ServiceMinParticipants"]').attr("disabled", true);

            }
            else{

                $('select[name="data[Service][min_participants]"]').val(2);
                $('[data-id="ServiceMinParticipants"] .filter-option').text("2");
                $('[data-id="ServiceMinParticipants"]').attr("disabled", false);
            }

        });
        $('.selectpicker').selectpicker();


    });
</script>
