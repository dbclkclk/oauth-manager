<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'media/com_oauth_manager/css/oauthmanager.css');

$document->addStyleSheet('http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
$document->addStyleSheet('http://fonts.googleapis.com/css?family=Cookie');
?>

<div id="root"></div>

<script type="text/javascript" src="<?= JUri::root().'media/com_oauth_manager/js/com_oauthmanager/build/bundled.js' ?>" ></script>