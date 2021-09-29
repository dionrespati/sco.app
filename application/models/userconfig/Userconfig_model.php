<?php
class Userconfig_model extends MY_Model {

	function __construct() {
        // Call the Model constructor
        parent::__construct();

    }




	/*----------------
	 * USER GROUP
	 * --------------*/

	function getListUserGroup($param, $value) {
		$qry = "SELECT * FROM ecomm_usergroup WHERE $param = ?";
		$res = $this->getRecordset($qry, $value, $this->db2);
		if($res == null) {
			throw new Exception("No result", 1);
		}
		return $res;
	}
	function getListAllUserGroup() {
		$qry = "SELECT * FROM ecomm_usergroup";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		if($res == null) {
			throw new Exception("Data user group is empty..!", 1);
		}
		return $res;
	}

	function getListAllUserGroupByID($id) {
		$qry = "SELECT * FROM ecomm_usergroup WHERE groupid = ?";
		$res = $this->getRecordset($qry, $id, $this->db2);
		if($res == null) {
			throw new Exception("Data user group is empty..!", 1);
		}
		return $res;
	}
	function saveInputUserGroup() {
        $data = $this->input->post(NULL, TRUE);
        $arr = array(
            'groupname' => $data['groupname']
        );
        $res = $this->db->insert('klink_mlm2010.dbo.ecomm_usergroup', $arr);
        if($res > 0) {
            $arr = jsonTrueResponse(null, "Berhasil menyimpan data user..");
        }
        return $arr;
    }

	function updateUserGroup() {
        $data = $this->input->post(NULL, TRUE);
        $arr = array(
            'groupname' => $data['groupname'],
            'groupid' => $data['id']
        );
        $this->db->where('groupid', $data['id']);
        $res = $this->db->update('klink_mlm2010.dbo.ecomm_usergroup', $arr);
        if($res > 0) {
            $arr = jsonTrueResponse(null, "Berhasil memperbarui user group..");
        }
        return $arr;
	}

	function deleteUserGroup($id) {
		$qry = "DELETE FROM ecomm_usergroup WHERE groupid = ?";
		$query = $this->executeQuery2($qry, $id);
		if(!$query) {
			throw new Exception("Delete Group Menu failed..!!", 1);
		}
		return $query;
	}

	function updatePreviousBnsmonth($username, $prevbns) {
		$arrResponse = jsonFalseResponse("Setting Previous Bonus Period gagal..");
		$arr = array(
            'prev_period_bnsmonth' => $prevbns
        );
		$this->db->where('username', $username);
        $res = $this->db->update('klink_mlm2010.dbo.ecomm_user', $arr);
        if($res > 0) {
            $arrResponse = jsonTrueResponse(null, "Bonus Period periode sebelum nya untuk $username sudah diaktifkan..");
		}
		return $arrResponse;
	}

	/*----------------
	 * USER
	 * --------------*/
	 function getListAllUser() {
	 	$qry = "SELECT
				  b.groupname,
				  a.username,
				  a.password,
				  a.departmentid,
				  a.branchid,
				  c.lastkitno, c.memberprefix, a.prev_period_bnsmonth,
				  CONVERT(VARCHAR(30),a.createdt, 103) AS createdt
				FROM
				   ecomm_user a
				  INNER JOIN ecomm_usergroup b
				  	ON (a.groupid = b.groupid)
				  LEFT OUTER JOIN dbo.mssc c
				    ON (a.branchid COLLATE SQL_Latin1_General_CP1_CS_AS = c.loccd)";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		if($res == null) {
			throw new Exception("Data user is empty..!", 1);
		}
		return $res;
	 }

	 function getListAllUserByParam($param, $value) {
	 	$qry = "SELECT
				  dbo.ecomm_usergroup.groupname,
				  dbo.ecomm_user.username,
				  dbo.ecomm_user.departmentid,
				  dbo.ecomm_user.branchid,
				  dbo.ecomm_user.password,
				  CONVERT(VARCHAR(30),dbo.ecomm_user.createdt, 103) AS createdt
				FROM
				  dbo.ecomm_user
				  INNER JOIN dbo.ecomm_usergroup
				  ON (dbo.ecomm_user.groupid = dbo.ecomm_usergroup.groupid)
				WHERE dbo.ecomm_user.$param = ?";
		$res = $this->getRecordset($qry, $value, $this->db2);
		if($res == null) {
			throw new Exception("Data user is empty..!", 1);
		}
		return $res;
	 }

	 function getListUser($param, $value) {
		$qry = "SELECT * FROM ecomm_user WHERE $param = ?";
		$res = $this->getRecordset($qry, $value, $this->db2);
		if($res == null) {
			throw new Exception("No result", 1);
		}
		return $res;
	 }

    function saveInputUser() {
         $data = $this->input->post(NULL, TRUE);
         $arr = array(
             'username' => $data['username'],
             'password' => $data['password'],
             'status' => $data['status'],
             'branchid' => $data['branchid'],
             'departmentid' => $data['departmentid'],
             'createnm' => $this->username,
             'groupid' => $data['groupid']
         );
         $res = $this->db->insert('klink_mlm2010.dbo.ecomm_user', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan user..");
         }
         return $arr;
    }

    function saveUpdateUser() {
        $data = $this->input->post(NULL, TRUE);
        $arr = array(
            'password' => $data['password'],
            'status' => $data['status'],
            'branchid' => $data['branchid'],
            'departmentid' => $data['departmentid'],
            'groupid' => $data['groupid']
        );
        $this->db->where('username', $data['username']);
        $res = $this->db->update('klink_mlm2010.dbo.ecomm_user', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan user..");
         }
         return $arr;
    }

	 function deleteUser($id) {
	 	$qry = "DELETE FROM ecomm_user WHERE username = ?";
		$query = $this->executeQuery($qry, $id);
		if(!$query) {
			throw new Exception("Delete User failed..!!", 1);
		}
		return $query;
	 }

     /*----------------
	 * USER
	 * --------------*/
     function getListAllApplication() {
	 	$qry = "SELECT
				  a.app_id,
				  a.app_name,
				  a.app_url, a.status, createnm,
				  CONVERT(VARCHAR(30),a.createdt, 103) AS createdt
				FROM
				  app_table a";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		if($res == null) {
			throw new Exception("Data Application is empty..!", 1);
		}
		return $res;
	 }

	 function getListApplication($param, $value) {
		$qry = "SELECT * FROM app_table WHERE $param = ?";
		$res = $this->getRecordset($qry, $value, $this->db2);
		if($res == null) {
			throw new Exception("No result", 1);
		}
		return $res;
	 }
	 function saveInputApplication() {
         $data = $this->input->post(NULL, TRUE);
         $arr = array(
             'app_id' => $data['app_id'],
             'app_name' => $data['app_name'],
             'app_url' => $data['app_url'],
             'status' => $data['status'],
             'createnm' => $this->username
         );
         $res = $this->db->insert('klink_mlm2010.dbo.app_table', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan user application..");
         }
         return $arr;
	 }


	 function saveUpdateApplication() {
        $data = $this->input->post(NULL, TRUE);
        $arr = array(
            'app_name' => $data['app_name'],
            'app_url' => $data['app_url'],
            'status' => $data['status']
        );
        $this->db->where('app_id', $data['app_id']);
        $res = $this->db->update('klink_mlm2010.dbo.app_table', $arr);
        if($res > 0) {
            $arr = jsonTrueResponse(null, "Berhasil memperbarui user application..");
        }
        return $arr;
	 }

	 function deleteApplication($id) {
	 	$qry = "DELETE FROM app_table WHERE app_id = ?";
		$query = $this->executeQuery2($qry, $id);
		if(!$query) {
			throw new Exception("Delete User failed..!!", 1);
		}
		return $query;
	 }

	 /*----------------
	 * MENU GROUP
	 * --------------*/
     function getListAllGroupMenu() {
	 	$qry = "SELECT
				  dbo.app_table.[app_name],
				  dbo.app_tabprg.app_id,
				  dbo.app_tabprg.app_menu_id,
				  dbo.app_tabprg.app_menu_desc,
				  dbo.app_tabprg.app_submenu_prefix,
				  dbo.app_tabprg.[status],
				  dbo.app_tabprg.menu_order
				FROM
				  dbo.app_tabprg
				  INNER JOIN dbo.app_table ON (dbo.app_table.app_id = dbo.app_tabprg.app_id)
				WHERE dbo.app_tabprg.app_menu_parent_id = '0'";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		if($res == null) {
			throw new Exception("Data Application is empty..!", 1);
		}
		return $res;
	 }

	 function getListGroupMenu($param, $value) {
		$qry = "SELECT * FROM app_tabprg WHERE $param = ?";
		$res = $this->getRecordset($qry, $value, $this->db2);
		if($res == null) {
			throw new Exception("No result", 1);
		}
		return $res;
	 }
	 function getMenuID($prefix = "RT") {

	 	$qry = "SELECT max(a.app_menu_id) as jum
				FROM app_tabprg a
				WHERE app_menu_id LIKE '$prefix%' ESCAPE '!'";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		$jumlah = substr($res[0]->jum, 2, 3);
		$next_id = $jumlah + 1;

    	$next_id = sprintf("%03s",$next_id);
    	$y =  strval($prefix.$next_id);
        return $y;
	 }

	 function saveInputGroupMenu($id) {
         $data = $this->input->post(NULL, TRUE);
         $arr = array(
            'app_id' => $data['app_id'],
            'app_menu_id' => $data['id'],
            'app_menu_submenu_prefix' => $data['app_submenu_prefix'],
            'app_menu_desc' => $data['app_menu_desc'],
            'app_menu_url' => '#',
            'status' => $data['status'],
            'createnm' => $this->username,
            'menu_order' => $data['menu_order']
         );
         $res = $this->db->insert('klink_mlm2010.dbo.app_tabprg', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan group menu..");
         }
         return $arr;
	 }

	 function updateAppIdOnSubMenu($data) {
        $arr = array(
            'app_id' => $data['app_id']
        );
        $this->db->where('app_menu_parent_id', $data['id']);
		$query = $this->executeQuery($qry);
		$res = $this->db->insert('klink_mlm2010.dbo.app_tabprg', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan group menu..");
         }
         return $arr;
	 }


	 function saveUpdateGroupMenu($data) {
        /* $qry = "UPDATE app_tabprg SET app_menu_desc = '$data[app_menu_desc]',
                    app_id = '$data[app_id]', status = '$data[status]',
                    menu_order = '$data[menu_order]'
                WHERE app_menu_id = '$data[id]'"; */
        $arr = array(
            'app_id' => $data['app_id'],
            'status' => $data['status'],
            'menu_order' => $data['menu)order']
        );
        $this->db->where('app_menu_id', $data['id']);
        $res = $this->db->update('klink_mlm2010.dbo.app_tabprg', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan group menu..");
         }
         return $arr;
	 }

	 function deleteGroupMenu($id) {
	 	$qry = "DELETE FROM app_tabprg WHERE app_menu_id = '$id'";
		//echo $qry;
		$query = $this->executeQuery($qry);
		if(!$query) {
			throw new Exception("Delete Group Menu failed..!!", 1);
		}
		return $query;
	 }

	 /*-----------------------
	  * SUB MENU
	  * ----------------------
	  */

	 function getListAllSubMenu() {
	  	 $qry = "SELECT
				  a.app_menu_id,
				  a.app_menu_desc,
				  b.app_menu_desc as group_menu,
				  a.menu_order
				 FROM
				  app_tabprg a
				  INNER JOIN app_tabprg b
				  ON (a.app_menu_parent_id = b.app_menu_id)";
		 $res = $this->getRecordset($qry, NULL, $this->db2);
		 if($res == null) {
			throw new Exception("Data Application is empty..!", 1);
		 }
		 return $res;
	 }

	 function getListSubMenu($param, $value) {
			$qry = "SELECT * FROM app_tabprg WHERE $param = ?";
			$res = $this->getRecordset($qry, $value, $this->db2);
			if($res == null) {
				throw new Exception("No result", 1);
			}
			return $res;
	 }
	 function saveInputSubMenu($menu_id, $data) {
		$qry = "INSERT INTO app_tabprg (app_id, app_menu_id, app_menu_parent_id, app_menu_desc, app_menu_url, status, createnm, menu_order)
		        VALUES ('$data[app_id]','$menu_id', '$data[app_menu_parent_id]', '$data[app_menu_desc]', '$data[app_menu_url]', '$data[status]',
		               '".$this->username."', '$data[menu_order]')";
        $arr = array(
            'app_id' => $data['app_id'],
            'app_menu_id' => $menu_id,
            'app_menu_parent_id' => $data['app_menu_parent_id'],
            'app_menu_desc' => $data['app_menu_desc'],
            'app_menu_url' => $data['app_menu_url'],
            'status' => $data['status'],
            'createnm' => $this->username,
            'menu_order' => $data['menu_order']
         );
         $res = $this->db->insert('klink_mlm2010.dbo.app_tabprg', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan group menu..");
         }
         return $arr;
	  }

	  function saveUpdateSubMenu($data) {
	  	$qry = "UPDATE app_tabprg SET app_menu_desc = '$data[app_menu_desc]',
	  	              app_menu_url = '$data[app_menu_url]',
	  	              menu_order = '$data[menu_order]',
		              status = '$data[status]',
		              app_menu_parent_id = '$data[app_menu_parent_id]'
                WHERE app_menu_id = '$data[id]'";
        $arr = array(
            'app_menu_desc' => $data['app_menu_desc'],
            'app_menu_url' => $data['app_menu_url'],
            'menu_order' => $data['menu_order'],
            'status' => $data['status'],
            'app_menu_parent_id' => $data['app_menu_parent_id']
        );
        $this->db->where('app_menu_id', $data['id']);
        $res = $this->db->update('klink_mlm2010.dbo.app_tabprg', $arr);
         if($res > 0) {
             $arr = jsonTrueResponse(null, "Berhasil menambahkan group menu..");
         }
         return $arr;
	  }



	  /*------------------------------
	 * ACCESS MENU
	 *---------------------------- */
	 function deleteUserAuthByID($groupid) {
	 	$qry = "DELETE FROM klink_mlm2010.dbo.ecomm_scoauth WHERE groupid = '$groupid'";
		//echo $qry;
		$query = $this->executeQuery($qry);

		return $query;
	 }

	 function saveInputAccessMenu($data) {
		$jum = count($data['menuid']);
		$res = 0;
		for($i = 0; $i < $jum; $i++) {
           if($data['menuid'][$i] != "") {
                /* if(isset($data['add'][$i])) {
                	$add = "1";
                } else {
                	$add = "0";
                }
				if(isset($data['edit'][$i])) {
                	$edit = "1";
                } else {
                	$edit = "0";
                }
				if(isset($data['view'][$i])) {
                	$view = "1";
                } else {
                	$view = "0";
                }
                if(isset($data['delete'][$i])) {
                	$delete = "1";
                } else {
                	$delete = "0";
                } */
                $qry2 = "INSERT INTO ecomm_scoauth (groupid, menuid, toggle_add, toggle_edit, toggle_delete, toggle_view, createnm)
                       VALUES ('$data[grpid]', '".$data['menuid'][$i]."', '1', '1', '1', '1', '".$this->username."')";
                $arr = array(
                    'groupid' => $data['grpid'],
                    'menuid' => $data['menuid'][$i],
                    'toggle_add' => "1",
                    'toggle_edit' => "1",
					'toggle_delete' => "1",
					'toggle_view' => "1",
                    'createnm' => $this->username
                );
                $query = $this->db->insert('klink_mlm2010.dbo.ecomm_scoauth', $arr);
				//echo $qry2;
				//echo "<br />";
				//$query2 = $this->db->query($qry2);
				// $query2 = $this->executeQuery($qry2);
				if($query < 0) {
					throw new Exception("Save User Access failed..!!", 1);
				} else {
					$res++;
				}
           }
        }

		if($res == $jum) {
			return true;
		} else {
			return false;
		}


	 }

	 function updatePassword($data) {
		$arr = array(
            'password' => $data['new_password'],
        );
        $this->db->where('username', $data['username']);
        $res = $this->db->update('klink_mlm2010.dbo.ecomm_user', $arr);

		$return = jsonFalseResponse("Password $data[username] gagal diubah..");
		if($res > 0) {
			$return = jsonTrueResponse(null, "Password $data[username] berhasil diubah..");
		}

		return $return;
	 }
}