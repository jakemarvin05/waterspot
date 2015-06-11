<div class="supplier-navigation">
    <!--<div class="navigation-name">Navigation:</div>-->
    <ul>
	<?php if($Supplier_Details['Supplier']['profile_complete'] >= 3){ ?>
	<li><?php echo $this->Html->link('Available Leads',array('controller'=>'suppliers','action'=>'index','plugin'=>'supplier_manager'));?></li>
	<li><?php echo $this->Html->link('My Leads',array('controller'=>'suppliers','action'=>'my_leads','plugin'=>'supplier_manager'));?></li>
	<li><?php echo $this->Html->link('View Profile',array('controller'=>'suppliers','action'=>'my_account','plugin'=>'supplier_manager'));?></li>
	<li><?php echo $this->Html->link('Change Membership',array('controller'=>'suppliers','action'=>'membership_plans','plugin'=>'supplier_manager'));?></li>
	<li><?php echo $this->Html->link('Help',array('controller'=>'suppliers','action'=>'help','plugin'=>'supplier_manager'));?></li>
	<?php } ?>
	<li><?php echo $this->Html->link('Logout',array('controller'=>'suppliers','action'=>'logout','plugin'=>'supplier_manager'));?></li>
    </ul>   
</div>
<?php if($Supplier_Details['Supplier']['profile_complete'] >= 3){ ?>
<div class="remaining-leads">Leads Remaining This Month:
		<span>
		    <?php if(strtotime(date('Y-m-d',strtotime($Supplier_Details['Supplier']['end_date']))) < strtotime(date('Y-m-d'))){
			echo "0";
		    }
		    else if($Supplier_Details['SupplierPayment']['leads']){
			echo (($Supplier_Details['SupplierPayment']['leads'])-($Supplier_Details['SupplierPayment']['buy_leads']));
		    }else{
			echo "UNLIMITED";
		    }
		    ?>
		</span>
</div>
<?php } ?>