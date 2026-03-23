var formatSecurityFirewall = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '1' ? '<span class="label label-success">'+i18n.t('enabled')+'</span>' :
    colvar = colvar == '2' ? '<span class="label label-success">'+i18n.t('security.block_all')+'</span>' :
    (colvar === '0' ? '<span class="label label-danger">'+i18n.t('disabled')+'</span>' : colvar)
    col.html(colvar)
}

var formatConsoleSessionLocked = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '7' ? '<span class="label label-success">'+i18n.t('security.locked_display_off')+'</span>' :
    colvar = colvar == '6' ? '<span class="label label-warning">'+i18n.t('security.unlocked_display_off')+'</span>' :
    colvar = colvar == '5' ? '<span class="label label-success">'+i18n.t('security.locked_screen_saver')+'</span>' :
    colvar = colvar == '4' ? '<span class="label label-warning">'+i18n.t('security.unlocked_screen_saver')+'</span>' :
    colvar = colvar == '3' ? '<span class="label label-success">'+i18n.t('security.locked_login_window')+'</span>' :
    colvar = colvar == '1' ? '<span class="label label-success">'+i18n.t('yes')+'</span>' :
    (colvar === '0' ? '<span class="label label-info">'+i18n.t('no')+'</span>' : 
    colvar = "")
    col.html(colvar)
}

var formatSecurityFileVaultEncrypted = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '1' ? '<span class="label label-success">'+i18n.t('encrypted')+'</span>' :
    (colvar === '0' ? '<span class="label label-danger">'+i18n.t('unencrypted')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecurityGatekeeper = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'Active' ? '<span class="label label-success">'+i18n.t('enabled')+'</span>' :
    colvar = (colvar == 'Not Supported' ? '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
    colvar = '<span class="label label-danger">'+i18n.t('disabled')+'</span>')
    col.html(colvar)
}

var formatSecurityRootUser = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecurityFirmwarePW = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'Yes' ? '<span class="label label-success">'+i18n.t('enabled')+'</span>' :
    colvar = colvar == 'command' ? '<span class="label label-success">'+i18n.t('enabled')+'</span>' :
    colvar = colvar == 'No' ? '<span class="label label-danger">'+i18n.t('disabled')+'</span>' :
    colvar = (colvar == 'Not Supported' ? '<span class="label label-info">'+i18n.t('unsupported')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecuritySKEL = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '1' ? '<span class="label label-info">'+i18n.t('security.skel.all-approved')+'</span>' :
    colvar = (colvar == '0' ? '<span class="label label-info">'+i18n.t('security.skel.user-approved')+'</span>' :
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecuritySecureBoot = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'SECUREBOOT_FULL' ? '<span class="label label-success">'+i18n.t('security.full')+'</span>' :
    colvar = colvar == 'SECUREBOOT_MEDIUM' ? '<span class="label label-warning">'+i18n.t('security.medium')+'</span>' :
    colvar = colvar == 'SECUREBOOT_OFF' ? '<span class="label label-danger">'+i18n.t('security.off')+'</span>' :
    colvar = (colvar == 'SECUREBOOT_UNSUPPORTED' ? '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecurityExternalBoot = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'EXTERNALBOOT_ON' ? '<span class="label label-danger">'+i18n.t('security.on')+'</span>' :
    colvar = colvar == 'EXTERNALBOOT_OFF' ? '<span class="label label-success">'+i18n.t('security.off')+'</span>' :
    colvar = (colvar == 'EXTERNALBOOT_UNSUPPORTED' ? '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatASSecurityMode = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'FULL_SECURITY' ? '<span class="label label-success">'+i18n.t('security.full')+'</span>' :
    colvar = colvar == 'REDUCED_SECURITY' ? '<span class="label label-warning">'+i18n.t('security.reduced')+'</span>' :
    colvar = colvar == 'PERMISSIVE_SECURITY' ? '<span class="label label-danger">'+i18n.t('security.permissive')+'</span>' :
    colvar = (colvar == 'SECURITYMODE_UNSUPPORTED' ? '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecurityThirdPartyKexts = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'Enabled' ? '<span class="label label-warning">'+i18n.t('enabled')+'</span>' :
    colvar = colvar == 'Disabled' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' :
    colvar = (colvar == 'UNSUPPORTED' ? '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecurityActivationLock = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == 'activation_lock_enabled' ? '<span class="label label-danger">'+i18n.t('enabled')+'</span>' :
    colvar = colvar == 'activation_lock_disabled' ? '<span class="label label-success">'+i18n.t('disabled')+'</span>' :
    colvar = (colvar == 'not_supported' ? '<span class="label label-info">'+i18n.t('security.unsupported')+'</span>' : 
    colvar = '<span class="label label-default">'+i18n.t('unknown')+'</span>')
    col.html(colvar)
}

var formatSecuritytimestampToMoment =  function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    if (colvar > 0){
        var date = new Date(parseInt(colvar) * 1000);
        col.html('<span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span>')
    }
}

// Filters

var fv_state = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^filevault_off$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '!= 1';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^filevault_on$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }
}

var ssh_state = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^ssh_off$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = 'SSH Disabled';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^ssh_on$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '^(?!.*SSH Disabled).*';
        console.log("Column search value:", d.columns[colNumber].search.value);
        // Clear global search
        d.search.value = '';
    }
}


var gatekeeper_state = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^gatekeepr_off$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = 'Disabled';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^gatekeepr_on$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = 'Active';
        // Clear global search
        d.search.value = '';
    }
}

var sip_state = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^sip_off$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = 'Disabled';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^sip_on$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = 'Active';
        // Clear global search
        d.search.value = '';
    }
    
    // Look for 'Disabled' keyword
    if(d.search.value.match(/^sip_no_support$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = 'Not Supported';
        // Clear global search
        d.search.value = '';
    }
}

var firewall_state = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^firewall_off$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 0';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^firewall_on$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }
    
    // Look for 'Disabled' keyword
    if(d.search.value.match(/^firewall_stealth$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 2';
        // Clear global search
        d.search.value = '';
    }
}

var console_session_locked_state = function(colNumber, d){
    // Look for '7' keyword
    if(d.search.value.match(/^console_session_locked_yes_do/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 7';
        // Clear global search
        d.search.value = '';
    }

    // Look for '6' keyword
    if(d.search.value.match(/^console_session_locked_no_do/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 6';
        // Clear global search
        d.search.value = '';
    }

    // Look for '5' keyword
    if(d.search.value.match(/^console_session_locked_yes_ss/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 5';
        // Clear global search
        d.search.value = '';
    }

    // Look for '4' keyword
    if(d.search.value.match(/^console_session_locked_no_ss/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 4';
        // Clear global search
        d.search.value = '';
    }

    // Look for '3' keyword
    if(d.search.value.match(/^console_session_locked_yes_lw/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 3';
        // Clear global search
        d.search.value = '';
    }

    // Look for '1' keyword
    if(d.search.value.match(/^console_session_locked_yes/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }

    // Look for '0' keyword
    if(d.search.value.match(/^console_session_locked_no/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 0';
        // Clear global search
        d.search.value = '';
    }
}