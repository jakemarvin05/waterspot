    <div class="fix-width-popup top-ins">
        <h1>Instructions on How to Use This Site</h1>
        <p>Below we have compiled detailed instructions on how your Search All Wreckers (SAW) account works.</p>        
        <div class="pdf-download"><?php echo $this->Html->link('CLICK HERE TO DOWNLOAD AS A PDF',array('controller'=>'suppliers','action'=>'download','plugin'=>'supplier_manager'));?></div>
        <div class="pdf-instruction"><a href='javascript:printPDF()'>CLICK HERE TO PRINT THESE INSTRUCTIONS</a></div>
    </div>
    <hr class="top-ins-hr">

    <div class="fix-width-popup">
    <h2>Available Leads Page</h2>
    <p>After you have logged into your account, you will automatically be taken to your Available Leads Page that will look like this:</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/available-leads.png",array('alt'=>''));?></div>
    <br />
    <p><strong>There are a few important elements to this page that you need to be aware of.</strong></p>
    <p>1.	In the top left, you have your navigation, which will take you to the various pages in your account. We will explain each of these pages in this help guide.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/menus.png",array('alt'=>''));?></div>
    <p>2.	In the top right, you will see a counter telling you how many leads you have left for the month. You are free to see all of the details of a part request (a lead), except for the contact details. This is designed so that you can only count the leads you want towards your monthly total & get the most out of your SAW membership.</p>
    <p>Each time you choose to reveal the contact details of a part request, we count it as you &ldquo;buying&rdquo; that lead and we subtract one from your monthly allotment of leads &ndash; of course, if you have an unlimited account, you don&rsquo;t have to worry about this, as everything is already revealed to you.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/remaing.png",array('alt'=>''));?></div>
    <p>3.	In the middle of the page if the available lead table. This lists all of the available part requests you can access through SAW.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/table.png",array('alt'=>''));?></div>
    <br />
    <p><strong>Here is a brief rundown of what each column represents</strong></p>
    <table cellpadding="8" cellspacing="0" border="0" width="100%" align="center">
     <tbody>
       <tr>
          <td>Date</td>
          <td>The date that we received the part request</td>
       </tr>
       <tr>
          <td>Condition</td>
          <td>Whether the customer is looking for a new or used part (either means they don't care about condition)</td>
       </tr>
       <tr>
          <td>Vehicle</td>
          <td>The type of vehicle the customer wants a part for</td>
       </tr>
       <tr>
          <td>Make</td>
          <td>The brand of their vehicle</td>
       </tr>
       <tr>
          <td>Model</td>
          <td>The model of their vehicle</td>
       </tr>
       <tr>
          <td>Part Type</td>
          <td>What general category their part request falls into</td>
       </tr>
       <tr>
          <td>City or Town</td>
          <td>The city the customer is located in</td>
       </tr>
       <tr>
          <td>State</td>
          <td>The state the customer is located in</td>
       </tr>
       <tr>
          <td>Post Code</td>
          <td>The customers post code</td>
       </tr>
       <tr>
          <td>Action</td>
          <td>Your current relationship with that lead - explained further below</td>
       </tr>
     </tbody>
    </table>
    <br />
    <p>The Action column will show any one of 3 values depending on your relationship with that lead. it will either be:</p>
    <ul>
    <li>VIEW</li>
    <li>YOURS</li>
    <li>FREE</li>
    </ul>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/names-brand.png",array('alt'=>''));?></div>
    <br />
    <p><strong>VIEW</strong></p>
    <p>Most leads will be marked as VIEW, this means you have not yet purchased the contact details, but are free to view all the details of the part request itself. You can then decide if you are able to fill the request & if you want those contact details, by officially purchasing the lead, at which point, it will become YOURS.</p>
    <p><strong>YOURS</strong></p>
    <p>Once you have brought a lead, it will become orange & be marked as YOURS. This indicates that you now own this lead. You have full access to its contact details & they're yours to do with as your wish. These details will stay in your account for 3 months, then they are automatically deleted. You have the option of emailing all lead details directly to your email so you can keep them indefinitely.</p>
    <p><strong>FREE</strong></p>
    <p>FREE leads are open to everyone & these DO NOT count towards your monthly total of leads. Free leads are designed to ensure all our leads get answered & the smaller value requests get answered. All part requests will become free after 4 days if not enough wreckers purchase the part request.</p>
    <p>Clicking on the VIEW button will show the part request itself. This should give you all the information you need to decide whether or not you want to purchase this lead. You can see all of the details we have on this part request, except for the Name & Contact Details.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/electrical-either.png",array('alt'=>''));?></div>
    <p>Both the leads name & contact details are hidden until you purchase the lead. If you have an unlimited account, this will not be a concern, as all contact details are already revealed.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/contact-details.png",array('alt'=>''));?></div>
    <p>To purchase a part request &amp; see the contact details of that lead, you can either click the blue underlined link &ldquo;<a href="#">Click here to buy & Reveal</a>&rdquo; or click the big yellow button marked &ldquo;Buy &amp; email this to me&rdquo; &ndash; this will purchase the lead &amp; automatically send the contact details to your email for your own reference. We use the email address that you gave us when you signed up, you can change this on your &ldquo;View Profile&rdquo; page.</p>
    <p>Clicking the &ldquo;Delete this lead&rdquo; will permanently delete this part request from your account.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/part-request-details.png",array('alt'=>''));?></div>
    <p>You will also be able to see all of the details of the request that we received. All of these details refer to the make &amp; model of their vehicle. The &ldquo;Description&rdquo; is the part that they are actually requesting, described in their own words.</p>
    <br />
    <hr />
    <br />
    <p>Clicking on a YOURS or a FREE lead will show a similar, but slightly different part request</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/toyota-prado.png",array('alt'=>''));?></div>
    <br />
    <p>The part request contact details are now all revealed (everything that was supplied to us) and there is no option to purchase anything. You are now free to contact them with any follow up questions or offers you wish.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/contact-details-1.png",array('alt'=>''));?></div>
    <br />
    <hr />
    <br />
    <p><strong>On the top left of the available leads table, you will see two large yellow buttons.</strong>These buttons control bulk actions - ie, they will affect multiple leads on the page.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/email-slected.png",array('alt'=>''));?></div>
    <br />
    <p>By clicking on the square boxes on the left, you will highlight that part request and will be able to use either of the two yellow buttons to perform an action.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/buy-email.png",array('alt'=>''));?></div>
    <p><strong>Buy & Email Selected Leads</strong></p>
    <p>This button will purchase any chosen part requests &amp; automatically send the details to your email address &ndash; the email address you gave us when you created your account (you can chance this on the &ldquo;View Profile&rdquo; page). These purchases will come off your monthly total of part requests, unless you have an unlimited account, because you have total access to as many leads as you like.</p>
    <p><strong>Delete Selected</strong></p>
    <p>You can permanently delete any part requests that don&rsquo;t interest you. These will be removed from your account forever. Simply click on the square checkboxes on the left hand side &amp; press the &ldquo;Delete Selected&rdquo; button.</p>
    <br />
    <hr />
    <br />
    <p>On the top right of the of the available leads table, there is a small dropdown box where you can control how many part requests are displayed on that page.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/view-pages.png",array('alt'=>''));?></div>
    <br />
    <p>At the bottom of the available leads page, you may see a series of numbers with a NEXT/PREVIOUS option (as shown below). This means that there are more part requests over the page, click the corresponding button to keep inspecting requests. </p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/next-1-2.png",array('alt'=>''));?></div>
    <br />
    <br />
    <h2>My Leads Page</h2>
    <p>By using the navigation at the top of your account &amp; clicking on the link that says &ldquo;My Leads&rdquo; you will be taken to a page similar to what you see below.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/my-leads.png",array('alt'=>''));?></div>
    <p>This page work very similarly to the &ldquo;Available Leads&rdquo; page, except that it lists all of the part requests that you already own. You have already chosen to purchase all the request on this page and all of their contact details are available on this one page. This is the same data as you will find on the &ldquo;Available Leads&rdquo; page, except we have removed all of the FREE and VIEW part requests.</p>
    <br />
    <p><strong>View Profile Page</strong></p>
    <p>All of your personal & company details that you gave us when you created an account are listed on this page. You are able to update any of your details whenever you wish.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/profile-page.png",array('alt'=>''));?></div>
    <br />
    <p><strong>Your Business Details</strong></p>
    <p>In the section on the right, you can see all the details about your business that you supplied us when you signed up for an account. You can also change any details from here.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/business-details.png",array('alt'=>''));?></div>
    <p>By clicking the link at the bottom &ldquo;Update Your Details&rdquo;, you will be taken to a new page, where you can update any details you want to change.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/update-your-details.png",array('alt'=>''));?></div>
    <p>The &ldquo;New Lead Notification&rdquo; check box means that if it has a tick in it, you will receive a summary email every morning at 6am, telling you how many leads you have.</p>
    <br />
    <p><strong>Membership Details</strong></p>
    <p>This section lists all of the details about the plan you are currently subscribed to. You can upgrade or downgrade your plan whenever you want by clicking the link &ldquo;Change Your Plan&rdquo; &ndash; more details on this are given further down the page.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/membership-details.png",array('alt'=>''));?></div>
    <br />
    <p><strong>Change Your Password</strong></p>
    <p>This is how you are able to change the password to your account. Simply enter your current password &amp; then your new password twice.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/change-your-password.png",array('alt'=>''));?></div>
    <br />
    <p><strong>Change Your Lead Types</strong></p>
    <p>This section allows you to change the types of part requests that you&rsquo;re receiving. You are able to specify what types of requests you receive down to type of vehicle &amp; brand of vehicle.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/change-your-leads-type.png",array('alt'=>''));?></div>
    <p>By clicking either of the corresponding links &ndash; &ldquo;Change Vehicle Types&rdquo; or &ldquo;Change Brand Types&rdquo;, you are able to add or remove part requests.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/change-your-leads-type-1.png",array('alt'=>''));?></div>
    <br />
    <p><strong>Change Vehicle Types</strong></p>
    <p>This page will let you add or remove the TYPES of vehicle you receive part requests for. No longer supplying parts for Four Wheel Drives? Just click on the box next to that name & remove the tick. Want to start wrecking motorbikes? click the checkboxes next to motorbikes & dirt bikes & you will start receiving those leads straight away.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/tell-us.png",array('alt'=>''));?></div>
    <br />
    <p><strong>Change Brand Types</strong></p>
    <p>This page will let you change the type of BRANDS you receive part requests for (Toyota, Ducati, Suzuki, etc). You will go through this process when you&rsquo;re signing up for an account with SAW. When a brand has a tick to its left, you WILL receive part requests for that brand. By clicking that tick box, you can remove the tick &amp; you will no longer receive parts for that brand.</p>
    <p>You can make as many changes to these alerts as you wish.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/tell-us-1.png",array('alt'=>''));?></div>
    <br />
    <p>If there are any brands that you know of that we don&rsquo;t list, click the check box at the bottom of each section that looks like this:</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/any-other.png",array('alt'=>''));?></div>
    <p>This will open up a text window that you can type in that will look like:</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/any-other-1.png",array('alt'=>''));?></div>
    <p>You can lists any brands that we have missed & we will add them to our options for you to receive alerts for.
    </p>
    <br />
    <p><strong>Change Membership Page</strong></p>
    <p>This page lists all of the different membership plans you can choose from &amp; their specific details &ndash; note that the picture below may not match our current plans as they are change from time to time.</p>
    <div style="text-align:center; margin:5px 0;"><?php echo $this->Html->image("ac_instructions/new-signup.png",array('alt'=>''));?></div>
    <p>You are able to upgrade or downgrade your account whenever you like, at any point in the month. Upgrades will be effective immediately, so you can access more leads straight away. Downgrades will be effective from the beginning of your next buying cycle.</p>
    <p>For instance, if you joined on the 15th of April and you decided to downgrade your account on the 25th. You would have to wait until the 15th of May until you saw the change reflected in your account.</p>
    </div><!--fix-width-->
