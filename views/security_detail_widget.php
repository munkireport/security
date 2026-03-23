<div class="col-lg-4">
    <h4><i class="fa fa-key fa-fixed"></i> <span data-i18n="security.security"></span><a data-toggle="tab" title="Security" class="btn btn-xs pull-right" href="#security-tab" aria-expanded="false"><i class="fa fa-arrow-right"></i></a></h4>
    <table id="security-data" class="table"></table>
</div>

<script>
$(document).on('appReady', function(){
    // Get security data
    $.getJSON( appUrl + '/module/security/get_data/' + serialNumber, function( data ) {
        $.each(data, function(index, item){
            $('#security-data')
                .append($('<tbody>')
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.gatekeeper')))
                        .append($('<td>')
                            .html(function(){
                                if(item.gatekeeper == 'Active'){
                                    return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
                                }
                                return item.gatekeeper == 'Not Supported' ? 
                                    '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
                                    '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.sip')))
                        .append($('<td>')
                            .html(function(){
                                if(item.sip == 'Active'){
                                    return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
                                }
                                return item.sip == 'Not Supported' ? 
                                    '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
                                    '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
                            })))
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
                            .text(i18n.t('security.apple_setup_timestamp')))
                        .append($('<td>')
                            .html(function(){
                                if(item.apple_setup_timestamp > 0){
                                    var date = new Date(parseInt(item.apple_setup_timestamp) * 1000);
                                    return('<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>')
                                }
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.console_session_locked')))
                        .append($('<td>')
                            .html(function(){
                                switch(parseInt(item.console_session_locked)){
                                    case 7:
                                        return '<span class="label label-success">'+i18n.t('security.locked_display_off')+'</span>';
                                    case 6:
                                        return '<span class="label label-warning">'+i18n.t('security.unlocked_display_off')+'</span>';
                                    case 5:
                                        return '<span class="label label-success">'+i18n.t('security.locked_screen_saver')+'</span>';
                                    case 4:
                                        return '<span class="label label-warning">'+i18n.t('security.unlocked_screen_saver')+'</span>';
                                    case 3:
                                        return '<span class="label label-success">'+i18n.t('security.locked_login_window')+'</span>';
                                    case 1:
                                        return '<span class="label label-success">'+i18n.t('yes')+'</span>';
                                    case 0:
                                        return '<span class="label label-info">'+i18n.t('no')+'</span>';
                                    default:
                                        return '';
                                }
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.firmwarepw')))
                        .append($('<td>')
                            .html(function(){
                                if(item.firmwarepw == 'Yes' || item.firmwarepw == 'command'){
                                    return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
                                }
                                return item.firmwarepw == 'Not Supported' ? 
                                    '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
                                    '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.firewall_state')))
                        .append($('<td>')
                            .html(function(){
                                if(item.firewall_state == 1){
                                    return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
                                }
                                if(item.firewall_state == 2){
                                    return '<span class="label label-success">'+i18n.t('security.block_all')+'</span>';
                                }
                                return '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.skel.kext-loading')))
                        .append($('<td>')
                            .html(function(){
                                if(item.skel_state == 1){
                                    return '<span class="label label-info">'+i18n.t('security.skel.all-approved')+'</span>';
                                }
                                return item.skel_state == 0 ? 
                                    '<span class="label label-info">'+i18n.t('security.skel.user-approved')+'</span>' : 
                                    '<span class="label label-default">'+i18n.t('unknown')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.t2_secureboot')))
                        .append($('<td>')
                            .html(function(){
                                switch(item.t2_secureboot){
                                    case 'SECUREBOOT_FULL':
                                        return '<span class="label label-success">'+i18n.t('security.full')+'</span>';
                                    case 'SECUREBOOT_MEDIUM':
                                        return '<span class="label label-warning">'+i18n.t('security.medium')+'</span>';
                                    case 'SECUREBOOT_OFF':
                                        return '<span class="label label-danger">'+i18n.t('security.off')+'</span>';
                                    case 'SECUREBOOT_UNSUPPORTED':
                                        return '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>';
                                    default:
                                        return '<span class="label label-default">'+i18n.t('unknown')+'</span>';
                                }
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.t2_externalboot')))
                        .append($('<td>')
                            .html(function(){
                                switch(item.t2_externalboot){
                                    case 'EXTERNALBOOT_ON':
                                        return '<span class="label label-danger">'+i18n.t('security.on')+'</span>';
                                    case 'EXTERNALBOOT_OFF':
                                        return '<span class="label label-success">'+i18n.t('security.off')+'</span>';
                                    case 'EXTERNALBOOT_UNSUPPORTED':
                                        return '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>';
                                    default:
                                        return '<span class="label label-default">'+i18n.t('unknown')+'</span>';
                                }
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.security_mode')))
                        .append($('<td>')
                            .html(function(){
                                switch(item.as_security_mode){
                                    case 'FULL_SECURITY':
                                        return '<span class="label label-success">'+i18n.t('security.full')+'</span>';
                                    case 'REDUCED_SECURITY':
                                        return '<span class="label label-warning">'+i18n.t('security.reduced')+'</span>';
                                    case 'PERMISSIVE_SECURITY':
                                        return '<span class="label label-danger">'+i18n.t('security.permissive')+'</span>';
                                    case 'SECURITYMODE_UNSUPPORTED':
                                        return '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>';
                                    default:
                                        return '<span class="label label-default">'+i18n.t('unknown')+'</span>';
                                }
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.third_party_kexts')))
                        .append($('<td>')
                            .html(function(){
                                if(item.as_third_party_kexts == 'Enabled'){
                                    return '<span class="label label-warning">'+i18n.t('enabled')+'</span>';
                                }
                                return item.as_third_party_kexts == 'UNSUPPORTED' ? 
                                    '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                                    '<span class="label label-success">'+i18n.t('disabled')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.user_mdm_control')))
                        .append($('<td>')
                            .html(function(){
                                if(item.as_user_mdm_control == 'Enabled'){
                                    return '<span class="label label-warning">'+i18n.t('enabled')+'</span>';
                                }
                                return item.as_user_mdm_control == 'UNSUPPORTED' ? 
                                    '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                                    '<span class="label label-success">'+i18n.t('disabled')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.dep_mdm_control')))
                        .append($('<td>')
                            .html(function(){
                                if(item.as_dep_mdm_control == 'Enabled'){
                                    return '<span class="label label-warning">'+i18n.t('enabled')+'</span>';
                                }
                                return item.as_dep_mdm_control == 'UNSUPPORTED' ? 
                                    '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                                    '<span class="label label-success">'+i18n.t('disabled')+'</span>';
                            })))
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('security.activation_lock_status')))
                        .append($('<td>')
                            .html(function(){
                                if(item.activation_lock == 'activation_lock_enabled'){
                                    return '<span class="label label-danger">'+i18n.t('enabled')+'</span>';
                                }
                                return item.activation_lock == 'not_supported' ? 
                                    '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                                    '<span class="label label-success">'+i18n.t('disabled')+'</span>';
                            }))))
        });
    });
});
</script>
