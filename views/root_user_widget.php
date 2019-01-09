		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa user-circle"></i>
					    <span data-i18n="security.root_user_status"></span>
					    <list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="rootuser-disabled" class="btn btn-danger hide">
						<span class="rootuser-count bigger-150"></span><br>
						<span class="rootuser-label"></span>
						<span data-i18n="enabled"></span>
					</a>
					<a id="rootuser-enabled" class="btn btn-success hide">
						<span class="rootuser-count bigger-150"></span><br>
						<span class="rootuser-label"></span>
						<span data-i18n="disabled"></span>
					</a>

          <span id="rootuser-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_root_user_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#rootuser-disabled').attr('href', url)
	$('#rootuser-enabled').attr('href', url)

        // Show no clients span
        $('#rootuser-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#rootuser-' + prop).removeClass('hide');
                $('#rootuser-' + prop + '>span.rootuser-count').text(val);

                // Hide no clients span
                $('#rootuser-nodata').addClass('hide');
            }
            else
            {
                $('#rootuser-' + prop).addClass('hide');
            }
        });
    });
});


</script>
