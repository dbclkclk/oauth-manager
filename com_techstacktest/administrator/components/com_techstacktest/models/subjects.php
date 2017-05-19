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
class TechstacktestModelSubjects extends JModelList
{
                
        public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
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
               
               
               
	}
	
	public function getGoalsBySubject($id)
	{


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
        public function getSubjectsByGrade ($grade)
        {
            $db = JFactory::getDbo();
            $query = $this->getListQuery();
            
            $query->where("a.grade = ".(int)$grade);
            
            $db->SetQuery($query);
            $db->query();
                 
            $rows= $db->loadObjectList();
            return $rows;
            
            
        }
        
        
        public function getGrades ()
        {
            $db = JFactory::getDbo();
            $query = $this->getListQuery();
            
            $query->clear("select");
            $query->select("DISTINCT a.grade as grade");
            
            $db->SetQuery($query);
            $db->query();
                 
            $rows= $db->loadObjectList();
            return $rows;
            
            
        }
}
