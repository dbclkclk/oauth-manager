<<<<<<< HEAD
<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
?>
<form class="form-horizontal" method="post" 
			action="<?php echo JRoute::_('index.php?option=com_teachpro&view=Questions&layout=edit'); ?>">
    	<div class="control-group">
        	<label class="control-label" for="subjects">Subjects</label>
     		<div class="controls">
        		<select class="form-control" id="subjects" name="subjectoption">
          			<option id="">Select ...</option>
                <?php foreach ($this->subjectList as $subjectKey) { ?>
          				<option value="<?php echo $subjectKey->id;?>">
                    <?php echo $subjectKey->subject_name;  }?> 
                  </option>
            	</select>
        	</div>
    	</div>

    	<div class="control-group" id="goalList">
        	  <label class="control-label" for="goals">Goals</label>    		
        	   <div class="controls">
        		  <select class="form-control" id="goals"  disabled="disabled">
          			<option id="">Select ...</option>
                <option id=""> </option>
              </select> 
        	</div>
    	</div>

    	<div class="control-group">
        	 <label class="control-label" for="questions">Question</label>     		
        	 <div class="controls">
        		<label id="question">
            </label> 
        	</div>
    	</div>

    	<div class="control-group">
        	  <label class="control-label" for="answerType">Answer Type</label>    		
        	 <div class="controls">
        	 	<select class="form-control" id="answertype" name="answertype">
          			<option id="">Select ...</option>
          			<option id="">Multiple</option>
          			<option id="">Single</option>
          			<option id="">Textbox</option> 
        		</select>
        	</div>
    	</div>

      <div id="multiple-options" style="display:none">
    	 <div class="control-group">
			   <label class="control-label" for="options">Options</label>
			   <div class="option_wrapper" id="options">
		 		 <div class="controls">
		 			<p>
						<input type="text" name="multiple-option[]"> &nbsp; 
						<a href="#" class="btn btn-primary addOptionField">Add More</a>
					</p>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="options" name="multiple-answer[]">Answers</label>
			<div class="answer_wrapper">
		 		<div class="controls">
					<p>
						<input type="text"> &nbsp; 
						<a href="#" class="btn btn-primary addAnswerField">Add More</a>
					</p>
				</div>
			</div>
		</div>
  </div>
  <div id="single-options" style="display:none">
    <div class="control-group">
      <label class="control-label" for="options">Options</label>
        <div class="option_wrapper">
          <div class="controls">
    		<p>
              <input type="text" name="sin-multiple-option[]"> &nbsp; 
              <a href="#" class="btn btn-primary addField">Add More</a>
            </p>
          </div>
        </div>
      </div>
      <div class="control-group">
      	<label class="control-label" for="options" name="singleanswer">Answers</label>
      	<div class="controls">
          <input type="text"> 
      	</div>
      </div>
    </div>

    <div id="textbox" style="display:none">
      <div class="control-group">
      	<label class="control-label" for="options">Answers</label>
      	<div class="controls">
        	<input type="text" name="textbox">
        </div>
      </div>
    </div>

    <div class="control-group">
    	<div class="controls">
    	 <input type="submit" value="Save & Close" class="btn btn-primary">
    	 <a href="index.php?option=com_teachpro&view=questions&layout=questionsList" class="btn btn-danger"> Close </a>
    	</div>
    </div>

    <input type="hidden" name="task" value="save">
</form>
</div>

<script type="text/javascript">

  jQuery('#subjects').change(function() {
   var subjectID = jQuery("#subjects option:selected").attr('value');
  jQuery.ajax({
    type: 'get',
    url: 'index.php?option=com_teachpro&task=questions.ajaxGetGoals',
    data: {subjectID: subjectID },
    success: function(data) {
        jQuery('#goalList').html(data);
        }
  });
});

  jQuery('#goalList').change(function() {
   var goalID = jQuery("#goalList option:selected").attr('value');
  jQuery.ajax({
    type: 'get',
    url: 'index.php?option=com_teachpro&task=questions.ajaxGetQuestion',
    data: {goalID: goalID },
    success: function(data) {
        jQuery('#question').text(data);

        }
  });
});
</script>



=======
<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Teachpro
 * @author     TechStack Solutions Ltd <deanclarke811@yahoo.com>
 * @copyright  2016 TeachPro
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

?>

<?php

?>


<?php  //foreach($this->questionList as $question) {


	 // echo $question->question_name;
 // } ?>
<form class="form-horizontal" method="post" 
			action="<?php echo JRoute::_('index.php?option=com_teachpro&view=Questions&layout=edit'); ?>">
    	<div class="control-group">
        	<label class="control-label" for="subjects">Subjects</label>
     		<div class="controls">
        		<select class="form-control" id="subjects" name="subjectoption">
          			<option id="">Select ...</option>
                <?php foreach ($this->subjectList as $subjectKey) { ?>
          				<option value="<?php echo $subjectKey->id;?>">
                    <?php echo $subjectKey->subject_name;  }?> 
                  </option>
            	</select>
        	</div>
    	</div>

    	<div class="control-group" id="goalList">
        	  <label class="control-label" for="goals">Goals</label>    		
        	   <div class="controls">
        		  <select class="form-control" id="goals"  disabled="disabled">
          			<option id="">Select ...</option>
                <option id=""> </option>
              </select> 
        	</div>
    	</div>

    	<div class="control-group">
        	 <label class="control-label" for="questions">Question</label>     		
        	 <div class="controls">
        		<label id="question"></label> 
        	</div>
    	</div>

    	<div class="control-group">
        	  <label class="control-label" for="answerType">Answer Type</label>    		
        	 <div class="controls">
        	 	<select class="form-control" id="answertype" name="answertype">
          			<option id="">Select ...</option>
          			<option id="">Multiple</option>
          			<option id="">Single</option>
          			<option id="">Textbox</option> 
        		</select>
        	</div>
    	</div>

      <div id="multiple-options" style="display:none">
    	 <div class="control-group">
			   <label class="control-label" for="options">Options</label>
			   <div class="option_wrapper" id="options">
		 		 <div class="controls">
		 			<p>
						<input type="text" name="multiple-option[]"> &nbsp; 
						<a href="#" class="btn btn-primary addOptionField">Add More</a>
					</p>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="options" name="multiple-answer[]">Answers</label>
			<div class="answer_wrapper">
		 		<div class="controls">
					<p>
						<input type="text"> &nbsp; 
						<a href="#" class="btn btn-primary addAnswerField">Add More</a>
					</p>
				</div>
			</div>
		</div>
  </div>
  <div id="single-options" style="display:none">
    <div class="control-group">
      <label class="control-label" for="options">Options</label>
        <div class="option_wrapper">
          <div class="controls">
    		<p>
              <input type="text" name="sin-multiple-option[]"> &nbsp; 
              <a href="#" class="btn btn-primary addField">Add More</a>
            </p>
          </div>
        </div>
      </div>
      <div class="control-group">
      	<label class="control-label" for="options" name="singleanswer">Answers</label>
      	<div class="controls">
          <input type="text"> 
      	</div>
      </div>
    </div>

    <div id="textbox" style="display:none">
      <div class="control-group">
      	<label class="control-label" for="options">Answers</label>
      	<div class="controls">
        	<input type="text" name="textbox">
        </div>
      </div>
    </div>

    <div class="control-group">
    	<div class="controls">
    	 <input type="submit" value="Save & Close" class="btn btn-primary">
    	 <a href="index.php?option=com_teachpro&view=questions&layout=questionsList" class="btn btn-danger"> Close </a>
    	</div>
    </div>

    <input type="hidden" name="task" value="save">
</form>
</div>

<script type="text/javascript">

  jQuery('#subjects').change(function() {
   var subjectID = jQuery("#subjects option:selected").attr('value');
  jQuery.ajax({
    type: 'get',
    url: 'index.php?option=com_teachpro&task=questions.ajaxGetGoals',
    data: {subjectID: subjectID },
    success: function(data) {
        jQuery('#goalList').html(data);
        }
  });
});

  jQuery('#goalList').change(function() {
   var goalID = jQuery("#goalList option:selected").attr('value');
  jQuery.ajax({
    type: 'get',
    url: 'index.php?option=com_teachpro&task=questions.ajaxGetQuestion',
    data: {goalID: goalID },
    success: function(data) {
        jQuery('#question').text(data);

        }
  });
});
</script>



>>>>>>> fa91134062de280eccf1a5778b1080e1be293217
