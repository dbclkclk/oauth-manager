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
class TeachproModelGoalss extends JModelList {

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
                'modified_by', 'a.modified_by',
                'goalid', 'a.goalid',
                'name', 'a.name',
                'description', 'a.description',
                'checked_out', 'a.checked_out',
                'checked_out_time', 'a.checked_out_time',
                'subjectid', 'a.subjectid',
            );
        }

        parent::__construct($config);
    }

    /**
     * 
     * @param int $subjectId
     * @return mixed returns goals of a subject
     */
    public function getGoals($subjectId) {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*')
                ->from($db->quoteName('#__teachpro_goal', 'goal'))
                ->join('INNER', $db->quoteName('#__teachpro_subject', 'subject')
                        . ' ON (' . $db->quoteName('goal.subjectid') . ' = ' . $db->quoteName('subject.id') . ')')
                ->where($db->quoteName('subject.id') . '=' . $db->quote($subjectId));

        $db->setQuery($query);

        $goals = $db->loadAssocList();

        return $goals;
    }

}
