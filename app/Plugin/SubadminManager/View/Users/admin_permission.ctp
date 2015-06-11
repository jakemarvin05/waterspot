<script>
	function show(vals)
	{		
		var val=0;
		for(i=1; i<document.permission.elements.length; i++)
		{
			if(document.permission.elements[i].type=="checkbox"){				
				
				val=(document.permission.elements[i].value);
				if(document.permission.elements[i].checked==true)
				{
					(document.getElementById(val)).style.display="";
				}
				else if(document.permission.elements[i].checked==false){
					(document.getElementById(val)).style.display="none";
					
				}
			}
		}
	}
	
</script>
<body onload="show()"></body>
<div>
        <article>
		<header>
			<h2>Permission Manager [<?=$user_name?>]</h2>
		</header>
        </article>
	
        <?php echo $this->element('admin/message');?>
                
	<?php echo $this->Form->create('User',array('name'=>'permission','url'=>'permission/'.$user_id,'id'=>'UserDeleteForm','onSubmit'=>'return validate(this)','class'=>'table-form'));?>
	<?php echo $this->Form->hidden('user_id',array('value'=>$user_id)); ?>
	
		<fieldset>
			<table width="100%">
				<tr>
					<th width="40%">Manager</th>
					<th width="10%"></th>
					<th width="15%">View Only</th>
					<th width="15%">View/Edit</th>	
					<th width="15%">Full Permission</th>
					<th width="5%">&nbsp;</th>
				</tr>
				<tr>
					<td colspan="6">
						<table width="100%">
							<?php //print_r($modules); ?>
							<?php foreach($modules as $module){ ?>
							<tr>
							<?php if($module['name']=='Pages Manager'){
								$module['name']='Content Manager';
							}else if($module['name']=='Inquiries Manager'){
								$module['name']='Leads Manager';
							}?>
								<th width="40%"><?=$module['name']?></th>
								<td>
									<?php if(isset($this->request->data['content'][$module['file']]) && $this->request->data['content'][$module['file']] ){ ?>
									<?php 
									
										$value = $this->request->data['content'][$module['file']];
										$disabled = '';
									
									 ?>
									<?php } else{ ?>
									<?php $value = null; $disabled = 'disabled';  ?>
									<?php } ?>
									<?php echo $this->Form->checkbox('chekbox',array('value'=>1,"checked"=>$value,'onchange'=>"toggle_permission('permission".$module['file']."',this)")); ?>
								</td>
								<td width="100%" colspan="3" align="center">
									<div style="" id="permission<?=$module['file']?>"><?php echo $this->Form->radio('content.'.$module['file'],array('1'=>'','2'=>'','3'=>''),array('label'=>false,'legend'=>false,$disabled,'style'=>'margin-left:50px;margin-right:25px;'));?></div>
								</td>					
							</tr>
							
							<?php } ?>
							
							
							
							
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<button type="submit">Save</button> or 
		<?php echo $this->Html->link('Cancel', array('controller'=>'users', 'action' => 'index'));?>
	</form>							
</div>
<script type="text/javascript">
$(document).read(function(){
	
	});
	
	function toggle_permission(id,obj){
		if($(obj).attr('checked')){
			$('#'+id).children().attr('disabled',false);
		}else{
			$('#'+id).children().attr('disabled',true);
		}
		
	}
</script>
