		<div class="col-lg-4 col-md-6">

			<div class="card">

				<div class="card-header">

					<i class="fa fa-external-link-square"></i>
					    <span data-i18n="security.t2_externalboot"></span>
					    <a href="/show/listing/security/security" class="pull-right"><i class="fa fa-list"></i></a>
					

				</div>

				<div class="card-body text-center">


					<a id="t2_externalboot-externalbooton" class="btn btn-danger hide">
						<span class="t2_externalboot-count bigger-150"></span><br>
						<span class="t2_externalboot-label"></span>
						<span data-i18n="security.on"></span>
					</a>
					<a id="t2_externalboot-externalbootoff" class="btn btn-success hide">
						<span class="t2_externalboot-count bigger-150"></span><br>
						<span class="t2_externalboot-label"></span>
						<span data-i18n="security.off"></span>
					</a>

          <span id="t2_externalboot-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_externalboot_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#t2_externalboot-externalbooton').attr('href', url + "#EXTERNALBOOT_ON")
	$('#t2_externalboot-externalbootoff').attr('href', url + "#EXTERNALBOOT_OFF")

        // Show no clients span
        $('#t2_externalboot-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#t2_externalboot-' + prop).removeClass('hide');
                $('#t2_externalboot-' + prop + '>span.t2_externalboot-count').text(val);

                // Hide no clients span
                $('#t2_externalboot-nodata').addClass('hide');
            }
            else
            {
                $('#t2_externalboot-' + prop).addClass('hide');
            }
        });
    });
});


</script>
