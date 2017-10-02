<?php

defined('_JEXEC') or die();

jimport( 'joomla.application.component.controller' );

class TeachproControllerTests extends JControllerForm
{
	public function save($key = NULL, $urlVar = NULL)
	{
            
            JSession::checkToken( 'get' ) or die( 'Invalid Token' );
            
            $jinput = JFactory::getApplication()->input;
            $params['student_subject_id'] = $jinput->get('id', '', 'INT');
           
            $now = JFactory::getDate();
            
            $params['start_date'] = $now->toSql();
           
            $id = $jinput->get('id','', 'INT');
 
            $empty = array_filter($params);
            
            $subjectStudentModel = JModelItem::getInstance('Subjectstudents', 'TeachproModel', array('ignore_request'=>true));
            
            $array = array("id"=>$id,"");
            
            if($subjectStudentModel->getData($id))
            {
                if(!empty($empty)) 
                {
                  $modelList =   $subjectStudentModel = JModelItem::getInstance('Testss', 'TeachproModel', array('ignore_request'=>true));
                  $record = $modelList->getRecentTestIdByStudentSubjectId($id);
                  if($record==false)
                  {
                      $modelForm = $this->getModel('TestsForm');
                      $modelForm->save($params);
                  }
                }
                $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=displaytest&id='.$id, false));
            }
            else
            {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_TEACHPRO_SUBJECT_STUDENT_PAYMENT_NOT_COMPLETE'), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=assigntests', false));
                
            }
        }
}