<script type="text/javascript">
    function formsubmit(action)
    {
        var flag = true;
        if (flag)
        {
            document.getElementById('action').value = action;
            if (validate())
                document.getElementById('ServiceTypeDeleteForm').submit();
        }
    }

    function validate() {
        var ans = "0";
        for (i = 0; i < document.servicetypes.elements.length; i++) {
            if (document.servicetypes.elements[i].type == "checkbox") {
                if (document.servicetypes.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select service type to " + document.getElementById('action').value);
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + document.getElementById('action').value + ' Service Type(s)');
            if (!answer)
                return false;
        }
        return true;
    }


    function CheckAll(chk)
    {
        var fmobj = document.getElementById('ServiceTypeDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('ServiceCheck').checked;
        }

    }
    
</script>

<!-- END BROWSERIE -->
<!-- BEGIN BROWSERMOZ -->
<style type="text/css">
    ul.Main {
        list-style-type: none;
    }
    .ui-state-highlight { height: 30px; line-height: 25px; }
    /*
    ul.Main {
        list-style-type: none;
        margin-left:-40px;
        margin-top:-1px;
    }
    ul li.Main2 {
        color:#000000;
        border: 1px solid #cccccc;
        cursor: move;
        margin-bottom: 0px;
        background:  #FFFFFF;
        border: 1px solid #efefef;
        /*width: 763px; *
        text-align: left;
        font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;
    }	
    ul li.Main3 {
        color:#000000;
        border: 1px solid  #FFE8E8;
        cursor: move;
        margin-bottom: 0px;
        background: #FFE8E8;
        border: 1px solid #efefef;
        width: 763px;
        text-align: left;
        font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;

    }
.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
*/
</style>

<script type='text/javascript'>
    $(function(){
      //Keep track of last scroll
      var lastScroll = 0;
      var loading_start = 0;
      var page = <?=$this->paginator->counter('{:page}')?>;
      var pages = <?=$this->paginator->counter('{:pages}')?>;
      //var services = <?=$this->paginator->counter('services')?>;
     // var services = <?=$this->paginator->counter('services')?>;
      $(window).bind('scroll',function(event){
           //Sets the current scroll position
          var st = $(this).scrollTop();
          var win_height = $(this).height();
          var doc_height = $(document).height();
          var scrollBottom = doc_height - win_height - st;
          var scroll_value=200;
		    
       
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'index',$search,'page:'));?>'+page,
                        async:false,
                        success:function(data){
                            $('ul.Main').append(data);
                            loading_start = 0;
                            $('#loader_pagination').hide();
                        }
                    });
                }
            }
        
            
          lastScroll = st;
          
          
      });
      
    });
</script>

<div>
    <article>
        <header>
            <h2>Service Type Manager</h2>
            <div style="float:right;">
                <a href="javascript:" onClick="return formsubmit('Publish');" class="button">Publish</a>
                <a href="javascript:" onClick="return formsubmit('Unpublish');" class="button">Unpublish</a>
                <a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>
                <?php echo $this->Html->link('New', array('controller' => 'service_types', 'action' => 'add'), array('escape' => false, 'class' => 'button')); ?>
            </div>
        </header>
    </article>
   
    <form method="post">
    <div class="input text" style="overflow:hidden;">
Search:
<input id="search" type="text" style="margin-left:10px; margin-right:10px;" name="search" value="<?=$search?>" />

<button type="submit" style="margin-top:10px; margin-left:10px" >search</button>
    </div>
    </form>
    <?php echo $this->element('admin/message'); ?>
    <?=$this->Form->create('ServiceType', array('name' => 'servicetypes', 'action' => 'delete' , 'id' => 'ServiceTypeDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            <th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
            <th width="5%">SNo.</th>
            <th width="35%">Title</th>
            <th width="30%">Image</th>
            
            <th width="10%">Publish</th>
            <th width="15%">Actions</th>
        </tr>
        <tr>
            <td colspan="7">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                 foreach ($services as $service) {
				?>
                    <li id="sort_<?= $service['ServiceType']['id'] ?>"  style="cursor:move" >
                        <table width="100%">
                            <tr>
                                <td width="5%"><?php echo $this->Form->checkbox('ServiceType.id.'.$i, array('value' => $service['ServiceType']['id'])); ?></td>
                                <td width="5%"><?php echo $i++; ?></td>
                                <td width="35%"><?php echo $service['ServiceType']['name']; ?></td>
                                <td width="30%"><? 
								$imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$service['ServiceType']['image'],'width'=>110,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
								$resizedImg = $this->ImageResize->ResizeImage($imgArr);
								echo $this->Html->image($resizedImg,array('border'=>'0'));
								?>
								</td>
                                <td width="10%">
                                <?php
                                if ($service['ServiceType']['status'] == '1')
                                    echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
                                    echo $this->Html->image('admin/icons/icon_error.png', array());
                                ?>
                                </td>
                                <td width="15%">
                                    <ul class="actions">
                                        <li><?php echo $this->Html->link('edit', array('controller' => 'service_types', 'action' => 'add', $service['ServiceType']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Service', 'rel' => 'tooltip')); ?></li>
                                        <li><?php echo $this->Html->link('edit', array('controller' => 'service_types', 'action' => 'add_attribute', $service['ServiceType']['id']), array('escape' => false, 'class' => 'add_template', 'title' => 'Add Service Attibute', 'rel' => 'tooltip')); ?></li>
                                        <li>
                                        <?=$this->Html->link('view', array('controller' => 'service_types', 'action' => 'view', $service['ServiceType']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
                                        
                                        </li>
                                    											
                                    </ul >


                                </td> 
                            </tr>
                        </table>
                    </li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
    <tr>
        <td colspan="7" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="7">
                    <?php if (!$services) { ?>
                    <div style='color:#FF0000'>No Record Found</div>
                    <?php } ?>
                     

                </td>
            </tr>
        </tfoot>

    </table>
</form>

</div>
<script type="text/javascript">
$(document).ready(function(){
        $( ".Main" ).sortable({
                placeholder: "ui-state-highlight",
                opacity: 0.6,
            update: function(event, ui) {
                var info = $(this).sortable("serialize");
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'ajax_sort','admin'=>false)); ?>",
                    data: info,
                    context: document.body,
                    success: function(){
                       
                    }
              });
            }
        });
        $( ".Main" ).disableSelection();         
    });
</script>
