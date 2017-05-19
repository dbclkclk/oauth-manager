<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of report
 *
 * @author dclarke
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
jimport('joomla.filesystem.file');
jimport('html2pdf.html2pdf');

class TeachproModelReport extends JModelBase {
    //put your code here
    
    
    public function getParentProposalAndScorecard($testId)
    {
        return $this->generateReport($testId,true);
    }
    
    
    private function generateReport ($testId,$sendToCorporate)
    {
        $testModel = JModelList::getInstance("Testss", "TeachproModel");
        $goals = $this->getGoals($testId);
    
        $averageScore = $testModel->getAverageScore($testId);

        $test = $this->getTest($testId);
        
        $subjectStudent = $this->getSubjectStudent($test->student_subject_id);

        $subject = $this->getSubject($subjectStudent->subject_id);

        $student = $this->getStudent($subjectStudent->student_id);

        $parentProposal = $this->getParentProposal($subject, $averageScore, $test->start_date, $student, $goals);

        $scoreCard = $this->getScoreCard($goals, $averageScore, $test->start_date, $student, $subject->subject_name);

        $result = $this->createPdfs($scoreCard, $parentProposal, $testId);

       return $result;
    
    }
    

    /*     * *************************************************************************************************** */

    private function getTestId($studentsubjectid) {

        
        $model = JModelList::getInstance("Testss","TeachproModel");
        
        $testId =$model->getRecentTestIdByStudentSubjectId($studentsubjectid);
       
        if(!$testId)
        {
            $app = JFactory::getApplication();
            JFactory::getApplication()->enqueueMessage(JText::_('COM_TEACHPRO_REPORT_TEST_ERROR'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_teachpro&view=testss', false));
            
        }

        return $testId;
    }

    private function getSubject($subjectId) {

        $subject = JModelItem::getInstance('Subjects', 'TeachproModel', array('ignore_request' => TRUE));

        return $subject->getData($subjectId);
    }

    private function getStudent($studentId) {

        $student = JModelItem::getInstance('Students', 'TeachproModel', array('ignore_request' => TRUE));

        return $student->getData($studentId);
    }

    private function getGoalArrays($subjectId) {
        
        $goals = $this->getModel('Goalss', 'TeachproModel');

        return $goals->getGoals($subjectId);
    }

    private function getTest($testId) {

        $test = JModelItem::getInstance('Tests', 'TeachproModel', array('ignore_request' => TRUE));

        return $test->getData($testId);
    }

    private function getSubjectStudent($testId) {

        $subjectStudent = JModelItem::getInstance('SubjectStudents', 'TeachproModel', array('ignore_request' => TRUE));

        return $subjectStudent->getData($testId);
    }

    
    private function getGoals($testId) {

        $model = JModelItem::getInstance('Responses', 'TeachproModel');
        ///$response = $this->getModel('Responses', 'TeachproModel');

        $goals = $model->getScoreForGoals($testId);

        return $goals;
    }

    private function getParentProposalPath($testId) {

        return JPATH_COMPONENT_SITE . "/assets/parentProposal$testId.pdf";
    }

    private function getScoreCardPath($testId) {

        return JPATH_COMPONENT_SITE . "/assets/scoreCard$testId.pdf";
    }

    private function createPdfs($scoreCard, $parentProposal, $testId) {

        $result = array();
        
        $scoreCardPdf = new HTML2PDF();
        $scoreCardPdf->writeHTML($parentProposal);
        $scoreCardPdf->Output($this->getParentProposalPath($testId), 'F');
        array_push($result, $this->getScoreCardPath($testId));
        

        $parentProposalPdf = new HTML2PDF();
        $parentProposalPdf->writeHTML($scoreCard);
        $parentProposalPdf->Output($this->getScoreCardPath($testId), 'F');
        array_push($result,$this->getParentProposalPath($testId));
        
        return $result;
                
    }

    private function getParentProposal($subject, $averageScore, $testDate, $student, $goals) {

       // $goals = $this->getGoalArrays($subject->id);
        
        $displayData['subjectName'] = $subject->subject_name;
        $displayData['grade'] = $subject->grade;
        $displayData['averageScore'] = $averageScore;
        $displayData['testDate'] = $testDate;
        $displayData['student'] = $student;
        $displayData['goals'] = $goals;

        $layout = new JLayoutFile('parentproposal', NULL, array('component' => 'com_teachpro'));

        $parentProposal = $layout->render($displayData);

        return $parentProposal;
    }

    private function getScoreCard($goals, $averageScore, $testDate, $student, $subjectName) {

        $displayData['goals'] = $goals;
        $displayData['averageScore'] = $averageScore;
        $displayData['testDate'] = $testDate;
        $displayData['student'] = $student;
        $displayData['subjectName'] = $subjectName;

        $layout = new JLayoutFile('scorecard', NULL, array('component' => 'com_teachpro'));

        $scoreCard = $layout->render($displayData);

        return $scoreCard;
    }

    
    public function getScoreVerbiage($averageScore) {

        $layout = new JLayoutFile('scoreverbiage', NULL, array('component' => 'com_teachpro'));

        $scoreVerbiage = $layout->render($averageScore);

        return $scoreVerbiage;
    }
    public function deleteParentProposalAndScorecard($attachments)
    {
        foreach ($attachments as $attachment)
        {
            JFile::delete($attachment);
        }
    }
    
}
