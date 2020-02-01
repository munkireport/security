		<div class="col-lg-4 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">

					<h3 class="panel-title"><i class="fa fa-shield"></i>
					    <span data-i18n="security.t2_secureboot"></span>
					    <list-link data-url="/show/listing/security/security"></list-link>
					</h3>

				</div>

				<div class="panel-body text-center">


					<a id="t2_secureboot-securebootoff" class="btn btn-warning hide">
						<span class="t2_secureboot-count bigger-150"></span><br>
						<span class="t2_secureboot-label"></span>
						<span data-i18n="security.off"></span>
					</a>
					<a id="t2_secureboot-securebootmedium" class="btn btn-info hide">
						<span class="t2_secureboot-count bigger-150"></span><br>
						<span class="t2_secureboot-label"></span>
						<span data-i18n="security.medium"></span>
					</a>
					<a id="t2_secureboot-securebootfull" class="btn btn-success hide">
						<span class="t2_secureboot-count bigger-150"></span><br>
						<span class="t2_secureboot-label"></span>
						<span data-i18n="security.full"></span>
					</a>

          <span id="t2_secureboot-nodata" data-i18n="no_clients"></span>

				</div>

			</div><!-- /panel -->

		</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/security/get_secureboot_stats', function( data ) {
	    
	if(data.error){
    		//alert(data.error);
    		return;
    	}

	// Set URLs. TODO - once filtered update this to deep link
	var url = appUrl + '/show/listing/security/security'
	$('#t2_secureboot-securebootoff').attr('href', url)
	$('#t2_secureboot-securebootmedium').attr('href', url)
	$('#t2_secureboot-securebootfull').attr('href', url)

        // Show no clients span
        $('#t2_secureboot-nodata').removeClass('hide');

        $.each(data.stats, function(prop, val){
            if(val >= 0)
            {
                $('#t2_secureboot-' + prop).removeClass('hide');
                $('#t2_secureboot-' + prop + '>span.t2_secureboot-count').text(val);

                // Hide no clients span
                $('#t2_secureboot-nodata').addClass('hide');
            }
            else
            {
                $('#t2_secureboot-' + prop).addClass('hide');
            }
        });
    });
});


</script>
