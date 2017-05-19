<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Oauth_manager
 * @author     TechStack Solutions Ltd <support@techstacksolutions.com>
 * @copyright  2017 Techstack Solutions Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Oauthserver controller class.
 *
 * @since  1.6
 */
class Oauth_managerControllerOauthserver extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'oauthservers';
		parent::__construct();
	}
}
