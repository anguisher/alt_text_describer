jQuery(document).ready(function($) {
    function generateAltForImages(forAllImages = true) {
        var totalImagesProcessed = 0;
        var languageAttribute = $("#select_describer_language option:selected").attr("attr_lng");
        var progressContent = 'Processing images...<br><br>';
        progressContent += "Don't close this browser tab, leave it running in background, while it runs, you can navigate through other tabs.";
        $('#progress-body').html(progressContent);
        $('#progressModal').show();
        function processImages() {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: forAllImages ? 'generate_alt_for_all_images' : 'generate_alt_for_images',
                    language: languageAttribute
                },
                success: function(response) {
                    var status = response.status;
                    var type = response.type;
                    if(status == 'error' && type != "description_error"){
                        $('#progress-body').html(response.content);
                        return;
                    }
                    totalImagesProcessed += response.processed;
                    var progressContent = 'Total images processed: ' + totalImagesProcessed + '<br><br><br>';
                    progressContent += 'Images left to process: ' + (response.total - 1) + '<br><br>';
                    progressContent += "Don't close this browser tab, leave it running in background, while it runs, you can navigate through other tabs.<br><br>";
                    $('#progress-body').html(progressContent);
                    if (parseInt(response.total) > 0) {
                        processImages();
                    } else {
                        $('#progressModal').hide();
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        processImages();
        $('#cancelButton').click(function() {
            location.reload();
        });
    }
    $("#button_generate_images_alt").click(function(){
        generateAltForImages(false);
    });
    $("#button_generate_all_images_alt").click(function(){
        generateAltForImages(true);
    });
    
});