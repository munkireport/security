<div class="col-lg-4">
    <h4><i class="fa fa-key fa-fixed"></i> <span data-i18n="security.security"></span></h4>
    <table id="security-data" class="table"></table>
</div>

<script>
$(document).on('appReady', function(){
	// Get security data
	$.getJSON( appUrl + '/module/security/get_data/' + serialNumber, function( data ) {
		$.each(data, function(index, item){
            $('#security-data')
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.gatekeeper')))
                    .append($('<td>')
                        .text(item.gatekeeper)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.sip')))
                    .append($('<td>')
                        .text(item.sip)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.ssh_groups')))
                    .append($('<td>')
                        .text(item.ssh_groups)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.ssh_users')))
                    .append($('<td>')
                        .text(item.ssh_users)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.ard_users')))
                    .append($('<td>')
                        .text(item.ard_users)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.ard_groups')))
                    .append($('<td>')
                        .text(item.ard_groups)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.firmwarepw')))
                    .append($('<td>')
                        .text(item.firmwarepw)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.firewall_state')))
                    .append($('<td>')
                        .text(item.firewall_state)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.skel.kext-loading')))
                    .append($('<td>')
                        .text(item.skel_state)))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.t2_secureboot')))
                    .append($('<td>')
                        .text(function(){
                            if(item.t2_secureboot == 'SECUREBOOT_OFF'){
                                return i18n.t('security.off');
                            }
                            if(item.t2_secureboot == 'SECUREBOOT_MEDIUM'){
                            return i18n.t('security.medium');
                            }
                            if(item.t2_secureboot == 'SECUREBOOT_FULL'){
                            return i18n.t('security.full');
                            }
                        })))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('security.t2_externalboot')))
                    .append($('<td>')
                        .text(function(){
                            if(item.t2_externalboot == 'EXTERNALBOOT_OFF'){
                                return i18n.t('security.off');
                            }
                            if(item.t2_externalboot == 'EXTERNALBOOT_ON'){
                                return i18n.t('security.on');
                            }
                        })))
		});
    });
});
</script>

