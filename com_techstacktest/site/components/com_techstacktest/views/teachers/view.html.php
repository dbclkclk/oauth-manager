<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for student enrollment.
 *
 * @since  1.6
 */
class TeachproViewTeachers extends JViewLegacy {
    
    protected $state;
    protected $item;
    protected $form;
    protected $params;
    protected $canSave;
    
    public function display($tpl = NULL) {

        parent::display($tpl);
    }
}