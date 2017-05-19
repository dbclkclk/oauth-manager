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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
	$user       = JFactory::getUser();
	$userId     = $user->get('id');
	/*
	$listOrder  = $this->state->get('list.ordering');
	$listDirn   = $this->state->get('list.direction');
	$canCreate  = $user->authorise('core.create', 'com_teachpro');
	$canEdit    = $user->authorise('core.edit', 'com_teachpro');
	$canCheckin = $user->authorise('core.manage', 'com_teachpro');
	$canChange  = $user->authorise('core.edit.state', 'com_teachpro');
	$canDelete  = $user->authorise('core.delete', 'com_teachpro');
	*/
echo 'Current PHP version: ' . phpversion();
//phpinfo();
?>
 {instantpaypal}{/instantpaypal}
{instantpaypal}action=addtocart,price=30,taxamount=5,taxtext=USMAIL+,productname=DemoProductxxx1,showquantity=1{/instantpaypal}

<form class="paypal" action="http://localhost/techpro/index.php?option=com_teachpro&task=processPaypalPayment" method="post" id="paypal_form" target="_blank">
		<input type="hidden" name="cmd" value="_xclick" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="lc" value="UK" />
		<input type="hidden" name="currency_code" value="GBP" />
		<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
		<input type="hidden" name="first_name" value="Customer's First Name"  />
		<input type="hidden" name="last_name" value="Customer's Last Name"  />
		<input type="hidden" name="payer_email" value="customer@example.com"  />
		<input type="hidden" name="item_number" value="123456" / >
		
	
						
						
						
		<input type="submit" name="submit" value="Submit Payment"/>
	</form>
	
	
	
	
	
	
	
	

<form id = "paypal_checkout" action = "http://localhost/techpro/index.php?option=com_teachpro&task=processPaypalPayment" method = "post">
    <input name = "cmd" value = "_cart" type = "hidden">
    <input name = "upload" value = "1" type = "hidden">
    <input name = "no_note" value = "0" type = "hidden">
    <input name = "bn" value = "PP-BuyNowBF" type = "hidden">
    <input name = "tax" value = "0" type = "hidden">
    <input name = "rm" value = "2" type = "hidden">
 
    <input name = "business" value = "ersandeepthapa@gmail.com" type = "hidden">
    <input name = "handling_cart" value = "0" type = "hidden">
    <input name = "currency_code" value = "GBP" type = "hidden">
    <input name = "lc" value = "GB" type = "hidden">
    <input name = "return" value = "http://mysite/myreturnpage" type = "hidden">
    <input name = "cbt" value = "Return to My Site" type = "hidden">
    <input name = "cancel_return" value = "http://mysite/mycancelpage" type = "hidden">
    <input name = "custom" value = "" type = "hidden">
 
    <div id = "item_1" class = "itemwrap">
        <input name = "item_name_1" value = "Gold Tickets" type = "hidden">
		
        <input name = "quantity_1" value = "4" type = "hidden">
        <input name = "amount_1" value = "30" type = "hidden">
       
    </div>
    <div id = "item_2" class = "itemwrap">
        <input name = "item_name_2" value = "Silver Tickets" type = "hidden">
		
        <input name = "quantity_2" value = "2" type = "hidden">
        <input name = "amount_2" value = "20" type = "hidden">
      
    </div>
    <div id = "item_3" class = "itemwrap">
        <input name = "item_name_3" value = "Bronze Tickets" type = "hidden">
		
        <input name = "quantity_3" value = "2" type = "hidden">
        <input name = "amount_3" value = "15" type = "hidden">
 
    </div>
 
    <input id = "ppcheckoutbtn" value = "Checkout" class = "button" type = "submit">
</form>














<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="business" value="ersandeepthapa@gmail.com">
	
	  <div id = "item_1" class = "itemwrap">
        <input name = "item_name_1" value = "Gold Tickets" type = "hidden">
		
        <input name = "quantity_1" value = "4" type = "hidden">
        <input name = "amount_1" value = "30" type = "hidden">
       
    </div>
    <div id = "item_2" class = "itemwrap">
        <input name = "item_name_2" value = "Silver Tickets" type = "hidden">
		
        <input name = "quantity_2" value = "2" type = "hidden">
        <input name = "amount_2" value = "20" type = "hidden">
      
    </div>
    <div id = "item_3" class = "itemwrap">
        <input name = "item_name_3" value = "Bronze Tickets" type = "hidden">
		
        <input name = "quantity_3" value = "2" type = "hidden">
        <input name = "amount_3" value = "15" type = "hidden">
 
    </div>
	
	
	<input type="hidden" name="custom" value="' . $product_id_array . '">
	<input type="hidden" name="notify_url" value="http://localhost/techpro/index.php?option=com_teachpro&task=Shoppingcart.my_ipn">
	<input type="hidden" name="return" value="http://localhost/techpro/index.php?option=com_teachpro&view=tests">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="cbt" value="Return to The Store">
	<input type="hidden" name="cancel_return" value="https://localhost/techpro/index.php?option=com_teachpro&view=tests">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="currency_code" value="USD">
	<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
	</form>
		
		

<?php
/*
$to = "ersandeepthapa@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com" . "\r\n" .
"CC: somebodyelse@example.com";
 mail($to,$subject,$txt,$headers);

if( mail($to,$subject,$txt,$headers)) {
	
	echo 'done';
}else echo 'khoya'
*/




/* //working
$to = "ersandeepthapa@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com" . "\r\n" .
"CC: somebodyelse@example.com";
 mail($to,$subject,$txt,$headers);
 */
?>





		
		
	
	
	
	
	


