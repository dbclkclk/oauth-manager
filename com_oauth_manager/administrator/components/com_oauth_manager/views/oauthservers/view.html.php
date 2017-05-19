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

jimport('joomla.application.component.view');

/**
 * View class for a list of Oauth_manager.
 *
 * @since  1.6
 */
class Oauth_managerViewOauthservers extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

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
            $this->items = array();
            $this->state = $this->get('State');
            $this->items = $this->get('Items');
            $this->pagination = $this->get('Pagination');
  
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
		$canDo = Oauth_managerHelpersOauth_manager::getActions();

		JToolBarHelper::title(JText::_('COM_OAUTH_MANAGER_TITLE_OAUTHSERVERS'), 'oauthservers.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/oauthserver';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('oauthserver.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('oauthservers.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('oauthserver.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('oauthservers.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('oauthservers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'oauthservers.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('oauthservers.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('oauthservers.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'oauthservers.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('oauthservers.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_oauth_manager');
		}

                
                JHtmlSidebar::addEntry(array("text"=>JText::_("COM_OAUTH_MANAGER_SIDEBAR_MENU_SERVERS"),"icon"=>"fa-server"), JRoute::_("index.php?option=com_oauth_manager&view=oauthservers"), true);
                
                JHtmlSidebar::addEntry(array("text"=>JText::_("COM_OAUTH_MANAGER_SIDEBAR_MENU_SERVICES"),"icon"=>"fa-users"), JRoute::_("index.php?option=com_oauth_manager&view=oauthservices"), false);
		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_oauth_manager&view=oauthservers');

		$this->extra_sidebar = '';
		/* JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
                 
                 */
	}
        
        public function getServerStatus($id)
        {
            $oauthServerModel = $this->getModel("Oauthservers");
            $models = $oauthServerModel->getItem($id);
            
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
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`url`' => JText::_('COM_OAUTH_MANAGER_OAUTHSERVERS_URL'),
			'a.`client_id`' => JText::_('COM_OAUTH_MANAGER_OAUTHSERVERS_CLIENT_ID'),
		);
	}
}







