<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;

/**
 * Teachpro model.
 *
 * @since  1.6
 */
class TeachproModelResponses extends JModelItem {

    /**
     * Generate score for each goals.
     * 
     * @param int $testId
     * @return array score of goals
     */
    public function getScoreForGoals($testId) {

        $db = JFactory::getDbo();

        $subjectId = $this->getSubjectId($db, $testId);

        $questions = $this->getQuestions($db, $subjectId);

        $results = $this->getResult($db, $questions, $testId);

        $correctlyAnsweredQuestions = $this->getCorrectlyAnsweredQuestions($results);

        $goals = $this->getGoals($db, $subjectId, $correctlyAnsweredQuestions);

        return $goals;
    }

    /**
     * Generates average score of all goals.
     * 
     * @param int $testId
     * @return decimal Average Score
     */
    public function getAverageScore($testId) {

        $goals = $this->getScoreForGoals($testId);

        $totalScore = 0;

        foreach ($goals as $goal) {

            $totalScore += $goal['score'];
        }

        $average = $this->calculateAverage($totalScore, count($goals));

        return $average;
    }

    /**
     * Returns the total number of questions responded by the student.
     * 
     * @param int $testId
     * @return array
     */
    public function getNumberOfRespondedQuestions($testId) {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('COUNT(*)')
                ->from($db->quoteName('#__teachpro_response', 'response'))
                ->join('INNER', $db->quoteName('#__teachpro_answer', 'answer')
                        . ' ON (' . $db->quoteName('answer.id') . ' = ' . $db->quoteName('response.answerid') . ')')
                ->join('INNER', $db->quoteName('#__teachpro_question', 'question')
                        . ' ON (' . $db->quoteName('question.id') . ' = ' . $db->quoteName('answer.questionid') . ')')
                ->where($db->quoteName('response.testid') . '=' . $testId);
//                ->group($db->quoteName('question.id'));

        $db->setQuery($query);

        $numberOfRespondedQuestions = $db->loadResult();

        return $numberOfRespondedQuestions;
    }

    /**
     * Returns the total number of questions of a subject.
     * 
     * @param int $studentSubjectId
     * @return array
     */
    public function getTotalNumberOfQuestions($studentSubjectId) {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('COUNT(*)')
                ->from($db->quoteName('#__teachpro_student_subject', 'studentSubject'))
                ->join('INNER', $db->quoteName('#__teachpro_subject', 'subject')
                        . ' ON (' . $db->quoteName('subject.id') . ' = ' . $db->quoteName('studentSubject.subject_id') . ')')
                ->join('INNER', $db->quoteName('#__teachpro_goal', 'goal')
                        . ' ON (' . $db->quoteName('goal.subjectid') . ' = ' . $db->quoteName('subject.id') . ')')
                ->join('INNER', $db->quoteName('#__teachpro_question', 'question')
                        . ' ON (' . $db->quoteName('goal.id') . ' = ' . $db->quoteName('question.goalid') . ')')
                ->where($db->quoteName('studentSubject.id') . '=' . $studentSubjectId);

        $db->setQuery($query);

        $totalNumberOfQuestions = $db->loadAssocList();

        return $totalNumberOfQuestions[0]['COUNT(*)'];
    }

    /*     * ********************************************************************************************************************************************************************** */

    private function getCorrectlyAnsweredQuestions($results) {

        $correctlyAnsweredQuestions = array();

        foreach ($results as $result) {

            if ($result['status'] == TRUE) {

                $correctlyAnsweredQuestions[]['questionId'] = $result["questionId"];
            }
        }

        return $correctlyAnsweredQuestions;
    }

    private function getTotalCorrectAnswers(&$db, $question) {

        $query = $db->getQuery(true);

        $query->select(array('COUNT(*)'))
                ->from($db->quoteName("#__teachpro_answer", "answer"))
                ->where($db->quoteName('answer.questionid') . '=' . $db->quote($question['questionId']))
                ->where($db->quoteName("answer.correct") . '=1');

        $db->setQuery($query);

        $totalCorrectAnswers = $db->loadResult();

        return $totalCorrectAnswers;
    }

    private function getTotalResponses(&$db, $question, $testId) {

        $query = $db->getQuery(true);

        $query->select(array('COUNT(*)'))
                ->from($db->quoteName("#__teachpro_answer", "answer"))
                ->join("INNER", $db->quoteName('#__teachpro_response', 'response')
                        . ' ON (' . $db->quoteName('answer.id') . ' = ' . $db->quoteName('response.answerid') . ')')
                ->where($db->quoteName('response.testid') . '=' . $db->quote($testId))
                ->where($db->quoteName('answer.questionid') . '=' . $db->quote($question['questionId']));

        $db->setQuery($query);

        $totalResponses = $db->loadResult();

        return $totalResponses;
    }

    private function getTotalCorrectResponses(&$db, $question, $testId) {

        $query = $db->getQuery(true);

        $query->select(array('COUNT(*)'))
                ->from($db->quoteName("#__teachpro_answer", "answer"))
                ->join("INNER", $db->quoteName('#__teachpro_response', 'response')
                        . ' ON (' . $db->quoteName('answer.id') . ' = ' . $db->quoteName('response.answerid') . ')')
                ->where($db->quoteName('response.testid') . '=' . $db->quote($testId))
                ->where($db->quoteName('answer.questionid') . '=' . $db->quote($question['questionId']))
                ->where($db->quoteName("answer.correct") . '=1');

        
        $db->setQuery($query);

        $totalCorrectResponses = $db->loadResult();

        return $totalCorrectResponses;
    }

    private function getResult(&$db, $questions, $testId) {

        foreach ($questions as &$question) {

            $totalCorrectAnswers = $this->getTotalCorrectAnswers($db, $question);

            $totalCorrectResponses = $this->getTotalCorrectResponses($db, $question, $testId);

            $totalResponses = $this->getTotalResponses($db, $question, $testId);

            if ($totalCorrectAnswers == $totalCorrectResponses) {

                $question['status'] = TRUE;

                if ($totalCorrectAnswers != $totalResponses) {

                    $question['status'] = FALSE;
                }
            } else {

                $question['status'] = FALSE;
            }
        }

        return $questions;
    }

    private function getGoals(&$db, $subjectId, $correctlyAnsweredQuestions) {

        $query = $db->getQuery(true);

        $query->select(array('goal.id AS goalId, goal.name AS goalName, goal.description AS goalDescription,COUNT(*) AS totalQuestions'))
                ->from($db->quoteName('#__teachpro_question', 'question'))
                ->join('INNER', $db->quoteName('#__teachpro_goal', 'goal')
                        . ' ON (' . $db->quoteName('question.goalid') . ' = ' . $db->quoteName('goal.id') . ')')
                ->where($db->quoteName('goal.subjectid') . '=' . $db->quote($subjectId))
                ->group($db->quoteName('goal.id'));

        $db->setQuery($query);
        
       

        $goals = $db->loadAssocList();
        

        foreach ($goals as &$goal) {

            $goal['totalCorrectlyAnsweredQuestions'] = 0;

            foreach ($correctlyAnsweredQuestions as $correctlyAnsweredQuestion) {

                $query = $db->getQuery(true);

                $query->select(array('COUNT(*)'))
                        ->from($db->quoteName('#__teachpro_question', 'question'))
                        ->where($db->quoteName('question.id') . '=' . $db->quote($correctlyAnsweredQuestion['questionId']))
                        ->where($db->quoteName('question.goalid') . '=' . $db->quote($goal['goalId']));

                $db->setQuery($query);

                $goal['totalCorrectlyAnsweredQuestions'] += $db->loadResult();
            }
            
        }
       
        foreach ($goals as &$goal) {

            $goal['score'] = round(($goal['totalCorrectlyAnsweredQuestions'] / $goal['totalQuestions']) * 100, 2);
        }

       
        
        foreach (array_keys($goals) as $key) {
            $scores[$key]  = $goals[$key]['score'];
        }
        
      
          

        array_multisort($scores,SORT_ASC, $goals);  
        
        return $goals;
    }

    private function getQuestions(&$db, $subjectId) {

        $query = $db->getQuery(true);

        $query->select(array('question.id AS questionId'))
                ->from($db->quoteName('#__teachpro_question', 'question'))
                ->join('INNER', $db->quoteName('#__teachpro_goal', 'goal')
                        . ' ON (' . $db->quoteName('question.goalid') . ' = ' . $db->quoteName('goal.id') . ')')
                ->join('INNER', $db->quoteName('#__teachpro_subject', 'subject')
                        . ' ON (' . $db->quoteName('goal.subjectid') . ' = ' . $db->quoteName('subject.id') . ')')
                ->where($db->quoteName('subject.id') . '=' . $db->quote($subjectId));

        $db->setQuery($query);

        // Get all questions inside a subject.
        $questions = $db->loadAssocList();

        return $questions;
    }

    private function getSubjectId(&$db, $testId) {

        $query = $db->getQuery(true);

        $query->select($db->quoteName('studentSubject.subject_id', 'subjectId'))
                ->from($db->quoteName('#__teachpro_test', 'test'))
                ->join('INNER', $db->quoteName('#__teachpro_student_subject', 'studentSubject')
                        . ' ON (' . $db->quoteName('test.student_subject_id') . ' = ' . $db->quoteName('studentSubject.id') . ')')
                ->where($db->quoteName('test.id') . '=' . $testId);

        $db->setQuery($query);

        $subjectId = $db->loadResult();

        return $subjectId;
    }

    private function calculateAverage($totalScore, $totalNumberOfGoals) {

        $average = $totalScore / $totalNumberOfGoals;

        return round($average, 2);
    }

    protected function populateState() {
        $app = JFactory::getApplication('com_teachpro');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_teachpro.edit.responses.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_teachpro.edit.responses.id', $id);
        }

        $this->setState('responses.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();

        if (isset($params_array['item_id'])) {
            $this->setState('responses.id', $params_array['item_id']);
        }

        $this->setState('params', $params);
    }

    /**
     * Method to get an object.
     *
     * @param   integer  $id  The id of the object to get.
     *
     * @return  mixed    Object on success, false on failure.
     */
    public function &getData($id = null) {
        if ($this->_item === null) {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('responses.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = ArrayHelper::toObject($properties, 'JObject');
            }
        }

        if (isset($this->_item->created_by)) {
            $this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
        }if (isset($this->_item->modified_by)) {
            $this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
        }

        return $this->_item;
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
    public function getTable($type = 'Responses', $prefix = 'TeachproTable', $config = array()) {
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');

        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Get the id of an item by alias
     *
     * @param   string  $alias  Item alias
     *
     * @return  mixed
     */
    public function getItemIdByAlias($alias) {
        $table = $this->getTable();

        $table->load(array('alias' => $alias));

        return $table->id;
    }

    /**
     * Method to check in an item.
     *
     * @param   integer  $id  The id of the row to check out.
     *
     * @return  boolean True on success, false on failure.
     *
     * @since    1.6
     */
    public function checkin($id = null) {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('responses.id');

        if ($id) {
            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param   integer  $id  The id of the row to check out.
     *
     * @return  boolean True on success, false on failure.
     *
     * @since    1.6
     */
    public function checkout($id = null) {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('responses.id');

        if ($id) {
            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = JFactory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Publish the element
     *
     * @param   int  $id     Item id
     * @param   int  $state  Publish state
     *
     * @return  boolean
     */
    public function publish($id, $state) {
        $table = $this->getTable();
        $table->load($id);
        $table->state = $state;

        return $table->store();
    }

    /**
     * Method to delete an item
     *
     * @param   int  $id  Element id
     *
     * @return  bool
     */
    public function delete($id) {
        $table = $this->getTable();

        return $table->delete($id);
    }

    
    public function isAlreadyAnswered($testId,$questionId)
    {
        
        // Get a db connection.
         $db = JFactory::getDbo();
        $result = false;
          $query = $db->getQuery(true);
        
          $query->select('*')
                ->from($db->quoteName('#__teachpro_answer', 'answer'))
               ->where($db->quoteName('answer.questionid') . '=' . $questionId)
                  ->where($db->quoteName('answer.id')." IN (SELECT "
                          .$db->quoteName("response.answerid")." FROM "
                          .$db->quoteName("#__teachpro_response","response")." WHERE "
                          .$db->quoteName("response.testid")." = ".$db->quote($testId).")");

        
        
        $db->setQuery($query);
        
        

        $totalNumberOfQuestions = $db->loadAssocList();
        
        if (count($totalNumberOfQuestions)>0)
            $result = true;
        
        return $result; 
    }
   
}
