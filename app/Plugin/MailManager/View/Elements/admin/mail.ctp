<?php echo $this->Html->script('admin/managecms.js');?>
<?php echo $this->Html->script('admin/fckeditor.js');?>
<script type="text/javascript">
function validate()
{//alert('ff');
	if(document.getElementById('MailTitle').value=='')
	{
		alert("Sorry! we cannot complete your request, please enter Mail Title");
		document.getElementById('MailTitle').focus();
		return false;
	}
	if(document.getElementById('MailFrom').value=='')
	{
		alert("Sorry! we cannot complete your request, please fill the Mail From field");
		document.getElementById('MailFrom').focus();
		return false;
	}
	if(document.getElementById('MailSubject').value=='')
	{
		alert("Sorry! we cannot complete your request, please enter Mail Subject");
		document.getElementById('MailSubject').focus();
		return false;
	}
}
</script>
<?=$this->element('admin/breadcrumbs');?>
<div>
      <article>
         <header>
           <h2>
            <?php if (!empty($this->request->data) && $this->request->data['Mail']['id']){
		echo ('Update Mail Format');
		}
		else{
		echo ('Add Mail Format');
		}
		?>
            </h2>
         </header>
       </article>
                
           <?php echo $this->element('admin/message');?>
           <?php 
		echo $this->Form->create('Mail', array('type' => 'file','onSubmit'=>"return validate()"));?>
		<?php echo $this->Form->input('id'); ?>
       <fieldset>
	    <dl>
		<dt><label>Mail Title</label><span style="color:red;">*</span></dt>
		<dd><?php echo $this->Form->text('title',array('class'=> 'small','size'=>'45')); ?></dd>
		<dt>
		<label>Mail From <span style="color:red;">*</span></label>
		</dt>
                <dd><?php echo $this->Form->text('from',array('class'=> 'small','size'=>'45')); ?>
                </dd>
                 <dt>
		<label>Mail Subject <span style="color:red;">*</span></label>
		</dt>
                <dd> <?php echo $this->Form->text('subject',array('class'=> 'small','size'=>'45')); ?>
                 </dd>
                <dt>
		<label>Mail Body</label>
		</dt>
		<dd><?php					
					echo $this->Form->textarea('body', array('cols'=>'60','rows'=>'3'));
					echo $this->Fck->load('Mail.body');
				?>
		</dd>
                
                </dl>
              	</fieldset>
                <button type="submit">
                <?php 
			if (!empty($this->request->data) && $this->request->data['Mail']['id']):
				echo ('Update');
				else:
				echo ('Add');
			endif;
		?>
                </button> or
		<?php echo $this->Html->link('Cancel', array('action' => 'index'));?>
	<?php $this->Form->end();?>                
                
                
                
</div>


