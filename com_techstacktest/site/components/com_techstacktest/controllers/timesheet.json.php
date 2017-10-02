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


class TeachproControllerTimesheet extends TeachproController
{
	
        
        public function getTimesheet()
        { 
            
            $availableTeachers = array();
            $availabilityModel = JModelList::getInstance("Teacheravailabilities","TeachproModel"); 
            $timesheetsModel = JModelList::getInstance("Timesheets", "TeachproModel");
            $timesheetModel = JModelForm::getInstance("Timesheet","TeachproModel");
            $teacherDetailModel = JModelItem::getInstance("Teacherdetail", "TeachproModel");
            $teachersModel = JModelList::getInstance("Teachers","TeachproModel");
            
            
            try {
                
                $json = new JInputJSON;

                $request = $json->getArray(array(),null);
               
                $teachers = $teachersModel->getTeachersBySubject($request["subject"]);
                
                //Construct param
                $param = new stdClass();
                $param->signupterms = $request["signupterms"];
                $param->timesheet = $request["dates"];
                $param->sessionWeek  = $request["frequency"];
                
                
                $numWeeklySessions=$timesheetModel->getNumWeeklySession($param);
                
                
                $param->timesheet= $this->convertDates($param->timesheet);
                
                $dates= $timesheetModel->getStartDateEndDate($param->timesheet,$numWeeklySessions);
               
                foreach($teachers as $teacher)
                {
                    if($teacher->user_id!=null)
                    {

                        $users = JFactory::getUser($teacher->user_id);
                        //check if user is NOT blocked or NOT activated yet
                        if($users->block == '0' && empty($users->activation))
                        {

                           $available = $availabilityModel->isTeacherAvailable($teacher->id, $dates,$request["subject"] );

                           if($available)
                           {
                                   $availableTeacher=new stdClass();
                                  // $teacherDetail = $teacherDetailModel->getData(array("teacher_id"=>$users->id));

                                   $availableTeacher->name = $users->name;
                                   $availableTeacher->id = $teacher->id;
                                   $availableTeacher->education = "";//"$teacherDetail->education;"
                                   $availableTeacher->experience = ""; // $teacherDetail->experience;

                                   array_push($availableTeachers, $availableTeacher); 

                           }

                        }
                    }

                }
                
            } 
            catch (Exception $ex) 
            {
                echo new JResponseJson(null,$ex->getMessage(),true);
                return;
            }
            echo new JResponseJson($availableTeachers,true);
        }
        
        private function convertDates ($dates)
        {
            $newArray = array();
            $count = 0;
            foreach($dates as $key => $value) {
               $newArray[$count]["teacherstartdate"] = $value["startDate"];
               $newArray[$count]["teacherenddate"] = $value["endDate"];
               $count++;
            }
            return $newArray;
        }
      
        
}