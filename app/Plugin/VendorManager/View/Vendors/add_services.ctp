<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="../js/editor/ckeditor.js"></script>
<script src="../js/editor/adapters/jquery.js"></script>
<link href="../js/editor/samples/sample.css" rel="stylesheet">
<script>
	CKEDITOR.disableAutoInline = true;
	$( document ).ready( function() {
		$( '#VendorDescription' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
			$( '#editable' ).ckeditor(); // Use CKEDITOR.inline().
		} );
</script>

<? $services=array('1'=>'service1','2'=>'service2','3'=>'service3');?>
<? $locations=array('1'=>'location1','2'=>'location1','3'=>'location3');?>
<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
//set a counter
var i = $('#add_services :input').length + 1;

//add input
$('a#add_btn').click(function () {
    $('<div class="add-values"><label></label><input name="data[Vendor][value_add_service][]" class="add-service" type="text" ><input name="data[Vendor][value_add_price][]" class="enter-price" type="text" >' +
        '<a class="dynamic-link" id="add_btn" href="#step2">Remove</a></div>').fadeIn("slow").appendTo('#extender');
    i++;
    return false;
});


//fadeout selected item and remove
$("#add_services").on('click', '.dynamic-link', function () {
    $(this).parent().fadeOut(300, function () {
        $(this).empty();
        return false;
    });
});

});//]]>  

</script>

<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"><a href="index.html">Home</a> &raquo; Add Services</div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Add <span style="color:#000;">Services</span></h2>
<div class="middle-area">
	<?=$this->Form->create('Vendor',array('class'=>'add-services','id'=>'add_services','action'=>'add_services'));?>
	
	<div class="form-row"><label>Select your services: </label>
	
		<?=$this->Form->input('service_id',array('type' =>'select', 'options' => $services,'label'=>false));?>	
		<?=$this->Form->error('service_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
	</div>
    <div class="form-row"><label>Description:</label>
		<div class="description"> <?=$this->Form->textarea('description', array('cols' => '60', 'rows' => '3'));
		// echo $fck->load('Page.content');?>
		<?=$this->Form->error('description',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
	</div>
    <div class="form-row"><label>Price: </label>
		<?=$this->Form->input('service_price',array('label'=>false,'div'=>false,'class'=>'enter-price'));?>
		<?=$this->Form->error('service_price',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
    </div>
    <div class="form-row"><label>Location: </label>
        <?=$this->Form->input('location_id',array('type' =>'select', 'options' => $locations,'label'=>false));?>
        
		<?=$this->Form->error('location_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
    </div>
    <div class="form-row" id="p_scents"><label>Value Added Services: </label>
		<?=$this->Form->input('value_add_service',array('label'=>false,'div'=>false,'class'=>'add-service'));?>
		<?=$this->Form->error('value_add_service',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		<?=$this->Form->input('value_add_price',array('label'=>false,'div'=>false,'class'=>'enter-price'));?>
		<?=$this->Form->error('value_add_price',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		
    <div class="add-value"><?=$this->Html->image('add-servces.png');?></div>
    <div class="add-values"><a id="add_btn" href="#">Add</a></div>
    <div id="extender"></div>
	</div><div class="clear"></div>
      <input class="smt2" value="Submit" type="submit">
		<?php echo $this->Form->end();?>
        </div>
</div>
      
<div class="clear"></div>
      
  
  </div>

</div>  
