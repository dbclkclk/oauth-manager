<script src="<?php echo JUri::base() . 'libraries/fullcalendar/lib/jquery.min.js'; ?>"></script>
<script src="<?php echo JUri::base() . 'libraries/fullcalendar/lib/moment.min.js'; ?>"></script>
<script src="<?php echo JUri::base() . 'libraries/fullcalendar/fullcalendar.min.js' ?>"></script>

<link href="<?php echo JUri::base() . 'libraries/fullcalendar/fullcalendar.css'; ?>" rel="stylesheet">
<link href="<?php echo JUri::base() . 'libraries/fullcalendar/fullcalendar.print.css'; ?>" rel="stylesheet">

<style>

    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }

</style>

<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>

<div id='calendar'></div>

<script>

    jQuery(document).ready(function () {

        // Hide the "other" text input when document loads.
        jQuery(".otherReferral").hide();

        jQuery('#calendar').fullCalendar({
            defaultView: 'agendaWeek',
            defaultDate: '2016-01-12',
            selectable: true,
            selectHelper: true,
            select: function (start, end) {
                var eventData;
                eventData = {
                    start: start,
                    end: end
                };
                jQuery('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                jQuery('#calendar').fullCalendar('unselect');
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: [
                {
                    title: 'All Day Event',
                    start: '2016-01-01'
                },
                {
                    title: 'Long Event',
                    start: '2016-01-07',
                    end: '2016-01-10'
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: '2016-01-09T16:00:00'
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: '2016-01-16T16:00:00'
                },
                {
                    title: 'Conference',
                    start: '2016-01-11',
                    end: '2016-01-13'
                },
                {
                    title: 'Meeting',
                    start: '2016-01-12T10:30:00',
                    end: '2016-01-12T12:30:00'
                },
                {
                    title: 'Lunch',
                    start: '2016-01-12T12:00:00'
                },
                {
                    title: 'Meeting',
                    start: '2016-01-12T14:30:00'
                },
                {
                    title: 'Happy Hour',
                    start: '2016-01-12T17:30:00'
                },
                {
                    title: 'Dinner',
                    start: '2016-01-12T20:00:00'
                },
                {
                    title: 'Birthday Party',
                    start: '2016-01-13T07:00:00'
                },
                {
                    title: 'Click for Google',
                    url: 'http://google.com/',
                    start: '2016-01-28'
                }
            ]
        });
    });
</script>