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
/**
 * Students controller class.
 *
 * @since  1.6
 */

require_once JPATH_COMPONENT . '/controller.php';


class TeachproControllerResponse extends TeachproController
{
	
        
        public function save()
        { 
            
            $jinput = JFactory::getApplication()->input;
            $studentsubject = $jinput->get("subjectstudentid");

            
            $studentsubjectModel = JModelItem::getInstance("SubjectStudents","TeachproModel");
            
            //Test if user payed for this course
            $model = $studentsubjectModel->getData($studentsubject);
            
            
            $testssModel = JModelList::getInstance("Testss","TeachproModel");
           
            $testId = $testssModel->getRecentTestIdByStudentSubjectId($model->id);
            
            
            
            $responsesFormModel = JModelForm::getInstance("ResponsesForm","TeachproModel");
            
             $responsesModel = JModelForm::getInstance("Responses","TeachproModel");
          
            
             $json = new JInputJSON;
             
            $responses = $json->getArray(array("answerId"=>"INT"),null);
            
            $question = $json->getArray(array("questionId"=>"INT"),null);
            
            
          
            
            foreach($responses as $key => $value)
            {
                        
                $param = array("testid"=>$testId,"answerid"=>$value);
                
                $result = $responsesModel->isAlreadyAnswered($testId, $question["questionId"]);
                
                if(!$result)
                {
                    $response = $responsesFormModel->getData($param);

                    if(!$response)
                    {
                        $responsesFormModel->save($param);
                    }
                }
                
            }
            
            echo new JResponseJson(true);
            
        }
}