<?php
defined('_JEXEC') or die; 

 JHtml::stylesheet('com_teachpro/bootstrap.min.css', array(), true);
?>
 
 <form action="./?option=com_teachpro&view=questions&layout=questionlist" method="post" id= "adminForm" name="adminForm">

    <input type="hidden" name="option" value="com_teachpro" />
    <input type="hidden" name="task" value="list" />
    <input type="hidden" name="boxchecked" value="0" />

<div class="container">
  <h2> <a href= "index.php?option=com_teachpro&view=Questions&layout=edit" class="btn btn-primary" role="button"  >  Add New </a> 
    Questions List</h2>
  <p></p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="col-md-1">S.N.</th>
        <th class="col-md-3">Question Name</th>
        <th class="col-md-6"> Answers </th>
        <th class="col-md-1"  >Edit</th>
		    <th class="col-md-1">Delete</th>
      </tr>
    </thead>
    
    <tbody>

        <tr>
          <td>1</td>
          <td >What is the highest Mountain</td>
          <td>  
              <ul class="list-group" style="margin: 0;">
                <li class="list-group-item">First item</li>
                <li class="list-group-item list-group-item-success">Second item</li>
               <li class="list-group-item">Third item</li>
              </ul>
          </td>
         
         <td >  <a href= "index.php?option=com_teachpro&view=questions&layout=edit&questionid=" class="btn btn-warning" role="button"> EDIT </a> 
         </td>
		    
          <td >
			     <a href= "index.php?option=com_teachpro&view=subjects&layout=subjectlist&deleteaction=" 
              class="btn btn-danger" role="button" 
              onclick="javascript:return confirm('Are you sure you want to delete?')" >  DELETE </a> 
      </tr>

         <tr>
          <td>1</td>
          <td >What is the highest Mountain</td>
          <td>  
              <ul class="list-group" style="margin: 0;">
                <li class="list-group-item">First item</li>
                <li class="list-group-item list-group-item-success">Second item</li>
               <li class="list-group-item">Third item</li>
              </ul>
    
         <td >  <a href= "index.php?option=com_teachpro&view=questions&layout=edit&questionid=" class="btn btn-warning" role="button"> EDIT </a> 
         </td>
        
          <td >
           <a href= "index.php?option=com_teachpro&view=subjects&layout=subjectlist&deleteaction=" 
              class="btn btn-danger" role="button" 
              onclick="javascript:return confirm('Are you sure you want to delete?')" >  DELETE </a> 
      </tr>

      <tr>
        <td><?php echo $this->pagination->getLimitBox(); ?> </td>
        <td><?php echo $this->pagination->getListFooter(); ?></td>
      </tr>
    </tbody>
  </table>
</div>   
</form>

