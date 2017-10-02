<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Teachpro.
 *
 * @since  1.6
 */
class TeachproViewTeacheravailability extends JViewLegacy
{
	protected $state;

	protected $item;

        protected $items;
        
	protected $form;
        
        protected $grades;
        
        protected $teachers;
        
        protected $subjects;
        
        protected $selectedGrade;
        
        protected $selectedSubject;
        
        protected $selectedTeacher;
        /**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->form  = $this->get('Form');
                
                //Selected Teacher
                $teacher = JFactory::getApplication()->input->get('teacher',0, 'INT');
                
                //Selected Subject
                $subject = JFactory::getApplication()->input->get('subject',0, 'INT');
                $this->selectedSubject = $subject;
                
                //Selected Grade
                $subjectsModel = JModelList::getInstance("Subjects", "TeachproModel");
                $subject =  $subjectsModel->getItem($subject);
                $this->selectedGrade = $subject->grade;
                
                
                //List of Grades
                $subjectsListModel = JModelList::getInstance("Subjectss", "TeachproModel");
                $this->grades=  $subjectsListModel->getGrades();
               
               
               
               //List of subjects
                $this->subjects = $subjectsListModel->getSubjectsByGrade($subject->grade);
            
                
                //List of teachers
                $model = JModelList::getInstance("Teachers", "TeachproModel");
                $this->teachers =$model->getTeachersBySubject($subject->id);

               
                //List of dates selected as available by teacher
                $modelList = JModelList::getInstance("Teacheravailabilities", "TeachproModel");
                $this->items = $modelList->getAvailabilitiesByTeacherAndSubject($teacher, $subject->id);
                
                
                //Get teacher record
                $teacherModel = JModelItem::getInstance("Teacher", "TeachproModel");
                $this->selectedTeacher = $teacherModel->getItem($teacher);
               
                // Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	 
	
	
	protected function addToolbar()
	{
		//JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item == null || $this->item->id==0);

             
		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = TeachproHelpersTeachpro::getActions();

		JToolBarHelper::title(JText::_('COM_TEACHPRO_TITLE_TEACHER_AVAILABILITY'), 'students.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('teacheravailability.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('teacheravailability.save', 'JTOOLBAR_SAVE');
		}

		if (!$checkedOut && ($canDo->get('core.create')))
		{
			//JToolBarHelper::custom('timesheet.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			//JToolBarHelper::custom('timesheet.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('teacher.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('teacher.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
