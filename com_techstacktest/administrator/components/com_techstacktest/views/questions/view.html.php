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
 * View to edit
 *
 * @since  1.6
 */
class TeachproViewQuestions extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;
        
        protected $answers;

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
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

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
	 
	protected function getAnswers($id)
        {
            If($id!=null)
            {
                $goalModels = JModelList::getInstance('Answerss', 'TeachproModel', array('ignore_request'=>true));
                

                $this->answers = $goalModels->getAnswersByQuestion($id);
            }
                
            
        }
	
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = TeachproHelpersTeachpro::getActions();

		JToolBarHelper::title(JText::_('COM_TEACHPRO_TITLE_ANSWERS'), 'students.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('questions.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('questions.save', 'JTOOLBAR_SAVE');
		}

		if (!$checkedOut && ($canDo->get('core.create')))
		{
			JToolBarHelper::custom('questions.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolBarHelper::custom('questions.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		// Button for version control
		if ($this->state->params->get('save_history', 1) && $user->authorise('core.edit')) {
			JToolbarHelper::versions('com_teachpro.questions', $this->item->id);
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('questions.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('questions.cancel', 'JTOOLBAR_CLOSE');
		}
	}
        
        public function getSummaryText($sid)    
        {
            $summaryModel = JModelForm::getInstance('Summary', 'TeachproModel', array('ignore_request'=>true));
            $result = $summaryModel->getSummaryText($sid);
            return $result;
        }
        
        
        public function getGradeAndSubject($goalId)
        {
            $goalModel =JModelItem::getInstance("Goals","TeachproModel", array('ignore_request'=>true));
            $goal = $goalModel->getItem($goalId);
            
            
            $subjectModel =JModelItem::getInstance("Subjects","TeachproModel", array('ignore_request'=>true));
            $subject = $subjectModel->getItem($goal->subjectid);
            
            $a = new stdClass();
            $a->currentGrade = $subject->grade;
            $a->currentSubject = $subject->id;
            
            return $a;
        }
        
        public function getRelatedSubjects($grade)
        {
            
              $model = JModelList::getInstance("Subjectss", "TeachproModel");
                           
               $results =$model->getSubjectsByGrade($grade);
               return $results;
        }
        
        
        public function getRelatedGoals($subject)
        {
            
            $model = JModelList::getInstance("Goalss", "TeachproModel");
                           
            $results =$model->getGoalsBySubject($subject);
            return $results;
        }
}
