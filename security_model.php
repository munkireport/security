<?php

use CFPropertyList\CFPropertyList;

class Security_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'security'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['gatekeeper'] = '';
        $this->rs['sip'] = '';
        $this->rs['ssh_groups'] = '';
        $this->rs['ssh_users'] = '';
        $this->rs['ard_groups'] = '';
        $this->rs['ard_users'] = '';
        $this->rs['firmwarepw'] = '';
        $this->rs['firewall_state'] = '';
        $this->rs['skel_state'] = '';
        $this->rs['root_user'] = '';
        $this->rs['t2_secureboot'] = '';
        $this->rs['t2_externalboot'] = '';        
        $this->rs['activation_lock'] = '';

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }
    
    
    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
    	if (strpos($data, '<?xml') === false) {
    		// old style txt file data has been passed - throw an error.
    		throw new Exception("Error Processing Request: old format data found, please update the security module", 1);	
    	}
    	else {
    		$parser = new CFPropertyList();
    		$parser->parse($data);

    		$plist = $parser->toArray();

    		foreach (array('activation_lock', 'sip', 'gatekeeper', 'ssh_groups', 'ssh_users', 'ard_groups', 'ard_users', 'firmwarepw', 'firewall_state', 'skel_state', 'root_user', 't2_secureboot', 't2_externalboot') as $item) {
    			if (isset($plist[$item])) {
    				$this->$item = $plist[$item];
    			} else {
    				$this->$item = '';
    			}
    		}
    		$this->save();
    	}
    }

    public function get_activation_lock_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN activation_lock = 'activation_lock_enabled' THEN 1 END) AS Enabled,
		COUNT(CASE WHEN activation_lock = 'activation_lock_disabled' THEN 1 END) AS Disabled,
		COUNT(CASE WHEN activation_lock = 'not_supported' THEN 1 END) as Notsupported
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_sip_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN sip = 'Active' THEN 1 END) AS Active,
		COUNT(CASE WHEN sip = 'Disabled' THEN 1 END) AS Disabled
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_gatekeeper_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN gatekeeper = 'Active' THEN 1 END) AS Active,
                COUNT(CASE WHEN gatekeeper = 'Disabled' THEN 1 END) AS Disabled
                FROM security
                LEFT JOIN reportdata USING(serial_number)
                ".get_machine_group_filter();
        return current($this->query($sql));
    }

    public function get_firmwarepw_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN firmwarepw = 'Yes' THEN 1 END) AS enabled,
        COUNT(CASE WHEN firmwarepw = 'No' THEN 1 END) AS disabled,
        COUNT(CASE WHEN firmwarepw = 'Not Supported' THEN 1 END) as notsupported
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_firewall_state_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN firewall_state = '2' THEN 1 END) as blockall,
                COUNT(CASE WHEN firewall_state = '1' THEN 1 END) as enabled,
                COUNT(CASE WHEN firewall_state = '0' THEN 1 END) as disabled
                FROM security
                LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_skel_stats()
    {
    $sql = "SELECT COUNT(CASE WHEN skel_state = '0' THEN 1 END) as disabled,
                COUNT(CASE WHEN skel_state = '1' THEN 1 END) as enabled
                FROM security
                LEFT JOIN reportdata USING(serial_number)
        ".get_machine_group_filter();
    return current($this->query($sql));
    }

    public function get_ssh_stats()
    {
    $sql = "SELECT COUNT(CASE WHEN ssh_users <> 'SSH Disabled' THEN 1 END) as enabled,
                COUNT(CASE WHEN ssh_users = 'SSH Disabled' THEN 1 END) as disabled
                FROM security
                LEFT JOIN reportdata USING(serial_number)
        ".get_machine_group_filter();
    return current($this->query($sql));
    }

    public function get_root_user_stats()
    {
    $sql = "SELECT COUNT(CASE WHEN root_user = '0' THEN 1 END) as disabled,
                COUNT(CASE WHEN root_user = '1' THEN 1 END) as enabled
                FROM security
                LEFT JOIN reportdata USING(serial_number)
            ".get_machine_group_filter();
    return current($this->query($sql));
    }

    public function get_secureboot_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN t2_secureboot = 'SECUREBOOT_FULL' THEN 1 END) AS securebootfull,
		COUNT(CASE WHEN t2_secureboot = 'SECUREBOOT_MEDIUM' THEN 1 END) AS securebootmedium,
		COUNT(CASE WHEN t2_secureboot = 'SECUREBOOT_OFF' THEN 1 END) AS securebootoff,
		COUNT(CASE WHEN t2_secureboot = 'SECUREBOOT_UNKNOWN' THEN 1 END) AS securebootunknown,
		COUNT(CASE WHEN t2_secureboot = 'SECUREBOOT_UNSUPPORTED' THEN 1 END) AS securebootunsupported
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

    public function get_externalboot_stats()
    {
	$sql = "SELECT COUNT(CASE WHEN t2_externalboot = 'EXTERNALBOOT_ON' THEN 1 END) AS externalbooton,
		COUNT(CASE WHEN t2_externalboot = 'EXTERNALBOOT_OFF' THEN 1 END) AS externalbootoff,
		COUNT(CASE WHEN t2_externalboot = 'EXTERNALBOOT_UNKNOWN' THEN 1 END) AS externalbootunknown,
		COUNT(CASE WHEN t2_externalboot = 'EXTERNALBOOT_UNSUPPORTED' THEN 1 END) AS externalbootunsupported
		FROM security
		LEFT JOIN reportdata USING(serial_number)
		".get_machine_group_filter();
	return current($this->query($sql));
    }

}
