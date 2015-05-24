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
                document.getElementById('MemberDeleteForm').submit();
        }
    }

    function validate() {
        var ans = "0";
        for (i = 0; i < document.member.elements.length; i++) {
            if (document.member.elements[i].type == "checkbox") {
                if (document.member.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select member(s) to " + document.getElementById('action').value);
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + document.getElementById('action').value + ' Member(s)');
            if (!answer)
                return false;
        }
        return true;
    }


    function CheckAll(chk)
    {
        var fmobj = document.getElementById('MemberDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('MemberCheck').checked;
        }

    }
    
</script>

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
        
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>'member_manager','controller'=>'members','action'=>'index',$search,'page:'));?>'+page,
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
            <h2>Member Manager</h2>
            <div style="float:right;">
                <a href="javascript:" onClick="return formsubmit('Activate');" class="button">Activate</a>
                <a href="javascript:" onClick="return formsubmit('Deactivate');" class="button">Deactivate</a>
                <a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>
                <?php echo $this->Html->link('New', array('controller' => 'members', 'action' => 'add'), array('escape' => false, 'class' => 'button')); ?>
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
    <?=$this->Form->create('Member', array('name' => 'member', 'action' => 'delete/' , 'id' => 'MemberDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            <th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
            <th width="5%">SNo.</th>
			<th width="12%">First Name</th>
			<th width="12%">Last Name</th>
			<th width="21%">Email</th>
			<th width="20%">Contact No.</th>
			<th width="10%">Activate</th>
			<th width="15%">Actions</th>
        </tr>
        <tr>
            <td colspan="9">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($members as $member) {
                ?>
				<li id="sort_<?=$member['Member']['id'];?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $this->Form->checkbox('Member.id.'.$i, array('value' => $member['Member']['id'])); ?></td>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="12%"><?=$member['Member']['first_name'];?></td>
							<td width="12%"><?=$member['Member']['last_name'];?></td>
							<td width="21%"><?=$member['Member']['email_id'];?></td>
							<td width="20%"><?=$member['Member']['phone'];?></td>
							<td width="10%">
							<?php if($member['Member']['active']=='1') 
									echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
									echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
							</td>
							<td width="15%">
								<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('controller' => 'members', 'action' => 'add', $member['Member']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Member', 'rel' => 'tooltip')); ?></li>
									<li>
									<?=$this->Html->link('view', array('controller' => 'members', 'action' => 'view', $member['Member']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
									
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
        <td colspan="9" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="9">
                    <?php if (!$members) { ?>
                    <div style='color:#FF0000'>No Record Found</div>
                     <?php } else { ?>
							 <noscript>
                            <ul class="pagination">
                             <?php if($this->Paginator->first()){?>
							<li><?php echo $this->Paginator->first('« First',array('class'=>'button gray')); ?></li>
							<?php } ?>
							
							<?php if($this->Paginator->hasPrev()){?>
							<li><?php echo $this->Paginator->prev('< Previous',array('class'=>'button gray'), null, array('class'=>'disabled'));?>&nbsp;... &nbsp;</li>
							<?php } ?>
							
							<?=$this->Paginator->numbers(array('modulus'=>6,'tag'=>'li','class'=>'','separator'=>'')); ?>
                            <?php if($this->Paginator->hasNext()){?>
                            <li>&nbsp;... &nbsp;<?php echo $this->Paginator->next('Next >',array('class'=>'button gray'));?></li>
							<?php } ?>
							<?php if($this->Paginator->last()){?>
							<li><?php echo $this->Paginator->last('Last »',array('class'=>'button gray')); ?></li>
							<?php } ?>
                             </ul>
                             </noscript> 
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
                    url: "<?php echo Router::url(array('plugin'=>'member_manager','controller'=>'members','action'=>'ajax_sort','admin'=>false)); ?>",
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
