		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-lock"></i>
					    <span data-i18n="security.activation_lock"></span>
					    <list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="activation_lock_enabled" class="btn btn-danger hide">
						<span class="activation_lock-count bigger-150"></span><br>
						<span class="activation_lock-label"></span>
						<span data-i18n="enabled"></span>
					</a>
					<a id="activation_lock_disabled" class="btn btn-success hide">
						<span class="activation_lock-count bigger-150"></span><br>
						<span class="activation_lock-label"></span>
						<span data-i18n="disabled"></span>
					</a>

          <span id="activation_lock-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_activation_lock_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#activation_lock_enabled').attr('href', url + "#activation_lock_enabled")
	$('#activation_lock_disabled').attr('href', url + "#activation_lock_disabled")

        // Show no clients span
        $('#activation_lock-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#activation_lock-' + prop).removeClass('hide');
                $('#activation_lock-' + prop + '>span.activation_lock-count').text(val);

                // Hide no clients span
                $('#activation_lock-nodata').addClass('hide');
            }
            else
            {
                $('#activation_lock-' + prop).addClass('hide');
            }
        });
    });
});


</script>
