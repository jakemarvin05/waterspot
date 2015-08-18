<div class="container-fluid vendor-panel">

<br/><br<br/><br/><br/><br><br>

<div class="middle-area">
    <div class="registration-form-box">
    <?php if ($this->Session->check('Message.login_error')): ?>
        <div class="error-message">
        <p><?=$this->Session->flash('login_error'); ?></p>
        </div>
    <?php endif;?>
        <br/>
	<p class="beforeHeader">Create an Activity!</p>
        <h1 class="headerAlt">VENDOR LOGIN</h1>
        <br/>

    <?php echo $this->Form->create('Vendor',array('action'=>'registration','name'=>'vendors','id'=>'VendorsLoginPage','controller'=>'vendors' ,'type'=>'file','novalidate' => true, 'class'=>'login-form', 'url'=>'/vendors/registration'));?>
    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'LoginForm')); ?>
    <?=$this->Form->email('emailid',array('required'=>false,'class'=>'form-control popoverInput','placeholder'=>'Email')); ?>
    <?=$this->Form->error('emailid',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
    <input class="form-control popoverInput" name="data[Vendor][pass]" placeholder="Password" type="password">
    <button type="submit" class="btn btnDefaults btnFillOrange">Login</button>
    <?php echo $this->Form->end();?>
    </div>
    </div>


 <script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
        $('#VendorsLoginPage').submit(function(){
            
            //var data = $(this).serializeArray();
            var data = new FormData(this);
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#VendorsLoginPage > span#for_owner_cms').show();
            $('#VendorsLoginPage > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/vendors/validation/login',
                async: false,
                data: data,
                dataType:'json', 
                type:'post',
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                     
                    if(data.error==1){
                        $.each(data.errors,function(i,v){
                            $('#'+i).addClass("invalid form-error").after('<div class="error-message">'+v+'</div>');
                            $('#'+i).bind('click',function(){
                                $(this).removeClass('invalid form-error');
                                $(this).next().remove();
                                });
                        });
                    }else{
                        status = 1;
                    }
                   
                 }
            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#VendorsLoginPage > button[type=submit]').attr({'disabled':false});
               $('#VendorsLoginPage > span#for_owner_cms').hide();
            }
            
           return (status===1)?true:false; 
            
        });
    });
 </script>

</div>

 

 



