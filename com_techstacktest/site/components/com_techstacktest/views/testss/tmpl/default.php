<?php
defined('_JEXEC') or die;

$document = JFactory::getDocument();

$document->addScript(JUri::root() . 'media/com_teachpro/js/knockout-3.4.0.js');

?>
<h2>Take Test And Enrollment Process</h2>
<h4>Click <b>enroll</b> button to move onto the enrollment process or click <b>Take Test</b> to start your evaluation.</h4>
<div class="container">
    <div class="row">
        <button type="button" class="btn btn-success" data-bind="click:refresh">Refresh</button>
    </div>
</div>
<?php echo JLayoutHelper::render('default_header', array('view' => $this), dirname(__FILE__)); ?>
<br />
<form id="adminForm" method="post" name="adminForm">
    <table class="table table-striped table-bordered">

        <thead>
            <tr>
                <th width="5%"> <?php echo JText::_('Action'); ?> </th>
                
                <th width="30%"> <?php echo JText::_('Name') ?> </th>

                <th width="20%"> <?php echo JText::_('Grade'); ?> </th>

                <th width="30%"> <?php echo JText::_('Subject'); ?> </th>

               
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <tr>
                    <td>
                        <?php
                        // If student has completed the test.
                        if ($this->isTestTaken($item->id) == 2) {
                            ?>

                            <?php
                            // If student is already enrolled.
                            if ($this->getEnrollmentStatus($item->id) == TRUE) {

                                if ('payed' == TRUE) {
                                    ?>

                                    Enrolled
                                <?php } else { ?>

                                    <a class="btn btn-primary"
                                       href="#">
                                        Pay

                                    </a>
                                    <?php
                                }
                            } else {
                                ?>

                                <a class="btn btn-primary"
                                   href="<?php echo JRoute::_('?option=com_teachpro&view=enrollment&id=' . (int) $item->id) . "&" . JSession::getFormToken() . '=1'; ?>">
                                    Enroll
                                </a>

                            <?php } ?>

                            <?php
                        }

                        // If student has started the test but not completed.
                        elseif ($this->isTestTaken($item->id) == 1) {
                            ?>

                            <a class="btn btn-warning"
                               href="<?php echo JRoute::_('?option=com_teachpro&task=tests.save&id=' . (int) $item->id) . "&" . JSession::getFormToken() . '=1'; ?>">
                                Resume Test
                            </a>

                            <?php
                        }

                        // If student has not started the test.
                        else {
                            ?>
                            <a class="btn btn-success"
                               href="<?php echo JRoute::_('?option=com_teachpro&task=tests.save&id=' . (int) $item->id) . "&" . JSession::getFormToken() . '=1' ?>">
                                Start Test Now
                            </a>
                        <?php } ?>
                    </td>
                    <td>
                        <span><?php echo $this->escape($item->firstname) . ' ' . $this->escape($item->lastname); ?></span>
                    </td>

                    <td>
                        <span><?php echo $this->escape($item->student_grade); ?></span>
                    </td>

                    <td>
                        <span><?php echo $this->escape($item->subject_name); ?></span>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('a.close').on('click', function (e) {
            e.preventDefault();
            jQuery(this).parent().hide();
        });
    });
    

var TestViewModel = function ()
{
    var self = this;
    self.refresh = function ()
    {
        location.reload();
    };
}
    
    // Activates knockout.js
ko.applyBindings(new TestViewModel());

</script>
