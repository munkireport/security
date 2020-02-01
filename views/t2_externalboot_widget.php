		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-external-link-square"></i>
					    <span data-i18n="security.t2_externalboot"></span>
					    <list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="t2_externalboot-externalbooton" class="btn btn-warning hide">
						<span class="t2_externalboot-count bigger-150"></span><br>
						<span class="t2_externalboot-label"></span>
						<span data-i18n="security.off"></span>
					</a>
					<a id="t2_externalboot-externalbootoff" class="btn btn-success hide">
						<span class="t2_externalboot-count bigger-150"></span><br>
						<span class="t2_externalboot-label"></span>
						<span data-i18n="security.on"></span>
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
	$('#t2_externalboot-externalbooton').attr('href', url)
	$('#t2_externalboot-externalbootoff').attr('href', url)

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
