<?php

defined('_JEXEC') or die();

class TeachproModelAssignedtestlists extends JModelList
{

	public function __construct($config = array())
	{   
		$config['filter_fields'] = array(
			'std.firstname', 'sub.subject_name', 'std.gradelevel','std_sub.is_test_taken'
		);
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('std.firstname', 'asc');
	}

	protected function getListQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(
			$this->getState(
			'list.select',
			'std.id, std.gradelevel, std.firstname, std.lastname,sub.id, sub.subject_name, std_sub.is_test_taken'
			)
		);
		$query->from($db->quoteName('#__teachpro_student_subject').'AS std_sub');
		$query->join('inner', '#__teachpro_student AS std ON std.id = std_sub.student_id');
		$query->join('inner', '#__teachpro_subject AS sub ON sub.id = std_sub.subject_id');
		$query->join('inner', '#__teachpro_payment AS pay ON pay.id = std_sub.payment_id');
		$query->where('pay.is_paid = '. (int) 1);
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}
}
?>