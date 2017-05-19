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
class TeachproModelTestss extends JModelList {

    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'modified_by', 'a.modified_by'
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
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->getUserStateFromRequest('limitstart', 'limitstart', 0);
        $this->setState('list.start', $limitstart);

        if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array')) {
            foreach ($list as $name => $value) {
                switch ($name) {
                    case 'fullordering':
                        $orderingParts = explode(' ', $value);
                        if (count($orderingParts) >= 2) {
                            // Latest part will be considered the direction
                            $fullDirection = end($orderingParts);
                            if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', ''))) {
                                $this->setState('list.direction', $fullDirection);
                            }
                            unset($orderingParts[count($orderingParts) - 1]);
                            // The rest will be the ordering
                            $fullOrdering = implode(' ', $orderingParts);

                            if (in_array($fullOrdering, $this->filter_fields)) {
                                $this->setState('list.ordering', $fullOrdering);
                            }
                        } else {
                            $this->setState('list.ordering', $ordering);
                            $this->setState('list.direction', $direction);
                        }
                        break;
                    case 'ordering':
                        if (!in_array($value, $this->filter_fields)) {
                            $value = $ordering;
                        }
                        break;
                    case 'direction':
                        if (!in_array(strtoupper($value), array('ASC', 'DESC', ''))) {
                            $value = $direction;
                        }
                        break;
                    case 'limit':
                        $limit = $value;
                        break;
                    // Just to keep the default case
                    default:
                        $value = $value;
                        break;
                }
                $this->setState('list.' . $name, $value);
            }
        }
        // Receive & set filters
        if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array')) {
            foreach ($filters as $name => $value) {
                $this->setState('filter.' . $name, $value);
            }
        }
        $ordering = $app->input->get('filter_order');

        if (!empty($ordering)) {
            $list = $app->getUserState($this->context . '.list');
            $list['ordering'] = $app->input->get('filter_order');
            $app->setUserState($this->context . '.list', $list);
        }
        $orderingDirection = $app->input->get('filter_order_Dir');

        if (!empty($orderingDirection)) {
            $list = $app->getUserState($this->context . '.list');
            $list['direction'] = $app->input->get('filter_order_Dir');
            $app->setUserState($this->context . '.list', $list);
        }

        $list = $app->getUserState($this->context . '.list');

        if (empty($list['ordering'])) {
            $list['ordering'] = 'ordering';
        }

        if (empty($list['direction'])) {
            $list['direction'] = 'asc';
        }

        if (isset($list['ordering'])) {
            $this->setState('list.ordering', $list['ordering']);
        }

        if (isset($list['direction'])) {
            $this->setState('list.direction', $list['direction']);
        }
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return   JDatabaseQuery
     *
     * @since    1.6
     */
    protected function getListQuery() {


        $user = JFactory::getUser();
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $user = JFactory::getUser();

        $studentsubjectid = $this->getState("studentsubjectid");
        $top = $this->getState("top");

        // Select the required fields from the table.

        if ($top) {
            $query->order("a.id" . " DESC");
        }

        $query
                ->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
        );


        $query->from('`#__teachpro_test` AS a');

        // Join over the users for the checked out user.

        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the created by field 'created_by'
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        // Join over the created by field 'modified_by'
        $query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

        $query->join("INNER", "#__teachpro_student_subject as ss ON ss.id=a.student_subject_id");
        $query->join('INNER', '#__teachpro_student as s on ss.student_id=s.id');


        $query->where("s.userid=" . $user->id);

        if (!JFactory::getUser()->authorise('core.edit', 'com_teachpro')) {
            $query->where('a.state = 1');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            }
        }

        if (!empty($studentsubjectid)) {

            $query->where("ss.id=" . (int) $studentsubjectid);
        }



        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

//                echo $query->__toString();exit;

        return $query;
    }

    /**
     * Method to get an array of data items
     *
     * @return  mixed An array of data on success, false on failure.
     */
    public function getItems() {
        $items = parent::getItems();

        return $items;
    }

    /**
     * Overrides the default function to check Date fields format, identified by
     * "_dateformat" suffix, and erases the field if it's not correct.
     *
     * @return void
     */
    protected function loadFormData() {
        $app = JFactory::getApplication();
        $filters = $app->getUserState($this->context . '.filter', array());
        $error_dateformat = false;

        foreach ($filters as $key => $value) {
            if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null) {
                $filters[$key] = '';
                $error_dateformat = true;
            }
        }

        if ($error_dateformat) {
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
    private function isValidDate($date) {
        $date = str_replace('/', '-', $date);
        return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
    }

    public function getRecentTestIdByStudentSubjectId($studentSubjectId) {
        $db = $this->getDbo();

        $this->setState("studentsubjectid", $studentSubjectId);
        $this->setState("top", true);
        $query = $this->getListQuery();


        $db->setQuery($query, 0, 1);
        $item = $db->loadResult();
        return $item;
    }

    /**
     * Checks whether the test is completed by the student or not.
     * 
     * @param int $testId
     * @param int $studentSubjectId
     * @return int
     */
    public function getTestStatus($testId, $studentSubjectId) {

        $response = JModelItem::getInstance('Responses', 'TeachproModel', array('ignore_request' => true));

        $numberOfRespondedQuestions = $response->getNumberOfRespondedQuestions($testId);

        $totalNumberOfQuestions = $response->getTotalNumberOfQuestions($studentSubjectId);

        if ($totalNumberOfQuestions > $numberOfRespondedQuestions) {

            // Test is not completed.
            return 1;
        } else {

            // Test is completed.
            return 2;
        }
    }
    
    public function getAverageScore($testId) {

        $response = JModelItem::getInstance('Responses', 'TeachproModel', array('ignore_request' => TRUE));

        $averageScore = $response->getAverageScore($testId);

        return $averageScore;
    }


}