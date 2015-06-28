

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
                        url:'<?=Router::url(array('plugin'=>false,'controller'=>'carts','action'=>'abandon_cart','page:'));?>'+page,
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
   <?=$this->Form->create('Abandon',array('class'=>'query-from','id'=>'abondon','action'=>'admin_abandon_cart','novalidate' => true));
	?>
    <article>
		<header>
			<h2>Abandon Cart</h2>
		</header>
	</article>
     
    <table width="100%">
        <tr>
            
            <th width="5%">SNo.</th>
			<th width="20%">Email</th>
			<th width="20%">Vendor Name</th>
			<th width="20%">Service Name</th>
			<th width="15%">Date</th>
	    </tr>
         
        <tr>
            <td colspan="5">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                if(!empty($abandon_carts)) {
                    foreach ($abandon_carts as $abandon_cart) {
                ?>
				<li>
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="20%"><a href="mailto:<?=$abandon_cart['Cart']['guest_email']?>"><?=$abandon_cart['Cart']['guest_email']?> </a></td>
							<td width="20%"><?= ucfirst($abandon_cart['Cart']['vendor_name'])?></td>
							<td width="20%"><?=$abandon_cart['Cart']['service_title']?></td>
							<td width="15%"><?=date(Configure::read('Calender_format_php'),strtotime($abandon_cart['Cart']['time_stamp'])); ?>
							</td>
						</tr>
					</table>
				</li>
				<?php } } ?>
                </ul>
            </td>
        </tr>
    <tr>
        <td colspan="5" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="4">
                    <?php if (!$abandon_carts) { ?>
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
