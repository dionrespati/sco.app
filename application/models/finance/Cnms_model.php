
<?php
class Cnms_model extends MY_Model {
		
	function __construct() {
      // Call the Model constructor
      parent::__construct();
      $this->tbl_register = "klink_mlm2010.dbo.ordivhdr";
      $this->tbl_transaksi = "klink_mlm2010.dbo.ordivtrh";
      $this->tbl_prod = "klink_mlm2010.dbo.ordivtrd";
  }

  function getCurrentPeriod() // dipake
    {
        $qry = "SELECT 
            CONVERT(VARCHAR(10), DATEADD(month, -1, a.currperiod), 120) as prevperiod,
            CONVERT(VARCHAR(10), a.currperiod, 120) as lastperiod,
            CONVERT(VARCHAR(10), DATEADD(month, 1, a.currperiod), 120) as nextperiod
            from klink_mlm2010.dbo.syspref a";
            $res = $this->getRecordset($qry, null, $this->db2);
        return $res;
	}

  function getCNheader($param, $no_cn) {
		$ordivTrh = "SELECT TOP 1 a.trcd, a.ordtype, a.trtype, a.dfno,
						a.loccd, a.docno, a.receiptno, a.flag_paid,
						CONVERT(VARCHAR(10), a.bnsperiod, 120) AS bnsperiod,
						CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
						CONVERT(VARCHAR(10), a.registerdt, 120) AS registerdt,
						a.tdp, a.tbv, a.registerno,
						b.trcd as no_kw, CONVERT(VARCHAR(10), b.etdt, 120) AS kwdt,
						b.createnm as kw_createnm,
						c.GDO,
						CONVERT(VARCHAR(10), d.etdt, 120) AS GDOdt, d.createnm as GDO_createnm,
						a.batchscno as batchno, CONVERT(VARCHAR(10), a1.batchdt, 120) AS batchdt,
						a.createnm, a2.onlinetype, a.note, a.remarks,
						f.NO_DO as do_wms,
						f.CREATED_BY as do_wms_create_by,
						CONVERT(VARCHAR(10), f.CREATED_DATE, 120) AS do_wms_create_dt,
						f.ID_STOCKIES as sent_to, 
						g.WAREHOUSE_NAME as sent_from
					FROM klink_mlm2010.dbo.ordivtrh a
					LEFT OUTER JOIN klink_mlm2010.dbo.ordivhdr a2 ON (a.registerno = a2.trcd)
					LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh a1 ON (a.invoiceno = a1.csno)
					LEFT OUTER JOIN klink_mlm2010.dbo.billivhdr b ON (a.registerno = b.applyto)
					LEFT OUTER JOIN klink_mlm2010.dbo.intrh c ON (b.trcd = c.applyto)
					LEFT OUTER JOIN klink_mlm2010.dbo.gdohdr d ON (c.GDO = d.trcd)
					LEFT OUTER JOIN klink_whm.dbo.T_DETAIL_DO e 
					ON (b.trcd COLLATE SQL_Latin1_General_CP1_CI_AS = e.NO_KWITANSI)
					LEFT OUTER JOIN klink_whm.dbo.T_DO f ON (e.ID_DO = f.ID_DO)
					LEFT OUTER JOIN klink_whm.dbo.MASTER_WAREHOUSE g ON (f.ID_WAREHOUSE = g.ID_WAREHOUSE)
					WHERE $param = '$no_cn'";
		//echo $ordivTrh;
		/* if($this->username == "DION") {
			echo "<pre>";
			print_r($ordivTrh);
			echo "</pre>";
		} */
		$resHead = $this->getRecordset($ordivTrh, null, $this->db2);
		return $resHead;
	}

  function getCNSumProductCheck($no_cn, $ol_type) {
		if($ol_type === "M") {
			/* $ordivrtd = "SELECT a.prdcd, b.prdnm, a.qtyord, a.qtyremain, a.qtyship, c.dp, c.bv
					 FROM ordivtrd a
					 LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
					 LEFT OUTER JOIN pricetab c ON (a.prdcd = c.prdcd and a.pricecode = c.pricecode)
					 WHERE a.invoiceno = '$no_cn'"; */
			/* $ordivtrd = "SELECT a.*, d.prdcd as prd_ttp, ISNULL(SUM(d.qtyord), 0) as jum_input
								FROM klink_mlm2010.dbo.REKAP_PRD_ORDIVTRD a 
								LEFT OUTER JOIN klink_mlm2010.dbo.newtrh c ON (a.invoiceno = c.trcd2)
								LEFT OUTER JOIN klink_mlm2010.dbo.newtrd d ON (c.trcd = d.trcd and a.prdcd = d.prdcd)
								WHERE a.invoiceno = '$no_cn'
								GROUP BY a.prdcd, a.prdnm, a.qtyord, 
								a.qtyremain, a.qtyship, a.dp, a.bv, a.invoiceno, d.prdcd"; */
			$ordivtrd = "SELECT a.*, b.prdcd as prd_ttp, ISNULL(b.jum, 0) as jum_input
									FROM klink_mlm2010.dbo.REKAP_PRD_ORDIVTRD a
									LEFT OUTER JOIN klink_mlm2010.dbo.REKAP_NEWTRH_NEWTRD_BYTRCD2 b
										ON (a.prdcd = b.prdcd AND a.invoiceno = b.trcd2)
									WHERE a.invoiceno = '$no_cn'";
			$resDetail = $this->getRecordset($ordivtrd, null, $this->db2);
			return $resDetail;

		} else {
			/* $ordivtrd = "SELECT a.*, d.prdcd as prd_ttp, ISNULL(SUM(d.qtyord), 0) as jum_input
								FROM klink_mlm2010.dbo.REKAP_PRD_ORDIVTRD a 
								LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh c ON (a.invoiceno = c.trcd2)
								LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd d ON (c.trcd = d.trcd and a.prdcd = d.prdcd)
								WHERE a.invoiceno = '$no_cn'
								GROUP BY a.prdcd, a.prdnm, a.qtyord, 
								a.qtyremain, a.qtyship, a.dp, a.bv, a.invoiceno, d.prdcd"; */
			$ordivtrd = "SELECT a.*, b.prdcd as prd_ttp, ISNULL(b.jum, 0) as jum_input
									FROM klink_mlm2010.dbo.REKAP_PRD_ORDIVTRD a
									LEFT OUTER JOIN klink_mlm2010.dbo.REKAP_SCNEWTRH_SCNEWTRD_BYTRCD2 b
											ON (a.prdcd = b.prdcd AND a.invoiceno = b.trcd2)
									WHERE a.invoiceno = '$no_cn'";
			$resDetail = $this->getRecordset($ordivtrd, null, $this->db2);
			return $resDetail;
		}
		
		//$resDetail = $this->getRecordset($ordivrtd, null, $this->db2);
		//return $resDetail;
	}

  function listTtpById($field, $value) {
		$qry = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm,
				   a.totpay, a.tdp, a.tbv, CONVERT(VARCHAR(10),a.bnsperiod, 121) as bnsperiod, a.no_deposit
				 FROM klink_mlm2010.dbo.sc_newtrh a
				 INNER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
				 WHERE a.$field = '$value'
				 ORDER BY trcd ASC";
		$hasil1 = $this->getRecordset($qry, null, $this->db2);
		if($hasil1 == null) {
			$qry = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm,
				   a.totpay, a.tdp, a.tbv, CONVERT(VARCHAR(10),a.bnsperiod, 121) as bnsperiod, '' as no_deposit
				 FROM klink_mlm2010.dbo.newtrh a
				 INNER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
				 WHERE a.$field = '$value'
				 ORDER BY trcd ASC";
			$hasil1 = $this->getRecordset($qry, null, $this->db2);
		}
		return $hasil1;
	}

  function getIncPayByCnno($no_cn) {
		$qry = "SELECT a.trcd, a.docno, a.payamt, b.[description] as pay_desc, d.bankdesc,
				d.bankaccnm, d.bankaccno
				FROM klink_mlm2010.dbo.ordivtrp a
				LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON (a.paytype = b.id)
				LEFT OUTER JOIN klink_mlm2010.dbo.bbhdr c ON (a.docno = c.trcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.msbankacc d ON (c.bankacccd = d.bankacccd)
				WHERE a.trcd = '$no_cn'";
		$resInc = $this->getRecordset($qry, null, $this->db2);
		return $resInc;
	}

  public function searchRegisterByParam($param) {
    $where = "";
    if($param['searchby'] !== "" && ($param['searchby'] == "registerno" || $param['searchby'] == "invoiceno")) {
      if($param['searchby'] == "registerno") {
        $where .= " a.trcd = '$param[paramValue]'"; 
      } else {
        $where .= " c1.invoiceno = '$param[paramValue]'"; 
      }
    } else {
      $where = " CONVERT(VARCHAR(10), a.registerdt, 120) BETWEEN '$param[from]' AND '$param[to]' ";
      if($param['searchby'] !== "" && $param['searchby'] == "loccd") {
        $where .= " AND a.dfno = '$param[paramValue]'"; 
      }

      if($param['online'] !== "") {
        if($param['online'] === "ol") {
          $where .= " AND LEFT(c1.invoiceno, 3) IN ('CNE', 'CNP', 'MME')";
        } else if($param['online'] === "stk") {
          $where .= " AND LEFT(c1.invoiceno, 3) NOT IN ('CNE', 'CNP', 'MME')";
        }
      }
    }
    


    

    /* if($param['online'] !== "") {
      if($param['online'] === "stk") {

      }
      $where .= " AND a.trcd = '$param[paramValue]'"; 
    } */

    $qry = "SELECT a.trcd, a.tdp, a.tbv, 
              CONVERT(VARCHAR(10), a.registerdt, 120) as registerdt, 
              a.dfno, c.fullnm as nama_stockist, d.trcd as kw_no,
              ISNULL(COUNT(c1.invoiceno), 0) as tot_invoice 
            FROM klink_mlm2010.dbo.ordivhdr a 
            LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON (a.dfno = c.loccd)
            LEFT OUTER JOIN klink_mlm2010.dbo.ordivtrh c1 ON (a.trcd = c1.registerno)
            LEFT OUTER JOIN klink_mlm2010.dbo.billivhdr d ON (a.trcd = d.applyto)
            WHERE $where
            GROUP BY a.trcd, a.tdp, a.tbv, 
            CONVERT(VARCHAR(10), a.registerdt, 120), 
            c.fullnm, a.dfno, c.fullnm, d.trcd";

    if($this->username == "BID06" || $this->username == "DION") {
      /* echo "<pre>";
      print_r($qry);
      echo "</pre>"; */
    }

    return $this->getRecordset($qry, null, $this->db2);
  }

  function getRegisterNo() {

    $prefix = date("y").date("m").date("d");
    $qry = "SELECT * FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'"; 
    $res = $this->getRecordset($qry, null, $this->db2);
    if($res === null) {
      $ins = "INSERT klink_mlm2010.dbo.mssysno (prefix, lastno) VALUES ('$prefix', '2')";
      $resIns = $this->executeQuery($ins);
      if($resIns > 0) {
        $noawal = sprintf("%04s", 1);
        return $prefix.$noawal;
      }
    } else {
      $lastno = $res[0]->lastno;
      $upd = "UPDATE a SET a.lastno = a.lastno + 1 FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'"; 
      $resUpd = $this->executeQuery($upd);
      $noawal = sprintf("%04s", $lastno);
      return $prefix.$noawal;
    }
  }

  public function getDataStockist($loccd) {
    $qry = "SELECT a.loccd, a.fullnm, a.pricecode 
            FROM mssc a WHERE a.loccd = '$loccd'";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function saveRegister($data) {

    

    $stk = $this->getDataStockist($data['stk']);
    if($stk !== null) {
      if($stk[0]->pricecode === "" || $stk[0]->pricecode === null) {
        return jsonFalseResponse("Pricecode Stockist tidak ada");
      }
      $data['pricecode'] = $stk[0]->pricecode;
    } else {
      return jsonFalseResponse("Kode Stokis invalid / salah..");
    }

    $noregister = $this->getRegisterNo();
    //$noregister = "220401TESDION";
    $shipto = "";
    if($data['ship'] == "2") {
      $shipto = "001";
    }
    
    $ordhdr = array(
      "dfno" => $data['stk'],
      "bnsperiod" => $data['bonusmonth'],
      "loccd" => $data['stk'],
      "pricecode" => $data['pricecode'],
      "trcd" => $noregister,
      "registerno" => $noregister,
      "ship" => $data['ship'], //1 or 2
      "shipto" => $shipto,
      "whcd" => $data['whcd'], //WH001
      "createnm" => $this->username,
      "updatenm" => $this->username,
      "branch" => "B001",
      "onlinetype" => $data['onlinetype']
    );

    /* echo "<pre>";
    print_r($ordhdr);
    echo "</pre>"; */

    $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
    $res = $dbqryx->insert($this->tbl_register, $ordhdr);
    if($res > 0) {
      $arr = array(
        "registerno" => $noregister
      );
      return jsonTrueResponse($arr, "Register berhasil disimpan, No Register : $noregister");
    } else {
      return jsonFalseResponse("Simpan register gagal..");
    }

    //getDataRegisterByID
    
  }

  public function getFullDataRegisterByID($registerno) {
    $qry = "SELECT a.trcd, a.dfno, CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod, 
              a.pricecode, a.whcd, a.ship, a.shipto, 
            CONVERT(VARCHAR(10), a.registerdt, 120) as registerdt, a.branch,
            COUNT(b.invoiceno) as jum_invoice
            FROM klink_mlm2010.dbo.ordivhdr a 
            LEFT OUTER JOIN klink_mlm2010.dbo.ordivtrh b ON (a.trcd = b.registerno)
            WHERE a.trcd = '$registerno'
            GROUP BY a.trcd, a.dfno, a.bnsperiod, a.pricecode, a.whcd, a.ship, a.shipto, a.branch,
            CONVERT(VARCHAR(10), a.registerdt, 120)";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function getDataRegisterByID($registerno) {
    $qry = "SELECT a.trcd, a.tdp, a.tbv, 
              CONVERT(VARCHAR(10), a.registerdt, 120) as registerdt, 
              a.dfno, b.fullnm,
              a.pricecode, e.description as pricecode_desc, a.ship, 
              CASE  
              WHEN a.ship = '1' THEN 'Pick Up'
              WHEN a.ship = '2' THEN 'Ship To'
              WHEN a.ship = '3' THEN 'Hold'
              WHEN a.ship = '4' THEN 'Dont Ship'
              END AS ship_desc, 
              a.whcd, d.fullnm  as whnm, a.createnm,  a.onlinetype,
              CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod
            FROM klink_mlm2010.dbo.ordivhdr a 
            LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.dfno = b.loccd)
            LEFT OUTER JOIN klink_mlm2010.dbo.mssc d ON (a.whcd = d.loccd)
            LEFT OUTER JOIN klink_mlm2010.dbo.pricecode e ON (a.pricecode = e.code)
            WHERE a.trcd = '$registerno'";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function listCNDariRegister($registerno) {
    $qry = "SELECT a.invoiceno, CONVERT(VARCHAR(10), a.invoicedt, 120) as invoicedt, 
             CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod, a.tdp, a.dfno, a.batchscno, a.receiptno
            FROM klink_mlm2010.dbo.ordivtrh a
            WHERE a.registerno = '$registerno'";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function getDataCN($invoiceno) {
    $qry = "SELECT a.invoiceno, a.registerno, a.tdp, SUM(b.qtyord) as tot_prd, SUM(c.payamt) as tot_pay, d.trcd
            FROM klink_mlm2010.dbo.ordivtrh a
            INNER JOIN klink_mlm2010.dbo.ordivtrd b ON (a.invoiceno = b.invoiceno)
            INNER JOIN klink_mlm2010.dbo.ordivtrp c ON (a.invoiceno = c.trcd)
            INNER JOIN klink_mlm2010.dbo.newivtrh d ON (a.invoiceno = d.trcd)
            WHERE a.invoiceno = '$invoiceno'
            GROUP BY a.invoiceno, a.registerno, a.tdp, d.trcd";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function listSSRBelumJadiCn($kodestk, $bnsperiod) {
    $qry = "SELECT 
              a.batchno,a.loccd, CONVERT(VARCHAR(10), a.bnsperiod, 120),
              a.sc_dfno, b.fullnm as nama_stk, a.pricecode,
              ISNULL(SUM(a.tbv), 0) as BV, 
              ISNULL(SUM(a.tdp), 0) as DP, 
              ISNULL(SUM(c.payamt), 0) as vch_cash, 
              c.trcd2 as incpay_vchcash 
            FROM klink_mlm2010.dbo.sc_newtrh a 
            LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.sc_dfno = b.loccd)
            LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrp_vc_det c ON (a.batchno = c.trcd AND c.paytype = '08')
            WHERE (a.csno is null or a.csno = '') 
            AND CONVERT(VARCHAR(10), a.bnsperiod, 120) = '$bnsperiod'  
            AND a.batchno is not NULL 
            AND a.batchno <> '' 
            AND LEFT(a.batchno, 2) NOT LIKE 'MM%'
            AND a.loccd = '$kodestk'
            GROUP BY a.loccd, CONVERT(VARCHAR(10), a.bnsperiod, 120), a.batchno,
            a.loccd,a.sc_dfno, a.pricecode, b.fullnm, c.trcd2
            ORDER BY b.fullnm";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function listSSRBelumJadiCnV2($kodestk, $bnsperiod) {
    $qry = "SELECT 
              a.batchno,a.loccd, CONVERT(VARCHAR(10), a.bnsperiod, 120),
              a.sc_dfno, b.fullnm as nama_stk, a.pricecode,
              ISNULL(SUM(a.tbv), 0) as BV, 
              ISNULL(SUM(a.tdp), 0) as DP
              --ISNULL(SUM(c.payamt), 0) as vch_cash, 
              --c.trcd2 as incpay_vchcash 
            FROM klink_mlm2010.dbo.sc_newtrh a 
            LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.sc_dfno = b.loccd)
            WHERE (a.csno is null or a.csno = '') 
            AND CONVERT(VARCHAR(10), a.bnsperiod, 120) = '$bnsperiod'  
            AND a.batchno is not NULL 
            AND a.batchno <> '' 
            --AND a.ttptype <> 'MEMB'
            AND a.loccd = '$kodestk'
            GROUP BY a.loccd, CONVERT(VARCHAR(10), a.bnsperiod, 120), a.batchno,
            a.loccd,a.sc_dfno, a.pricecode, b.fullnm--, c.trcd2
            ORDER BY b.fullnm";
    /* echo "<pre>";        
    echo $qry;
    echo "</pre>"; */
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function checkIfAnyVoucherCashPrd($batchno) {
    $prefix = substr($batchno, 0, 3);
    $prefix2 = substr($batchno, 0, 6);
    if($prefix === "PVR" || $prefix2 == "PVMDCR") {
      $qry = "SELECT a.trcd, 
                  '12' as paytype, 'Product Voucher BB' as payDesc, 
                  a.amount, a.dfno, b.balamt,
                  (
                    SELECT SUM(d.payamt) as total_cash 
                    FROM klink_mlm2010.dbo.sc_newtrp d 
                    INNER JOIN klink_mlm2010.dbo.sc_newtrh c 
                      ON (c.batchno = '$batchno' and c.trcd = d.trcd AND d.paytype = '01')
                  ) as total_csh    
              FROM klink_mlm2010.dbo.bbhdr a 
              LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd = b.trcd)
              WHERE a.refno LIKE '$batchno%'";
      //echo $qry;
      return $this->getRecordset($qry, null, $this->db2);
    } else {
      /* $qry = "SELECT a.trcd2 as trcd, 
                '08' as paytype, 
                'Cash Voucher' as payDesc,
                SUM(a.payamt) as amount, a1.dfno, b.balamt
              FROM klink_mlm2010.dbo.sc_newtrp_vc_det a
              LEFT OUTER JOIN klink_mlm2010.dbo.bbhdr a1 ON (a.trcd2 = a1.trcd)
              LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd2 = b.trcd)
              WHERE a.trcd = '$batchno' and a.paytype = '08'
              GROUP BY a.trcd2, b.balamt, a1.dfno"; */
      $qry = "SELECT a.trcd, '08' as paytype, 'Cash Voucher' as payDesc,
              SUM(b.payamt) as amount, a1.dfno, a.balamt, SUM(c1.payamt) as total_csh
              FROM klink_mlm2010.dbo.custpaybal a
              LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrp_vc_det b ON (a.trcd = b.trcd2 AND b.paytype = '08')
              LEFT OUTER JOIN klink_mlm2010.dbo.bbhdr a1 ON (a.trcd = a1.trcd)
              LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrp_vc_det c1 ON (a.trcd = c1.trcd2 AND c1.paytype = '01')
              WHERE b.trcd = '$batchno'
              GROUP BY a.trcd, a.balamt, a1.dfno";        
      return $this->getRecordset($qry, null, $this->db2);
    }
  }

  public function rekapProdukSSR($batchno) {
    $qry = "SELECT a.prdcd, b.prdnm, 
              SUM(a.qtyord) AS total_qty, 
              c.dp, c.bv,
              SUM(a.qtyord) * c.dp as total_dp, 
              SUM(a.qtyord) * c.bv as total_bv,
              a1.sc_dfno, a1.loccd, ax.pricecode
            FROM klink_mlm2010.dbo.sc_newtrd a
            LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh a1 ON (a.trcd = a1.trcd)
            INNER JOIN klink_mlm2010.dbo.mssc ax ON (a1.loccd = ax.loccd)
            LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
            LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c 
              ON (a.prdcd = c.prdcd AND ax.pricecode = c.pricecode)
            WHERE a1.batchno = '$batchno'
            GROUP BY a.prdcd, b.prdnm, c.dp, c.bv, a1.sc_dfno, a1.loccd, ax.pricecode";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function rekapSSR($batchno) {
    $qry = "SELECT SUM(a.tdp) as total_dp, SUM(a.tbv) as total_bv
            FROM klink_mlm2010.dbo.sc_newtrh a
            WHERE a.batchno = '$batchno'";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function listIncPayByID($idmember) {
    $qry = "SELECT a.trcd, a.dfno , a.[type], a.custtype , 
              a.status , a.createnm , a.updatenm , b.amount, b.balamt,
              a1.effect, CONVERT(VARCHAR(10), a.createdt, 120) as createdt 
            FROM klink_mlm2010.dbo.bbhdr a 
            INNER JOIN klink_mlm2010.dbo.custpaydet a1 ON (a.trcd = a1.applyto AND a1.effect = '+')
            LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd = b.trcd )
            WHERE a.dfno = '$idmember' 
            AND a1.custtype = 'S'
            AND a.status = 'O' and b.balamt > 0
            AND a.createnm <> 'ECOMMERCE'
            ORDER BY a.createdt DESC";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function getIncomingByID($trcd, $dfno) {
    $qry = "SELECT a.trcd, a.dfno , a.[type], a.custtype , 
              a.status , a.createnm , a.updatenm , b.amount, b.balamt,
              a1.effect, CONVERT(VARCHAR(10), a.createdt, 120) as createdt 
            FROM klink_mlm2010.dbo.bbhdr a 
            INNER JOIN klink_mlm2010.dbo.custpaydet a1 ON (a.trcd = a1.applyto AND a1.effect = '+')
            LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd = b.trcd )
            WHERE a.trcd = '$trcd' AND a.dfno = '$dfno'";
    return $this->getRecordset($qry, null, $this->db2);
  }

  public function listIncPayByIDV2($idmember, $exclude) {
    $and = "";
    if($exclude !== "") {
      $and .= " AND a.trcd NOT IN ($exclude)";
    }
    /* $qry = "SELECT a.trcd, a.dfno , a.[type], a.custtype , 
              a.status , a.createnm , a.updatenm , b.amount, b.balamt,
              a1.effect 
            FROM klink_mlm2010.dbo.bbhdr a 
            INNER JOIN klink_mlm2010.dbo.custpaydet a1 ON (a.trcd = a1.applyto AND a1.effect = '+')
            LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd = b.trcd )
            WHERE a.dfno = '$idmember' 
            AND a1.custtype = 'M'
            AND a.status = 'O' and b.balamt > 0 $and"; */

    $qry = "SELECT 
                x.trcd, x.dfno , x.[type], x.custtype , 
                x.status , x.createnm , x.updatenm , x.amount, 
                x.effect,
                x.amount - ISNULL(SUM(a2.amount), 0) as balamt
            FROM   
            (
              SELECT a.trcd, a.dfno , a.[type], a.custtype , 
                  a.status , a.createnm , a.updatenm , b.amount, 
                  a1.effect
                FROM klink_mlm2010.dbo.bbhdr a 
              INNER JOIN klink_mlm2010.dbo.custpaydet a1 ON (a.trcd = a1.applyto AND a1.effect = '+')
              LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd = b.trcd )
              WHERE a.dfno = '$idmember' 
              AND a1.custtype = 'S'
              AND a.status = 'O' and b.amount > 0 AND a.createnm <> 'ECOMMERCE' $and
            ) x    
            LEFT OUTER JOIN klink_mlm2010.dbo.custpaydet a2 ON (x.trcd = a2.applyto AND a2.effect = '-')
            GROUP BY x.trcd, x.dfno , x.[type], x.custtype , 
                x.status , x.createnm , x.updatenm , x.amount, 
                x.effect
            HAVING (x.amount - ISNULL(SUM(a2.amount), 0) > 0)";
    //echo $qry;
    return $this->getRecordset($qry, null, $this->db2);
  }

  function getDocNo($bnsperiod, $sc_dfno) {
    $date= date_create($bnsperiod);
    $thn = date_format($date,"y");
    $bln = date_format($date,"m");

    $prefix = "BN".$thn.$bln.$sc_dfno;

    $qry = "SELECT * FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'";
    $res = $this->getRecordset($qry, null, $this->db2);
    $noawal = "";
    if($res === null) {
      $ins = "INSERT INTO klink_mlm2010.dbo.mssysno (prefix, lastno) VALUES ('$prefix', '2')";
      $resIns = $this->executeQuery($ins);
      $noawal = sprintf("%02d", 1);
    } else {
      $upd = "UPDATE a SET a.lastno = a.lastno + 1 FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'";
      $resIns = $this->executeQuery($upd);
      $lastno = $res[0]->lastno;
      /* if($lastno < 10) {
        $noawal = sprintf("%02d", $lastno);
      } else {
        $noawal = $lastno;
      } */
      $noawal = sprintf("%02d", $lastno);
    }

    $docno = "BN".$thn.$bln."/".$noawal."/".$sc_dfno;
    return $docno;
  }

  function getCNNo($sc_dfno) {
    $check = "SELECT a.loccd, a.sctype FROM mssc a WHERE a.loccd = '$sc_dfno'";
    $res = $this->getRecordset($check, null, $this->db2);
    if($res === null) {
      return null;
    }

    $sc_type = $res[0]->sctype;
    if($sc_type === "1" || $sc_type === "2") {
      $awalan = "CN";
    } else if($sc_type === "3") {
      $awalan = "MS";
    } else if($sc_type === "3") {
      $awalan = "MDC";
    }

    $thn = date("y");
    $bln = date("m");
    $prefix = $awalan.$thn.$bln;

    $qry = "SELECT * FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'";
    $res = $this->getRecordset($qry, null, $this->db2);

    $noawal = "";
    if($res === null) {
      $ins = "INSERT INTO klink_mlm2010.dbo.mssysno (prefix, lastno) VALUES ('$prefix', '2')";
      $resIns = $this->executeQuery($ins);
      $noawal = sprintf("%06d", 1);
    } else {
      $upd = "UPDATE a SET a.lastno = a.lastno + 1 FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'";
      $resIns = $this->executeQuery($upd);
      $lastno = $res[0]->lastno;
      $noawal = sprintf("%06d", $lastno);
      //echo "no awal :".$noawal; 
    }

    $cnno = $awalan.$thn.$bln.$noawal;
    return $cnno;
  }

  function getNoTrxWithPrefix($prefix) {
    
    $thn = date("y");
    $bln = date("m");
    $prefix = $prefix.$thn.$bln;

    $qry = "SELECT * FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'";
    $res = $this->getRecordset($qry, null, $this->db2);

    $noawal = "";
    if($res === null) {
      $ins = "INSERT INTO klink_mlm2010.dbo.mssysno (prefix, lastno) VALUES ('$prefix', '2')";
      $resIns = $this->executeQuery($ins);
      $noawal = sprintf("%06d", 1);
    } else {
      $upd = "UPDATE a SET a.lastno = a.lastno + 1 FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$prefix'";
      $resIns = $this->executeQuery($upd);
      $lastno = $res[0]->lastno;
      $noawal = sprintf("%06d", $lastno);
      //echo "no awal :".$noawal; 
    }

    $cnno = $prefix.$noawal;
    return $cnno;
  }
  
  function getInfoSSR($nossr) {
    $qry = "SELECT TOP 1 a.batchno, a.sc_dfno, a.sc_co, a.loccd, a.csno 
            FROM klink_mlm2010.dbo.sc_newtrh a WHERE a.batchno = '$nossr'";
    $res = $this->getRecordset($qry, null, $this->db2);
    return $res;
  }

  function saveCNMS($data) {

    $register = $this->getFullDataRegisterByID($data['registerno']);
    $registerno = $register[0]->trcd;
    $pricecode = $register[0]->pricecode;
    $bnsperiod = $register[0]->bnsperiod;
    $whcd = $register[0]->whcd;
    $next_seq = $register[0]->jum_invoice + 1;
    $ship = $register[0]->ship;
    $shipto = $register[0]->shipto;
    $registerdt = $register[0]->registerdt;
    $branch = $register[0]->branch;

    $infossr = $this->getInfoSSR($data['nossr']);
    if($infossr === null) {
      return jsonFalseResponse("No $data[nossr] tidak valid..");
    }

    if($infossr[0]->csno !== null && $infossr[0]->csno !== "") {
      $nocnms = $infossr[0]->csno;
      return jsonFalseResponse("No $data[nossr] sudah digenerate menjadi $nocnms");
    }

    $data['sc_dfno'] = $infossr[0]->sc_dfno;
    $data['sc_co'] = $infossr[0]->sc_co;
    $data['stk'] = $infossr[0]->loccd;

    

    if(substr($data['nossr'], 0, 3) === "PVR" || substr($data['nossr'], 0, 6) === "PVMDCR") {
      $invoiceno = $this->getNoTrxWithPrefix("PV");
      $ordtype = "P";
      $docno = "";
      $data['tbv'] = 0;
      $category = "1";
    } else if(substr($data['nossr'], 0, 3) === "MMS") {
      $invoiceno = $this->getNoTrxWithPrefix("MM");
      $docno = "";
      $category = "5";
      $ordtype = "5";
    } else {
      $invoiceno = $this->getCNNo($data['sc_dfno']);
      $ordtype = "2";
      $docno = $this->getDocNo($bnsperiod, $data['sc_dfno']);
      $category = "1";
    }

    
    $tgl = date("Y-m-d");
    

    /* $invoiceno = "CN2204003TES";
    $docno = "BN2204/TE3/".$data['sc_dfno'];
    $ordtype = "2"; */
    

    $dbqryx  = $this->load->database("klink_mlm2010", TRUE);

    
    $ordivtrh = array(
      "ordtype" => $ordtype,
      "dfno" => $data['sc_dfno'],
      "sc_dfno" => $data['sc_co'],
      "loccd" => $data['stk'],
      "seq" => $next_seq,
      "invoiceno" => $invoiceno,
      "bnsperiod" => $bnsperiod,
      "pricecode" => $pricecode,
      "ship" => $ship,
      "shipto" => $shipto,
      "registerno" => $registerno,
      "registerdt" => $registerdt,
      "branch" => $branch,
      "whcd" => $whcd,
      "tpv" => $data['tbv'],
      "tbv" => $data['tbv'],
      "tdp" => $data['tdp'],
      "npv" => $data['tbv'],
      "nbv" => $data['tbv'],
      "ndp" => $data['tdp'],
      "createnm" => $this->username,
      "updatenm" => $this->username,
      "trcd" => $invoiceno,
      "trtype" => "SA0",
      "trdt" => $tgl,
      "totpay" => $data['tdp'],
      "docno" => $docno,
      "applyto" => $invoiceno,
      "category" => $category,
      "batchscno" => $data['nossr']
    );
    $dbqryx->insert("klink_mlm2010.dbo.ordivtrh", $ordivtrh);


    $rekap_prd = $this->rekapProdukSSR($data['nossr']);
    foreach($rekap_prd as $dta) {
      $ordivtrd = array(
        "registerno" => $registerno,
        "invoiceno" => $invoiceno,
        "prdcd" => $dta->prdcd,
        "qtyord" => $dta->total_qty,
        "qtyship" => 0,
        "qtyremain" => 0,
        "dp" => $dta->dp,
        "pv" => $dta->bv,
        "bv" => $dta->bv,
        "pricecode" => $dta->pricecode,
        "taxrate" => 0,
        "promocd" => "APLIBARU",
        "indexfree" => 1
      );

      $dbqryx->insert("klink_mlm2010.dbo.ordivtrd", $ordivtrd);


      $newivtrd = array(
        "trcd" => $invoiceno,
        //"invoiceno" => $invoiceno,
        "prdcd" => $dta->prdcd,
        "qtyord" => $dta->total_qty,
        "qtyship" => 0,
        "qtyremain" => 0,
        "dp" => $dta->dp,
        "pv" => $dta->bv,
        "bv" => $dta->bv,
        "pricecode" => $dta->pricecode,
        "taxrate" => 0,
        "promocd" => "APLIBARU",
        "indexfree" => 0
      );

      $dbqryx->insert("klink_mlm2010.dbo.newivtrd", $newivtrd);
      
      /* echo "<pre>";
      print_r($ordivtrd);
      echo "</pre>"; */
    }

    $seqa = 1;
    for($i = 0; $i < count($data['byrPayType']); $i++) {
      $ordivtrp = array(
        "trcd" => $invoiceno,
        "seqno" => $seqa,
        "paytype" => $data['byrPayType'][$i],
        "docno" => $data['byrIncPay'][$i],
        "payamt" => $data['byrAmount'][$i],
        "deposit" => 0,
        "trcd2" => $registerno,
      );
      $dbqryx->insert("klink_mlm2010.dbo.ordivtrp", $ordivtrp);
      

      $ordivdetp = array(
        "trcd" => $registerno,
        "paytype" => $data['byrPayType'][$i],
        "seqno" => $seqa, //urutan
        "docno" => $data['byrIncPay'][$i],
        "payamt" => $data['byrAmount'][$i]
       );
       $dbqryx->insert("klink_mlm2010.dbo.ordivdetp", $ordivdetp);

       $newivtrp = array(
        "trcd" => $invoiceno,
        "seqno" => $seqa,
        "paytype" => $data['byrPayType'][$i],
        "docno" => $data['byrIncPay'][$i],
        "payamt" => $data['byrAmount'][$i],
        "deposit" => 0,
        "trcd2" => $registerno,
      );
      $dbqryx->insert("klink_mlm2010.dbo.newivtrp", $newivtrp); 
       //$dbqryx->query($orddetp);
       //$dbqryx->insert('klink_mlm2010.dbo.ordivdetp', $orddetp);
       /* echo "<pre>";
       print_r($orddetp);
       echo "</pre>"; */

       if($data['byrPayType'][$i] == "03") {
        $custpaydet = array(
          "trcd" => $invoiceno,
          "trtype" => "P01",
          "effect" =>  "-",
          "dfno" => $data['stk'],
          "custtype" => "S",
          "amount" => $data['byrAmount'][$i],
          "createnm" => $this->username,
          "applyto" => $data['byrIncPay'][$i],
          "updatenm" => $this->username,
        );
        $dbqryx->insert("klink_mlm2010.dbo.custpaydet", $custpaydet);

        $seqa++;
      }

      /* echo "<pre>";
      print_r($ordivtrp);
      print_r($ordivdetp);
      print_r($custpaydet);
      echo "</pre>"; */
    }

    $upd_sc_newtrh = array(
			'csno' => $invoiceno,
			'flag_approval' => "1",
      'flag_batch' => "2"
		);
		$dbqryx->where('batchno', $data['nossr']);
    $dbqryx->update('klink_mlm2010.dbo.sc_newtrh',$upd_sc_newtrh);

    //"tdp" => $data['tdp'],
    //"npv" => $data['tbv'],

    $updordivhdr = "UPDATE a SET a.tdp = a.tdp + $data[tdp], 
              a.ndp = a.ndp + $data[tdp], 
              a.tbv = a.tbv + $data[tbv], 
              a.npv = a.npv + $data[tbv], 
              a.tpv = a.tpv + $data[tbv],
              a.totpay = a.totpay + $data[tdp],
              a.totinvoice = a.totinvoice + 1
            FROM klink_mlm2010.dbo.ordivhdr a 
            WHERE a.trcd = '$registerno'";
    $dbqryx->query($updordivhdr);

    $newivtrh = array(
      "trcd" =>	$invoiceno,
      "trtype" => "SA0",
      "trdt" => $tgl,
      "dfno" => $data['sc_dfno'], //IDMSYG15905
      "loccd" => $data['stk'],
      "sc_dfno" => $data['stk'], 		//IDSYG20
      "tdp" => $data['tdp'],
      "tpv"	=> $data['tbv'], //157
      "tbv"	=> $data['tbv'], //157
      "npv"	=> $data['tbv'], //157
      "nbv"	=> $data['tbv'], //157
      "ndp"	=> $data['tdp'],
      "whcd" =>	$whcd,
      "docno"	=> $docno,
      "branch" => "B001",	
      "batchno" => $data['nossr'],
      "pricecode"	=> $pricecode,
      "totpay" => $data['tdp'],
      "ship" => $ship,
      "shipto" => $shipto,
      "createnm" => $this->username,
      "updatenm" => $this->username,
      "ordtype"	=> $ordtype,
      "bnsperiod"	=> $bnsperiod,
      "batchscno" => $data['nossr'],
      //"invoiceno"	CNI1903010467
      "applyto" =>	$invoiceno,
      "category" =>	$category
    );

    $dbqryx->insert("klink_mlm2010.dbo.newivtrh", $newivtrh);

    $respx = $this->getDataCN($invoiceno);
    if($respx !== null) {
      return jsonTrueResponse($respx, "Proses generate CN berhasil, No CN : $invoiceno");
    } else {
      return jsonFalseResponse("Proses Generate CN gagal..");
    }

  }

  function getHeaderCN($no_cn) {
    $header = "SELECT a.invoiceno, a.registerno, 
                    CONVERT(VARCHAR(10), a.registerdt, 120) as registerdt,
                    CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod,
                    a.batchscno as ssr_no,
                    CONVERT(VARCHAR(10), a.invoicedt, 120) as invoicedt,
                    e.description as wh_name, 
                    CASE 
                      WHEN a.ship = '2' THEN 'SHIP TO'
                      WHEN a.ship = '1' THEN 'PICK UP'
                      WHEN a.ship = '3' THEN 'HOLD'
                      WHEN a.ship = '4' THEN 'DONT SHIP'
                    END AS ship_desc, a.shipto,   
                    a.dfno as stk, b.fullnm as stk_name, b.tel_hp as stk_telp, 
                    b.addr1 as stk_addr1, b.addr2 as stk_addr2, b.addr3 as stk_addr3,
                    a.sc_dfno,  c.fullnm as co_stk_name, c.tel_hp as co_stk_telp,
                    c.addr1 as co_stk_addr1, c.addr2 as co_stk_addr2, c.addr3 as co_stk_addr3,
                    a.loccd, d.fullnm as main_stk_name, d.tel_hp as main_stk_telp,
                    d.addr1 as main_stk_addr1, d.addr2 as main_stk_addr2, d.addr3 as main_stk_addr3,
                    CASE 
                      WHEN LEFT(a.invoiceno, 2) = 'CN' OR LEFT(a.invoiceno, 3) = 'MDC' THEN 'CONSIGNMENT NOTE'
                      WHEN LEFT(a.invoiceno, 2) = 'MS' THEN 'MOBILE STOCKIST NOTE'
                      WHEN LEFT(a.invoiceno, 2) = 'MM' THEN 'MARKETING MATERIAL NOTE'
                      WHEN LEFT(a.invoiceno, 2) = 'PV' THEN 'PRODUCT VOUCHER REPORT'
                    END AS judul, 
                    CASE 
                      WHEN LEFT(a.invoiceno, 2) = 'CN' OR LEFT(a.invoiceno, 3) = 'MDC' OR LEFT(a.invoiceno, 2) = 'MS' THEN a.docno
                      WHEN LEFT(a.invoiceno, 2) = 'MM' THEN 'MM'
                      WHEN LEFT(a.invoiceno, 2) = 'PV' THEN 'PVR'
                    END AS docno,
                    a.note, a.createnm,  'PT K-Link Nusantara' as branch 
                  FROM klink_mlm2010.dbo.ordivtrh a
                  LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.dfno = b.loccd)
                  LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON (a.sc_dfno = c.loccd)
                  LEFT OUTER JOIN klink_mlm2010.dbo.mssc d ON (a.loccd = d.loccd)
                  LEFT OUTER JOIN klink_mlm2010.dbo.mswh e ON (a.whcd = e.whcd)
                  WHERE a.invoiceno = '$no_cn'";
        $resHead = $this->getRecordset($header, null, $this->db2);
        return $resHead;
  }

  function getDataCNtoPrint($listCN) {
    $arrx = array();
    for($i=0; $i < count($listCN); $i++) {
        $resHead = $this->getHeaderCN($listCN[$i]);
        $arr['header'] = $resHead;

        $prd = "SELECT a.invoiceno, a.prdcd, b.prdnm, a.qtyord, a.dp, a.qtyord * a.dp as total_dp
                FROM klink_mlm2010.dbo.ordivtrd a
                LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
                WHERE a.invoiceno = '$listCN[$i]'
                ORDER BY a.invoiceno";
        
        $resPrd = $this->getRecordset($prd, null, $this->db2);
        $arr['produk'] = $resPrd;

        $pay = "SELECT a.paytype, b.[description] as pay_desc, a.docno, a.payamt
                FROM klink_mlm2010.dbo.ordivtrp a 
                LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON (a.paytype = b.id)
                WHERE a.trcd = '$listCN[$i]'";
        
        $resPay = $this->getRecordset($pay, null, $this->db2);
        $arr['payment'] = $resPay;

        array_push($arrx, $arr);
    }

    return $arrx;
    
  }

  function getRunningNumberInc($prefix, $suffix) {
    
    $thn = date("y");
    $bln = date("m");
    $check_pref = $prefix.$thn.$bln.$suffix;

    $qry = "SELECT a.[prefix], a.lastno FROM mssysno a where prefix = '$check_pref'";
    $res = $this->getRecordset($qry, null, $this->db2);

    $noawal = "";
    if($res === null) {
      $ins = "INSERT INTO klink_mlm2010.dbo.mssysno (prefix, lastno) VALUES ('$check_pref', '2')";
      $resIns = $this->executeQuery($ins);
      $noawal = sprintf("%04d", 1);
    } else {
      $upd = "UPDATE a SET a.lastno = a.lastno + 1 FROM klink_mlm2010.dbo.mssysno a WHERE a.[prefix] = '$check_pref'";
      $resIns = $this->executeQuery($upd);
      $lastno = $res[0]->lastno;
      $noawal = sprintf("%04d", $lastno);
      //echo "no awal :".$noawal; 
    }

    $cnno = $prefix.$thn.$bln.$noawal."/".$suffix;
    return $cnno;
  }

  function getTotalNilaiVch($batchno, $tipe) {
    $qry = "SELECT ISNULL(SUM(d.payamt), 0) as total_uang 
            FROM klink_mlm2010.dbo.sc_newtrp d 
            INNER JOIN klink_mlm2010.dbo.sc_newtrh c 
            ON (c.batchno = '$batchno' and c.trcd = d.trcd AND d.paytype = '$tipe')";
    $resPay = $this->getRecordset($qry, null, $this->db2);
    return $resPay[0]->total_uang;
  }
  

  function pvrApprove($batcno, $kodestk) {
    $ifExist = "SELECT TOP 1 a.trcd, a.amount, a.dfno 
                FROM klink_mlm2010.dbo.bbhdr a
                WHERE a.refno LIKE '$batcno%'";
    $resIncExist = $this->getRecordset($ifExist, null, $this->db2);
    if($resIncExist !== null) {
      $noinc = $resIncExist[0]->trcd;
      $amount = number_format($resIncExist[0]->amount,0,".",".");
      return jsonFalseResponse("Product Voucher sudah di approve dengan no $noinc sebesar Rp. $amount");
    }

    $nilai_vch = $this->getTotalNilaiVch($batcno, "10");
    if($nilai_vch === 0) {
      return jsonFalseResponse("Product Voucher tidak ada / kosong");
    }

    $getIncomingNumber = $this->getRunningNumberInc("IP", "B0000");

    $checkExistInc = "SELECT a.trcd, a.amount, a.dfno 
                      FROM klink_mlm2010.dbo.bbhdr a
                      WHERE a.trcd = '$getIncomingNumber'";
    $resIncExist = $this->getRecordset($ifExist, null, $this->db2);
    if($resIncExist !== null) {
      $this->pvrApprove($batcno, $kodestk);
    }

    $arrBbhdr = array(
      "trcd"	=> $getIncomingNumber,
      "type"	=> "I",
      "trtype" =>	"PVOUCHER",
      "bankacccd"	=> "B0000",
      "refno"	=> $batcno."-01",
      "description"	=> "FROM APPROVAL PVR",
      "amount" => $nilai_vch,
      "status" => "O",
      "dfno" => $kodestk,
      "createnm" => $this->username,
      "updatenm" => $this->username,
      "effect" => "+",	
      "custtype" => "S"
    );

    $nilai_vch_formatted = number_format($nilai_vch, 0, ".", ",");

    $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
    $res = $dbqryx->insert("klink_mlm2010.dbo.bbhdr", $arrBbhdr);
    if($res > 0) {
      return jsonTrueResponse(null, "Incoming Payment PVR berhasil digenerate, no : $getIncomingNumber, senilai Rp.$nilai_vch_formatted");
    }
  }

  public function getInfoCNmanual($nocn) {
    $qry = "SELECT a.invoiceno, a.dfno as sc_dfno, a.loccd, a.sc_dfno as sc_co, a.tdp, a.tbv, 
                CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod,
                ISNULL(COUNT(b.trcd), 0) as jum_ttp,
                ISNULL(SUM(b.tdp), 0) as total_dp_ttp,
                ISNULL(SUM(b.tbv), 0) as total_bv_ttp, c.onlinetype, d.trcd as no_kw,
                a.pricecode, a.whcd, a.branch, 
                a.ship, a.shipto, a.batchscno as batchno, a.receiptno,
                ISNULL(COUNT(b.trcd), 0) as totinv
            FROM klink_mlm2010.dbo.ordivtrh a
            LEFT OUTER JOIN klink_mlm2010.dbo.newtrh b ON (a.invoiceno = b.trcd2)
            INNER JOIN klink_mlm2010.dbo.ordivhdr c ON (a.registerno = c.trcd)
            LEFT OUTER JOIN klink_mlm2010.dbo.billivhdr d ON (a.receiptno = d.trcd)
            WHERE a.invoiceno = '$nocn'
            GROUP BY a.invoiceno, a.dfno, a.loccd, a.sc_dfno, a.tdp, a.tbv, 
            a.bnsperiod, c.onlinetype, d.trcd, a.pricecode, a.whcd, a.branch, 
            a.ship, a.shipto, a.batchscno, a.receiptno";
    $result = $this->getRecordsetArray($qry, null, $this->db2);
    /* print_r($result) */
    if($result === null) {
      return jsonFalseResponse("No CN/MS $nocn tidak valid..");
    }

    if($result[0]['no_kw'] === null || $result[0]['no_kw'] === "") {
      return jsonFalseResponse("No CN/MS $nocn belum dibuatkan no KW..");
    }

    if($result[0]['onlinetype'] !== "M" || $result[0]['onlinetype'] === "") {
      return jsonFalseResponse("No CN/MS $nocn bukan CN/MS manual");
    }

    $result[0]['dp_balance'] = $result[0]['tdp'] === $result[0]['total_dp_ttp'] ? "1" : "0";
    $result[0]['bv_balance'] = $result[0]['tbv'] === $result[0]['total_bv_ttp'] ? "1" : "0";
       
    return jsonTrueResponse($result, "OK");
  }

  public function cek_seQ($tipe_pay) // dipake
    {
        $this->db = $this->load->database('klink_mlm2010', true);
        $y1=date("y");
        $m=date("m");

        //$this->db->trans_begin();

        //if(in_array('p',$tipe_pay))
        if ($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $tbl = "SEQ_PV"."$y1"."$m";
        } elseif ($tipe_pay == 'cv') {
            $tbl = "SEQ_CV"."$y1"."$m";
        } elseif ($tipe_pay == 'pv_hydro') {
            $tbl = "SEQ_ID"."$y1"."$m";
        } else {
            $tbl = "SEQ_ID"."$y1"."$m";
        }

        $cek = "select * from $tbl";

        $query = $this->db->query($cek);
        if ($query->num_rows < 1) {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        } else {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        }

        /*if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        } */

        return $query;
    }

    public function get_idno($tipe_pay) // dipake
    {
        $this->db = $this->load->database('klink_mlm2010', true);
        $y1=date("y");
        $m=date("m");

        //$this->db->trans_begin();

        //if(in_array('p',$tipe_pay))
        if ($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $tbl = "SEQ_PV"."$y1"."$m";
        } elseif ($tipe_pay == 'cv') {
            $tbl = "SEQ_CV"."$y1"."$m";
        } elseif ($tipe_pay == 'pv_hydro') {
            $tbl = "SEQ_ID"."$y1"."$m";
        } else {
            $tbl = "SEQ_ID"."$y1"."$m";
        }

        $qry = "SELECT * FROM $tbl
                    WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";

        $query = $this->db->query($qry);
        if ($query == null) {
            $ss = 0;
        } else {
            foreach ($query->result() as $data) {
                $ss = $data->SeqID;
            }
        }
        $jumlah = $query->num_rows();

        $next_seq = sprintf("%06s", $ss);
        $prefix = date('ym');

        if ($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $y =  strval("PV".$prefix.$next_seq);
        } elseif ($tipe_pay == 'cv') {
            $y =  strval("CV".$prefix.$next_seq);
        } elseif ($tipe_pay == 'pv_hydro') {
            $y =  strval("IDH".$prefix.$next_seq);
        } else {
            $y =  strval("ID".$prefix.$next_seq);
        }

        return $y;
    }

  public function simpanCNManual($data, $trcd) {

    $dbqryx  = $this->load->database("klink_mlm2010", TRUE);

    //$cek_seQ = $this->cek_seQ($data['jenis_bayar']);
    //$trcd = $this->get_idno($data['jenis_bayar']);
    
    //$trcd = "IDTESD1001AX";

    $tgl = date("Y-m-d h:m:s");

    $arrNewtrh = array(
      "trcd" => $trcd,
      "trtype" => $data['trtype'],
      "dfno" => $data['dfno'],
      "loccd" => $data['loccd'],
      "sc_dfno" => $data['sc_dfno'],
      "sc_co" => $data['sc_co'],
      "tdp" => $data['total_all_dp'],
      "taxrate" => "11",
      "tpv" => $data['total_all_bv'],
      "tbv" => $data['total_all_bv'],
      "npv" => $data['total_all_bv'],
      "nbv" => $data['total_all_bv'],
      "ndp" => $data['total_all_dp'], 
      "whcd" => $data['whcd'], 
      "batchno" => $data['batchno'], 
      "docno" => "",
      "branch" => $data['branch'], 
      "pricecode" => $data['pricecode'], 
      "pay1amt" => $data['total_all_dp'], 
      "totpay" => $data['total_all_dp'],
      "ship" => $data['ship'],
      "shipto" => $data['shipto'],
      "createnm" => $this->username,
      "updatenm" => $this->username,
      "createdt" => $tgl,
      "updatedt" => $tgl,
      "etdt" => $tgl,
      "ordtype" => "1",
      "cnid" => "",
      "orderno" => $data['orderno'],
      "receiptno" => $data['receiptno'],
      "seq" => $data['nourut'],
      "trcd2" => $data['cnno'],
      "bnsperiod" => $data['bnsperiod'],
      "trdt2" => $tgl,
      "ttptype" => $data['ttptype'],
      "entrytype" => "0",
      "tdp_ori" => $data['total_all_dp'],
      "tbv_ori" => $data['total_all_bv'],
    );

    /*---------------------------------------------------------------
      DETAIL PRODUK, SET values utk multiple insert ke sc_newtrd
    * --------------------------------------------------------------*/
    $jum = count($data['prdcd']);
    $totBV = 0;
    $totDP = 0;
    $totprd = 0;
    $qryAddProduct = "";
    for ($i=0;$i<$jum;$i++) {
      if($data['prdcd'] !== "") {
        $arrNewtrd = array(
          "trcd" => $trcd,
          "prdcd" => $data['prdcd'][$i],
          "qtyord" => $data['jum'][$i],
          "dp" => $data['harga'][$i],
          "pv" => $data['poin'][$i],
          "bv" => $data['poin'][$i],
          "pricecode" => $data['pricecode'],
          "trcd2" => $data['cnno'],
        ); 
        /* echo "<pre>";
        print_r($arrNewtrd);
        echo "</pre>"; */
        $res = $dbqryx->insert("klink_mlm2010.dbo.newtrd", $arrNewtrd);
        $totprd++;
      }
    }  

    $checkPrd = "SELECT COUNT(a.trcd) as jum 
              FROM klink_mlm2010.dbo.newtrd a 
              WHERE a.trcd = '$trcd'";
    $resCheckPrd = $this->getRecordsetArray($checkPrd, null, $this->db2);
    $jum_prd_rec = (int) $resCheckPrd[0]['jum'];

    if($totprd !== $jum_prd_rec) {
      return jsonFalseResponse("Jumlah record produk tidak sama..");
    }

    $res2 = $dbqryx->insert("klink_mlm2010.dbo.newtrh", $arrNewtrh);
    if($res2 > 0) {
      $arrdata = array(
        "cnno" => $data['cnno'],
      );
      return jsonTrueResponse($arrdata, "Transaksi berhasil diinput, no Trx : $trcd");
    } else {
      return jsonFalseResponse("Transaksi gagal diinput..");
    }


    /* echo "<pre>";
    print_r($arrNewtrh);
    echo "</pre>"; */

    
  }

  public function showProductPriceForPvr($arr, $tipe = "input")
  {
    $productcode = $arr['productcode']; 
    $pricecode = $arr['pricecode']; 
    $jenis = $arr['jenis']; 
    $jenis_promo = $arr['jenis_promo'];
    $no_cn = $arr['cnno'];
    $qty = $arr['qty'];

    $check = "SELECT a.prdcd, a.qtyord, b.prdcd , ISNULL(b.jum, 0) as qty_ttp
              FROM klink_mlm2010.dbo.REKAP_PRD_ORDIVTRD a
              LEFT OUTER JOIN klink_mlm2010.dbo.REKAP_NEWTRH_NEWTRD_BYTRCD2 b
                  ON (a.prdcd = b.prdcd AND a.invoiceno = b.trcd2)
              WHERE a.invoiceno = ? AND a.prdcd = ?";
    $arrParam1 = array($no_cn, $productcode);
    $resCheck = $this->getRecordset($check, $arrParam1, $this->db2);  

    if($resCheck === null) {
      $arr = array("response" => "false", "message" => "Kode produk $productcode tidak ada di dalam detail produk $no_cn");
      return $arr;
    }

    if($tipe === "input") {

      $qty_input = (int) $qty;
      $qty_sisa = (int) $resCheck[0]->qtyord - $resCheck[0]->qty_ttp;

      if($qty_input > $qty_sisa) {
        $arr = array("response" => "false", "message" => "Jumlah maksimum qty untuk produk $productcode adalah : $qty_sisa");
        return $arr;
      }
    }

    $qry = "SELECT  b.prdcd,b.prdnm, b.webstatus, b.scstatus, b.status,
                c.bv,c.dp, c.cp
            FROM klink_mlm2010.dbo.pricetab c
            LEFT OUTER JOIN klink_mlm2010.dbo.msprd b
                on c.prdcd=b.prdcd
            WHERE c.pricecode = ? and
                  c.prdcd = ?";
        //return $this->get_data_json_result($qry);
        $arrParam = array($pricecode, $productcode);
        $hasil = $this->getRecordset($qry, $arrParam, $this->db2);
        if ($hasil == null) {
            $arr = array("response" => "false", "message" => "Kode produk salah");
            return $arr;
        }

        //jika produk adalah, tipe product exclude yang tidak boleh diinput dalam pembelanjaan PVR
        $produkname = $hasil[0]->prdnm;
        if ($jenis == "pv" && $hasil[0]->pvr_exclude_status == "1") {
            $arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput dalam pembelanjaan PVR");
            return $arr;
        }
        /* $produkname = $hasil[0]->prdnm;
        if($hasil[0]->pvr_exclude_status == "1") {

        } */

        //jika produk adalah produk free / yang harga nya 0
        //tidak bisa untuk inputan PVR
        $harga = (int) $hasil[0]->dp;
        if ($jenis == "pv" && $harga == 0) {
            $arr = array("response" => "false", "message" => "Produk $productcode / $produkname adalah kode produk FREE");
            return $arr;
        }

        //jika produk adalah produk bundling
        //tidak bisa untuk inputan PVR
        if ($jenis == "pv" && $hasil[0]->bundling !== null) {
            $arr = array("response" => "false", "message" => "Produk $productcode / $produkname adalah produk bundling/paket");
            return $arr;
        }

        //jika produk status nya sudah
        if($jenis_promo == "reguler") {
            if($jenis !== "bo") {
                if ($hasil[0]->scstatus !== "1" || $hasil[0]->webstatus !== "1" || $hasil[0]->status !== "1") {
                    $arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput untuk stokis..");
                    return $arr;
                }
            }
        }

        $arr = array("response" => "true", "arraydata" => $hasil);
        return $arr;
    }

    function getDataTtpByTrcd($trcd) {
        $qry = "SELECT a.trcd, a.dfno, b.fullnm, a.orderno, a.tdp, a.tbv, a.trcd,
                  a.sc_dfno, a.sc_co, a.loccd, a.trcd2, a.pricecode,
                  CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod
                FROM klink_mlm2010.dbo.newtrh a
                LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
                WHERE a.trcd = '$trcd'";
        $resHeader = $this->getRecordset($qry, null, $this->db2); 

        $qry2 = "SELECT a.trcd, a.prdcd, b.prdnm, a.qtyord, c.dp, c.bv, a.pricecode, 
                  a.qtyord * c.dp as TOTDP,
                  a.qtyord * c.bv as TOTBV
                FROM klink_mlm2010.dbo.newtrd a
                LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
                LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (a.prdcd = c.prdcd AND a.pricecode = c.pricecode)
                WHERE a.trcd = '$trcd'";
        $resDetail = $this->getRecordset($qry2, null, $this->db2); 

        $arr = array(
          "header" => $resHeader,
          "detail" => $resDetail,
        );

        return $arr;
    }

    function deleteTTpByTrcd($trcd) {
      $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
      $qry = "DELETE FROM klink_mlm2010.dbo.newtrh WHERE trcd = '$trcd'";
      $qry2 = "DELETE FROM klink_mlm2010.dbo.newtrd WHERE trcd = '$trcd'";

      $res1 = $dbqryx->query($qry);
      $res2 = $dbqryx->query($qry2);

      if($res1 > 0 && $res1 > 0) {
        return jsonTrueResponse(null, "Data $trcd berhasil dihapus");
      } 
      
      return jsonFalseResponse("Data $trcd gagal dihapus");
    }

}
