<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;

class TeachproModelPaymentterms extends JModelItem {
    
    /**
     * Method to get an object.
     *
     * @param   integer  $id  The id of the object to get.
     *
     * @return  mixed    Object on success, false on failure.
     */
    public function &getData($id = null) {

        if ($this->_item === null) {

            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('paymentterms.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = ArrayHelper::toObject($properties, 'JObject');
            }
        }

        if (isset($this->_item->created_by)) {

            $this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
        }

        if (isset($this->_item->modified_by)) {

            $this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
        }

        return $this->_item;
    }
    
    /**
     * Get an instance of JTable class
     *
     * @param   string  $type    Name of the JTable class to get an instance of.
     * @param   string  $prefix  Prefix for the table class name. Optional.
     * @param   array   $config  Array of configuration values for the JTable object. Optional.
     *
     * @return  JTable|bool JTable if success, false on failure.
     */
    public function getTable($type = 'Paymentterms', $prefix = 'TeachproTable', $config = array()) {

        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_teachpro/tables');

        return JTable::getInstance($type, $prefix, $config);
    }
    
    
    public function calculateEnrollmentPaymentTerms($data)
    {
            
            $result = 0;
                if($data->signupterms=="commitment")
                {
                    $result = $this->calculatePaymentDetailsForCommitment($data);
                    
                }
                else
                {
                   $result =$this->calculatePaymentDetailsForNoCommitment($data);
                }
                
                return $result;
    }
        
        
    private function calculatePaymentDetailsForNoCommitment($data)
    {
            $paymentDetails = array();

            // Cost per session is $17 for commitment.
            $costPerSession = JComponentHelper::getParams('com_teachpro')->get('uncommitment_price');

            $numberOfWeeks = $data->total_weeks_for_sessions;

            $numberOfSessionsPerWeek = $data->sessions_per_week;

            $totalNumberOfSessions = $numberOfWeeks * $numberOfSessionsPerWeek;

            $result = new stdClass();
            
            $result->name ="Teachpro Session Enrollment";
            

            switch ($data->payment_frequency) {

                case 'weekly_payment':

                    $result->occurance = $numberOfWeeks/1;
                    
                    $result->amount_due = $costPerSession * $numberOfSessionsPerWeek;

                    $result->payment_frequency = 'weekly';

                    $result->description = 'Weekly Payment';

                    break;

                case 'biweekly_payment':

                    
                    $frequency = 2;
                    if($data->total_weeks_for_sessions<2)
                    {
                        $frequency = $numberOfWeeks;
                        $result->occurance = 1;
                    }
                    else
                    {
                        $result->occurance = ceil($data->total_weeks_for_sessions/2);
                    }
                    
                       
                    $result->amount_due = $costPerSession * $numberOfSessionsPerWeek * $frequency;
                     $result->payment_frequency = '2 weeks';

                    $result->description = 'Bi-Weekly Payment';

                    break;

                case 'monthly_payment':
                    
                     $frequency = 4;
                    if( $data->total_weeks_for_sessions<4)
                    {
                      $result->occurance = 1;
                      $frequency = $numberOfWeeks;
                    }
                    else
                    {
                        $result->occurance = ceil($data->total_weeks_for_sessions/4);
                    }

                    $result->amount_due = $costPerSession * $numberOfSessionsPerWeek * $frequency;

                    $result->payment_frequency = '4 weeks';

                    $result->description = 'Monthly Payment';

                    break;

                default:

                    $result->amount_due = $costPerSession * $totalNumberOfSessions;
                  
                    $result->occurance = 1;
                    
                    $result->payment_frequency = 'weekly';

                    $result->description = 'All Payment';

                    break;
            }
            return $result;
    }

    private function calculatePaymentDetailsForCommitment($data) 
    {
        

            $result = new stdClass();


            // Cost per session is $17 for commitment.
            $costPerSession = JComponentHelper::getParams('com_teachpro')->get('commitment_price');

            $totalNumberOfSessions = JComponentHelper::getParams('com_teachpro')->get('total_committed_sessions');

            // Total cost for all 20 sessions.
            $totalCost = $costPerSession * $totalNumberOfSessions;

            $numberOfSessionsPerWeek = $data->sessions_per_week;


            

            switch ($data->payment_frequency) {

                case 'weekly_payment':

                 
                    $result->amount_due = $costPerSession * $numberOfSessionsPerWeek;

                 
                    $result->occurance = $this->calculateOccurance($totalCost,$result->amount_due);
                    
                    $result->payment_frequency = 'weekly';

                    $result->description = "Weekly Payment";

                    break;

                case 'biweekly_payment':

                   
                    $result ->amount_due = $costPerSession * $numberOfSessionsPerWeek * 2;

                   
                    $result->occurance = $this->calculateOccurance($totalCost,$result->amount_due);;
                    
                    $result->payment_frequency = '2 weeks';

                    $result->description = 'Bi-Weekly Payment';

                    break;

                case 'monthly_payment':

                  
                    $result->amount_due = $costPerSession * $numberOfSessionsPerWeek * 4;

                    
                    $result->occurance = $this->calculateOccurance($totalCost,$result->amount_due);;
                    
                    $result->payment_frequency = '4 weeks';

                    $result->description = 'Monthly Payment';

                    break;

                default:

                   
                    $result->amount_due = $costPerSession * $totalNumberOfSessions;

                     $result->occurance = $this->calculateOccurance($totalCost,$result->amount_due);;
                    
                    $result->payment_frequency = 'weekly';

                    $result->description = 'All Payment';

                    break;
            }

            return $result;
            
        }
        
        public function getTotalCost ($data)
        {
            $total_cost = 0;
            $price =  $costPerSession = JComponentHelper::getParams('com_teachpro')->get('commitment_price');
            $totalNumberOfSessions = JComponentHelper::getParams('com_teachpro')->get('total_committed_sessions');
            
            if($data->signupterms=="commitment")
            {
                $total_cost = $totalNumberOfSessions * $price;
                    
            }
            else
            {
                $price =   JComponentHelper::getParams('com_teachpro')->get('uncommitment_price');
                
                $total_cost =  $data->total_weeks_for_sessions * $data->sessions_per_week * $price;
                
            }
            return $total_cost;
        }
        
        
        public function getTotalWeeksForSessions ($data)
        {
            $totalNumberOfSessions = JComponentHelper::getParams('com_teachpro')->get('total_committed_sessions',0);
            
            $result = 0;
            if($data->signupterms=="commitment")
            {
           
                $result =  $totalNumberOfSessions / $data->sessions_per_week;
                
            }
            elseif($data->signupterms=="noCommitment")
            {
                  $result=  $data->total_weeks_for_sessions;
            }
                
            return $result;
        }
        
        public function getTotalInvoiceForTests ($items)
        {
            $result = new stdClass();
            
            $result->amount_due = count($items) * JComponentHelper::getParams('com_teachpro')->get('test_cost');
                  
            $result->occurance = 1;
                    
            $result->payment_frequency = 'weekly';

            $result->description = 'All Payment';
            
            
            
            return $result;
            
            
        }
        
        public function calculateOccurance ($total, $installment)
        {
            
            $installment = ceil($total/$installment);
            return $installment;
            
        }
        public function getCurrentPayment($param)
        {
            
            $obj = new stdClass();
            
            $paymentModel = JModelList::getInstance("Paymentss","TeachproModel");
            $result = $this->getData($param);
           
            
           $obj = $this->calculateEnrollmentPaymentTerms($result);
           $obj->change = false;
           $totalPayments = $paymentModel->getTotalPaymentByPaymentTerms($result->id);
           
           $remains = $result->total_cost - $totalPayments;
           
           if($remains<$obj->amount_due)
           {
               $obj->amount_due=$remains;
               $obj->change=true;
           }
           return $obj;
            
        }

}