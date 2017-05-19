<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;


class TeachproControllerSubjectss extends JControllerLegacy 
{
	public function filterSubject() 
	{
		$app = JFactory::getApplication();
		$gradeId = $app->input->get('gradelevel');
		$model = $this->getModel('assigntests');
		$grade = $model->filteredSubjectList($gradeId);
		$json_grade =  json_encode($grade);
		echo $json_grade;
		die();
	}
}