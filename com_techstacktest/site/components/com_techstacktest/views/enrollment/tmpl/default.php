<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('jquery.framework', false);

JHtml::_('behavior.formvalidator');


$document = JFactory::getDocument();


JHtml::script(Juri::base()."media/com_teachpro/js/fullcalendar/lib/moment.min.js");
JHtml::script(Juri::base()."media/com_teachpro/js/fullcalendar/fullcalendar.js");
JHtml::script(Juri::base()."media/com_teachpro/js/knockout-3.4.0.js");

JHtml::stylesheet(Juri::base()."media/com_teachpro/js/fullcalendar/fullcalendar.min.css",array(),true);
JHtml::stylesheet(Juri::base() . "media/com_teachpro/css/bootstrap-fullcalendar.css", array(), true);
JHtml::stylesheet(Juri::base()."media/com_teachpro/css/style.css",array(),true);


 JComponentHelper::getParams('com_teachpro')->get('numofdaysstarttutoring', '2');
 
 $termsofuse = Juri::base()."media/com_teachpro/assets/pdfs/legal/termsofuse.pdf";
 
 
 
            $dtz = new DateTimeZone($this->student->timezone);
            $time = new DateTime('now', $dtz);
            $offset= $dtz->getOffset( $time );
            $offset = $offset/3600;
            
            $daylightSavings = date("I")*-1;
            
         
            $east = -4+ $daylightSavings;
            $mountain = -6 + $daylightSavings;
            $central = -5 + $daylightSavings;
            $pacific = -7 + $daylightSavings;
            $hawaii = -9 + $daylightSavings;
            
            $location = "America/East";
            $starttime = "";
            $endtime = "";
            
            if($offset==$east)
            {
                $starttime="10:30:00";
                $endtime="20:15:00";
                $location="America/Eastern Standard Time";
            }
            if($offset==$central)
            {
                $starttime="10:45:00";
                $endtime="20:30:00";
                $location="America/Central Time";
            }
            if($offset==$mountain)
            {
                $starttime="11:00:00";
                $endtime="20:45:00";
                $location="America/Mountain Time";
            }
            if($offset==$pacific)
            {
               $starttime="10:00:00";
               $endtime="21:00:00";
               $location="America/Pacific Time";
            }
            if($offset==$hawaii)
            {
                $starttime="9:30:00";
                $endtime="20:30:00";
                $location="America/Hawaiian Time";
            }

?>

<div class="container-fluid">
    
    <div class="row-fluid">
<h2>Step 4: Student Enrollment Form</h2>
<h3>Go through the process below to get your child registered. Click each step to proceed...</h3>
<?php echo JLayoutHelper::render('default_header', array('view' => $this), dirname(__FILE__)); ?>
<br />
<br />

<form id="form-students" class="well"
      action="<?php echo JRoute::_('index.php?option=com_teachpro&task=enrollmentform.save&student_subject_id=14'); ?>"
      method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

    
     <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('heard',"enrollment"); ?></div>
        <div class="form-inline"><?php echo $this->form->getInput('heard',"enrollment"); ?><span data-bind="visible: isOtherRerral "><?php echo $this->form->getInput('otherReferral',"enrollment"); ?></span></div>
    </div>
    

    <div class="control-group">
        <div class="control-label"> <?php echo $this->form->getLabel('parent_full_name',"parent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('parent_full_name',"parent", $this->parent['name']); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"> <?php echo $this->form->getLabel('parent_occupation',"parent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('parent_occupation',"parent"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('parent_cell_number',"parent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('parent_cell_number',"parent"); ?></div>
    </div>
    
    <div class="container">
            <h3>
                <span>Select Tutoring Time Slot</span> &nbsp;
            </h3>
        <h4>Earliest start date for tutoring: <span data-bind="text: startDate.format('MMMM Do YYYY')"></span></h4>
    </div>
     
    
    <div class="container">
        <h4 class="text-info text-capitalize">Calendar displayed for <?php echo $location ?></h4>
    </div>
    <div id='calendar' data-bind="fullCalendar: calendarViewModel"></div>
    <br />
    <div class="control-group">
            <div class="control-label"> <?php echo $this->form->getLabel('parent_email',"parent"); ?></div>
            <?php 
                if($this->parent)
                {
            ?>
            <div class="controls"><?php echo $this->form->getInput('parent_email','parent',$this->parent['email']); ?></div>
            <?php 
                }
                else
                {
                  ?>
                       <div class="controls"><?php echo $this->form->getInput('parent_email','parent'); ?></div>
           
                <?php   
                }
                ?>
    
    </div>

    <div class="control-group">
            <div class="control-label">  <?php echo $this->form->getLabel('parent_address',"parent"); ?></div>
             <?php 
                if($this->parent)
                {
            ?>
            
                <div class="controls"> <?php echo $this->form->getInput('parent_address','parent'); ?></div>
             <?php 
                }
                else
                {
                  ?>
    
                         <div class="controls"> <?php echo $this->form->getInput('parent_address','parent'); ?></div>
                 <?php   
                }
                ?>
    </div>

    <div class="control-group">
            <div class="control-label">  <?php echo $this->form->getLabel('parent_employer',"parent"); ?></div>
            
             <?php 
                if($this->parent)
                {
            ?>
            
            <div class="controls"> <?php echo $this->form->getInput('parent_employer',"parent"); ?></div>
            <?php 
                }
                else
                {
                  ?>
                    <div class="controls"> <?php echo $this->form->getInput('parent_employer',"parent"); ?></div>
                    
                   <?php   
                }
                ?>  
                    
    </div>

    <h3>Spouse's Detail</h3>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('spouseName',"spouse"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('spouseName',"spouse"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"> <?php echo $this->form->getLabel('spouseOccupation',"spouse"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('spouseOccupation',"spouse"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('spouseCellNumber',"spouse"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('spouseCellNumber',"spouse"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('spouseEmail',"spouse"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('spouseEmail',"spouse"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('spouseAddress',"spouse"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('spouseAddress',"spouse"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('spouseEmployer',"spouse"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('spouseEmployer',"spouse"); ?></div>
    </div>

    <h3>Fill information of the student</h3>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('schoolName',"subjectstudent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('schoolName',"subjectstudent"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('studentType',"subjectstudent"); ?></div>
        <div class="radio-inline"><?php echo $this->form->getInput('studentType',"subjectstudent"); ?></div>

    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('medicationTaken',"subjectstudent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('medicationTaken',"subjectstudent"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('problem',"subjectstudent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('problem',"subjectstudent"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('glass',"subjectstudent"); ?></div>
        <div class="radio-inline"><?php echo $this->form->getInput('glass',"subjectstudent"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('accomplishment',"subjectstudent"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('accomplishment',"subjectstudent"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('attainmentGrade',"subjectstudent"); ?></div>
        <div class="radio-inline"><?php echo $this->form->getInput('attainmentGrade',"subjectstudent"); ?></div>

    </div>

    <div class="control-group">
        <div class="control-label"> <?php echo $this->form->getLabel('sessions_per_week',"paymentterms"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('sessions_per_week',"paymentterms"); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"> <?php echo $this->form->getLabel('signupterms',"paymentterms"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('signupterms',"paymentterms"); ?></div>
    </div>

    <div class="control-group sessionWeekContainer">
       <div class="control-label"> <?php echo $this->form->getLabel('total_weeks_for_sessions',"paymentterms"); ?></div>
       <div class="controls"><?php echo $this->form->getInput('total_weeks_for_sessions',"paymentterms"); ?></div>
    </div>

    <div class="control-group">
        <div class="controls"><?php echo $this->form->getInput('payment_frequency',"paymentterms"); ?></div>
    </div>


    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('firstPayment'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('firstPayment'); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"> <?php echo $this->form->getLabel('total_cost',"paymentterms"); ?></div>
        <div class="controls"><?php echo $this->form->getInput('total_cost',"paymentterms"); ?></div>
    </div>
    
    <div class="control-group">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-lg-2">
                <h3>Find a Tutor</h3>
            </div>
            <div class="col-md-2 col-sm-2 col-lg-2 padding-top-3">
                 <br />
                  <button type="button" class="btn btn-success" data-bind="click:teacherSearch, css:{disabled:disableTeacherSearch()==false}">
                    <span class="glyphicon glyphicon-search"></span> Teachers
                </button>
            </div>  
        </div>
        
    </div>
    <div class="control-group">
        <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12" data-bind="visible: selectedTeacher().name()!=null">
                        <h5><span><b>Selected Teacher : </b></span><span data-bind="text:selectedTeacher().name"></span> <a data-toggle="tooltip" title="Delete selected teacher" href="#" data-bind="click:removeSelectTeacher"><span class="glyphicon glyphicon-remove" ></span></a></h5>
                    </div>
        </div>
    </div>
    <br />
    <div class="control-group">
        <div class="controls"><?php echo $this->form->getInput('student_subject_id',"enrollment", $this->student_subject_id); ?></div>
        <?php echo $this->form->getInput('id',"enrollment"); ?>
        <?php echo $this->form->getInput('id',"parent"); ?>
        <?php echo $this->form->getInput('id',"spouse"); ?>
        <?php echo $this->form->getInput('id',"subjectstudent"); ?>
        <?php echo $this->form->getInput('teacherid'); ?>
        <?php echo $this->form->getInput("id","paymentterms"); ?>
        
        <div data-bind="foreach:selectedDates">
            <?php echo $this->form->getInput('teacherstartdate',"timesheet"); ?>
            <?php echo $this->form->getInput('teacherenddate',"timesheet"); ?>
        
        </div>
        
    </div>
    
   
    
    <div class="control-group">
        <div class="controls">
            <div class="checkbox">
                &nbsp; &nbsp; &nbsp; <?php echo $this->form->getInput("termsofuse"); ?> <label>Have you read our terms of service agreement? <a target="_blank" href="<?php echo $termsofuse ?>"><span class="glyphicon glyphicon-download-alt"></span><a/></label>
                
            </div>   
        </div>
    </div>
    
    <div class="control-group">
        
        <div class="controls">
            
            <input type="submit" data-bind="css:{disabled:termsofuse()==false}" class="button btn btn-primary" value="Submit">
            
        </div>
    

    </div>
    
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="option" value="com_teachpro" />
    <input type="hidden" name="task" value="enrollmentform.save" />
</form>


<div class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Select an available teacher</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
              <thead>
                  <th>Select teacher</th>
                  <th>Name</th>
                  <th>Educational Background</th>
                  <th>Experience</th>
              </thead>
              <tbody data-bind="foreach: teachers">
                  <tr>
                      <td>
                          <input type="radio" name="teacherselect" data-bind="click: $parent.setSelectedTeacher" />
                      </td>
                      <td data-bind="text:name"></td>
                      <td data-bind="text: education"></td>
                      <td data-bind="text: experience"></td>
                  </tr>
              </tbody>
              <tfoot data-bind="if: teachers().length === 0">
                  <tr>
                      <td colspan="4">No Teacher available for those times</td>
                  </tr>
                  <tr>
                      <td colspan="4">We can pear a teacher with your automatically and send you a notification when it's complete. Just leave this section blank</td>
                  </tr>
              </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
        </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</div>

</div>


<script>

    
    // Document ready
    $(document).ready(function () {
        
        
        jQuery(".paymentMethod").hide();
        jQuery(".noCommitmentPaymentMethod").hide();
        jQuery(".sessionWeekContainer").hide();
    });

    // Display "other" text input when user selects "other" in selection dropdown.
    jQuery(".others").click(function () {

        jQuery(".otherReferral").show();
    });

    // Hide "other" text input when user selects other option values.
    jQuery(".heardOption").click(function () {

        jQuery(".otherReferral").hide();
    });
    
    
    function TeacherViewModel(data)
    {
        var self = this;
        self.id = ko.observable(data.id);
        self.name = ko.observable(data.name);
        self.photo = ko.observable(data.photo);
        self.experience = ko.observable(data.experience);
        self.education = ko.observable(data.education);
        self.startDate = ko.observable();
        self.endDate = ko.observable();
    }
    
    function EnrollmentViewModel ()
    {
        var self = this;
      
        self.teachers = ko.observableArray(); 
        self.selectedDates = ko.observableArray(); 
        self.subject = ko.observable(<?php echo $this->subject ?>);
        self.totalWeeksforSessions = ko.observable(1);
        self.startDate = moment().add(<?php echo JComponentHelper::getParams('com_teachpro')->get('numofdaysstarttutoring', '2'); ?>,"days");
        self.refferal = ko.observable();
        self.typeOfSession = ko.observable();
        self.paymentMethod = ko.observable();
        self.noCommitmentPaymentMethod = ko.observable();
        self.committedPrice = ko.observable(<?php echo JComponentHelper::getParams('com_teachpro')->get('commitment_price'); ?>);
        self.unCommittedPrice = ko.observable(<?php echo JComponentHelper::getParams('com_teachpro')->get('uncommitment_price'); ?>);
        
        self.selectedTeacher = ko.observable(new TeacherViewModel({}));
        
         self.termsofuse = ko.observable(false);
        
        self.availableDates = [];
        
        
        <?php

            if($offset==$east)
            {
            
        ?>
        self.availableTimes = [{startTime:{h:10, m:30}, endTime:{h:11,m:30}},{startTime:{h:11,m:45}, endTime:{h:12,m:45}},
            {startTime:{h:13,m:00}, endTime:{h:14,m:00}},{startTime:{h:14,m:15}, endTime:{h:15,m:15}},{startTime:{h:15,m:30}, endTime:{h:16,m:30}},
            {startTime:{h:16,m:45}, endTime:{h:17,m:45}},{startTime:{h:18,m:00}, endTime:{h:19,m:00}},{startTime:{h:19,m:15}, endTime:{h:20,m:15}}];
        
        <?php
            }
            if($offset==$central)
            {
        ?>  
         self.availableTimes = [{startTime:{h:10, m:45}, endTime:{h:11,m:45}},{startTime:{h:12,m:00}, endTime:{h:13,m:00}},
            {startTime:{h:13,m:15}, endTime:{h:14,m:15}},{startTime:{h:14,m:30}, endTime:{h:15,m:30}},{startTime:{h:15,m:45}, endTime:{h:16,m:45}},
            {startTime:{h:17,m:00}, endTime:{h:18,m:00}},{startTime:{h:18,m:15}, endTime:{h:19,m:15}},{startTime:{h:19,m:30}, endTime:{h:20,m:30}}];

        <?php
        
            }
            if($offset==$mountain)
            {
        ?>      
         self.availableTimes = [{startTime:{h:11, m:00}, endTime:{h:12,m:00}},{startTime:{h:12,m:15}, endTime:{h:13,m:15}},
            {startTime:{h:13,m:30}, endTime:{h:14,m:30}},{startTime:{h:14,m:45}, endTime:{h:15,m:45}},{startTime:{h:16,m:00}, endTime:{h:17,m:00}},
            {startTime:{h:17,m:15}, endTime:{h:18,m:15}},{startTime:{h:18,m:30}, endTime:{h:19,m:30}},{startTime:{h:19,m:45}, endTime:{h:20,m:45}}];

        <?php
        
            }

            if($offset==$pacific)
            {
        ?>      
         self.availableTimes = [{startTime:{h:10, m:00}, endTime:{h:11,m:00}},{startTime:{h:11,m:15}, endTime:{h:12,m:15}},
            {startTime:{h:12,m:30}, endTime:{h:13,m:30}},{startTime:{h:13,m:45}, endTime:{h:14,m:45}},{startTime:{h:15,m:00}, endTime:{h:16,m:00}},
            {startTime:{h:16,m:15}, endTime:{h:17,m:15}},{startTime:{h:17,m:30}, endTime:{h:18,m:30}},{startTime:{h:18,m:45}, endTime:{h:19,m:45}}, {startTime:{h:20,m:00}, endTime:{h:21,m:00}}];

        <?php
        
            }
            if($offset==$hawaii)
            {
         ?>
                 
         self.availableTimes = [{startTime:{h:09, m:30}, endTime:{h:10,m:30}},{startTime:{h:10,m:45}, endTime:{h:11,m:45}},
            {startTime:{h:12,m:00}, endTime:{h:13,m:00}},{startTime:{h:13,m:15}, endTime:{h:14,m:15}},{startTime:{h:14,m:30}, endTime:{h:15,m:30}},
            {startTime:{h:15,m:45}, endTime:{h:16,m:45}},{startTime:{h:17,m:00}, endTime:{h:18,m:00}},{startTime:{h:18,m:15}, endTime:{h:19,m:15}}];

        <?php
        
            }
        ?>
        
        
        
        var availableTimesCount = 0;
        var dayCount = 1;
        var dateCalculator =  moment().utcOffset(<?php echo $offset ?>).startOf("week");
        var counter = 0;
        
        while(dayCount<=7)
        {
           
            while(availableTimesCount<self.availableTimes.length)
            {
                var start  = dateCalculator.set({hour:self.availableTimes[availableTimesCount].startTime.h, minute:self.availableTimes[availableTimesCount].startTime.m}).clone();   
              
                var end = dateCalculator.set({hour:self.availableTimes[availableTimesCount].endTime.h, minute:self.availableTimes[availableTimesCount].endTime.m}).clone();
              
                self.availableDates.push({start:start.clone(), end:end.clone(), className:'available'});
                //Counter
                availableTimesCount++;
                counter++;
                
            }
            //Get next day
            dateCalculator = dateCalculator.add(1, "days");
            dayCount++;
            availableTimesCount=0;
        }
        
        self.setSelectedTeacher = function (){
            
            self.selectedTeacher(this);
            return true;
        };
        
        self.isOtherRerral = ko.computed(function(){
            
            if(self.refferal()==="Others")
                return true;
            else
                return false;
            
        });
        self.evaluateDate = function(date)
        {
            return date.toString();
        };
        
        self.calendarViewModel = new ko.fullCalendar.viewModel({
           header: {
                left: '',
                center: '',
                right: ''
            },
            columnFormat: {
                week: 'ddd'
            },
            minTime: '<?php echo $starttime ?>',
            maxTime: '<?php echo $endtime ?>',
            slotLabelFormat:"h:mm a",
            selectable: false,
            selectHelper: false,
            editable: false,
            eventStartEditable: false,
            allDaySlot: false,
            defaultView: 'agendaWeek',
            slotDuration: '00:15:00',
            allDay: false,
            events:self.availableDates,
            slotEventOverlap: false,
            dayClick: function(date,JSevent, view) {
                    $('#calendar').fullCalendar('clientEvents', function(event) {
                      
                        if(event.start <= date && event.end >= date) {
                            return true;
                        }
                        return false;
                    });
            },
            select: function (start, end)
            {
                
                var minutes = end.diff(start, "minutes");
                $('#calendar').fullCalendar('unselect');
                if (minutes === 60)
                {
                    $('#calendar').fullCalendar('renderEvent', {
                        start: start,
                        end: end,
                        allDay: false
                    },
                     true 
                    );
            
                    self.selectedDates.push({startDate:start, endDate:end});
                }
            },
            eventRender: function(event, element) {
               
               element.find(".fc-bg").html("<span class='glyphicon glyphicon-ok-sign'></span>");
             
                element.click(function() {
                    if($(this).hasClass("available"))
                    {
                        self.selectedDates.push({startDate:event.start, endDate:event.end});
                    }
                    else
                    {
                        self.removeDate(event);
                    }
                    $(this).toggleClass("available");
                });
               
            }
        });

        
        self.enablePayment = ko.computed(function()
        {
             if(self.typeOfSession()=="")
             {
                jQuery(".sessionWeekContainer").hide();
                jQuery(".paymentMethod").hide();
                
                self.paymentMethod(null);
                self.totalWeeksforSessions(1);
                
                //self.totalPayment(0);
                jQuery(".firstPayment").val('');
             }
             else
             {
                
                 jQuery(".paymentMethod").show();
                 if(self.typeOfSession()==="commitment")
                 {
                    self.paymentMethod(null);
                    jQuery(".sessionWeekContainer").hide();;
                    
                 }
                 else
                 {
                     if(self.typeOfSession()==="noCommitment")
                     {
                        jQuery(".sessionWeekContainer").show();
                        self.paymentMethod(0);
                     }
                 }
             }
        });
        
        self.removeDate = function (date)
        {
            var mappedDates = ko.utils.arrayFilter(self.selectedDates(), function(item) {
                if(item!=null)
                {
                    if(item.startDate.unix()!=date.start.unix() && item.endDate.unix()!=date.end.unix())
                        return item;
                }
            });
            self.selectedDates.removeAll();
            ko.utils.arrayPushAll(self.selectedDates,mappedDates);
            
        };
        
        self.sessionsPerWeek = ko.computed(function()
        {
            return  parseInt(this.selectedDates().length);
        },self);
        
        self.firstPayment = ko.computed(function(){
            
            var firstPayment = 0;
            
            
            var costPerSession = this.committedPrice();
            
            
            if(this.typeOfSession()=="noCommitment")
            {
                costPerSession=this.unCommittedPrice();
                    
            }
            
            if(this.typeOfSession()!="")
            {
                // Committed Payment
                if(this.paymentMethod()=="weekly_payment")
                {
                    firstPayment = '$' + this.sessionsPerWeek()* costPerSession;
                    
                }
                if(this.paymentMethod()=="biweekly_payment")
                {
                    var frequency = 2;
                    if(this.typeOfSession()=="noCommitment")
                    {
                        if(frequency>this.totalWeeksforSessions())
                            frequency = this.totalWeeksforSessions();
                    }
                    
                    firstPayment = '$' + this.sessionsPerWeek() * costPerSession * frequency;

                }
                if(this.paymentMethod()=="monthly_payment")
                {
                   
                    var frequency = 4;
                    if(this.typeOfSession()=="noCommitment")
                    {
                        if(frequency>this.totalWeeksforSessions())
                            frequency = this.totalWeeksforSessions();
                    }
                    firstPayment = '$' + this.sessionsPerWeek() * costPerSession * frequency;

                }
                
                
                if(this.paymentMethod()=="all_at_once_payments")
                {
                    if(this.typeOfSession()=="commitment")
                        firstPayment = '$' + 20 *  costPerSession;
                    else
                    {
                        if(this.typeOfSession()=="noCommitment")
                            firstPayment = '$' + this.totalWeeksforSessions() * this.sessionsPerWeek() * costPerSession;
                    }
                    
                }
                
            }
            
            //Set first payment to null regardless if committed or uncommitted
            if(this.paymentMethod()=="")
            {
                firstPayment ='';
                 
            }
            return firstPayment;
            
        },self);
        
        
        self.totalPayment =ko.computed(function(){

            var totalPayment = 0;
            
            if(this.typeOfSession()=="noCommitment")
                 totalPayment = '$' + this.totalWeeksforSessions() * this.sessionsPerWeek() * this.unCommittedPrice();
  
            if(this.typeOfSession()=="commitment")
                totalPayment = '$' + 20 *  this.committedPrice();
            
            return totalPayment;
            
        },this);
        
        
        self.disableTeacherSearch = ko.computed(function(){
            var result = false;
            if(self.selectedDates().length>0)
            {
                if(self.typeOfSession()!="" && self.typeOfSession()!=null)
                {
                    if(self.typeOfSession()!="commitment")
                    {
                        if(self.totalWeeksforSessions()!="" && self.totalWeeksforSessions()!=null)
                        {
                            result =true;
                        }
                    }
                    else
                        result = true;
                }
            }
            return result;
        });
      
        
        self.teacherSearch = function()
        {
            var teachersURL = "<?php echo JRoute::_("index.php?option=com_teachpro&task=timesheet.getTimesheet&studentsubjectid=") ?>"+"<?php echo JFactory::getApplication()->input->get("id") ?>&format=json";
            self.teachers.removeAll();
            var frequency = null;
            
             if(this.typeOfSession()=="noCommitment")
                 frequency = self.totalWeeksforSessions();
  
            if(this.typeOfSession()=="commitment")
                frequency = 20;
            
            
            $.ajax({
                    url:teachersURL,
                    contentType:"application/json",
                    method:"POST",
                    data:ko.toJSON({dates:self.selectedDates(),frequency:frequency, subject:self.subject(), signupterms:self.typeOfSession()}),
                    success:function(data)
                    {
                        
                        var transformList = ko.utils.arrayMap(data.data, function(obj){return new TeacherViewModel(obj); });
                      
                        ko.utils.arrayPushAll(self.teachers, transformList);
                        $(".modal").modal('show');
                         
                    }
            });
        };
        
        
        self.removeSelectTeacher = function()
        {
             self.selectedTeacher(new TeacherViewModel({}));
        };
        
        self.changesUpdateTeacher = ko.computed(function(){
            
                self.selectedDates();
                self.totalWeeksforSessions();
                
               self.removeSelectTeacher();
                
        });
    }
    
    ko.fullCalendar = {
        // Defines a view model class you can use to populate a calendar
        viewModel: function(configuration) {
            this.events = configuration.events;
            this.header = configuration.header;
            this.editable = configuration.editable;
            this.minTime = configuration.minTime;
            this.maxTime = configuration.maxTime;
            this.slotLabelFormat = configuration.slotLabelFormat;
            this.slotDuration = configuration.slotDuration;
            this.columnFormat= configuration.columnFormat;
            this.selectable= configuration.selectable;
            this.selectHelper=configuration.selectHelper;
            this.editable=configuration.editable;
            this.eventStartEditable=configuration.eventStartEditable;
            this.allDaySlot=configuration.allDaySlot;
            this.defaultView=configuration.defaultView;
            this.allDay=configuration.allDay;
            this.slotEventOverlap=configuration.slotEventOverlap;
            this.dayClick=configuration.dayClick;
            this.select=configuration.select;
            this.eventRender = configuration.eventRender;
            this.events = configuration.events;
            
            
            
        }
    };

    // The "fullCalendar" binding
    ko.bindingHandlers.fullCalendar = {
            // This method is called to initialize the node, and will also be called again if you change what the grid is bound to
            update: function(element, viewModelAccessor) {
                var viewModel = viewModelAccessor();
                element.innerHTML = "";

                $(element).fullCalendar({
                    events: ko.utils.unwrapObservable(viewModel.events),
                    header: viewModel.header,
                    editable: viewModel.editable,
                    columnFormat: viewModel.columnFormat,
                    minTime: viewModel.minTime,
                    maxTime: viewModel.maxTime,
                    selectable: viewModel.selectable,
                    selectHelper: viewModel.selectHelper,
                    eventStartEditable: viewModel.eventStartEditable,
                    allDaySlot: viewModel.allDaySlot,
                    defaultView: viewModel.defaultView,
                    slotDuration: viewModel.slotDuration,
                    allDay: viewModel.allDay,
                    slotLabelFormat :viewModel.slotLabelFormat,
                    slotEventOverlap: viewModel.slotEventOverlap,
                    dayClick: viewModel.dayClick,
                    select: viewModel.select,
                    eventRender:viewModel.eventRender,
                    events:viewModel.events
                   
                    
                });
            }
    };
    
    ko.applyBindings(new EnrollmentViewModel());
   
</script>
