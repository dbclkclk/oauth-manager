<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Stackcommerce
 * @author     Dean Clarke <deanclarke811@yahoo.com>
 * @copyright  2017 Techstack Solutions Ltd. 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Products list controller class.
 *
 * @since  1.6
 */
class StackcommerceControllerProducts extends StackcommerceController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Products', $prefix = 'StackcommerceModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
