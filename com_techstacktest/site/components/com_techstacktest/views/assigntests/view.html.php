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
class TeachproViewAssignTests extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

        public  $childList;

        public $subjectList;

        public $gradeList;
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
            
                $modelList = JModelList::getInstance("Subjectstudentss","TeachproModel");
                $user = JFactory::getUser();
                
                $model = $this->getModel();
                
                $this->items = $modelList->getUnPaidForStudentSubject();
                
                $this->childList= $model->getChildList($user->id);
                $this->subjectList = $model->getSubjectList();
                $this->gradeList = $model->getAllGrade();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		parent::display($tpl);
	}  

	
	
        
        public function getStudentInfo ($id)
        {
               $studentModel = JModelItem::getInstance("Students","TeachproModel");
               
               $data = $studentModel->getData($id);
               
               $param = array("firstname"=>$data->firstname, "lastname"=>$data->lastname);
               
               return $param;
        }
        
        public function getSubjectGradeInfo ($id)
        {
            $subjectModel = JModelItem::getInstance("Subjects","TeachproModel");
         
            $data= $subjectModel->getData($id);
            $param = array("subject"=>array("id"=>$data->id, "subject_name"=>$data->subject_name), "grade"=>array("id"=>$data->grade, "grade"=>$data->grade));
            
            return $param;
            
        }

}
