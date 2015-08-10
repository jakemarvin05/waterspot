<?php print_r($site_setting); die;?>
<script language="javascript">
function validatefields(val)
{
	if(document.getElementById('PageName').value==''){
		alert("Please Enter The Title");
		document.getElementById('PageName').focus();
		return false;
	}
	if(document.getElementById('PagePageTitle').value==''){
		alert("Please Enter The Page Title");
		document.getElementById('PagePageTitle').focus();
		return false;
	}	
}
function saveform()
{
	document.getElementById('PagePublish').value=1;
	document.getElementById('PageCms').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>
                <?php
                    if ($this->request->data['Page']['id']):
                          echo  __('Update Content');
                    else:
                          echo  __('Add Content');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php 
        if($this->request->data['Page']['id'])$act='edit';
        else $act='add';
        $act=$act.'/'.$parentId;
    ?>
    <?php echo $this->Form->create('Page',array('name'=>'pages','id'=>'PageCms','action'=>$act,'onsubmit'=>'return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    <?php echo $this->Form->hidden('parentId', array('value'=>$parentId)); ?>
    
    <fieldset>
        <dl>
            <dt>
                <label>Title <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('name',array('class'=> 'small','size'=>'45')); ?>
            </dd>
            
            <dt>
                <label>Page Title <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('page_title',array('class'=> 'small','size'=>'45')); ?>
            </dd>
            <dt>
                <label>Meta Keywords</label>
            </dt>
            <dd>
                <?=$this->Form->textarea('metaKeyword',array('class'=>'small','style'=>'height:100px;width:300px'));?>
            </dd>
            
            <dt>
                <label>Meta Description</label>
            </dt>
            <dd>
                <?=$this->Form->textarea('metaDescription',array('class'=>'small','style'=>'height:100px;width:300px'));?>
            </dd>
            
            <dt>
                <label>Page Content</label>
            </dt>
            
            <dd>
                <?php 	
                    echo $this->Form->textarea('content', array('cols' => '60', 'rows' => '3'));
                   // echo $fck->load('Page.content');
                ?>
            </dd>
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if ($this->request->data['Page']['id']):
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
    $(document).ready(function(){
        $('#PageCms').submit(function(){
            
           var data = new FormData(this);
           var formData = $(this);
           var status = 0;
           
            $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                
            });
            $('.error-message').remove();
             $('#PageCms > span#for_owner_cms').show();
            $('#PageCms > button[type=submit]').attr({'disabled':true});
           
        
            $.ajax({
                url: '<?=$site_setting['siteurl']?>/content_manager/pages/ajax_check_validation',
                async: false,
                processData: false,
                contentType: false,
                data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
					 
                    if(data.error==1){
                        $.each(data.errors,function(i,v){
							//alert(i);
                            $('#'+i).addClass("invalid form-error").after('<span class="error-message">'+v+'</span>');
                            //alert(formData.data[Owner][i].value);
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                    //alert(data);
                   }


            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#PageCms > button[type=submit]').attr({'disabled':false});
               $('#PageCms > span#for_owner_cms').hide();
            }
          // return false;
           return (status===1)?true:false; 
            
        });
        
        
    });
    </script>
