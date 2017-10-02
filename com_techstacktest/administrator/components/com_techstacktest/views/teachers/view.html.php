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

jimport('joomla.application.component.view');

/**
 * View class for a list of Teachpro.
 *
 * @since  1.6
 */
class TeachproViewTeachers extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;
        
        protected $grades;
        
        protected $teachers;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		
		$this->pagination = $this->get('Pagination');
                
                $model = $this->getModel();
                
                $this->items =  $this->get("Items");
                
                $this->teachers = $model->getTeachers();
                
                $gradesModel = JModelList::getInstance("Subjectss", "TeachproModel");
                
                $this->grades = $gradesModel->getItems();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		//TeachproHelpersTeachpro::addSubmenu('studentss');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = TeachproHelpersTeachpro::getActions();

		JToolBarHelper::title(JText::_('COM_TEACHPRO_TITLE_TEACHERS'), 'studentss.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/teacheravailability';

		if (file_exists($formPath))
		{
//			if ($canDo->get('core.create'))
//			{
//				JToolBarHelper::addNew('teacheravailability.add', 'JTOOLBAR_NEW');
//				JToolbarHelper::custom('teacheravailability.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
//			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('teacheravailability.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('teacheravailability.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('teacheravailability.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'teacheravailability.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('teacheravailability.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('teacheravailability.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_teachpro');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_teachpro&view=teacheravailability');

		$this->extra_sidebar = '';
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`name`' => JText::_('COM_TEACHPRO_TEACHERS_NAME'),
			'a.`subject`' => JText::_('COM_TEACHPRO_TEACHERS_SUBJECT'),
			'a.`grade`' => JText::_('COM_TEACHPRO_TEACHERS_GRADE')
		);
	}
}
