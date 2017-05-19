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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_teachpro/css/form.css');
$document->addScript(JUri::root() . 'administrator/components/com_teachpro/assets/js/knockout.js');


JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');



$this->getAnswers($this->item->id);
$summaryText = "";
if(!empty($this->item->summaryID))
{
    $summaryText =  $this->getSummaryText($this->item->summaryID);
}
$currentGradeSubject = "";
$subjects = "";
$goals = "";
if($this->item->goalid)
{
    $currentGradeSubject = $this->getGradeAndSubject($this->item->goalid);


    $subjects = $this->getRelatedSubjects($currentGradeSubject->currentGrade);


    $goals = $this->getRelatedGoals($currentGradeSubject->currentSubject);

}
?>

<style>
    
    .form-horizontal .controls
    {
        margin-left: 80px !important;
    }
    
</style>
<script type="text/javascript">
	js = jQuery.noConflict();
	

	Joomla.submitbutton = function (task) {
		if (task == 'questions.cancel') {
			Joomla.submitform(task, document.getElementById('students-form'));
		}
		else {
			
			if (task != 'questions.cancel' && document.formvalidator.isValid(document.id('students-form'))) {
				
				Joomla.submitform(task, document.getElementById('students-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
      jQuery(document.body).on('change', '#jform_summary_dropdown', function (){
        var id = jQuery(this).val(); 
        jQuery.ajax({method: "GET",
            url: "index.php?option=com_teachpro&task=summary.getSummaryText",
            data:{id: id}
        }).done(function( msg ) {
            jQuery('#jform_summary_textbox').text(msg);
        });
            
      });
</script>


<form
	action="<?php echo JRoute::_('index.php?option=com_teachpro&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="students-form" class="form-validate">

    <div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'question')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'question', JText::_('COM_TEACHPRO_TITLE_STUDENTS', true)); ?>
                
        
                <div class="container">
                <div class="row-fluid"> 
                    <div class="span6 form-horizontal">
			<fieldset class="adminform">
                            
                            
                            	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
                                <?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
				<?php if(empty($this->item->modified_by)){ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />

				<?php } ?>	
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('name'); ?></div>
                                </div>
                                        
                                <div class="control-group">
                                    <div class="control-label">
                                        Grade
                                    </div>
                                    <div class="controls">
                                        <select class="form-control" id="jform_grade" name="jform[grade]" data-bind="value:grade, event:{change:updateSubjects}">
                                            <option value=""> <?php echo JText::_("COM_TEACHPRO_FORM_SUBJECT_FIELD_GRADE_DEFAULT_VALUE") ?></option>
                                            <option value="1" >1</option>
                                            <option value="2" >2</option>
                                            <option value="3" >3</option>
                                            <option value="4" >4</option>
                                            <option value="5" >5</option>
                                            <option value="6" >6</option>
                                            <option value="7" >7</option>
                                            <option value="8" >8</option>
                                            <option value="9" >9</option>
                                            <option value="10" >10</option>
                                            <option value="11" >11</option>
                                            <option value="12" >12</option>
                                            <option value="k" >K</option>
                                        </select> 
                                    </div>
                                </div>
                                        
                                        
                                 <div class="control-group">
                                    <div class="control-label">
                                        Subject
                                    </div>
                                    <div class="controls">
                                        <select  id="jform_subject" name="jform[subject]" data-bind="chosen:subjects, optionsText:'name', optionsValue:'id', value:subject, optionsCaption:'Choose a subject', event:{change:updateGoals}">
                                        </select> 
       
                                    </div>
                                </div>
                                
                                        
                                <div class="control-group">
                                    <div class="control-label">
                                        Goals
                                    </div>
                                    <div class="controls">
                                        <select  id="jform_goalid" name="jform[goalid]" data-bind="chosen:goals, optionsText:'name', optionsValue:'id', value:goal, optionsCaption:'Choose a goal'">
                                        </select> 
                                    </div>
                                </div>
                                       
                               
                                        
                        </fieldset>
                    </div>
                    <div class="span6">
                         <div class="control-group">
                             <div class="span12">
                                 <div class="span8">
                                      <div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('type'); ?></div>
                                 </div>
                                 <div class="span4" data-bind="visible: checkQuestionType">
                                       <button type="button" data-bind="click:$root.openEditor, visible:$root.showAddButton()==true" class="btn btn-small btn-success" data-toggle="tooltip" title="Add an answer"><span class="icon-plus"></span></button>
                                 </div>
                                 
                             </div>
                                   
                         </div>
                         <div class="control-group">
                                <div class="span12">
                                        <div class="row span12">
                                            <div class="accordion span12" id="editor-according">
                                                    <div class="accordion-group">
                                                        <div id="collapseOne" class="accordion-body collapse out">
                                                                           <?php 
                                                                           $editor = JFactory::getEditor();
                                                                           $params = array( 'smilies'=> '0' ,
                                                                                    'style'  => '1' ,  
                                                                                    'layer'  => '0' , 
                                                                                    'table'  => '0' ,
                                                                                    'clear_entities'=>'0'
                                                                                    );
                                                                           echo $editor->display( 'answertext', '', '400', '300', '40', '30', array("article","readmore","digicom","pagebreak"),"answertext",null,null,$params );
                                                                            ?>
                                                         </div>
                                                    </div>
                                            </div>
                                        </div>
                                </div>
                        </div> 
                        <div class="row">
                                        <div class="span12">
                                             <div class="span7"></div>
                                             <div class="span1">
                                                 
                                              
                                                
                                                <button type="button" data-bind="click:$root.closeEditor, visible:$root.showDeclineButton()==true" class="btn btn-small btn-info" data-toggle="tooltip" title="Remove content in editor"><span class="icon-minus"></span></button>
                                                
                                            </div>
                                            <div class="span3">
                                                <button type="button" class="btn btn-small btn-default" data-bind="click:$root.updateList, visible:$root.showSaveButton()==true"><span class="icon-ok"></span></button>
                                            </div>
                                        </div>
                                        <div class="span12" data-bind="visible:answers().length>0 && type==='0'">
                                            <div class="span7"></div>
                                            <div class="span3">
                                                <div class="control-label"><b>Correct Answer?</b></div>
                                            </div>

                                        </div>
                                        <div class="span12 offset2">
                                                <div class="span12">
                                                 <div  class="span12 control-group" data-bind="foreach:answers">
                                                     <input type="hidden" data-bind="value:id, attr: { name: 'jform[questions]['+$index()+'][id]'}"  />
                                                     <input type="hidden" data-bind="value:ordering, attr: { name: 'jform[questions]['+$index()+'][ordering]'}"  />
                                                     <input type="hidden" data-bind="value:state, attr: { name: 'jform[questions]['+$index()+'][state]'} " />
                                                     <input type="hidden" data-bind="value:created_by, attr: { name: 'jform[questions]['+$index()+'][created_by]'} " />
                                                     <input type="hidden" data-bind="value:modified_by, attr: { name: 'jform[questions]['+$index()+'][modified_by]'}"  />

                                                     <input type="hidden" data-bind="value:checked_out, attr: { name: 'jform[questions]['+$index()+'][checked_out]'}"  />
                                                     <input type="hidden" data-bind="value:checked_out_time, attr: { name: 'jform[questions]['+$index()+'][checked_out_time]'}" />
                                                     <input type="hidden" data-bind="value:checked_out_time, attr: { name: 'jform[questions]['+$index()+'][questionid]'}" />
                                                     <div class="span12">
                                                         <div class="span1">
                                                             <label data-bind="text: ($index()+1)+'.'"></label>
                                                         </div>
                                                         <div class="span6">
                                                             <div data-bind="stripHtml:name" style="overflow-x: hidden"></div>
                                                                 
                                                             <input type="hidden" data-bind="value:name, attr: { name: 'jform[questions]['+$index()+'][name]'}" placeholder="Enter answer"  />
                                                        </div>
                                                        <div class="span5">
                                                           <div class="span12 checkbox" data-bind="visible: $parent.type()==0">
                                                               <div class="span4">
                                                                   <label class="radio">
                                                                    <input type="radio" data-bind="checked:correct, attr: { name: 'jform[questions]['+$index()+'][correct]'}" value="0" checked>
                                                                    No
                                                                  </label>
                                                                  <label class="radio">
                                                                    <input type="radio" data-bind="checked:correct, attr: { name: 'jform[questions]['+$index()+'][correct]'}"  value="1">
                                                                    Yes
                                                                  </label>
                                                                </div>
                                                                <div class="span6">
                                                             
                                                                    <button type="button" data-bind="click:$parent.removeAnswer" class="btn btn-small btn-warning" data-toggle="tooltip" title="Delete answer"><span class="icon-remove"></span></button>
                                                                    <button type="button" data-bind="click:$root.openEditor" class="btn btn-small btn-default"><i class="icon-edit" data-toggle="tooltip" title="Edit this answer"></i></button>
                                                                    
                                                                </div>
                                                           </div>
                                                            <div class="checkbox" data-bind="visible: $parent.type()==1">
                                                                <input type="hidden" data-bind="value:correct,attr: { name: 'jform[questions]['+$index()+'][correct]'}" />
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                         
                                                     </div>
                                                 </div>
                                            </div>
                                        </div>
                        </div>
                       
                        
                        <!--
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('summaryID');?></div>
                            <div class="controls">
                                <span><?php echo $this->form->getInput('summaryID');?> </span>
                                <a type="button" href="index.php?option=com_teachpro&view=summary&layout=edit" class="btn btn-small btn-info modal">Add Summary</a>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <div class="controls">
                              <?php echo $this->form->getInput('summary-textbox',null,$summaryText); ?></div>
                        </div>
                        
                        <div class="control-group">
                             <div class="control-label"><?php echo $this->form->getLabel('DisplaySummary');?></div>
                            <div class="controls"><?php echo $this->form->getInput('DisplaySummary'); ?></div>
                        </div>
                        
                        -->
                    </div>
                </div>
                </div>
    </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
    
    
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'summary',"Adding Summary Text"); ?>
    
    
                <?php echo JHtml::_('bootstrap.endTab'); ?>
		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value="questions.save"/>
		<?php echo JHtml::_('form.token'); ?>
   
</form>

<script>

jQuery(document).ready(function(){
    
     jQuery("#jform_subject").trigger("liszt:updated");
    
    jQuery("#jform_goalid").trigger("liszt:updated");
    
});

function Answer(data) {
    this.id = ko.observable(data.id);
    this.correct = ko.observable(data.correct);
    this.name = ko.observable(data.name);

    this.description = ko.observable(data.description);
    this.ordering = ko.observable(data.ordering);
    this.state = ko.observable(data.state);

    this.checked_out = ko.observable(data.checked_out);
    this.checked_out_time = ko.observable(data.checked_out_time);
    this.created_by = ko.observable(data.created_by);

    this.modified_by = ko.observable(data.modified_by);
    this.questionid = ko.observable(data.questionid);
}

function Goal (data)
{
    that = this;
    that.id = ko.observable(data.id);
    that.name = ko.observable(data.name);
}

function Subject (data)
{
    that = this;
    that.id = ko.observable(data.id);
    that.name = ko.observable(data.subject_name);
}

function AnswerListViewModel(answers, goallist, subjectlist, current) {
    // Data
    
    console.log(that);
    var that = this;
    
    
    
    that.subject = ko.observable();
    that.goal = ko.observable();
    
    
    that.answers = ko.observableArray(ko.utils.arrayMap(answers, function(obj) {
    return new Answer(obj);
  }));
  
  
  
  
  that.goals = ko.observableArray(ko.utils.arrayMap(goallist, function(obj) {
    return new Goal(obj);
  }));
  
  
   that.subjects = ko.observableArray(ko.utils.arrayMap(subjectlist, function(obj) {
       var holder = new Subject(obj);
       return holder;
  }));
  
  
  
  var subHolder =ko.utils.arrayFirst(that.subjects(),function(item){
      
     if(item.id()==current.currentSubject)
         return true
     else
         return false
  });
  console.log(subHolder);
  
  if(subHolder)
  {
        that.subject(subHolder.id());
  }
  
  
  



  var goalHolder =ko.utils.arrayFirst(that.goals(),function(item){
    
     if(item.id()==<?php echo ($this->item->goalid!=null ? $this->item->goalid :  -1) ?>)
         return true
     else
         return false;
  });
  
  if(goalHolder)
  {
        that.goal(goalHolder.id());
  }


  
  
    
    that.type = ko.observable('0');
    // Operations
    that.currentEditedAnswer = ko.observable();
    that.showAddButton = ko.observable(true);
    
 
 
    that.grade = ko.observable(current.currentGrade);
    
  

    that.addAnswer = function() {
        var answer = that.createNewAnswer();
        that.answers.push(answer);
    };
    that.createNewAnswer = function() {
        var correct = false;
        if (that.type() == "1")
            correct = true;
        return new Answer({
            id: null,
            correct: correct,
            name: null,
            description: null,
            ordering: null,
            state: null,
            checked_out: null,
            checked_out_time: null,
            created_by: null,
            modified_by: null,
            questionid: null
        });

    }

    that.openEditor = function() {
        var text = '';
        jQuery("#collapseOne").collapse("show");
        if (this instanceof Answer) {
            text = this.name();
            that.currentEditedAnswer(this);
        } else
            that.currentEditedAnswer(that.createNewAnswer())

        tinyMCE.get("answertext").setContent(text);
        tinyMCE.triggerSave();
    };


    that.closeEditor = function() {
        jQuery("#collapseOne").collapse("hide");
        that.currentEditedAnswer(null);

        tinyMCE.get("answertext").setContent("");
        tinyMCE.triggerSave();
    };



    that.showDeclineButton = ko.computed(function() {

        if ((that.currentEditedAnswer() != "" && that.currentEditedAnswer() != null))
            return true;
        else
            return false;
    });

    that.showSaveButton = ko.computed(function() {

        if (that.currentEditedAnswer() != null && that.currentEditedAnswer() != "") {
            return true;
        } else
            return false;

    });
    that.showSaveButton.subscribe(function(val) {

        if (val)
            that.showAddButton(false);
        else
            that.showAddButton(true);

    });
    


    that.updateList = function() {
        tinyMCE.triggerSave();
        var text = tinyMCE.get("answertext").getContent();
        if (that.currentEditedAnswer() !== null && that.currentEditedAnswer() !== undefined) {
            that.currentEditedAnswer().name(text);
            if (that.answers.indexOf(that.currentEditedAnswer()) <0)
                that.answers.push(that.currentEditedAnswer());
            that.closeEditor();
        }
    };
    
    that.checkQuestionType = ko.computed(function(){
        
        if(that.type()=='1')
        {
            if(that.answers().length<1)
                return true;
            else
                return false;
        }
        else
            return true;        
    });

    that.removeAnswer = function(answer) {
        that.answers.remove(answer);
    };

    that.setTypeAnswer = function() {

        
        if (that.type() == "" || that.type() == null)
            return;

        that.answers.removeAll();
    };
    
    that.updateSubjects = function (){
        
        var subjectsURL = '<?php echo JRoute::_("index.php?option=com_teachpro&format=json&task=subjectss.getsubjects&grade=",false) ?>'+that.grade();
        
        
        
        jQuery.ajax({
            url:subjectsURL,
            dataType:"json",
            success:function(data)
            {
                if(data.success)
                {
                    that.subjects.removeAll();
                    that.goals.removeAll();
                    ko.utils.arrayForEach(data.data,function(result){
                        that.subjects.push(new Subject(result));
                    });
                }
            }
        });      
    };
    
    that.updateGoals = function ()
    {
       var goalsURL = "<?php echo JRoute::_("index.php?option=com_teachpro&format=json&task=goalss.getgoals&subjectid=",false) ?>"+that.subject();

        
        jQuery.ajax({
            url:goalsURL,
            dataType:"json",
            success:function(data)
            {
                if(data.success)
                {
                    that.goals.removeAll();
                    ko.utils.arrayForEach(data.data,function(result){
                        that.goals.push(new Goal(result));
                    });
                }
            }
        }); 
    }
}


var helper = document.createElement('div');

ko.bindingHandlers.stripHtml = {
    update: function(elem, valueAccessor) {
        var html = ko.unwrap(valueAccessor());
        helper.innerHTML = html;
        elem.innerText = (helper.innerText + "").trim();
    }
};


ko.bindingHandlers.chosen = {
    init: function(element)  {
        ko.bindingHandlers.options.init(element);
        jQuery(element).chosen({disable_search_threshold: 10});
    },
    update: function(element, valueAccessor, allBindings) {
        ko.bindingHandlers.options.update(element, valueAccessor, allBindings);
        jQuery(element).trigger('liszt:updated');
    }
};

ko.applyBindings(new AnswerListViewModel(<?php echo json_encode($this->answers) ?>, <?php echo json_encode($goals) ?>, <?php echo json_encode($subjects) ?>, <?php echo json_encode($currentGradeSubject) ?>));



</script>
