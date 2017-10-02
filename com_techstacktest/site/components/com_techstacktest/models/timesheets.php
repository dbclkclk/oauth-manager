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
class TeachproModelTimesheets extends JModelList
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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		
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
                
		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);
                
		$query->from('`#__teachpro_timesheet` AS a');
                $query->innerJoin("#__teachpro_student_subject as ss on a.subject_student_id = ss.id");
               
		
		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			
		}
                
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
        private function getTimeBasedOnStartEndDate($userid, $startDate, $endDate)
        {
            $db = JFactory::getDbo();
            $query = $this->getListQuery();
            $query->where("( available_start_date<='".$startDate->toSql()."' AND available_end_date>='".$startDate->toSql()."' )","OR");
         
            
            $query->where("( available_start_date <='".$endDate->toSql()."' AND available_end_date >='".$endDate->toSql()."' )");
            
            
            $sql = $query->__toString();
            
            $db->setQuery($query);
            return $db->loadObjectList();
            
        }
        
        public function isTeacherTimeConflicting($userid, $dates)
        {
             $available = true;
             foreach($dates as $date)
             {
                $available = $this->getTimeBasedOnStartEndDate($userid, new JDate($date['startDate']), new JDate($date['endDate']));
                if(count($available)<=0)
                    $available = false;
             }
             return $available;
        }
        
        public function meetClassSizeRequirement($teacher,$startDate,$endDate)
        {
            $query = $this->getListQuery();
            $db = JFactory::getDbo();
            
            
            $query->where("a.teacher=".$teacher);
            $query->where("a.available_start_date<='".$startDate->toSql()."'");
            $query->where("a.available_end_date>='".$endDate->toSql()."'");
            
            $db->setQuery($query);
            
           $result = $db->loadObjectList();
           
           $status = false;
           if(count($result)<=1)
           {
               $status=true;
           }
           return $status;
            
        }
        
        public function getStartDate ($studentSubject)
        {
            $query = $this->getListQuery();
            $db = JFactory::getDbo();
            $query->where("a.subject_student_id=".$studentSubject);
            $query->clear("select");
            $query->select("a.available_start_date");
            $query->order("a.available_start_date ASC");
            $db->setQuery($query);
            $row = $db->loadObject();
            return $row;
        }
}
