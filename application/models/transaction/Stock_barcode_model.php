<?php
class Stock_barcode_model extends MY_Model {

	function __construct() {
        // Call the Model constructor
        parent::__construct();

    }

	function getListStkbarMenu($groupid) {
		$qry = "SELECT a.id, a.[desc]
		        FROM stockbarcode_menu a
		        INNER JOIN stockbarcode_groupmenu b ON (a.id = b.stockbarcode_id)
		        WHERE b.groupid = $groupid
		        ORDER BY a.id desc";
		return $this->getRecordset($qry, null, $this->db1);
	}

  function detailScaProduk($trcd,$prdcd) {
    // $header = $this->getDataDetail($id);
    $detail = $this->selectDetail($trcd,$prdcd);

    if($detail == null) {
        $arr = array("response" => "false");
    }
    else {
        $arr = array("response" => "true", "detail" => $detail);
      }
    return $arr;
}

function selectDetail($trcd,$prdcd,$type = "array") {
  $qry = "SELECT trcd,prdcd,barcode from klink_mlm2010.dbo.sc_newtrd_barcode
            where trcd='$trcd' and prdcd='$prdcd'";
  return $this->getRecordset($qry, null, $this->db2);
  }

  function getDataDetail($id, $type = "array")
    {
        $qry = "SELECT
        xx1.trcd,
        xx1.prdcd,
        xx1.prdnm,
        xx1.qtyord,
        ISNULL(COUNT(x2.barcode), 0) as jumlah_sdh_dibarcode
            FROM
            (
            SELECT
                    x1.trcd,
                    x1.prdcd,
                    x1.prdnm,
                    SUM(x1.qty) as qtyord
            FROM
            (
                    SELECT
                    x.trcd,
                    ISNULL(x.prdcdDet, x.prdcd) AS prdcd,
                    ISNULL(x.prdcdNmDet, x.prdnm) AS prdnm,
                    CASE
                        WHEN x.bundle_qty IS NULL THEN x.total_qty
                        ELSE x.bundle_qty * x.total_qty
                    END AS qty
                    FROM
                    (
                    SELECT a.trcd, a.prdcd, b.prdnm, SUM(a.qtyord) as total_qty, b1.prdcdDet , b1.prdcdNmDet , b1.qty as bundle_qty
                    FROM klink_mlm2010.dbo.sc_newtrd a
                    LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
                    LEFT OUTER JOIN klink_mlm2010.dbo.newera_PRDDET b1 ON (a.prdcd = b1.prdcdCat)
                    WHERE a.trcd = '$id'
                    GROUP BY a.trcd, a.prdcd, b.prdnm, b1.prdcdDet , b1.prdcdNmDet, b1.qty
                    ) x
            ) x1
            GROUP BY x1.trcd, x1.prdcd, x1.prdnm
            ) xx1
            LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd_barcode x2
                ON (xx1.trcd COLLATE SQL_Latin1_General_CP1_CI_AI = X2.trcd AND xx1.prdcd COLLATE SQL_Latin1_General_CP1_CI_AI = x2.prdcd)
            GROUP BY xx1.trcd, xx1.prdcd, xx1.prdnm, xx1.qtyord
            ORDER BY xx1.prdnm";

        //echo $qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getDataDetailRange($to, $from, $username, $type = "array")
    {
        // $username = $this->session->userdata('username');

        if($username=='BID06'){
            $loccd = $this->input->post('loccd');
            if($loccd == NULL OR $loccd ==''){
                $where_user = "";
            }else{
                $where_user = "and a.loccd = '$loccd'";
            }
            
        }else{
            $where_user = "and a.loccd = '$username'";
        }
        $qry = "SELECT a.trcd,
            CASE WHEN a.createnm = 'ECOMMERCE' THEN a.trcd ELSE a.orderno END AS orderno,
            a.dfno,  convert(varchar(10),  a.etdt, 120) as etdt, a.tdp, a.tbv,a.createnm, b.fullnm
            FROM klink_mlm2010.dbo.sc_newtrh a
            join klink_mlm2010.dbo.msmemb as b on a.dfno = b.dfno
            --join klink_mlm2010.dbo.sc_newtrd as c on a.trcd = c.trcd
            WHERE
            (a.etdt BETWEEN '$from' AND '$to')
            $where_user
            ORDER BY a.etdt ASC";

        // echo $qry;
        // die();
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getDataDetV2($id, $type = "array") {
        $qry = "SELECT
                        x1.trcd,
                        x1.prdcd,
                        x1.prdnm,
                        SUM(x1.qty) as qtyord
                FROM
                (
                        SELECT
                        x.trcd,
                        ISNULL(x.prdcdDet, x.prdcd) AS prdcd,
                        ISNULL(x.prdcdNmDet, x.prdnm) AS prdnm,
                        CASE
                            WHEN x.bundle_qty IS NULL THEN x.total_qty
                            ELSE x.bundle_qty * x.total_qty
                        END AS qty
                        FROM
                        (
                        SELECT a.trcd, a.prdcd, b.prdnm, SUM(a.qtyord) as total_qty, b1.prdcdDet , b1.prdcdNmDet , b1.qty as bundle_qty
                        FROM klink_mlm2010.dbo.sc_newtrd a
                        LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
                        LEFT OUTER JOIN klink_mlm2010.dbo.newera_PRDDET b1 ON (a.prdcd = b1.prdcdCat)
                        WHERE a.trcd = '$id'
                        GROUP BY a.trcd, a.prdcd, b.prdnm, b1.prdcdDet , b1.prdcdNmDet, b1.qty
                        ) x
                ) x1
                GROUP BY x1.trcd, x1.prdcd, x1.prdnm";

        return $this->getRecordsetArray($qry, null, $this->db2);
        
    }


    function getInformation($id,$type = "array") {


	    $qry = "SELECT a.trcd,b.dfno, b.fullnm,a.orderno, CONVERT(VARCHAR(10), a.etdt, 120) as etdt 
        FROM klink_mlm2010.dbo.sc_newtrh as a
        join klink_mlm2010.dbo.msmemb as b on a.dfno = b.dfno
        where a.trcd='$id'";
		//echo $qry;
		return $this->getRecordset($qry, null, $this->db2);
    }
    function getBarcode($trcd, $prdcd)
    {
        $qry = "SELECT * FROM klink_mlm2010.dbo.sc_newtrd_barcode where prdcd ='$prdcd' AND trcd ='$trcd'";

        //echo $qry;
        return $this->getRecordsetArray($qry, null, $this->db2);
        // return $this->get_recordset_array($qry, $type, "alternate");
    }

    function getBarcodeV2($trcd, $prdcd)
    {
        // die('model');
        $hasil = $this->getBarcode($trcd, $prdcd);
        $total_qty = 0;
        for($i = 0; $i < count($hasil); $i++) {
            $jum = count($hasil);
            
            for($i = 0; $i < $jum; $i++) {
                $exp = explode("|", $hasil[$i]['barcode']);
                $jum_per_record = count($exp);
                $total_qty += $jum_per_record;
            
            }
        }

        return $total_qty;
    }

  function detailTransaction($id) {
    $header = $this->getDataDetail($id);
    $detail = $this->getInformation($id);

    if($header == null) {
        $arr = array("response" => "false");
    } else {
        $arr = array("response" => "true", "header" => $header, "detail" => $detail);
    }
    return $arr;
}

function detailTransactionRange($to, $from) {
    $header = $this->getDataDetail($to, $from);
    $detail = $this->getInformation($to, $from);

    if($header == null) {
        $arr = array("response" => "false");
    } else {
        $arr = array("response" => "true", "header" => $header, "detail" => $detail);
    }
    return $arr;
}

  function cekTrcd($id) {

    $type = "array";
    $qry = "SELECT trcd, orderno from klink_mlm2010.dbo.sc_newtrh
                where orderno='$id'
                ORDER by trcd DESC";		//
    //echo $qry;
    return $this->getRecordset($qry, null, $this->db2);
  }

	function getListWH() {
		$qry = "select a.code, a.[description]
				from tabcourier a
				where a.IS_WH='1'
				order by a.code";
        return $this->getRecordset($qry, null, $this->db2);
	}

	function getListDOStk($stk, $from, $to){
        //$stk = $this->input->post('sendto');
        $from = date("Y/m/d",strtotime($from));
        $to = date("Y/m/d",strtotime($to));

        $qry = "SELECT a.trcd,a.shipto,a.receiptno FROM gdohdr a
                where a.shipto = '".$stk."' AND
                (CONVERT(VARCHAR(10), a.etdt, 111) between '".$from."' and '".$to."')
                order by a.trcd";
        //echo "query ".$qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getListDOWHtoStk($dotype, $sendTo, $from, $to) {
		//$from = $this->input->post('from');
		//$to = $this->input->post('to');

        //$username = $this->session->userdata('username');
		$username = $this->session->userdata('stockist');
		$usr =  substr($username, 0, 2);

		$param = "";
		//update by DION 12/05/2016
		//if ($usr == "WH"){
		if ($usr == "WH" || $username == "BID06"){
			$param = " AND a.is_wh='0'   AND (A.pl_no is null OR LTRIM(A.pl_no )='') ";
		}
		$qry = "SELECT A.gdono,
				    A.dono,
				    A.receiptno,
				    A.etdt,
				    A.shipby,
				    A.shipby_nm,
				    A.is_wh,
				    A.shipto,
				    A.dotype
				FROM V_HILAL_SCAN_FOR_WH A
				WHERE (A.etdt BETWEEN '$from' AND '$to') $param
				  AND A.shipto='$sendTo' AND a.dotype='$dotype'";		//
		echo $qry;
		return $this->getRecordset($qry, null, $this->db2);
	}

    function getListTTPStockist($stk, $from, $to) {
        $qry = "select A.bnsperiod, A.etdt, A.trcd, A.orderno, A.dfno, B.fullnm
                FROM sc_newtrh a
                LEFT OUTER JOIN msmemb B ON A.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = B.dfno
                WHERE (a.etdt BETWEEN '$from' AND '$to') AND
                  a.loccd='$stk' AND A.createnm='$stk' ";
        return $this->getRecordset($qry, null, $this->db2);
    }

	function getHeaderProdByTTP($trcd, $type = "array")
    {
        $qry = "select A.bnsperiod, A.etdt, A.trcd, A.orderno, A.dfno, B.fullnm
                FROM sc_newtrh a
                LEFT OUTER JOIN msmemb B ON A.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = B.dfno
                WHERE  A.trcd = '$trcd'";
        //echo $qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getDetailProdByTTP($trcd, $type = "array")
    {

		$qry = "SELECT XX.trcd, XX.prdcd, SUM(XX.qtyord) AS qtyord,
					   SUM(XX.jumbarcode) AS jumbarcode, XX.prdnm
				FROM(
					SELECT
					  A.trcd,
					  CASE
						WHEN X.prdcdDet is null THEN a.prdcd
					    ELSE X.prdcdDet
					  END AS prdcd,
					  CASE
						WHEN SUM(X.qty) is null THEN SUM(a.qtyord)
					    ELSE SUM(X.qty * A.qtyord)
					  END AS qtyord,
					  SUM(B.jumbarcode) AS jumbarcode,
					  CASE
						WHEN D1.prdnm IS NULL THEN D.prdnm
					    ELSE D1.prdnm
					  END AS prdnm
					FROM
					  dbo.sc_newtrd A
					  LEFT OUTER JOIN dbo.QDION_jumbarcode B ON (A.trcd COLLATE SQL_Latin1_General_CP1_CS_AS= B.trcd)
									  AND (A.prdcd COLLATE SQL_Latin1_General_CP1_CS_AS = B.prdcd)
					  LEFT OUTER JOIN dbo.msprd D ON (A.prdcd = D.prdcd)
					  LEFT OUTER JOIN newera_PRDDET X ON (A.prdcd=X.prdcdCat)
					  LEFT OUTER JOIN dbo.msprd D1 ON (X.prdcdDet = D1.prdcd)
					WHERE A.trcd = '$trcd'
					GROUP BY A.trcd, X.prdcdDet, a.prdcd, D1.prdnm, D.prdnm
				) XX
				GROUP BY XX.trcd, XX.prdcd, XX.prdnm";
			//echo $qry;
    	/*
       $qry = "SELECT
                  dbo.sc_newtrd.trcd,
                  dbo.sc_newtrd.prdcd,
                  dbo.sc_newtrd.qtyord,
                  dbo.QDION_jumbarcode.jumbarcode,
                  dbo.msprd.prdnm
                FROM
                  dbo.sc_newtrd
                  LEFT OUTER JOIN dbo.QDION_jumbarcode ON (dbo.sc_newtrd.trcd COLLATE SQL_Latin1_General_CP1_CS_AS = dbo.QDION_jumbarcode.trcd)
                  AND (dbo.sc_newtrd.prdcd COLLATE SQL_Latin1_General_CP1_CS_AS = dbo.QDION_jumbarcode.prdcd)
                  LEFT OUTER JOIN dbo.msprd ON (dbo.sc_newtrd.prdcd = dbo.msprd.prdcd)
                WHERE dbo.sc_newtrd.trcd = '$trcd'";
		 * /

       // echo $qry;
        /*$qry = "select a.trcd, a.prdcd, b.prdnm, a.qtyord
                from sc_newtrd a
                INNER JOIN msprd b on a.prdcd=b.prdcd
                where a.trcd='$trcd'
                order by b.prdnm"; */
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getDataProductByTTP($trcd) {
        $header = $this->getHeaderProdByTTP($trcd);
        $detail = $this->getDetailProdByTTP($trcd);
        if($header == null) {
            $arr = array("response" => "false");
        } else {
            $arr = array("response" => "true", "header" => $header, "detail" => $detail);
        }
        return $arr;
    }

    function getListProductBarcode($stk, $trcd, $prdcd) {
        $tbl = "HILAL_BC_newtrd";
        $fieldTrcd = "trcd";
        if($stk == "BID06") {
        	$fieldTrcd = "trcdGroup";
        	$tbl = "HILAL_BC_newtrd_wh";
        }
        $qry = "SELECT  $fieldTrcd,
						 prdcd,
						 prdcd_bc,
						 qty,
						 createnm,
						 createdt,
						 updatenm,
						 updatedt FROM $tbl WHERE $fieldTrcd = '$trcd' AND prdcd = '$prdcd'";
		//echo $qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

	function listPrdBarcodeStk($dono,$prdcd){
        $qry = "select a.trcd,a.prdcd,a.prdcd_bc,b.prdnm
                from HILAL_BC_newtrd_wh_sub a
                inner join msprd b on a.prdcd = b.prdcd COLLATE SQL_Latin1_General_CP1_CS_AS
                where a.trcd = '".$dono."' and a.prdcd = '".$prdcd."' order by a.prdcd_bc";
        return $this->getRecordset($qry, null, $this->db2);
    }

	function validateSaveBarcode($trcd)
    {
        $prdcd = $this->input->post('prdcd');
        $qry = "SELECT trcd FROM HILAL_BC_newtrh WHERE trcd = '$trcd'";
        return $this->getRecordset($qry, null, $this->db2);
    }

	function saveBarcodeHeader()
    {
        $trcd = $this->input->post('trcd');
        $createnm = $this->input->post('createnm');
        $qtysum = $this->input->post('qtysum');
        $username = $this->session->userdata('username');
        //$this->db = $this->load->database("alternate", true);
        $qry = "INSERT INTO HILAL_BC_newtrh (trcd, loccd, createnm, qtysum) VALUES
                ('$trcd', '$username', '$username', $qtysum)";
        //echo "<br />$qry<br />";
        $query = $this->executeQuery($qry, $this->db2);
        return $query;
    }

    // function saveBarcodeDetail()
    // {
    //     $trcd = $this->input->post('trcd');
    //     $prdcd = $this->input->post('prdcd');
    //     $barcode = $this->input->post('barcode');
    //     $jum = count($barcode);
    //     $x = 0;
    //     for($i = 0; $i < $jum; $i++) {
    //         if($barcode[$i] != "") {
    //         	$brcode = str_replace("MW6 Demo", "", $barcode[$i]);
    //             //echo $brcode;
    //             $qry = "INSERT INTO HILAL_BC_newtrd (trcd, prdcd, prdcd_bc, qty) VALUES
    //                     ('$trcd', '".$prdcd."', '".$brcode."', 1)";
    //             $query = $this->executeQuery($qry, $this->db2);
    //             if($query > 0) {$x++; }
    //         }
    //         //echo "<br />$qry<br />";
    //     }
    //     return $x++;
    // }

    function saveInputBarcode()
    {

        $trcd = $this->input->post('trcd');
        $header = 1;
        $detail = $this->saveBarcodeDetail();
        if($detail > 0) {
            $arr = array("response" => "true", "message" => "Input barcode berhasil..!!", "trcd" => $trcd);
        }
        return $arr;
    }

    function getTraceBarcode($barcode) {
        $type = "array";
        // $barcode = $this->input->post('tr_barcode');
        $username = $this->session->userdata('username');

        $header         = $this->headTrack($barcode);
        $detail         = $this->detTrackV2($barcode);
        $jumlah_header  =  count($header);
        $jumlah_detail  =  count($detail);
        $hitung         = $jumlah_detail + $jumlah_header;
        if($header == null){
            $header ="0";
        }
        if($detail == null){
            $detail = "0";
        }
        if($hitung > 0){
            $response = "true";
        }
        if($hitung < 1){
            $response = "false";
        }
        $arr = array("response" => $response, "header" => $header, "detail" => $detail, "jml_header" => $jumlah_header, "jml_detail" => $jumlah_detail);
        return $arr;
    }

    function headTrack($barcode) {
       
	    $qry = "SELECT A.CODE, B.NO_DO, B.ID_STOCKIES,D.fullnm as STOCKIES_NAME, B.NAMA, B.CREATED_DATE, B.ID_WAREHOUSE, C.WAREHOUSE_NAME
        FROM klink_whm.dbo.T_SCAN_DETAIL A
        INNER JOIN klink_whm.dbo.T_DO B ON A.ID_DO = B.ID_DO
        INNER JOIN klink_whm.dbo.MASTER_WAREHOUSE C ON B.ID_WAREHOUSE = C.ID_WAREHOUSE
        JOIN klink_mlm2010.dbo.mssc as D on B.ID_STOCKIES COLLATE SQL_Latin1_General_CP1_CI_AI = D.loccd
                --WHERE A.CODE LIKE '%17040091787A%'
                WHERE A.CODE LIKE '%$barcode%'
                ORDER BY B.CREATED_DATE ASC";
		// return $this->get_recordset($qry, $type, "alternate");
        return $this->getRecordset($qry, null, $this->db2);
    }

    function detTrack($barcode) {
       
	    $qry = "SELECT CONVERT(VARCHAR(10), a.tgl_barcode, 120) as tanggal, a.barcode, a.prdcd, a.trcd,  b.loccd, b.dfno, c.fullnm as asal, d.fullnm as tujuan, b.orderno
                from klink_mlm2010.dbo.sc_newtrd_barcode as a
                join klink_mlm2010.dbo.sc_newtrh as b
                join klink_mlm2010.dbo.mssc as c on b.loccd = c.loccd
                join klink_mlm2010.dbo.msmemb as d on b.dfno = d.dfno
                on a.trcd COLLATE SQL_Latin1_General_CP1_CI_AI = b.trcd
                --where a.barcode='19100025052A'
                where a.barcode LIKE '%$barcode%'
                ORDER BY tanggal ASC";
		return $this->getRecordset($qry, null, $this->db2);
    }

    function detTrackV2($barcode) {
        $qry = "SELECT CONVERT(VARCHAR(10), a.tgl_barcode, 120) as tanggal,
                        a.barcode, 
                    a.kode_stk as loccd, 
                    a.id_member_ms as dfno,
                    a.nama as fullnmm,
                    b.fullnm as asal,
                    CASE WHEN a.no_ttp is null OR a.no_ttp = '' THEN '-' ELSE a.no_ttp END AS orderno,
                    a.id_member_ms + ' - ' + a.nama as tujuan 
                FROM klink_mlm2010.dbo.sc_newtrd_barcodeV2 a
                LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.kode_stk COLLATE SQL_Latin1_General_CP1_CS_AS = b.loccd)
                WHERE a.barcode LIKE '%$barcode%'
                ORDER BY tgl_barcode ASC";
        return $this->getRecordset($qry, null, $this->db2);
    }


    function saveBarcodeDetail()
    {

        $trcd = $this->input->post('trcd');
        $prdcd = $this->input->post('prdcd');
        $barcode = $this->input->post('newmulti');
        $jum = count($barcode);
        
        $x = 0;
        $tgl_barcode = date("Y-m-d h:i:s");
        // $this->input->post('substockistcode');

        for($i = 0; $i < $jum; $i++) {
            $a = 1;
            // $name_post = "barcode".($a+$i);
            $name_post = $this->input->post('newmulti');
            $barcode = $name_post[$i];
            // echo $barcode;
                $qry = "INSERT INTO sc_newtrd_barcode (trcd, prdcd, barcode, tgl_barcode) VALUES
                ('$trcd', '$prdcd', '$barcode', '$tgl_barcode')";
                // $query = $this->db2->query($qry);
                $query =$this->executeQuery($qry, $this->db2);
            if($query > 0) {$x++; }

        }
        // die('model2');
        // $query = $this->db->query($qry);
        return $x++;
    }

    function saveInputBarcode_bak($trcd)
    {
        //$trcd = $this->input->post('trcd');
        $header = 1;

        $arr = jsonFalseResponse("Input barcode gagal..!!");
		$check = $this->validateSaveBarcode($trcd);
        if($check == null) {
            $header = $this->saveBarcodeHeader();
        }
        $detail = $this->saveBarcodeDetail();
        if($header > 0 && $detail > 0) {
            $arr = array("response" => "true", "message" => "Input barcode berhasil..!!", "trcd" => $trcd);
        }
        return $arr;
    }

	function headByDOSTK($dono){
        $qry = "SELECT a.trcd,a.shipto,b.fullnm
                FROM gdohdr a
                INNER JOIN mssc b on a.shipto = b.loccd COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.trcd = '".$dono."'";
        return $this->getRecordset($qry, null, $this->db2);

    }

    function detByDOSTK($dono){
        $qry = "SELECT a.trcd,b.prdnm,a.prdcd,a.qtyord,
                	   sum(ISNULL(c.qty, 0)) as qty_bc,
                       a.qtyord-sum(ISNULL(c.qty, 0)) as qty_bo
                FROM gdoprd a
            	  INNER JOIN msprd b on a.prdcd = b.prdcd COLLATE SQL_Latin1_General_CP1_CS_AS
                  LEFT OUTER JOIN HILAL_BC_newtrd_wh_sub c on a.trcd=c.trcd and a.prdcd=c.prdcd
                WHERE a.trcd = '".$dono."'
                GROUP BY a.trcd,b.prdnm,a.prdcd,a.qtyord order by a.prdcd";
        //echo $slc;
        return $this->getRecordset($qry, null, $this->db2);
    }

	function getDataDetailDO($dono) {
		$header = $this->headByDOSTK($dono);
        $detail = $this->detByDOSTK($dono);
        if($header == null) {
            $arr = array("response" => "false");
        } else {
            $arr = array("response" => "true", "header" => $header, "detail" => $detail);
        }
        return $arr;
	}

	function cekNewtrhStk($trcd){
        $slc= "select count(a.trcd) as jmlCount
                 from Hilal_BC_newtrh_wh_sub a
                 where a.trcd = '".$trcd."'";
        $res = $this->getRecordset($slc, null, $this->db2);
        /*$qry = $this->db->query($slc);
        $row = $qry->row();
        $xx = $row->jmlCount;*/
        return $res[0]->jmlCount;
    }

    function cekSameBarcodePrd($prdcd_bc,$trcd){
        $slc= "select count(a.prdcd_bc) as jmlCountbc
                 from Hilal_BC_newtrd_wh_sub a
                 where a.prdcd_bc = '".$prdcd_bc."' and a.trcd = '".$trcd."'";
        $res = $this->getRecordset($slc, null, $this->db2);
        /*$qry = $this->db->query($slc);
        $row = $qry->row();
        $xx = $row->jmlCount;*/
        return $res[0]->jmlCountbc;
    }

    function saveBarcodeWHToStk()
    {
        $arr = array("response" => "false", "message" => "Input barcode failed..!!");
        $trcd = $this->input->post('trcd');
        $prdcd = $this->input->post('prdcd');
        $barcode = $this->input->post('barcode');
        $sendTo = $this->input->post('sendTo');
        $jum = count($barcode);
        $username = $this->session->userdata('username');

        $cekMasuk = $this->cekNewtrhStk($trcd);

        if($cekMasuk == 0){
            $insNewtrh = "insert into Hilal_BC_newtrh_wh_sub (trcd,whcd,loccdTo,createnm)
                          values('".$trcd."','".$username."','".$sendTo."','".$username."')";
            //echo "newtrh ".$insNewtrh;
            $query = $this->db->query($insNewtrh);
        }

		/*$shipinfo = array(
				  "trcd" => $trcd,
				  "loccd" => $this->session->userdata('kode_gudang'),
				  "loccdTo" => $this->input->post('sendTo'),
				  "info" => $this->input->post('info')
				);*/

        $x = 0;
        for($i = 0; $i < $jum; $i++) {
                if($barcode[$i] != "" ) {
                    $brcode = str_replace("MW6 Demo", "", $barcode[$i]);
                    $cekSameBc = $this->cekSameBarcodePrd($brcode,$trcd);

                    if($cekSameBc == '1'){
                        $x++;
                        //$arr = array("response" => "false", "message" => "Barcode Already Exist");
                    }else{
                        $qry = "INSERT INTO HILAL_BC_newtrd_wh_sub (trcd, prdcd, prdcd_bc, qty, createnm) VALUES
                            ('".$trcd."', '".$prdcd."', '".$brcode."', 1, '".$username."')";

                        $query = $this->db->query($qry);
                        if(!$query) { $x++; }
                    }
                    //echo $brcode;
                }
            //echo "newtrd ".$qry;
        }

        //$arr = array("response" => "true", "message" => "Input barcode success..!!");
		if($x <= 0 && $barcode[0]<>'') {
			$arr = array("response" => "true", "message" => "Input Barcode Success..!!");
		}else{
		  $arr = array("response" => "false", "message" => "Barcode Already Exist / No barcode scanned");
		}
		return $arr;
    }

	function getListDOWHtoWH($sendto, $from, $to) {
		$qry = "SELECT A.gdono as trcd,
				    A.dono,
				    A.receiptno,
				    A.etdt,
				    A.shipby,
				    A.shipby_nm,
				    A.is_wh,
				    A.shipto,
				    A.dotype

				FROM V_HILAL_SCAN_FOR_WH A
				WHERE (A.etdt BETWEEN '$from' AND '$to')
				  AND A.shipby='$sendto'  AND A.is_wh='1'
				  AND (A.pl_no is null OR LTRIM(A.pl_no )='')";
		//echo $qry;
	    return $this->getRecordset($qry, null, $this->db2);
	}

	function groupingDO($arr) {
		$qry = "select A.prdcd, B.prdnm, SUM(A.qtyord) AS qtyord
				from gdoprd a
				INNER JOIN msprd B ON A.prdcd=B.prdcd
				where a.trcd in ($arr)
				GROUP BY A.prdcd, B.prdnm
				ORDER BY A.prdcd";
		//return $this->getRecordset($qry, $type, "alternate");

		//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		$listDO = str_replace("'", "`", $arr);
        if($arr != null) {
            $return = array("response" => "true", "arrayData" => $res, "listDO" => $listDO, "listDO2" => $arr);
        }  else {
            $return = array("response" => "false");
        }

	   return $return;
	}

	function getPackingListByDest($sendTo, $from, $to) {
		$kode_gudang = $this->session->userdata('kodegudang');
		$qry = "SELECT * FROM HILAL_BC_newtrh_wh A WHERE (A.createdt BETWEEN '$from' AND '$to')
				  AND A.loccd = '$kode_gudang' AND A.loccdTo='$sendTo'";
		//echo $qry;
	    return $res = $this->getRecordset($qry, null, $this->db2);
	}

	function trackingBarcode($barcode) {
		$qry = "select a.createdt,
					a.trcd,
					a.loccdFrom,
					a.fromNM,
					a.loccdTo,
					a.toNM,
					a.prdcd,
					a.prdnm,
					a.prdcd_bc
					from V_HILAL_BC_TRACK a
					where a.prdcd_bc= '$barcode' ";
		return $this->getRecordset($qry, null, $this->db2);
	}

	/*----------------
	 * PACKING LIST
	 * ---------------*/

	 function getDataBarcode($trcd, $prdcd) {
		$qry = "SELECT trcdGroup as trcd, prdcd, prdcd_bc FROM HILAL_BC_newtrd_wh WHERE trcdGroup = '$trcd' AND prdcd = '$prdcd'";
        return $this->getRecordset($qry, null, $this->db2);
	}
	 function generatePLNo() {
		$month = date("n");
		$year = date("Y");
		 $month = "0".$month;
		 $month = substr($month, -2);
		 $year = substr($year, -2);

		 //$qry = "insert into SEQ_PL$year$month(SeqVal) VALUES('A'); select TOP 1 SeqID from  SEQ_PL$year$month ORDER BY SeqID DESC";
		 $qry = "insert into SEQ_PL$year$month(SeqVal) VALUES('A')";
		 //echo $qry;
		 //$query = $this->db->query($qry);
		 $query = $this->executeQuery($qry, $this->db2);
		 $cek = "select TOP 1 SeqID from  SEQ_PL$year$month ORDER BY SeqID DESC";
		 //$cek2 = $this->db->query($cek);
		 $cek2 = $this->executeQuery($cek, $this->db2);
		if($cek2 == null) {
            $ss = 0;
        } else {
            $ss = $cek2[0]->SeqID;
        }
         $prefix = $year.$month;
		 $next_id = substr(("000000".$ss), -6);
		 $y =  strval("PL".$prefix.$next_id);
         return $y;
	}

	function insertHeaderPL($id, $listDO) {
		//$listDO = $this->input->post('listDO');
		$kode_gudang = $this->session->userdata('kodegudang');
		$username = $this->session->userdata('stockist');
		$sendTo = $this->input->post('sendTo');
		$info = $this->input->post('info');
		$totalQty = $this->input->post('totalQty');
		$qry = "INSERT INTO Hilal_BC_newtrh_wh (trcdGroup, trcd, loccd, loccdTo, dest_info, createnm, qtysum)
		        VALUES ('$id', '$listDO', '$kode_gudang', '$sendTo', '$info', '$username', $totalQty)";
	    //echo $qry;
		//$query = $this->db->query($qry);
		$query = $this->executeQuery($qry, $this->db2);
		return $query;
	}

	function insertDetailSummaryPL($id) {
		$arrReturn = true;
		$prdcd = $this->input->post('prdcd');
		$prdnm = $this->input->post('prdnm');
		$qtyord = $this->input->post('qtyord');
		$jum = count($prdcd);
		$err=0;
		for($i=0; $i < $jum; $i++) {
			$qry = "INSERT INTO Hilal_BC_newtrd_sum_wh (trcdGroup, prdcd, prdnm, qty)
		            VALUES ('$id', '$prdcd[$i]', '$prdnm[$i]', $qtyord[$i])";
			//echo $qry;
			//echo "</br >";
			//$query = $this->db->query($qry);
			$query = $this->executeQuery($qry, $this->db2);
			if(!$query) {
				$this->deletePL("Hilal_BC_newtrd_sum_wh", $id);
				$arrReturn = false;
				break;
			}
		}

		return $arrReturn;
	}

	function showSummaryPackingListByID($id) {
		$arr = array("response" => "false");
		$head = "SELECT trcdGroup, loccdTo, dest_info as info
		         FROM Hilal_BC_newtrh_wh
		         WHERE trcdGroup = '$id'";
		//$query1 = $this->db->query($head);
		$query1 = $this->getRecordset($head, null, $this->db2);

		$list = " SELECT A.trcdGroup, A.prdcd, A.prdnm,
				   A.qty, ISNULL(SUM(B.qty), 0) AS BC,
				       A.qty-ISNULL(SUM(B.qty), 0) SLH
				FROM Hilal_BC_newtrd_sum_wh a
				LEFT OUTER JOIN HILAL_BC_newtrd_wh b on a.trcdGroup=B.trcdGroup AND A.prdcd = b.prdcd
				WHERE A.trcdGroup = '$id'
				GROUP BY A.trcdGroup, A.prdcd, A.prdnm, A.qty";
		//$query2 = $this->db->query($list);
		$query2 = $this->getRecordset($list, null, $this->db2);
		if($query1 && $query2) {
		  $arr = array("response" => "true", "shipinfo" => $query1, "listPrd" => $query2);
		}

		return $arr;

	}

	function deletePL($table, $id) {
		$qry = "DELETE FROM $table WHERE trcdGroup = '$id'";
		//$query = $this->db->query($qry);
		$query = $this->executeQuery($qry, $this->db2);
		return $query;
	}

	function updatePLonGDOhdr($id, $listDO2) {
		$qry = "UPDATE gdohdr SET pl_no = '$id'
		        WHERE trcd IN ($listDO2)";
		//$query = $this->db->query($qry);
		$query = $this->executeQuery($qry, $this->db2);
		return $query;
	}

    function checkMsMember($type, $value) {
        if($type == "1") {
            $keterangan = "Data Member";
            $qry = "SELECT fullnm FROM klink_mlm2010.dbo.msmemb a WHERE a.dfno = UPPER('$value')";
        } else {
            $keterangan = "Data Stockist";
            $qry = "SELECT fullnm FROM klink_mlm2010.dbo.mssc a WHERE a.loccd = UPPER('$value')";
        }

        $hasil = $this->getRecordset($qry, null, $this->db2);
        if($hasil == null) {
            return jsonFalseResponse("$keterangan $value tidak ditemukan..");
        } else {
            return jsonTrueResponse($hasil, "");
        }
    } 

    function simpanBarcodeV2($data) {
        $jum = count($data['barcode']);

        $dbqryx  = $this->load->database("db_ecommerce", TRUE);

        $insSukses = 0;
        for($i = 0; $i < $jum; $i++) {
            
            $arr = array(
                "type" => $data['tipe'],
                "id_member_ms" => strtoupper($data['id_member_ms']),
                "kode_stk" => $data['kode_stk'],
                "nama" => $data['nama'],
                "no_ttp" => $data['no_ttp'],
                "barcode" => $data['barcode'][$i]
            );

            $hasil = $dbqryx->insert("klink_mlm2010.dbo.sc_newtrd_barcodeV2", $arr);
            if($hasil > 0) {
                $insSukses++;
            }
        }

        if($jum == $insSukses) {
            return jsonTrueResponse(null, "Barcode berhasil disimpan..");
        }

        return jsonFalseResponse("Barcode gagal disimpan..");
    }
}