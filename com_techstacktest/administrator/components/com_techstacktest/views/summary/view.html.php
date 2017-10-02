<?php

defined('_JEXEC') or die;

class TeachproViewSummary extends JViewLegacy 
{
    protected $form;
    protected $item;
    protected $state;
    
    public function display($tpl = null) {
        
        $this->state = $this->get('State');
	$this->form  = $this->get('Form');

	// Check for errors.
	if (count($errors = $this->get('Errors')))
	{
            throw new Exception(implode("\n", $errors));
	}
        parent::display($tpl);
    }
}
