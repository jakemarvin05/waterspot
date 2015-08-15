

        <a href="/">
            <div id="logoWrapper" xmlns="http://www.w3.org/1999/html">
                <img id="whiteLogo" src="/img/logo-white.png">
                <img id="coloredLogo" src="/img/logo-colored.png">
            </div>
        </a>
        <nav>
          <div class="navGroup">
                 <div class="navButtonOuter <?php if($this->params['controller']=="pages" && $this->params['action']=="home" ){ echo "navActive";}?>">
                    <a href="/"> <i class="fa fa-home"></i><span class="navTextLabel">HOME</span></a>
                 </div>
            </div>
          <div class="navGroup">
               <div class="navButtonOuter <?php if($this->params['controller']=="vendors" ){ echo "navActive"; }?>">
                    <a href="/vendor/vendor_list/"><i class="fa fa-list-alt"></i><span class="navTextLabel">VENDORS</span></a>
                </div>
           </div>
          <div class="navGroup">
                 <div class="navButtonOuter <?php if($this->params['controller']=="activity" ){ echo "navActive"; }?>">
                   <a href="/activities/"><i class="fa fa-ship"></i><span class="navTextLabel">ACTIVITIES</span></a>
                 </div>
             </div>
            <div class="navGroup">
                <?php if($this->LoginMenu->isLogin()){ ?>
                    <div class="navButtonOuter dropdown" data-placement="bottom">

                       <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa fa-user"></i>
                            <?php 
                                if($this->Vendor->isVendorLogin()) { echo "Vendor Menu";
                                } else { echo "Member Menu"; } 
                            ?>
                           <span class="caret"></span>
                       </button>
                    <?php echo $this->LoginMenu->show(); ?>
                    </div> 
                    <?php } else { ?>
                    <div class="navButtonOuter" data-toggle="popover" data-placement="bottom">
                        <i class="fa fa-user"></i><span class="navTextLabel">LOGIN/SIGNUP</span>
                    </div>
                <?php  } ?>
             <script>
             $(function () {
          
                 var content = '';
                 
                 content += '<div class="popoverBlock popoverBlockActive">';
                     content += '<h3 id="popoverIAm">I am a</h3>';
          
                     //member-vendor toggle
                     content += '<div class="popoverCheckboxToggle btn-group" data-toggle="buttons">';
                         content += '<label id="popoverMemberLabel" class="btn btn-primary active ">';
                             content += '<input type="radio" autocomplete="off" checked>Member';
                         content += '</label>';
          
                         content += '<label id="popoverVendorLabel" class="btn btn-primary">';
                             content += '<input type="radio" autocomplete="off">Vendor';
                         content += '</label>';
                     content += '</div>';
                 content += '</div>';
          
                 // member
                 content += '<div class="popoverBlock popoverMemberBlock popoverBlockActive">';
          
                     content += '<h3 class="popover-title-custom popover-title-custom-first">Login</h3>';
                     content += '<div class="popoverFormBlock">';
          
                         //inputs
                         content += '<?php echo $this->Form->create('Member',array('action'=>'registration','name'=>'members','id'=>'MemberLogin','controller'=>'members','novalidate'=>true,'class'=>'login-form', 'url'=>'/members/registration'));?>';
                             content += '<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'LoginForm')); ?>';
                             content += '<?=$this->Form->email('email_id',array('required'=>false, 'class' => 'form-control popoverInput', 'placeholder' => 'Email')); ?><?=$this->Form->error('email_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>';
                             content += '<?=$this->Form->password('password',array('required'=>false, 'class' => 'form-control popoverInput', 'placeholder' => 'Password')); ?><?=$this->Form->error('password',null,array('wrap'=>'div','class'=>'error-message'));?>';
                             content += '<button type="submit" class="btn btnDefaults btnFillOrange">Login</button>';
                         content += '<?php echo $this->Form->end();?>';
          
                     //or facebook login
                     content += '<div class="loginOrContainer">';
                         content += '<div class="loginLine"></div>';
                         content += '<div class="loginOr">OR</div>';
                     content += '</div>';
                     content += '<button onclick="FB.login(function(response) {statusChangeCallback(response);})" class="btnDefaults" type="button" id="loginFB">Login with <i class="fa fa-facebook-official"></i></button>';
          
                     content += '</div>'; // .popoverFormBlock
                 content += '</div>'; // .popoverBlock
          
          
                 content += '<div class="popoverBlock popoverMemberBlock popoverBlockActive">';
          
                     content += '<h3 class="popover-title-custom">New To Our Website?</h3>';
                     content += '<a href="/members/registration" style="color:#FFF; text-decoration:none;"><button class="btnDefaults btnFillOrange" type="button" id="createMember">Create account</button></a>';
          
                 content += '</div>';
          
                 // vendor
                 content += '<div class="popoverBlock popoverVendorBlock">';
                     content += '<h3 class="popover-title-custom popover-title-custom-first">Login</h3>';
                     content += '<div class="popoverFormBlock">';
          
                         //inputs
                         content += '<?php echo $this->Form->create('Vendor',array('action'=>'registration','name'=>'vendors','id'=>'VendorsLogin','controller'=>'vendors' ,'type'=>'file','novalidate' => true, 'class'=>'login-form', 'url'=>'/vendors/registration'));?>';
                             content += '<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'LoginForm')); ?>';
                             content += '<?=$this->Form->email('emailid',array('required'=>false,'class'=>'form-control popoverInput')); ?>';
                             content += '<?=$this->Form->error('emailid',null,array('wrap' => 'div', 'class' => 'error-message')); ?>';
                            // content += '<input class="form-control popoverInput" name="data[Vendor][emailid]" placeholder="Email" type="email">';
                             content += '<input class="form-control popoverInput" name="data[Vendor][pass]" placeholder="Password" type="password">';
                             content += '<button type="submit" class="btn btnDefaults btnFillOrange">Login</button>';
                         content += '<?php echo $this->Form->end();?>';
          
                     content += '</div>'; // .popoverFormBlock
                 content += '</div>'; // .popoverBlock
          
                 content += '<div class="popoverBlock popoverVendorBlock">';
                     content += '<h3 class="popover-title-custom">New To Our Website?</h3>';
                     content += '<p class="popoverVendorOnly">Create a vendor account to start listing on our site:</p>';
                     content += '<a href="/vendors/registration" style="color:#FFF; text-decoration:none;"><button class="btnDefaults btnFillOrange popoverVendorOnly" type="button" id="listWithUs">List With Us!</button></a>';
          
                 content += '</div>';
          
          
                 var $loginPopover = $('[data-toggle="popover"]').popover({
                     html: true,
                     content: content,
                     viewport: { selector: 'body', padding: 10 }
                 });
          
                 $loginPopover.on('shown.bs.popover', function() {
          
                     $('#popoverMemberLabel').off('click.velocity');
                     $('#popoverVendorLabel').off('click.velocity');
          
                     $('#popoverMemberLabel').on('click.velocity', function() {
                         $('.popoverVendorBlock').velocity('transition.slideRightOut', 200, function(el) {
                             $(el).removeClass('popoverBlockActive');
                             $('.popoverMemberBlock').velocity('transition.slideLeftIn', 200);
                         });
                         
                     });
                     $('#popoverVendorLabel').on('click.velocity', function() {
                         $('.popoverMemberBlock').velocity('transition.slideRightOut', 200, function() {
                             $('.popoverVendorBlock').velocity('transition.slideLeftIn', 200);
                         });
                         
                     });
          
                 });
          
             });


             </script>
           </div>
        </nav>
