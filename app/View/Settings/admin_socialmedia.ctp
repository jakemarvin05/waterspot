<script type="text/javascript">
    function formsubmit(action)
    {
        //alert(action);
        var flag = true;
//	if(action=='Delete')
        //flag=confirm('Are You Sure, You want to Delete this Page(s)!');
        if (flag)
        {
            document.getElementById('action').value = action;
            if (validate())
                document.getElementById('SocialDeleteForm').submit();
        }
    }

    function validate() {
        var ans = "0";
        for (i = 0; i < document.socialurl.elements.length; i++) {
            if (document.socialurl.elements[i].type == "checkbox") {
                if (document.socialurl.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select testimonials to " + document.getElementById('action').value);
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + document.getElementById('action').value + ' Link (s)');
            if (!answer)
                return false;
        }
        return true;
    }


    function CheckAll(chk)
    {
//alert(document.getElementById('PageCheck').checked);
//alert(document.getElementsByTagName('checkbox').length);
        var fmobj = document.getElementById('SocialDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('SocialCheck').checked;
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
      var socialurl = <?=$this->paginator->counter('social')?>;
      var socialurls = <?=$this->paginator->counter('socialurls')?>;
      $(window).bind('scroll',function(event){
           //Sets the current scroll position
          var st = $(this).scrollTop();
          var win_height = $(this).height();
          var doc_height = $(document).height();
          var scrollBottom = doc_height - win_height - st;
          var scroll_value=200;
		    
//alert(sta+'--'+st);
            
          //Determines up-or-down scrolling
          //alert(doc_height+'--'+(st+win_height));
          //if((st > lastScroll) && ((doc_height-100) < (st+win_height)) ){
        
            if((scrollBottom <= scroll_value) && (socialurls >= (socialurl+1))){
                if(loading_start===0){
                    loading_start = 1;
                    socialurl++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('controller'=>'sitesettings','action'=>'index',$search,'search:'));?>'+socialurl,
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
            <h2>Social Link Manager</h2>
            <div style="float:right;">
                <a href="javascript:" onClick="return formsubmit('Publish');" class="button">Publish</a>
                <a href="javascript:" onClick="return formsubmit('Unpublish');" class="button">Unpublish</a>
                <a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>
                <?php echo $this->Html->link('New', array('controller' => 'sitesetting', 'action' => 'add'), array('escape' => false, 'class' => 'button')); ?>
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
    <?=$this->Form->create('', array('name' => 'socialmedia', 'action' => 'delete', 'id' => 'SocialDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            <th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
            <th width="1%">&nbsp;</th>
            <th width="6%">SNo.</th>
            <th width="30%">Link Name</th>
            <th width="35%">Description</th>
            <th width="50%">Actions</th>
        </tr>
        <tr>
            <td colspan="6">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                 foreach ($socialurls as $socialurl) {
                ?>
                    <li id="sort_<?= $socialurl['SiteSetting']['id'] ?>"  style="cursor:move" >
                        <table width="100%">
                            <tr>
                                <td width="5%"><?php echo $this->Form->checkbox('SiteSetting.id.'.$i, array('value' => $socialurl['SiteSetting']['id'])); ?></td>
                                <td width="6%"><?php echo $i++; ?></td>
                                <td width="30%"><?php echo $socialurl['SiteSetting']['key']; ?></td>
                                <td width="35%">
                                <?php echo $socialurl['SiteSetting']['values']; ?>
                                </td>
                                <td width="50%">
                                    <ul class="actions">
                                        <li><?php echo $this->Html->link('edit', array('controller' => 'sitesettings', 'action' => 'add', $socialurl['SiteSetting']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Link', 'rel' => 'tooltip')); ?></li>
                                        <li>
                                        <?=$this->Html->link('view', array('controller' => 'settings', 'action' => 'view', $socialurl['SiteSetting']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
                                        
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
        <td colspan="6" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="7">
                    <?php if (!$socialurls) { ?>
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
                    url: "<?php echo Router::url(array('controller'=>'settings','action'=>'ajax_sort_link','admin'=>false)); ?>",
                    data: info,
                    context: document.body,
                    success: function(){
                        
                        // alert("cool");
                    }
              });
            }
        });
        $( ".Main" ).disableSelection();         
    });
</script>
