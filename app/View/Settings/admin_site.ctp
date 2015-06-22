<div>
	<article>
		<header>
			<h2>Site Setting</h2>
		</header>
	</article>
	<?=$this->element('admin/message');?>
	<?=$this->Form->create('Setting',array('name'=>'siteconfig','url'=>array('controller'=>'settings','action'=>'site'),'type' => 'file','onsubmit'=>'return validate();' ))?>
	<fieldset>
		<dl>
			<dt>
				<label>Name <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->input('site_name',array('class'=> 
				'medium','size'=>'60','div'=>false,'label'=>false)); ?>
			</dd>
			
		
			<dt>
				<label>Url <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->input('site_protocol',array('options'=>array('http://'=>'http://','https://'=>'https://'),'class'=> 
				'small','label'=>false,'div'=>false,'style'=>'width:80px;'));?>
				<?=$this->Form->input('site_url',array('class'=> 
				'small','size'=>'20','label'=>false,'div'=>false,'style'=>'width:160px;'));?>
			</dd>
			<dt>
				<label>Contact Email <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->input('site_contact_email',array('class'=> 
				'medium','size'=>'20','label'=>false,'div'=>false));?>
			</dd>
			
			<dt><label>Logo</label></dt>
			<dd>
			<?php echo  $this->Form->file('site_logo', array('class'=> 'fileupload customfile-input')); ?>
				<p style="padding-bottom:15px;">(Only png, gif, jpg, jpeg types are allowed. Max Image Size is 15KB)</p>
				<?php if(isset($this->request->data['Setting']['site_logo']) && file_exists(WWW_ROOT."img/site/".$this->request->data['Setting']['site_logo'])){?>
				<?=$this->Html->image("site/".$this->request->data['Setting']['site_logo']);?>
				<?php } ?>
            </dd>
            
			
			<dt><label>Icon</label></dt>
			<dd>
			<?php echo  $this->Form->file('site_icon', array('class'=> 'fileupload customfile-input')); ?>
				<p style="padding-bottom:15px;">(Only icon types are allowed. Max Image Size is 10KB)</p>
				<?php if(isset($this->request->data['Setting']['site_icon']) && file_exists(WWW_ROOT."img/site/".$this->request->data['Setting']['site_icon'])){?>
				<?=$this->Html->image("site/".$this->request->data['Setting']['site_icon']);?>
				<?php } ?>
            </dd>
			
			<dt><label>No Image</label></dt>
			<dd>
			<?php echo  $this->Form->file('site_noimage', array('class'=> 'fileupload customfile-input')); ?>
				<p style="padding-bottom:15px;">(Only icon types are allowed. Max Image Size is 225KB)(1028 X 929 px)</p>
				<?php if(isset($this->request->data['Setting']['site_noimage']) && file_exists(WWW_ROOT."img/site/".$this->request->data['Setting']['site_noimage'])){?>
				<?=$this->Html->image("site/".$this->request->data['Setting']['site_noimage'],array('width'=>75,'height'=>75));?>
				<?php } ?>
            </dd>
            <dt>
				<label>Title <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->input('site_title',array('class'=> 
				'medium','size'=>'60','div'=>false,'label'=>false)); ?>
			</dd>
			
			<dt>
				<label>Meta keyword <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->textarea('site_metakeyword',array('class'=> 
				'medium'));?>
			</dd>
			
			<dt>
				<label>Meta Description <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->textarea('site_metadescription',array('class'=> 
				'medium'));?>
			</dd>
			<dt>
				<label>Google Analytic Code <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->textarea('site_google_analytic_code',array('class'=> 
				'medium'));?>
			</dd>
			<dt>
				<label>Copyright</label>
			</dt>
			<dd>
				<?=$this->Form->textarea('copyright',array('class'=> 
				'medium'));?>
			</dd>
		
		</dl>

                    
                 
    </fieldset>
    <button type="submit"><?=__('Save');?></button>
         <?=$this->Form->end();?>
        </div>
