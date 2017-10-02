<?php

defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

 if(isset($_POST['custom'])){
          // post back from paypal
         // this is my email, the one who is receiving the payment, seller email get in from data base or modify in production 
        $myemail= "ersandeepthapa@gmail.com";
          
       // check if the corret payment is done
        $receiver_email = $_POST['receiver_email'];
            if ($receiver_email != $myemail) {
            $message = "Investigate why and how receiver email is wrong. Email = " . $_POST['receiver_email'] . "\n\n\n$req";
            mail($myemail, "Receiver Email is incorrect", $message, "From: you@youremail.com" );
            exit(); // exit script
        }
        // uncoment when taken to UAT , sandbox gives payment status as pending which we cannot use to test
        // but this is important because we wanna check if the payment is completed or not.
        /*
        // check if payment status is complete or not
        if ($_POST['payment_status'] != "Completed") {
            // Handle how you think you should if a payment is not complete yet, a few scenarios can cause a transaction to be incomplete
        die('sorry payment not completed');
        }
         // check about the price, either pull the price from db or modify in production if static values
        $fullAmount = $_POST['num_cart_items']*20;
        $grossAmount = $_POST['mc_gross']; 
        if ( $fullAmount != $grossAmount) {
                    $message = "Possible Price Jack: " . $_POST['payment_gross'] . " != $fullAmount \n\n\n$req";
                    mail($myemail, "Price Jack or Bad Programming", $message, "From: you@youremail.com" );
                    exit(); // exit script
            } 
         * 
         * 
         */
           // looks like everything is okay so modify is_paid status  
        $status = $this->checkAndSaveTransection($_POST['txn_id'],$_POST['custom'] );
          if( $status ){
              
              ?>

            <div class="alert alert-success">
             <a href="#" class="close" data-dismiss="alert">&times;</a>
              <strong>Success!</strong> Payment done  successfully.
            </div>
            <?php  } else { ?>

             <a href="#" class="close" data-dismiss="alert">&times;</a>
              <strong>Oops!</strong> Something is wrong, please contact the vendor or check your paypal Id.
            </div>

            <?php } 

          
          
      }
?>

<button class="btn btn-primary" style="float: right;margin-bottom: 10px;">Take Test Later</button>
<form id="adminForm" method="post" name="adminForm">
<table class="table table-striped table-bordered">
    
    <thead>
        <tr>
            <th width="30%"> <?php echo JHtml::_('grid.sort', 'Student', 
                'student_id', $listDirn, $listOrder); ?>
                </th>
            
            <th width="20%"> <?php echo JHtml::_('grid.sort', 'Subject', 
                'subject_id', $listDirn, $listOrder); ?>
                </th>

            <th width="30%"> <?php echo JHtml::_('grid.sort', 'Grade', 
                'payment_id', $listDirn, $listOrder); ?>
                </th>
        
            <th width="20%"> <?php echo JHtml::_('grid.sort', 'Status', 
                'is_test_taken', $listDirn, $listOrder); ?>
            </th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($this->items as $i => $item) :?>
            <tr>
                <td>
                <a href="#"><?php echo $this->escape($item->firstname).' '.$this->escape($item->lastname); ?></a>
                </td>

                <td>
                <a href="#">
                    <?php echo $this->escape($item->subject_name); ?></a>
                </td>

                <td>
                <a href="#"><?php echo $this->escape($item->gradelevel); ?></a>
                </td>

                <td>
                <?php
                if($item->is_test_taken == 1)
                {
                echo '<button class="btn btn-info completed"> Test Completed </button>';
                }  else {?>
                <a class="btn btn-success"
                    href="<?php echo '?option=com_teachpro&view=tests&student_id='.(int) $item->id.'&subject_id='. (int) $item->id; ?>">
                Start Test Now
                 </a> 
                <?php } ?>
                </td>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>

    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
</form>

<script type="text/javascript">
    jQuery('button.completed').on('click', function(e) {
        e.preventDefault();
    });

</script>