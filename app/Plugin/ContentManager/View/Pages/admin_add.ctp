 
<script language="javascript">
function saveform()
{
	document.getElementById('PagePublish').value=1;
	document.getElementById('PageCms').submit();
}
</script>
<?php $managemenutop=array('0'=>'No','1'=>'Yes');?>
<?php $managemenufooter=array('0'=>'No','1'=>'Yes');?>

<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Page']['id']) && $this->request->data['Page']['id']):
                          echo  __('Update Content');
                    else:
                          echo  __('Add Content');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Page',array('name'=>'pages','id'=>'PageCms','action'=>'add/'.$parent_id,'onsubmit'=>'//return validatefields();','type'=>'file','novalidate'=>true))?>
    <?php echo $this->Form->hidden('id');?>
    <?php echo $this->Form->hidden('status');?>
    <?php echo $this->Form->hidden('parent_id', array('value'=>$parent_id)); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
     <?=$this->Form->hidden('form_name',array('required'=>false,'value'=>'AdminForm')); ?>
    <fieldset>
        <dl>
            <dt>
                <label>Title <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('name',array('class'=> 'small','size'=>'45')); ?>
                <?=$this->Form->error('name',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            
            <dt>
                <label>Page Title <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('page_title',array('class'=> 'small','size'=>'45')); ?>
                <?=$this->Form->error('page_title',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
             <dt>
                <label>SEO Keyword <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('url_key',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('url_key',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                 
            </dd>
            
<!--
             <dt>
                <label>Sub Page</label>
            </dt>
            
            <dd>
                <?//=$this->Form->checkbox('sub_page',array('required'=>false)); ?>
                <?//=$this->Form->error('sub_page',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                 
            </dd>
-->
            
            <dt>
				<label>Show Top Menu</label>
            </dt>
            <dd>
				<?=$this->Form->input('show_top_menu', array('type' =>'select', 'options' => $managemenutop,'label'=>false));?>
				<?=$this->Form->error('show_top_menu',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
            </dd>
            <dt>
			 <dt>
				<label>Show Footer Menu</label>
            </dt>
            <dd>
				<?=$this->Form->input('show_footer_menu', array('type' =>'select', 'options' => $managemenufooter,'label'=>false));?>
				<?=$this->Form->error('show_footer_menu',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
				</dd>
            <dt>	
                <label>Meta Keywords</label>
            </dt>
            <dd>
                <?=$this->Form->textarea('page_metakeyword',array('class'=>'small','style'=>'height:100px;width:300px','required'=>false));?>
                <?=$this->Form->error('page_metakeyword',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
            </dd>
            
            <dt>
                <label>Meta Description</label>
            </dt>
            <dd>
                <?=$this->Form->textarea('page_metadescription',array('class'=>'small','style'=>'height:100px;width:300px','required'=>false));?>
                <?=$this->Form->error('page_metadescription',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
            </dd>
            
            <dt>
                <label>Page Short Description</label>
            </dt>
            
            <dd>
                <?php 	
                    echo $this->Form->textarea('page_shortdescription', array('cols' => '60', 'rows' => '3'));
                   // echo $fck->load('Page.content');
                ?>
                <?=$this->Form->error('page_shortdescription',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                <div class="float_left"><a href="Javascript:void(0);" onclick="removeeditor(0)">hide editor</a> |
                <a href="Javascript:void(0);" onclick="addeditor(0,'PagePageShortdescription')">show editor</a>
                </div>
            </dd>
            
            <dt>
                <label>Page Long Description</label>
            </dt>
            
            <dd>
                <?php 	
                    echo $this->Form->textarea('page_longdescription', array('cols' => '60', 'rows' => '3'));
                   // echo $fck->load('Page.content');
                ?>
                <?=$this->Form->error('page_longdescription',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                <div class="float_left"><a href="Javascript:void(0);" onclick="removeeditor(1)">hide editor</a> |
                <a href="Javascript:void(0);" onclick="addeditor(1,'PagePageLongdescription')">show editor</a>
                </div>
            </dd>
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Page']['id']) && $this->request->data['Page']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'pages', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>

 <script type="text/javascript">
     <?php $path = $this->Html->webroot; ?>
     var fckeditor = new Array;
       addeditor(0,'PagePageShortdescription'); 
       addeditor(1,'PagePageLongdescription');
     
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
		$('#PageCms').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('form-error');
                });
            $('.error-message').remove();
            $('#PageCms > span#for_owner_cms').show();
            $('#PageCms > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>content_manager/pages/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
					 
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							
							$('#'+i).addClass("form-error").after('<span class="error-message">'+v+'</span>');
                            
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                   }


            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#PageCms > button[type=submit]').attr({'disabled':false});
               $('#PageCms > span#for_owner_cms').hide();
            }
           
          
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
