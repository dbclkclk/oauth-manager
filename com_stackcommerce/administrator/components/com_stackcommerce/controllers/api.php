<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Stackcommerce
 * @author     Dean Clarke <deanclarke811@yahoo.com>
 * @copyright  2017 Techstack Solutions Ltd. 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Product controller class.
 *
 * @since  1.6
 */
class StackcommerceControllerApi extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'apis';
		parent::__construct();
	}
}
