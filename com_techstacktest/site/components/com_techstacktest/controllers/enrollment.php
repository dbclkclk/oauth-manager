<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <sajendramanandhar@gmail.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

include JPATH_COMPONENT_SITE . '/controller.php';

include JPATH_LIBRARIES . '/freshbooks/Freshbooks/FreshBooksApi.php';

use Freshbooks\FreshBooksApi;

class TeachproControllerEnrollment extends TeachproController {

    const domain = 'teachpro-accounts';
    const token = 'ed4eb641c733d74d5778f7a0eb13b781';

    public function getTimeSheets() {

        $timesheetsModel = $this->getModel('Timesheets', 'TeachproModel');

        $timeSheets = $timesheetsModel->getTimeSheets();

        print_r($timeSheets);
    }

    public function sendRecurringPaymentDetails() {
        
        $app = JFactory::getApplication();

        $paymenTermsModel = JModelItem::getInstance("Paymentterms","TeachproModel");
        
  
        
        // Get the current edit id.
        $enrollmentData = $app->getUserState('com_teachpro.edit.enrollment.data');
        
        
        $paymentData = (object)$enrollmentData["paymentterms"];
        $paymentDetails = $paymenTermsModel->calculateEnrollmentPaymentTerms($paymentData);
        
        
        $parentFormModel = $this->getModel('ParentForm', 'TeachproModel');
        $parent = $parentFormModel->getData($enrollmentData['parent']['id']);
        $clientid = $this->createClient($parent);
        $parent->client_id = $clientid;
        $parentFormModel->save($parent->getProperties());


        $recurringInvoiceData = $this->createRecurringInvoice($parent, $paymentDetails);
        $invoice = $this->getInvoice($recurringInvoiceData["invoiceId"]);
        
        $totalCost = $paymenTermsModel->getTotalCost($paymentData);
        $paymentTermsParam = array("recurringid"=>intval($invoice["invoice"]["recurring_id"]),"total_cost"=>$totalCost);
        $enrollmentData["paymentterms"]=array_merge ($enrollmentData["paymentterms"],$paymentTermsParam);
        
        

        //Update Payment Terms Information with Invoice
        $paymenttermsId = $this->savePaymentTerms($enrollmentData["paymentterms"]);
        
        
        //Email customer
        $resp = $this->emailInvoice($recurringInvoiceData['invoiceId']);
        
        // Flush the data from the session.
        $app->setUserState('com_teachpro.edit.enrollment.data', null);

        $this->setMessage(JText::_('COM_TEACHPRO_ENROLLED_SAVED_SUCCESSFULLY'));
        $this->setMessage(JText::_('COM_TEACHPRO_PAYMENT_INVOICE_SENT').$parent->parent_email. JText::_('COM_TEACHPRO_PAYMENT_INVOICE_ENROLLMENT'));
        $this->setRedirect("index.php?option=com_teachpro&view=testss");
    }
    
    public function payForTest()
    {
        
        $user = JFactory::getUser();
        $modelList = JModelList::getInstance("Subjectstudentss","TeachproModel");
        $modelForm = JModelList::getInstance("SubjectstudentForm","TeachproModel");
        $items = $modelList->getUnPaidForStudentSubject();
        $paymentTermsModel = JModelItem::getInstance("Paymentterms", "TeachproModel");
        $payment = $paymentTermsModel->getTotalInvoiceForTests($items);
        $parent = new stdClass();
        
        $parent->parent_full_name = $user->name;
        $parent->parent_email = $user->email;
        $parent->parent_occupation = "";
        $clientid = $this->createClient($parent);
        $parent->client_id = $clientid;
        $invoiceNum =$this->createOnetimeInvoice($parent, $payment);
        $this->sendInvoiceNotiication($invoiceNum["invoiceId"]);
        $paymentObj = array("invoiceid"=>$invoiceNum["invoiceId"],"amount_due"=>$payment->amount_due, "amount_received"=>0);
        $paymentFormModel = JModelForm::getInstance("PaymentForm","TeachproModel");
        $paymentid = $paymentFormModel->save($paymentObj);
        foreach ($items as $item)
        {
           $item->client_id = $clientid;
           $item->payment_id = $paymentid;
           $param = json_decode(json_encode($item), True);
           $modelForm->save($param);
        }
        $this->setMessage(JText::_('COM_TEACHPRO_PAYMENT_INVOICE_SENT').$user->email . JText::_("COM_TEACHPRO_PAYMENT_INVOICE_REFRESH"));
        $this->setRedirect("index.php?option=com_teachpro&view=testss");
    }
    
    private function sendInvoiceNotiication($invoiceId)
    {
        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('invoice.sendByEmail');

        $fb->post(array(
            'invoice_id' => $invoiceId
        ));

        $fb->request();

        $response = $fb->getResponse();
        
    }
 
    
    
    public function getRecurringList() {

        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('recurring.list');

        $fb->post(array(
            'client_id' => 92170
        ));

        $fb->request();

        $response = $fb->getResponse();

        echo '<pre>';

        print_r($response);
    }
    
    /*
     * capture payment notification from webhook
     */
    public function capturePaymentNotification() {
               
        $model = $this->getModel('EnrollmentForm', 'TeachproModel');
        
        $this->send_remote_syslog();
 
        
        
        $paymentId  = JFactory::getApplication()->input->post->get("object_id");
        
        
        //$this->send_remote_syslog($verification);
        
        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('payment.get');

        $fb->post(array(
            'payment_id' => $paymentId
        ));

        $fb->request();

        $response = $fb->getResponse();
        
        $this->recoredPayment($response["payment"]);
        
    }
    
    private function recoredPayment($data)
    {
        
        $paymentModel = JModelItem::getInstance("Payments","TeachproModel");      
        $paymentFormModel = JModelItem::getInstance("PaymentForm","TeachproModel");
        
        
       // $paymentTermsForm = JModelForm::getInstance("PaymenttermsForm","TeachproModel");
        
        $paymentTermsModel = JModelForm::getInstance("Paymentterms","TeachproModel");
        
        $payment = null;
        //Get single test payment
        if($data["invoice_id"])
        {
            $param = array("invoiceid"=>intval($data["invoice_id"]));
            $payment = $paymentModel->getData($param);
        }
        
        //update recurring payment
        if(!$payment)
        {
            $invoice = $this->getInvoice($data["invoice_id"]);
            $param = array("recurringid"=>$invoice["invoice"]["recurring_id"]);
            
            $paymentTerms = $paymentTermsModel->getData($param);
            $payment = $paymentTermsModel->getCurrentPayment($param);
            
            
            $payment->paymenttermsid = $paymentTerms->id;
        }
    
        if($payment)
        {
                //Test Payment
                $payment->amount_recieved = $data["amount"];
                $val1 = intval($payment->amount_due);
                $val2 = intval($data["amount"]);
                if($val1==$val2)
                {
                    $payment->is_paid=1;
                }
                $payment = get_object_vars($payment);
                $paymentFormModel->updateData($payment);
                
        } 
            
        //Save Payment Terms Information
        if($payment["paymenttermsid"])                
        {
            $paymentAmount =  $paymentTermsModel->getCurrentPayment($payment->paymenttermsid);
            if($paymentAmount->change)
            {
                $paymentTerms = $paymentTermsModel->getData($payment->paymenttermsid);
                $this->updateRecurringInvoice($parent, $paymentTerms);
            }
        }
        //Send email
        if($paymentTerms->enrollment_id)
        {
            
            $session = JFactory::getSession();
            $registry = $session->get('registry');

            $mailModel = JModelLegacy::getInstance("Email","TeachproModel",$registry);
            $mailModel->enrollment($paymentTerms->enrollment_id);
        }
    }
   
    private function createClient(&$parent) {

        
        $result =  null;
        if (!$parent->client_id || $parent->client_id==null  ) {

            $fb = new FreshBooksApi(self::domain, self::token);

            $fb->setMethod('client.create');
            
            $name = split(" ", $parent->parent_full_name);

           $name = array_merge($name, array()); 
            $fb->post(array(
                'client' => array(
                    'first_name' => $name[0],
                    'last_name' => $name[1],
                    'organization' => $parent->parent_full_name,
                    'email' => $parent->parent_email
                )
            ));

            $fb->request();

           $data = $fb->getResponse();
               
           $result["client_id"]=$data['client_id'];
           
        }
        else
        {
            $result["client_id"] =$parent->client_id;
        }
        return  $result["client_id"];
    }

    public function createOnetimeInvoice($parent, $paymentDetails) {

        $date = new DateTime();

        $dateString = $date->format('Y-m-d');

        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('invoice.create');
        
         

        $fb->post(array(
            'invoice' => array(
                'client_id' => $parent->client_id,
                'status'=>"sent",
                'date' => $dateString,
                'currency_code'=>"USD",
                "language"=>"en",
                'lines' => array(
                    'line' => array(
                        'name' => "Payment for test",
                        'description' => $paymentDetails->description,
                        'unit_cost' => $paymentDetails->amount_due,
                        'quantity' => 1
                    )
                )
            )
        ));

        $fb->request();

        $response = $fb->getResponse();

        $recurringInvoiceData = array();

        $recurringInvoiceData['invoiceId'] = $response['invoice_id'];

        return $recurringInvoiceData;
    }
    
    
    public function createRecurringInvoice($parent, $paymentDetails) {

        $date = new DateTime();

        $dateString = $date->format('Y-m-d');

        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('recurring.create');

        $fb->post(array(
            'recurring' => array(
                'client_id' => $parent->client_id,
                'occurrences' => $paymentDetails->occurance,
                'frequency' => $paymentDetails->payment_frequency,
                'return_uri' => JURI::root(),
                'lines' => array(
                    'line' => array(
                        'name' => "Recurring Payment for enrollment",
                        'description' => $paymentDetails->description,
                        'unit_cost' => $paymentDetails->amount_due,
                        'quantity' => 1
                    )
                )
            )
        ));

        $fb->request();

        $response = $fb->getResponse();

        $recurringInvoiceData = array();

        $recurringInvoiceData['recurringId'] = $response['recurring_id'];
        $recurringInvoiceData['invoiceId'] = $response['invoice_id'];

        return $recurringInvoiceData;
    }
    
    
     public function updateRecurringInvoice($parent, $paymentDetails) {

        $date = new DateTime();

        $dateString = $date->format('Y-m-d');

        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('recurring.update');

        $fb->post(array(
            'recurring' => array(
                'client_id' => $parent->client_id,
                'recurring_id'=>$paymentDetails->recurringid, 
                'occurrences' => $paymentDetails->occurance,
                'frequency' => $paymentDetails->payment_frequency,
                'return_uri' => JURI::root(),
                'lines' => array(
                    'line' => array(
                        'name' => $parent->parent_full_name,
                        'description' => $paymentDetails->description,
                        'unit_cost' => $paymentDetails->amount_due,
                        'quantity' => 1
                    )
                )
            )
        ));

        $fb->request();

        $response = $fb->getResponse();

        $recurringInvoiceData = array();

        $recurringInvoiceData['recurringId'] = $response['recurring_id'];
        $recurringInvoiceData['invoiceId'] = $response['invoice_id'];

        return $recurringInvoiceData;
    }
    
    
    

    public function emailInvoice($invoiceId) {

        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('invoice.sendByEmail');

        $fb->post(array(
            'invoice_id' => $invoiceId
        ));

        $fb->request();

        $fb->getResponse();
    }

    private function getInvoice($invoiceId) {

        $fb = new FreshBooksApi(self::domain, self::token);

        $fb->setMethod('invoice.get');

        $fb->post(array(
            'invoice_id' => $invoiceId
        ));

        $fb->request();

        return $fb->getResponse();
       
    }

    public function getClients() {

        FreshBooksApi::init(self::domain, self::token);

        $fb = new FreshBooksApi(self::domain, self::token);
        $fb->setMethod('client.list');
        $fb->post(array(
            'email' => 'johndoe@gmail.com'
        ));

        try {

            $fb->request();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        echo 'hello';
        exit;
    }
    
    private function savePaymentTerms($data)
    {
        $model = JModelForm::getInstance("PaymenttermsForm","TeachproModel");
       return $model->save($data);
    }
    
   private function savePaymentInformation($data)
    {
        
        $model = JModelForm::getInstance("PaymentForm","TeachproModel");
        return $model->save($data);
    }
    
    

}
