<?php
class Reseller_model extends MY_Model
{
    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->tbl_register = "klink_mlm2010.dbo.ordhdr";
        $this->tbl_transaksi = "klink_mlm2010.dbo.ordtrh";
        $this->tbl_prod = "klink_mlm2010.dbo.ordtrd";
    }

    public function showProductPriceForPvr($productcode, $pricecode, $jenis, $jenis_promo = "reguler")
    {
        $qry = "SELECT  b.prdcd,b.prdnm, b.webstatus, b.scstatus, b.status,
                    c.bv,c.dp, c.cp, d.cat_inv_id_parent as bundling, b1.pvr_exclude_status
                from klink_mlm2010.dbo.pricetab c
                LEFT OUTER JOIN klink_mlm2010.dbo.msprd b
                    on c.prdcd=b.prdcd
                LEFT OUTER JOIN klink_mlm2010.dbo.product_exclude_sales b1
                    on (b.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS = b1.prdcd)
                LEFT OUTER JOIN db_ecommerce.dbo.master_prd_bundling d
                    ON (b.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS = d.cat_inv_id_parent)
                where c.pricecode = ? and
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
        /* if($jenis_promo == "reguler") {
            if ($hasil[0]->scstatus !== "1" || $hasil[0]->webstatus !== "1" || $hasil[0]->status !== "1") {
                $arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput untuk stokis..");
                return $arr;
            }
        } */

        $arr = array("response" => "true", "arraydata" => $hasil);
        return $arr;
    }

    public function getTrxInvoice($inv) {
        $qry = "SELECT a.invoiceno, a.registerno, CONVERT(VARCHAR(10), a.invoicedt, 120) as invoicedt, 
                  a.dfno, b.fullnm, a.tdp, a.tbv, a.totpay, c.kode_reseller, d.fullnm as reseler_name,
                  a.pricecode, a.whcd, c1.fullnm as whnm, 'PT. K-Link Nusantara' as branch,
                  a.[print], CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod,
                  CASE  
                    WHEN a.ship = '1' THEN 'Pick Up'
                    WHEN a.ship = '2' THEN 'Ship To'
                    WHEN a.ship = '3' THEN 'Hold'
                    WHEN a.ship = '4' THEN 'Dont Ship'
                  END AS ship_desc 
                FROM klink_mlm2010.dbo.ordtrh a 
                LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
                LEFT OUTER JOIN klink_mlm2010.dbo.ordhdr c ON (a.registerno = c.trcd)
                LEFT OUTER JOIN klink_mlm2010.dbo.mssc c1 ON (a.whcd = c1.loccd)
                LEFT OUTER JOIN db_reseler.dbo.msreseller d ON (c.kode_reseller COLLATE SQL_Latin1_General_CP1_CS_AS = d.resellerID)
                WHERE a.invoiceno = '$inv'";
        $header = $this->getRecordset($qry, null, $this->db2);
        if($header === null) {
          return jsonFalseResponse("no Invoice $inv tidak ada..");
        }

        $qry2 = "SELECT a.prdcd, b.prdnm, a.qtyord, c.dp, c.bv, 
        a.qtyord * c.dp as sub_total_dp,
        a.qtyord * c.bv as sub_total_bv,
        a1.pricecode
        FROM klink_mlm2010.dbo.ordtrd a 
        INNER JOIN klink_mlm2010.dbo.ordtrh a1 ON (a.invoiceno = a1.invoiceno)
        LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
        LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (b.prdcd = c.prdcd AND a1.pricecode = c.pricecode)
        WHERE a.invoiceno = '$inv'";
        $produk = $this->getRecordset($qry2, null, $this->db2);

        $qry3 = "SELECT a.paytype, b.[description] as pay_desc, a.docno, a.payamt
        FROM klink_mlm2010.dbo.ordtrp a
        LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON (a.paytype = b.id)
        WHERE a.trcd = '$inv'";
        $payment = $this->getRecordset($qry3, null, $this->db2);

        return array(
          "response" => "true",
          "header" => $header,
          "produk" => $produk,
          "payment" => $payment,
        );

        
    }

    public function searchResellerByParam($param) {

      $where = " AND CONVERT(VARCHAR(10), a.registerdt, 120) BETWEEN '$param[from]' AND '$param[to]' ";
  
      if($param['searchby'] !== "" && $param['searchby'] == "registerno") {
        $where .= " AND a.trcd = '$param[paramValue]'"; 
      }

      $qry = "SELECT a.trcd, a.tdp, a.tbv, 
                CONVERT(VARCHAR(10), a.registerdt, 120) as registerdt, 
                a.kode_reseller, b.fullnm as nama_reseller, a.dfno, c.fullnm as nama_member, d.trcd as kw_no,
                ISNULL(COUNT(c1.invoiceno), 0) as tot_invoice  
              FROM klink_mlm2010.dbo.ordhdr a 
              LEFT OUTER JOIN db_reseler.dbo.msreseller b ON (a.kode_reseller  COLLATE SQL_Latin1_General_CP1_CS_AS = b.resellerID)
              LEFT OUTER JOIN klink_mlm2010.dbo.msmemb c ON (a.dfno = c.dfno)
              LEFT OUTER JOIN klink_mlm2010.dbo.ordtrh c1 ON (a.trcd = c1.registerno)
              LEFT OUTER JOIN klink_mlm2010.dbo.billhdr d ON (a.trcd = d.applyto)
              WHERE (a.kode_reseller is not NULL AND a.kode_reseller != '') $where OR a.trcd = '2001310058'
              GROUP BY a.trcd, a.tdp, a.tbv, 
              CONVERT(VARCHAR(10), a.registerdt, 120), 
              a.kode_reseller, b.fullnm, a.dfno, c.fullnm, d.trcd"; 
      
      if($this->username == "BID06" || $this->username == "DION") {
        echo "<pre>";
        print_r($qry);
        echo "</pre>";
      }

      return $this->getRecordset($qry, null, $this->db2);
    }

    public function getDataReseller($id) {
      $qry = "SELECT a.resellerID as kode_reseller, a.fullnm as nama_reseller, 
                  b.dfno  as referal_code,          
                  b.fullnm as referal_name
              FROM db_reseler.dbo.msreseller a
              INNER JOIN klink_mlm2010.dbo.msmemb b 
              ON (a.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = b.dfno)
              WHERE a.resellerID = '$id'";
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function listActivePayType() {
      $qry = "SELECT id, description FROM klink_mlm2010.dbo.paytype a WHERE a.activestatus = '1'";
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function listIncPayByID($idmember) {
      $qry = "SELECT a.trcd, a.dfno , a.[type], a.custtype , 
                a.status , a.createnm , a.updatenm , b.amount, b.balamt,
                a1.effect 
              FROM klink_mlm2010.dbo.bbhdr a 
              INNER JOIN klink_mlm2010.dbo.custpaydet a1 ON (a.trcd = a1.applyto AND a1.effect = '+')
              LEFT OUTER JOIN klink_mlm2010.dbo.custpaybal b ON (a.trcd = b.trcd )
              WHERE a.dfno = '$idmember' 
              AND a1.custtype = 'M'
              AND a.status = 'O' and b.balamt > 0";
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
                AND a1.custtype = 'M'
                AND a.status = 'O' and b.amount > 0 $and
              ) x    
              LEFT OUTER JOIN klink_mlm2010.dbo.custpaydet a2 ON (x.trcd = a2.applyto AND a2.effect = '-')
              GROUP BY x.trcd, x.dfno , x.[type], x.custtype , 
                  x.status , x.createnm , x.updatenm , x.amount, 
                  x.effect
              HAVING (x.amount - ISNULL(SUM(a2.amount), 0) > 0)";
      //echo $qry;
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function getDataRegisterByID($registerno) {
      $qry = "SELECT a.trcd, a.tdp, a.tbv, 
                CONVERT(VARCHAR(10), a.registerdt, 120) as registerdt, 
                a.kode_reseller, b.fullnm as  nama_reseller, a.dfno, c.fullnm as nama_member,
                a.pricecode, e.description as pricecode_desc, a.ship, 
                CASE  
                WHEN a.ship = '1' THEN 'Pick Up'
                WHEN a.ship = '2' THEN 'Ship To'
                WHEN a.ship = '3' THEN 'Hold'
                WHEN a.ship = '4' THEN 'Dont Ship'
                END AS ship_desc, 
                a.whcd, d.fullnm  as whnm, a.createnm, CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod
              FROM klink_mlm2010.dbo.ordhdr a 
              LEFT OUTER JOIN db_reseler.dbo.msreseller b 
              ON (a.kode_reseller COLLATE SQL_Latin1_General_CP1_CS_AS = b.resellerID AND a.kode_reseller is not NULL AND a.kode_reseller <> '')
              LEFT OUTER JOIN klink_mlm2010.dbo.msmemb c ON (a.dfno = c.dfno)
              LEFT OUTER JOIN klink_mlm2010.dbo.mssc d ON (a.whcd = d.loccd)
              LEFT OUTER JOIN klink_mlm2010.dbo.pricecode e ON (a.pricecode = e.code )
              WHERE a.trcd = '$registerno'";
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function listInvoiceByRegisterno($registerno) {
      $qry = "SELECT a.invoiceno , a.dfno , b.fullnm , a.bnsperiod , a.tdp , a.tbv, a.pricecode, a.whcd, a.ship
              FROM klink_mlm2010.dbo.ordtrh a 
              LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
              WHERE a.registerno = '$registerno'";
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function rekapProdukByRegisterno($registerno) {
      $qry = "SELECT a.prdcd, c.prdnm, 
                SUM(a.qtyord) as qty_prd, 
                d.bv, d.dp, 
                SUM(a.qtyord) * d.bv as sub_total_bv, 
                SUM(a.qtyord) * d.dp as sub_total_dp
              FROM klink_mlm2010.dbo.ordtrd a 
              INNER JOIN klink_mlm2010.dbo.ordtrh b ON (a.invoiceno = b.invoiceno)
              LEFT OUTER JOIN klink_mlm2010.dbo.msprd c ON (a.prdcd = c.prdcd)
              LEFT OUTER JOIN klink_mlm2010.dbo.pricetab d ON (c.prdcd = d.prdcd AND b.pricecode = d.pricecode )
              WHERE b.registerno = '$registerno'
              GROUP BY a.prdcd, c.prdnm, d.bv, d.dp";
      return $this->getRecordset($qry, null, $this->db2);
    }

    /* function getCurrentPeriod() // dipake
    {
        $qry = "SELECT 
        CONVERT(VARCHAR(10), DATEADD(month, -1, a.currperiodSCO), 120) as prevperiod,
        CONVERT(VARCHAR(10), a.currperiodSCO, 120) as lastperiod,
        CONVERT(VARCHAR(10), DATEADD(month, 1, a.currperiodSCO), 120) as nextperiod
        from klink_mlm2010.dbo.syspref a";
        $res = $this->getRecordset($qry, null, $this->db2);
		    return $res;
	  } */

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

    public function listWh() {
      $qry = "SELECT a.loccd, a.fullnm 
              FROM mssc a WHERE a.loccd LIKE 'WH%'";
      return $this->getRecordset($qry, null, $this->db2);
    }

    function getRegisterNo($prefix1 = "") {
    	$y1 = date("y");
    	$m = date("m");
    	$d = date("d");

    	$tbl = "klink_mlm2010.dbo.SEQ_"."$y1"."$m";
    	$qry = "SELECT * FROM $tbl WHERE SeqID = (SELECT MAX(SeqID) FROM $tbl)";
    	//echo $qry;
    	$query = $this->db->query($qry);
    	if ($query == null) {
    		$ss = 0;
    	} else {
    		foreach($query->result() as $data) {
    			$ss = $data->SeqID;
    		}
    	}
    	$jumlah = $query->num_rows();

    	$next_seq = sprintf("%04s", $ss);
    	$prefix = date('ymd');

    	$y = strval($prefix1.$prefix.$next_seq);
    	return $y;
    }

    public function saveRegister($data) {

      $noregister = $this->getRegNo();
      //$noregister = "R2021JDOE";
      $shipto = "";
      if($data['ship'] == "2") {
        $shipto = "001";
      }
      $ordhdr = array(
        "dfno" => $data['dfno'],
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
        "trtype" => "SA0",
        "branch" => "B001",
        "kode_reseller" => $data['kode_reseller']
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

    public function getRegNo($prefix1 = "") {
      //NO REG : YYMMDD. ambil dari KSEQ_2201."R"
      $y1 = date("y");
    	$m = date("m");
    	$d = date("d");

      $tbl_seq_reg = "klink_mlm2010.dbo.KSEQ_".$y1.$m;
      $input = "insert into $tbl_seq_reg (SeqVal) values('a')";
      $query = $this->db->query($input);

      $qry = "SELECT max(a.SeqID) as nomor FROM $tbl_seq_reg a";
      $query = $this->db->query($qry);
    	if ($query == null) {
    		$ss = 1;
    	} else {
    		foreach($query->result() as $data) {
    			$ss = $data->nomor;
    		}
    	}

    	$next_seq = sprintf("%04s", $ss);
    	$prefix = date('ymd');

    	$y = strval($prefix1.$prefix.$next_seq."R");
    	return $y;
    }

    public function getInvNo($prefix1 = "IDI") {
      //no inv : IDI.YYMM. ambil dari KSEQ_IVI2201. "R"
      $y1 = date("y");
    	$m = date("m");
    	$d = date("d");

      $tbl_seq_reg = "klink_mlm2010.dbo.KSEQ_IVI".$y1.$m;
      $input = "insert into $tbl_seq_reg (SeqVal) values('a')";
      $query = $this->db->query($input);

      $qry = "SELECT max(a.SeqID) as nomor FROM $tbl_seq_reg a";
      $query = $this->db->query($qry);
    	if ($query == null) {
    		$ss = 1;
    	} else {
    		foreach($query->result() as $data) {
    			$ss = $data->nomor;
    		}
    	}

    	$next_seq = sprintf("%04s", $ss);
    	$prefix = date('ym');

    	$y = strval($prefix1.$prefix.$next_seq."R");
    	return $y;
    }

    public function insertTrx() {
      $ordhdr = array(
        "dfno" => $idmember,
        "bnsperiod" => $bnsperiod,
        "loccd" => "BID06",
        "pricecode" => "12W4",
        "registerno" => $noregister,
        //"registerdt" => $createdt,
        "etdt" => $createdt,
        "ship" => $ship, //1 or 2
        "whcd" => $kode_wh, //WH001
        //"totinvoice" => $jum_ttp,
        //"tpv" => $tot_bv,
        //"tbv" => $tot_bv,
        //"nbv" => $tot_bv,
        //"tdp" => $tot_dp,
        //"ndp" => $tot_bv,
        "createnm" => $username,
        //"createdt" => $createdt,
        //"updatedt" => $createdt,
        "updatenm" => $username,
        //"trcd" => $noregister,
        "trtype" => "SA0",
        "trdt" => $createdt,
        "totpay" => $tot_dp
      );

      $shipTo = "";
      if($ship == "2") {
        $shipTo = "001";
      }
       
       $ordtrh = array(
         "ordtype" => "1",
         "dfno" => $idmember,
         "invoiceno" => $invoiceno,
         "bnsperiod" => $bnsperiod,
         "loccd" => "BID06",
         "pricecode" => "12W4",
         "ship" => $ship, //1 or 2,
         "registerno" => $noregister,
         "branch" => "B001",
         "whcd" => $kode_wh, //WH001,
         "tpv" => $bv,
         "tbv" => $bv,
         "nbv" => $bv,
         "npv" => $bv,
         "tdp" => $dp,
         "ndp" => $dp, 
         "createnm" => $username,
         //"createdt" => $createdt,
         //"updatedt" => $createdt,
         "updatenm" => $username,
         "trcd" => $invoiceno,
         "trtype" => "SA0",
         "trdt" => $tgl,
         "totpay" => $dp,
       );
       
       $ordtrd = array( 
        "registerno" => $noregister,
        "invoiceno" => $invoiceno,
        "prdcd" => $prdcd,
        "qtyord" => $qty,	
        "pricecode" => $pricecode,
       );
       
       $ordtrp = array(
        "trcd" => $invoiceno,
        "seqno" => 1, //urutan,
        "paytype" => $paytype,
        "docno" => $ip_no,
        "payamt" => $nilai,
        "trcd2" => $noregister,
        "vchtype" => "C" //blm tau
       );
       
       $orddetp = array(
        "trcd" => $registerno,
        "seqno" => 1, //urutan
        "docno" => $ip_no,
        "payamt" => $total_nilai_ip
       );
    }

    function simpanTrxInvoiceReseller($arr) {

      /* echo "<pre>";
      var_dump($arr);
      echo "</pre>"; */

      $invoiceno = $this->getInvNo();
      $noregister = $arr['header']['registerno'];
      /* $invoiceno = "TESR001R";
      $noregister = "RSTESR001R"; */

      $idmember = $arr['header']['dfno'];
      //$invoiceno = "IDITESINVJD05";
      $bnsperiod = $arr['header']['bnsperiod'];
      $kode_wh = $arr['header']['whcd'];
      $username = $arr['header']['createnm'];
      //$noregister = $arr['header']['registerno'];
      $bv = $arr['header']['total_bv'];
      $dp = $arr['header']['total_dp_dist'];
      $ship = $arr['header']['ship'];
      $pricecode = $arr['header']['pricecode'];
      $dfno = $arr['header']['dfno'];
      $tgl =  "2020-02-21"; //$this->dateTime;


      $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
			//$dbqryx->trans_begin();

      $qryxx = "SELECT COUNT(ISNULL(a.seq, 0)) jum_inv
              FROM klink_mlm2010.dbo.ordtrh a
              WHERE a.registerno = '$noregister'";
      $respa = $this->getRecordset($qryxx, null, $this->db2);
      $urut_inv = $respa[0]->jum_inv + 1;

      $shipTo = "";
      if($ship == "2") {
        $shipTo = "001";
      }

      $ordtrh = array(
        "ordtype" => "1",
        "seq" => $urut_inv,
        "dfno" => $idmember,
        "invoiceno" => $invoiceno,
        "bnsperiod" => $bnsperiod,
        "loccd" => "BID06",
        "pricecode" => "12W4",
        "ship" => $ship, //1 or 2,
        "shipto" => $shipTo,
        "registerno" => $noregister,
        "branch" => "B001",
        "whcd" => $kode_wh, //WH001,
        "tpv" => $bv,
        "tbv" => $bv,
        "nbv" => $bv,
        "npv" => $bv,
        "tdp" => $dp,
        "ndp" => $dp, 
        "createnm" => $username,
        "updatenm" => $username,
        "trcd" => $invoiceno,
        "trtype" => "SA0",
        "trdt" => $tgl,
        "totpay" => $dp,
      );
      $dbqryx->insert('klink_mlm2010.dbo.ordtrh', $ordtrh);

      /* echo "<pre>";
      print_r($ordtrh);
      echo "</pre>"; */


      foreach($arr['produk'] as $prd) {
        $prdcd = $prd['prdcd'];
        $qty = $prd['qtyord'];
        $ordtrd = array( 
          "registerno" => $noregister,
          "invoiceno" => $invoiceno,
          "prdcd" => $prdcd,
          "qtyord" => $qty,	
          "dp" => $prd['dp'],
          "pv" => $prd['bv'],
          "bv" => $prd['bv'],
          "pricecode" => $pricecode,
          "indexfree" => 0
         );
         $dbqryx->insert('klink_mlm2010.dbo.ordtrd', $ordtrd);
         /* echo "<pre>";
         print_r($ordtrd);
         echo "</pre>"; */
      }

      $seqno = 1;
      foreach($arr['new_payment'] as $pay) {
        $paytype = $pay['paytype'];
        $ip_no = $pay['docno'];
        $nilai = $pay['payamt'];
        $ordtrp = array(
          "trcd" => $invoiceno,
          "seqno" => $seqno, //urutan,
          "paytype" => $paytype,
          "docno" => $ip_no,
          "payamt" => $nilai,
          "trcd2" => $noregister,
          "vchtype" => "C" //blm tau
         );

         //$dbqryx->query($ordtrp);
         $dbqryx->insert('klink_mlm2010.dbo.ordtrp', $ordtrp);

         /* echo "<pre>";
         print_r($ordtrp);
         echo "</pre>"; */


         $orddetp = array(
          "trcd" => $noregister,
          "paytype" => $paytype,
          "seqno" => $seqno, //urutan
          "docno" => $ip_no,
          "payamt" => $nilai
         );
         //$dbqryx->query($orddetp);
         $dbqryx->insert('klink_mlm2010.dbo.orddetp', $orddetp);
         /* echo "<pre>";
         print_r($orddetp);
         echo "</pre>"; */


         $custpaydet = array(
           "trcd" => $invoiceno,
           "trtype" => "P01",
           "effect" =>  "-",
           "dfno" => $dfno,
           "custtype" => "M",
           "amount" => $nilai,
           "createnm" => $username,
           "applyto" => $ip_no,
           "updatenm" => $username,
         );
         //$dbqryx->query($custpaydet);
         $dbqryx->insert('klink_mlm2010.dbo.custpaydet', $custpaydet);
         /* echo "<pre>";
         print_r($custpaydet);
         echo "</pre>"; */
         $seqno++;
      }

      //UPDATE ordhdr
      $updOrdHdr = "UPDATE a SET a.totinvoice = a.totinvoice + 1, 
                   a.tpv = a.tpv + $bv, 
                   a.tbv = a.tbv + $bv, 
                   a.npv = a.npv + $bv, 
                   a.nbv = a.nbv + $bv, 
                   a.tdp = a.tdp + $dp,
                   a.ndp = a.ndp + $dp
              FROM klink_mlm2010.dbo.ordhdr a
              WHERE a.registerno = '$noregister'";
      $dbqryx->query($updOrdHdr);
      
      $resx = $this->getTrxInvoice($invoiceno);
      return $resx;

    }  

    function insertOrdTrh($data) {

      
    }

    function listPrdReseller($param = null) {
      $where = "";
      if($param !== null || is_array($param)){
        if(array_key_exists("param", $param)) {
          if($param['param'] == "prdcd") {
            $where .= " AND a.prdcd LIKE '$param[paramValue]'";
          }

          if($param['param'] == "prdnm") {
            $where .= " AND a.prdnm LIKE '%$param[paramValue]%'";
          }  
        }
      } 

      $qry = "SELECT a.prdcd, a.prdnm, a.bv, a.price_w, a.price_e, a.price_tl
              FROM DION_msprd_pricetabV2 a WHERE a.is_reseller = '1' $where";
      /* echo "<pre>";
      echo $qry;
      echo "</pre>"; */
      $hasil = $this->getRecordsetArray($qry, null, $this->db2);
      return $hasil;
    }

    function listNamaReseller($param = null) {
      $where = "";
      if($param !== null || is_array($param)){
        if(array_key_exists("param", $param)) {
          if($param['param'] == "nama_reseller") {
            $where .= " WHERE a.fullnm LIKE '%$param[paramValue]%'";
          }
        }
      } 
      $qry = "SELECT a.resellerID, a.fullnm, 
              a.dfno as kode_referal, b.fullnm as nama_referal
              FROM db_reseler.dbo.msreseller a
              LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno) $where";
      
      $hasil = $this->getRecordsetArray($qry, null, $this->db2);
      return $hasil;
    }
}