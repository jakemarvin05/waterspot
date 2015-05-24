<script type="text/javascript">
	$(function(){
		$('.contentselector').contenthover({
			data_selector: '.contenthover',
			effect:'slide',
			slide_direction: 'left',
			slide_speed:300,
			overlay_opacity: 1
		});
	});
</script>

<div class="hr-line"></div>
<div class="clear"></div>
<div class="search-listing-header">
<?=$this->element('breadcrumbs');?>
<h2 class="page-title"><?=$service_type_details['ServiceType']['name'] ?></h2>
</div>

<div class="search-listing">
	<!-- <div class="search"><span> Search</span><input type="search"></div>-->
	<div class="vendor-left-area">

		<div id='sort_by_price' class="ajax-loder" style="display:none">
			<?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..'));?>
		</div>

		<div class="listing-boxes">
			<? if(!empty($activity_service_list)) { ?>
				<?php $i = $this->paginator->counter('{:start}'); ?>
				<? foreach($activity_service_list as $service_list) { ?>
					<div class="vendorwise-listing">
						<div class="contentvisible contentselector">
							<div class="tile">
								<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
										$imgArr = array('source_path'=>$path,'img_name'=>$service_list['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
										$resizedImg = $this->ImageResize->ResizeImage($imgArr);
										echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service_list['Service']['service_title'])); ?>
							</div>
							<div class="tile-info"> 
								<h4><?=$this->Format->Headingsubstring($service_list['Service']['service_title'],24);?></h4>
								 <div class="activity-rating-wrapper">
									<? if(!empty($service_list['rating'])){ ?>
										<?php $ratings = range(1,10); ?>
										<?php foreach($ratings as $rating){ ?>
											<input type="radio" value="<?php echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($service_list['rating']==$rating)?'checked="checked"':'';?> />
										<?php } ?>
									<? }else{ ?>
									<div class="no-rating">No feedback yet</div>
								<? } ?>	
								</div>	
								<div class="price-start">
									<span>from</span> <br/>$<?= number_format($service_list['Service']['service_price'],2)?>
								</div>
							</div>
						</div>
						<div class="contenthover">
						    <div class="short-desc"> <?=$this->Format->Headingsubstring(strip_tags($service_list['Service']['description']),200);?></div>
						    <a href="/activity/index/<?=$service_list['Service']['id']?>" class="view-description">Book A Spot</a>
						</div>
					</div>
				<?php $i++; ?>
				<? } ?>
			<? } else { ?>
				<div class="sun-text no-record"> There are no record found.</div>
			<? } ?>
		</div>
		
		<div class="load-more-listings">
			<div class="load-more-row">
				<button class="load-more" id="loader_pagination">Load More Results</button>
			</div>
			<div class="load-more-row">
				<?=$this->Html->image('loader-2.gif',array('style'=>'display:none;','alt'=>'Activity Loader','id'=>'loader-image'));?>
			</div>
		</div>

	</div>
	<div class="vendor-right-area">
		<h4>All activities of:</h4>
		<?php 
				/* Resize Image */
					if(isset($service_type_details['ServiceType']['image'])) {
						$imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$service_type_details['ServiceType']['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0',  'alt'=>$service_type_details['ServiceType']['name']));
					}
		?>
		<h6><?=ucfirst($service_type_details['ServiceType']['name']); ?></h6>
		<div class="vendorlisting-vendordesc"><p><?=$service_type_details['ServiceType']['description']?></p></div>
	</div>

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
         
	</div>

	<div class="clear"></div>
</div>

<div class="clear"></div>

 
<script type='text/javascript'>
	var loading_start = 0;
	$(document).ready(function(){
		var page = <?=$this->paginator->counter('{:page}')?>;
		var pages = <?=$this->paginator->counter('{:pages}')?>;
      
		if(page >= pages){
		  $("#loader_pagination").attr("disabled", true);
		  $('#loader_pagination').addClass('no-more-activities').html('No more results');
		
		}else{
			$("#loader_pagination").attr("disabled", false);
			$('#loader_pagination').removeClass('no-more-activities').html('Load More results');
		}
		$('#loader_pagination').bind('click',function(){
			$('#loader-image').show();
			if(pages >= (page+1)){
				 if(loading_start===0){
                    loading_start = 1;
                    page++;
						$.ajax({url:'<?=Router::url(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'service_type_detail',$service_type_id));?>'+'/page:'+page,
                        async:false,
                        timeout:5,
                        success:function(data){
							loading_start = 0;
							$('#loader-image').hide();
							$('.vendorwise-listing:last').after(data );
							$('.contentselector').contenthover({
								data_selector: '.contenthover',
								effect:'slide',
								slide_direction: 'left',
								slide_speed:300,
								overlay_opacity: 1
							});
							
                            if(page >= pages){
								$("#loader_pagination").attr("disabled", true);
								$('#loader_pagination').addClass('no-more-activities').html('No more results');
							}
                            
                        },
                        error: function(jqXHR, textStatus){
							if(textStatus == 'timeout')
							{     
								 alert('Failed from timeout');         
								//do something. Try again perhaps?
							}
						},
                    });
                }
            }
			
		});
	});
	
</script>	
  

