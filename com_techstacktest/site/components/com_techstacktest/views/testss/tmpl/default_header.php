<?php 

JHtml::stylesheet(Juri::base()."media/com_teachpro/css/style.css",array(),true);


?>

<div class="container">
    
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">Step 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="<?php echo JRoute::_("index.php?option=com_teachpro&view=studentsform"); ?>" class="bs-wizard-dot" data-toggle="tooltip" data-placement="left" title="Step 1: click here"></a>
                  <div class="bs-wizard-info text-center">Add your child to the system. See below for more information.</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Step 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="<?php echo JRoute::_("index.php?option=com_teachpro&view=assigntests"); ?>" class="bs-wizard-dot" data-toggle="tooltip" data-placement="left" title="Step 2: click here"></a>
                  <div class="bs-wizard-info text-center">Assign your child to a subject and pay for a pre-evaluation test.</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum"><b>Step 3</b></div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="<?php echo JRoute::_("index.php?option=com_teachpro&view=testss"); ?>" class="bs-wizard-dot" data-toggle="tooltip" data-placement="left" title="Step 3: click here"></a>
                  <div class="bs-wizard-info text-center"><b>Have child take pre-evaluation test and get results emailed to you.</b></div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">Step 4</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="<?php echo JRoute::_("index.php?option=com_teachpro&view=testss"); ?>" class="bs-wizard-dot" data-toggle="tooltip" data-placement="left" title="Step 4: click here"></a>
                  <div class="bs-wizard-info text-center">Have child enrolled into our program to improve his/her grades.</div>
                </div>
            </div>
</div>
