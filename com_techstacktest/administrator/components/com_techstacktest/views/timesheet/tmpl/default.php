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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>
    
    <!---- full calendar ---->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css" type="text/css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
    <!---- datepicker ---->
    <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->

<?php

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_teachpro/assets/css/teachpro.css');
$document->addStyleSheet(JUri::root() . 'media/com_teachpro/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_teachpro');
$saveOrder = $listOrder == 'a.`ordering`';



if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_teachpro&task=timesheet.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'timesheetList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}


// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}



$user = JFactory::getUser();
$loggedin_usergroups = $user->groups;

$user_id = $user->id;
// Get a db connection.
$db = JFactory::getDbo();

$user_query = $db->getQuery(true);
$user_query
    ->select('*')
    ->from($db->quoteName('#__usergroups'))
	->where($db->quoteName('title')." = 'Super Users'");
$db->setQuery($user_query);
$results_user = $db->loadObjectList();
$super_user_id = $results_user[0]->id;

$current_url = JUri::getInstance();


$query1 = $db->getQuery(true);
$query1
    ->select('*')
    ->from($db->quoteName('#__teachpro_timesheet'));
	
if(!in_array($super_user_id,$loggedin_usergroups)) {
	$query1->where($db->quoteName('teacher_id')." = ".$user_id);
}
$db->setQuery($query1);
$results_time = $db->loadObjectList();
$test_array = '';
$i = 1;
foreach($results_time as $data_time)
{
	$state = $data_time->state;
	$available_start_date = $data_time->available_start_date;
	$available_end_date = $data_time->available_end_date;
	$timesheet_id = $data_time->id;
	$complete = $data_time->complete;
	$approved_absent = $data_time->approved_absent;
	$teacher_id = $data_time->teacher_id;
	$teacher_start_date = $data_time->teacher_start_time;
	$teacher_end_date = $data_time->teacher_end_time;
	if($teacher_start_date=='0000-00-00 00:00:00' || $teacher_start_date=='0000-00-00'|| $teacher_start_date=='') 
	$teacher_start_date = '';
	if($teacher_end_date=='0000-00-00 00:00:00' || $teacher_end_date=='0000-00-00'|| $teacher_end_date=='') 
	$teacher_end_date = '';
	
	
	if($approved_absent==1)
	{
		$teacher_new_date = $data_time->available_start_date;
		if($teacher_new_date=='0000-00-00 00:00:00' || $teacher_new_date=='0000-00-00'|| $teacher_new_date=='') 
		$teacher_new_date = '';
		$teacher_new_start_time = $data_time->teacher_start_time;
		$teacher_new_end_time = $data_time->teacher_end_time;	
		if($teacher_new_start_time=='0000-00-00 00:00:00' || $teacher_new_start_time=='0000-00-00'|| $teacher_new_start_time=='') 
		$teacher_new_start_time = '';
		if($teacher_new_end_time=='0000-00-00 00:00:00' || $teacher_new_end_time=='0000-00-00'|| $teacher_new_end_time=='') 
		$teacher_new_end_time = '';		
		$comment = $data_time->comment;
	}
	else
	{
		$teacher_new_date = '';
		$teacher_new_start_time = '';
		$teacher_new_end_time = '';
		$comment = '';
	}
	
	if(count($results_time)==$i)
	{
		$last_portion = "}";
	}
	else
	{
		$last_portion = "},";
	}
	
		$test_array .= '{
			"description":
                        "<input type=\"time\" value=\"'.$teacher_start_date.'\" placeholder=\"enter start time (hour:min)\" name=\"teacherStartDate\" /><br/>'
                        . '<input type=\"time\" value=\"'.$teacher_end_date.'\" placeholder=\"enter end time  (hour:min)\" name=\"teacherEndDate\" /><br/>'
                        . '<input type=\"checkbox\" value=\"1\" class=\"is_completed\" onclick =\"show_approved_absent()\" name=\"complete\" checked=\"checked\">&nbsp;Completed?<br /><br />'
                        . '<div class=\"approved_absent_part\"><input class=\"is_approved_absent\" onclick =\"show_new_dat_time()\" type=\"checkbox\" value=\"1\" name=\"approved_absent\">&nbsp;Approved Absent<br /><br /></div>'
                        . '<div class=\"new_start_date_part\"><input type=\"date\" value=\"'.$teacher_new_date.'\" placeholder=\"new start date  (yyyy-mm-dd)\" name=\"newteacherDate\" /><br /></div>'
                        . '<div class=\"new_start_time_part\"><input onclick=\"myFunction()\" type=\"time\" value=\"'.$teacher_new_start_time.'\" placeholder=\"new start time  (hour:min)\" name=\"newteacherStartTime\" /><br /></div>'
                        . '<div class=\"new_end_time_part\"><input type=\"time\" value=\"'.$teacher_new_end_time.'\" placeholder=\"new end time  (hour:min)\" name=\"newteacherEndTime\" /><br /></div>'
                        . '<div class=\"comment_part\" ><textarea placeholder=\"comment\" name=\"comment\">'.$comment.'</textarea></div>'
                        . '<input type=\"hidden\" name=\"timesheet_id\" value=\"'.$timesheet_id.'\" />'
                        . '<input type=\"hidden\" name=\"teacher_id\" value=\"'.$teacher_id.'\" />",
						
						
 
			"start":"'.$available_start_date.'",
			"end":"'.$available_end_date.'"
		'.$last_portion;
	
	$i++;
}


if(isset($_POST['timesheet_id']))
{
	
	
	$timesheet_id = $_POST['timesheet_id'];  
	$teacher_id = $_POST['teacher_id'];
	 
	if(isset($_POST['teacherStartDate']))
	$teacherStartDate = $_POST['teacherStartDate'];
	else
	$teacherStartDate = '';
	
	if(isset($_POST['teacherEndDate']))
	$teacherEndDate = $_POST['teacherEndDate'];
	else
	$teacherEndDate = '';
	
	if(isset($_POST['complete']))
	$complete = $_POST['complete'];
	else
	$complete = 0;
	
	if(isset($_POST['approved_absent']))
	$approved_absent = $_POST['approved_absent'];
	else
	$approved_absent = 0;
	
	if(isset($_POST['newteacherDate']))
	$newteacherDate = $_POST['newteacherDate'];
	else
	$newteacherDate = '';
	
	if(isset($_POST['newteacherStartTime']))
	$newteacherStartTime = $_POST['newteacherStartTime'];
	else
	$newteacherStartTime = '';
	
	if(isset($_POST['newteacherEndTime']))
	$newteacherEndTime = $_POST['newteacherEndTime'];
	else
	$newteacherEndTime = '';
	
	if(isset($_POST['comment']))
	$comment = $_POST['comment'];
	else
	$comment = '';
	
	
	if($complete==1) 
	{	
		$query2 = $db->getQuery(true);
		$fields = array(
				$db->quoteName('complete') . ' = ' . $complete,
				$db->quoteName('teacher_start_time') . ' = "'.$teacherStartDate.'"',
				$db->quoteName('teacher_end_time') . ' = "'.$teacherEndDate.'"'
			);
		$conditions = array($db->quoteName('id') . ' = ' . $timesheet_id);		
		$query2->update($db->quoteName('#__teachpro_timesheet'))->set($fields)->where($conditions);		
		$db->setQuery($query2);
		$db->execute();
	}
	else if($approved_absent==1)
	{
		$query2 = $db->getQuery(true);
		$fields = array(
				$db->quoteName('complete') . ' = 0',
				$db->quoteName('approved_absent') . ' = '.$approved_absent,
                                $db->quoteName('comment') . " = '".$comment ."'"
			);
		$conditions = array($db->quoteName('id') . ' = ' . $timesheet_id);		
		$query2->update($db->quoteName('#__teachpro_timesheet'))->set($fields)->where($conditions);		
		$db->setQuery($query2);
		$db->execute();
		
                //$newteacherDate->modify();
		$query3 = $db->getQuery(true);
		$columns = array('state','available_start_date', 'available_end_date', 'teacher_id', 'teacher_start_time', 'teacher_end_time', 'approved_absent');
		$values = array('1','"'.$newteacherDate.'"', '"'.$newteacherDate.'"', $teacher_id, '"'.$newteacherStartTime.'"', '"'.$newteacherEndTime.'"', '0');
		$query3
			->insert($db->quoteName('#__teachpro_timesheet'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		$db->setQuery($query3);
		$db->execute();
                 
	}
}

  
if(isset($_GET['studentsubjectid']))
{
	?>
    
    
    <script>
        jQuery(document).ready(function() {
			
            jQuery('#bootstrapModalFullCalendar').fullCalendar({
				
                header: {
                    left: '',
                    center: 'prev title next',
                    right: ''
                },
                eventClick:  function(event, jsEvent, view) {
                    jQuery('#modalTitle').html(event.title);
                    jQuery('#modalBody').html(event.description);
                    jQuery('#eventUrl').attr('href',event.url);
                    jQuery('#fullCalModal').modal();
                    return false;
                },
				
                events:
                [
					<?php echo $test_array;?>
                ]
            });
			
			
        });
    </script>
    <div id="eventContent" style="margin:20px auto; float:none; width:70%;" title="Event Details">
		<a href="index.php?option=com_teachpro&amp;view=timesheet">Back to previous page</a>
        <div id="bootstrapModalFullCalendar"></div>
    </div>
    
    <div id="fullCalModal" class="modal fade">
        <!-- cdn for modernizr, if you haven't included it already -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<!-- polyfiller file to detect and load polyfills -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshims.setOptions('forms-ext', {types: 'date'});
  webshims.polyfill('forms forms-ext');
</script>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                    <h2 id="modalTitle" class="modal-title" style="text-align:center">Teacher Time Log</h2>
                </div>
                
                
                
                <form action="<?php echo $current_url?>" method="post">
                <div id="modalBody" class="modal-body" style="text-align:center;"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="saveEvent" class="btn btn-primary" value="Save Event" />
                </div>
                

<style>
.approved_absent_part,.new_start_date_part,.new_start_time_part,.new_end_time_part,.comment_part { display:none;}
</style>


<script>

function show_approved_absent() {
	if(jQuery(".is_completed").is(':checked')==false)
	{
		jQuery(".approved_absent_part").css("display","block");
	}
	else
	{
		jQuery(".approved_absent_part").css("display","none");
		jQuery(".new_start_date_part").css("display","none");
		jQuery(".new_start_time_part").css("display","none");
		jQuery(".new_end_time_part").css("display","none");
		jQuery(".comment_part").css("display","none");
	}
}
function show_new_dat_time() {
	if(jQuery(".is_approved_absent").is(':checked')==true)
	{
		jQuery(".new_start_date_part").css("display","block");
		jQuery(".new_start_time_part").css("display","block");
		jQuery(".new_end_time_part").css("display","block");
		jQuery(".comment_part").css("display","block");
	}
	else
	{
		jQuery(".new_start_date_part").css("display","none");
		jQuery(".new_start_time_part").css("display","none");
		jQuery(".new_end_time_part").css("display","none");
		jQuery(".comment_part").css("display","none");
	}
}
</script>
                </form>
            </div>
        </div>
    </div>
	<?php
	
}
else
{	

$query = $db->getQuery(true);
$query
    ->select(array('a.id','a.firstname','a.lastname', 'c.id as subject_id', 'c.grade', 'c.subject_name'))
    ->from($db->quoteName('#__teachpro_student', 'a'))
    ->join('INNER', $db->quoteName('#__teachpro_student_subject', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.student_id') . ')')
    ->join('LEFT', $db->quoteName('#__teachpro_subject', 'c') . ' ON (' . $db->quoteName('b.subject_id') . ' = ' . $db->quoteName('c.id') . ')')
	->where($db->quoteName('b.state')." = 1");
$db->setQuery($query);
$results_test = $db->loadObjectList();
?>
<table class="table table-striped" id="table-1">
	<thead>
    	<tr>
            <th class="left">
            	<strong>Student</strong>
            </th>
            <th class="left">
            	<strong>Subject</strong>
            </th>
            <th class="left">
            	<strong>Grade</strong>
            </th>
            <th class="left">
            	<strong>Date</strong>
            </th>
        </tr>
    </thead>
    
    <tbody>
    
  <?php
    	foreach($results_test as $data)
		{
			?>
			<tr>
				<td><?php echo $data->firstname." ".$data->lastname;?></td>
				<td><?php echo $data->subject_name?></td>
				<td><?php echo $data->grade;?></td>
				<td class="time_calender" id="<?php echo $data->grade?>"><a href="<?php echo $current_url."&studentsubjectid=".$data->subject_id;?>">enter time</a></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

<?php
}


?>
    
    <!--<script>
jQuery(function() {
  jQuery( "#datetime" ).datepicker({
	  changeMonth:true,
	  changeYear:true,
      yearRange:"-100:+0",
	  dateFormat:"yy-mm-dd"
  });
});
</script>-->
