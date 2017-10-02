<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;
/**
 * Teachpro model.
 *
 * @since  1.6
 */
class TeachproModelQuestions extends JModelItem
{
	/**
	 * Method to populate the subject name list.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	public function getSubjectList() {

		$table = $this->getTable('subjects');
		$table->load();
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string  $type    Name of the JTable class to get an instance of.
	 * @param   string  $prefix  Prefix for the table class name. Optional.
	 * @param   array   $config  Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Questions', $prefix = 'TeachproTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	public function getAllSubjects()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,subject_name');
		$query->from('#__teachpro_subject');
		$db->setQuery((string) $query);
		$subjects = $db->loadObjectList();
		return $subjects;
	}

	public function getAllGoals($subjectID)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__teachpro_goal');
		$query->where("subject_id = ". $subjectID);
		$db->setQuery((string) $query);
		$subjects = $db->loadObjectList();
		return $subjects;
	}

	public function getAllQuestions($goalID)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('question_name');
		$query->from('#__teachpro_question');
		$query->where("goal_id = ". $goalID);
		$db->setQuery((string) $query);
		$subjects = $db->loadObjectList();
		return $subjects;
	}

	public function getAllAnswers($questionID)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('answer_name,question_ID');
		$query->from('#__teachpro_answer');
		$query->where("question_id = ". $questionID);
		$db->setQuery((string) $query);
		$subjects = $db->loadObjectList();
		return $subjects;
	}

	public function getRightAnswers($questionID)
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('answer_id,right_answer');
		$query->from('#__teachpro_answer');
		$query->where("status = ". 1);
		$db->setQuery((string) $query);
		$subjects = $db->loadObjectList();
		return $subjects;
	}


}
