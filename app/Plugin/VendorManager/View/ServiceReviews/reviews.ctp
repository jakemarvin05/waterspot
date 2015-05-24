<div class="wrapper">
	<div class="hr-line"></div>
	<div class="clear"></div>
	<?=$this->element('breadcrumbs');?>
	<h2 class="page-title">Service Reviews</h2>
	<?=$this->element('VendorManager.left-vendor-panel');?>
	<div class="right-area">

		<div class="service">
			<?=$this->element('message');?>
			<h3 class="dashboard-heading">Review Search</h3>
			<?=$this->element('message');?>
			<?=$this->Form->create('ServiceReview',array('class'=>'dashboard-form','action'=>'reviews',$service_id,'novalidate' => true)); ?>
				<?=$this->Form->input('searchtext',array('div'=>false,'label'=>false,'Placeholder'=>'Type your message here...')); ?>
				<input type="submit" value="Search" class="dashboard-buttons">
			<?=$this->Form->end();?>
			
		<div class="clear"></div>
		 
		<table id="service_reviews" width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
			<tr>
				<th width="5%">S.No.</th>
				<th width="40%">Service Name</th>
				<th width="30%">Member Name</th>
				<th width="10%">Date</th>
				<th width="5%">Status</th>
				<th width="5%">Action</th>
			</tr>
			<? $i = $this->Paginator->counter('{:start}'); ?>
			<? if(!empty($service_reviews)){ 
				foreach($service_reviews as $review){ ?>
					<tr>
						<td class="align-center"><?php echo $i++; ?></td>
						<td><?php echo $review['Service']['service_title']; ?></td>
						<td><?php echo ucfirst($review['Member']['first_name']." ".$review['Member']['last_name']); ?></td>
						<td><?=date(Configure::read('Calender_format_php'),strtotime($review['ServiceReview']['date'])); ?></td>
						<td class="align-center">
							<?php if($review['ServiceReview']['status']=='1') 
								echo $this->Html->image('admin/icons/icon_success.png', array());
								else 
									echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
						</td>
						<td class="align-center"><?=$this->Html->link('View', array('controller' => 'service_reviews', 'action' => 'view', $review['ServiceReview']['id']), array('escape' => false,'class'=>'dashboard-links fancybox fancybox.iframe','title'=> __('View'),'rel'=>'tooltip'))?></td>
					</tr>
				<? } ?>
			<? } else {?>
				<tr class="no-details">
					<td colspan="6">No reviews available</td>
				</tr>
			<? }?>
		</table>
		
		<div class="loader_pagination" style="display:none;" ><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div>
		
		<? //pagination 
		 if(!empty($service_reviews)) {?>
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
       
   
  
   <div class="clear"></div>
  
  
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
           //Sets the current scroll position
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
						
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$service_id,$search,'page:'));?>'+page,
                        async:false,
                        success:function(data){
							$('.dashboard-content').append(data);
                            loading_start = 0;
                            $('#loader_pagination').hide();
                        }
                    });
                }
            }
        
            
          lastScroll = st;
          
          
      });
      
    });
    
    
    function AddPlace(id) {
		var selected = $("#"+id+" :selected").text();
		$("#BookingSearchtext").attr("placeholder", "Type your "+selected);
	}
  
    function AddPlace_date(id) {
		var selected = $("#"+id+" :selected").text();
		$("#BookingSearchbydate").attr("placeholder", "Please select "+selected);
	}
  
</script>
<script type="text/javascript">
$( document ).ready(function() {
		$(document).ready(function() {
			$('.fancybox').fancybox({
				showNavArrows: false,
				arrows: false
			});
		});
	});
</script>
