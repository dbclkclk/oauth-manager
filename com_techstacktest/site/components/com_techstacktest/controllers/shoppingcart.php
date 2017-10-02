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

/**
 * Students controller class.
 *
 * @since  1.6
 */
class TeachproControllerShoppingcart extends JControllerLegacy
{
	
	public function display($cachable = false, $urlparams = false)
	{
		
	}
            
               
       
	
	
	function my_ipn()
	{ 
            
                // this call is just to check if the ipn is hit or not,
                //*** HAD TO REMOVE WHEN IN PRODUCTION **//
                 $this->checkAndSaveTransection('1221','5XXqjsa71SGh');
                  
                  
		$myemail ="ersandeepthapa@gmail.com";
		
		
				
	
		// Check to see there are posted variables coming into the script
		if ($_SERVER['REQUEST_METHOD'] != "POST") die ("No Post Variables");
		// Initialize the $req variable and add CMD key value pair
		$req = 'cmd=_notify-validate';
		// Read the post from PayPal
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		// Now Post all of that back to PayPal's server using curl, and validate everything with PayPal
		// We will use CURL instead of PHP for this for a more universally operable script (fsockopen has issues on some environments)
		$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		//$url = "https://www.paypal.com/cgi-bin/webscr";
		$curl_result=$curl_err='';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
		curl_setopt($ch, CURLOPT_HEADER , 0);   
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$curl_result = @curl_exec($ch);
		$curl_err = curl_error($ch);
		curl_close($ch);

		$req = str_replace("&", "\n", $req);  // Make it a nice list in case we want to email it to ourselves for reporting
	
		// Check that the result verifies
		if (strpos($curl_result, "VERIFIED") !== false) {
			$req .= "\n\nPaypal Verified OK";
		} else {
			$req .= "\n\nData NOT verified from Paypal!";
			mail($myemail, "IPN interaction not verified", "$req", "From: you@youremail.com" );
			exit();
		}
		
		

		/* CHECK THESE 4 THINGS BEFORE PROCESSING THE TRANSACTION, HANDLE THEM AS YOU WISH
		1. Make sure that business email returned is your business email
		2. Make sure that the transaction’s payment status is “completed”
		3. Make sure there are no duplicate txn_id
		4. Make sure the payment amount matches what you charge for items. (Defeat Price-Jacking) */
		 
		// Check Number 1 ------------------------------------------------------------------------------------------------------------
		$receiver_email = $_POST['receiver_email'];
		if ($receiver_email != $myemail) {
			$message = "Investigate why and how receiver email is wrong. Email = " . $_POST['receiver_email'] . "\n\n\n$req";
			mail($myemail, "Receiver Email is incorrect", $message, "From: you@youremail.com" );
			exit(); // exit script
		}
		// Check number 2 ------------------------------------------------------------------------------------------------------------
		if ($_POST['payment_status'] != "Completed") {
			// Handle how you think you should if a payment is not complete yet, a few scenarios can cause a transaction to be incomplete
			die('sorry payment not completed');
		}
		
		// Check number 3 ------------------------------------------------------------------------------------------------------------
		
		if( $this->checkTransection($_POST['txn_id'])) {
			
			$message = "Duplicate transaction ID occured so we killed the IPN script. \n\n\n$req";
			mail($myemail, "Duplicate txn_id in the IPN system", $message, "From: you@youremail.com" );
			exit(); // exit script
			
			
			
		}
	
		// Check number 4 ------------------------------------------------------------------------------------------------------------
		$product_id_string = $_POST['custom'];
	
		$fullAmount = count($_POST['custom'])*200;
		$grossAmount = $_POST['mc_gross']; 
		if ( $fullAmount != $grossAmount) {
				$message = "Possible Price Jack: " . $_POST['payment_gross'] . " != $fullAmount \n\n\n$req";
				mail($myemail, "Price Jack or Bad Programming", $message, "From: you@youremail.com" );
				exit(); // exit script
		} 


		// END ALL SECURITY CHECKS NOW IN THE DATABASE IT GOES ------------------------------------
		////////////////////////////////////////////////////
		// Homework - Examples of assigning local variables from the POST variables
		$txn_id = $_POST['txn_id'];
		$payer_email = $_POST['payer_email'];
		$custom = $_POST['custom'];
		// Place the transaction into the database
		
		//expolde custom array on '-', and get  subjectId and studentId,
		//save them to database
		
		
		 $this->checkAndSaveTransection($_POST['txn_id'],$custom );
		
		// Mail yourself the details
	
		$subject = "Payment Done";
		$txt = "payment done successfully!";
		$headers = "From: you@youremail.com" . "\r\n" .
		"CC: someone@example.com";
		 mail($myemail,$subject,$txt,$headers);
		
				
		
		
		
	}
	 
	 
	 
	
	
	public function checkTransection($txn)
	{
		
			//$model = $this->getModel('shoppingcart'); 
			//return $model->checkTransection($txn);
		
		
	}
        
        public function test(){
            
            $this->checkAndSaveTransection('1221','2L1ULtElqJJK');
          
        }
	
	public function checkAndSaveTransection($txn,$custom)
	{
		$model = $this->getModel('shoppingcart'); 
		if(	$model->checkAndSaveTransection($txn,$custom)){
					
			echo 'Transection Data saved';
                        }else {  echo 'This Transection doesnt Exist'; }
		
	}
	
		
	

	
	
	
	
	
}
