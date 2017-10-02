<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Teachpro records.
 *
 * @since  1.6
 */
class TeachproModelTeacheravailabilities extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id'
			);
		}

		parent::__construct($config);
	}

	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
                
                $user = JFactory::getUser();
                

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__teachpro_teacher_availability` AS a');
                
		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_TEACHPRO_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}    
        
        private function getAvailability ($userid, $startDate, $endDate, $subject)
        {
            $db = JFactory::getDbo();
            
            $query = $this->getListQuery();
            $query->where("teacher_id =".(int) $userid);

            $query->where("start_date <= '".$startDate->toSql()."'");
            $query->where("end_date >= '".$endDate->toSql()."'");
            $query->where("subject_id=".$subject);
            
            $sql = $query->__toString();

            
            $db->setQuery($query);
            
            return $db->loadObjectList();
            
        }
        
        public function isTeacherAvailable($teacher, $dates, $subject)
        {
             
             $available = true;
             $timesheetModel = JModelList::getInstance("Timesheets", "TeachproModel");
             foreach($dates as $date)
             {
                $startDate = JFactory::getDate($date['available_start_date']);
                $endDate = JFactory::getDate($date['available_end_date']);
                $records = $this->getAvailability($teacher,$startDate , $endDate, $subject);
               
                if(count($records)>0)
                {
                    if(!$timesheetModel->meetClassSizeRequirement($teacher, $startDate,$endDate))
                        $available = false;
                       
                }
                else
                     $available = false;
             }
             return $available;
        }
        
       
}
