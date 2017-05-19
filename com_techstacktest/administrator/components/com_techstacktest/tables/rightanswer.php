<?php
/**
 * Answers table class
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * ANswers Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TeachproTableRightAnswer extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	/**
	 * @var string
	 */

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function  __construct($db) {
		parent::__construct( '#__teachpro_right_answer', 'id', $db );
	}
}