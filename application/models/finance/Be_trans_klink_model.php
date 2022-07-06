<?php
class Be_trans_klink_model extends MY_Model {

	function __construct() {
        // Call the Model constructor
        parent::__construct();

    }

	function getListTrxByParam($searchby, $param, $bnsmonth=null, $bnsyear=null, $from=null, $to=null) {
		//dfno, cnno, batchno, trcd, orderno
		//echo "param searchby = $searchby";
		$qryParam = "";
		if($searchby == 'cnno'){ //dfno, cnno, batchno
			$qryParam = " A.invoiceno = '$param'";
		}elseif($searchby == 'batchno'){
			$qryParam = " A.batchno = '$param' ";
		}elseif($searchby == 'trcd'){
			$qryParam = " A.trcd = '$param' OR A.orderno = '$param' ";
		}elseif($searchby == 'dfno'){
			if($bnsyear != "" || $bnsyear != null){
				if($bnsmonth != "all"){
					$period = "$bnsyear-$bnsmonth-01";
					$qryPeriod = " A.bnsperiod = '$period' AND";
				}else{
					$qryPeriod = " YEAR(A.bnsperiod) = '$bnsyear' AND";
				}

				if($from != NULL && $to != NULL){
					$dtfrom = date("Y/m/d", strtotime($from));
					$dtto = date("Y/m/d", strtotime($to));
					$qryParam = " (CONVERT(VARCHAR(10), A.etdt, 111) between '$dtfrom' and '$dtto') AND ";
				}
				//chk-dtrange
				$qryParam = " $qryParam $qryPeriod A.dfno='$param' ";
				//echo "$qryParam<BR/>";
			}
		}
		/*
		$qry = "SELECT  A.trcd, A.orderno, A.batchno, A.invoiceno, A.trtype,
						A.ttptype, A.etdt, A.batchdt, A.remarks, A.createdt,
						A.createnm, A.updatedt, A.updatenm, A.dfno, A.distnm, A.loccd, A.loccdnm,
						A.sc_co, A.sc_conm, A.sc_dfno, A.sc_dfnonm, A.tdp,
						A.tbv, A.bnsperiod, A.statusTrx
	   			 FROM V_HILAL_CHECK_BV_ONLINE_HDR a
	   			 WHERE $qryParam" ;
		 *
		 */
		$qry = "SELECT  A.trcd, A.orderno, A.batchno, A.invoiceno, A.trtype, A.ttptype,
					    MAX(A.etdt) AS etdt,  MAX(A.createdt) AS createdt,
					    A.dfno, A.distnm, A.loccd, A.loccdnm, A.sc_co, A.sc_conm, A.sc_dfno,
					    A.sc_dfnonm, A.tdp, A.tbv, A.bnsperiod, A.statusTrx, A.flag_batch,
						b.dfno as bv_ori
	   			 FROM V_HILAL_CHECK_BV_ONLINE_HDR a
				 LEFT OUTER JOIN klink_mlm2010.dbo.NHBJAM2019 b ON (a.trcd = b.trcd)	
	   			 WHERE $qryParam
	   			 GROUP BY A.trcd, A.orderno, A.batchno, A.invoiceno, A.trtype, A.ttptype,
					    A.dfno, A.distnm, A.loccd, A.loccdnm, A.sc_co, A.sc_conm, A.sc_dfno,
					    A.sc_dfnonm, A.tdp, A.tbv, A.bnsperiod, A.statusTrx, A.flag_batch,
						b.dfno
				ORDER BY MAX(A.etdt), A.trcd" ;
		if($this->username == "DION") {
			echo "<pre>";
			echo $qry;
			echo "</pre>";
		}		
	   	//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function getLisrRekapPrd($searchby, $param, $bnsmonth=null, $bnsyear=null, $from=null, $to=null) {
		if($bnsyear != "" || $bnsyear != null){
			if($bnsmonth != "all"){
				$period = "$bnsyear-$bnsmonth-01";
				$qryPeriod = " AND b.bnsperiod = '$period'";
			}else{
				$qryPeriod = " AND YEAR(b.bnsperiod) = '$bnsyear'";
			}

			
			//chk-dtrange
			$qryParam = "b.dfno='$param' $qryPeriod";
			//echo "$qryParam<BR/>";
		}

		$qry = "SELECT a.prdcd , c.prdnm, SUM(a.QTY) as jumlah_qty 
				FROM klink_mlm2010.dbo.QTWA_SALESDETAIL a
				LEFT OUTER JOIN klink_mlm2010.dbo.newtrh b ON (a.trcd = b.trcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd c ON (a.prdcd = c.prdcd)	
	   			WHERE $qryParam
	   			GROUP BY a.prdcd , c.prdnm" ;
		$res = $this->getRecordset($qry, null, $this->db2);

		if($this->username == "DION") {
			/* echo "<pre>";
			echo $qry;
			echo "</pre>"; */
			//print_r($res);
		}		
	   	//echo $qry;
		
		return $res;
	}

	function listTTPContainPrd($idmember, $prd, $bns) {
		$qry = "SELECT a.trcd, b.orderno, CONVERT(VARCHAR(10), b.etdt, 120) as tgl_trx , 
		            b.loccd,  
					a.prdcd, c.prdnm, CAST(a.qty as INT) as qty
				FROM klink_mlm2010.dbo.QTWA_SALESDETAIL a
				LEFT OUTER JOIN klink_mlm2010.dbo.newtrh b ON (a.trcd = b.trcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd c ON (a.prdcd = c.prdcd)
				WHERE b.dfno = '$idmember' and b.bnsperiod = '$bns'
				and a.prdcd = '$prd'
				ORDER BY b.etdt" ;
		$res = $this->getRecordsetArray($qry, null, $this->db2);	
		return $res;
	}

	function getTrxByTrcdHead($param, $value) {
		/* $qry = "SELECT  TOP 1 a.trcd, a.orderno, a.batchno, a.invoiceno, a.trtype, a.ttptype,
						a.etdt, CONVERT(VARCHAR(10), a.batchdt, 120) as batchdt,
						a.remarks, a.createdt, a.createnm, A.updatedt, A.updatenm, a.dfno,
						a.distnm, a.loccd, a.loccdnm, a.sc_co, a.sc_conm, a.sc_dfno,
						a.sc_dfnonm, a.tdp, a.tbv, a.bnsperiod, a.statusTrx, a.id_deposit, a.flag_batch,
						b.createnm as cnms_createnm, CONVERT(VARCHAR(10), b.createdt, 120) as cnms_createdt,
						b.receiptno, c.createdt as kw_date,
						c.createnm as kw_createnm,
						d.GDO, e.createnm as gdo_createnm,
						CONVERT(VARCHAR(10), e.etdt, 120) as gdo_createdt,
						e.shipto , f.NO_DO as do_wms,

										f.CREATED_BY as do_wms_create_by,
										CONVERT(VARCHAR(10), f.CREATED_DATE, 120) AS do_wms_create_dt,
										f.ID_STOCKIES as sent_to, 
										g.WAREHOUSE_NAME as sent_from
				FROM klink_mlm2010.dbo.V_HILAL_CHECK_BV_ONLINE_HDR a
				LEFT OUTER JOIN klink_mlm2010.dbo.ordivtrh b ON (a.invoiceno = b.invoiceno AND a.invoiceno is not NULL AND a.invoiceno != '')
				LEFT OUTER JOIN klink_mlm2010.dbo.billivhdr c ON (b.registerno = c.applyto AND b.registerno is not NULL AND b.registerno != '')
				LEFT OUTER JOIN klink_mlm2010.dbo.intrh d ON (c.trcd = d.applyto AND c.trcd is not NULL AND c.trcd != '')
				LEFT OUTER JOIN klink_mlm2010.dbo.gdohdr e ON (d.GDO = e.trcd AND d.GDO is not NULL AND d.GDO != '')
				LEFT OUTER JOIN klink_whm.dbo.T_DETAIL_DO e1 
				ON (b.receiptno COLLATE SQL_Latin1_General_CP1_CI_AS = e1.NO_KWITANSI)
				LEFT OUTER JOIN klink_whm.dbo.T_DO f ON (e1.ID_DO = f.ID_DO)
				LEFT OUTER JOIN klink_whm.dbo.MASTER_WAREHOUSE g ON (f.ID_WAREHOUSE = g.ID_WAREHOUSE)
				WHERE a.$param = '$value'" ; */
	   	// echo $qry;
	   	//echo "<br />";

		$qry = "SELECT  TOP 1 a.trcd, a.orderno, a.batchno, a.invoiceno, a.trtype, a.ttptype,
						a.etdt, CONVERT(VARCHAR(10), a.batchdt, 120) as batchdt,
						a.remarks, a.createdt, a.createnm, A.updatedt, A.updatenm, a.dfno,
						a.distnm, a.loccd, a.loccdnm, a.sc_co, a.sc_conm, a.sc_dfno,
						a.sc_dfnonm, a.tdp, a.tbv, a.bnsperiod, a.statusTrx, a.id_deposit, a.flag_batch,
						a.cnms_createnm, a.cnms_createdt,
						a.kw_no as receiptno, a.kw_date, a.kw_createnm,
						d.GDO, e.createnm as gdo_createnm, CONVERT(VARCHAR(10), e.etdt, 120) as gdo_createdt, e.shipto , 
						a.do_wms, a.do_wms_create_by, a.do_wms_create_dt, a.sent_to, a.sent_from
				FROM klink_mlm2010.dbo.V_DION_CHECK_BV_ONLINE_HDR a
				LEFT OUTER JOIN klink_mlm2010.dbo.intrh d ON (a.kw_no = d.applyto AND a.kw_no is not NULL AND a.kw_no != '')
				LEFT OUTER JOIN klink_mlm2010.dbo.gdohdr e ON (d.GDO = e.trcd AND d.GDO is not NULL AND d.GDO != '')
				WHERE a.$param = '$value'";
		   
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function getTrxByTrcdHeadInv($param, $value) {
		$qry = "SELECT
					a.trcd, a.orderno, a.batchno, a.invoiceno, a.trtype, a.ttptype, a.etdt,
					a.batchdt, a.remarks, a.createdt, a.createnm, a.updatedt, a.updatenm,
					a.dfno, a1.fullnm as distnm,
					a.loccd, sc1.fullnm as loccdnm,
					a.sc_co, sc1.fullnm as sc_conm,
					a.sc_dfno, sc1.fullnm as sc_dfnonm,
					a.tdp, a.tbv, a.bnsperiod,
					b.receiptno,
				c.createnm as kw_createnm, '' as descStat,
				CONVERT(VARCHAR(10), c.createdt, 121) as kw_date,
				d.GDO, e.createnm as gdo_createnm,
				CONVERT(VARCHAR(10), e.etdt, 121) as gdo_createdt,
				e.shipto,
				'Invoice Approved' AS statusTrx,
				'' as id_deposit,
				'' as flag_batch,
				f.NO_DO as do_wms,
										f.CREATED_BY as do_wms_create_by,
										CONVERT(VARCHAR(10), f.CREATED_DATE, 120) AS do_wms_create_dt,
										f.ID_STOCKIES as sent_to, 
										g.WAREHOUSE_NAME as sent_from
				FROM klink_mlm2010.dbo.newtrh a
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb a1 ON (a.dfno = a1.dfno)
				LEFT OUTER JOIN klink_mlm2010.dbo.mssc sc1 ON (a.loccd = sc1.loccd)
				LEFT OUTER JOIN klink_mlm2010.dbo.ordtrh b ON (a.trcd = b.invoiceno)
				LEFT OUTER JOIN klink_mlm2010.dbo.billhdr c ON (b.registerno = c.applyto)
				LEFT OUTER JOIN klink_mlm2010.dbo.intrh d ON (c.trcd = d.applyto)
				LEFT OUTER JOIN klink_mlm2010.dbo.gdohdr e ON (d.GDO = e.trcd)
				LEFT OUTER JOIN klink_whm.dbo.T_DETAIL_DO e1 
				ON (b.receiptno COLLATE SQL_Latin1_General_CP1_CI_AS = e1.NO_KWITANSI)
				LEFT OUTER JOIN klink_whm.dbo.T_DO f ON (e1.ID_DO = f.ID_DO)
				LEFT OUTER JOIN klink_whm.dbo.MASTER_WAREHOUSE g ON (f.ID_WAREHOUSE = g.ID_WAREHOUSE)
				WHERE a.$param = '$value'";
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function getTrackingDO($do_wms) {
		$qry = "SELECT NO_DO, STATUS,
				CONVERT(VARCHAR(30), CREATED_DATE, 20) AS CREATED_DATE, CREATED_BY
				FROM klink_whm.dbo.T_TRACKING_DO
				WHERE NO_DO = '$do_wms'
				ORDER BY CREATED_DATE DESC";
		$res = $this->getRecordsetArray($qry, null, $this->db2);
		return $res;
	}

	function geTrxByTrcdDet($param, $value) {
		$qry = "SELECT  a.trcd, a.prdcd, a.prdnm, a.qtyord, a.bv, a.dp, a.TOTBV, a.TOTDP
	   			 FROM V_HILAL_CHECK_BV_ONLINE_DET a
	   			 WHERE $param = '$value'" ;
	   		//echo $qry;
	   	//echo "<br />";
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function getTrxByTrcdPay($param, $value) {
		$qry = "SELECT a.trcd, a.paytype, b.description,
				a.payamt, a.vchtype, a.docno
				FROM sc_newtrp a
				LEFT OUTER JOIN paytype b ON (a.paytype = b.id)
		        WHERE $param = '$value'";
		$res = $this->getRecordset($qry, null, $this->db2);
		if($res == null) {
			$qry = "SELECT a.trcd, a.paytype, b.description,
			a.payamt, a.vchtype, a.docno
			FROM newtrp a
			LEFT OUTER JOIN paytype b ON (a.paytype = b.id)WHERE $param = '$value'";
			$res = $this->getRecordset($qry, null, $this->db2);
		}
		return $res;
	}

	function getStockist($loccd) {
		$qry = "SELECT a.loccd, a.fullnm AS fullnm
	   			 FROM mssc a
	   			 WHERE a.loccd = '$loccd'" ;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function getAllStockist() {
		$qry = "SELECT a.loccd, a.fullnm AS fullnm
	   			 FROM mssc a
	   			 WHERE a.fullnm NOT LIKE '%TERMINATION%'
	   			 	  AND A.LOCCD NOT IN ('ADJ', 'C/S', 'D/S','PCC','LINTAS')
	   			 	  AND A.LOCCD NOT LIKE '%C/S%'
	   			 	  AND A.LOCCD NOT LIKE '%D/S%'
	   			 	  AND A.LOCCD NOT LIKE '%BLK1%'
	   			 	  AND A.LOCCD NOT LIKE '%CPC%'
	   			 	  AND A.LOCCD NOT LIKE '%GCX%'
	   			 	  AND A.LOCCD NOT LIKE '%CELEBES%'
	   			 	  AND A.fullnm NOT LIKE '%CANCEL%'
	   			 	  AND A.fullnm NOT LIKE '%CENCEL%'
	   			 ORDER BY A.sctype, A.LOCCD";
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function getListRecapSales($param, $bnsmonth=null, $bnsyear=null) {
		$qryParam = "";
		if($bnsyear != "" || $bnsyear != null){
			$period = "$bnsyear-$bnsmonth-01";
			if($bnsmonth == "all"){
				$bnsmonthParam = "";
			}else{
				$bnsmonth = intval($bnsmonth);
				$bnsmonthParam = " AND A.bonusmth = '$bnsmonth'";
			}

			$qryPeriod = " WHERE A.bonusyr = '$bnsyear' $bnsmonthParam ";

			//echo $param;
			if($param != "all" && $param != "all-sc" && $param != "all-scandsub" && $param != "all-sub" && $param != "all-ms"){
				$qryParam = " AND A.sc_dfno = '$param' ";
			}elseif($param == "all-sc"){
				$qryParam = " AND A.sctype = '1' ";
			}elseif($param == "all-scandsub"){
				$qryParam = " AND A.sctype IN ('1','2') ";
			}elseif($param == "all-sub"){
				$qryParam = " AND A.sctype = '2' ";
			}elseif($param == "all-ms"){
				$qryParam = " AND A.sctype = '3' ";
			}

			$qryParam = " $qryPeriod $qryParam ";
			//echo "$qryParam<BR/>";
		}

		$qry = "SELECT A.sctype, A.sc_dfno, A.fullnm_sc, A.bonusmth, A.bonusyr,
					   SUM(A.tdp) AS tdp, SUM(A.ndp) AS ndp,
				       SUM(A.tbv) AS tbv, SUM(A.nbv) AS nbv
				FROM V_ECOMM_HILAL_SALES_STK a
				$qryParam
				GROUP BY A.sctype, A.sc_dfno, A.fullnm_sc, A.bonusmth, A.bonusyr
				ORDER BY a.sctype, a.fullnm_sc" ;
	   	//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}


	function getListBonus_Vcr($param, $rptType, $bnsmonth=null, $bnsyear=null) {
		$qryParam = "";
		if($bnsyear != "" || $bnsyear != null){
			$period = "$bnsyear-$bnsmonth-01";
			if($bnsmonth == "all"){
				$bnsmonthParam = "";
			}else{
				$bnsmonth = intval($bnsmonth);
				$bnsmonthParam = " AND A.bonusmonth = '$bnsmonth'";
			}

			$qryPeriod = " WHERE A.bonusyear = '$bnsyear' $bnsmonthParam ";

			//echo $param;
			if($param == "all"){
				$qryParam = " ";
			}elseif($param == "idr"){
				$qryParam = " AND A.countrycode = 'ID' AND ";
			}elseif($param == "sar"){
				$qryParam = " AND A.countrycode = 'SA' AND ";
			}elseif($param == "tl"){
				$qryParam = " AND A.countrycode = 'TL' AND ";
			}elseif($param == "xid"){
				$qryParam = " AND A.countrycode != 'ID' AND ";
			}

			$qryParam = " $qryPeriod $qryParam ";
			//echo "$qryParam<BR/>";
		}

		if($rptType == "cvr" or $rptType == "pvr"){
			if($rptType == "cvr"){
				$vchtype = "'C'";
			}else{
				$vchtype = "'P'";
			}
			$qry = "SELECT 	a.DistributorCode, a.VoucherNo, a.vchtype, a.VoucherAmtCurr, a.VoucherAmt,
							a.countrycode, a.bonusyear, a.bonusmonth
					FROM tcvoucher a
					$qryParam and vchtype=$vchtype
					ORDER BY a.bonusyear desc, a.bonusmonth desc, a.countrycode DESC, a.DistributorCode ASC" ;
		}if($rptType == "allvr" OR $rptType == "chq" OR $rptType == "chq_stk" OR $rptType == "novac"){

			$novac = "";

			if($rptType == "allvr"){
				$join = "INNER ";
				$netIncome = " ";
			}elseif($rptType == "chq"){
				$join = "LEFT OUTER ";
				$netIncome = " AND c.netincome >= 20 ";
			}elseif($rptType == "chq_stk"){
				$join = "LEFT OUTER ";
				$netIncome = " AND c.netincome BETWEEN 1 AND 50 ";
			}elseif($rptType == "novac"){
				$novac = " , d.novac";
				$join = " LEFT OUTER JOIN msmemb d ON c.DistributorCode=d.dfno
						  LEFT OUTER ";
				$netIncome = " AND c.netincome BETWEEN 1 AND 50 ";
			}


			$qry = "SELECT a.DistributorCode, d.fullnm, a.VoucherNo as 'CVoucherNo',
					   (SELECT B.VoucherNo
				       	FROM tcvoucher B
				        WHERE B.vchtype='P'
					  	      and B.VoucherAmtCurr < 100
				        	  and a.BonusMonth=B.BonusMonth
				              and a.BonusYear=B.BonusYear
				              AND a.DistributorCode=B.DistributorCode) AS 'PVoucherNo',
					   a.countrycode, a.bonusyear, a.bonusmonth, c.netincome, c.stockiestcode $novac
				FROM tbonus c
					 $join JOIN tcvoucher a ON a.BonusMonth=c.BonusMonth
				              and a.BonusYear=c.BonusYear AND a.DistributorCode=c.DistributorCode
				     LEFT OUTER JOIN msmemb d on c.distributorcode=d.dfno
				$qryParam AND a.vchtype='C' $netIncome";
		}

	   	echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function listWHFromGNV(){
		$qry = "select A.id, A.[description], A.kode, A.warehouse_name
				from wmswarehousemaster a
				where a.[description]='WAREHOUSE'
				order by a.[description]" ;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function saveImportToDB($data, $whcdx) {
		$totalRecord = 0;
		$double = 0;
		$insSuccess = 0;
		$insFail = 0;
		$arrFail = array();
		$arrDouble = array();

		$whcdy = explode("***", $whcdx);
		$whcd = $whcdy[0];
		$whnm = $whcdy[1];
		$whid = $whcdy[2];

		//$nmTbl = "WMS_STOCK_AKHIR_upload";
		$nmTbl = "WMS_STOCK_AKHIR";

		$i = 0;
		foreach($data['xlsData'] as $list) {
			if(is_numeric($list[0][1]) && $list[0][0] != ""){
				if($i > 0){
					$totalRecord++;
					$check = $this->checkDataStockAwal($list[0][0], $whcd, $nmTbl);
					if($check) {

						$createDate = date("Y-m-d h:i:s");

						$qry = "INSERT INTO $nmTbl (kode_barang, stock_akhir, wms_warehouse	, wms_warehouse_name)
							    VALUES ('".$list[0][0]."', ".$list[0][1].", '".$whcd."', '".$whnm."')";
						//array_push($arrQry, $qry);
						//echo $qry."<BR/>";

					    $insExec = $this->executeQuery($qry, $this->db2);
						if($insExec > 0) {
							$insSuccess++;
						} else {
							$insFail++;
							array_push($arrFail, $list[0][0]);
						}
						//echo $qry;
					} else {
						$double++;
						array_push($arrDouble, $list[0][0]);
					}
				}
				$i++;
			}

	    }

		if($insSuccess > 0){ //if upload stock more than 0 then update stock real to GNV
			$updqry = "EXEC SP_Hilal_UpdStockWMS_sentul '$whcd', '$whnm'";
			$SPExec = $this->executeQuery($updqry, $this->db2);
			if($SPExec > 0) {
				$msgSP = "Succes update stock.";
			} else {
				$msgSP = "Failed update stock.";
				array_push($arrFail, $msgSP);
			}
		}

		$arr = array("totalRecord" => $totalRecord,
					"double" => $double,
					"insSuccess" => $insSuccess,
					"insFail" => $insFail,
					"arrFail" => $arrFail,
					"arrDouble" => $arrDouble,
					//"msgSP" => $msgSP,
					);
		return $arr;
	}


	function checkDataStockAwal($id, $whcd, $nmTbl) {
		$mdReturn = false;
		$qry = "SELECT * FROM $nmTbl WHERE kode_barang = '$id' AND wms_warehouse ='$whcd'";
		$res = $this->getRecordset($qry, null, $this->db2);
		if($res == null) {
			$mdReturn = true;
		}
		return $mdReturn;
	}

	function delDataStockAwal($whcdx) {
		$whcdy = explode("***", $whcdx);
		$whcd = $whcdy[0];
		$whnm = $whcdy[1];
		$whid = $whcdy[2];
		//$nmTbl = "WMS_STOCK_AKHIR_upload";
		$nmTbl = "WMS_STOCK_AKHIR";

		$mdReturn = false;
		$qry = "DELETE FROM $nmTbl WHERE wms_warehouse ='$whcd'";
		$res = $this->executeQuery($qry, $this->db2);
		if($res == null) {
			$mdReturn = true;
		}
		return $mdReturn;
	}

	function listBnsPeriod(){
		$qry = "select CONVERT(VARCHAR(10), A.currperiod, 111) AS currperiod,
					   CONVERT(VARCHAR(10), A.currperiodSCO, 111) AS currperiodSCO,
					   CONVERT(VARCHAR(10), A.currperiod, 111) AS currperiod1
			 	from syspref a" ;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function lastDayPrevBnsPeriod() {
		$qry = "SELECT * FROM db_ecommerce.dbo.master_period";
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function updateLastPrevBns($data) {
		$dbqryx  = $this->load->database("db_ecommerce", TRUE);
		$res = $dbqryx->update('db_ecommerce.dbo.master_period', $data);
		return $res;
	}

	function showSysPref() {
		$qry = "SELECT CONVERT(VARCHAR(10), A.currperiodSCO, 111) as bns_stk,
		           CONVERT(VARCHAR(10), A.currperiodBO, 111) as bns_bo,
				   CONVERT(VARCHAR(10), A.currperiod, 111) as bns_inv,
				   CONVERT(VARCHAR(10), A.update_bv, 111) as bns_knet_upd
				   FROM syspref a";
		//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function uptBnsPeriod($period, $param){
		$mdReturn = false;
		if($param == "1"){
			$paramUpd = "currperiodSCO = '$period'";
		}elseif($param == "2"){
			$paramUpd = "currperiodBO = '$period'";
		} elseif($param == "3") {
			$paramUpd = "currperiod = '$period'";
		} elseif($param == "4") {
			$paramUpd = "update_bv = '$period'";
		}

		$qry = "UPDATE syspref SET $paramUpd" ;
		echo "$qry<BR/>";
		$res = $this->executeQuery($qry, $this->db2);
		if($res == null) {
			$mdReturn = true;
		}
		return $res;
	}

	function getTrxShippingByKWNo($id) {
		$qry = "select a.dfno, b.fullnm, a.registerno, a.trcd, a.tdp, a.tbv,
                    a.totpay, a.ship, a.whcd
                    from ordhdr a inner join msmemb b on (a.dfno=b.dfno)
                    where a.applyto = '$id'";
	    $res = $this->getRecordset($qry, null, $this->db2);
	    if($res == null) {
	    	$tipe_trans = "CN/MSN";
	    	//Jika null maka
	    	$cn = "select a.dfno, b.fullnm,a.registerno, a.trcd, a.tdp, a.tbv,
                    a.totpay, a.ship, a.whcd
                    from ordivhdr a inner join mssc b on (a.dfno=b.loccd)
                    where a.applyto = '$id'";
			$res2 = $this->getRecordset($cn, null, $this->db2);
			if($res2 != null) {
				$arr = array("response" => "true", "trx_type" => $tipe_trans, "arrayData" => $res2);
			} else {
				$arr = array("response" => "false", "message" => "Invalid Receipt No..");
			}
	    } else {
	    	$tipe_trans = "INVOICE";
			$arr = array("response" => "true", "trx_type" => $tipe_trans, "arrayData" => $res);
	    }
	}

	function getTrxShippingByKWNo2($id) {
		$arr = array("response" => "false", "message" => "Invalid Receiptno");
		/*$qry = "SELECT a.ordtype, a.receiptno, a.registerno, a.dfno, a.ship, a.tdp, a.tbv
		        FROM ordtrh a WHERE a.receiptno = '$id'";	*/
		$qry = "SELECT a.ordtype, a.receiptno, a.registerno,  a.ship, SUM(a.tdp) as tdp, SUM(a.tbv) as tbv
				FROM ordtrh a WHERE a.receiptno = '$id'
				GROUP BY a.ordtype, a.receiptno, a.registerno,  a.ship";
		//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		if($res == null) {
			$tipe_trans = "CN/MSN";
			$qryCN = "SELECT a.receiptno, a.registerno, a.loccd as dfno, a.ship, SUM(a.tdp) as tdp, SUM(a.tbv) as tbv, a.shipto
						FROM ordivtrh a WHERE a.receiptno = '$id'
						GROUP BY a.receiptno, a.registerno, a.loccd, a.ship,  a.shipto";
			$res2 = $this->getRecordset($qryCN, null, $this->db2);
			$arr = array("response" => "true", "trx_type" => $tipe_trans, "arrayData" => $res2);
		} else {
	    	$tipe_trans = "INVOICE";
			$arr = array("response" => "true", "trx_type" => $tipe_trans, "arrayData" => $res);
	    }
	    return $arr;
	}

	function updateShipping($no_kw, $no_reg, $ship) {
		$arr = $this->getTrxShippingByKWNo2($no_kw);
		if($arr['response'] == "true") {
			$db_qryx = $this->load->database('klink_mlm2010', true);
            $db_qryx->trans_begin();
			if($arr['trx_type'] == "INVOICE") {
				$upd1 = "UPDATE ordtrh SET ship = '$ship' WHERE receiptno = '$no_kw'";
				$upd2 = "UPDATE ordhdr SET ship = '$ship' WHERE registerno = '$no_reg'";
				$upd3 = "UPDATE newtrh SET ship = '$ship' WHERE receiptno = '$no_kw'";
				$upd4 = "UPDATE intrh SET ship = '$ship' WHERE applyto = '$no_kw'";

			} else if($arr['trx_type'] == "CN/MSN") {
				$upd1 = "UPDATE ordivtrh SET ship = '$ship' WHERE receiptno = '$no_kw'";
				$upd2 = "UPDATE ordivhdr SET ship = '$ship' WHERE registerno = '$no_reg'";
				$upd3 = "UPDATE newivtrh SET ship = '$ship' WHERE receiptno = '$no_kw'";
				$upd4 = "UPDATE intrh SET ship = '$ship' WHERE applyto = '$no_kw'";
			}
			$exeUpd1 = $db_qryx->query($upd1);
			$exeUpd2 = $db_qryx->query($upd2);
			$exeUpd3 = $db_qryx->query($upd3);
			$exeUpd4 = $db_qryx->query($upd4);

			if ($db_qryx->trans_status() === FALSE) {
				$db_qryx->trans_rollback();
				return array("response" => "false", "message" => "Update Shipping Gagal");
			} else {
				$db_qryx->trans_commit();
				return array("response" => "true", "message" => "Update Shipping berhasil..");
			}
		} else {
			return $arr;
		}
	}


    function getOperatorSMS() {
		$qry = "SELECT A.id, A.operator_desc, a.is_activated
				FROM sms_operator A
				ORDER BY A.id ";
		$res = $this->getRecordset($qry);
		return $res;
	}

	function listTtpById($field, $value) {
		$qry = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm,
				   a.totpay, a.tdp, a.tbv, CONVERT(VARCHAR(10),a.bnsperiod, 121) as bnsperiod, a.no_deposit
				 FROM sc_newtrh a
				 INNER JOIN msmemb b ON (a.dfno = b.dfno)
				 WHERE a.$field = '$value'
				 ORDER BY trcd ASC";
		$hasil1 = $this->getRecordset($qry, null, $this->db2);
		if($hasil1 == null) {
			$qry = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm,
				   a.totpay, a.tdp, a.tbv, CONVERT(VARCHAR(10),a.bnsperiod, 121) as bnsperiod, '' as no_deposit
				 FROM newtrh a
				 INNER JOIN msmemb b ON (a.dfno = b.dfno)
				 WHERE a.$field = '$value'
				 ORDER BY trcd ASC";
			$hasil1 = $this->getRecordset($qry, null, $this->db2);
		}
		return $hasil1;
	}

	function getDepositVchInfo($value) {
		$qry1 = "SELECT * FROM deposit_H a WHERE a.no_trx = '$value'";
		$hasil1 = $this->getRecordset($qry1, null, $this->db2);

		$qry2 = "SELECT a.voucher_scan, a.no_trx, a.nominal, a.dfno,
					b.fullnm, CONVERT(VARCHAR(10),a.createdt, 121) as createdt, a.kategori, a.is_active, a.createnm
				FROM deposit_D a
				LEFT OUTER JOIN msmemb b ON (a.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = b.dfno)
				WHERE a.no_trx = '$value'
				ORDER BY a.voucher_scan";
		$hasil2 = $this->getRecordset($qry2, null, $this->db2);

		$qry3 = "SELECT a.orderno, a.trcd, a.dfno, a.bnsperiod, a.etdt,
							a.totpay, a.tdp, a.tbv,
							a.sc_dfno, a.sc_co, a.loccd, a.createnm,
							a.flag_batch, a.batchno, a.flag_recover, a.csno, a.no_deposit,
							CONVERT(VARCHAR(10),a.batchdt, 121) as batchdt, a.flag_approval,
							a.ttptype, a.trtype, a.pricecode
				FROM sc_newtrh a
				WHERE a.no_deposit = '$value'";
				//LEFT OUTER JOIN msmemb b ON (a.dfno = b.dfno)
		$hasil3 = $this->getRecordset($qry3, null, $this->db2);

		$arr = array(
			"deposit_header" => $hasil1,
			"list_vch" => $hasil2,
			"list_ttp" => $hasil3,
		);

		return $arr;
	}

	function getSummaryTrxBySSR($field, $ssrno) {
		/* $qry = "SELECT
				    COUNT(a.trcd) as jum_ttp,
					SUM(a.totpay) AS total_dp,
					SUM(a.tbv) as total_bv,
					a.sc_dfno, a.sc_co, a.loccd, a.createnm,
					a.flag_batch, a.batchno, a.flag_recover, a.csno, a.no_deposit,
					CONVERT(VARCHAR(10),a.batchdt, 121) as batchdt,
					CONVERT(VARCHAR(10),a.bnsperiod, 121) as bnsperiod
				FROM sc_newtrh a
				WHERE $field = '$ssrno'
				GROUP BY
				 a.sc_dfno, a.sc_co, a.loccd, a.createnm,
				 a.flag_recover, a.csno, a.no_deposit,
				 a.flag_batch, a.batchno, CONVERT(VARCHAR(10),a.batchdt, 121),
				 CONVERT(VARCHAR(10),a.bnsperiod, 121)"; */

		$qry = "   SELECT COUNT(a.trcd) as jum_ttp,
						SUM(a.tdp) as total_dp,
						SUM(a.tbv) as total_bv,
						a.sc_dfno, a.sc_co, a.loccd, a.createnm, a.flag_batch, a.batchno,
						CONVERT(VARCHAR(10), a.batchdt, 120) as batchdt, a.id_deposit,
						CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod, 
						trh.invoiceno as csno, 
						trh.createnm as cnms_createnm, 
						CONVERT(VARCHAR(10), trh.invoicedt, 120) as cnms_createdt, 
							bilhdr.trcd as kw_no, 
						CONVERT(VARCHAR(10), bilhdr.createdt, 120) as kw_date, 
						bilhdr.createnm as kw_createnm,
							do.no_do as do_wms,  
						CONVERT(VARCHAR(10), do.do_date, 120) as do_wms_create_dt, 
						do.do_createby as do_wms_create_by, 
						do.warehouse_name as sent_from, 
						do.id_stockist as sent_to
					FROM klink_mlm2010.dbo.sc_newtrh a
					LEFT OUTER JOIN klink_mlm2010.dbo.ordivtrh trh ON (a.csno = trh.invoiceno AND a.csno is not NULL AND a.csno != '')
					LEFT OUTER JOIN klink_mlm2010.dbo.billivhdr bilhdr 
						ON (trh.receiptno = bilhdr.trcd AND trh.receiptno is not NULL AND trh.receiptno != '')
					LEFT OUTER JOIN klink_mlm2010.dbo.DO_NINGSIH do 
						ON (bilhdr.trcd COLLATE SQL_Latin1_General_CP1_CS_AS = do.no_kwitansi AND bilhdr.trcd is not null AND bilhdr.trcd != '')
					WHERE $field = '$ssrno'
					GROUP BY a.sc_dfno, a.sc_co, a.loccd, a.createnm, a.flag_batch, a.batchno,
						CONVERT(VARCHAR(10), a.batchdt, 120), a.id_deposit,
						CONVERT(VARCHAR(10), a.bnsperiod, 120), trh.invoiceno, trh.createnm, 
					CONVERT(VARCHAR(10), trh.invoicedt, 120), 
						bilhdr.trcd, CONVERT(VARCHAR(10), bilhdr.createdt, 120), bilhdr.createnm,
						do.no_do, do.do_createby,
					CONVERT(VARCHAR(10), do.do_date, 120), 
					do.warehouse_name, do.id_stockist";
		//echo $qry;
		$hasil1 = $this->getRecordset($qry, null, $this->db2);
		if($hasil1 != null) {

			$qry2 = "SELECT SUM(b.payamt) as amount,
							b.paytype, c.[description], b.trcd, b.trcd2, d.refno
					FROM sc_newtrp_vc_det b
					INNER JOIN paytype c ON (b.paytype = c.id)
					LEFT OUTER JOIN bbhdr d on (b.trcd2 = d.trcd)
					where b.trcd = '".$hasil1[0]->batchno."'
					group by b.paytype, c.[description], b.trcd , b.trcd2, d.refno";
			$hasil2 = $this->getRecordset($qry2, null, $this->db2);



			$qry4 = "SELECT a.no_trx, CONVERT(VARCHAR(10),a.createdt, 121) as createdt,
						a.dfno, a.loccd, a.total_deposit, a.total_keluar, a.[status]
					FROM deposit_H a
					WHERE a.no_trx = '".$hasil1[0]->id_deposit."'";
			$hasil4 = $this->getRecordset($qry4, null, $this->db2);

			$returnArr = array(
				"trx_ssr" => $hasil1,
				"newtrp" => $hasil2,
				//"bbhdr" => $hasil3,
				"deposit" => $hasil4
			);
		} else {
			$returnArr = array(
				"trx_ssr" => null,
			);
		}



		return $returnArr;

	}

	function getSummaryIPVchCash($ssrno) {
		$qry = "SELECT
		            COUNT(a.trcd) as jum_ttp,
					SUM(a.totpay) AS total_dp,
					SUM(a.tbv) as total_bv,
					a.sc_dfno, a.sc_co, a.loccd, a.createnm,
					a.flag_batch, a.batchno, a.flag_recover, a.csno, a.no_deposit, id_deposit
					CONVERT(VARCHAR(10),a.batchdt, 121) as batchdt
				FROM sc_newtrh a
				WHERE a.batchno = '$ssrno'
				GROUP BY
				 a.sc_dfno, a.sc_co, a.loccd, a.createnm,
				 a.flag_recover, a.csno, a.no_deposit,
				 a.flag_batch, a.batchno, CONVERT(VARCHAR(10),a.batchdt, 121)";
		$hasil1 = $this->getRecordset($qry, null, $this->db2);
		if($hasil1 != null) {

			$qry2 = "SELECT SUM(b.payamt) as amount,
							b.paytype, c.[description], b.trcd, b.trcd2, d.refno
					FROM sc_newtrp_vc_det b
					INNER JOIN paytype c ON (b.paytype = c.id)
					LEFT OUTER JOIN bbhdr d on (b.trcd2 = d.trcd)
					where b.trcd = '$ssrno'
					group by b.paytype, c.[description], b.trcd , b.trcd2, d.refno";
			$hasil2 = $this->getRecordset($qry2, null, $this->db2);



			$qry4 = "SELECT a.no_trx, CONVERT(VARCHAR(10),a.createdt, 121) as createdt,
						a.dfno, a.loccd, a.total_deposit, a.total_keluar, a.[status]
					FROM deposit_H a
					WHERE a.no_trx = '".$hasil1[0]->no_deposit."'";
			$hasil4 = $this->getRecordset($qry4, null, $this->db2);

			$returnArr = array(
				"trx_ssr" => $hasil1,
				"newtrp" => $hasil2,
				//"bbhdr" => $hasil3,
				"deposit" => $hasil4
			);
		} else {
			$returnArr = array(
				"trx_ssr" => null,
			);
		}



		return $returnArr;

	}

	function getListVcNewtrp($ssrno) {

		return $ssrno;
	}

	function recoverGeneratedSSR($ssrno) {
		$upd = "UPDATE sc_newtrh SET flag_batch = '0', flag_recover = '1' batchdt = null
		        WHERE batchno = '$ssrno'";
	}

	function getOrdivByRegNo($regno) {
		$resOrdivHdr = $this->getOrdHeader("stk", "registerno", $regno);
		if($resOrdivHdr != null) {

            $resOrdivTrh = $this->getOrdTrhHeader("stk", "registerno", $regno);
			$hasil = array(
				"response" => "true",
				"tipe" => "stk",
				"ordivhdr" => $resOrdivHdr,
				"ordivtrh" => $resOrdivTrh
			);
			$hasil['billivhdr'] = null;
			if($resOrdivTrh != null) {
				if($resOrdivTrh[0]->receiptno != null && $resOrdivTrh[0]->receiptno != "") {
					$resBillivhdr = $this->getKwHeader("stk", "applyto", $regno);
					if($resBillivhdr != null) {
						$hasil['billivhdr'] = $resBillivhdr;
					}
				}
			}
			return $hasil;
		} else {

				$resOrdivHdr = $this->getOrdHeader("inv", "registerno", $regno);
				$resOrdivTrh = $this->getOrdTrhHeader("inv", "registerno", $regno);
				if($resOrdivHdr != null) {
					$hasil = array(
						"response" => "true",
						"tipe" => "invoice",
						"ordivhdr" => $resOrdivHdr,
						"ordivtrh" => $resOrdivTrh
					);
					$hasil['billivhdr'] = null;
					if($resOrdivTrh != null) {
						if($resOrdivTrh[0]->receiptno != null && $resOrdivTrh[0]->receiptno != "") {
							$resBillivhdr = $this->getKwHeader("inv", "applyto", $regno);
							if($resBillivhdr != null) {
								$hasil['billivhdr'] = $resBillivhdr;
							}
						}
					}
				} else {
					$hasil = array(
						"response" => "false",
						"tipe" => "",
					);
				}
				return $hasil;
		}
	}

	function getOrdHeader($tipe, $param, $value) {
		if($tipe == "stk") {

			$ordivHdr = "SELECT a.dfno, a1.fullnm as dfno_name,
						a.loccd, a2.fullnm as loccd_name,
						CONVERT(VARCHAR(10), a.bnsperiod, 120) AS bnsperiod,
						CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
						a.createnm,
						CONVERT(VARCHAR(10), a.updatedt, 120) AS updatedt,
						a.updatenm,
						a.tdp, a.tbv, a.registerno,
						CONVERT(VARCHAR(10), a.registerdt, 120) AS registerdt,
						a.paynote, a.branch, a.ship, a.shipto, a.whcd, a.trcd,
						CASE 
						  WHEN a.ship = '1' THEN 'Pick Up'
						  WHEN a.ship = '2' THEN 'Ship'
						  WHEN a.ship = '3' THEN 'Hold'
						  WHEN a.ship = '4' THEN 'Don`t Ship'
						END AS ship_status,  
						CASE 
						  WHEN a.onlinetype = 'M' THEN 'M (Manual)'
						  WHEN a.onlinetype = 'O' THEN 'O (Online)'
						  ELSE '-'
						END AS onlinetype,
						a.pricecode
					FROM ordivhdr a
					LEFT OUTER JOIN mssc a1 ON (a.dfno = a1.loccd)
					LEFT OUTER JOIN mssc a2 ON (a.loccd = a2.loccd)
					WHERE a.$param = '$value'";
			$resOrdivHdr = $this->getRecordset($ordivHdr, null, $this->db2);
		} else {
			$ordivHdr = "SELECT a.dfno, a1.fullnm as dfno_name,
						a.loccd, a2.fullnm as loccd_name,
						CONVERT(VARCHAR(10), a.bnsperiod, 120) AS bnsperiod,
						CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
						a.createnm,
						CONVERT(VARCHAR(10), a.updatedt, 120) AS updatedt,
						a.updatenm,
						a.tdp, a.tbv, a.registerno,
						CONVERT(VARCHAR(10), a.registerdt, 120) AS registerdt,
						a.paynote, a.branch, a.ship, a.shipto, a.whcd, a.trcd,
						CASE 
						  WHEN a.ship = '1' THEN 'Pick Up'
						  WHEN a.ship = '2' THEN 'Ship'
						  WHEN a.ship = '3' THEN 'Hold'
						  WHEN a.ship = '4' THEN 'Don`t Ship'
						END AS ship_status,  
					    'Invoice' AS onlinetype,
						a.pricecode
					FROM ordhdr a
					LEFT OUTER JOIN mssc a1 ON (a.dfno = a1.loccd)
					LEFT OUTER JOIN mssc a2 ON (a.loccd = a2.loccd)
					WHERE a.$param = '$value'";
			$resOrdivHdr = $this->getRecordset($ordivHdr, null, $this->db2);
		}

		return $resOrdivHdr;
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

	function getCNSumProduct($no_cn) {
		$ordivrtd = "SELECT a.prdcd, b.prdnm, a.qtyord, a.qtyremain, a.qtyship, c.dp, c.bv
					 FROM ordivtrd a
					 LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
					 LEFT OUTER JOIN pricetab c ON (a.prdcd = c.prdcd and a.pricecode = c.pricecode)
					 WHERE a.invoiceno = '$no_cn'";
		$resDetail = $this->getRecordset($ordivrtd, null, $this->db2);
		return $resDetail;
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

	function getIncPayByCnno($no_cn) {
		$qry = "SELECT a.trcd, a.docno, a.payamt, b.[description] as pay_desc, d.bankdesc,
				d.bankaccnm, d.bankaccno
				FROM ordivtrp a
				LEFT OUTER JOIN paytype b ON (a.paytype = b.id)
				LEFT OUTER JOIN bbhdr c ON (a.docno = c.trcd)
				LEFT OUTER JOIN msbankacc d ON (c.bankacccd = d.bankacccd)
				WHERE a.trcd = '$no_cn'";
		$resInc = $this->getRecordset($qry, null, $this->db2);
		return $resInc;
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

	function getKwHeader($tipe, $param, $value) {
		if($tipe == "stk") {

			/*$billivhdr = "SELECT
							CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
							CONVERT(VARCHAR(10), a.updatedt, 120) AS updatedt,
							a.createnm, a.updatenm, a.trcd, a.dfno, a.tdp, a.tbv, a.statusbo,a.flag_ship,
							a.totinvoice, a.flagOL, b.GDO
							FROM billivhdr a
							LEFT OUTER JOIN intrh b ON (a.trcd = b.applyto)
							WHERE a.$param = '$value'";
			$resBillivhdr = $this->getRecordset($billivhdr, null, $this->db2);*/
			$resBillivhdr = $this->getBillivHdr($param, $value);
		} else {
			/*$billivhdr = "SELECT
							CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
							CONVERT(VARCHAR(10), a.updatedt, 120) AS updatedt,
							a.createnm, a.updatenm, a.trcd, a.dfno, a.tdp, a.tbv, a.statusbo,a.flag_ship,
							a.totinvoice, '1' as flagOL, b.GDO
							FROM billhdr a
							LEFT OUTER JOIN intrh b ON (a.trcd = b.applyto)
							WHERE a.$param = '$value'";
			$resBillivhdr = $this->getRecordset($billivhdr, null, $this->db2);*/
			$resBillivhdr = $this->getBillHdr($param, $value);
		}
		return $resBillivhdr;

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

	function getBillHdr($param, $value) {
		$billivhdr = "SELECT DISTINCT
							CONVERT(VARCHAR(10), a.createdt, 120) AS createdt,
							CONVERT(VARCHAR(10), a.updatedt, 120) AS updatedt,
							a.createnm, a.updatenm, a.trcd, a.dfno, a.tdp, a.tbv, a.statusbo,a.flag_ship,
							a.totinvoice, '1' as flagOL, b.GDO , a.applyto,
							CONVERT(VARCHAR(10), c.etdt, 120) AS GDOdt, c.createnm as GDO_createnm,
							c.whcd, b.trtype,
							e.NO_DO as do_wms,
							e.CREATED_BY as do_wms_create_by,
							CONVERT(VARCHAR(10), e.CREATED_DATE, 120) AS do_wms_create_dt,
							e.ID_STOCKIES as sent_to, 
							f.WAREHOUSE_CODE + ' - ' + f.WAREHOUSE_NAME as sent_from
							FROM billhdr a
							LEFT OUTER JOIN intrh b ON (a.trcd = b.applyto)
							LEFT OUTER JOIN gdohdr c ON (b.GDO = c.trcd)
							LEFT OUTER JOIN klink_whm.dbo.T_DETAIL_DO d ON (a.trcd COLLATE SQL_Latin1_General_CP1_CI_AS = d.NO_KWITANSI)
							LEFT OUTER JOIN klink_whm.dbo.T_DO e ON (e.ID_DO = d.ID_DO)
							LEFT OUTER JOIN klink_whm.dbo.MASTER_WAREHOUSE f ON (e.ID_WAREHOUSE = f.ID_WAREHOUSE)
							WHERE a.$param = '$value'";
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

	function recoverSSR($arr) {
	    $db_qryx = $this->load->database('klink_mlm2010', true);
        $db_qryx->trans_begin();

		$ssrno = $arr['batchno'];
		/*--------------------------------------------------------
		INSERT DATA YANG AKAN DI RECOVER KE TABLE SSR_RECOVERY_TBL
		----------------------------------------------------------*/
		$qry = "INSERT INTO ssr_recover_tbl (trcd, batchno, total_dp, total_bv)
		        SELECT a.trcd, a.batchno, a.tdp, a.tbv
				FROM sc_newtrh a
				WHERE a.batchno = '$ssrno'";
		//echo $qry;
		$db_qryx->query($qry);

		/*--------------------------------------------------------
		RECOVER SALES REPORT
		----------------------------------------------------------*/
		$upd = "UPDATE sc_newtrh SET flag_batch = '0', flag_recover = '1' batchdt = null
		        WHERE batchno = '$ssrno'";
		//$recover = $db_qryx->query($upd);
		//diaktifkan saat LIVE

		if ($db_qryx->trans_status() === FALSE) {
			$db_qryx->trans_rollback();
			return array("response" => "false", "message" => "Recover Gagal");
		} else {
			$db_qryx->trans_commit();
			return array("response" => "true", "message" => "Recover Sales Report $ssrno berhasil..");
		}
	}

	/**
	 * Get vcip details
	 * @param varchar $trcd
	 */
	function getVcipDetail($trcd) {

		// Voucher Cash Incoming Payment detail
		$query_b = "SELECT
						trcd,
						refno,
						description,
						amount,
						CONVERT(VARCHAR(10), etdt, 120) AS etdt,
						dfno
					FROM
						bbhdr
					WHERE
						trcd = '$trcd'";
		$res_b = $this->getRecordset($query_b, null, $this->db2);

		$query_s = "SELECT
						trcd,
						paytype,
						vhcno,
						payamt
					FROM
						sc_newtrp_vc_det
					WHERE
						trcd2 = '$trcd'";
		$res_s = $this->getRecordset($query_s, null, $this->db2);

		$query_c = "SELECT a.trcd, a.effect, a.dfno, a.createnm,
						CONVERT(VARCHAR(10), createdt, 120) AS createdt,
						a.trtype, a.applyto, a.idno, a.amount
					FROM custpaydet a
					WHERE a.applyto = '$trcd'";
		$res_c = $this->getRecordset($query_c, null, $this->db2);

		$custpaybal = "SELECT a.trcd, dfno, custtype, amount, balamt, createdt, status,
							CONVERT(VARCHAR(10), a.createdt, 120) AS updatedt, a.createnm
					   FROM custpaybal a
					   WHERE a.trcd = '$trcd'";
		$resCustpaybal = $this->getRecordset($custpaybal, null, $this->db2);
		// end


		if (!empty($res_b || $res_s)) {
			$recover = null;
			if($res_s != null) {
				$recover = $this->checkingSSR($res_s[0]->trcd); // checking SSR
			}
			// collect'em all as array
			$returnArr = array(
				"bbhdr" => $res_b,
				"sc_newtrp_vc_det" => $res_s,
				"custpaydet" => $res_c,
				"recover" => $recover,
				"ip_bal" => $resCustpaybal
			);
		} else {
			$returnArr = NULL;
		}
		return $returnArr;
	}

	function checkingSSR($ssr){
		$qry = "SELECT batchno FROM klink_mlm2010.dbo.sc_newtrh WHERE batchno = '$ssr'";
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	function recoverVoucher($ips, $vch) {
		$db_qryx = $this->load->database('klink_mlm2010', true);
		$db_qryx->trans_begin();

		$sql = "DELETE FROM bbhdr where trcd = '$ips'";
		$exeSql = $db_qryx->query($sql);
		$sql1 = "DELETE FROM sc_newtrp_vc_det where trcd2 = '$ips'";
		$exeSql1 = $db_qryx->query($sql1);
		$sql2 = "UPDATE deposit_H SET status='1' WHERE no_trx = '$vch'";
		$exeSql2 = $db_qryx->query($sql2);

		if ($db_qryx->trans_status() === FALSE) {
			$db_qryx->trans_rollback();
			return array("response" => "false", "message" => "Recover $ips Gagal");
		} else {
			$db_qryx->trans_commit();
			return array("response" => "true", "message" => "Recover $ips berhasil..");
		}

		// $execQuery = $this->executeQuery($sql, $this->db1);
		// $execQuery1 = $this->executeQuery($sql1, $this->db1);
		// $execQuery2 = $this->executeQuery($sql2, $this->db1);

		/*echo "<pre>";
		echo $sql."<br />";
		echo $sql1."<br />";
		echo $sql2."<br />";
		echo "</pre>";*/
	}

	/**
	 * @Author: Ricky
	 * @Date: 2019-03-21 16:09:50
	 * @Desc: Recover Trx updating data
	 * @param mixed $loccd
	 * @param mixed $sc_co
	 * @param mixed $sc_dfno
	 * @param mixed $bnsperiod
	 * @param mixed $trcd
	 * @return string|array
	 */
	function updateRecoverTrx($loccd, $sc_co, $sc_dfno, $bnsperiod, $trcd) {
		$db_qryx = $this->load->database('klink_mlm2010', true);
		$db_qryx->trans_begin();
		$query = "UPDATE klink_mlm2010.dbo.sc_newtrh
							SET loccd = '$loccd', sc_co = '$sc_co', sc_dfno = '$sc_dfno', bnsperiod = '$bnsperiod'
							WHERE trcd = '$trcd'";
		$res = $db_qryx->query($query);
		if ($db_qryx->trans_status() !== FALSE) {
			$db_qryx->trans_commit();
			return array(
				"response" => "true",
				"message" => "Recover berhasil..."
			);
		} else {
			$db_qryx->trans_rollback();
			return array(
				"response" => "false",
				"message" => "Recover Gagal"
			);
		}
	}

	/**
	 * @Author: Ricky
	 * @Date: 2019-05-07 10:07:28
	 * @Desc: Remove Trx
	 * @param string $trcd
	 * @return string|array
	 */
	function deleteTrx($trcd) {
		$db_qryx = $this->load->database('klink_mlm2010', TRUE);
		$query_check = "SELECT sc_newtrh.trcd, sc_newtrh.batchno, sc_newtrh.dfno, sc_newtrh.trtype,
								sc_newtrp.paytype, sc_newtrp.docno, tcvoucher.VoucherNo,
								sc_newtrp.payamt, tcvoucher.VoucherAmt, tcvoucher.DistributorCode,
								tcvoucher.claimstatus, sc_newtrh.no_deposit
						FROM dbo.sc_newtrh
						LEFT OUTER JOIN dbo.sc_newtrp ON sc_newtrh.trcd = sc_newtrp.trcd
						LEFT OUTER JOIN dbo.tcvoucher ON sc_newtrp.docno = tcvoucher.VoucherNo
						WHERE sc_newtrh.trcd = '$trcd'";
		$check =  $this->getRecordset($query_check, NULL, $this->db2);
		if ($check[0]->trtype == 'VP1') {
			if ($check[0]->docno === $check[0]->VoucherNo) {
				$voucher = $check[0]->VoucherNo;
				$db_qryx->trans_begin();
				$query_newtrh = "DELETE FROM klink_mlm2010.dbo.sc_newtrh WHERE trcd = '$trcd'";
				$query_newtrd = "DELETE FROM klink_mlm2010.dbo.sc_newtrd WHERE trcd = '$trcd'";
				$query_newtrp = "DELETE FROM klink_mlm2010.dbo.sc_newtrp WHERE trcd = '$trcd'";
				$query_voucher = "UPDATE klink_mlm2010.dbo.tcvoucher SET claimstatus = '0' WHERE VoucherNo = '$voucher'";
				//echo $query_newtrh . '<br>' . $query_newtrd;
				$res_newtrh = $db_qryx->query($query_newtrh);
				$res_newtrd = $db_qryx->query($query_newtrd);
				$res_newtp = $db_qryx->query($query_newtrp);
				$req_voucher = $db_qryx->query($query_voucher);
				if ($db_qryx->trans_status() !== FALSE) {
					$db_qryx->trans_commit();
					return array(
						"response" => "true",
						"message" => "Data berhasil dihapus..."
					);
				} else {
					$db_qryx->trans_rollback();
					return array(
						"response" => "false",
						"message" => "Data gagal dihapus..."
					);
				}
			}
		} elseif ($check[0]->trtype == 'SB1') {
			if ($check[0]->paytype == '01' && $check[0]->VoucherNo == NULL) {
				$db_qryx->trans_begin();
				$query_newtrh = "DELETE FROM klink_mlm2010.dbo.sc_newtrh WHERE trcd = '$trcd'";
				$query_newtrd = "DELETE FROM klink_mlm2010.dbo.sc_newtrd WHERE trcd = '$trcd'";
				// echo $query_newtrh;
				$res_newtrh = $db_qryx->query($query_newtrh);
				$res_newtrd = $db_qryx->query($query_newtrd);
				if ($db_qryx->trans_status() !== FALSE) {
					$db_qryx->trans_commit();
					return array(
						"response" => "true",
						"message" => "Data berhasil dihapus..."
					);
				} else {
					$db_qryx->trans_rollback();
					return array(
						"response" => "false",
						"message" => "Data gagal dihapus..."
					);
				}
			} elseif ($check[0]->VoucherNo != NULL || $check[0]->VoucherNo == '') {
				$voucher = $check[0]->VoucherNo;
				$db_qryx->trans_begin();
				$query_newtrh = "DELETE FROM klink_mlm2010.dbo.sc_newtrh WHERE trcd = '$trcd'";
				$query_newtrd = "DELETE FROM klink_mlm2010.dbo.sc_newtrd WHERE trcd = '$trcd'";
				$query_voucher = "UPDATE klink_mlm2010.dbo.tcvoucher SET claimstatus = '0' WHERE VoucherNo = '$voucher'";
				$res_newtrh = $db_qryx->query($query_newtrh);
				$res_newtrd = $db_qryx->query($query_newtrd);
				$req_voucher = $db_qryx->query($query_voucher);
				if ($db_qryx->trans_status() !== FALSE) {
					$db_qryx->trans_commit();
					return array(
						"response" => "true",
						"message" => "Data berhasil dihapus..."
					);
				} else {
					$db_qryx->trans_rollback();
					return array(
						"response" => "false",
						"message" => "Data gagal dihapus..."
					);
				}
			}
		}
	}

	/**
	 * @Author: Ricky
	 * @Date: 2019-05-24 17:15:13
	 * @Desc: Update BV
	 */
	function updateBv($trcd, $tbv) {
		$db_qryx = $this->load->database('klink_mlm2010', true);
		$db_qryx->trans_begin();
		$query = "UPDATE klink_mlm2010.dbo.sc_newtrh
							SET tpv = '$tbv', tbv = '$tbv', npv = '$tbv', nbv = '$tbv'
							WHERE trcd = '$trcd'";
		$res = $db_qryx->query($query);
		// echo $query;
		$query1 = "UPDATE klink_mlm2010.dbo.newtrh
							SET tpv = '$tbv', tbv = '$tbv', npv = '$tbv', nbv = '$tbv'
							WHERE trcd = '$trcd'";
		$res1 = $db_qryx->query($query1);
		// echo $query1;
		if ($db_qryx->trans_status() !== FALSE) {
			$db_qryx->trans_commit();
			return array(
				"response" => "true",
				"message" => "Recover berhasil..."
			);
		} else {
			$db_qryx->trans_rollback();
			return array(
				"response" => "false",
				"message" => "Recover Gagal"
			);
		}
  }

  function updateDp($trcd, $tdp) {
    $db_qryx = $this->load->database('klink_mlm2010', true);
    $db_qryx->trans_begin();
    $query = "UPDATE klink_mlm2010.dbo.sc_newtrh
              SET ndp = '$tdp', tdp = '$tdp', pay1amt = '$tdp', totpay = '$tdp'
              WHERE trcd = '$trcd'";
    $res = $db_qryx->query($query);
    // echo $query;
    $query1 = "UPDATE klink_mlm2010.dbo.newtrh
              SET ndp = '$tdp', tdp = '$tdp', pay1amt = '$tdp', totpay = '$tdp'
              WHERE trcd = '$trcd'";
    $res1 = $db_qryx->query($query1);
    // echo $query1;
    if ($db_qryx->trans_status() !== FALSE) {
      $db_qryx->trans_commit();
      return array(
        "response" => "true",
        "message" => "Update Berhasil..."
      );
    } else {
      $db_qryx->trans_rollback();
      return array(
        "response" => "false",
        "message" => "Update gagal"
      );
    }

  }

  function detailIncomingPayment($param) {
	$query = "SELECT a.trcd, a.amount,
			  CONVERT(VARCHAR(10), a.etdt, 120) AS createdt,
				a.dfno, a.createnm,
				a.[status], b.trcd as invoiceno, b.effect
			  FROM bbhdr a
			  LEFT OUTER JOIN custpaydet b ON (a.trcd = b.applyto)
			  WHERE a.refno LIKE '%$param%'";
	$res = $this->getRecordset($query, NULL, $this->db2);
	return $res;
  }

  function getDOheader($do) {
		$qry = "SELECT a.trcd, a.whcd, a.shipto, a.createnm,
				CONVERT(VARCHAR(10), a.etdt, 21) as etdt,
				a.shipby, COUNT(b.applyto) as total_kw, b.trtype
				FROM gdohdr a
				LEFT OUTER JOIN intrh b ON (a.trcd = b.GDO)
				WHERE a.trcd = '$do'
				GROUP BY a.trcd, a.whcd, a.shipto, a.createnm,
				CONVERT(VARCHAR(10), a.etdt, 21),
				a.shipby, b.trtype";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
  }

  function getDODetail($do) {
		$qry = "SELECT a.prdcd, b.prdnm, a.qtyord
				FROM gdoprd a
				INNER JOIN msprd b ON (a.prdcd = b.prdcd)
				WHERE a.trcd = '$do'
				ORDER BY b.prdnm";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
  }

  function listInvoiceByDO($do) {
		$qry = "SELECT b.applyto,
				CASE
					WHEN c.invoiceno is null THEN d.invoiceno ELSE c.invoiceno
				END AS invoiceno,
				CASE
					WHEN c.invoiceno is null THEN CONVERT(VARCHAR(10), d.invoicedt, 21)
					ELSE CONVERT(VARCHAR(10), c.invoicedt, 21)
				END AS invoicedt,
				CASE
					WHEN c.invoiceno is null THEN d.createnm
					ELSE c.createnm
				END AS createnm,
				CASE
					WHEN c.invoiceno is null THEN d.totpay
					ELSE c.totpay
				END AS totpay,
				CASE
					WHEN c.invoiceno is null THEN d.tbv
					ELSE c.tbv
				END AS tbv,
				CASE
					WHEN c.invoiceno is null THEN d.dfno
					ELSE c.dfno
				END AS dfno
				FROM intrh b
				LEFT OUTER JOIN ordivtrh c ON (b.applyto = c.receiptno)
				LEFT OUTER JOIN ordtrh d ON (b.applyto = d.receiptno)
				WHERE b.GDO = '$do'";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
  }

    function getRecoveredSSR($trxno) {
        $query = "SELECT B.TRCD, SN.BATCHNO,
                    SN.TRCD, SNVD.PAYTYPE,
                    SNVD.VHCNO, B.REFNO
                    FROM SC_NEWTRP_VC_DET SNVD
                    LEFT OUTER JOIN SC_NEWTRH SN ON SNVD.TRCD = SN.BATCHNO
                    LEFT OUTER JOIN BBHDR B ON SNVD.TRCD2 = B.TRCD
                    WHERE SNVD.TRCD = ?";
        return $this->getRecordset($query, $trxno, $this->db2);
	}

	function headerWmsDO($id) {
		$qry = "SELECT a.NO_DO as no_do, c.WAREHOUSE_NAME as sent_from, a.ID_STOCKIES + ' - ' + a.NAMA as ship_to, 
					a.NO_RESI as resi, CONVERT(VARCHAR(10), a.TANGGAL_DO, 120) as tgl_do, b.NAMA as courier, 
					a.CREATED_BY as create_by
				FROM klink_whm.dbo.T_DO a
				LEFT OUTER JOIN klink_whm.dbo.COURIER b ON (a.ID_COURIER = b.ID )
				LEFT OUTER JOIN klink_whm.dbo.MASTER_WAREHOUSE c ON (a.ID_WAREHOUSE = c.ID_WAREHOUSE)
				WHERE a.NO_DO = '$id'";
		return $this->getRecordsetArray($qry, null, $this->db2);
	}

	function listKwByDoWMS($id) {
		$qry = "SELECT DISTINCT(a.NO_KWITANSI) as no_kw, c.invoiceno as no_cn, C.batchscno as no_ssr, c.tdp , c.tbv , CONVERT(VARCHAR(10), c.createdt , 120) as cn_date,
				c.createnm as cn_createnm, c.loccd as main_stk, c.sc_dfno as stk
				FROM klink_whm.dbo.T_DETAIL_DO a
				INNER JOIN klink_whm.dbo.T_DO b ON (a.ID_DO = b.ID_DO)
				LEFT OUTER JOIN klink_mlm2010.dbo.ordivtrh c ON (a.NO_KWITANSI COLLATE SQL_Latin1_General_CP1_CS_AS = c.receiptno)
				WHERE b.NO_DO = '$id'";
		return $this->getRecordsetArray($qry, null, $this->db2);
	}	

	function listProdukDoWms($id) {
		/* $qry = "SELECT c.PRODUCT_CODE as prdcd, 
		             c.PRODUCT_NAME as prdnm, 
					 a.QTY as qty, 
					 ISNULL(d.QTY, 0) as qty_indent
				FROM klink_whm.dbo.T_DETAIL_DO a
				INNER JOIN klink_whm.dbo.T_DO b ON (a.ID_DO = b.ID_DO)
				LEFT OUTER JOIN klink_whm.dbo.MASTER_PRODUK c ON (a.ID_PRODUCT = c.ID_PRODUCT)
				LEFT OUTER JOIN klink_whm.dbo.T_INDENT d ON (a.ID_DO = d.ID_DO AND a.ID_PRODUCT = d.ID_PRODUCT)
				WHERE b.NO_DO = '$id'"; */
		$qry = "SELECT
					XX.prdcd,
					XX.prdnm,
					SUM(qty) as qty,
					qty_indentx as qty_indextx,
					SUM(qty) - qty_indentx as qty_kirim
				FROM (
				SELECT c.PRODUCT_CODE as prdcd, 
					c.PRODUCT_NAME as prdnm, 
					a.QTY as qty, 
					ISNULL(d.QTY, 0) as qty_indentx,
					a.QTY - ISNULL(d.QTY, 0) as qty_kirim
				FROM klink_whm.dbo.T_DETAIL_DO a
				INNER JOIN klink_whm.dbo.T_DO b ON (a.ID_DO = b.ID_DO)
				LEFT OUTER JOIN klink_whm.dbo.MASTER_PRODUK c ON (a.ID_PRODUCT = c.ID_PRODUCT)
				LEFT OUTER JOIN klink_whm.dbo.T_INDENT d ON (a.ID_DO = d.ID_DO AND a.ID_PRODUCT = d.ID_PRODUCT)
				WHERE b.NO_DO = '$id'
				) XX
				GROUP BY XX.prdcd, XX.prdnm, XX.qty_indentx ";		
		return $this->getRecordsetArray($qry, null, $this->db2);
	}

	function listIndentByDo($id) {
		$qry = "select a.ID_PRODUCT, PRODUCT_CODE, PRODUCT_NAME, SUM(QTY)as total
				from T_INDENT a
				left join MASTER_PRODUK b on a.ID_PRODUCT=b.ID_PRODUCT
				where a.ID_DO = ?
				and a.IS_ACTIVE != 1
				group by a.ID_PRODUCT, PRODUCT_NAME,PRODUCT_CODE";
	}
	
	function getDOWms($no_trx) {
		$header = $this->headerWmsDO($no_trx);
		if($header == null) {
			return jsonFalseResponse("DO tidak ada..");		
		}

		$arr = array(
			"response" => "true",
			"header" => $header,
			"listKW" => $this->listKwByDoWMS($no_trx),
			"listPrd" => $this->listProdukDoWms($no_trx)
		);

		return $arr;
	}

	function log_update_bv($dfno, $bnsperiod) {
		/* $qry1 = "SELECT a.notrx , a.prev_id , 
					a.trf_to, a1.fullnm, 
					b.dfno, b2.fullnm as bv_akhir, 
					CONVERT(VARCHAR(10), a.trf_dt , 120) as trf_dt, a.updfrom , a.undo_bv,
					b.bnsperiod , b.remarks 
				FROM klink_mlm2010.dbo.knet_bv_update_log a
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb a1 ON (a.trf_to COLLATE SQL_Latin1_General_CP1_CS_AS = a1.dfno )
				INNER JOIN klink_mlm2010.dbo.newtrh b ON (a.notrx COLLATE SQL_Latin1_General_CP1_CS_AS = b.trcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b2 ON (b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = b2.dfno)
				WHERE a.prev_id = '$dfno' AND CONVERT(VARCHAR(10), b.bnsperiod , 120) = '$bnsperiod'
				ORDER BY a.notrx";
		$res1 = $this->getRecordsetArray($qry1, null, $this->db2); */	

		$qry2 = "SELECT a.DFNO , SUM(b.tbv) as total_bv_awal
				FROM klink_mlm2010.dbo.NHBJAM2019 a
				INNER JOIN klink_mlm2010.dbo.newtrh b ON (a.trcd COLLATE SQL_Latin1_General_CP1_CS_AS = b.trcd )
				WHERE a.DFNO = '$dfno' AND CONVERT(VARCHAR(10), b.bnsperiod , 120) = '$bnsperiod'
				GROUP BY a.DFNO";
		$res2 = $this->getRecordsetArray($qry2, null, $this->db2);

		$qry3 = "SELECT a.trcd , b.tbv, 
		        b.remarks, a1.trf_to , 
						CONVERT(VARCHAR(16), a1.trf_dt , 120) as trf_dt, b.dfno as DFNO_AKHIR , b1.fullnm,
						a1.update_by
				--SUM(b.tbv) as bv_akhir 
				FROM klink_mlm2010.dbo.NHBJAM2019 a
				LEFT OUTER JOIN klink_mlm2010.dbo.knet_bv_update_log a1 ON (a.trcd COLLATE SQL_Latin1_General_CP1_CS_AS = a1.notrx)
				INNER JOIN klink_mlm2010.dbo.newtrh b ON (a.trcd COLLATE SQL_Latin1_General_CP1_CS_AS = b.trcd )
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b1 ON (b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = b1.dfno )
				WHERE a.DFNO = '$dfno' AND b.DFNO != '$dfno' AND CONVERT(VARCHAR(10), b.bnsperiod , 120) = '$bnsperiod'
				ORDER BY a.trcd, a1.trf_dt";
		$res3 = $this->getRecordsetArray($qry3, null, $this->db2);

		$hasil = array(
			"log_bv" => $res3,
			"bv_sebelum_pindah" => $res2
		);

		return $hasil;
	}

	function listVchMember($idmember, $month, $year) {
		$qry = "SELECT CASE WHEN a.vchtype = 'P' THEN a.VoucherNo ELSE a.voucherkey END AS no_vch, 
					a.VoucherAmt , CONVERT(VARCHAR(10), a.ExpireDate, 120) as tgl_expire , 
					CASE WHEN	a.claimstatus = '1' THEN 'Sudah Di klaim' ELSE 'Blm diklaim' END AS status_klaim , 
					CONVERT(VARCHAR(10), a.claim_date, 120) as claim_date , a.loccd 
					FROM klink_mlm2010.dbo.tcvoucher a 
					WHERE a.DistributorCode = '$idmember' 
					  and a.BonusYear = $year AND a.BonusMonth = $month";
		$res3 = $this->getRecordsetArray($qry, null, $this->db2);
		
		$arrDetail = array();
		foreach($res3 as $dta) {
			if(substr($dta['no_vch'], 0, 3) === "XPV") {
				$listPrdFree = "SELECT a.VoucherNo,
												b.prdcd, b.prdnm, b.qtyord
												FROM klink_mlm2010.dbo.TWA_KLPromo_Oct17_H a
												LEFT OUTER JOIN klink_mlm2010.dbo.TWA_KLPromo_Oct17_D b ON (a.VoucherNo = b.Voucherno)
												WHERE a.Voucherno = '$dta[no_vch]'";
				$resPrd = $this->getRecordsetArray($listPrdFree, null, $this->db2);
				array_push($arrDetail, $resPrd);
			}
		}

		$arr = array(
			"listVch" => $res3,
			"detailPrd" => $arrDetail,
		);
		
		return $arr;
	}
}