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

use Joomla\Utilities\ArrayHelper;
/**
 * subjects Table class
 *
 * @since  1.6
 */
class TeachproTableTests extends JTable
{
		function  __construct($db) {
		parent::__construct( '#__teachpro_test', 'id', $db );
	}

}