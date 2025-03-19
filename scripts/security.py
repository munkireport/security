#!/usr/local/munkireport/munkireport-python3
"""
Extracts information about SIP, Gatekeeper, and users who have remote access.
"""

import os
import sys
import subprocess
import grp

sys.path.insert(0, '/usr/local/munki')
sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist

from Foundation import CFPreferencesCopyAppValue

# Disable PyLint complaining about 'invalid' names and lines too long
# pylint: disable=C0103
# pylint: disable=C0301

def gatekeeper_check():
    """ Gatekeeper checks. Simply calls the spctl and parses status. Requires 10.7+"""

    if float(os.uname()[2][0:2]) >= 11:
        sp = subprocess.Popen(['/usr/sbin/spctl', '--status'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        out, err = sp.communicate()
        if "enabled" in out.decode():
            return "Active"
        else:
            return "Disabled"
    else:
        return "Not Supported"

def activation_lock_check():
    """ Checks if Activation Lock is enabled."""

    try:
        cmd = ['/usr/sbin/system_profiler', 'SPHardwareDataType', '-xml']
        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()

        try:
            plist = plistlib.readPlistFromString(output.decode())
        except AttributeError as e:
            plist = plistlib.loads(output.decode())
            
        # system_profiler xml is an array
        sp_dict = plist[0]
        items = sp_dict['_items']
        for item in items:
            for key in item:
                if key == "activation_lock_status":
                     return item[key]
            return "not_supported" 
    except Exception:
        return "not_supported"

def t2_chip_check():
    """ Checks if T2 chip is present."""

    sp = subprocess.Popen(['/usr/sbin/system_profiler', 'SPiBridgeDataType'], stdout=subprocess.PIPE)
    out, err = sp.communicate()
    if "Apple T2" in out.decode():
        return True
    else:
        return False

def t2_secureboot_check():
    """ Checks Secure Boot settings from nvram. T2 chip models only. """

    sp = subprocess.Popen(['/usr/sbin/nvram', '94b73556-2197-4702-82a8-3e1337dafbfb:AppleSecureBootPolicy'], stdout=subprocess.PIPE)
    out, err = sp.communicate()
    out_value = out.decode().split("\t")[1].rstrip() 

    if "%02" in out_value:
        secureboot_value = "SECUREBOOT_FULL"
    elif "%01" in out_value:
        secureboot_value = "SECUREBOOT_MEDIUM"
    elif "%00" in out_value:
        secureboot_value = "SECUREBOOT_OFF"
    else:
        secureboot_value = "SECUREBOOT_UNKNOWN"           

    return secureboot_value

def t2_externalboot_check():
    """ Checks External Boot settings from nvram. T2 chip models only. """
    
    sp = subprocess.Popen(['/usr/sbin/nvram', '5eeb160f-45fb-4ce9-b4e3-610359abf6f8:StartupManagerPolicy'], stdout=subprocess.PIPE)
    out, err = sp.communicate()
    out_value = out.decode().split("\t")[1].rstrip()
    
    if "%03" in out_value:
        externalboot_value = "EXTERNALBOOT_ON"
    elif "%00" in out_value:
        externalboot_value = "EXTERNALBOOT_OFF"
    else:
        externalboot_value = "EXTERNALBOOT_UNKNOWN"           
    
    return externalboot_value

def as_security_mode_check():
    """ Checks Security Mode settings. Apple Silicon Macs only. """
    
    sp = subprocess.Popen(['/usr/bin/arch', '-arm64', '/usr/bin/bputil', '--display-policy'], stdout=subprocess.PIPE)
    out, err = sp.communicate()
    out_value = out.decode()
    
    if "Security Mode:               Full" in out_value:
        security_mode_value = "FULL_SECURITY"
    elif "Security Mode:               Reduced" in out_value:
        security_mode_value = "REDUCED_SECURITY"
    elif "Security Mode:               Permissive" in out_value:
        security_mode_value = "PERMISSIVE_SECURITY"           
    else:
        security_mode_value = "UNKNOWN"           
    
    return security_mode_value

def sip_check():
    """ SIP checks. We need to be running 10.11 or newer."""

    if float(os.uname()[2][0:2]) >= 15:
        sp = subprocess.Popen(['/usr/bin/csrutil', 'status'],
                              stdout=subprocess.PIPE,
                              universal_newlines=True)
        out, err = sp.communicate()

        # just read the first line of the output, the
        # System Integrity Protection status: ....
        # search for a full stop, as custom configurations don't have
        # that there.
        first_line = out.split("\n")[0]
        if "enabled." in first_line:
            return "Active"
        else:
            return "Disabled"
    else:
        return "Not Supported"

def ssh_user_access_check():
    """Check for users who can log in via SSH
    using the built-in group reporting.
    Checks for explicitly added users , both local and directory based."""

    #Check first that SSH is enabled!
    sp = subprocess.Popen(['/usr/sbin/systemsetup', '-getremotelogin'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    if "Off" in out.decode():
        return "SSH Disabled"

    else:
        # First we need to check if SSH is open to all users. A few ways  to tell:
        # -on 10.8 and older, systemsetup will show as on but the access_ssh groups are not present
        # -on 10.9, systemsetup will show as on and list all users in access_ssh
        # -on 10.10 and newer, systemsetup will show as on and access_ssh-disabled will be present
        # Note for 10.10 and newer - root will show up as authorized if systemsetup was used to turn
        # on SSH, and not if pref pane was used.

        sp = subprocess.Popen(['/usr/bin/dscl', '.', 'list', '/Groups'], stdout=subprocess.PIPE)
        out, err = sp.communicate()

        if "com.apple.access_ssh-disabled" in out.decode():
            # if this group exists, all users are permitted to access SSH
            return "All users permitted"

        elif "com.apple.access_ssh" in out.decode():
            # if this group exists, SSH is enabled but only some users are permitted
            # Get a list of users in the com.apple.access_ssh GroupMembership
            user_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.access_ssh', 'GroupMembership'], stderr=subprocess.PIPE, stdout=subprocess.PIPE)
            user_out, user_err = user_sp.communicate()
            user_list = user_out.decode().split()

            return ', '.join(item for item in user_list[1:])

        else:
            # if neither SSH group exists but SSH is enabled, it was turned on with
            # systemsetup and all users are enabled.
            return "All users permitted"

def ssh_group_access_check():
    """Check for groups that have members who can log in via SSH
    using the built-in group reporting.
    Checks for explicitly added groups, both local and directory based."""

    #Check first that SSH is enabled!
    sp = subprocess.Popen(['/usr/sbin/systemsetup', '-getremotelogin'], stdout=subprocess.PIPE)
    out, err = sp.communicate()

    if "Off" in out.decode():
        return "SSH Disabled"

    else:
        # First we need to check if SSH is open to all users. A few ways  to tell:
        # -on 10.8 and older, systemsetup will show as on but the access_ssh groups are not present
        # -on 10.9, systemsetup will show as on and list all users in access_ssh
        # -on 10.10 and newer, systemsetup will show as on and access_ssh-disabled will be present
        # Note for 10.10 and newer - root will show up as authorized if systemsetup was used to turn
        # on SSH, and not if pref pane was used.

        sp = subprocess.Popen(['/usr/bin/dscl', '.', 'list', '/Groups'], stdout=subprocess.PIPE)
        out, err = sp.communicate()

        if "com.apple.access_ssh-disabled" in out.decode():
            # if this group exists, all users are permitted to access SSH.
            # Nothing group specific
            return ''

        elif "com.apple.access_ssh" in out.decode():
            # Get a list of UUIDs of Nested Groups
            group_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.access_ssh', 'NestedGroups'], stderr=subprocess.PIPE, stdout=subprocess.PIPE)
            group_out, group_err = group_sp.communicate()
            group_list_uuid = group_out.split()

            # Translate group UUIDs to gids
            group_list = []
            try:
                for group_uuid in group_list_uuid[1:]:
                    group_id_sp = subprocess.Popen(['/usr/bin/dsmemberutil', 'getid', '-x', group_uuid], stdout=subprocess.PIPE)
                    group_id_out, group_id_err = group_id_sp.communicate()
                    group_list.append(grp.getgrgid(group_id_out.split()[1]).gr_name)
            except IndexError:
                pass
                
            return ', '.join(item for item in group_list)

        else:
            # If neither SSH group exists but SSH is enabled, it was turned on with
            # systemsetup and all users are enabled.
            # Nothing group specific
            return ''

def ard_access_check():
    """Check for local users who have ARD permissions
    First we need to check if all users are allowed. If not, we look for granular permissions
    in the directory. Thank you @frogor and @foigus for help on the directory part."""

    # First method: check if all users are permitted.
    # Thank you to @steffan for pointing out this plist key!
    plist_path = '/Library/Preferences/com.apple.RemoteManagement.plist'
    if os.path.exists(plist_path):
        plist = FoundationPlist.readPlist(plist_path)

        if plist.get('ARD_AllLocalUsers', None):
            return "All users permitted"
        else:
            # Second method - check local directory for naprivs
            sp = subprocess.Popen(['/usr/bin/dscl', '.', '-list', '/Users'], stdout=subprocess.PIPE)
            out, err = sp.communicate()

            user_list = out.decode().split()
            ard_users = []
            for user in user_list:
                if user[0] in '_':
                    continue
                else:
                    args = '/Users/' + user
                    sp = subprocess.Popen(['/usr/bin/dscl', '.', '-read', args, 'naprivs'], stderr=subprocess.PIPE, stdout=subprocess.PIPE)
                    out, err = sp.communicate()
                if out.decode() not in 'No such key':
                    ard_users.append(user)
                else:
                    pass

            remote_user_check = subprocess.Popen(['/usr/bin/dscl', '.', 'list', '/Groups'], stdout=subprocess.PIPE)
            remote_out, remote_err = remote_user_check.communicate()

            ard_user_list = []
            if "com.apple.local.ard_interact" in remote_out.decode():
                ard_gm_check = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_interact'], stdout=subprocess.PIPE)
                ard_gm_check_out, ard_gm_check_err = ard_gm_check.communicate()

                if "GroupMembership" in ard_gm_check_out.decode():
                    ard_user_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_interact', 'GroupMembership'], stdout=subprocess.PIPE)
                    ard_user_out, ard_user_err = ard_user_sp.communicate()
                    ard_user_list = ard_user_out.decode().split()
                    ard_users.extend(ard_user_list[1:])

            if "com.apple.local.ard_admin" in remote_out.decode():
                ard_gm_check = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_admin'], stdout=subprocess.PIPE)
                ard_gm_check_out, ard_gm_check_err = ard_gm_check.communicate()

                if "GroupMembership" in ard_gm_check_out.decode():
                    ard_user_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_admin', 'GroupMembership'], stdout=subprocess.PIPE)
                    ard_user_out, ard_user_err = ard_user_sp.communicate()
                    ard_user_list = ard_user_out.decode().split()
                    if ard_user_out not in ard_user_list:
                        ard_users.extend(ard_user_list[1:])

            if "com.apple.local.ard_manage" in remote_out.decode():
                ard_gm_check = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_manage'], stdout=subprocess.PIPE)
                ard_gm_check_out, ard_gm_check_err = ard_gm_check.communicate()

                if "GroupMembership" in ard_gm_check_out.decode():
                    ard_user_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_manage', 'GroupMembership'], stdout=subprocess.PIPE)
                    ard_user_out, ard_user_err = ard_user_sp.decode().communicate()
                    ard_user_list = ard_user_out.split()
                    if ard_user_out not in ard_user_list:
                        ard_users.extend(ard_user_list[1:])

            return ', '.join(item for item in ard_users)
    else:
        # plist_path does not exist, which indicates that ARD is not enabled.
        return "ARD Disabled"

def ard_group_access_check():
    """Check for local users who have ARD permissions
    First we need to check if all users are allowed. If not, we look for granular permissions
    in the directory. Thank you @frogor and @foigus for help on the directory part."""

    # First method: check if all users are permitted.
    # Thank you to @steffan for pointing out this plist key!
    plist_path = '/Library/Preferences/com.apple.RemoteManagement.plist'
    if os.path.exists(plist_path):
        plist = FoundationPlist.readPlist(plist_path)

        if plist.get('ARD_AllLocalUsers', None):
            return "All users permitted"
        else:
            # Get list of groups from dscl
            remote_group_check = subprocess.Popen(['/usr/bin/dscl', '.', 'list', '/Groups'], stdout=subprocess.PIPE)
            remote_group_out, remote_group_err = remote_group_check.communicate()
            group_list = []

            #Check if ard_interact is in the group list
            if "com.apple.local.ard_interact" in remote_group_out.decode():
                # If so read the group and check if there is a NestedGroups value
                ard_ng_check = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_interact'], stdout=subprocess.PIPE)
                ard_ng_check_out, ard_ng_check_err = ard_ng_check.communicate()

                if "NestedGroups" in ard_ng_check_out.decode():
                    try:
                        # Get a list of UUIDs of Nested Groups
                        group_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_interact', 'NestedGroups'], stdout=subprocess.PIPE)
                        group_out, group_err = group_sp.communicate()
                        group_list_uuid = group_out.split()

                        # Translate group UUIDs to gids
                        try:
                            for group_uuid in group_list_uuid[1:]:
                                group_id_sp = subprocess.Popen(['/usr/bin/dsmemberutil', 'getid', '-x', group_uuid], stdout=subprocess.PIPE)
                                group_id_out, group_id_err = group_id_sp.communicate()
                                group_name = grp.getgrgid(group_id_out.decode().split()[1]).gr_name
                                if group_name not in group_list:
                                    group_list.append(group_name)
                        except IndexError:
                            pass
                    except:
                        pass

            #Check if ard_admin is in the group list
            if "com.apple.local.ard_admin" in remote_group_out.decode():
                # If so read the group and check if there is a NestedGroups value
                ard_ng_check = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_admin'], stdout=subprocess.PIPE)
                ard_ng_check_out, ard_ng_check_err = ard_ng_check.communicate()

                if "NestedGroups" in ard_ng_check_out.decode():
                    try:
                        # Get a list of UUIDs of Nested Groups
                        group_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_admin', 'NestedGroups'], stdout=subprocess.PIPE)
                        group_out, group_err = group_sp.communicate()
                        group_list_uuid = group_out.decode().split()

                        # Translate group UUIDs to gids
                        try:
                            for group_uuid in group_list_uuid[1:]:
                                group_id_sp = subprocess.Popen(['/usr/bin/dsmemberutil', 'getid', '-x', group_uuid], stdout=subprocess.PIPE)
                                group_id_out, group_id_err = group_id_sp.communicate()
                                if group_id_sp.returncode == 0:
                                    ard_group = grp.getgrgid(group_id_out.decode().split()[1]).gr_name
                                    group_name = grp.getgrgid(group_id_out.decode().split()[1]).gr_name
                                    if group_name not in group_list:
                                        group_list.append(group_name)
                        except IndexError:
                            pass
                    except:
                        pass

            #Check if ard_manage is in the group list
            if "com.apple.local.ard_manage" in remote_group_out.decode():
                # If so read the group and check if there is a NestedGroups value
                ard_ng_check = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_manage'], stdout=subprocess.PIPE)
                ard_ng_check_out, ard_ng_check_err = ard_ng_check.communicate()

                if "NestedGroups" in ard_ng_check_out.decode():
                    try:
                        # Get a list of UUIDs of Nested Groups
                        group_sp = subprocess.Popen(['/usr/bin/dscl', '.', 'read', '/Groups/com.apple.local.ard_manage', 'NestedGroups'], stdout=subprocess.PIPE)
                        group_out, group_err = group_sp.communicate()
                        group_list_uuid = group_out.decode().split()

                        # Translate group UUIDs to gids
                        try:
                            for group_uuid in group_list_uuid[1:]:
                                group_id_sp = subprocess.Popen(['/usr/bin/dsmemberutil', 'getid', '-x', group_uuid], stdout=subprocess.PIPE)
                                group_id_out, group_id_err = group_id_sp.communicate()
                                if group_id_sp.returncode == 0:
                                    group_name = grp.getgrgid(group_id_out.decode().split()[1]).gr_name
                                    if group_name not in group_list:
                                        group_list.append(group_name)
                        except IndexError:
                            pass
                    except:
                        pass
                        
            return ', '.join(item for item in group_list)

#            return group_list
    else:
        # plist_path does not exist, which indicates that ARD is not enabled.
        return "ARD Disabled"

def firmware_pw_check():
    """Checks to see if a firmware password is set.
    The command firmwarepassword appeared in 10.10, so we use nvram for older versions.
    Thank you @steffan for this check."""
    # Firmware passwords not supported on Apple Silicon - return No if we are running it

    if "arm64" in os.uname()[3].lower():
        return "Not Supported"

    if float(os.uname()[2][0:2]) >= 14:
        try:
            sp = subprocess.Popen(['/usr/sbin/firmwarepasswd', '-check'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            out, err = sp.communicate()

            if "machine is not supported." in err.decode():
                return "Not Supported"
            else:
                firmwarepw = out.decode().split()[2]
        except OSError as e:
            # firmwarepasswd command not found at the path we specified
            # so set the data to blank and print a warning.
            print ("Error: firmwarepasswd binary not found or accessible.")
            firmwarepw = ""

    else:
        sp = subprocess.Popen(['/usr/sbin/nvram', 'security-mode'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        mode_out, mode_err = sp.communicate()

        if "none" in mode_out.decode() or "Error getting variable" in mode_err.decode():
            firmwarepw = "No"
        else:
            firmwarepw = "Yes"

    return firmwarepw

def firewall_enable_check():
    """Checks to see if firewall is by calling the preference domain.
    Doing it this way because we want to check if it's enabled via profile"""

    return CFPreferencesCopyAppValue('globalstate', '/usr/libexec/ApplicationFirewall/com.apple.alf')

def skel_state_check():
    """Checks to see if Secure Kernel Extension Loading ("SKEL") is enabled or disabled.
    Only supported with macOS High Sierra (10.13 / 17) and up."""

    if float(os.uname()[2][0:2]) >= 17:
        sp = subprocess.Popen(['/usr/sbin/spctl', 'kext-consent', 'status'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        out, err = sp.communicate()

        if "ENABLED" in out.decode():
            return 1
        else:
            return 0
    else:
        return 1 # if the OS is < 10.13, KEXT loading is open by default.

def root_enabled_check():
    """Checks to see if the root user is enabled or disabled."""
    sp = subprocess.Popen(['/usr/bin/dscl', '-plist', '.', '-read', '/Users/root/'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    out, err = sp.communicate()

    root_plist = FoundationPlist.readPlistFromString(out)

    try:
        if len(root_plist['dsAttrTypeStandard:Password']) > 1:
            # several *s in output means password is set and account is enabled
            return 1
        else:
            # a single * means the password is set but account is disabled
            return 0
    except KeyError:
        # root pw has never been set, so it is disabled
        return 0

def get_filevault_status():
    """ FileVault boot drive encrypted checks. """

    fv_info = {'filevault_status':0}
    cmd = ['/usr/bin/fdesetup', 'status']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
            
    for fv_line in output.decode().split('\n'):
        if 'FileVault is On.' in fv_line:
            fv_info['filevault_status'] = 1
    
    # If FileVault is encrypted, get FileVault users
    if fv_info['filevault_status'] == 1:

        cmd = ['/usr/bin/fdesetup', 'list']
        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()
        
        fv_users = []
        
        for fv_user in output.decode().split('\n'):
            fv_users.append(fv_user.split(',')[0])
        
        del fv_users[-1]
        
        fv_info['filevault_users'] = ', '.join(fv_users)
    else:
        fv_info['filevault_users'] = ""

    return fv_info


def main():
    """main"""

    # Create an empty directory object to hold results from check methods, then run.
    result = {}
    result.update({'gatekeeper': gatekeeper_check()})
    result.update({'sip': sip_check()})
    result.update({'ssh_users': ssh_user_access_check()})
    result.update({'ssh_groups': ssh_group_access_check()})
    result.update({'ard_users': ard_access_check()})
    result.update({'ard_groups': ard_group_access_check()})
    result.update({'firmwarepw': firmware_pw_check()})
    result.update({'firewall_state':firewall_enable_check()})
    result.update({'skel_state':skel_state_check()})
    result.update({'root_user':root_enabled_check()})
    if t2_chip_check():
        result.update({'as_security_mode': "SECURITYMODE_UNSUPPORTED"})
        result.update({'t2_secureboot': t2_secureboot_check()})
        result.update({'t2_externalboot': t2_externalboot_check()})
    elif "arm" in os.uname()[3].lower():
        result.update({'as_security_mode': as_security_mode_check()})
        result.update({'t2_secureboot': "SECUREBOOT_UNSUPPORTED"})
        result.update({'t2_externalboot': "EXTERNALBOOT_UNSUPPORTED"})
    else:
        result.update({'as_security_mode': "SECURITYMODE_UNSUPPORTED"})
        result.update({'t2_secureboot': "SECUREBOOT_UNSUPPORTED"})
        result.update({'t2_externalboot': "EXTERNALBOOT_UNSUPPORTED"})
    result.update({'activation_lock': activation_lock_check()})
    result.update(get_filevault_status())

    # Write results of checks to cache file
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'security.plist')
    FoundationPlist.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
