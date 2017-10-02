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
 * Enrollment controller class.
 *
 * @since  1.6
 */
class TeachproControllerEnrollmentForm extends JControllerForm {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @return void
     *
     * @since    1.6
     */
    public function edit() {
        
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_teachpro.edit.enrollment.id');
        $editId = $app->input->getInt('id', 0);

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_teachpro.edit.enrollment.id', $editId);

        // Get the model.
        $model = $this->getModel('EnrollmentForm', 'TeachproModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=enrollmentform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return void
     *
     * @throws Exception
     * @since  1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('EnrollmentForm', 'TeachproModel');
        
        $timeSheetModel = $this->getModel('TimesheetForm', 'TeachproModel');
        
        
        
        
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');
        
         $timesheetData= $data["timesheet"];

	// Save the data in the session.
	$app->setUserState('com_teachpro.edit.enrollment.data', $data);
        
        $data = $this->validateForm($model, $data);
        
        $sanitizedTimesheetData = array();
        if(!$data)
            return;
        
        $test = true;
        foreach($timesheetData as $timesheet)
        {
           $result=  $this->validateForm($timeSheetModel, $timesheet);
           if($test)
           {
               if(!$result)
               {
                    $test = $result;
               }
               else
               {
                   array_push($sanitizedTimesheetData,$result);
               }
           }
        }
        if(!$test)
            return;
        
        
        $data["timesheet"] =$sanitizedTimesheetData;
        
        
        // Get the user data.
       
        $subjectStudentFormModel = JModelForm::getInstance('SubjectstudentForm', 'TeachproModel');   
        $studentSubject = $subjectStudentFormModel->getData($data['enrollment']['student_subject_id']);
        
        
        $studentSubject->schoolName =$data['subjectstudent']['schoolName'];
        $studentSubject->studentType =$data['subjectstudent']['studentType'];
        $studentSubject->medicationTaken =$data['subjectstudent']['medicationTaken'];
        $studentSubject->problem =$data['subjectstudent']['problem'];
        $studentSubject->glass =$data['subjectstudent']['glass'];
        $studentSubject->accomplishment =$data['subjectstudent']['accomplishment'];
        $studentSubject->attainmentGrade =$data['subjectstudent']['attainmentGrade'];
        $data['subjectstudent']=$studentSubject->getProperties();
        
        
        $data['enrollment']['student_id'] = $studentSubject->student_id;
        $data['parent']['student_id']= $studentSubject->student_id;
        $data['spouse']['student_id']= $studentSubject->student_id;

        // Attempt to save the data.
        $return = $this->savedata($data["enrollment"], $model);
        if($return)
        {
            $data["enrollment"]["id"] = $return;
            $data["paymentterms"]["enrollment_id"] = $return;
            $return = $this->saveParent($data['parent']);
            if($return)
            {
                $data['parent']["id"] = $return;
                
                if(!empty($data['spouse']['spouseName']) || !empty($data['spouse']['spouseName']) || !empty($data['spouse']['spouseOccupation']) || !empty($data['spouse']['spouseCellNumber']) || !empty($data['spouse']['spouseEmail']) || 
                        !empty($data['spouse']['spouseAddress']) || !empty($data['spouse']['spouseEmployer']))
                {
                    $return = $this->saveSpouse($data['spouse']);
                    $data['spouse']["id"] = $return;
                }
                $return = $this->saveStudentSubject($data['subjectstudent']);
                if($return)
                {
                    $data['subjectstudent']["id"]= $return;
                    $this->saveTimesheet($data, $data["enrollment"]["student_subject_id"], $data["teacherid"]);
                }
                       
             
                
            }
        }
        if(!$return)
            return;
        
        
      
      
        
        // Save the data in the session.
	$app->setUserState('com_teachpro.edit.enrollment.data', $data);
        
        // Clear the profile id from the session.
        $app->setUserState('com_teachpro.edit.enrollment.id', null);

        // Redirect to the list screen.
        $url = 'index.php?option=com_teachpro&task=enrollment.sendRecurringPaymentDetails';
        $this->setRedirect(JRoute::_($url, false));

        
    }

    /**
     * Method to abort current operation
     *
     * @return void
     *
     * @throws Exception
     */
    public function cancel() {
        $app = JFactory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_teachpro.edit.enrollment.id');

        // Get the model.
        $model = $this->getModel('EnrollmentForm', 'TeachproModel');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }

        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_teachpro&view=enrollment' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    /**
     * Method to remove data
     *
     * @return void
     *
     * @throws Exception
     */
    public function remove() {
        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('EnrollmentForm', 'TeachproModel');

        // Get the user data.
        $data = array();
        $data['id'] = $app->input->getInt('id');

        // Check for errors.
        if (empty($data['id'])) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_teachpro.edit.enrollment.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_teachpro.edit.enrollment.id');
            $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=enrollment&layout=edit&id=' . $id, false));
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_teachpro.edit.enrollment.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_teachpro.edit.enrollment.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=enrollment&id=' . $id, false));
        }

        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_teachpro.edit.enrollment.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_TEACHPRO_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_teachpro&view=enrollment' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_teachpro.edit.enrollment.data', null);
        
        
    }
    
    
    
    
    private function saveParent($data)
    {
	$model = $this->getModel('ParentForm', 'TeachproModel');
        return $this->savedata($data, $model);
    }
    
    
    
    private function saveSpouse($data)
    {
	// Initialise variables.
	$model = $this->getModel('SpouseForm', 'TeachproModel');
        return $this->savedata($data, $model);
    }
    
    
    private function saveStudentSubject($data)
    {
       $model = JModelForm::getInstance('SubjectstudentForm', 'TeachproModel');  
       return $this->savedata($data, $model);
    }
    
    private function savePaymentTerms($data)
    {
        $this->getModel("Paymentterms","TeachproModel");
        $paymentTermsFormModel = $this->getModel("PaymenttermsForm","TeachproModel");
        $result = $paymentTermsFormModel->save($data);
        return $result;
        
        
    }
    
    
    private function saveTimesheet($data, $subjectstudent, $teacher)
    {
        $timesheetFormModel = JModelForm::getInstance("TimesheetForm","TeachproModel");
        $timesheetModel = JModelForm::getInstance("Timesheet","TeachproModel");
       
        
        
        $param = new stdClass();
        $param->signupterms = $data["paymentterms"]["signupterms"];
        $param->timesheet = $data["timesheet"];
        $param->sessionWeek  = $data["paymentterms"]["total_weeks_for_sessions"];
        
        $numWeeklySessions=$timesheetModel->getNumWeeklySession($param);
        
        $dates= $timesheetModel->getStartDateEndDate($data['timesheet'],$numWeeklySessions);
        
        
       
        if(!$teacher)   
        {
            $teacher = $this->getAvailableTeacher($data, $dates);
            if($teacher)
                $teacher = $teacher->id;  
        }
       
       foreach($dates as $date)
       {
           $date['available_start_date'] =  $date['available_start_date']->toSql();
           $date['available_end_date'] =  $date['available_end_date']->toSql();
           $date['teacher'] = ($teacher=="" ? null : $teacher);
           $date['subject_student_id'] = $subjectstudent;
                      
           $timesheetFormModel->save($date);
       }
    }
    
    private function savedata($data, $model)
    {
        
        $app   = JFactory::getApplication();
  
	// Attempt to save the data.
	$return = $model->save($data);

	// Check for errors.
	if ($return === false)
	{

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_teachpro.edit.enrollment.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=enrollment&layout=edit&id=' . $id, false));            
        }

	// Check in the profile.
	if ($return)
	{
            $model->checkin($return);
	}

        return $return;
	
    }
    
    
    
    private function validateForm($model,$data ,$inSession=false)
    {
        
        
        $app   = JFactory::getApplication();
        
         // Validate the posted data.
	$form = $model->getForm();
	if (!$form)
	{
            throw new Exception($model->getError(), 500);
	}
	// Validate the posted data.
	$data = $model->validate($form, $data);
	// Check for errors.
	if ($data === false)
	{
            //the validation messages.
            $errors = $model->getErrors();

		// Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                    if ($errors[$i] instanceof Exception)
                    {
                        $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                    }
                    else
                    {
                        $app->enqueueMessage($errors[$i], 'warning');
                    }
            }
            
            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_teachpro.edit.enrollment.id');
            $this->setRedirect(JRoute::_('index.php?option=com_teachpro&view=enrollment&layout=edit&id=' . $id, false));
             return;
            
        }
        return $data;
    }
    private function getAvailableTeacher($data, $dates)
    {
        
        $availabilityModel = JModelList::getInstance("Teacheravailabilities","TeachproModel"); 
        $teachersModel = JModelList::getInstance("Teachers","TeachproModel");
        $subjectStudentFormModel = JModelForm::getInstance('SubjectstudentForm', 'TeachproModel');   
        $studentSubject = $subjectStudentFormModel->getData($data['enrollment']['student_subject_id']);
        
        
        $teachers = $teachersModel->getTeachersBySubject($studentSubject->subject_id);
        
        $availableTeachers = array();
        foreach($teachers as $teacher)
        {

            $users = JFactory::getUser($teacher->user_id);
            //check if user is NOT blocked or NOT activated yet
            if($users->block == '0' && empty($users->activation))
            {
                $available = $availabilityModel->isTeacherAvailable($teacher->id,$dates,$studentSubject->subject_id);
                if($available)
                {
                    array_push($availableTeachers, $teacher);
                }
            }
        }
        return $availableTeachers[0];
        
    }
    private function notififyAdministrator($data)
    {
        $mail = JFactory::getMailer();
        
        $mail->Sendmail("admin@teachpro.net","Enrollment","support@teachpro.net","A student has been enrollment");
        
    }

}
