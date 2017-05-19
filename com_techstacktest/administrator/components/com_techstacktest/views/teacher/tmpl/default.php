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


JHtml::_('jquery.framework', true);
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


// Import CSS
$document = JFactory::getDocument();
JHtml::stylesheet(JUri::root() . 'administrator/components/com_teachpro/assets/css/teachpro.css',array(),true);
JHtml::stylesheet(JUri::root() . 'media/com_teachpro/css/list.css',array(),true);
JHtml::stylesheet(Juri::root()."media/com_teachpro/js/fullcalendar/fullcalendar.css",array(),true);


JHtml::script(Juri::root()."media/com_teachpro/js/knockout-3.4.0.js");
JHtml::script(Juri::root()."media/com_teachpro/js/fullcalendar/lib/moment.min.js");
JHtml::script(Juri::root()."media/com_teachpro/js/fullcalendar/fullcalendar.min.js");

 
 



?>

    
    
    <script>
	jQuery(document).ready(function() {		
		jQuery('#select_grade').change(function() {			
			var dataString = jQuery(this).find(':selected').val();
			jQuery.ajax({
				type: "POST",
				url: "<?php echo JUri::root()?>ajax-get-subject.php",
				data: {ajax_grade_id: dataString},
				success: function(msg) 
					{
						jQuery("#select_subject").html(msg);
					}
			});
		}); 	  
	});
	</script>
    
    
<?php

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_teachpro');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_teachpro&task=teacher_availability.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'teacher_availabilityList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>

    <div id="eventContent" style="margin:20px auto; float:none; width:70%;" title="Event Details">
  
       <form id="calendar_form" action="   " method="post">
        
            <div class="calendar_top_part">
                <div class="select_subject_grade">
                    <label>Select Grade *</label>   
                </div>
                <div class="select_subject_grade">
                    <label>Select Subject *</label>
                    <select required name="select_subject" id="select_subject">
                    <option value="">--select subject--</option>
                    </select>                    
                </div>
            </div>
            
            <div class="calendar_top_part">                
                <div id="bootstrapModalFullCalendar"></div>
                <input type="hidden" name="teacher_id" value="  " />
                <input type="hidden" name="start_date" id="start_date" value="" />
                <input type="hidden" name="end_date" id="end_date" value="" />
                <input type="submit" name="saveEvent" id="saveEvent" class="btn btn-primary" value="Save Available Date" />            
            </div>
        </form>
    </div>
<style>
	.calendar_top_part { float:left; width:100%;}
	#calendar_form { margin:50px 0;}
	#start_end_date { float:right; margin:0 0 10px 0;}
	.select_subject_grade {  width:50%; margin:20px 0; float:left;}
	#saveEvent { margin:20px 0; }
</style>




 <script>
    jQuery(document).ready(function() {
			
        jQuery('#bootstrapModalFullCalendar').fullCalendar({
           
            columnFormat: {
                week: 'ddd'
            },
             selectable: true,
            selectHelper: true,
            editable: false,
            eventStartEditable: false,
            allDaySlot: false,
            defaultView: 'agendaWeek',
            slotDuration: '01:00:00',
            allDay: false,
            slotEventOverlap: false,
            dayClick: function(date,JSevent, view) {
                       
            }
				
	 });
    });
</script>

    
   