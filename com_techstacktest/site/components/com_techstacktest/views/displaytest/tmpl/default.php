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
    $document = JFactory::getDocument();

    $document->addStyleSheet(JUri::root() . 'media/com_teachpro/css/base.css');
    $document->addStyleSheet(JUri::root() . 'media/com_teachpro/css/style.css');

    $document->addScript(JUri::root() . 'media/com_teachpro/js/knockout-3.4.0.js');
    $document->addScript(JUri::root() . 'media/com_teachpro/js/jquery-1.9.1.min.js');
    
    $document->addScript(JUri::root() . 'media/com_teachpro/js/jquery.scrollto.min.js');
   
    $questions=json_encode(array_values($this->items));

     
    $questionCount = count($this->items); 
?>





<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
    
            <div class="page-header">


                    <h2>Step 3: TeachPro ONLINE ASSESSMENT</h2>
                    <p>
                        Parents are strictly advised not to assist their child during the online assessment for correct and accurate evaluation 
                    </p>
                    <?php echo JLayoutHelper::render('default_header', array('view' => $this), dirname(__FILE__)); ?>
                    <ul>
                        <li>Total Questions :<?php echo $questionCount; ?> </li>
                       
                    </ul>         
                    <div class="row">
                        <div class="col-sm-4"> <h3> <?php echo $this->studentDetails->studentFirstName. ' '.$this->studentDetails->studentLastName ; ?>   </h3> </div>
                        <div class="col-sm-4">  <h3> Subject : <?php echo $this->studentDetails->subjectName; ?>     </h3> </div>
                        <div class="col-sm-4">  <h3> Grade : <?php echo $this->studentDetails->studentGrade; ?>   </h3> </div>
                   </div>
            </div>
 
            <!-- Carousel items -->
            <div id="test-display" class="carousel slide" data-ride="carousel" data-bind="visible:hideTest()==false">
                <div class="carousel-inner" data-bind="foreach:questions" style="background-color: #f2f5f7 ">
                       <div class="row item">
                                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                        <p> <span data-bind="html:questionName"></span></p>

                                                    <!--<p> <span data-bind="html:questionGoalName"></span></p>
                                                    -->

                                        <div class="multiple">
                                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                <div data-bind="if: typeOfAnswer=='multiple'">
                                                    <label data-bind="foreach:answers">
                                                        <input type="checkbox" name="answer[]" data-bind="attr:{value: answerId},checked:$parent.answer,checkedValue:$data" />
                                                        <span data-bind="html:answerName"></span> </br>
                                                    </label>
                                                </div>
                                                <div data-bind="if: typeOfAnswer=='single'" class="form-inline">
                                                   
                                                    <div  data-bind="foreach:answers">
                                                        <div class="row">

                                                                <!-- ko if: ($parent.type()==0) -->
                                                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                                                        <span data-bind="text:$root.alphabet($index)"></span>
                                                                        <input type="radio" name="answer[]" data-bind="attr:{value: answerId},checked:$parent.answer,checkedValue:$data"  />
                                                                    </div>
                                                                    <div>
                                                                        <b data-bind="html:answerName"></b>
                                                                    </div>

                                                                <!-- /ko -->


                                                                <!-- ko if: ($parent.type()==1) -->
                                                                    <input type="text" name="answer[]"  />
                                                                <!-- /ko -->


                                                        </div>

                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                        </div>
                </div>
            </div>
            
            <br />
            
            <div class="progress">
                        <div class="progress-bar" role="progressbar" data-bind="attr:{'aria-valuenow':totalAnsweredQuestions}, style: {width:progressBarPercentageComplete}" aria-valuemin="0" aria-valuemax="<?php echo $questionCount ?>">
                          completed (success)
                        </div>
           </div>
                    
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <p class="text-warning text-left" data-bind="visible:showSkipMessage()">You have not answered. This will be marked as incorrect. Click <b>Next Question</b> to continue.</p>
                    <p class="text-warning text-left" data-bind="visible:showConfirmMessage()">Review your answer and click <b>Save</b> if you want to continue</p>
                </div>
            </div>      
                     
            
            
            
            <div class="row" data-bind="visible:hideQuestionnaire()==false && hideTest()==false">
                <div class="col-sm-4"></div>
                <div class="col-sm-2"> 
                    <button  class="cslidex-next"  data-bind="click:confirmSubmission, visible:showSubmitButton()==true">Submit</button>  
                </div>
                <div class="col-sm-2"> 
                    <button class="cslidex-next" data-bind="click:nextQuestion, css:{'cslide-disabled':showConfirmMessage()===false && showSkipMessage()===false}, text:showSkipMessage()==true ? 'Next Question?': 'Save?' ">Save</button>
                </div>
                <div class="col-sm-2"> 
                    <span class="cslidex-next" data-bind="click:confirmSkipNext,  visible:showSubmitButton()==false">Skip</span>
                </div>     
            </div>
            
            
            <div class="row" data-bind="visible:isTestOustanding()">
                <h3>You've reached the end of the test.</h3>
                <h4>However, you have skipped questions that need to be answered. See below</h4>
            </div>
            
            <div class="row" data-bind="visible:skippedQuestions().length>0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td><small><em>You can always answer the skipped questions here</em></small></td>
                                </tr>
                                <tr class="active">
                                    <td colspan="2"><h4<small>Skipped Questions</small></h4></td>
                                </tr>
                            </thead>
                            <tbody class="skipped-questions" data-bind="foreach:skippedQuestions">
                                <tr class="warning">
                                    <td>
                                        <span><small>Question #</small><small data-bind="text:questionNum"></small></span>
                                        <small><a href="#" data-bind="click:$root.viewSkippedQuestions">Click to go back</a></small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
            </div>
            
            <div class="row" data-bind="visible:isTestFinished()">
                <h3>Thank you for completing the test.</h3>
                <h4>we'll generate your score and send an email shortly.</h4>
            </div>
        </div>
    </div>
</div>





<script type="text/javascript">

jQuery(document).ready(function(){
   
    jQuery("#test-display > .carousel-inner > .item:first").addClass("active");
        
    
    jQuery(".carousel .item.active").find(".avPlayerBlock")
        .each(function(element){
            var id = jQuery(this).attr("id");
            var playerId = "avID_"+id;
            jwplayer(playerId).seek(0);
            jwplayer(playerId).stop();
 
    });
    
    var carousel=jQuery("#test-display").carousel({
         pause: true,
        interval: false
    });
    
    carousel.on('slid.bs.carousel', function() {
      
       try
        {
            if(jwplayer().seek)
            {
                var currentPlayer = jQuery(".carousel-inner > .item.active .avPlayerBlock");
                var id = currentPlayer.attr("id");
                var playerId = "avID_"+id;
                jwplayer(playerId).seek(0);
                jwplayer(playerId).stop();
            }
        }   
        catch (e)
        {

        }
    });
    
    
    carousel.on('slide', function() {
       try
        {
            if(jwplayer().seek)
            {
                var currentPlayer = jQuery(".carousel-inner > .item.active .avPlayerBlock");
                var id = currentPlayer.attr("id");
                var playerId = "avID_"+id;
                jwplayer(playerId).seek(0);
                jwplayer(playerId).stop();
            }
        }   
        catch (e)
        {

        }
    });
       
   
});


function validateForm() {
    
    var x = document.forms["myForm"]["answer[]"].value
    // document.getElementById("confirm").className = "";
   alert("The variable named x has value:  " + x);
    //  document.getElementById("next").className = "";
    if (x == null || x == "") {
        document.getElementById("confirm").className = "btn  cslide-submit";
    }else {
        document.getElementById("next").className = "btn  cslide-next";
    }
      return false;
}



// This is a simple *viewmodel* - JavaScript that defines the data and behavior of your UI
function answer(data){
    
    this.answerId = ko.observable(data.id);
    this.questionId = ko.observable(data.questionid);
    this.answerName = ko.observable(data.name);
}



function singleAnswerQuestion(data, index) {
  
  var self = this;
  self.type = ko.observable(data.questionType);
 
  self.goalIndex = ko.observable();
  self.typeOfAnswer  = 'single';
  self.index = ko.observable(index);
  self.questionNum = ko.observable(data.index);
  self.questionId = ko.observable(data.questionId);
  self.questionName = ko.observable(data.questionName);
  
  self.answers = ko.observableArray(ko.utils.arrayMap(data.answers, function(obj) {
    var ans = new answer(obj);
    ans.questionId(self.questionId());
    
    return ans;
  }));
  
  self.answer = ko.observable();
  
  
  self.isAnswered = ko.computed(function() {
    return null != self.answer();
  });
  
};

function multipleAnswersQuestion(data, index) {
  
  
  var   self = this;
  
  this.type = ko.observable(data.questionType);
   this.goalIndex = ko.observable();
 
  
  this.typeOfAnswer = 'multiple';
  
  this.index = ko.observable(index);
  this.questionNum = ko.observable(data.index);
  this.questionId = ko.observable(data.questionId);
  this.questionName = ko.observable(data.questionName);
  
  
  this.answers = ko.observableArray(ko.utils.arrayMap(data.answers, function(obj) {
      
       var ans = new answer(obj);
       ans.questionId(self.questionId());
        return ans;
      
  }));
  
  this.answer = ko.observableArray();
  
  
  this.isAnswered = ko.computed(function() {
      
    return 0 < self.answer().length;
  });
  
};


function AppViewModel(list) {
    var self = this;
    
    self.questions = ko.observableArray(ko.utils.arrayMap(list, function(obj, index) 
        {
            return obj.typeOfAnswer == '1' ? new singleAnswerQuestion(obj, index) : new multipleAnswersQuestion(obj, index);
        }
    ));
    

    self.activeIndex = ko.observable(0);
    self.showSkipMessage = ko.observable(false);
    self.showConfirmMessage = ko.observable(false);
    self.hideQuestionnaire = ko.observable(false);
    self.skippedQuestions = ko.observableArray();
    self.showConfirmButton = ko.observable(false);
    self.showSkipButton = ko.observable(false);
    self.savedQuestions = ko.observableArray();
    self.totalQuestions = ko.observable(<?php echo $questionCount ?>);
    self.totalAnsweredQuestions=ko.observable(0);
    self.currentQuestionIndex = ko.observable(null);
    

    self.answeredQuestions = ko.computed(function() 
    {
        var result = ko.utils.arrayFilter(self.questions(), function(question){
        
            if(question.answer())
            {
                return true;
            }
            else
            {
                return false;
            }
        }); 
        
        self.totalAnsweredQuestions(result.length);
        
        return result;
    });
    
    
     
    self.showSubmitButton = ko.computed(
    {
        read: function() 
        {
            var question = self.questions()[self.activeIndex()];
            if(question)
            {
                return question.isAnswered();
            }
            else
                return false;
            
        },
        deferEvaluation: true
  });
     
     
    
    //Objects for viewing skipped questions
    self.currentQuestionIndex = ko.observable();
    self.currentGoalIndex = ko.observable();
    self.viewingSkippedQuestions = ko.observable(false);
    
    
    
    self.confirmSkipNext = function()
    {
        self.showConfirmMessage(false);
        self.showSkipMessage(true);
    };
    
    self.confirmSubmission = function()
    {
        self.showSkipMessage(false);
        self.showConfirmMessage(true);
    };
    
    self.saveResponse = function()
    { 
        var responseURL = "<?php echo JRoute::_("index.php?option=com_teachpro&task=response.save&format=json&subjectstudentid=".$this->subjectstudentid, false); ?>";
        var reportURL = "<?php echo JRoute::_("index.php?option=com_teachpro&task=report.sendreport&studentsubjectid=".$this->subjectstudentid) ?>";
        
       var question = self.questions()[self.activeIndex()];
              
        if(question.isAnswered())
        {
            jQuery.ajax({

                url:responseURL,
                contentType:"application/json",
                method:"POST",
                data:ko.toJSON(question.answer),
                success:function()
                {
                    self.savedQuestions.push(question);
                    if(self.totalAnsweredQuestions()>=self.totalQuestions())
                    {
                        setTimeout(function(){ 
                            window.location=reportURL;
                        }, 5000);
                    }
                }

            });
        }
    };
    
    self.isTestFinished = ko.computed(function()
    {
        var result = false;
        if(self.savedQuestions().length>=self.totalQuestions())
        {
            result = true;
        }
        return result;
    });
    
    self.isTestOustanding =ko.computed(function(){
        
        var result = false;
        var remainder = self.totalQuestions()-self.savedQuestions().length;
        
        if(remainder==self.skippedQuestions().length && remainder!=0)
        {
            result = true;
        }
        return result;
    });
    
    self.hideTest = ko.computed(function(){
        
         var result = true;
        if((self.savedQuestions().length+self.skippedQuestions().length)<self.totalQuestions())
        {
            result = false;
        }
        return result;
    });
    
    self.alphabet = function (num)
    {
        var result = "";
        switch (num())
        {
            
            case 0:
                result = "A. ";
                break;
            case 1:
                result = "B. ";
                break;
            case 2:
                result ="C. ";
                break;
            case 3:
                result = "D. ";
                break;
            case 4:
                result ="E. ";
                break;
            case 5:
                result ="F. ";
                break;
            case 6:
                result ="G. ";
                break;
            case 7:
                result ="H. ";
                break;
            default:
                result="";
                break;
        }
        return result;
            
    }
    
    self.nextQuestion = function ()
    {
        
        self.saveResponse();
        
        //Skipped questions
        if (!self.showConfirmMessage() && self.showSkipMessage()==true)
        {
            var question = self.questions()[self.activeIndex()];
           
            self.skippedQuestions.push(question);  
        }

        //If either skipped or confirmed
        if (self.showSkipMessage()===true || self.showConfirmMessage()===true)
        {
            //Next
            if(!self.viewingSkippedQuestions())
            {
                self.activeIndex(self.activeIndex()+1);
            }
            else
            {
                self.activeIndex(self.currentQuestionIndex());
                self.currentQuestionIndex(null);
                //YOU'RE LOOKING AT A SKIPPED QUESTION
                self.showSkipMessage(false);
                self.showConfirmMessage(false);
                self.viewingSkippedQuestions(false);
                 
            }
            self.showSkipMessage(false);
            self.showConfirmMessage(false);
        }
        jQuery("#test-display").carousel(self.activeIndex());
        
        
        $(".page-header .row").ScrollTo({
            
            duration: 300,
            easing: 'linear'
            
        });
        
        
    };
    
    self.iniitJWPlayer = function()
    {
        try
        {
            if(jwplayer().seek)
            {
                var currentPlayer = jQuery(".carousel-inner > .item.active .avPlayerBlock");
                var id = currentPlayer.attr("id");
                var playerId = "avID_"+id;
                jwplayer(playerId).seek(0);
                jwplayer(playerId).stop();
            }
        }   
        catch (e)
        {

        }
    };
    self.viewSkippedQuestions =function()
    {
        
        
        self.currentQuestionIndex(self.activeIndex());
        
        self.activeIndex(this.index());
        jQuery("#test-display").carousel(self.activeIndex());
  
        self.skippedQuestions.remove(this);
        
        self.showConfirmMessage(false);
        self.showSkipMessage(false);
        self.viewingSkippedQuestions(true);
      //  self.hideQuestionnaire(false);
        
    };
     

    
    self.getNonNullAnswers = function(answers){
        
          var filtered = self.questions()[self.activeIndex()].answers().filter(function (value){
            
            console.log(value);
            
            if (value() == null || value()==undefined)
            {
                return false;
            }
            else
            {
                
                return true;
            }
        });
        return filtered;
    };
    

    
    
    self.progressBarPercentageComplete = ko.computed(function ()
    {
        
        filtered=self.totalAnsweredQuestions();
        var percentage = 0;
        if(filtered==0)
            return 0;
        percentage=(filtered/self.totalQuestions())*100;
        
        console.log(percentage);
        
        return percentage+"%";
    });
    
};

var helper = document.createElement('div');

ko.bindingHandlers.stripHtml = {
  update: function(elem, valueAccessor) {
  	var html = ko.unwrap(valueAccessor());
    var result = html.split("jwplayer");
   
    helper.innerHTML = result[0];
    elem.innerText = (helper.innerText+"").trim();
  }
};

// Activates knockout.js
ko.applyBindings(new AppViewModel( <?php echo $questions; ?>));


</script>

