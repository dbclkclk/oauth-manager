<?php 

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'media/com_oauth_manager/css/font-awesome.min.css');
$doc->addStyleSheet(JUri::root().'media/com_oauth_manager/css/oauthmanager.css');
?>

<div class="nav-side-menu">
    <div class="brand"><?php echo JText::_("COM_OAUTH_MANAGER_SIDEBAR_MENU") ?></div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <ul id="menu-content" class="menu-content out">
                <li>
                  <a href="#">
                  <i class="fa fa-dashboard fa-lg"></i> Dashboard
                  </a>
                </li>
                <?php
                
                
                foreach($displayData->list as $menu)
                { 
                  ?>
                    <li class="<?php echo $menu[2]==1 ? "active" : "" ?>">
                      <a href="<?php echo $menu[1] ?>"><i class="fa <?php echo $menu[0]['icon'] ?>"></i> <?php echo $menu[0]['text'] ?> <span class="arrow"></span></a>
                    </li>
                <?php 
                }
                ?>
              
            </ul>
     </div>
</div>