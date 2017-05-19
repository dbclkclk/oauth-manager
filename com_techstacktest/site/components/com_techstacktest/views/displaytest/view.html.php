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
 * View class for a Assigning Test To Child
 *
 * @since  1.6
 */
class TeachproViewDisplayTest extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

        protected $studentDetails;
        
        protected $subjectstudentid;
        
        protected $totalQuestions;
  
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
		$app = JFactory::getApplication();
                
                $this->state      = $this->get('State');
                
                $jinput = JFactory::getApplication()->input;
                $studentSubjectId = $jinput->get('id', '0', 'INT');
                
                $this->subjectstudentid =$studentSubjectId;
                
                //Load Subect Student
                $subjectStudentModel = JModelItem::getInstance('SubjectStudents', 'TeachproModel', array('ignore_request'=>true));
                $studentSubject= $subjectStudentModel->getData($studentSubjectId);
                
                
                //Load Subject 
                $subjectModel = JModelItem::getInstance('Subjects', 'TeachproModel', array('ignore_request'=>true));
                $subject= $subjectModel->getData($studentSubject->subject_id);
                
                
                //Load Student 
                $studentModel = JModelItem::getInstance('Students', 'TeachproModel', array('ignore_request'=>true));
                $student= $studentModel->getData($studentSubject->student_id);
                
                
                //Container 
                $a = new stdClass;
                $a->studentFirstName= $student->firstname;
                $a->studentLastName= $student->lastname;
                $a->subjectName = $subject->subject_name;
                $a->studentGrade= $subject->grade;
                $a->subjectId = $subject->id;
                
                $this->studentDetails = $a;
                
                
                $model = $this->getModel();
                $model->setState("subjectid",$subject->id);
		
                
                $questions = $model->getItems("Items");
                
                $model->setState("questions",$questions);
                
		$result = $this->getQuestionsAnswers($questions);
                
                //Check if test completed and redirect 
                $this->checkIfTestCompleted($result);
                
                $this->items = $result;
               
		$this->pagination = $this->get('Pagination');
		$this->params     = $app->getParams('com_teachpro');


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->_prepareDocument();
		parent::display($tpl);
	}  
        
        
        /**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_TEACHPRO_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
        
        private function checkIfTestCompleted($questions)
        {
            $app = JFactory::getApplication();
            if(count($questions)<=0)
            {
               $app->enqueueMessage('Test has already been completed', 'Notice');
               $app->redirect("index.php?option=com_teachpro&view=testss"); 
            }
        }
	
        private function getQuestionsAnswers($questions) 
        {
            
            $list =array();
            $questionsList = array();
            $counter =0;
            $totalQuestions = 0;
              
            JPluginHelper::importPlugin('content');
            $dispatcher = JEventDispatcher::getInstance();
      
            $responsesModel = JModelList::getInstance("Responses","TeachproModel");
            $answerModel = JModelList::getInstance("Answers", "TeachproModel");
            
            $filterQuestions= array();
            
            $testId = $this->getRecentTestId();
            
            $index = 1;
            
            foreach($questions as $question)
            {    /// foor loop to loop through all values and only put uniq goals 
                 
                $addQuestionAnswer = false;
                if (!$responsesModel->isAlreadyAnswered($testId,$question->questionId))
                {
                                $totalQuestions++;
                                $article = new stdClass;
                                $article->text = &$question->questionName;
                                $dispatcher->trigger('onContentPrepare', array('some.context', &$article, &$params, 0));


                                $multipleAnswers = array();
                                
                                $answers = $answerModel->getItemsByQuestion($question->questionId);
                              
                                foreach($answers as $answer)
                                {   // for  loop to  loop all and only find questions of that particualr goal
                                    if($answer->correct)
                                        array_push($multipleAnswers,true);
                                }
                                $question->answers = array();
                                $question->answers = $answers;
                                
                                $question->typeOfAnswer = null;
                                if(count($multipleAnswers)>1)
                                    $question->typeOfAnswer=0;
                                else
                                    $question->typeOfAnswer=1;
                                
                                $question->index = $index;

                                $addQuestionAnswer = true;
                                
                }
                          
                if($addQuestionAnswer)
                {
                    array_push($filterQuestions, $question);
                }
                $addQuestionAnswer = false; 
                $index++;
             }
             
             return $filterQuestions;
           
           }
           
           private function  getRecentTestId()
           {
               
                $jinput = JFactory::getApplication()->input;
                $studentSubjectId = $jinput->get('id', '0', 'INT');
        
                $model = JModelList::getInstance("Testss","TeachproModel");
        
                $testId =$model->getRecentTestIdByStudentSubjectId($studentSubjectId);
                

                return $testId;
       
           }
        
}
