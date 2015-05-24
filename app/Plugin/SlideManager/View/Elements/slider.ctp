<?php $slide = $this->requestAction(array('plugin'=>'slide_manager','controller'=>'slides','action'=>'show'));
<div class="flexslider">
          <ul class="slides">
          <?php foreach($slide as $slides){ ?>
            <li>
  	    	    <?php echo $this->Html->image('slide/'.$slides['Slide']['image'],array('class'=>'image','alt'=>''));?></li>
                    <?php } ?>
  	    		</li>
  	    		
          </ul>
        </div>
