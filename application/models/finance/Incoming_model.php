<?php
class Incoming_model extends MY_Model {
		
	function __construct() {
      // Call the Model constructor
      parent::__construct();
  }

  function getListBank() {
    $qry = "SELECT a.bankacccd, a.bankaccno, a.bankaccnm, a.bankdesc, a.bankshortdesc
            FROM klink_mlm2010.dbo.msbankacc a 
            WHERE a.bankacccd IN (
            'AI01',
            'AI02',
            'AI03',
            'B0002',
            'B0003',
            'B0015',
            'B0021',
            'B0022',
            'B0023',
            'B0026',
            'VC001'
            )";
    $result = $this->getRecordset($qry,null,$this->db2);
		return $result;
  }

  function getListIncomingPayment($param) {
    /* echo "<pre>";
    print_r($param);
    echo "</pre>"; */
    $and = "";
    if(array_key_exists("trcd", $param)) {
      $and .= $param['trcd'] !== "" ? " a.trcd = '$param[trcd]'" : "";
    } else {
      if($param['from'] !== "" && $param['to'] !== "") {
        $and .= " a.createnm <> 'ECOMMERCE' AND CONVERT(VARCHAR(10), a.etdt, 120) BETWEEN '$param[from]' AND '$param[to]'";
      }

      if($param['bankacccd'] !== "") {
        $and .= " AND a.bankacccd = '$param[bankacccd]'";
      }

      if($param['inc_status'] !== "") {
        $and .= " AND a.status = '$param[inc_status]'";
      }

      if($param['customertype'] !== "") {
        $and .= " AND a.custtype = '$param[customertype]'";
      }

      if($param['paramValue'] !== "") {
        $and .= " AND a.dfno = '$param[paramValue]'";
      }
    } 
    
    
    $qry = "SELECT a.trcd, a.refno, a.createnm, a.[description], 
            CONVERT(VARCHAR(10), a.createdt, 120) as createdt, 
            CONVERT(VARCHAR(10), a.trdt, 120) as trdt,
               a.amount, a.bankacccd, b.bankdesc, 
            CASE 
              WHEN a.custtype = 'M' THEN 'MEMBER'
              WHEN a.custtype = 'S' THEN 'STOCKIST'
              WHEN a.custtype = 'O' THEN 'OTHER'
            END AS tipe_cust,
            CASE 
            WHEN a.custtype = 'M' THEN d.dfno + ' / ' + d.fullnm
            WHEN a.custtype = 'S' THEN e.loccd + ' / ' + e.fullnm
            ELSE a.dfno
            END AS cust,
            CASE 
              WHEN a.status = 'O' THEN 'OPEN'
              WHEN a.status = 'H' THEN 'HOLD'
            END AS status, a1.balamt
            FROM klink_mlm2010.dbo.bbhdr a
            INNER JOIN klink_mlm2010.dbo.custpaybal a1 ON (a.trcd = a1.trcd)
            LEFT OUTER JOIN klink_mlm2010.dbo.msbankacc b on (a.bankacccd = b.bankacccd)
            LEFT OUTER JOIN klink_mlm2010.dbo.msmemb d ON (a.dfno = d.dfno AND a.custtype = 'M')
            LEFT OUTER JOIN klink_mlm2010.dbo.mssc e ON (a.dfno = e.loccd AND a.custtype = 'S')
            WHERE  $and";
    /* echo $qry; */
    /* if($this->username === "BID06") {
      echo "<pre>";
      print_r($qry);
      echo "</pre>";
    } */
    $result = $this->getRecordset($qry,null,$this->db2);
		return $result;
  }

  function checkBbhdr($param, $value) {
    $qry = "SELECT * FROM klink_mlm2010.dbo.bbhdr a WHERE a.$param = '$value'";
    $result = $this->getRecordset($qry,null,$this->db2);
    return $result;
  }

  function getVcipDetail($trcd) {

		// Voucher Cash Incoming Payment detai
    $param['trcd'] = $trcd;
		$res_b = $this->getListIncomingPayment($param);

		$query_c = "SELECT a.trcd, a.effect, a.dfno, a.createnm,
						CONVERT(VARCHAR(10), createdt, 120) AS createdt,
						a.trtype, a.applyto, a.idno, a.amount
					FROM custpaydet a
					WHERE a.applyto = '$trcd' ORDER BY a.effect, a.createdt";
		$res_c = $this->getRecordset($query_c, null, $this->db2);

		$custpaybal = "SELECT a.trcd, dfno, 
                   CASE 
                    WHEN a.custtype = 'M' THEN 'MEMBER'
                    WHEN a.custtype = 'S' THEN 'STOCKIST'
                    WHEN a.custtype = 'O' THEN 'OTHER'
                  END AS custtype, amount, balamt, createdt, 
                  CASE 
                   WHEN a.status = 'O' THEN 'OPEN'
                   WHEN a.status = 'H' THEN 'HOLD'
                  END AS status,
							CONVERT(VARCHAR(10), a.createdt, 120) AS updatedt, a.createnm, a.updatenm
					   FROM custpaybal a
					   WHERE a.trcd = '$trcd'";
		$resCustpaybal = $this->getRecordset($custpaybal, null, $this->db2);
		// end


		if ($res_b !== null) {
			
			// collect'em all as array
			$returnArr = array(
				"bbhdr" => $res_b,
				"custpaydet" => $res_c,
				"ip_bal" => $resCustpaybal
			);
      $return = jsonTrueResponse($returnArr);
		} else {
			$return = jsonFalseResponse("Incoming Payment Tidak ditemukan..");
		}
		return $return;
	}

  function getFullName($table, $field, $value) {
    $qry = "SELECT a.fullnm FROM klink_mlm2010.dbo.$table a WHERE a.$field = '$value'";
    return $this->getRecordset($qry, null, $this->db2);
  }

  function getIncomingNumber($bank) {

    $prefix = "IP".date("y").date("m").$bank;
    $qry = "SELECT * FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'"; 
    $res = $this->getRecordset($qry, null, $this->db2);
    if($res === null) {
      $ins = "INSERT klink_mlm2010.dbo.mssysno (prefix, lastno) VALUES ('$prefix', '2')";
      $resIns = $this->executeQuery($ins);
      if($resIns > 0) {
        $noawal = sprintf("%04s", 1);
        $pref2 = "IP".date("y").date("m");
        return $pref2.$noawal."/".$bank;
      }
    } else {
      $lastno = $res[0]->lastno;
      $upd = "UPDATE a SET a.lastno = a.lastno + 1 FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'"; 
      $resUpd = $this->executeQuery($upd);
      $noawal = sprintf("%04s", $lastno);
      $pref2 = "IP".date("y").date("m");
      return $pref2.$noawal."/".$bank;
    }
  }

  function saveIncomingPayment($data) {
    $incpay = $this->getIncomingNumber($data['kodebank']);
    //$incpay = "IPTES/01";
    $ins = "INSERT INTO klink_mlm2010.dbo.bbhdr (trcd, type, trtype, bankacccd, refno, 
        amount, createdt, etdt, trdt, status, 
        dfno, createnm, updatenm, updatedt, custtype, description, effect) VALUES (
          '$incpay', 'I', 'IP', '$data[kodebank]', '$data[inc_refno]', 
          '$data[amount]', '$data[tgl_input]', '$data[tgl_input]', '$data[tgl_mutasi]', '$data[inc_status]', 
          '$data[dfno]', '".$this->username."', '".$this->username."', '$data[tgl_input]', '$data[customer_type]', '$data[inc_remark]', '')";
    $resUpd = $this->executeQuery($ins);

    if($resUpd > 0) {
      $hasil = $this->checkBbhdr("trcd", $incpay);
      if($hasil !== null) {
        $trcd = $hasil[0]->trcd;
        return jsonTrueResponse($hasil, "Incoming Payment berhasil di create : $trcd");
      } else {
        return jsonFalseResponse($hasil, "Incoming Payment gagal disimpan..");
      }
    } 

    return jsonFalseResponse($hasil, "Incoming Payment gagal disimpan..");
  }
}