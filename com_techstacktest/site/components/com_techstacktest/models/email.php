<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mail
 *
 * @author dclarke
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

       

use Joomla\Utilities\ArrayHelper;

class TeachproModelEmail extends JModelBase {
    
    
    public function enrollment($enrollmentid)
    {
            $studentSubjectModel = JModelItem::getInstance("SubjectStudents","TeachproModel");
            $enrollmentModel = JModelItem::getInstance("Enrollment","TeachproModel");
            
            //Get enrollment
            $enrollment = $enrollmentModel->getData($enrollmentid);
            
            //Get Student Subject
            $studentSubject =  $studentSubjectModel->getData($enrollment->student_subject_id);
        
            $this->enrollmentParent($studentSubject);
            $this->enrollmentTutor($studentSubject);
            $this->sendReportAfterEnrollment($enrollmentid);
        
    }
    public function test($testId)
    {
        $testModel = JModelItem::getInstance("Tests","TeachproModel");
        $studentSubjectModel = JModelItem::getInstance("SubjectStudents","TeachproModel");
        $studentModel = JModelItem::getInstance("Students","TeachproModel");
        $testModels = JModelList::getInstance("Testss", "TeachproModel");
    
        $session = JFactory::getSession();
        $registry = $session->get('registry');
        $reportModel = JModelLegacy::getInstance("Report","TeachproModel",$registry);
        
        $test = $testModel->getData($testId);
        
        $studentSubject = $studentSubjectModel->getData($test->student_subject_id);
        
        $student = $studentModel->getData($studentSubject->student_id);
        
        $user = JFactory::getUser($student->userid);
        
        $attachments=$reportModel->getParentProposalAndScorecard($test->id);
        $averageScore = $testModels->getAverageScore($test->id);
        
        $body = $reportModel->getScoreVerbiage($averageScore);
        $subject = JText::_("COM_TEACHPRO_REPORT_TEST_MAIL_SUBJECT");
        
        $this->sendEmail($body, $subject, $attachments, "no-reply@teachpro.net", $user);
        
        $reportModel->deleteParentProposalAndScorecard($attachments);
        
        
    }
    private function enrollmentParent ($studentSubject)
    {   
        $studentModel = JModelItem::getInstance("Students","TeachproModel");
            //get Student
        $student = $studentModel->getData($studentSubject->student_id);
        $user = JFactory::getUser($student->userid);
           
        $subject=JText::_("COM_TEACHPRO_ENROLLMENT_PARENT_EMAIL_SUBJECT");
        $body=JText::_("COM_TEACHPRO_ENROLLMENT_PARENT_EMAIL");
        
        $this->sendEmail($body, $subject, null, "no-reply@teachpro.net",$user);
    }
    
    private function enrollmentTutor ($studentSubject)
    {
        
        $timesheetModel = JModelItem::getInstance("Timesheet","TeachproModel");
        $timesheet = $timesheetModel->getData(array("subject_student_id"=>$studentSubject->id));
        
        if($timesheet->teacher)
        {
            $teacherModel = JModelItem::getInstance("Teacher","TeachproModel");
            $teacher = $teacherModel->getData($timesheet->teacher);
            
            $user = JFactory::getUser($teacher->user_id);

            $subject=JText::_("COM_TEACHPRO_ENROLLMENT_TUTOR_EMAIL_SUBJECT");
            $body = JText::_("COM_TEACHPRO_ENROLLMENT_TUTOR_EMAIL");
            
            $this->sendEmail($body, $subject, null,"no-reply@teachpro.net", $user);
            
        }
    }
    
    public function sendReportAfterEnrollment($enrollmentId)
    {       
        
       
        JLoader::register('TeachproFrontendHelper',JPATH_COMPONENT.DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."teachpro.php");
        
        $results = $this->getEnrollmentDetails($enrollmentId);
        $enrollmentModel = JModelItem::getInstance("Enrollment","TeachproModel");
        $studentSubjectModel = JModelItem::getInstance("SubjectStudents", "TeachproModel");
        $testModel = JModelItem::getInstance("Tests", "TeachproModel");
        
        $enrollment = $enrollmentModel->getData($enrollmentId);
        $studentSubject = $studentSubjectModel->getData($enrollment->student_subject_id);
        
        $test = $testModel->getData(array("student_subject_id"=>$studentSubject->id));
        
        
        $config = JFactory::getConfig();

        
        $session = JFactory::getSession();
        $registry = $session->get('registry');
          
        $reportModel = JModelLegacy::getInstance("Report","TeachproModel",$registry);
        
        
        $attachments = $reportModel->getParentProposalAndScorecard($test->id);
        
        $subject = JText::_("COM_TEACHPRO_ENROLLMENT_CORPORATE_EMAIL_SUBJECT_NOTIFICATION");
      
        $body = JText::sprintf("COM_TEACHPRO_ENROLLMENT_CORPORATE_EMAIL_NOTIFICATION",$results[0],$results[1],
                $results[2], $results[3], $results[4], $results[5], $results[6],
                $results[7], $results[8], $results[9],$results[10], $results[11], $results[12],
                $results[13], $results[14], $results[15],$results[16], $results[17], $results[18], $results[19], $results[20], $results[21], $results[22]);
       
        $test = json_encode($body);
        $recipient = TeachproFrontendHelper::getUserByUsername("admin");
        
        $emailModel = JModelLegacy::getInstance("Email","TeachproModel",$registry);
        $emailModel->sendEmail($body, $subject, $attachments, $config->get("mailfrom"), $recipient);
        
        $reportModel->deleteParentProposalAndScorecard($attachments);
        
    }
    
    
    public function sendEmail($body, $subject, $attachments, $sender, $recipient) {

        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $mailer->setSender($sender);

        $mailer->addRecipient($recipient->email, $recipient->name);
        //'Score Card, Parent Proposal and Score Verbiage'
        $mailer->setSubject($subject);
        $mailer->isHtml(TRUE);
        //$mailer->encoding = $message->encoding;
        $mailer->setBody($body);
        foreach($attachments as $attachment)
        {
            $mailer->addAttachment($attachment);
        }
        $send = $mailer->Send();
        return $send;
    }
    
    private function getEnrollmentDetails ($enrollmentId)
    {
        $enrollmentModel = JModelItem::getInstance("Enrollment", "TeachproModel");
        $enrollment = $enrollmentModel->getData($enrollmentId);
        
        $subjectStudentsModel = JModelItem::getInstance("Subjectstudents", "TeachproModel");
        $studentSubject = $subjectStudentsModel->getData($enrollment->student_subject_id);
        
        
        $studentModel = JModelItem::getInstance("Students","TeachproModel");
        $student = $studentModel->getData($studentSubject->student_id);
        
        $timesheetModel = JModelItem::getInstance("Timesheet", "TeachproModel");
        $timesheet = $timesheetModel->getData(array("subject_student_id"=>$studentSubject->id));
        
        $timesheetModels = JModelList::getInstance("Timesheets", "TeachproModel");
        $startDate = $timesheetModels->getStartDate($studentSubject->id);
        $timezone = new DateTimeZone("UTC");
        $startDate = new DateTime($startDate->available_start_date,$timezone);
        
        $blah2 = $startDate->format('r');
        
        $teacherModel = JModelItem::getInstance("Teacher","TeachproModel");
        $teacher = $teacherModel->getData($timesheet->teacher);
        
        
        $subjectModel = JModelItem::getInstance("Subjects","TeachproModel");
        $subject = $subjectModel->getData($studentSubject->subject_id);
        
        $parentModel = JModelItem::getInstance("Parent","TeachproModel");
        $parent = $parentModel->getData(array("student_id"=>$student->id));
        
        $user = JFactory::getUser($teacher->user_id);
        
        $teachertimezone = $user->getParam('timezone');
        $studenttimezone = new DateTimeZone($student->timezone);
        $startDate = $startDate->setTimezone($studenttimezone);
        
        
       $offset= $studenttimezone->getOffset($startDate);
        
        $offset = $offset/3600;
        
        $blah = $startDate->format('Y-m-d h:i a');
        
        $results = array($parent->parent_full_name, $student->firstname." ".$student->lastname,$parent->parent_full_name,
           $user->name,$subject->subject_name,$startDate->format("Y-m-d h:i a"),null, $student->city, $student->usstate, $student->zipcode,null,
            null,$student->timezone,$user->parent_address,null,null,$user->email,null,$teachertimezone,$subject->grade,$parent->parent_email,$parent->parent_cell_number);
        
        return $results;
    }
 }
