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

jimport('joomla.application.component.controllerform');

/**
 * Questions controller class.
 *
 * @since  1.6
 */
class TeachproControllerQuestions extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception

	 */

	protected $questionList;
	public function __construct()
	{
		$this->view_list = 'questions';
		parent::__construct();
	}

	public function ajaxGetGoals() {
		$app = JFactory::getApplication();
		$getData = $app->input->get('subjectID');
		$model = $this->getModel();
		$goalList = $model->getAllGoals($getData);
		
		$html = '<label class="control-label" for="goals">Goals</label><div class="controls">';
		$html .= '<select class="form-control" id="goals" name="goaloption">';
		foreach($goalList as $goals){
			$html .= '<option value="'.$goals->id.'">'.$goals->name.'</option>';
		}
		$html .= '</select></div>';
		echo $html;
        die();
	}

	public function ajaxGetQuestion() {
		$app = JFactory::getApplication();
		$getData = $app->input->get('goalID');
		$model = $this->getModel();

		$questionList = $model->getAllQuestions($getData);
		$html = 'Question List';
		$this->questionList = $questionList;
	}
}
