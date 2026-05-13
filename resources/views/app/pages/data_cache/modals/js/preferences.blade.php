<script type="text/javascript">
    $( document ).ready(function() {
        $("#tabs").tabs();

        $('#submit_pref').click(function(event){
            event.preventDefault();

             $('.alert-preferences-success').css('display', 'none');
             $('.alert-preferences-error').css('display', 'none');

            if ($("#pref_company_name").val() == '' || $("#pref_company_name").val() == null) {
                alert('Company Name must be filled out.')
                $("#pref_company_name").focus()
            } else if ($("#pref_first_name").val() == '' || $("#pref_first_name").val() == null) {
                alert('First Name must be filled out.')
                $("#pref_first_name").focus()
            } else if ($("#pref_last_name").val() == '' || $("#pref_last_name").val() == null) {
                alert('Last Name must be filled out.');
                $("#pref_last_name").focus();
            } else if ($("#pref_phone_no").val() == '' || $("#pref_phone_no").val() == null) {
                alert('Phone # must be filled out.');
                $("#pref_phone_no").focus();
            } else if ($("#pref_fax_no").val() == '' || $("#pref_fax_no").val() == null) {
                alert('Fax # must be filled out.');
                $("#pref_fax_no").focus();
            } else if ($("#pref_alternate_email").val() == '' || $("#pref_alternate_email").val() == null) {
                alert('Email must be filled out.');
                $("#pref_alternate_email").focus();
            } else if ($("#pref_billing_title").val() == '' || $("#pref_billing_title").val() == null) {
                alert('Billing Title must be filled out.');
                $("#pref_billing_title").focus();
            } else if ($("#pref_address").val() == '' || $("#pref_address").val() == null) {
                alert('Address must be filled out.');
                $("#pref_address").focus();
            } else {
                var data_info_up = {
                    "id": $('#pref_user_id').val(),
                    "name": $('#pref_user_name').val(),
                    "email": $('#pref_user_email').val(),
                    "company_name": $('#pref_company_name').val(),
                    "first_name": $('#pref_first_name').val(),
                    "last_name": $('#pref_last_name').val(),
                    "phone_no": $('#pref_phone_no').val(),
                    "fax_no": $('#pref_fax_no').val(),
                    "alternate_email": $('#pref_alternate_email').val(),
                    "address": $('#pref_address').val(),
                    "billing_title": $('#pref_billing_title').val(),
                };
                
                $.ajax({
                    url : "{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/users/me",
                    type: "PUT",
                    dataType: "JSON",
                    data: data_info_up,		  
                    success: function(msg){
                        $('.alert-preferences-success').show();
                    },
                    error: function (xhr, ajaxOptions, error) {
                        $('.alert-preferences-error').show();
                    }
                }); 
            }
        });
    
        $('.logo-box:not(.active)').click(function(event){
            event.preventDefault();

            $userId = $(this).data('user-id');
            $logo = $(this).data('logo');

            var data = {
                logo: $logo
            }

            var confirmDialog = confirm("Are you sure you want to choose this logo?");
            if (confirmDialog == true) {
                $.ajax({
                    url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/users/'+$userId,
                    type: 'PUT',
                    data: data,
                    dataType: 'JSON',
                    success: function (data) { 
                        location.reload();
                    }
                }); 
            }
        });

        $('.logo-delete').click(function(event){
            event.preventDefault();

            $logoId = $(this).data('logo-id');

            var confirmDialog = confirm("Are you sure you want to delete this logo?");
                if (confirmDialog == true) {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/logos/'+$logoId,
                        type: 'DELETE',
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
        });

        $('#logoSubmit').click(function(event){
            if ($("#logo_file").val() == '' || $("#logo_file").val() == null) {
                alert('Choose a file for the logo.');
                $("#logo_file").focus();
            } else {
                $('#form-part').hide();
                $('#loader-part').show();
            }
        });

    });
</script>