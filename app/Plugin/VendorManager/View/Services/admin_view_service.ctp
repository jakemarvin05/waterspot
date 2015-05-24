<div style="padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Service Title</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$this->request->data['Service']['service_title'];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Price</b></div>
		<div align="justify;" style="float:left; width:400px;">$<?=$this->request->data['Service']['service_price'];?></div>
		<div style="clear:both;"></div>

	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Full Day Price</b></div>
		<div align="justify;" style="float:left; width:400px;">$<?=$this->request->data['Service']['full_day_amount'];?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>No of Person</b></div>
		<div align="justify;" style="float:left; width:400px;"> <?=$this->request->data['Service']['no_person'];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Description</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$this->request->data['Service']['description'];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Itinerary</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$this->request->data['Service']['itinerary'];?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>How to get there reviews</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$this->request->data['Service']['how_get_review'];?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Service Type</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$service_types[$this->request->data['Service']['service_type_id']];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Image</b></div>
		<div align="justify;" style="float:left; width:400px;" class="admin-vendorservicelist">
			<?php foreach($this->request->data['ServiceImage'] as $image){
				/* Resize Image */
				if(isset($image['image'])) {
					$imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$image['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
					$resizedImg = $this->ImageResize->ResizeImage($imgArr);
					echo $this->Html->image($resizedImg,array('border'=>'0'));
				}
			}
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Value Added Service</b></div>
		 
		<div align="justify;" style="float:left; width:400px;">
			<table>
				<tr>
					<th>VAS Name</th>
					<th>Price(<?=Configure::read('currency'); ?>)</th>
				</tr>
				
			<?php foreach($this->request->data['ValueAddedService'] as $vas){ ?>
				<tr>
					<td><?=$vas['value_added_name'] ?></td>
					<td><?=number_format($vas['value_added_price'],2) ?></td>
				</tr>
			 
			
			<? } ?>
			</table>
		</div>
		<div style="clear:both;"></div>
		
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Slot Timing</b></div>
		 
		<div align="justify;" style="float:left; width:400px;">
			
			<?php foreach($this->request->data['ServiceSlot'] as $slot){ 
				 echo $this->Time->meridian_format($slot['start_time']). " To ".$this->Time->end_meridian_format($slot['end_time']);
				 echo "<br>";
			 } ?>
			
		</div>
		<div style="clear:both;"></div>
		
	</div>
</div>
 
