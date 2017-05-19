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
 * Students controller class.
 *
 * @since  1.6
 */
class TeachproControllerSubjects extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'subjectss';
		parent::__construct();
	}
	
	
	function check_txnid($tnxid){
            global $link;
            return true;
            $valid_txnid = true;
            //get result set
            $sql = mysql_query("SELECT * FROM `payments` WHERE txnid = '$tnxid'", $link);
            if ($row = mysql_fetch_array($sql)) {
                    $valid_txnid = false;
            }
            return $valid_txnid;
        }

        function check_price($price, $id)
        {
               $valid_price = false;
                //you could use the below to check whether the correct price has been paid for the product

                /*
                $sql = mysql_query("SELECT amount FROM `products` WHERE id = '$id'");
                if (mysql_num_rows($sql) != 0) {
                        while ($row = mysql_fetch_array($sql)) {
                                $num = (float)$row['amount'];
                                if($num == $price){
                                        $valid_price = true;
                                }
                        }
                }
                return $valid_price;
                */
                return true;
        }

        function updatePayments($data)
        {
                global $link;

                if (is_array($data)) 
                {
                        $sql = mysql_query("INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, createdtime) VALUES (
                                        '".$data['txn_id']."' ,
                                        '".$data['payment_amount']."' ,
                                        '".$data['payment_status']."' ,
                                        '".$data['item_number']."' ,
                                        '".date("Y-m-d H:i:s")."'
                                        )", $link);
                        return mysql_insert_id($link);
                }
         }
}
