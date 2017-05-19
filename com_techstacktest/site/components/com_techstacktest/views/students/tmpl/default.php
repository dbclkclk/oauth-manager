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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_teachpro');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_teachpro')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

	<div class="item_fields">
		<table class="table">
			<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_CREATED_BY'); ?></th>
			<td><?php //echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_MODIFIED_BY'); ?></th>
			<td><?php //echo $this->item->modified_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_FIRSTNAME'); ?></th>
			<td><?php echo $this->item->firstname; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_LASTNAME'); ?></th>
			<td><?php echo $this->item->lastname; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_DATEOFBIRTH'); ?></th>
			<td><?php echo $this->item->dateofbirth; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_GRADELEVEL'); ?></th>
			<td><?php echo $this->item->gradelevel; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_CITY'); ?></th>
			<td><?php echo $this->item->city; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_USSTATE'); ?></th>
			<td><?php echo $this->item->usstate; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_ZIPCODE'); ?></th>
			<td><?php echo $this->item->zipcode; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_TEACHPRO_FORM_LBL_STUDENTS_USERID'); ?></th>
			<td><?php echo $this->item->userid; ?></td>
</tr>

		</table>
	</div>
	<?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_teachpro&task=students.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_TEACHPRO_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_teachpro')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_teachpro&task=students.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_TEACHPRO_DELETE_ITEM"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_TEACHPRO_ITEM_NOT_LOADED');
endif;
