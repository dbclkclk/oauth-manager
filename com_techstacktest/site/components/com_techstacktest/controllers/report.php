<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

include JPATH_COMPONENT_SITE . '/controller.php';

jimport('joomla.filesystem.file');
jimport('html2pdf.html2pdf');

class TeachproControllerReport extends TeachproController {

    public function sendreport() {
        
        $testModel = JModelItem::getInstance("Tests","TeachproModel");
        
        $input = JFactory::getApplication()->input;
        $subjectstudentid = $input->get('studentsubjectid');
        $test = $testModel->getData(array("student_subject_id"=>$subjectstudentid));
        
        $session = JFactory::getSession();
        $registry = $session->get('registry');
        $emailModel = JModelLegacy::getInstance("Email","TeachproModel",$registry);
        $emailModel->test($test->id);
        
        $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=testss', false));
        JFactory::getApplication()->enqueueMessage(JText::_('COM_TEACHPRO_REPORT_EMAIL_SUCCESS'), 'success');

        
    }
    
    public function enrollment ()
    {
        
        $enrollmentModel = JModelItem::getInstance("Enrollment","TeachproModel");
        
        $input = JFactory::getApplication()->input;
        $enrollmentid = $input->get('enrollmentid');
        $enrollment = $enrollmentModel->getData($enrollmentid);
        
        $session = JFactory::getSession();
        $registry = $session->get('registry');

        $mailModel = JModelLegacy::getInstance("Email","TeachproModel",$registry);
        $mailModel->enrollment($enrollment->id);
    }
   
   
}
