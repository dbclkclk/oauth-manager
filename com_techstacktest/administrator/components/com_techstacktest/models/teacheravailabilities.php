<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     Sandeep Thapa <ersandeepthapa@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Teachpro model.
 *
 * @since  1.6
 */
class TeachproModelTeacheravailabilities extends JModelList
{
                
        public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'subject_name', 'a.`subject_name`'
			);
		}

		parent::__construct($config);
	}

	
	protected function getListQuery()
	{
               
                
                $db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__teachpro_teacher_availability` AS a');

		$query->join("INNER", "`#__teachpro_teacher` as t on t.id = a.teacher_id");
		
		$query->join('INNER', '`#__users` AS u  ON u.id = t.`user_id`');

		// Join over the user field 'modified_by'
		$query->join('INNER', '#__teachpro_subject AS s ON s.id = a.`subject_id`');

		$query->where("u.block=0");

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.`subject_name` LIKE ' . $search . ' )');
			}
		}

		return $query;
               
	}
	
	public function getGoalsBySubject($id)
	{


	// return array();


		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		
		$query->select('*');
		$query->from(' #__teachpro_goal');
		$query->where('subject_id= '.$id.'');
		$query->order('ordering ASC');
		//$query->join('left',' #__teachpro_goal as goal on subject.id = goal.subject_id ');
		 
		 $db->SetQuery($query);
                 $db->query();
                 
		 $rows= $db->loadObjectList();
		 return $rows;
		
	}
        public function getTeacher_availabilityByGrade ($grade)
        {
            $db = JFactory::getDbo();
            $query = $this->getListQuery();
            
            $query->where("a.grade = ".(int)$grade);
            
            $db->SetQuery($query);
            $db->query();
                 
            $rows= $db->loadObjectList();
            return $rows;
            
            
        }
        
         public function deleteTeacherAvailability($keys, $teacher, $subject)
        {
            $db= JFactory::getDbo();
            $query = $this->getListQuery();
            
            $keys = implode(",", $keys);
            
            $query->clear();
            
            $query->delete($db->quoteName("#__teachpro_teacher_availability"))
                    ->where($db->quoteName("id")." NOT in (".$keys.")")
                    ->where($db->quoteName("teacher_id")."=".$teacher)
                    ->where($db->quoteName("subject_id")."=".$subject);
            
            $db->setQuery($query);
            
            $rows = $db->execute();
            
        }  
        
        public function getAvailabilitiesByTeacherAndSubject($teacher, $subject)
        {
            $db = JFactory::getDbo();
            $query = $this->getListQuery();
            
            $query->clear("select");
            $query->select("a.*, DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') as start_date, DATE_FORMAT(end_date, '%Y-%m-%dT%TZ') as end_date  ");
            
            $query->where($db->quoteName("a.teacher_id")."=".$teacher);
            $query->where($db->quoteName("a.subject_id")."=".$subject);
            
          
            
            $db->setQuery($query);
            
            return $db->loadObjectList();
            
        }
        
}
