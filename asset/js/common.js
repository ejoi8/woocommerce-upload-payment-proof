jQuery(window).load(function() {


    jQuery(function($) {
        $('body').on('change', '#_proof', function() {
            var file_data = $("#_proof").prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('action', 'proof');
            var fadz = 0;
            jQuery.ajax({
                url: ajax_object.ajaxurl,
                type: 'POST',
                cache: true,
                contentType: false,
                processData: false,
                data: form_data,
                beforeSend: function() {
                    // setting a timeout
                    $('#_proof').addClass('loading');
                    fadz++;
                },
                success: function(response) {
                    if ($("#_proof").val() != '') {
                        $("#proof_field").html(response);
                        console.log(response);
                    }
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                    $('#_proof').append(xhr.statusText + xhr.responseText);
                    $('#_proof').removeClass('loading');
                },
                complete: function() {
                    fadz--;
                    if (fadz <= 0) {
                        $('#_proof').removeClass('loading');
                    }
                }

            });



        });





    });

});