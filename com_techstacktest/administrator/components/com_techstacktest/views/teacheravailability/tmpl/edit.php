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


JHtml::_('jquery.framework', false);

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

// Import CSS
$document = JFactory::getDocument();

JHtml::stylesheet(Juri::root()."media/com_teachpro/js/fullcalendar/fullcalendar.min.css",array(),true);
JHtml::stylesheet(Juri::root() . "media/com_teachpro/css/bootstrap-fullcalendar.css", array(), true);
JHtml::stylesheet(Juri::root()."media/com_teachpro/css/style.css",array(),true);


JHtml::script(Juri::root()."media/com_teachpro/js/knockout-3.4.0.js");
JHtml::script(Juri::root()."media/com_teachpro/js/fullcalendar/lib/moment.min.js");
JHtml::script(Juri::root()."media/com_teachpro/js/fullcalendar/moment-timezone.js");
JHtml::script(Juri::root()."media/com_teachpro/js/fullcalendar/fullcalendar-diff.min.js");

 

$grades=json_encode(array_values($this->grades));
 


$user      = JFactory::getUser($this->selectedTeacher->user_id);
$timezone = $user->getParam('timezone');


    $dtz = new DateTimeZone($timezone);
    $time = new DateTime('now', $dtz);
    $offset= $dtz->getOffset( $time );
    $offsetSeconds = $offset;
    $offset = $offset/3600;
    
    $daylightSavings=0;
    
    $daylightSavings = date("I") * -1;
    $east = -4 + $daylightSavings;
    $mountain = -6 + $daylightSavings;
    $central = -5 + $daylightSavings;
    $pacific = -7 + $daylightSavings;
    $hawaii = -9 + $daylightSavings;
            
    $location = "America/East";
    $starttime = "";
    $endtime = "";
            
            if($offset==$east)
            {
                $location="America/Eastern Standard Time";
            }
            if($offset==$central)
            {
                $location="America/Central Time";
            }
            if($offset==$mountain)
            {
                $location="America/Mountain Time";
            }
            if($offset==$pacific)
            {
               $location="America/Pacific Time";
            }
            if($offset==$hawaii)
            {
                $location="America/Hawaiian Time";
            }
    


$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_teachpro');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_teachpro&task=teacheravailability.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'teacheravailabilityList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}


$availabilities = json_encode($this->items);

$availableTeachers = json_encode($this->teachers);

$availableSubjects =  json_encode($this->subjects);


?>


<script type="text/javascript">
	//js = jQuery.noConflict();
	

	Joomla.submitbutton = function (task) {
		if (task == 'teacher.cancel') {
			Joomla.submitform(task, document.getElementById('students-form'));
		}
		else {
			
			if (task != 'teacher.cancel' && document.formvalidator.isValid(document.id('students-form'))) {
				
				Joomla.submitform(task, document.getElementById('students-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
      $(document.body).on('change', '#jform_summary_dropdown', function (){
        var id = $(this).val(); 
        $.ajax({method: "GET",
            url: "index.php?option=com_teachpro&task=summary.getSummaryText",
            data:{id: id}
        }).done(function( msg ) {
            $('#jform_summary_textbox').text(msg);
        });
            
      });
</script>

    <div class="container" title="Event Details">
        <form action="<?php echo JRoute::_('index.php?option=com_teachpro&layout=edit'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="form-validate">

           <div class="row">
               <div class="span2">
               </div>
                <div class="span3">
                    <H3>Select Grade *</h3>  
                    <select id="jform_grade" name="grade" id="select_subject" data-bind="chosen:grades, optionsText:'name', optionsValue:'name', value:selectedGrade, optionsCaption:'Select Grade', event:{change:fetchSubject}">
                        <option value="">--select grade--</option>
                    </select>  
                </div>
                <div class="span3">
                    <h3>Select Subject *</h3>
                    <select id="jform_subject" name="subject" data-bind="chosen:subjects, optionsText:'name', optionsValue:'id', value:selectedSubject, optionsCaption:'Select Subject', event:{change:fetchTeacher}">
                        <option value="">--select subject--</option>
                    </select>         
                </div>
               
                <div class="span3">
                    <h3>Select Tutor *</h3>
                    <select id="jform_teacher" name="teacher" data-bind="chosen:teachers, optionsText:'name', optionsValue:'id', value:selectedTeacher, optionsCaption:'Select Tutors'">
                        <option value="">--select tutors--</option>
                    </select>         
                </div>
               
            </div>
            <div class="row">  
                <br />
                <br />
                <div class="container">
                    <h2 class="text-info">Calendar displayed for <?php echo $location ?></h2>
                </div>
                <div class="container">
                    <div id='calendar' data-bind="fullCalendar: calendarViewModel">
                    </div>
                </div>
            </div>
            <div  class="span12 control-group" data-bind="foreach:selectedDates">
                   <input type="hidden" data-bind="value:id,attr: { name: 'jform[calendar]['+$index()+'][id]'}"  />
                   <input type="hidden" data-bind="value:subject_id, attr: { name: 'jform[calendar]['+$index()+'][subject_id]'}" />
                   <input type="hidden" data-bind="value:teacher_id, attr: { name: 'jform[calendar]['+$index()+'][teacher_id]'}" />
                   <input type="hidden "data-bind="value:$parent.getOffsetDate(start_date), attr: { name: 'jform[calendar]['+$index()+'][start_date]'}"  />
                   <input type="hidden" data-bind="value:$parent.getOffsetDate(end_date), attr: { name: 'jform[calendar]['+$index()+'][end_date]'}"  />
            </div>
            <input type="hidden" name="task" value="teacheravailability.save"/>
            <?php echo JHtml::_('form.token'); ?>
            
        </form>
    </div>



 <script>
     
     
     $(document).ready(function(){
    
        $("#jform_teacher").trigger("liszt:updated");

       $("#jform_subject").trigger("liszt:updated");
       
       $("#jform_grade").trigger("liszt:updated");
       

   });

     
     
    function GradeViewModel(grade)
    {
        var self = this;
        self.name = ko.observable(grade.grade);
    }
     
    function SubjectViewModel(subject)
    {
        var self = this;
        self.id = ko.observable(subject.id);
        self.name = ko.observable(subject.subject_name);
    }
     
    function TeacherViewModel (data)
    {
         var self = this;
         self.name = ko.observable(data.name);
         self.id= ko.observable(data.id);
    }
     
     function AvailablityViewModel (data)
     {
        var self = this;
        self.id = ko.observable(data.id);
        self.start_date = ko.observable(data.start_date);
        self.end_date = ko.observable(data.end_date);
        //Teacher
        if(ko.isObservable(data.teacher_id))
            self.teacher_id = data.teacher_id;
        else
            self.teacher_id = ko.observable(data.teacher_id);
        //Subject
        if(ko.isObservable(data.subject_id))
            self.subject_id = data.subject_id;
        else
            self.subject_id = ko.observable(data.subject_id);
     }
 
    function TeacherSubjectViewModel(data)
    {
        
       self = this;
       
       self.selectedDates = ko.observableArray(ko.utils.arrayMap(data.availabilities, function(obj) {
           
           obj.start_date = moment(obj.start_date);
           
           //obj.start_date.add(<?php echo (1*$offsetSeconds) ?>,"seconds");
            obj.start_date.utcOffset(<?php echo ($offset)  ?>);
          
           
           obj.end_date = moment(obj.end_date);
           //obj.end_date.add(<?php echo (1*$offsetSeconds) ?>,"seconds");
           obj.end_date.utcOffset(<?php echo ($offset)  ?>);
           
           
           var availablityViewModel = new AvailablityViewModel(obj);
           return availablityViewModel;
       }));
       
      
       
       self.subjects = ko.observableArray(ko.utils.arrayMap(data.subjects, function(obj) {
           var subject = new SubjectViewModel(obj);
           return subject;
       }));
       
        self.teachers =  ko.observableArray(ko.utils.arrayMap(data.teachers, function(obj) {
           var teacher = new TeacherViewModel(obj);
           return teacher;
       }));
       
       self.grades = ko.observableArray(ko.utils.arrayMap(data.grades, function(obj) {
           var grade = new GradeViewModel(obj);
           return grade;
       }));
       
       self.selectedSubject = ko.observable(<?php echo $this->selectedSubject ?>);
       self.selectedTeacher = ko.observable(<?php echo $this->selectedTeacher->id ?>);
       self.selectedGrade = ko.observable ("<?php echo $this->selectedGrade ?>");
        
       
       
      self.fetchSubject = function()
      {
            var subjectURL= "<?php echo JRoute::_("index.php?option=com_teachpro&task=Subjectss.getsubjects&format=json&grade=", false); ?>"+self.selectedGrade();
            $.getJSON(subjectURL, function(result) { 
                   
                self.subjects.removeAll();
                var data = ko.utils.arrayMap(result.data, function(obj) {
                        var subject = new SubjectViewModel(obj);
                        return subject;
                });
                ko.utils.arrayPushAll(self.subjects, data);
            });
      };
      
      self.fetchTeacher = function()
      {
            var teacherURL= "<?php echo JRoute::_("index.php?option=com_teachpro&task=teachers.getTeachers&format=json&subject=", false); ?>"+self.selectedSubject();
            $.getJSON(teacherURL, function(result) { 
                self.teachers.removeAll();
                var data = ko.utils.arrayMap(result.data, function(obj) {
                        var teacher = new TeacherViewModel(obj);
                        return teacher;
                });
                ko.utils.arrayPushAll(self.teachers, data);
            });
      };
       
       
       self.availableDates = [];

       ko.utils.arrayForEach(self.selectedDates(),function(selectedDate){
           
           var startDate = selectedDate.start_date().clone();
           var endDate = selectedDate.end_date().clone();
           
           startDate.add("<?php echo $offsetSeconds ?>","seconds");
           endDate.add("<?php echo $offsetSeconds ?>","seconds");
          
           
           self.availableDates.push({start:startDate.toISOString(), end:endDate.toISOString(), className:'available'});
           
       });
       
       <?php
        if($offset==$east)
        {
       ?>
            var businessHours = [
                 {start: '00:15',end: '01:15',dow: [0,1,2,3,4,5,6]},
                 {start: '01:30',end: '02:30',dow: [0,1,2,3,4,5,6]},
                 
                 {start: '10:30',end: '11:30',dow: [0,1,2,3,4,5,6]},
                 {start: '11:45',end: '12:45',dow: [0,1,2,3,4,5,6]},
                 {start: '13:00',end: '14:00',dow: [0,1,2,3,4,5,6]},
                 {start: '14:15',end: '15:15',dow: [0,1,2,3,4,5,6]},
                 {start: '15:30',end: '16:30',dow: [0,1,2,3,4,5,6]},
                 {start: '16:45',end: '17:45',dow: [0,1,2,3,4,5,6]},
                 {start: '18:00',end: '19:00',dow: [0,1,2,3,4,5,6]},
                 {start: '19:15',end: '20:15',dow: [0,1,2,3,4,5,6]},
                 {start: '20:30',end: '21:30',dow: [0,1,2,3,4,5,6]},
                 {start: '21:45',end: '22:45',dow: [0,1,2,3,4,5,6]},
                // {start: '23:00',end: '24:00',dow: [0,1,2,3,4,5,6]}
             ];
        <?php 
        }
        ?>
                
        <?php
        if($offset==$central)
        {
       ?>
            var businessHours = [
                
                 {start: '09:30',end: '10:30',dow: [0,1,2,3,4,5,6]},
                 {start: '10:45',end: '11:45',dow: [0,1,2,3,4,5,6]},
                 {start: '12:00',end: '13:00',dow: [0,1,2,3,4,5,6]},
                 {start: '13:15',end: '14:15',dow: [0,1,2,3,4,5,6]},
                 {start: '14:30',end: '15:30',dow: [0,1,2,3,4,5,6]},
                 {start: '15:45',end: '16:45',dow: [0,1,2,3,4,5,6]},
                 {start: '17:00',end: '18:00',dow: [0,1,2,3,4,5,6]},
                 {start: '18:15',end: '19:15',dow: [0,1,2,3,4,5,6]},
                 {start: '19:30',end: '20:30',dow: [0,1,2,3,4,5,6]},
                 {start: '20:45',end: '21:45',dow: [0,1,2,3,4,5,6]},
                 {start: '22:00',end: '23:00',dow: [0,1,2,3,4,5,6]}
             ];
        <?php 
        }
        ?>  
                
          <?php
        if($offset==$mountain)
        {
       ?>
            var businessHours = [
                 
                {start: '08:30',end: '09:30',dow: [0,1,2,3,4,5,6]},
                {start: '09:45',end: '10:45',dow: [0,1,2,3,4,5,6]},
                {start: '11:00',end: '12:00',dow: [0,1,2,3,4,5,6]},
                {start: '12:15',end: '13:15',dow: [0,1,2,3,4,5,6]},
                {start: '13:30',end: '14:30',dow: [0,1,2,3,4,5,6]},
                {start: '14:45',end: '15:45',dow: [0,1,2,3,4,5,6]},
                {start: '16:00',end: '17:00',dow: [0,1,2,3,4,5,6]},
                {start: '17:15',end: '18:15',dow: [0,1,2,3,4,5,6]},
                {start: '18:30',end: '19:30',dow: [0,1,2,3,4,5,6]},
                {start: '19:45',end: '20:45',dow: [0,1,2,3,4,5,6]},
                {start: '21:00',end: '22:00',dow: [0,1,2,3,4,5,6]},
             ];
        <?php 
        }
        ?>
                
                  <?php
        if($offset==$pacific)
        {
       ?>
            var businessHours = [
                 
                {start: '07:30',end: '08:30',dow: [0,1,2,3,4,5,6]},
                {start: '08:45',end: '9:45',dow: [0,1,2,3,4,5,6]},
                {start: '10:00',end: '11:00',dow: [0,1,2,3,4,5,6]},
                {start: '11:15',end: '12:15',dow: [0,1,2,3,4,5,6]},
                {start: '12:30',end: '13:30',dow: [0,1,2,3,4,5,6]},
                {start: '13:45',end: '14:45',dow: [0,1,2,3,4,5,6]},
                {start: '15:00',end: '16:00',dow: [0,1,2,3,4,5,6]},
                {start: '16:15',end: '17:15',dow: [0,1,2,3,4,5,6]},
                {start: '17:30',end: '18:30',dow: [0,1,2,3,4,5,6]},
                {start: '18:45',end: '19:45',dow: [0,1,2,3,4,5,6]},
                {start: '20:00',end: '21:00',dow: [0,1,2,3,4,5,6]},
                {start: '21:15',end: '22:15',dow: [0,1,2,3,4,5,6]},
                {start: '22:30',end: '23:30',dow: [0,1,2,3,4,5,6]},
             ];
        <?php 
        }
        ?>
                
                  <?php
        if($offset==$hawaii)
        {
       ?>
            var businessHours = [
                {start: '04:30',end: '05:30',dow: [0,1,2,3,4,5,6]},
                {start: '05:45',end: '6:45',dow: [0,1,2,3,4,5,6]},
                {start: '07:00',end: '08:00',dow: [0,1,2,3,4,5,6]},
                {start: '08:15',end: '09:15',dow: [0,1,2,3,4,5,6]},
                {start: '09:30',end: '10:30',dow: [0,1,2,3,4,5,6]},
                {start: '10:45',end: '11:45',dow: [0,1,2,3,4,5,6]},
                {start: '12:00',end: '13:00',dow: [0,1,2,3,4,5,6]},
                {start: '13:15',end: '14:15',dow: [0,1,2,3,4,5,6]},
                {start: '14:30',end: '15:30',dow: [0,1,2,3,4,5,6]},
                {start: '15:45',end: '16:45',dow: [0,1,2,3,4,5,6]},
                {start: '17:00',end: '18:00',dow: [0,1,2,3,4,5,6]},
                {start: '18:15',end: '19:15',dow: [0,1,2,3,4,5,6]},
                {start: '19:30',end: '20:30',dow: [0,1,2,3,4,5,6]},
                
             ];
        <?php 
        }
        ?>
                
        
       
       
        
        self.calendarViewModel = new ko.fullCalendar.viewModel({
            columnFormat: {
                week: 'ddd'
            },
//            minTime: '10:30:00',
//            maxTime: '20:15:00',
            slotLabelFormat:"h:mm a",
            selectable: true,
            selectHelper: true,
            editable: true,
            eventStartEditable: false,
            allDaySlot: false,
            defaultView: 'agendaWeek',
            slotDuration: '00:15:00',
            allDay: false,
            events:self.availableDates,
            slotEventOverlap: false,
            dayClick: function(date,JSevent, view) {

                        var count = 1;

                        $('#calendar').fullCalendar('clientEvents', function(event) {

                            if(event.start <= date && event.end >= date) {
                                return true;
                            }
                            return false;
                        });
            },
            eventRender:function(event, element) {

                        element.click(function() {
                           $('#calendar').fullCalendar('removeEvents',event._id);
                           
                            self.removeDate(event);

                        });
            },
            businessHours: businessHours,
            select:function (start, end)
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
                    
                            
                            start = start.add(<?php echo (-1*$offsetSeconds) ?>,"seconds");
                            start.utcOffset(<?php echo $offset ?>);
                            end = end.add(<?php echo (-1*$offsetSeconds) ?>,"seconds");
                            end.utcOffset(<?php echo $offset ?>);
               
               
                            var obj = new AvailablityViewModel({id:null,start_date:start, end_date:end, teacher_id:self.selectedTeacher, subject_id:self.selectedSubject});
                            self.selectedDates.push(obj);
                        }
            }
            
        });
        
        
        
        self.removeDate = function (date)
        {
            var calendar = $('#calendar').fullCalendar('getCalendar');
            
            date.start = date.start.add(<?php echo (-1*$offsetSeconds) ?>,"seconds");
            date.start.utcOffset(<?php echo $offset ?>);
           
            date.end = date.end.add(<?php echo (-1*$offsetSeconds) ?>,"seconds");
            date.end.utcOffset(<?php echo $offset ?>);
                            
            var mappedDates = ko.utils.arrayFilter(self.selectedDates(), function(item) {
                var result = true;
                 
                if(item!=null)
                {
                    // console.log(calendar.moment().utc(item.start_date()).unix()==date.start.unix() && calendar.moment().utc(item.end_date()).unix()==date.end.unix());
                   if(item.start_date().utc().unix()==date.start.utc().unix() && item.end_date().utc().unix()==date.end.utc().unix())
                    {
                        result =  false;
                    }
                }
              
                return result;
            });
            self.selectedDates.removeAll();
            ko.utils.arrayPushAll(self.selectedDates,mappedDates);
            
            
        };       
        
        
        self.getOffsetDate = function(date)
        {
            return date;
        }
        
    };
    
     ko.fullCalendar = {
        // Defines a view model class you can use to populate a calendar
        viewModel: function(configuration) {
            this.events = configuration.events;
            this.header = configuration.header;
            this.editable = configuration.editable;
//            this.minTime = configuration.minTime;
//            this.maxTime = configuration.maxTime;
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
            this.dayClick = configuration.dayClick;
            this.eventRender  = configuration.eventRender;
            this.businessHours = configuration.businessHours;
            this.select = configuration.select;
        }
    };

    // The "fullCalendar" binding
    ko.bindingHandlers.fullCalendar = {
            // This method is called to initialize the node, and will also be called again if you change what the grid is bound to
            update: function(element, viewModelAccessor) {
                var viewModel = viewModelAccessor();
                element.innerHTML = "";

                $(element).fullCalendar({
                    header: viewModel.header,
                    editable: viewModel.editable,
                    columnFormat: viewModel.columnFormat,
//                    minTime: viewModel.minTime,
//                    maxTime: viewModel.maxTime,
                    selectable: viewModel.selectable,
                    selectHelper: viewModel.selectHelper,
                    eventStartEditable: viewModel.eventStartEditable,
                    allDaySlot: viewModel.allDaySlot,
                    defaultView: viewModel.defaultView,
                    slotDuration: viewModel.slotDuration,
                    allDay: viewModel.allDay,
                    slotLabelFormat :viewModel.slotLabelFormat,
                    slotEventOverlap: viewModel.slotEventOverlap,
                    selectConstraint: 'businessHours',
                    eventConstraint: 'businessHours',
                    eventSources: [
                        {
                            events:viewModel.events,
                            color: 'black',     // an option!
                            textColor: 'yellow' // an option!
                        }
                    ],
                    ignoreTimezone: false,
                    timezone:false,
                    businessHours:viewModel.businessHours,
                    dayClick: viewModel.dayClick,
                    select: viewModel.select,
                    eventRender: viewModel.eventRender
                            
                });
            }
    };
    
    ko.bindingHandlers.chosen = {
        init: function(element)  {
            ko.bindingHandlers.options.init(element);
            jQuery(element).chosen({disable_search_threshold: 10});
        },
        update: function(element, valueAccessor, allBindings) {
            ko.bindingHandlers.options.update(element, valueAccessor, allBindings);
            jQuery(element).trigger('liszt:updated');
        }
    };
    
     ko.applyBindings(new TeacherSubjectViewModel({grades:<?php echo $grades ?>, 
                                                  teachers:<?php echo $availableTeachers ?>,
                                                  subjects:<?php echo $availableSubjects ?>,
                                                  availabilities:<?php echo $availabilities ?>}));
</script>

    
   