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
	

	
	public function checkTransection($txn)
	{
		
			$model = $this->getModel('shoppingcart'); 

			$model->checkTransection($txn);
		
		
	}	
	
	public function insertData($paisa='latest')
	{
		
			$model = $this->getModel('shoppingcart'); 

			$model->insertData($paisa);
			$txn = rand(5, 15);
				
			$model->saveTransection($paisa); 
				
				
			$model->checkTransection($txn, $txn ); 
	}	
	

	
	
	
	
	
}
