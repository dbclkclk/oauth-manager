<?php

defined('_JEXEC') or die;

class TeachproModelSummary extends JModelForm
{
    protected $item = null;
    
    public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'description',
				'text'
			);
		}
 
		parent::__construct($config);
	}
    
    public function getTable($type = 'Summary', $prefix = 'TeachproTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
    
    public function getForm($data=array(), $loadData= true)
    {
        $app = JFactory::getApplication();
        
        $form = $this->loadForm(
                'com_teachpro.summary', 'summary',
		array('control' => 'jform',
		'load_data' => $loadData
			)
                );
        if(empty($form))
        {
            return false;
        }
        
        return $form;
    }
    
    public function saveAjaxSummary($params)
    {
       $table = $this->getTable();
       $data['description'] = $params['summ_desc'];
       $data['text'] = $params['summ_text'];
       $data['ordering'] = 0;
       $data['state'] = 0;
       $data['checked_out'] = 0;
       $data['checked_out_date'] = JFactory::getDate('now')->toSql();
       $data['created_by'] = 0;
       $data['modified_by'] = 0;
       $table->bind($data);
       
       if($table->store())
       {
           return true;
       }
       else
       {
           return false;
       }
    }
       
    public function getSummaryText($id)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('text AS summ_text');
        $query->from('#__teachpro_summary');
        $query->where('id = '.(int) $id);
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }
}

