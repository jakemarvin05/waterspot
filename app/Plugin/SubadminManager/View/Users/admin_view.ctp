<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;">
    <div style="padding:10px 0;">
            <div style="float:left; width:110px;"><b>First Name Ok</b></div>
            <div align="justify;" style="float:left; width:400px;"><?php echo $user['User']['name'];?></div>
            <div style="clear:both;"></div>
    </div>
    
    <div style="padding:10px 0;">
            <div style="float:left; width:110px;"><b>Last Name</b></div>
            <div align="justify;" style="float:left; width:400px;"><?php echo $user['User']['lname'];?></div>
            <div style="clear:both;"></div>
    </div>
    
    <div style="padding:10px 0;">
            <div style="float:left; width:110px;"><b>Email</b></div>
            <div align="justify" style="float:left; width:400px;"><?php echo $user['User']['email'];?></div>
            <div style="clear:both;"></div>
    </div>
    
    <div style="padding:10px 0;">     
          <div style="float:left; width:110px;"><b>Username</b></div>
          <div align="justify" style="float:left; width:400px;"><?php echo $user['User']['username'];?></div>
          <div style="clear:both;"></div>
    </div>    
</div>
