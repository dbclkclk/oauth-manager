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
class TeachproViewEnrollment extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;
    protected $canSave;
    protected $student_subject_id;
    
    protected $student;
    protected $parent;
    
    protected $subject;

    public function display($tpl = NULL) {

        $enrollmentForm = JModelForm::getInstance('EnrollmentForm', 'TeachproModel');
        
        $studentModel = JModelItem::getInstance("Students", "TeachproModel");

        $this->setModel($enrollmentForm, TRUE);

        $app = JFactory::getApplication();
        $user = JFactory::getUser();

        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_teachpro');
        $this->canSave = $this->get('CanSave');
        $this->form = $this->get('Form');
        $this->parent = $user->getProperties();
        
        $profile = JUserHelper::getProfile($user->id);
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
       
        $this->student_subject_id = JFactory::getApplication()->input->get('id');
        
        
         $id = $this->student_subject_id;
        
        $subjectstudentModel = JModelItem::getInstance("SubjectStudents","TeachproModel");
        
        $studentsubject = $subjectstudentModel->getData($id);
        
        if($studentsubject==null)
            throw new Exception("Enrollment process can't continue"); 
        
        $this->subject = $studentsubject->subject_id;
        
        $this->student = $studentModel->getData($studentsubject->student_id);

        parent::display($tpl);
    }

}
