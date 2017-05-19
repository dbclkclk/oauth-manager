<?php

defined('_JEXEC') or die();

jimport('joomla.application.component.controllerform');

class TeachproControllerSummary extends JControllerForm
{
    public function save($key = NULL, $urlVar = NULL)
    {
        $jinput = JFactory::getApplication()->input;
        $model = $this->getModel();
        $data['summ_desc']  = $jinput->post->get('summ_desc', '', 'string');
        $data['summ_text']  = $jinput->post->get('summ_text', '', 'string');
        $status = $model->saveAjaxSummary($data);
        echo $status;
       die();
    }
    
    public function getSummaryText()
    {
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('id', '', 'INT');
        $model = $this->getModel();
        $response = $model->getSummaryText($id);
        echo $response;
        die();
    }
}

