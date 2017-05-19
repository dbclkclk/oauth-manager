<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Oauth_manager
 * @author     TechStack Solutions Ltd <support@techstacksolutions.com>
 * @copyright  2017 Techstack Solutions Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_oauth_manager/assets/css/oauth_manager.css');
$document->addStyleSheet(JUri::root() . 'media/com_oauth_manager/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_oauth_manager');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_oauth_manager&task=oauthservers.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'oauthserverList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});

	window.toggleField = function (id, task, field) {

		var f = document.adminForm,
			i = 0, cbx,
			cb = f[ id ];

		if (!cb) return false;

		while (true) {
			cbx = f[ 'cb' + i ];

			if (!cbx) break;

			cbx.checked = false;
			i++;
		}

		var inputField   = document.createElement('input');
		inputField.type  = 'hidden';
		inputField.name  = 'field';
		inputField.value = field;
		f.appendChild(inputField);

		cb.checked = true;
		f.boxchecked.value = 1;
		window.submitform(task);

		return false;
	};

</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>
<form action="<?php echo JRoute::_('index.php?option=com_oauth_manager&view=oauthservers'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row-fluid">
        <?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span4">
            <?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span8">
            <?php else : ?>
            <div id="j-main-container">
                <?php endif; ?>
                <div id="filter-bar" class="btn-toolbar">
                    <div class="filter-search btn-group pull-left">
                        <label for="filter_search"  class="element-invisible">
                            <?php echo JText::_('JSEARCH_FILTER'); ?>
                        </label>
                        <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
                    </div>
                    <div class="btn-group pull-left">
                        <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
                            <i class="icon-search"></i>
                        </button>
                        <button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
                            <i class="icon-remove"></i>
                        </button>
                    </div>
                    <div class="btn-group pull-right hidden-phone">
                        <label for="limit" class="element-invisible">
                            <?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
                        </label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>
                    <div class="btn-group pull-right hidden-phone">
                        <label for="directionTable" class="element-invisible">
                            <?php echo JText::_('JFIELD_ORDERING_DESC'); ?>
                        </label>
                        <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
                            <option value="asc" <?php echo $listDirn == 'asc' ? 'selected="selected"' : ''; ?>>
                                <?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?>
                            </option>
                            <option value="desc" <?php echo $listDirn == 'desc' ? 'selected="selected"' : ''; ?>>
                                <?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?>
                            </option>
                        </select>
                    </div>
                    <div class="btn-group pull-right">
                        <label for="sortTable" class="element-invisible">
                            <?php echo JText::_('JGLOBAL_SORT_BY'); ?>
                        </label>
                        <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value="">
                                <?php echo JText::_('JGLOBAL_SORT_BY'); ?>
                            </option>
                            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix">
                </div>
                <table class="table table-striped" id="oauthserverList">
                    <thead>
                        <tr>
                            <?php if (isset($this->items[0]->ordering)): ?>
                            <th width="1%" class="nowrap center hidden-phone">
                                <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                            </th>
                            <?php endif; ?>
                            <th width="1%" class="hidden-phone">
                                <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                            </th>
                            <?php if (isset($this->items[0]->state)): ?>
                            <th width="1%" class="nowrap center">
                                <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
                            </th>
                            <?php endif; ?>
                            <th class='left'>
                                <?php echo JHtml::_('grid.sort',  'COM_OAUTH_MANAGER_OAUTHSERVERS_ID', 'a.`id`', $listDirn, $listOrder); ?>
                            </th>
                            <th class='left'>
                                <?php echo JHtml::_('grid.sort',  'COM_OAUTH_MANAGER_OAUTHSERVERS_URL', 'a.`url`', $listDirn, $listOrder); ?>
                            </th>
                            <th class='left'>
                                <?php echo JHtml::_('grid.sort',  'COM_OAUTH_MANAGER_OAUTHSERVERS_CLIENT_ID', 'a.`client_id`', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                                <?php echo $this->pagination->getListFooter(); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>        