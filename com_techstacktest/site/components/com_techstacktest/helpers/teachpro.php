<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Class TeachproFrontendHelper
 *
 * @since  1.6
 */

jimport( 'joomla.user.helper' );

final class TeachproFrontendHelper
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_teachpro/models/' . strtolower($name) . '.php'))
		{
			$model = JModelLegacy::getInstance($name, 'TeachproModel');
		}

		return $model;
	}
        /**
	 * Get an instance of the user object
	 *
	 * @param   string  $username  username
	 *
	 * @return null|object
	 */
        public static function getUserByUsername($username)
        {
            $userid = JUserHelper::getUserId($username);
            
            return JFactory::getUser($userid);
        }
       
       
}
