<?php

defined('_JEXEC') or die;

class TeachproControllerAssigntests extends JControllerLegacy
{
	
	function getModel($name = '', $prefix = '', $config = array())
	{
            $model = parent::getModel($name, $prefix, $config);
            return $model;
	}

	public function saveData()
	{
            $app = JFactory::getApplication();
            $student = $app->input->get('student');
            $subject = $app->input->get('subject');
            $payment = $app->input->get('payment');

            $model = $this->getModel('subjectstudents', 'TeachproModel', array());
            $id = $model->ajaxSaveData($student, $subject, $payment);
            echo $id;
            die();

	}

	public function deleteData()
	{
            $app = JFactory::getApplication();
            $id = $app->input->get('id');
            $model = $this->getModel('subjectstudents', 'TeachproModel', array());
            $status = $model->ajaxDeleteData($id);
            echo $status;
            die();

	}

	public function updateData()
	{
            $app = JFactory::getApplication();
            $id = $app->input->get('id');
            $student = $app->input->get('student');
            $subject = $app->input->get('subject');
            $model = $this->getModel('subjectstudents', 'TeachproModel', array());
            $status = $model->ajaxUpdateData($id, $student, $subject);
            echo $status;
            die();

	}
}