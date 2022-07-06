<?php
class Payment_receipt_model extends MY_Model
{
    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function getKWNo($prefix1 = "KW", $suffix = "") {
      //NO REG : YYMMDD. ambil dari KSEQ_2201."R"
      $y1 = date("y");
    	$m = date("m");
    	$d = date("d");

      $tbl_seq_reg = "klink_mlm2010.dbo.KSEQ_KW".$y1.$m;
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

    	$next_seq = sprintf("%05s", $ss);
    	$prefix = date('ym');

    	$y = strval($prefix1.$prefix.$next_seq.$suffix);
    	return $y;
    }

    public function getKWNoSTK($prefix1 = "KW", $suffix = "") {
      //NO REG : YYMMDD. ambil dari KSEQ_2201."R"
      $y1 = date("y");
    	$m = date("m");
    	$d = date("d");

      $tbl_seq_reg = "klink_mlm2010.dbo.SEQ_KW".$y1.$m;
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

    	$next_seq = sprintf("%06s", $ss);
    	$prefix = date('ym');

    	$y = strval($prefix1.$prefix.$next_seq.$suffix);
    	return $y;
    }


    public function getListTrx($tipe, $noregister, $listInv = "") {
      if($tipe === "1" || $tipe === "2") {
        $tbl_header = "klink_mlm2010.dbo.ordtrh";
        $tbl_prd = "klink_mlm2010.dbo.ordtrd";

        $qry = "SELECT
                x.seq, x.invoiceno, x.tdp, x.ndp, x.totpay, 
                SUM(x.total_dp) as total_dp,
                SUM(x.total_bv) as total_bv
              FROM 
              (
              SELECT a.seq, a.invoiceno, a.tdp, a.ndp, a.totpay, 
                b.qtyord * c.dp as total_dp,
                b.qtyord * c.bv as total_bv
              FROM $tbl_header a 
              LEFT OUTER JOIN $tbl_prd b ON (a.invoiceno = b.invoiceno)
              LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (b.prdcd = c.prdcd AND a.pricecode = c.pricecode)
              WHERE a.registerno = '$noregister' and a.flag_paid <> '1' and (a.receiptno is null OR a.receiptno = '')
              ) x
              GROUP BY x.seq, x.invoiceno, x.tdp, x.ndp, x.totpay";

      } else {
        $tbl_header = "klink_mlm2010.dbo.ordivtrh";
        $tbl_prd = "klink_mlm2010.dbo.ordivtrd";

        $qry = "SELECT
                  x.seq, x.invoiceno, x.total_dp
                FROM 
                (
                  SELECT a.seq, a.invoiceno, a.tdp as total_dp
                  FROM $tbl_header a 
                  WHERE a.registerno = '$noregister' 
                    and a.flag_paid <> '1' and (a.receiptno is null OR a.receiptno = '') 
                ) x
                GROUP BY x.seq, x.invoiceno, x.total_dp";
      }

      $inv = "";
      if($listInv !== "") {
        $inv .= " AND a.invoiceno IN ($listInv)";
      }

     
      /* echo "<pre>";
      echo $qry;
      echo "</pre>"; */
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function getListTrxSdhJadiKW($tipe, $noregister, $listInv = "") {
      $inv = "";
      if($listInv !== "") {
        $inv .= " AND a.invoiceno IN ($listInv)";
      }

      if($tipe === "1") {
        $tbl_header = "klink_mlm2010.dbo.billhdr";
        $tbl_reg = "klink_mlm2010.dbo.ordhdr";
        $tbl_prd = "klink_mlm2010.dbo.billprd";

        $qry = "SELECT a.trcd as receiptno, a.applyto, a.tdp, a.tbv, 
                b.kode_reseller, c.fullnm as nama_reseller,
                CONVERT(VARCHAR(10), a.createdt, 120) as createdt, '$tipe' as tipe,
                a.createnm
                FROM $tbl_header a
                LEFT OUTER JOIN $tbl_reg b ON (a.applyto = b.trcd)
                LEFT OUTER JOIN db_reseler.dbo.msreseller c ON (b.kode_reseller COLLATE SQL_Latin1_General_CP1_CS_AS = c.resellerID)
                WHERE a.applyto = '$noregister'";

      } else if($tipe == "2") {
        $tbl_header = "klink_mlm2010.dbo.billhdr";
        $tbl_reg = "klink_mlm2010.dbo.ordhdr";
        $tbl_prd = "klink_mlm2010.dbo.billprd";

        $qry = "SELECT a.trcd as receiptno, a.applyto, a.tdp, a.tbv, 
                a.dfno as kode_reseller, c.fullnm as nama_reseller,
                CONVERT(VARCHAR(10), a.createdt, 120) as createdt, '$tipe' as tipe,
                a.createnm
                FROM $tbl_header a
                LEFT OUTER JOIN $tbl_reg b ON (a.applyto = b.trcd)
                LEFT OUTER JOIN klink_mlm2010.dbo.msmemb c ON (a.dfno = c.dfno)
                WHERE a.applyto = '$noregister'";
      } else {
        $tbl_header = "klink_mlm2010.dbo.billivhdr";
        $tbl_reg = "klink_mlm2010.dbo.ordivhdr";
        $tbl_prd = "klink_mlm2010.dbo.billivprd";

        $qry = "SELECT a.trcd as receiptno, a.applyto, a.tdp, a.tbv, a.dfno as kode_reseller,
                c.fullnm as nama_reseller,
                CONVERT(VARCHAR(10), a.createdt, 120) as createdt, '$tipe' as tipe,
                a.createnm
                FROM $tbl_header a
                LEFT OUTER JOIN $tbl_reg b ON (a.applyto = b.trcd)
                LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON (a.dfno = c.loccd)
                --LEFT OUTER JOIN db_reseler.dbo.msreseller c ON (b.kode_reseller COLLATE SQL_Latin1_General_CP1_CS_AS = c.resellerID)
                WHERE a.applyto = '$noregister'";
      }

      
      /* echo "<pre>";
      echo $qry;
      echo "</pre>"; */ 
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function getListIncomingPay($tipe_trx, $noregister, $listInv = "") {
      $inv = "";
      if($listInv !== "") {
        $inv .= " AND a.trcd IN ($listInv)";
      }

      if($tipe_trx === "1" || $tipe_trx === "2") {
        $tbl_trp = "klink_mlm2010.dbo.ordtrp";
        $tbl_trh = "klink_mlm2010.dbo.ordtrh";

      } else {
        $tbl_trp = "klink_mlm2010.dbo.ordivtrp";
        $tbl_trh = "klink_mlm2010.dbo.ordivtrh";
      }

      $qry = "SELECT a.paytype, b.[description] as pay_desc, a.docno, SUM(a.payamt) AS payamt
              FROM $tbl_trp a
              INNER JOIN $tbl_trh a1 ON (a.trcd = a1.trcd AND a1.flag_paid <> '1')
              LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON (a.paytype = b.id)
              WHERE a.trcd2 = '$noregister' $inv
              GROUP BY a.paytype, b.[description], a.docno";
      //echo $qry;
      return $this->getRecordset($qry, null, $this->db2);
    }

    public function simpanKW($tipe_trx, $noregister, $listInv = "") {
      if($tipe_trx === "1" || $tipe_trx === "2") {
        $tbl_register = "klink_mlm2010.dbo.ordhdr";
        $tbl_header = "klink_mlm2010.dbo.ordtrh";
        $tbl_prd = "klink_mlm2010.dbo.ordtrd";
        $tbl_trp = "klink_mlm2010.dbo.ordtrp";
        $tbl_kw_header = "klink_mlm2010.dbo.billhdr";
        $tbl_kw_prd = "klink_mlm2010.dbo.billprd";
        $tbl_kw_pay = "klink_mlm2010.dbo.billdetp";

      } else {
        $tbl_register = "klink_mlm2010.dbo.ordivhdr";
        $tbl_header = "klink_mlm2010.dbo.ordivtrh";
        $tbl_prd = "klink_mlm2010.dbo.ordivtrd";
        $tbl_trp = "klink_mlm2010.dbo.ordivtrp";
        $tbl_kw_header = "klink_mlm2010.dbo.billivhdr";
        $tbl_kw_prd = "klink_mlm2010.dbo.billivprd";
        $tbl_kw_pay = "klink_mlm2010.dbo.billivdetp";
      }

      $inv = explode(",", $listInv);
      $jum = count($inv);

      $tgl = date("Y-m-d");

      $inv = "";
      if($listInv !== "") {
        $inv .= " AND a.trcd IN ($listInv)";
      }

      $sum = "SELECT 
                x.seq, x.invoiceno, x.tdp, 
                x.ndp, x.totpay, x.bnsperiod , x.dfno , x.pricecode, x.loccd, 
                x.whcd , x.branch , x.receiptno , x.createnm , 
                x.ship , x.shipto , x.registerno,
                SUM(x.total_dp) as total_dp,
                SUM(x.total_bv) as total_bv
              FROM (
                SELECT a.seq, a.invoiceno, a.tdp, a.ndp, a.totpay, a.bnsperiod , a.dfno , a.pricecode, a.loccd, 
                        a.whcd , a.branch , a.receiptno , a.createnm , a.ship , a.shipto , a.registerno,
                    b.qtyord * c.dp as total_dp, b.qtyord * c.bv as total_bv 
                FROM $tbl_header a 
                LEFT OUTER JOIN $tbl_prd b ON (a.invoiceno = b.invoiceno) 
                LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (b.prdcd = c.prdcd AND a.pricecode = c.pricecode) 
                WHERE a.registerno = '$noregister' and a.flag_paid <> '1' and (a.receiptno is null OR a.receiptno = '') 
                $inv
              ) x
              GROUP BY x.seq, x.invoiceno, x.tdp, 
                x.ndp, x.totpay, x.bnsperiod , x.dfno , x.pricecode, x.loccd, 
                x.whcd , x.branch , x.receiptno , x.createnm , 
                x.ship , x.shipto , x.registerno";

      //echo $sum;
      $resList = $this->getRecordset($sum, null, $this->db2);
      $jum2 = count($resList);

      /* if($jum !== $jum2) {
        return jsonFalseResponse("Jumlah Transaksi yang akan di generate ada yang tidak valid..");
      } */

      if($tipe_trx == 1) {
        $no_kw = $this->getKWNo("KW", "R");
        //$no_kw = "KWTESDION01R";
      }else{
        $no_kw = $this->getKWNoSTK("KW", "");
      }

      $checkIFExist = "SELECT trcd FROM $tbl_kw_header WHERE trcd = '$no_kw'";
      $checkKWexist = $this->getRecordset($checkIFExist, null, $this->db2);
      if($checkKWexist !== null) {
        return jsonFalseResponse("Double No KW : $no_kw"); 
      }
      

      //$noregister = $resList[0]->registerno;

      $qry_register = "SELECT a.dfno, a.ship, a.shipto, a.loccd 
              FROM $tbl_register a WHERE a.trcd = '$noregister'";
      $res_reg = $this->getRecordset($qry_register, null, $this->db2);
      if($res_reg === null) {
        return jsonFalseResponse("No Register $noregister tidak ditemukan..");
      }

      $tdp = 0;
      $tbv = 0;
      foreach($resList as $dtaInv) {
        $tdp += $dtaInv->total_dp;
        $tbv += $dtaInv->total_bv;
      }

      
      $totinvoice = count($resList);
      $loccd = $res_reg[0]->loccd;
      $dfno = $res_reg[0]->dfno;

      $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
			/* $dbqryx->trans_start(); */


      $kw_header = "INSERT INTO $tbl_kw_header (trcd, etdt, createdt, updatedt, trdt, dfno, applyto, createnm, updatenm, 
                       loccd, tpv, tbv, npv, nbv, tdp, ndp, totinvoice, totpay, statusbo, flag_ship) 
                    VALUES ('$no_kw', '$tgl', '$tgl', '$tgl', '$tgl', '$dfno', '$noregister', '".$this->username."', '".$this->username."',
                       '$loccd', '$tbv', '$tbv', '$tbv', '$tbv', '$tdp', '$tdp', '$totinvoice', '$tdp', '1', '0')";
      
      $dbqryx->query($kw_header);
      /* echo "<pre>";
      echo $kw_header; 
      echo "<br />";  */

      $kw_prd = "INSERT INTO $tbl_kw_prd (trcd, prdcd, qtyord, qtyremain, dp, pv,bv)
                  SELECT '$no_kw', a.prdcd, SUM(a.qtyord), SUM(a.qtyord), c.dp, c.bv, c.bv
                  FROM $tbl_prd a 
                  LEFT OUTER JOIN $tbl_header b ON (a.invoiceno = b.invoiceno)
                  LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (a.prdcd = c.prdcd AND c.pricecode = b.pricecode)
                  WHERE a.registerno = '$noregister'
                  and a.invoiceno IN ($listInv)
                  GROUP BY a.prdcd, c.dp, c.bv";
      $dbqryx->query($kw_prd);
      /* echo $kw_prd;    
      echo "<br />"; */     

      $qry_trp = "SELECT a.paytype, a.docno, SUM(a.payamt) AS payamt
              FROM $tbl_trp a 
              LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON (a.paytype = b.id)
              WHERE a.trcd2 = '$noregister' and a.trcd IN ($listInv)
              GROUP BY a.paytype, b.[description], a.docno";
      $res_reg = $this->getRecordset($qry_trp, null, $this->db2);
      $urut = 1;
      foreach($res_reg as $pay) {
        
        $kw_trp = "INSERT INTO $tbl_kw_pay (trcd, seqno, paytype, docno, payamt) 
                   VALUES ('$no_kw', '$urut', '".$pay->paytype."', '".$pay->docno."', '".$pay->payamt."')";
        $urut++;
        $dbqryx->query($kw_trp);
        /* echo $kw_trp;
        echo "<br />"; */
      }

      $upd_ordtrh = "UPDATE a SET a.flag_paid = '1', a.receiptno = '$no_kw'
                     FROM $tbl_header a 
                     WHERE a.invoiceno IN ($listInv)";
      $dbqryx->query($upd_ordtrh);
      /* echo $upd_ordtrh;
      echo "<br />";
      echo "</pre>"; */

      if($tipe_trx === "3") {
          $upd_ordtrh = "UPDATE a SET a.receiptno = '$no_kw'
                          FROM klink_mlm2010.dbo.newivtrh a 
                          WHERE a.trcd IN ($listInv)";
          $dbqryx->query($upd_ordtrh);
      }

      //transaksi stokis tidak perlu masuk ke newtrh, newtrd dan newtrp karena
      //itu dari proses end of day
      if($tipe_trx !== "3") {
        foreach($resList as $dtaInv) {
          $trcd = $dtaInv->invoiceno;
          $bnsperiod = $dtaInv->bnsperiod;
          $dfno = $dtaInv->dfno;
          $loccd = $dtaInv->loccd;

          $tdpx = $dtaInv->total_dp;
          $tbvx = $dtaInv->total_bv;
          $whcd = $dtaInv->whcd;
          $pricecode = $dtaInv->pricecode;
          $branch = $dtaInv->branch;
          $createnm = $dtaInv->createnm;
          $ship = $dtaInv->ship;
          $shipto = $dtaInv->shipto;
          $registerno = $dtaInv->registerno;
          
          $ins_newtrh = "INSERT INTO klink_mlm2010.dbo.newtrh (trcd, orderno, bnsperiod, trtype, etdt, trdt, dfno, loccd, sc_dfno, sc_co, taxrate,
                            tdp, pay1amt,  ndp, totpay, tpv, tbv, npv, whcd, branch, pricecode, createnm,
                            ship, shipto, receiptno, registerno)
                        VALUES ('$trcd', '$trcd', '$bnsperiod', 'SA0', '$tgl', '$tgl', '$dfno', '$loccd', '$loccd', '$loccd', 10, 
                            '$tdpx', '$tdpx', '$tdpx', '$tdpx', '$tbvx', '$tbvx', '$tbvx', '$whcd' , '$branch', '$pricecode', '$createnm',
                            '$ship', '$shipto', '$no_kw', '$registerno')";
          $dbqryx->query($ins_newtrh);
          /* echo $ins_newtrh;
          echo "<br />";
          echo "</pre>"; */

        }

        $ins_newtrd = "INSERT INTO klink_mlm2010.dbo.newtrd (trcd, prdcd, qtyord, dp, bv,  pricecode)
                        SELECT a.invoiceno , a.prdcd , a.qtyord , a.dp , a.bv , b.pricecode 
                        FROM klink_mlm2010.dbo.ordtrd a
                        INNER JOIN klink_mlm2010.dbo.ordtrh b ON (a.invoiceno = b.invoiceno )
                        WHERE a.invoiceno IN ($listInv)";
        $dbqryx->query($ins_newtrd);
        /* echo $ins_newtrd;
        echo "<br />";
        echo "</pre>"; */

        $ins_newtrp = "INSERT INTO klink_mlm2010.dbo.newtrp (trcd, seqno , paytype , docno, payamt , trcd2, vchtype )
                        SELECT a.trcd, a.seqno , a.paytype , a.docno , a.payamt , a.trcd2 , a.vchtype 
                        FROM klink_mlm2010.dbo.ordtrp a
                        WHERE a.trcd IN ($listInv)";
        $dbqryx->query($ins_newtrp);
      }
     /*  echo $ins_newtrp;
      echo "<br />";
      echo "</pre>"; */
      

      /* $dbqryx->trans_complete(); */

      /* if ($dbqryx->trans_status() === FALSE) {
          $dbqryx->trans_rollback();
          return array(
            "response" => "false"
          );
      } else {
          $dbqryx->trans_commit(); */
          $arr = array(
            "no_kw" => $no_kw,
            "tdp" => $tdp,
            "tbv" => $tbv,
            "totinvoice" => $totinvoice,
            "createnm" => $this->username,
            "createdt" => $tgl
          );
          return array(
            "response" => "true",
            "arrayData" => $arr,
            "message" => "Berhasil menyimpan Payment Receipt, No : $no_kw"
          );
      //} 

    }

    function getDataByKw($tipe_trx, $trcd) {
      if($tipe_trx === "1" || $tipe_trx === "2") {
        $tbl_kw_header = "klink_mlm2010.dbo.billhdr";
        $tbl_reg_header = "klink_mlm2010.dbo.ordhdr";
        $tbl_cn = "klink_mlm2010.dbo.ordtrh";
        $tbl_pay = "klink_mlm2010.dbo.billdetp";
      } else {
        $tbl_kw_header = "klink_mlm2010.dbo.billivhdr";
        $tbl_reg_header = "klink_mlm2010.dbo.ordivhdr";
        $tbl_cn = "klink_mlm2010.dbo.ordivtrh";
        $tbl_pay = "klink_mlm2010.dbo.billivdetp";
      } 

      if($tipe_trx === "1") {
        $qry = "SELECT a.trcd as no_kw, a.applyto as registerno, 
                  CONVERT(VARCHAR(10), a.createdt, 120) as createdt,
                  a.dfno, b.kode_reseller, c.fullnm as nama_reseler
                FROM $tbl_kw_header a
                LEFT OUTER JOIN $tbl_reg_header b ON (a.applyto = b.trcd)
                LEFT OUTER JOIN db_reseler.dbo.msreseller c 
                  ON (b.kode_reseller COLLATE SQL_Latin1_General_CP1_CS_AS = c.resellerID 
                    AND b.kode_reseller is not null AND b.kode_reseller <> '')
                WHERE a.trcd = '$trcd'";
      } else if($tipe_trx === "2") {
        $qry = "SELECT a.trcd as no_kw, a.applyto as registerno, 
                  CONVERT(VARCHAR(10), a.createdt, 120) as createdt,
                  a.dfno, c.dfno as kode_reseller, c.fullnm as nama_reseler
                FROM $tbl_kw_header a
                LEFT OUTER JOIN $tbl_reg_header b ON (a.applyto = b.trcd)
                LEFT OUTER JOIN db_reseler.dbo.msmemb c 
                  ON (b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = c.dfno)
                WHERE a.trcd = '$trcd'";
      } else {
        $qry = "SELECT a.trcd as no_kw, a.applyto as registerno, 
                  CONVERT(VARCHAR(10), a.createdt, 120) as createdt,
                  a.dfno, c.loccd as kode_reseller, c.fullnm as nama_reseler
                FROM $tbl_kw_header a
                LEFT OUTER JOIN $tbl_reg_header b ON (a.applyto = b.trcd)
                LEFT OUTER JOIN klink_mlm2010.dbo.mssc c 
                  ON (a.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = c.loccd)
                WHERE a.trcd = '$trcd'";
      }
      $header = $this->getRecordset($qry, null, $this->db2);
      if($header === null) {
        $arr = array(
          "response" => "false",
          "message" => "No KW $trcd tidak ditemukan.."
        );
        return $arr;
      }

      $qry2 = "SELECT a.invoiceno, a.tdp
                FROM $tbl_cn a
                WHERE a.receiptno = '$trcd'";
      $listinv = $this->getRecordset($qry2, null, $this->db2);

      $qry3 = "SELECT a.paytype, b.[description], a.docno, a.payamt
                FROM $tbl_pay a
                LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON (a.paytype = b.id)
                WHERE a.trcd = '$trcd'";
      $payment = $this->getRecordset($qry3, null, $this->db2);

      $arr = array(
        "response" => "true",
        "header" => $header,
        "listinv" => $listinv,
        "payment" => $payment,
      );
      return $arr;
    }

    function getBillivHdr($param, $value) {
          $billivhdr = "SELECT DISTINCT
              CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
              CONVERT(VARCHAR(10), a.updatedt, 120) AS updatedt,
              a.createnm, a.updatenm, a.trcd, a.dfno, a.tdp, a.tbv, a.statusbo,a.flag_ship,
              a.totinvoice, a.flagOL, b.GDO, a.applyto,
              CONVERT(VARCHAR(10), c.etdt, 120) AS GDOdt, c.createnm as GDO_createnm,
              c.whcd, b.trtype,
              e.NO_DO as do_wms,
              e.CREATED_BY as do_wms_create_by,
              CONVERT(VARCHAR(10), e.CREATED_DATE, 120) AS do_wms_create_dt,
              e.ID_STOCKIES as sent_to, 
              f.WAREHOUSE_CODE + ' - ' + f.WAREHOUSE_NAME as sent_from
              FROM billivhdr a
              LEFT OUTER JOIN intrh b ON (a.trcd = b.applyto)
              LEFT OUTER JOIN gdohdr c ON (b.GDO = c.trcd)
              LEFT OUTER JOIN klink_whm.dbo.T_DETAIL_DO d ON (a.trcd COLLATE SQL_Latin1_General_CP1_CI_AS = d.NO_KWITANSI)
              LEFT OUTER JOIN klink_whm.dbo.T_DO e ON (e.ID_DO = d.ID_DO)
              LEFT OUTER JOIN klink_whm.dbo.MASTER_WAREHOUSE f ON (e.ID_WAREHOUSE = f.ID_WAREHOUSE)
              WHERE a.$param = '$value'";
      /* if($this->username == "DION") {
        echo "<pre>";
        echo $billivhdr;
        echo "</pre>";
      } */				
      $resBillivhdr = $this->getRecordset($billivhdr, null, $this->db2);
      return $resBillivhdr;
    }

    function getKWProduct($table, $param, $value) {
      $qry = "SELECT a.prdcd, b.prdnm, a.qtyord, a.dp
          FROM $table a
          LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
          WHERE a.$param = '$value'
          GROUP BY a.prdcd, b.prdnm, a.qtyord, a.dp
          ORDER BY a.prdcd";
      //echo $qry;
      $billPrd = $this->getRecordset($qry, null, $this->db2);
      return $billPrd;
    }

    function getKWListPay($table, $param, $value) {
      $qry = "SELECT a.trcd, a.paytype, b.[description], a.docno,
          a.payamt, a.notes, a.deposit
          FROM $table a
          LEFT OUTER JOIN paytype b ON (a.paytype = b.id)
          WHERE a.$param = '$value'";
      $billPrd = $this->getRecordset($qry, null, $this->db2);
      return $billPrd;
    }

    function getOrdTrhHeader($tipe, $param, $value) {
      if($tipe == "stk") {
        $ordivTrh = "SELECT a.trcd, a.ordtype, a.trtype, a.dfno,
                a.loccd, a.docno, a.receiptno, a.flag_paid,
                CONVERT(VARCHAR(10), a.bnsperiod, 120) AS bnsperiod,
                CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
                CONVERT(VARCHAR(10), a.registerdt, 120) AS registerdt,
                a.tdp, a.tbv, b.onlinetype
              FROM ordivtrh a
              INNER JOIN ordivhdr b ON (a.registerno = b.trcd)
              WHERE a.$param = '$value'";
              //echo $ordivTrh;
              //LEFT OUTER JOIN custpaydet b ON (a.trcd = b.trcd)
        $resOrdivTrh = $this->getRecordset($ordivTrh, null, $this->db2);
      } else {
        $ordivTrh = "SELECT a.trcd, a.ordtype, a.trtype, a.dfno,
            a.loccd, a.docno, a.receiptno, a.flag_paid,
            CONVERT(VARCHAR(10), a.bnsperiod, 120) AS bnsperiod,
            CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
            CONVERT(VARCHAR(10), a.registerdt, 120) AS registerdt,
            a.tdp, a.tbv
          FROM ordtrh a
          WHERE a.$param = '$value'";
          //echo $ordivTrh;
          //LEFT OUTER JOIN custpaydet b ON (a.trcd = b.trcd)
        $resOrdivTrh = $this->getRecordset($ordivTrh, null, $this->db2);
      }
      //echo $resOrdivTrh;
      return $resOrdivTrh;
    }

    
    public function getListKWReport($tipe, $state, $datefrom, $dateto) {
      /* $inv = "";
      if($listInv !== "") {
        $inv .= " AND a.invoiceno IN ($listInv)";
      } */

      if($tipe === "1" || $tipe === "2") {
        $tbl_header = "klink_mlm2010.dbo.billhdr";
        $tbl_reg = "klink_mlm2010.dbo.ordtrh";

      } else {
        $tbl_header = "klink_mlm2010.dbo.billivhdr";
        $tbl_reg = "klink_mlm2010.dbo.ordivtrh";
       
      }

      if($state == "2"){
        $trcd="AND b.trcd is not null AND a.trcd <> ''";

      }elseif ($state == "3") {
        $trcd=" AND b.trcd is null ";

      }else{
        $trcd="";
      }

      if($tipe == "1") {
        $qry = "SELECT CONVERT(VARCHAR(10), a.invoicedt, 120) as createdt, a.registerno, 
              a.dfno as sc_dfno, a.createnm, b.trcd as receiptno
              FROM $tbl_reg a
              LEFT OUTER JOIN $tbl_header b ON (a.receiptno = b.trcd)
              WHERE CONVERT(VARCHAR(10), a.invoicedt, 120) BETWEEN '$datefrom' AND '$dateto'
              AND a.createnm <> 'ECOMMERCE' $trcd 
              GROUP BY CONVERT(VARCHAR(10), a.invoicedt, 120), a.registerno, a.dfno, a.createnm, b.trcd";
      } else if($tipe == "2") {
        $qry = "SELECT CONVERT(VARCHAR(10), a.invoicedt, 120) as createdt, a.registerno, 
              a.dfno as sc_dfno, a.createnm, b.trcd as receiptno
              FROM $tbl_reg a
              LEFT OUTER JOIN $tbl_header b ON (a.receiptno = b.trcd)
              WHERE CONVERT(VARCHAR(10), a.invoicedt, 120) BETWEEN '$datefrom' AND '$dateto'
              AND a.createnm <> 'ECOMMERCE' $trcd 
              GROUP BY CONVERT(VARCHAR(10), a.invoicedt, 120), a.registerno, a.dfno, a.createnm, b.trcd";
      } else {

      $qry = "SELECT CONVERT(VARCHAR(10), a.invoicedt, 120) as createdt, a.registerno, 
              a.sc_dfno, a.createnm, b.trcd as receiptno
              FROM $tbl_reg a
              LEFT OUTER JOIN $tbl_header b ON (a.receiptno = b.trcd)
              WHERE CONVERT(VARCHAR(10), a.invoicedt, 120) BETWEEN '$datefrom' AND '$dateto'
              AND a.createnm <> 'ECOMMERCE' $trcd 
              GROUP BY CONVERT(VARCHAR(10), a.invoicedt, 120), a.registerno, a.sc_dfno, a.createnm, b.trcd";
      }
      echo "<pre>";
      echo $qry;
      echo "</pre>"; 
      return $this->getRecordset($qry, null, $this->db2);
    }

    function getDOByPaymentReceiptID($trcd) {
      $qry = "SELECT *
              FROM klink_mlm2010.dbo.DO_NINGSIH a 
              WHERE a.no_kwitansi = '$trcd'";
      return $this->getRecordset($qry, null, $this->db2);
    }

    function getHeaderKw($trcd) {
      
    }

    function batalKw($trcd) {
      if($trcd === "") {
        return jsonFalseResponse("No KW harus diisi / tidak boleh kosong..");
      }

      $checkDO = $this->getDOByPaymentReceiptID($trcd);
      if($checkDO !== null) {
        $no_do = $checkDO[0]->no_do;
        $tgl = $checkDO[0]->do_date;
        $wh = $checkDO[0]->warehouse_name;
        return jsonFalseResponse("No KW sudah dibuat DO, No : $no_do, Tgl : $tgl Create By : $wh");
      }

      $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
      $qry = "SELECT a.trcd FROM klink_mlm2010.dbo.billivhdr a WHERE a.trcd = '$trcd'";
      $result = $this->getRecordset($qry, null, $this->db2);
      if($result !== null) {
        $delete = "DELETE FROM billivhdr WHERE trcd = '$trcd'";
        $delete2 = "DELETE FROM billivprd WHERE trcd = '$trcd'";
        $delete3 = "DELETE FROM billivdetp WHERE trcd = '$trcd'";
        $update = "UPDATE a SET a.flag_paid = '0', a.receiptno = '' 
                   FROM klink_mlm2010.dbo.ordivtrh a 
                   WHERE a.receiptno = '$trcd'";

        echo "<pre>";
        print_r($delete);
        print_r($delete2);
        print_r($delete3);
        print_r($update);
        echo "</pre>";
        /* $dbqryx->query($delete);
        $dbqryx->query($delete2);
        $dbqryx->query($delete3);
        $dbqryx->query($update); */
      } else {
        $qry = "SELECT a.trcd FROM klink_mlm2010.dbo.billhdr a WHERE a.trcd = '$trcd'";
        $resultInv = $this->getRecordset($qry, null, $this->db2);
        if($resultInv !== null) {
          $delete = "DELETE FROM billhdr WHERE trcd = '$trcd'";
          $delete2 = "DELETE FROM billprd WHERE trcd = '$trcd'";
          $delete3 = "DELETE FROM billdetp WHERE trcd = '$trcd'";
          $update = "UPDATE a SET a.flag_paid = '0', a.receiptno = '' 
                    FROM klink_mlm2010.dbo.ordtrh a 
                    WHERE a.receiptno = '$trcd'";
          echo "<pre>";
          print_r($delete);
          print_r($delete2);
          print_r($delete3);
          print_r($update);
          echo "</pre>";
          /* $dbqryx->query($delete);
          $dbqryx->query($delete2);
          $dbqryx->query($delete3);
          $dbqryx->query($update); */
        } else {
          return jsonFalseResponse("No KW $trcd tidak ditemukan..");
        }
      }

    }
}