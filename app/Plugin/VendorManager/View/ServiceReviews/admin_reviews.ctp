<script type="text/javascript">
    function formsubmit(action)
    {
        //alert(action);
        var flag = true;
//	if(action=='Delete')
        //flag=confirm('Are You Sure, You want to Delete this review(s)!');
        if (flag)
        {
            document.getElementById('action').value = action;
            if (validate())
                document.getElementById('ServiceReviewDeleteForm').submit();
        }
    }

    function validate() {
        var ans = "0";
        for (i = 0; i < document.ServiceReview.elements.length; i++) {
            if (document.ServiceReview.elements[i].type == "checkbox") {
                if (document.ServiceReview.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select service review(s) to " + document.getElementById('action').value.toLowerCase());
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + document.getElementById('action').value.toLowerCase() + ' service review(s)');
            if (!answer)
                return false;
        }
        return true;
    }


    function CheckAll(chk){
		//alert(document.getElementById('ServiceReviewCheck').checked);
		//alert(document.getElementsByTagName('checkbox').length);
        var fmobj = document.getElementById('ServiceReviewDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('ServiceReviewCheck').checked;
        }

    }
    
</script>

<!-- END BROWSERIE -->
<!-- BEGIN BROWSERMOZ -->
<style type="text/css">
    ul.Main {
        list-style-type: none; margin-left:0;
    }
    .ui-state-highlight { height: 30px; line-height: 25px; }
    /*
    ul.Main {
        list-style-type: none;
        margin-left:-40px;
        padding:0;
        margin-top:-1px;
        margin-left:0;
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
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$vendor_id,$service_id,$search,'page:'));?>'+page,
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
            <h2>Service Review Manager
				<?php if(!empty($parent_detail)){ ?>
				 [<?=$parent_detail['ServiceReview']['name']?>]
				<?php } ?>
            
            </h2>
            <div style="float:right;">
               
                 <a href="javascript:" onClick="return formsubmit('Approve');" class="button">Approve</a>
                <a href="javascript:" onClick="return formsubmit('Disapprove');" class="button">Disapprove</a>

                <a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>
             
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
    <?=$this->Form->create('ServiceReview', array('name' => 'ServiceReview', 'action' => 'delete/' , 'id' => 'ServiceReviewDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            <th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
            <th width="1%">&nbsp;</th>
            <th width="4%">SNo.</th>
            <th width="20%">Service Name</th>
            <th width="20%">Member Name</th>
            <th width="20%">Vendor Name</th>
             
            <th width="15%">Date</th>
            <th width="5%">Status</th>
            <th width="15%">Actions</th>
        </tr>
        <tr>
            <td colspan="10">
                <ul class="Main">
                <?php
				$i = $this->paginator->counter('{:start}');
                    //$i = 0;
                foreach ($service_reviews as $review) {?>
                    <li id="sort_<?= $review['ServiceReview']['id'] ?>">
                        <table width="100%">
                            <tr>
                                <td width="5%"><?php echo $this->Form->checkbox('ServiceReview.id.'.$i, array('value' => $review['ServiceReview']['id'])); ?></td>
                                <td width="1%">&nbsp;</td>
                                <td width="4%"><?php echo $i++; ?></td>
                                <td width="20%"><?php echo $review['Service']['service_title']; ?></td>
                                <td width="20%"><?php echo ucfirst($review['Member']['first_name']." ".$review['Member']['last_name']); ?></td>
                                <td width="20%"><?php echo ucfirst($review['Vendor']['fname']." ".$review['Vendor']['lname']); ?></td>
                                <td width="15%">
									<?=date(Configure::read('Calender_format_php'),strtotime($review['ServiceReview']['date'])); ?>
									 
                                </td>
                                <td width="5%">
									<?php if($review['ServiceReview']['status']=='1') 
									echo $this->Html->image('admin/icons/icon_success.png', array());
									else
						 				echo $this->Html->image('admin/icons/icon_error.png', array());
									?>
								</td>
                                <td width="15%">
                                    <ul class="actions">
                                        <li><?php echo $this->Html->link('edit', array('controller' => 'service_reviews', 'action' => 'add',$review['ServiceReview']['vendor_id'],$review['ServiceReview']['service_id'],$review['ServiceReview']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Review', 'rel' => 'tooltip')); ?></li>
									    <li>
                                        <li>
                                        <?=$this->Html->link('view', array('controller' => 'service_reviews', 'action' => 'view', $review['ServiceReview']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
                                        
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
        <td colspan="10" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="9">
                    <?php if (!$service_reviews) { ?>
                    <div style='color:#FF0000'>No Record Found</div>
                    <?php }else{ ?>
                    <noscript>
                    <ul class="pagination">
							
						<?php if($this->Paginator->first()){?>
						<li><?php echo $this->Paginator->first('« First',array('class'=>'')); ?></li>
						<?php } ?>
						
						<?php if($this->Paginator->hasPrev()){?>
						<li><?php echo $this->Paginator->prev('< Previous',array('class'=>''), null, array('class'=>'disabled'));?>&nbsp;... &nbsp;</li>
						<?php } ?>
						
						<?=$this->Paginator->numbers(array('modulus'=>6,'tag'=>'li','class'=>'','separator'=>'')); ?>
						<?php if($this->Paginator->hasNext()){?>
						
						<li><?php echo $this->Paginator->next('Next >',array('class'=>''));?></li>
						<?php } ?>
						<?php if($this->Paginator->last()){?>
						<li><?php echo $this->Paginator->last('Last »',array('class'=>'')); ?></li>
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
