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
    
    JHtml::_('jquery.framework', false);
    
    $document = JFactory::getDocument();
    
    $document->addScript(JUri::root() . 'media/com_teachpro/js/knockout-3.4.0.js');
    
    $document->addStyleSheet(JUri::root()."media/com_teachpro/css/style.css");
    
    $param = new stdClass();
    
    $param->children = array_values($this->childList);
    $param->grades = array_values($this->gradeList);
    $param->subjects = array_values($this->subjectList);
    $param=json_encode($param);
    
    $records = array(); //Wjson_encode($this->items);
            
    foreach($this->items as $item)
    {
        $student =$this->getStudentInfo($item->student_id);
        
        $subjectgoal = $this->getSubjectGradeInfo($item->subject_id);
        
        array_push($records, array("id"=>$item->id,"student"=>$student, "subject"=>$subjectgoal["subject"], "grade"=>$subjectgoal["grade"]));
        
    }
    
     $termsofuse = Juri::base()."media/com_teachpro/assets/pdfs/legal/termsofuse.pdf";
    
    $records = array_values($records);
    
    $records=json_encode($records);
    
    $test_cost = JComponentHelper::getParams('com_teachpro')->get('test_cost');
    
    if ($test_cost==null)
        throw new Error("Cost configuration options");

?>
<h2>Step 2: Assign child to test</h2>
<h3>Go through the process below to get your child registered. Click each steps to proceed...</h3>
<?php echo JLayoutHelper::render('default_header', array('view' => $this), dirname(__FILE__)); ?>
<br />
<br />
<div class="container">  
    <form id="payment-form" class="form-inline"  action="<?php echo JRoute::_('?option=com_teachpro&task=enrollment.payForTest') . "&" . JSession::getFormToken() . '=1'; ?>" method="post">

        
        <div class="row">
        
            <div class="col-sm-7 col-md-7 col-lg-7">
                <div class="row">
                    <h4>Assign Child for Testing </h4>
                    <div class="dropdown-container">
                        <div class="selection-row-wrapper">

                            <select class="student" data-bind="options:children,optionsText:'fullname', value:selection().child,optionsCaption:'Select child'">
                            </select>

                            <select class="grade" data-bind="options:grades,optionsText:'grade', value:selection().grade, optionsCaption:'Select grade'">

                            </select>

                            <select class="subject" data-bind="options:subjects,optionsText:'subject_name', value:selection().subject, optionsCaption:'Select Subject'">

                            </select>

                            <span class="form-action-wrapper" data-bind="visible: isSelected"> 
                                <span class="btn btn-success save" data-id="btn0" data-bind="click:saveData">Click to Proceed</span>
                            </span>
                            <span id="unique" style="display: none"><?php  ?></span>
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                        <img class="loading" alt="Loading..." style="display:none" src="<?php echo JURI::base().'media/com_teachpro/image/ajax-loader.gif'; ?>">
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="row">
            <div data-bind="showMessage:responseCode"  class="warning-message warning alert alert-warning" style="display: none; margin-top: 20px;">
                <a href="#" class="close"  data-dismiss="alert">&times;</a>
                <strong>Warning!</strong> Duplicate Data, Test is already assigned
            </div>
        </div>
        <div class="row">
            
            <table class="table table-striped table-bordered">
                
                <thead>
                    <tr>
                        <td></td>
                        <th width="25%">Student</th>
                        <th width="15%">Grade</th>
                        <th width="50%">Subject</th>
                        <th width="10%"> Cost </th>
                    </tr>
                </thead>
                <tbody data-bind="foreach:selectedRecords">
                    <tr>
                        <td><a href="#" data-bind="click:$root.removeModule"><span class="glyphicon glyphicon-remove"></span></a></td>
                        <td data-bind="text:child().fullname"></td>
                        <td data-bind="text:grade().grade"></td>
                        <td data-bind="text:subject().subject_name"></td>
                        <td data-bind="text:$parent.test_fees"></td>
                    </tr>
                </tbody>
                
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="row" data-bind="visible: showShoppingCart">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <h5>Number of Test(s): <span data-bind="text:shoppingCart().totalSubjects"></span></h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <h5>Total Cost: <span data-bind="text:shoppingCart().totalCost"></span></h5>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <label> <input type="checkbox" data-bind="checked:termsofuse" /> Terms of use <a target="_blank" href="<?php echo $termsofuse ?>"><span class="glyphicon glyphicon-download-alt"></span><a/></label>
                                    <button type="submit" name="submit" value="Pay" class="btn btn-success" data-bind="css:{disabled:termsofuse()==false}">Pay</button>
                                </div>

                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <input type="hidden" name="custom" value="<?php ?>">
        <input type="hidden" name="return" value="<?php echo JUri::base(); ?>index.php?option=com_teachpro&view=testss">
        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="cbt" value="Return to The Store">
        <input type="hidden" name="cancel_return" value="<?php echo JUri::base(); ?>index.php?option=com_teachpro&view=assigntests">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="currency_code" value="USD">
	
</form>
</div>
	
<script type="text/javascript">
 



function SelectedModuleModel ()
{
    var self = this;
    self.id = ko.observable();
    self.child = ko.observable();
    self.grade = ko.observable();
    self.subject = ko.observable();
}

function ChildViewModel(data)
{
    var self = this;
    self.id = ko.observable(data.id);
    self.fullname = ko.observable(data.firstname+" "+data.lastname);
}

function GradeViewModel(data)
{
    var self = this;
    self.id = ko.observable(data.grade);
    self.grade = ko.observable(data.grade);
}


function SubjectViewModel(data)
{
    var self = this;
    self.id = ko.observable(data.id);
    self.subject_name = ko.observable(data.subject_name);
}


function ShoppingCartViewModel ()
{
    var self = this;
    self.totalSubjects = ko.observable();
    
    self.totalCost = ko.observable();
    
}


function PaymentViewModel (data, records)
{
    
    var self = this;
    
    self.test_fees = ko.computed(function(){
       
       return "$"+"<?php echo $test_cost ?>";
        
    });
    
    self.responseCode = ko.observable(0);
    
    self.termsofuse = ko.observable(false);
    
    self.shoppingCart = ko.observable(new ShoppingCartViewModel());
    self.selection = ko.observable(new SelectedModuleModel());
    
     self.showShoppingCart = ko.computed(function(){
        
        var result = false;
        if((self.shoppingCart().totalSubjects()!=null && self.shoppingCart().totalSubjects()!=0) && (self.shoppingCart().totalSubjects()!=undefined && self.shoppingCart().totalSubjects()!=0))
            result = true;
        
        console.log(self.shoppingCart().totalSubjects());
        return result;
        
    });
    
    self.selectedRecords = ko.observableArray();
            
     self.selectedRecords(ko.utils.arrayMap(records, function(obj) {
        
        
        var child = new ChildViewModel(obj.student);
        var subject = new SubjectViewModel (obj.subject);
        var grade = new GradeViewModel(obj.grade);
        
        var record = new SelectedModuleModel();
        
        record.id(obj.id);
        record.child(child);
        record.subject(subject);
        record.grade(grade);
        
        return record;
        
    }));
    
    
    self.shoppingCart().totalSubjects(self.selectedRecords().length);
                
    self.shoppingCart().totalCost("$"+(self.selectedRecords().length * <?php echo $test_cost ?>));
    
    
    self.children = ko.observableArray(ko.utils.arrayMap(data.children, function(obj) {
            return new ChildViewModel(obj);
    }));
    self.grades = ko.observableArray(ko.utils.arrayMap(data.grades, function(obj) {
            return new GradeViewModel(obj);
    }));
    self.subjects = ko.observableArray(ko.utils.arrayMap(data.subjects, function(obj) {
            return new SubjectViewModel(obj);
    }));
    
  
    self.selectedRecords.subscribe(function(){
        

        self.shoppingCart().totalSubjects(self.selectedRecords().length);
                
        self.shoppingCart().totalCost("$"+(self.selectedRecords().length * <?php echo $test_cost ?>));
        
        
    },null,"arrayChange");
    
   
    
    self.saveData = function ()
    {
        jQuery('.loading').show();
        jQuery.ajax({
            url:'index.php?option=com_teachpro&task=assigntests.saveData',
            data: { student: self.selection().child().id(), subject: self.selection().subject().id}
        }).success(function(row_id){
                row_id = jQuery.trim(row_id);
                if(row_id == 0) {
                   self.responseCode(2);
                } else {
                    self.selection().id(row_id);
                    
                    var tempAction = ko.utils.clone(self.selection(), new SelectedModuleModel());
                    
                    
                    self.selectedRecords.push(tempAction );
                    self.responseCode(1);
                }
        }).complete(function(){
             $('.loading').hide();
        });
    };
    
    self.removeModule = function(data)
    {
        console.log(data);
        jQuery('.loading').show();
        jQuery.ajax({
            url:'index.php?option=com_teachpro&task=assigntests.deleteData',
            data: { id: data.id}
        }).success(function(result){
     
           self.selectedRecords.remove(data);
           
        jQuery('.loading').hide();
        }).complete(function() {
            jQuery('.loading').hide();    
        });

        return true;
    };
    
    
    self.listOfSubjects = ko.computed(function(){
       
       if(self.selection().grade()!=null)
       {
            jQuery('.loading').show();
            self.subjects.removeAll();
           jQuery.get("index.php?option=com_teachpro&task=subjectss.filterSubject&gradelevel="+self.selection().grade().id(), 
                function(result, status ) 
                {
                   
                    var options = JSON.parse(result);
                    
                    self.subjects(ko.utils.arrayMap(options, function(obj) {
                            return new SubjectViewModel(obj);
                    }));
                    jQuery('.loading').hide();    
                });
           
       }
        
    });
    
   
    
    
    self.isSelected = ko.computed(function(){
        
        var result = true;
        if(self.selection().grade()==null)
            result = false;
        if (self.selection().child()==null)
            result = false;
        if (self.selection().subject()==null)
            result =false;
        return result;
    });
    
}


ko.bindingHandlers.showMessage = {
    'update': function(element, valueAccessor) {
        var shouldAllowBindings = ko.unwrap(valueAccessor());        
        if(shouldAllowBindings==1)
            $('.success-message').show();
        else
        {
            if(shouldAllowBindings==2)
                $('.warning-message').show();
        }
    }
};



// Activates knockout.js
ko.applyBindings(new PaymentViewModel( <?php echo $param; ?>,  <?php echo $records ?>));


ko.utils.extendObservable = function ( target, source ) {
    var prop, srcVal, tgtProp, srcProp,
        isObservable = false;

    for ( prop in source ) {

        if ( !source.hasOwnProperty( prop ) ) {
            continue;
        }

        if ( ko.isWriteableObservable( source[prop] ) ) {
            isObservable = true;
            srcVal = source[prop]();
        } else if ( typeof ( source[prop] ) !== 'function' ) {
            srcVal = source[prop];
        }

        if ( ko.isWriteableObservable( target[prop] ) ) {
            target[prop]( srcVal );
        } else if ( target[prop] === null || target[prop] === undefined ) {

            target[prop] = isObservable ? ko.observable( srcVal ) : srcVal;

        } else if ( typeof ( target[prop] ) !== 'function' ) {
            target[prop] = srcVal;
        }

        isObservable = false;
    }
    
    return target;
};


ko.utils.clone = function(obj, emptyObj){
    var json = ko.toJSON(obj);
    var js = JSON.parse(json);

    return ko.utils.extendObservable(emptyObj, js);
};

</script>