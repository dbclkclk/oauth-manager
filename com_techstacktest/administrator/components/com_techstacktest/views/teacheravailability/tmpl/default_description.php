<?php
		
$user = JFactory::getUser();
$user_id = $user->id;

if(isset($_GET['user_id']))
{
	$teacherId = $_GET['user_id'];
}
// Get a db connection.
$db = JFactory::getDbo();
$current_url = JUri::getInstance();
$query1 = $db->getQuery(true);
$query1
    ->select('*')
    ->from($db->quoteName('#__teachpro_teacher_availability'))
	->where($db->quoteName('teacher_id')." = ".$teacherId);
$db->setQuery($query1);
$results_time = $db->loadObjectList();
$test_array = '';
$i = 1;

	
foreach($results_time as $data_time)
{
	$availability_id = $data_time->id;
	$available_start_date = $data_time->start_date;
	$available_end_date = $data_time->end_date;
	$teacher_id = $data_time->teacher_id;
	if($available_start_date=='0000-00-00 00:00:00' || $available_start_date=='0000-00-00'|| $available_start_date=='') 
	$available_start_date = '';
	if($available_end_date=='0000-00-00 00:00:00' || $available_end_date=='0000-00-00'|| $available_end_date=='') 
	$available_end_date = '';
	
	
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
                        "<input type=\"hidden\" name=\"teacher_id\" value=\"'.$teacherId.'\" />",				
						
 
			"start":"'.$available_start_date.'",
			"end":"'.$available_end_date.'"
		'.$last_portion;
		
		
	$i++;
}

echo $test_array;


 
?>