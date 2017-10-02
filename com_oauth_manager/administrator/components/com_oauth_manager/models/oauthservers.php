<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Oauth_manager
 * @author     TechStack Solutions Ltd <support@techstacksolutions.com>
 * @copyright  2017 Techstack Solutions Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Methods supporting a list of Oauth_manager records.
 *
 * @since  1.6
 */
class Oauth_managerModelOauthservers extends Oauthbaselist
{
	public function __construct($config = array())
	{
            if($this->checkBasic())
            {
                $this->resourceEndpoint = JComponentHelper::getParams("com_oauth_manager")->get("oauth_clients_list_endpoint");
            }
            parent::__construct($config);
	}
}
