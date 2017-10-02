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
class TeachproModelAnswerss extends JModelList
{
	
	public  $where;

        public function __construct($config = array())
	{   
		$config['filter_fields'] = array(
			'ordering'
		);
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
		$query->from('`#__teachpro_answer` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__teachpro_answer AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__teachpro_answer AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__teachpro_answer AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

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
				$query->where('( a.`name` LIKE ' . $search . ' )');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
                
                
	}
        
        public function getAnswersByQuestion($id)
        {
         
            $db = JFactory::getDbo();
            $query = $this->getListQuery();
            if($id!=null)
            {
              $query->where("a.questionid=".$id);
            }
           
            
            $db->SetQuery($query);
            $db->query();
                 
            $rows= $db->loadObjectList();
            return $rows;
        }

        
        
       public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
        
        public function getItemsThatDoesNotExistInArray($pks, $questionId)
        {
            
            $db = JFactory::getDbo();
            $keys = array();

            $query = $this->getListQuery();
            
            $query->join("INNER", "#__teachpro_question as q ON q.id=a.questionid");
            
            $query->where("q.id=".(int)$questionId)
                    ->where("a.id NOT IN (".implode(",", $pks).")");
            
            $db->SetQuery($query);
            $db->query();
   
            $rows= $db->loadObjectList();
            
            foreach($rows as $key  => $record)
            {
                array_push($keys,$record->id);
            }
            
            return $keys;
            
            
            
        }



	
}
