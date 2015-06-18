<div id="logoWrapper">
            <img id="whiteLogo" src="/img/logo-white.png">
            <img id="coloredLogo" src="/img/logo-colored.png">
        </div>
        <nav>
          <div class="navGroup">
                 <div class="navButtonOuter <?php if($this->params['controller']=="pages" && $this->params['action']=="home" ){ echo "navActive"; }?>">
                     <a href="/"><i class="fa fa-home"></i><span class="navTextLabel">HOME</span></a>
                 </div>
            </div>
          <div class="navGroup">
               <div class="navButtonOuter <?php echo (!empty($this->params['action']) && ($this->params['action']=='vendor/vendor_list') )?'navActive' :'' ?>">
                   <a href="/vendor/vendor_list">  <i class="fa fa-list-alt"></i><span class="navTextLabel">VENDORS</span></a>
                </div>
           </div>
          <div class="navGroup">
                 <div class="navButtonOuter <?php echo (!empty($this->params['action']) && ($this->params['action']=='activities') )?'navActive' :'' ?>">
                     <a href="/activities"><i class="fa fa-ship"></i><span class="navTextLabel">ACTIVITIES</span></a>
                 </div>
             </div>
            <div class="navGroup">
             <div class="navButtonOuter" data-toggle="popover" data-placement="bottom">
                 <i class="fa fa-user"></i><span class="navTextLabel">LOGIN/SIGNUP</span>
             </div>
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
                         content += '<form>';
                             content += '<input name="data[Login][login_type]" type="hidden" value="0">';
                             content += '<input class="form-control popoverInput name="data[Login][email_id]" placeholder="Email" type="email">';
                             content += '<input class="form-control popoverInput" name="data[Login][password]" placeholder="Password" type="asssword">';
                             content += '<button type="submit" class="btn btnDefaults btnFillOrange">Login</button>';
                         content += '</form>';
          
                     //or facebook login
                     content += '<div class="loginOrContainer">';
                         content += '<div class="loginLine"></div>';
                         content += '<div class="loginOr">OR</div>';
                     content += '</div>';
                     content += '<button class="btnDefaults" type="button" id="loginFB">Login with <i class="fa fa-facebook-official"></i></button>';
          
                     content += '</div>'; // .popoverFormBlock
                 content += '</div>'; // .popoverBlock
          
          
                 content += '<div class="popoverBlock popoverMemberBlock popoverBlockActive">';
          
                     content += '<h3 class="popover-title-custom">New To Our Website?</h3>';
                     content += '<button class="btnDefaults btnFillOrange" type="button" id="createMember">Create account</button>';
          
                 content += '</div>';
          
                 // vendor
                 content += '<div class="popoverBlock popoverVendorBlock">';
                     content += '<h3 class="popover-title-custom popover-title-custom-first">Login</h3>';
                     content += '<div class="popoverFormBlock">';
          
                         //inputs
                         content += '<form>';
                             content += '<input name="data[Login][login_type]" type="hidden" value="1">';
                             content += '<input class="form-control popoverInput name="data[Login][email_id]" placeholder="Email" type="email">';
                             content += '<input class="form-control popoverInput" name="data[Login][password]" placeholder="Password" type="asssword">';
                             content += '<button type="submit" class="btn btnDefaults btnFillOrange">Login</button>';
                         content += '</form>';
          
                     content += '</div>'; // .popoverFormBlock
                 content += '</div>'; // .popoverBlock
          
                 content += '<div class="popoverBlock popoverVendorBlock">';
                     content += '<h3 class="popover-title-custom">New To Our Website?</h3>';
                     content += '<p class="popoverVendorOnly">Create a vendor account to start listing on our site:</p>';
                     content += '<button class="btnDefaults btnFillOrange popoverVendorOnly" type="button" id="listWithUs">List With Us!';
          
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
