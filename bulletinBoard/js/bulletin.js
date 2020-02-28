$(document).ready(function()
{

    // Switch between textarea and file uploading.
    $('input').change(function()
    {
        // File mode.
        if(this.value == "bulletin_field_bulletinType_2")
        {
            $('#bulletin_field_fileUrl_container').show();
            $('#bulletin_field_addFile').show();
            $('#bulletin_field_content').hide();
        }
        // Link and text mode.
        else if(this.value.includes("bulletin_field_bulletinType"))
        {
            $('#bulletin_field_fileUrl_container').hide();
            $('#bulletin_field_addFile').hide();
            $('#bulletin_field_content').show();
        }

        // Show popup message.
        if(this.value.includes("bulletin_field_bulletinType_3"))
            alert('請注意：輸入網址時，若後方有其他文字，請勿必使用空白做間隔。');
    });

    // Add a new file upload field.
    $('#bulletin_field_addFile').on('click', function()
    {
        $('#bulletin_field_fileUrl_container').append('<input style="margin:5px 0px; width:100%;" name="bulletin_field_fileUrl[]" type="file" value="" />');
    });

});

