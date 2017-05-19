<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_teachpro', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_teachpro/js/form.js');
 $doc->addStyleSheet("//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css");

$user = JFactory::getUser();

/**/
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-students').submit(function (event) {
				
			});

			
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-students').submit(function (event) {
				
			});

			
		});
	}
</script>

<div class="students-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Edit <?php echo $this->item->id; ?></h1>
	<?php else: ?>
		<h2>Step 1: Add another student</h2>
	<?php endif; ?>
                
        <h3>Go through the process below to get your child registered. Click each step to proceed...</h3>
        <?php echo JLayoutHelper::render('default_header', array('view' => $this), dirname(__FILE__)); ?>
        <br />
        <br />

	<form id="form-students"
		  action="<?php echo JRoute::_('index.php?option=com_teachpro&task=students.save'); ?>"
		  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<?php if(empty($this->item->modified_by)): ?>
		<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />
	<?php endif; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('firstname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('firstname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('lastname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('lastname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('dateofbirth'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('dateofbirth'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('gradelevel'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('gradelevel'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('city'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('city'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('usstate'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('usstate'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('zipcode'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('zipcode'); ?></div>
	</div>
                
        <div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('relationship'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('relationship'); ?></div>
	</div>
                
        <div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('timezone'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('timezone'); ?></div>
	</div>
                
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('userid'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('userid',null,$user->id); ?></div>
	</div>
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_teachpro&task=studentsform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_teachpro"/>
		<input type="hidden" name="task"
			   value="studentsform.save"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
