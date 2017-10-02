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

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Studentss list controller class.
 *
 * @since  1.6
 */
class TeachproControllerTeacheravailability extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'teachers';
                $this->view_item="teachers";
		parent::__construct();
	}
        
        public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$data  = $this->input->post->get('jform', array(), 'array');
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

                $keys = array();
                    
		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}
                $teacher =  null;
                $subject = null;
                foreach ($data["calendar"] as $calendar)
                {
                
                    $calendar["start_date"] = (new JDate($calendar["start_date"]))->toSql();
                    $calendar["end_date"] = (new JDate($calendar["end_date"]))->toSql();
                
                    
                    $teacher = $calendar["teacher_id"];
                    $subject = $calendar["subject_id"];
                    
                    // The save2copy task needs to be handled slightly differently.
                    if ($task == 'save2copy')
                    {
                            // Check-in the original row.
                            if ($checkin && $model->checkin($calendar[$key]) === false)
                            {
                                    // Check-in failed. Go back to the item and display a notice.
                                    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                                    $this->setMessage($this->getError(), 'error');

                                    $this->setRedirect(
                                            JRoute::_(
                                                    'index.php?option=' . $this->option . '&view=' . $this->view_item
                                                    . $this->getRedirectToItemAppend($calendar[$key], $urlVar), false
                                            )
                                    );

                                    return false;
                            }

                            // Reset the ID and then treat the request as for Apply.
                            $calendar[$key] = 0;
                            $task = 'apply';
                    }

                    // Access check.
                    if (!$this->allowSave($calendar, $key))
                    {
                            $this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
                            $this->setMessage($this->getError(), 'error');

                            $this->setRedirect(
                                    JRoute::_(
                                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                                            . $this->getRedirectToListAppend(), false
                                    )
                            );

                            return false;
                    }

                    // Validate the posted data.
                    // Sometimes the form needs some posted data, such as for plugins and modules.
                    $form = $model->getForm($calendar, false);

                    if (!$form)
                    {
                            $app->enqueueMessage($model->getError(), 'error');

                            return false;
                    }

                    // Test whether the data is valid.
                    $validData = $model->validate($form, $calendar);

                    // Check for validation errors.
                    if ($validData === false)
                    {
                            // Get the validation messages.
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

                            // Save the data in the session.
                            $app->setUserState($context . '.data', $calendar);

                            // Redirect back to the edit screen.
                            $this->setRedirect(
                                    JRoute::_(
                                            'index.php?option=' . $this->option . '&view=' . $this->view_item
                                            . $this->getRedirectToItemAppend($calendar[$key], $urlVar), false
                                    )
                            );

                            return false;
                    }

                    if($validData["id"]=="null" || $validData["id"]==null)
                        unset($validData["id"]);
                    
                    // Attempt to save the data.
                    if (!$model->save($validData))
                    {
                            // Save the data in the session.
                            $app->setUserState($context . '.data', $validData);

                            // Redirect back to the edit screen.
                            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
                            $this->setMessage($this->getError(), 'error');

                            $this->setRedirect(
                                    JRoute::_(
                                            'index.php?option=' . $this->option . '&view=' . $this->view_item
                                            . $this->getRedirectToItemAppend($calendar[$key], $urlVar), false
                                    )
                            );

                            return false;
                    }
                    
                    $id = $model->getState($model->getName() . '.id');
                    $model->setState($model->getName() . '.id',null);
                    
                    if($id!="null")
                        array_push($keys,$id);

                    // Save succeeded, so check-in the record.
                    if ($checkin && $model->checkin($validData[$key]) === false)
                    {
                            // Save the data in the session.
                            $app->setUserState($context . '.data', $validData);

                            // Check-in failed, so go back to the record and display a notice.
                            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                            $this->setMessage($this->getError(), 'error');

                            $this->setRedirect(
                                    JRoute::_(
                                            'index.php?option=' . $this->option . '&view=' . $this->view_item
                                            . $this->getRedirectToItemAppend($calendar[$key], $urlVar), false
                                    )
                            );

                            return false;
                    }
                }
                
                $modelList = JModelList::getInstance("Teacheravailabilities", "TeachproModel");
                
                $modelList->deleteTeacherAvailability($keys,  $teacher, $subject);

		$this->setMessage(
			JText::_("JLIB_APPLICATION". '_SAVE_SUCCESS')
		);

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				//$recordId = $model->getState($this->context . '.id');
				//$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				// $model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						, false
					)
				);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				//$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						, false
					)
				);
				break;

			default:
				// Clear the record id and data from the session.
				//$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						, false
					)
				);
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}
        
        
        public function edit($key = null, $urlVar = null)
	{
		$app   = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$cid   = $this->input->get->get('cid', array(), 'array');
		$context = "$this->option.edit.$this->context";

                $urlVar = "cid[]";
		
                
                $cid = array("teacher"=>$cid[1], "subject"=>$cid[0]);

		// Get the previous record id (if any) and the current record id.
		//$recordId = (int) (count($cid) ? $cid[0] : $this->input->getInt($urlVar));
		$checkin = property_exists($table, 'checked_out');

		// Access check.
//		if (!$this->allowEdit($cid, $key))
//		{
//			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
//			$this->setMessage($this->getError(), 'error');
//
//			$this->setRedirect(
//				JRoute::_(
//					'index.php?option=' . $this->option . '&view=' . $this->view_list
//					. $this->getRedirectToListAppend(), false
//				)
//			);
//
//			return false;
//		}

		
		$app->setUserState($context . '.data', null);

                $this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . "teacheravailability"
					. $this->getRedirectToItemAppend($cid, $urlVar), false
				)
			);

                return true;
		
	}
        
        
        
        protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$tmpl   = $this->input->get('tmpl');
		$layout = $this->input->get('layout', 'edit', 'string');
		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}

		if ($recordId)
		{
			$append .= '&teacher' . '=' . $recordId["teacher"];
                        $append .= '&subject' . '=' . $recordId["subject"];
		}

		return $append;
	}
        
        
        
}
