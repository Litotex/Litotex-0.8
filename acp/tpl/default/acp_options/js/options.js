$(document).ready(function() {
    /**
     * edit link click handler
     */
    $('.optionsEditOption').click(function() {
        // get optionID
        optionID = $(this).attr('optionID');
        
        // replace value field with a form
        optionValue = $(this).parent().prev().prev().get();
        value = $(optionValue).html();
        $(optionValue).html('<input type="text" value="'+value+'" placeholder="'+LN_default_will_be_used+'" />');
        
        // replace edit button with a save button (for the user, we handle it in a nother way)
        $(this).hide();
        $('.optionsSaveOption[optionID='+optionID+']').fadeIn();
        return false;
    });
    
    /**
     * save link click handler
     */
    $('.optionsSaveOption').live('click', function() {
        optionID = $(this).attr('optionID');
        
        // get value
        optionValue = $(this).parent().prev().prev().get();
        value = $(optionValue).find('input').val();
        
        // send value to server
        $.post('?package=acp_options&action=editSubmit&ajax=true', {
            optionID: optionID,
            value: value
        }, function() {
            // done, now remove the textfield and the button
            $(optionValue).html(value);
            $('.optionsSaveOption[optionID='+optionID+']').hide();
            $('.optionsEditOption[optionID='+optionID+']').fadeIn();
        });

        return false;
    });
});