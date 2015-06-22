<div class="hr-line"></div>
  <div class="clear"></div>
  <div class="bredcrum"> <?=$this->element('breadcrumbs');?></div>
  <h2 class="page-title">My Reviews</h2>
 <div class="middle-area">
   
     <div class="service">
     <div class="service-hd">My Reviews</div>
        
       <table width="100%" border="0" cellpadding="0" cellspacing="0" id="reviews">
			<tr class="bg">
					<td width="5%">S.No.</td>
					<td width="45%">Review Messag</td>
					<td width="30%">From</td>
					<td width="15%">Date</td>
					<td width="5%">Delete</td>
					 
			</tr>
		
		<? if(!empty($member_reviews)) {?>
			<? foreach($member_reviews as $key=>$member_review) {?>
			
				<tr>
					<td width="5%" class="border"><?=($key+1)?></td>
					<td width="50%" class="border"><?=$member_review['MemberReview']['member_message']?> </td>
					<td width="30%" class="border"><?=ucfirst($member_review['Member']['first_name']." ".$member_review['Member']['last_name'])?></td>
					<td width="15%" class="border">
						<?=date(Configure::read('Calender_format_php'),strtotime($member_review['MemberReview']['date'])); ?> 
					</td>
					<td width="5%" class="border">
						<?=$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'review_delete',$member_review['MemberReview']['id'],'reviews'),array('escape'=>false,"onclick"=>"return confirm('Are you want to delete this review?')")); ?>  
					</td>
					 
				</tr>
			<? } //end of foreach ?>
		<? } else {?>
			<tr>
				<td colspan="5">
					<div class="serv-msg">There are no review.</div>
							
				</td>
			</tr>
		<? } ?>
				
		</table>
		<div class="loader_pagination" style="display:none;" ><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div>
		
		<? //pagination 
		 if(!empty($member_reviews)) {?>
			<noscript>
				<div class='pag-box'>
					<ul class="pagination">
						<?php if($this->Paginator->first()){?>
							<li><?php echo $this->Paginator->first('<< First',array('class'=>'button gray')); ?></li>
						<?php } ?>
									
						<?php if($this->Paginator->hasPrev()){?>
							<li><?php echo $this->Paginator->prev('< Previous',array('class'=>'button gray'), null, array('class'=>'disabled'));?></li>
						<?php } ?>
						<?=$this->Paginator->numbers(array('modulus'=>7,'tag'=>'li','class'=>'','separator'=>'')); ?>
						<?php if($this->Paginator->hasNext()){?>
							<li><?php echo $this->Paginator->next('Next >',array('class'=>'button gray'));?></li>
						<?php } ?>
						<?php if($this->Paginator->last()){?>
							<li><?php echo $this->Paginator->last('Last >>',array('class'=>'button gray')); ?></li>
							<?php } ?>			  
					</ul>
				</div>
			</noscript>	
		<? }?>
	</div>
	</div>
   
  
   <div class="clear"></div>
  
  
 </div>

<script type='text/javascript'>
    $(function(){
	  //Keep track of last scroll
      var lastScroll = 0;
      var loading_start = 0;
      var page = <?=$this->paginator->counter('{:page}')?>;
      var pages = <?=$this->paginator->counter('{:pages}')?>;
      
      $(window).bind('scroll',function(event){
          var st = $(this).scrollTop();
          var win_height = $(this).height();
          var doc_height = $(document).height();
          var scrollBottom = doc_height - win_height - st;
          var scroll_value=200;
           if(navigator.userAgent.match(/(iPhone)/i)){
			  scroll_value=4500;
			 //alert(doc_height);
		  }
		  //Determines up-or-down scrolling
          //alert(doc_height+'--'+(st+win_height));
          //if((st > lastScroll) && ((doc_height-100) < (st+win_height)) ){
        
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'reviews','page:'));?>'+page,
                        async:false,
                        success:function(data){
                            //$('.ajaxpagination').append(data);
                            $('#reviews').append(data);
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
