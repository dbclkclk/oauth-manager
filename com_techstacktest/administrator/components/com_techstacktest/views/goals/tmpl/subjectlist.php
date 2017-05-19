<?php

defined('_JEXEC') or die; 
?>




<?php




		switch ($this->status) {
			case "0":
				
				break;
			case "1":
			?>
				<div class="alert alert-success">
 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> Subject added successfully.
</div>
<?php $this->status =0;
				break;
			case "2":
			?><div class="alert alert-success">
 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> Subject updated successfully.
</div>
<?php $this->status =0;
				break;
				
					case "3":
			?><div class="alert alert-success">
 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> Subject Deleted successfully.
</div>
<?php $this->status=0;
				break;
			default:
			
		}
		?>

 
 <form action="./?option=com_teachpro&view=subjects&layout=subjectlist" method="post" id= "adminForm" name="adminForm">



    <input type="hidden" name="option" value="com_teachpro" />
    <input type="hidden" name="task" value="list" />
    <input type="hidden" name="boxchecked" value="0" />


<?php

//echo '<pre>';
//print_r($this->goals);
//print_r($this->getGoals('9'));
//echo '</pre>';


//$model = $this->getModel('subjects'); 


/*


echo '<pre>';
print_r($this->pagination);
echo '</pre>';*/
?>

<div class="container">
  <h2> <a href= "index.php?option=com_teachpro&view=subjects&layout=edit" class="btn btn-primary" role="button"  >  Add New </a> 
    Subject List</h2>
  <p></p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="col-md-1" style="width: 20px;" >S.N.</th>
        <th class="col-md-5"  >Subject Name</th>
        <th class="col-md-5"> Goals </th>
                <th class="col-md-5" >Edit</th>
		 <th class="col-md-5" >Delete</th>
		
     
      </tr>
    </thead>
    <tbody>
<?php $i=1;
 foreach($this->items as $item) { ?>

     <tr>
        <td  ><?php echo $item->subjectId; ?></td>
        <td   ><?php echo $item->subjectName; ?></td>


  <td >  

           
         
         
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#collapse<?php echo $i; ?>">  <span class="glyphicon glyphicon-chevron-down"></span>Goals </a>
                </h4>
         
             



  </td>
        





         <td   >  <a href= "index.php?option=com_teachpro&view=subjects&layout=edit&subjectid=<?php echo $item->subjectId; ?>" class="btn btn-warning" role="button"  >  EDIT </a> 
 
 
  </td>
		    <td   > 
			
			
			 <a href= "index.php?option=com_teachpro&view=subjects&layout=subjectlist&deleteaction=<?php echo $item->subjectId; ?>"
        class="btn btn-danger" 
        role="button" 
      onclick="javascript:return confirm('Are you sure you want to delete?')" >  DELETE </a> 
 
			  </td>
   
					 
					 
      </tr>
	  <?php $this->getGoals($item->subjectId); ?>
      <td colspan="5">

   <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse">
               

                 <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th >Id   </th>
        <th>Goal    </th>
		    <th>Description  <a href= "index.php?option=com_teachpro&view=subjects&layout=edit" class="btn btn-primary" role="button"  >Add New Goal</a></th>
			    <th>Grade</th>
				    <th>Edit</th>
					    <th>Delete</th>
        
      </tr>
    </thead>
    <tbody>
	<?php foreach($this->goals as $goal) { ?>
      <tr>
        <td><?php echo $goal->goal_id; ?> </td>
		 <td><?php echo $goal->name; ?></td>
		 <td><?php echo $goal->description; ?></td>
		  <td><?php echo $goal->grade; ?></td>
		
		<td><a href= "index.php?option=com_teachpro&view=subjects&layout=edit&subjectid=<?php echo $item->subjectId; ?>" class="btn btn-warning" role="button"  >  EDIT </a> </td>


				 <td   > 
			
			
			 <a href= "index.php?option=com_teachpro&view=subjects&layout=subjectlist&deleteaction=<?php echo $item->subjectId; ?>"
        class="btn btn-danger" 
        role="button" 
      onclick="javascript:return confirm('Are you sure you want to delete?')" >  DELETE </a> 
 
			  </td>
   
      </tr>
	<?php } ?>

      
    </tbody>
  </table>
            
                 </div>
      </td>

      <tr>


      </tr>

     <?php
       $i ++; 
		
		}


     ?>






    <tr>
	<td ><?php echo $this->pagination->getLimitBox(); ?></td>
	  <td   ><?php echo $this->pagination->getListFooter(); ?> </td>
</tr>

    
    </tbody>
  </table>
</div>

     
</form>
