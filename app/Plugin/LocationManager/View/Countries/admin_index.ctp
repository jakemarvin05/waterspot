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
                document.getElementById('CountryDeleteForm').submit();
        }
    }

    function validate() {
        var ans = "0";
        for (i = 0; i < document.country.elements.length; i++) {
            if (document.country.elements[i].type == "checkbox") {
                if (document.country.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select countries to " +  (document.getElementById('action').value).toLowerCase());
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + (document.getElementById('action').value).toLowerCase() + ' country(s)');
            if (!answer)
                return false;
        }
        return true;
    }


    function CheckAll(chk)
    {
//alert(document.getElementById('PageCheck').checked);
//alert(document.getElementsByTagName('checkbox').length);
        var fmobj = document.getElementById('CountryDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('CountryCheck').checked;
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
                        url:'<?=Router::url(array('plugin'=>'location_manager','controller'=>'countries','action'=>'index',$search,'page:'));?>'+page,
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
            <h2>Country Manager
				<?php if(!empty($parent_detail)){ ?>
				 [<?=$parent_detail['Country']['name']?>]
				<?php } ?>
            
            </h2>
            <div style="float:right;">
                <a href="javascript:" onClick="return formsubmit('Publish');" class="button">Publish</a>
                <a href="javascript:" onClick="return formsubmit('Unpublish');" class="button">Unpublish</a>
                <a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>
                <?php echo $this->Html->link('New', array('controller' => 'countries', 'action' => 'add'), array('escape' => false, 'class' => 'button')); ?>
            
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
    <?=$this->Form->create('Country', array('name' => 'country', 'action' => 'delete/', 'id' => 'CountryDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            <th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
            <th width="1%">&nbsp;</th>
            <th width="6%">SNo.</th>
            <th width="65%">Title</th>
            <th width="10%">Publish</th>
            <th width="50%">Action</th>
        </tr>
        <tr>
            <td colspan="6">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($countries as $country) {
                ?>
                    <li id="sort_<?= $country['Country']['id'] ?>"  style="cursor:move" >
                        <table width="100%">
                            <tr>
                                <td width="5%"><?php echo $this->Form->checkbox('Country.id.'.$i, array('value' => $country['Country']['id'])); ?></td>
                                <td width="6%"><?php echo $i++; ?></td>
                                <td width="65%"><?php echo $country['Country']['name']; ?></td>
                                <td width="10%">
                                <?php
                                if ($country['Country']['status'] == '1')
                                    echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
                                    echo $this->Html->image('admin/icons/icon_error.png', array());
                                ?>
                                </td>
                                <td width="50%">
                                    <ul class="actions">
										<li><?php echo $this->Html->link('edit', array('controller' => 'countries', 'action' => 'add',$country['Country']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Country', 'rel' => 'tooltip')); ?></li>
									
                                        <li><?php echo $this->Html->link('Manage City', array('controller' => 'cities', 'action' => 'index', $country['Country']['id']), array('escape' => false, 'class' => 'subcontent', 'title' => 'Manage City', 'rel' => 'tooltip')); ?>
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
                    <?php if (!$countries) { ?>
                    <div style='color:#FF0000'>No Record Found</div>
                    <?php } else {?>
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
<script type="text/javascript">
$(document).ready(function(){
        $( ".Main" ).sortable({
                placeholder: "ui-state-highlight",
                opacity: 0.6,
            update: function(event, ui) {
                var info = $(this).sortable("serialize");
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('plugin'=>'location_manager','controller'=>'countries','action'=>'ajax_sort','admin'=>false)); ?>",
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
