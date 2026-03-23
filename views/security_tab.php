<div id="security-tab"></div>

<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/security/security" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/security/security" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2 data-i18n="security.security"></h2>
<div class="tab-pane col-md-6" id="security-tab">

    
    <table class="table table-striped">
        <tr>
            <th data-i18n="security.gatekeeper"></th>
            <td id="security-gatekeeper"></td>
        </tr>
        <tr>
            <th data-i18n="security.sip"></th>
            <td id="security-sip"></td>
        </tr>
        <tr>
            <th data-i18n="security.filevault_status"></th>
            <td id="security-filevault"></td>
        </tr>
        <tr>
            <th data-i18n="security.filevault_users"></th>
            <td id="security-filevault-users"></td>
        </tr>
        <tr>
            <th data-i18n="security.firewall_state"></th>
            <td id="security-firewall"></td>
        </tr>
        <tr>
            <th data-i18n="security.firmwarepw"></th>
            <td id="security-firmwarepw"></td>
        </tr>
        <tr>
            <th data-i18n="security.root_user"></th>
            <td id="security-root-user"></td>
        </tr>
        <tr>
            <th data-i18n="security.ssh_users"></th>
            <td id="security-ssh-users"></td>
        </tr>
        <tr>
            <th data-i18n="security.ssh_groups"></th>
            <td id="security-ssh-groups"></td>
        </tr>
        <tr>
            <th data-i18n="security.ard_users"></th>
            <td id="security-ard-users"></td>
        </tr>
        <tr>
            <th data-i18n="security.ard_groups"></th>
            <td id="security-ard-groups"></td>
        </tr>
        <tr>
            <th data-i18n="security.skel.kext-loading"></th>
            <td id="security-skel"></td>
        </tr>
        <tr>
            <th data-i18n="security.t2_secureboot"></th>
            <td id="security-secureboot"></td>
        </tr>
        <tr>
            <th data-i18n="security.t2_externalboot"></th>
            <td id="security-externalboot"></td>
        </tr>
        <tr>
            <th data-i18n="security.security_mode"></th>
            <td id="security-mode"></td>
        </tr>
        <tr>
            <th data-i18n="security.third_party_kexts"></th>
            <td id="security-third-party-kexts"></td>
        </tr>
        <tr>
            <th data-i18n="security.user_mdm_control"></th>
            <td id="security-user-mdm"></td>
        </tr>
        <tr>
            <th data-i18n="security.dep_mdm_control"></th>
            <td id="security-dep-mdm"></td>
        </tr>
        <tr>
            <th data-i18n="security.activation_lock_status"></th>
            <td id="security-activation-lock"></td>
        </tr>
        <tr>
            <th data-i18n="security.console_session_locked"></th>
            <td id="security-console-locked"></td>
        </tr>
        <tr>
            <th data-i18n="security.apple_setup_timestamp"></th>
            <td id="security-setup-timestamp"></td>
        </tr>
    </table>

</div>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/security/get_data/' + serialNumber, function(data){
        // Check if we have data
        if(!data || !data[0]){
            $('#security-tab')
                .empty()
                .append($('<h4>')
                    .text(i18n.t('no_data')));
            return;
        }
        
        // Use first item from data array
        var item = data[0];
        
        // Gatekeeper status
        $('#security-gatekeeper').html(function(){
            if(item.gatekeeper == 'Active'){
                return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
            }
            return item.gatekeeper == 'Not Supported' ? 
                '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
                '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
        });

        // SIP Status
        $('#security-sip').html(function(){
            if(item.sip == 'Active'){
                return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
            }
            return item.sip == 'Not Supported' ? 
                '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
                '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
        });

        // FileVault Status
        $('#security-filevault').html(function(){
            return item.filevault_status == 1 ? 
                '<span class="label label-success">'+i18n.t('encrypted')+'</span>' : 
                '<span class="label label-danger">'+i18n.t('unencrypted')+'</span>';
        });

        // FileVault Users
        $('#security-filevault-users').text(item.filevault_users);

        // Firewall State
        $('#security-firewall').html(function(){
            if(item.firewall_state == 1){
                return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
            }
            if(item.firewall_state == 2){
                return '<span class="label label-success">'+i18n.t('security.block_all')+'</span>';
            }
            return '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
        });

        // Firmware Password
        $('#security-firmwarepw').html(function(){
            if(item.firmwarepw == 'Yes' || item.firmwarepw == 'command'){
                return '<span class="label label-success">'+i18n.t('enabled')+'</span>';
            }
            return item.firmwarepw == 'Not Supported' ? 
                '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
                '<span class="label label-danger">'+i18n.t('disabled')+'</span>';
        });

        // Root User
        $('#security-root-user').html(function(){
            return item.root_user == 1 ? 
                '<span class="label label-danger">'+i18n.t('enabled')+'</span>' : 
                '<span class="label label-success">'+i18n.t('disabled')+'</span>';
        });

        // SSH Users and Groups
        $('#security-ssh-users').text(item.ssh_users);
        $('#security-ssh-groups').text(item.ssh_groups);

        // ARD Users and Groups
        $('#security-ard-users').text(item.ard_users);
        $('#security-ard-groups').text(item.ard_groups);

        // SKEL State
        $('#security-skel').html(function(){
            if(item.skel_state == 1){
                return '<span class="label label-info">'+i18n.t('security.skel.all-approved')+'</span>';
            }
            return item.skel_state == 0 ? 
                '<span class="label label-info">'+i18n.t('security.skel.user-approved')+'</span>' : 
                '<span class="label label-default">'+i18n.t('unknown')+'</span>';
        });

        // T2 Secure Boot
        $('#security-secureboot').html(function(){
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
        });

        // T2 External Boot
        $('#security-externalboot').html(function(){
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
        });

        // Security Mode
        $('#security-mode').html(function(){
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
        });

        // Third Party Kexts
        $('#security-third-party-kexts').html(function(){
            if(item.as_third_party_kexts == 'Enabled'){
                return '<span class="label label-warning">'+i18n.t('enabled')+'</span>';
            }
            return item.as_third_party_kexts == 'UNSUPPORTED' ? 
                '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                '<span class="label label-success">'+i18n.t('disabled')+'</span>';
        });

        // User MDM Control
        $('#security-user-mdm').html(function(){
            if(item.as_user_mdm_control == 'Enabled'){
                return '<span class="label label-warning">'+i18n.t('enabled')+'</span>';
            }
            return item.as_user_mdm_control == 'UNSUPPORTED' ? 
                '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                '<span class="label label-success">'+i18n.t('disabled')+'</span>';
        });

        // DEP MDM Control
        $('#security-dep-mdm').html(function(){
            if(item.as_dep_mdm_control == 'Enabled'){
                return '<span class="label label-warning">'+i18n.t('enabled')+'</span>';
            }
            return item.as_dep_mdm_control == 'UNSUPPORTED' ? 
                '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                '<span class="label label-success">'+i18n.t('disabled')+'</span>';
        });

        // Activation Lock
        $('#security-activation-lock').html(function(){
            if(item.activation_lock == 'activation_lock_enabled'){
                return '<span class="label label-danger">'+i18n.t('enabled')+'</span>';
            }
            return item.activation_lock == 'not_supported' ? 
                '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
                '<span class="label label-success">'+i18n.t('disabled')+'</span>';
        });

        // Console Session Locked
        $('#security-console-locked').html(function(){
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
        });

        // Apple Setup Timestamp
        $('#security-setup-timestamp').html(function(){
            if(item.apple_setup_timestamp){
                var date = new Date(parseInt(item.apple_setup_timestamp) * 1000);
                return '<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>';
            }
            return '';
        });
    });
});
</script>