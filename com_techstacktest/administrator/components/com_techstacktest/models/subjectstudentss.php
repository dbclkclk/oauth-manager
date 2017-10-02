<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TeachproModelStudentsubjectss extends JModelList
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
		$query->from('`#__teachpro_student_subject` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__teachpro_student_subject AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__teachpro_student_subject AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__teachpro_student_subject AS `modified_by` ON `modified_by`.id = a.`modified_by`');

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
     

        
       public function getItems()
        {
		$items = parent::getItems();

		return $items;
	}
        
       public function getItemBySubjectId($studentSubjectId)
       {
            $query =$this->getListQuery();
            $query->where('a.student_subject_id= ' . (int) $studentSubjectId);
       
            return  $this->_getList($query, 0, 1);
       }
       
}
