jQuery(window).load(function() {
    jQuery(function($) {
        //upload at checkout & admin meta is same
        $('body').on('change', '#_proof', function() {
            var file_data = $("#_proof").prop('files')[0];
            var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('action', 'proof');
                form_data.append('transaction', 'upload');
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
                    $('#loading').css('display','block');
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
                    $('#loading').css('display','none');
                },
                complete: function() {
                    fadz--;
                    if (fadz <= 0) {
                        $('#loading').css('display','none');
                    }
                }

            });
        });

        //delete at checkout
        $('body').on('click', '#delete_proof', function() {
            console.log('delete at checkout');
                // Selecting image source
                var proof_src = $("#proof_src").attr('href');
                var form_data_proof = new FormData();
                    form_data_proof.append('action', 'proof');
                    form_data_proof.append('proof_src', proof_src);
                    form_data_proof.append('transaction', 'delete');
                // AJAX request
                jQuery.ajax({
                    url: ajax_object.ajaxurl,
                    type: 'POST',
                    cache: true,
                    contentType: false,
                    processData: false,
                    data: form_data_proof,
                    success: function(data){
                        $("#proof_area").remove();
                        $("#_proof").val('');
                  }
                });
        });

        //delete at woocommerce meta box - carry order id
         $('body').on('click', '#delete_proof_admin', function() {
                console.log('delete at admin');
                // Selecting image source
                var proof_src = $("#proof_src").val();
                var form_data_proof = new FormData();
                    form_data_proof.append('action', 'proof');
                    form_data_proof.append('proof_src', proof_src);
                    form_data_proof.append('transaction', 'delete');
                    form_data_proof.append('order_id', $("#order_id").val());
                // AJAX request
                jQuery.ajax({
                    url: ajax_object.ajaxurl,
                    type: 'POST',
                    cache: true,
                    contentType: false,
                    processData: false,
                    data: form_data_proof,
                    success: function(data){
                        location.reload();
                  }
                });
        });

    });

});