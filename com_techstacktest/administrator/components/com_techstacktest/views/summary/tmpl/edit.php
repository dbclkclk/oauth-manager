<?php
defined('_JEXEC') or die;
?>
<form name="adminForm" id="summary-form">
    <div class="container">
        <div class="row-fluid">
            <div class="span12 form-horizontal">
                <fieldset class="adminform">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('summ_desc'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('summ_desc'); ?></div>
                    </div>
                    
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('summ_text'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('summ_text'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-success" id="save-btn"> Save </button>
                            <img class="loading" style="display:none"
                                 src="<?php echo  JURI::root().'media/com_teachpro/image/ajax-small-loader.gif' ?>"/>
                        </div>
                    </div>
                </fieldset>
        </div>  
    </div>
 </div>
</form>
<script type="text/javascript">
    
   
    jQuery(document.body).on('click', '#save-btn' ,function(e) {
        e.preventDefault();
        if(jQuery('#jform_summ_desc').val()== '')
        {
            alert('Please Enter The Summary Description');
            return false;
        }
        
        else if(jQuery('#jform_summ_text').val()== '')
        {
            alert('Please Enter The Summary Text');
            return false;
        }
        jQuery('.loading').show();
        summ_desc = jQuery('#jform_summ_desc').val();
        summ_text = jQuery('#jform_summ_text').val();
      
        jQuery.ajax({method: "POST",
            url: "index.php?option=com_teachpro&task=summary.save",
            data:{ summ_desc: summ_desc, summ_text: summ_text }
        }).done(function( msg ) {
            jQuery('.loading').show();
            window.location.reload();
        });
    });
</script>
<?php die();
