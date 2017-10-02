<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
use League\OAuth2\Client\Token;

class Oauthbaselist extends JModelList
{
    
        private $oauthProvider = null;
        private $accessToken = null;
        protected $resourceEndpoint = null;
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
            if($this->checkBasic())
            {
                $this->oauthProvider =  new \League\OAuth2\Client\Provider\GenericProvider([
                    'clientId'                => JComponentHelper::getParams("com_oauth_manager")->get("oauth_clientid"),    // The client ID assigned to you by the provider
                    'clientSecret'            => JComponentHelper::getParams("com_oauth_manager")->get("oauth_clientsecret"),   // The client password assigned to you by the provider
                    'redirectUri'             => 'http://example.com/your-redirect-url/',
                    'urlAuthorize'            => '',
                    'urlAccessToken'          => JComponentHelper::getParams("com_oauth_manager")->get("oauth_token_url"),
                    'urlResourceOwnerDetails' => ''
                ]);
                $this->accessToken = $this->getAccessToken();
                //$this->resourceEndpoint = JComponentHelper::getParams("com_oauth_manager")->get("oauth_clients_list_endpoint");
            }
            parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_oauth_manager');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.client_id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
            $result = null;
            try
            {
                if ($this->oauthProvider)
                    $result = $this->oauthProvider->getAuthenticatedRequest("GET",$this->resourceEndpoint, $this->accessToken);
                else
                    throw new Exception (JText::_("blah"));
            }
            catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e)
            {
                exit($e->getMessage());
            }
            catch(Exception $e)
            {
                JApplicationCms::getInstance()->enqueueMessage($e, "error");
            }
            return $result;
        }
        public function getList($pks=array())
        {
            $pks = implode(', ', $pks);
   
            $query = $db->getQuery(true);
            
            $query->where("a.id in (".$pks.")");
            $db->setQuery($query);
            return $db->execute();
        }
        private function getAccessToken()
        {
            $accessToken = null;
            $param = JComponentHelper::getParams("com_oauth_manager");
            if ($param->get("oauth_access_token"))
            {
                $accessToken = new Token($param->get("oauth_access_token"));
            }
            if(!$accessToken || $accessToken->hasExpired())
            {
                $accessToken = $this->oauthProvider->getAccessToken("client_credentials");
                $param->set("oauth_access_token", json_encode($accessToken));
            }
          
            return $accessToken;
        }
        protected function checkBasic()
        {
            $result = false;
            if (JComponentHelper::getParams("com_oauth_manager")->get("oauth_token_url") && JComponentHelper::getParams("com_oauth_manager")->get("oauth_clientid") && JComponentHelper::getParams("com_oauth_manager")->get("oauth_clientsecret"))
            {
                $result = true;
            }
            return $result;
        }
}

