<?php

defined('_JEXEC') or die; 

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
//$document = JFactory::getDocument();
//$document->addStyleSheet(JUri::root() . 'administrator/components/com_teachpro/assets/css/techstacktest.css');
//$document->addStyleSheet(JUri::root() . 'media/com_teachpro/css/list.css');
//$document->addScript(JUri::root() . 'administrator/components/com_teachpro/assets/js/Sortable.js');


$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_teachpro');

$saveOrder = $listOrder == 'a.`ordering`';
 if ($saveOrder)
{
    $saveOrderingUrl = 'index.php?option=com_teachpro&task=goalss.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'goalList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}



$sortFields = $this->getSortFields();

?>

		

 
 <form action="<?php echo JRoute::_('index.php?option=com_teachpro&view=subjectss'); ?>" method="post" id= "adminForm" name="adminForm">

  <div class="container">          
  <table id="table-1" class="table table-striped">
    
    
                            <thead>
				<tr>
					
					<th width="1%" class="nowrap center hidden-phone">
							
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
                                           <?php echo   JHtml::_('grid.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
                                        </th>
					<?php endif; ?>
                                         <th class='left'>
                                            <?php echo JHtml::_('grid.sort',  'S.N.', 'a.`id`', $listDirn, $listOrder); ?>
                                         </th>
                                         <th class='left'>
                                               <strong> <?php echo JText::_("Grade") ?></strong>
                                         </th>
                                        <th class='left'>
                                               <strong> <?php echo JText::_("Goals") ?></strong>
                                         </th>
                                         
                                         <th class='left'>
                                                    <?php echo JHtml::_('grid.sort',  'Subject Name', 'a.`subject_name`', $listDirn, $listOrder); ?>
                                         </th>
                                </tr>

                            </thead>

                            <tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			    </tfoot>
    
                            
                            
                            
                            <tbody>
                            <?php $i=1;
                          /*   foreach($this->items as $i => $item): 
                             {
                                    $canCreate  = $user->authorise('core.create', 'com_teachpro'); 

                                    $ordering   = ($listOrder == 'a.ordering');
                                    $canEdit    = $user->authorise('core.edit', 'com_teachpro');
                                    $canCheckin = $user->authorise('core.manage', 'com_teachpro');
                                    $canChange  = $user->authorise('core.edit.state', 'com_teachpro');
                                 ?>
                                    <tr>
						<td class="order nowrap center hidden-phone">
								
						</td>
                                                 <td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                                 </td>
						<?php if (isset($this->items[0]->state)): ?>
                                                 <td class="center">
                                                        <?php echo JHtml::_('jgrid.published', $item->state, $i, 'studentss.', $canChange, 'cb'); ?>
                                                 </td>
						<?php endif; ?>
                                                <td  ><?php echo $item->id; ?></td>
                                                <td><?php echo $item->grade; ?></td>
                                                <td>  
                                                        <h4 class="panel-title">
                                                          <a data-toggle="collapse" href="#collapse<?php echo $i; ?>">  <span class="glyphicon glyphicon-chevron-down"></span>View Goal</a>
                                                        </h4>
                                                </td>	
                                                <td   ><?php echo $item->subject_name; ?></td>
                                                		 
                                    </tr>
                                    <tr>
                                     <?php 
                                     
                                        $this->getGoals($item->id); 
                                       
                                     
                                     ?>
                                        <td colspan="7" style="padding: 0">
                                                <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse">
                                                     <table class="table table-striped table-bordered">
                                                              <thead>
                                                                <tr>
                                                                  <th width="1%" class="nowrap center hidden-phone">
                                                                   
                                                                  </th>
                                                                  <th>Id </th>
                                                                  <th>Goal</th>
                                                                  <th>Order</th>
                                                                  <th>Description  <a href= "index.php?option=com_teachpro&view=goals&layout=edit&subjectid=<?php  echo $item->id; ?>" class="btn btn-primary" role="button"  >Add New Goal</a></th>
                                                                 
                                                                  <th>Edit</th>
                                                                  <th>Delete</th>
                                                                  <th></th>
                                                                </tr>
                                                              </thead>
                                                              <tbody id="goalList">
                                                              <?php foreach($this->goals as $goal) { ?>
                                                                 <tr>
                                                                      <td class="order nowrap center hidden-phone">
                                                                          <span class="sortable-handler hasTooltip">
                                                                            <i class="icon-menu"></i>
                                                                          </span>
                                                                      </td>
                                                                      <td>
                                                                          <?php echo $goal->goal_id; ?> 
                                                                      </td>
                                                                      <td>
                                                                          <?php echo $goal->name; ?>
                                                                      </td>
                                                                      <td>
                                                                          <?php echo $goal->ordering; ?>
                                                                      </td>
                                                                      <td>
                                                                          <?php echo $goal->description; ?>
                                                                      </td>
                                                                      <td>
                                                                          <a href= "index.php?option=com_teachpro&view=goals&layout=edit&id=<?php echo $goal->id; ?>" class="btn btn-warning" role="button"  >  EDIT </a> 
                                                                      </td>
                                                                      <td>  
                                                                          <a href= "<?php echo JRoute::_( 'index.php?option=com_teachpro&task=goalss.delete&cid='.$goal->id.'&'. JSession::getFormToken(false) .'=1' ) ?>" class="btn btn-danger"  role="button" onclick="javascript:return confirm('Are you sure you want to delete?')" >  DELETE </a> 

                                                                      </td>
                                                                      <td>
                                                                          <?php echo $goal->id ?>
                                                                      </td>

                                                                  </tr>
                                                              <?php } ?>
                                                              </tbody>
                                                      </table>
                                                  </div>
                                                
                                            </td>
                                    </tr>
	 
                                    
                                    <?php 
                                    } 
                                    endforeach; 
                                  */  ?>
                                    <tr>
                                        <td colspan="7"><?php echo $this->pagination->getLimitBox(); ?></td>
                                                <input type="hidden" name="task" value=""/>
                                                <input type="hidden" name="boxchecked" value="0"/>
                                                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                                                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
                                                <?php echo JHtml::_('form.token'); ?>
                                    </tr>
    </tbody>
  </table>
</div>

</form>




<script type="text/javascript">

 Sortable.create(jQuery("#goalList").get(0), {
     animation: 150,
     handle: ".sortable-handler",
     ghostClass: "sortable-ghost",
     onUpdate: function (evt) {
        var itemEl = evt.item;  
        var cid=[];
        var order = [];
        
        var count = 1;
        
       jQuery(itemEl).parent("tbody").children("tr").each(function(){
             cid.push(parseInt(jQuery(this).children("td:nth-child(8)").first().text()));
             order.push(count);
             count++;           
       });
       
       jQuery.ajax({
          
           url:"index.php?option=com_teachpro&task=goalss.saveOrderAjax",
          data: {cid:cid, order:order, "<?php echo JSession::getFormToken() ?>":1},
           type:"POST",
           success:function()
           {
               console.log("success");
           },
           error:function()
           {
               console.log("error");
               
           }
           
           
       });
       }
    
    });
</script>

