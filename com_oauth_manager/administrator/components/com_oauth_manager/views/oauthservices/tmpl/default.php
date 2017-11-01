<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'media/com_oauth_manager/css/oauthmanager.css');
?>
<div id="oauth-manager">
</div>
<script type="text/javascript" src="<?= JUri::root().'media/com_oauth_manager/js/com_oauthmanager/build/bundled.js' ?>"></script>