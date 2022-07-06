<?php
class Sales_stockist_report_model extends MY_Model {

	function __construct() {
        // Call the Model constructor
        parent::__construct();
	}

	function getDetailVoucher($formno, $voucherkey, $usergroup) {

		 
		if($usergroup != "ADMIN") {
			$par = " AND a.vchkey = ?"; 
			$arrParam = array(
				$formno, $voucherkey
			);
		} else {
			$par = "";
			$arrParam = array(
				$formno
			);
		}

		$cekVoucherNum = "SELECT
		                      a.status,
					          a.vchkey,
					          a.formno,
					          a.activate_by,
					          a.prdcd,
					          c.prdnm,
					          a.updatenm,
					          a.activate_dfno,
					          b.fullnm as nama_member_aktif,
					          CONVERT(VARCHAR(30), a.updatedt, 103) AS tgl2,
					          sold_trcd,
					          sold_trcdnewera
					      FROM
					          starterkit a
					          LEFT OUTER JOIN msmemb b ON (a.activate_dfno = b.dfno)
							  LEFT OUTER JOIN msprd c ON (a.prdcd = c.prdcd)
					      WHERE
							  (a.formno = ? $par)";
		
        $result = $this->getRecordset($cekVoucherNum, $arrParam, $this->db2);
		//var_dump($result);
		return $result;
	}

	function getListGeneratedSales($x)
    {
        //$x['from'],$x['to'],$x['idstkk'],$x['bnsperiod'],$x['searchs']
        //$username = $this->session->userdata('stockist');
        $froms = date('Y/m/d', strtotime($x['from']));
        $tos = date('Y/m/d', strtotime($x['to']));

        /*if($x['searchs'] == "stock"){
            $usertype = "a.sc_dfno = '".$x['mainstk']."' and a.sctype = '".$x['sctype']."'";
        }*/

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."1";

        $slc = "select * from V_HILAL_SCO_SSR_LIST a
        		WHERE a.loccd = ?
        		AND a.sctype = ?
        		AND a.bnsperiod = ?
        		AND a.flag_batch <> '0'
        		AND (a.batchno <> '' OR a.batchno is not null)
        		ORDER BY a.batchdt desc";
	   //echo $slc;
	   $arrParam = array(
		$x['sc_dfno'],
		$x['sctype'],
		$bonusperiod
	   );
       return $this->getRecordset($slc, null, $this->db2);
    }

	function getHeaderSsr($field, $value) {
		$qry = "SELECT top 1 a.trcd, a.orderno, a.batchno,
						a.sc_dfno, b.fullnm as sc_dfno_name,
						a.sc_co, c.fullnm as sc_co_name,
						a.loccd, d.fullnm as loccd_name,
						a.createnm, a.id_deposit, a.no_deposit,
						CONVERT(char(10), a.etdt,126) as etdt,
						CONVERT(char(10), a.batchdt,126) as batchdt,
						CONVERT(char(10), a.bnsperiod,126) as bnsperiod,
						e.trcd as cn_no,
						CONVERT(char(10), e.createdt,126) as cn_dt,
						e.createnm as cn_createnm,
						f.trcd as kw_no,
						CONVERT(char(10), f.createdt,126) as kw_dt,
						f.createnm as kw_createnm,
						g.no_do as do_no,
						CONVERT(VARCHAR(10), g.do_date, 126) as do_dt,
						g.do_createby as do_createnm,
						a.dfno, a1.fullnm as distnm, a.tdp, a.tbv
				FROM sc_newtrh a
				LEFT OUTER JOIN msmemb a1 ON (a.dfno = a1.dfno)
				LEFT OUTER JOIN mssc b ON (a.sc_dfno = b.loccd)
				LEFT OUTER JOIN mssc c ON (a.sc_co = c.loccd)
				LEFT OUTER JOIN mssc d ON (a.loccd = d.loccd)
				LEFT OUTER JOIN ordivtrh e ON (a.batchno = e.batchscno)
				LEFT OUTER JOIN billivhdr f ON (e.receiptno = f.trcd)
				LEFT OUTER JOIN DO_NINGSIH G ON (e.receiptno COLLATE SQL_Latin1_General_CP1_CI_AS = g.no_kwitansi)
				WHERE a.$field = ?";
		return $this->getRecordset($qry, $value, $this->db2);
	}

	function getListSummaryTtp($field, $value) {
		$slc = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm, a.tdp, a.tbv
				FROM sc_newtrh a
				LEFT OUTER JOIN msmemb b ON (a.dfno = b.dfno)
				WHERE a.$field = ?";
       //echo $slc;
       return $this->getRecordset($slc, $value, $this->db2);
	}

	function getListSummaryProduct($field, $value) {
		$qry = "SELECT a.prdcd, b.prdnm, SUM(a.qtyord) as qtyord
				FROM sc_newtrd a
				INNER JOIN sc_newtrh c ON (a.trcd = c.trcd)
				LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
				WHERE c.$field = ?
				GROUP BY a.prdcd, b.prdnm
				ORDER BY a.prdcd, b.prdnm";
		return $this->getRecordset($qry, $value, $this->db2);
	}

	function getListProductByTrcd($field, $value) {
		$qry = "SELECT a.prdcd, b.prdnm, a.qtyord, c.dp, c.bv
				FROM sc_newtrd a
				INNER JOIN sc_newtrh a1 ON (a.trcd = a1.trcd)
				LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
				LEFT OUTER JOIN pricetab c ON (a.prdcd = c.prdcd AND a1.pricecode = c.pricecode)
				WHERE a.$field = ?
				ORDER BY a.prdcd, b.prdnm";
		return $this->getRecordset($qry, $value, $this->db2);
	}

	function getVoucherReportList($field, $value) {
		$qry = "SELECT a.claimstatus, c.trcd, d.batchno, d.batchdt, d.loccd,
				       a.DistributorCode, b.fullnm, a.voucherkey, a.VoucherNo as VoucherNo,
				       a.vchtype,a.VoucherAmt,
				       CONVERT(char(10), d.createdt, 126) as tglklaim,
				       CONVERT(char(10), a.ExpireDate,126) as ExpireDate,
				       CONVERT(char(10), GETDATE(),126) as nowDate,
				       CASE
				           WHEN CONVERT(char(10), GETDATE(),126) > CONVERT(char(10), a.ExpireDate,126) THEN '1'
				           ELSE '0'
				       END AS status_expire
				FROM tcvoucher a
				INNER JOIN msmemb b ON (a.DistributorCode = b.dfno)
				LEFT JOIN sc_newtrp c ON (a.VoucherNo = c.docno)
				LEFT JOIN sc_newtrh d ON ( d.trcd =
					CASE
	                    WHEN c.trcd is null THEN '0'
	                    WHEN c.trcd is not null THEN c.trcd
	                END
				)
				WHERE a.$field = ?";
		//echo $qry;
		return $this->getRecordset($qry, $value, $this->db2);
	}

	function getVoucherUmrohReport($idmember, $novoucher, $tipe, $usergroup) {

		$discode = "";
		if($usergroup !== "ADMIN") {
			$discode = " AND a.DistributorCode = ?";
		}

		$qry = "SELECT a.DistributorCode, b.fullnm, a.vchtype,
					CONVERT(VARCHAR(10),a.ExpireDate, 120) as ExpireDate,
					CONVERT(VARCHAR(10),a.claim_date, 120) as claim_date,
					a.claimstatus,
					CASE WHEN a.claimstatus = '0' THEN 'Belum diklaim'
					WHEN a.claimstatus = '1' THEN 'Sudah diklaim'
					END as status_claim,
					a.VoucherAmt as nilaivoucher,
					CASE WHEN CONVERT(VARCHAR(10),a.ExpireDate, 111) > GETDATE() THEN 0 else 1
					end as status_exp,
					a.VoucherNo as voucherno, a.voucherkey, a.loccd,a.VoucherNo, a.status_open, a.remarks
				FROM klink_mlm2010.dbo.tcvoucher a
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.DistributorCode = b.dfno)
				WHERE (a.VoucherNo = ? OR a.voucherkey = ?)
				$discode";
			//echo $qry;
		$arrParam = array(
			$novoucher, $novoucher
		);

		if($discode !== "") {
			array_push($arrParam, $idmember);
		}
		$headerVch = $this->getRecordset($qry, $arrParam, $this->db2);
		/* if($headerVch != null) {
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;
			$vch_deposit = 2;

			$qryTrx = "SELECT a.trcd, b.dfno, CONVERT(VARCHAR(10),b.etdt, 120) as createdt, b.loccd
						FROM sc_newtrp a
						LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd)
						WHERE a.docno = ?
						   OR a.docno = ?
						ORDER BY a.trcd DESC";
			$arrParam = array(
				$headerVch[0]->voucherno,
				$headerVch[0]->voucherkey,
			);
			$trxClaim = $this->getRecordset($qryTrx, $arrParam, $this->db2);
			$arr['trx'] = $trxClaim;
			$arr['deposit_vch'] = $vch_deposit;
		} else {
			$arr['response'] = "false";
			$arr['message'] = "Data voucher tidak ditemukan";
		}
		return $arr; */

		if($headerVch != null && $headerVch[0]->loccd != "K-Net" && $headerVch[0]->loccd != "K-NET") {
			//echo "masuk sinii";
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;
			$vch_deposit = 2;

			$qryTrx = "SELECT a.trcd, b.dfno, CONVERT(VARCHAR(10),b.etdt, 120) as createdt,
							b.loccd, c.fullnm as loccd_name
						FROM klink_mlm2010.dbo.sc_newtrp a
						LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
						LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON (b.loccd = c.loccd)
						WHERE a.docno = ?
						ORDER BY a.trcd DESC";
			$trxClaim = $this->getRecordset($qryTrx, $novoucher, $this->db2);
			//print_r($trxClaim);
			if($trxClaim == null) {
				$qryTrx = "SELECT b.invoiceno as trcd, b.dfno, CONVERT(VARCHAR(10),b.tgltrans, 120) as createdt,
							c.loccd, d.fullnm as loccd_name
							FROM KL_TVOCASH a
							LEFT OUTER JOIN KL_TEMPTRANS b ON (a.grupunik COLLATE SQL_Latin1_General_CP1_CS_AS = b.grupunik)
							LEFT OUTER JOIN newtrh c ON (b.invoiceno COLLATE SQL_Latin1_General_CP1_CS_AS = c.trcd)
							LEFT OUTER JOIN mssc d ON (c.loccd = d.loccd)
							LEFT OUTER JOIN kpv_header e ON (a.grupunik COLLATE SQL_Latin1_General_CP1_CS_AS = e.registerno)
							WHERE a.voucherno = ?";

				$trxClaim = $this->getRecordset($qryTrx, $novoucher, $this->db2);
			}

			if($trxClaim == null) {
				$qryTrx2 = "SELECT a.voucher_scan, a.createnm as loccd, b.fullnm as loccd_name,
							a.no_trx as trcd, a.dfno,
							CONVERT(VARCHAR(10),a.createdt , 120) as createdt
							FROM deposit_D a
							LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.createnm COLLATE SQL_Latin1_General_CP1_CS_AS = b.loccd)
							WHERE a.voucher_scan = ?";
				$trxClaim2 = $this->getRecordset($qryTrx2, $novoucher, $this->db2);
				//print_r($trxClaim2);
				if($trxClaim2 != null) {
					$vch_deposit = 1;
					$arr['trx'] = $trxClaim2;
					$arr['deposit_vch'] = $vch_deposit;
				} else {
					$arr['trx'] = $trxClaim2;
					$arr['deposit_vch'] = 0;
				}
			} else {
				$arr['trx'] = $trxClaim;
				$arr['deposit_vch'] = $vch_deposit;
			}
		} else if($headerVch != null && ($headerVch[0]->loccd == "K-Net" || $headerVch[0]->loccd == "K-NET")) {
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;

			$qryTrx2 = "SELECT a1.orderno as trcd, a1.id_memb as dfno,
							CONVERT(VARCHAR(10),a1.datetrans, 120) as createdt,
							'K-NET' as loccd, 'K-NET' as loccd_name
						FROM db_ecommerce.dbo.ecomm_trans_paydet a
						INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr a1
							ON (a.orderno COLLATE SQL_Latin1_General_CP1_CI_AS = a1.orderno)
						WHERE a.docno = ?";
				$trxClaim2 = $this->getRecordset($qryTrx2, $novoucher, $this->db2);
			$arr['trx'] = $trxClaim2;
		}else {
			$arr['response'] = "false";
			$arr['message'] = "Data voucher tidak ditemukan";
		}
		return $arr;
	}

	function getListVchByDist($idmember, $tipe, $usergroup) {
		if($tipe == "vc_prd" || $tipe == "vc_prm") {
			$vc = "VoucherNo";
			$vcas = "a.VoucherNo as voucherno, ";
			$vchLine = "AND a.voucherkey NOT LIKE 'V%' and a.vchtype != 'C'";
		} else if($tipe == "vc_c") {
			$vc = "voucherkey";
			$vcas = "a.voucherkey as voucherno, ";
			$vchLine = "AND a.voucherkey LIKE 'V%' AND a.voucherkey NOT LIKE 'VUMR%'";
		} else if($tipe == "vc_umr") {
			$vcas = "a.voucherkey as voucherno, ";
			$vchLine = "AND a.voucherkey LIKE 'VUMR%'";
		}

		$class = 'btn btn-mini btn-success';
		$qry = "SELECT $vcas
			        a.VoucherAmt as nilaivoucher,
					CONVERT(VARCHAR(10),a.ExpireDate, 120) as ExpireDate,
					CASE WHEN a.claimstatus = '0' THEN 'Belum diklaim'
					WHEN a.claimstatus = '1' THEN 'Sudah diklaim'
					END as status_claim,
					a.loccd + ' - ' +CONVERT(VARCHAR(10),a.claim_date, 120) as lokasi,
					CASE WHEN a.status_open = '1' THEN 'Sdh diaktifkan' ELSE 'Blm diaktifkan'
					END AS stt_open
				FROM klink_mlm2010.dbo.tcvoucher a
				WHERE a.DistributorCode = ? $vchLine";
		//echo $qry;
		$arrParam = array(
			$idmember
		);
		$headerVch = $this->getRecordsetArray($qry, $arrParam, $this->db2);

		if($headerVch !== null) {
			$arrPass = array();
			$i = 0;
			foreach($headerVch as $dtax) {
				array_push($arrPass, $dtax);
				$urlx = 'sales/voucher/no/'.$dtax['voucherno'];
				$arrPass[$i]['btn'] = "<input type='button' class='btn btn-mini btn-success' onclick=\"All.ajaxShowDetailonNextForm('$urlx')\" value='View' />";
				$i++;
			}
			/* echo "<pre>";
			print_r($arrPass);
			echo "</pre>"; */
			return $arrPass;
		} else {
			return null;
		}
	}

	function getVoucherReportListV2($idmember, $novoucher, $tipe, $usergroup) {
		$vc = "VoucherNo";
		$vcas = "a.VoucherNo as voucherno, ";
		if($tipe == "vc_prd" || $tipe == "vc_prm") {
			$vc = "VoucherNo";
			$vcas = "a.VoucherNo as voucherno, ";
		} else if($tipe == "vc_c") {
			$vc = "voucherkey";
			$vcas = "a.voucherkey as voucherno, ";
		}

		$discode = "";
		if($usergroup !== "ADMIN") {
			$discode = " AND UPPER(a.DistributorCode) = ?";
		}


		$qry = "SELECT a.DistributorCode, b.fullnm, $vcas a.vchtype,
					CONVERT(VARCHAR(10),a.ExpireDate, 120) as ExpireDate,
					CONVERT(VARCHAR(10),a.claim_date, 120) as claim_date,
					a.claimstatus,
					CASE WHEN a.claimstatus = '0' THEN 'Belum diklaim'
					WHEN a.claimstatus = '1' THEN 'Sudah diklaim'
					END as status_claim,
					a.VoucherAmt as nilaivoucher, a.loccd, a.remarks,
					CASE WHEN CONVERT(VARCHAR(10),a.ExpireDate, 120) > GETDATE() THEN 0 else 1
					end as status_exp, a.VoucherNo, a.voucherkey, a.status_open, CONVERT(VARCHAR(10),a.openstatus_dt, 120) as openstatus_dt
				FROM klink_mlm2010.dbo.tcvoucher a
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.DistributorCode = b.dfno)
				WHERE UPPER(a.$vc) = ? $discode";

		//echo $qry;
		$arrParam = array(
			$novoucher
		);

		if($discode !== "") {
			array_push($arrParam, $idmember);
		}
		$headerVch = $this->getRecordset($qry, $arrParam, $this->db2);
		if($headerVch != null && $headerVch[0]->loccd != "K-Net" && $headerVch[0]->loccd != "K-NET") {
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;
			$vch_deposit = 0;

			$qryTrx = "SELECT a.trcd, b.dfno, CONVERT(VARCHAR(10),b.etdt, 120) as createdt,
							b.loccd, c.fullnm as loccd_name
						FROM klink_mlm2010.dbo.sc_newtrp a
						LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
						LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON (b.loccd = c.loccd)
						WHERE a.docno = ?
						ORDER BY a.trcd DESC";
			$trxClaim = $this->getRecordset($qryTrx, $novoucher, $this->db2);
			if($trxClaim == null) {
				$qryTrx = "SELECT b.invoiceno as trcd, b.dfno, CONVERT(VARCHAR(10),b.tgltrans, 120) as createdt,
							c.loccd, d.fullnm as loccd_name
							FROM KL_TVOCASH a
							LEFT OUTER JOIN KL_TEMPTRANS b ON (a.grupunik COLLATE SQL_Latin1_General_CP1_CS_AS = b.grupunik)
							LEFT OUTER JOIN newtrh c ON (b.invoiceno COLLATE SQL_Latin1_General_CP1_CS_AS = c.trcd)
							LEFT OUTER JOIN mssc d ON (c.loccd = d.loccd)
							LEFT OUTER JOIN kpv_header e ON (a.grupunik COLLATE SQL_Latin1_General_CP1_CS_AS = e.registerno)
							WHERE a.voucherno = ?";

				$trxClaim = $this->getRecordset($qryTrx, $novoucher, $this->db2);
			}

			if($trxClaim == null) {
				$qryTrx2 = "SELECT a.voucher_scan, a.createnm as loccd, b.fullnm as loccd_name,
							a.no_trx as trcd, a.dfno,
							CONVERT(VARCHAR(10),a.createdt , 120) as createdt
							FROM deposit_D a
							LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.createnm COLLATE SQL_Latin1_General_CP1_CS_AS = b.loccd)
							WHERE a.voucher_scan = ?";
				$trxClaim2 = $this->getRecordset($qryTrx2, $novoucher, $this->db2);

				if($trxClaim2 != null) {
					$vch_deposit = 1;
					$arr['trx'] = $trxClaim2;
					$arr['deposit_vch'] = $vch_deposit;
				} else {
					$arr['trx'] = $trxClaim2;
					$arr['deposit_vch'] = 0;
				}
			} else {
				$arr['trx'] = $trxClaim;
				$arr['deposit_vch'] = $vch_deposit;
			}
		} else if($headerVch != null && ($headerVch[0]->loccd == "K-Net" || $headerVch[0]->loccd == "K-NET")) {
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;

			$qryTrx2 = "SELECT a1.orderno as trcd, a1.id_memb as dfno,
							CONVERT(VARCHAR(10),a1.datetrans, 120) as createdt,
							'K-NET' as loccd, 'K-NET' as loccd_name
						FROM db_ecommerce.dbo.ecomm_trans_paydet a
						INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr a1
							ON (a.orderno COLLATE SQL_Latin1_General_CP1_CI_AS = a1.orderno)
						WHERE a.docno = ?";
				$trxClaim2 = $this->getRecordset($qryTrx2, $novoucher, $this->db2);
			$arr['trx'] = $trxClaim2;
		}else {
			$arr['response'] = "false";
			$arr['message'] = "Data voucher tidak ditemukan";
		}

		$vchPromo = $this->getListProdPromo($novoucher);
		$arr['vchPromo'] = $vchPromo;
		return $arr;
	}

	public function getListProdPromo($vchnoo)
    {
        $detProd = "SELECT * FROM klink_mlm2010.dbo.TWA_KLPromo_Oct17_D WHERE Voucherno = ?";
        $res2 = $this->getRecordset($detProd, $vchnoo, $this->db2);
        return $res2;
    }

	function getGenerateRPTByCahyono($arr)
    {
		$from = $arr['from'];
		$to = $arr['to'];
		$username = $arr['main_stk'];
		$idstkk = $arr['idstkk'];
		$searchBy = $arr['searchs'];
		$status = $arr['statuses'];
        //$username = $this->session->userdata('username');
        //$froms = date('Y-m-d', strtotime($from));
        //$tos = date('Y-m-d', strtotime($to));
		$usertype="";
		$bnsperiod = $arr['bnsperiod'];

		$arrParam = array(
			$username,
			//$froms." 00:00:00",
			//$tos." 23:59:59",
			$bnsperiod,
		);

        if($searchBy == "ALL") {
            $usertype="";
        }
        else {
			$usertype = "and a.batchno like ? ";
			$str1 = "$searchBy%";
			array_push($arrParam, $str1);
        }

        if($status == "ALL"){
            $usertype2="";
        }
        else {
			$usertype2 = "and A.flag_batch = ? ";
			array_push($arrParam, $status);
		}

		$kodestk = "";
		if($idstkk != "" && $idstkk != null) {
			$kodestk = "and a.sc_dfno = ?' ";
			array_push($arrParam, $idstkk);
		}

		array_push($arrParam, $from);
		array_push($arrParam, $to);
		/* 
		SELECT 	a.trcd,a.tdp,
											CASE
												WHEN C.paytype='01' THEN (c.payamt)
											END AS cash,
											CASE
												WHEN (C.paytype='08' or C.paytype='10')  THEN (c.payamt)
											END AS vcash,

											CASE
												WHEN A.flag_batch='0' THEN 'Inputed'
												WHEN A.flag_batch='1' THEN 'Generated'
												WHEN A.flag_batch='2' and (D2.GDO is null OR D2.GDO = '') THEN 'Approved'
												WHEN A.flag_batch='2' AND (D2.GDO is not null AND D2.GDO != '') THEN 'DO Created'
											END AS x_status,
											A.sc_dfno,
										B1.fullnm AS fullnm_DFNO,
										
										A.loccd,
							
										A.createnm,
										A.batchno,
										CONVERT(VARCHAR(10),A.batchdt, 120) as batchdt,
										A.tbv
									FROM sc_newtrh A
										LEFT JOIN sc_newtrp C ON a.trcd=C.trcd
										INNER JOIN mssc B1 ON A.sc_dfno=B1.loccd
										LEFT OUTER JOIN ordivtrh D1 on (A.csno = D1.invoiceno)
										LEFT OUTER JOIN intrh D2 ON (D1.receiptno = D2.applyto)

		
								WHERE A.createnm = 'IDSL195'
								AND A.bnsperiod = '2021-01-01'
								AND A.batchno != 'IDBL' and a.batchno is not NULL and a.batchno != ''
		*/

        $slc="SELECT SUM(TOTDP) as TOTDP, 
		            SUM(cash) as cash,
					SUM(vcash) as vcash, 
					sc_dfno, fullnm_DFNO,
					
					loccd,  
					createnm, batchno, batchdt, x_status,
					sum(TOTBV) as TOTBV, trcd2
			  FROM (
					SELECT a.* ,
						b.trcd2
					from (
						SELECT trcd, (TOTDP) AS TOTDP,
							sum(cash) as cash,
							sum(vcash) as vcash,
							sc_dfno,
							fullnm_DFNO,
							
							loccd,
							
							createnm,
							batchno,
							batchdt,
							x_status,
							(TOTBV) as TOTBV
							FROM (
								SELECT trcd, (tdp) as TOTDP,
									SUM(cash) as cash,
									sum(vcash) as vcash,
									sui.sc_dfno,
									fullnm_DFNO,
						
							
									sui.loccd,
									
									sui.createnm,
									batchno,
									batchdt,
									x_status,
									(tbv) as TOTBV
								from
								(
									SELECT 	a.trcd,a.tdp, 
											--a.batchno, a.csno , d1.receiptno , d2.no_do ,
											CASE
												WHEN C.paytype='01' THEN (c.payamt)
											END AS cash,
											CASE
												WHEN (C.paytype='08' or C.paytype='10')  THEN (c.payamt)
											END AS vcash,

											CASE
												WHEN A.flag_batch='0' THEN 'Inputed'
												WHEN A.flag_batch='1' THEN 'Generated'
												WHEN A.flag_batch='2' AND a.csno is not null and (D2.no_do is null OR D2.no_do = '') THEN 'Approved'
												WHEN A.flag_batch='2' AND a.csno is not null and (D2.no_do is not null AND D2.no_do != '') THEN 'DO Created'
												ELSE ''
											END AS x_status,
											A.sc_dfno,
										B1.fullnm AS fullnm_DFNO,
										
										A.loccd,
							
										A.createnm,
										A.batchno,
										CONVERT(VARCHAR(10),A.batchdt, 120) as batchdt,
										A.tbv
									FROM klink_mlm2010.dbo.sc_newtrh A
										LEFT JOIN klink_mlm2010.dbo.sc_newtrp C ON a.trcd=C.trcd
										INNER JOIN klink_mlm2010.dbo.mssc B1 ON A.sc_dfno=B1.loccd
										LEFT OUTER JOIN klink_mlm2010.dbo.ordivtrh D1 on (A.csno = D1.invoiceno AND a.csno is not null and a.csno != '')
										LEFT OUTER JOIN klink_mlm2010.dbo.DO_NINGSIH D2 ON (D1.receiptno is not null AND D1.receiptno COLLATE SQL_Latin1_General_CP1_CI_AS = D2.no_kwitansi )	
								WHERE A.createnm =?
								AND A.bnsperiod = ?
								AND A.batchno != 'IDBL' and a.batchno is not NULL and a.batchno != ''
								AND CONVERT(VARCHAR(10), a.batchdt, 120) BETWEEN ? AND ?
								$usertype
								$usertype2
								$kodestk
								) sui
								GROUP BY sui.trcd, sui.tdp,
									sui.sc_dfno,
									fullnm_DFNO,
									
									
									sui.loccd,
									
									sui.createnm,
									sui.batchno,
									sui.batchdt,
									x_status ,
									tbv
							) ey
				GROUP BY ey.trcd,ey.TOTDP, sc_dfno, ey.TOTbv,
					fullnm_DFNO,
					
					loccd,
					
					createnm,
					batchno,
					batchdt,
					x_status
			) a
				LEFT JOIN sc_newtrp_vc_det b on a.batchno = b.trcd
			GROUP BY a.trcd, a.TOTDP,
					a.cash,
					a.vcash,
					a.sc_dfno,
					a.fullnm_DFNO,
					
					a.loccd,
					
					a.createnm,
					a.batchno,
					a.batchdt,
					a.x_status,
					a.TOTBV,
					b.trcd2
			) final
			GROUP BY
			sc_dfno, fullnm_DFNO, 
			
			loccd,
			 
			createnm, 
			batchno, batchdt, 
			x_status,  trcd2
			ORDER BY sc_dfno";
        
		/* echo '<pre>';
		echo $slc;
        print_r($arrParam);
        echo '</pre>'; */
		//return $this->get_recordset($slc, $type, "alternate");

		if(array_key_exists("return", $arr) && $arr['return'] == "array") {
			return $this->getRecordsetArray($slc, $arrParam, $this->db2);
		} else {
			return $this->getRecordset($slc, $arrParam, $this->db2);
		}
	}

	function getListKnetTrx($arr) {
		$qry = "SELECT a.trcd, a.dfno, a.batchno, CONVERT(VARCHAR(10), a.etdt ,120) as etdt, a.tdp , a.tbv,
				CASE 
				WHEN b.sentTo = '1' THEN 'Stockist'
				WHEN b.sentTo = '2' THEN 'Alamat'
				ELSE '--'  
				END AS sent_stt,
				CASE WHEN b.is_cod = '1' THEN 'COD' ELSE 'NON-COD' END AS cod_status
				FROM klink_mlm2010.dbo.sc_newtrh a
				LEFT OUTER JOIN db_ecommerce.dbo.ecomm_trans_hdr b ON (a.trcd COLLATE SQL_Latin1_General_CP1_CI_AS = b.orderno)
				--LEFT OUTER JOIN klink_mlm2010.dbo.intrh c ON (b.KWno COLLATE SQL_Latin1_General_CP1_CI_AS = c.applyto )
				WHERE a.createnm = 'ECOMMERCE' and a.loccd = ?
				AND a.bnsperiod = ?";
		 $arrParam = array(
			$arr['main_stk'], $arr['bnsperiod']
		);
		/*echo $qry;
		print_r($arrParam); */
		$hasil = $this->getRecordset($qry, $arrParam, $this->db2);

		$return = array();
		$total_jum_reg_member = 0;
		$total_dp_reg_member = 0;
		$total_bv_reg_member = 0;
		$total_jum_reg_member_amb_stk = 0;
		$total_jum_reg_member_kirim_alamat = 0;
		$total_dp_reg_member_amb_stk = 0;
		$total_dp_reg_member_kirim_alamat = 0;
		$total_bv_reg_member_amb_stk = 0;
		$total_bv_reg_member_kirim_alamat = 0;

		$total_jum_sales = 0;
		$total_dp_sales = 0;
		$total_bv_sales = 0;
		$total_jum_sales_amb_stk = 0;
		$total_jum_sales_kirim_alamat = 0;
		$total_dp_sales_amb_stk = 0;
		$total_dp_sales_kirim_alamat = 0;
		$total_bv_sales_amb_stk = 0;
		$total_bv_sales_kirim_alamat = 0;
		if($hasil !== null) {
			foreach($hasil as $dta) {
				$arr = array(
					"trcd" => $dta->trcd,
					"dfno" => $dta->dfno,
					"batchno" => $dta->batchno,
					"etdt" => $dta->etdt,
					"tdp" => $dta->tdp,
					"tbv" => $dta->tbv,
					"sent_stt" => $dta->sent_stt,
					"cod_status" => $dta->cod_status
				);

				array_push($return, $arr);

				$pref = substr($dta->batchno, 0, 4);
				if($pref == "MMSE") {
					$total_jum_reg_member++;
					$total_dp_reg_member += $dta->tdp;
					$total_bv_reg_member += $dta->tbv;
					if($dta->sent_stt == "Stockist") {
						$total_jum_reg_member_amb_stk++;
						$total_dp_reg_member_amb_stk += $dta->tdp;
						$total_bv_reg_member_amb_stk += $dta->tbv;
					} else if($dta->sent_stt == "Alamat") {
						$total_jum_reg_member_kirim_alamat++;
						$total_dp_reg_member_kirim_alamat += $dta->tdp;
						$total_bv_reg_member_kirim_alamat += $dta->tbv;
					}
				} else {
					$total_jum_sales++;
					$total_dp_sales += $dta->tdp;
					$total_bv_sales += $dta->tbv;
					if($dta->sent_stt == "Stockist") {
						$total_jum_sales_amb_stk++;
						$total_dp_sales_amb_stk += $dta->tdp;
						$total_bv_sales_amb_stk += $dta->tbv;
					} else if($dta->sent_stt == "Alamat") {
						$total_jum_sales_kirim_alamat++;
						$total_dp_sales_kirim_alamat += $dta->tdp;
						$total_bv_sales_kirim_alamat += $dta->tbv;
					}
				}
			}
		}

		$arrReturn = array(
			"data" => $return,
			"rekap" => array(
				array(
					"tipe" => "K-Net Registrasi Member",
					"total_jum_trx" => $total_jum_reg_member,
					"total_dp" => $total_dp_reg_member,
					"total_bv" => $total_bv_reg_member,
					/* "jum_trx_stk" => $total_jum_reg_member_amb_stk,
					"jum_dp_stk" => $total_dp_reg_member_amb_stk,
					"jum_bv_stk" => $total_bv_reg_member_amb_stk,
					"jum_trx_alamat" => $total_jum_reg_member_kirim_alamat,
					"jum_dp_alamat" => $total_dp_reg_member_kirim_alamat,
					"jum_bv_alamat" => $total_bv_reg_member_kirim_alamat, */
				),
				array(
					"tipe" => "K-Net Sales",
					"total_jum_trx" => $total_jum_sales,
					"total_dp" => $total_dp_sales,
					"total_bv" => $total_bv_sales,
					/* "jum_trx_stk" => $total_jum_sales_amb_stk,
					"jum_dp_stk" => $total_dp_sales_amb_stk,
					"jum_bv_stk" => $total_bv_sales_amb_stk,
					"jum_trx_alamat" => $total_jum_sales_kirim_alamat,
					"jum_dp_alamat" => $total_dp_sales_kirim_alamat,
					"jum_bv_alamat" => $total_bv_sales_kirim_alamat, */
				)
			)
		);
		return $arrReturn;
	}

	function getListKnetTrxRekapProduk() {

		$qry = "SELECT 
			x1.prdcd,
			x1.prdnm,
			SUM(x1.qty) as qty
		FROM 
		(  
			SELECT 
				ISNULL(x.prdcdDet, x.prdcd) AS prdcd,
				ISNULL(x.prdcdNmDet, x.prdnm) AS prdnm,
				CASE 
				WHEN x.bundle_qty IS NULL THEN x.total_qty
				ELSE x.bundle_qty * x.total_qty
				END AS qty  
			FROM 
			(    
				SELECT a.prdcd, c.prdnm, SUM(a.qtyord) as total_qty, b1.prdcdDet , b1.prdcdNmDet , b1.qty as bundle_qty 
				FROM klink_mlm2010.dbo.sc_newtrd a
				LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.newera_PRDDET b1 ON (a.prdcd = b1.prdcdCat)
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd c ON (a.prdcd = c.prdcd)
				WHERE b.loccd = 'IDBSS01' AND b.bnsperiod = '2020-10-01' 
				AND b.batchno is not null AND b.batchno != ''
				AND b.createnm <> 'ECOMMERCE'
				GROUP BY a.prdcd, c.prdnm, b1.prdcdDet , b1.prdcdNmDet, b1.qty
			) x	
		) x1
		GROUP BY x1.prdcd, x1.prdnm
		ORDER BY x1.prdnm";

		return $this->getRecordsetArray($qry, null, $this->db2);
	}

	function summaryProductBySSR($value) {
		$qry = "SELECT a.prdcd, c.prdnm, d.pricecode,
					SUM(a.qtyord) as total_qty, d.dp, d.bv,
					SUM(a.qtyord * d.dp) as total_dp,
					SUM(a.qtyord * d.bv) as total_bv
				FROM sc_newtrd a
				LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd)
				LEFT OUTER JOIN msprd c ON (a.prdcd = c.prdcd)
				LEFT OUTER JOIN pricetab d ON (a.prdcd = d.prdcd AND b.pricecode = d.pricecode)
				WHERE b.batchno = ?
				GROUP BY a.prdcd, c.prdnm, d.pricecode, d.dp, d.bv";
		$qryParam = array($value);
		return $this->getRecordset($qry, $qryParam, $this->db2);
	}

	function listTtpById($field, $value) {

		$qry = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm,
					a.totpay, a.tbv,
					CONVERT(VARCHAR(10),a.etdt, 120) as etdt,
					CONVERT(VARCHAR(10),a.bnsperiod, 120) as bnsperiod,
					a.no_deposit, a.batchno,
					CONVERT(VARCHAR(10), a.batchdt, 120) as batchdt,
					D2.no_do AS GDO,
					CONVERT(VARCHAR(10), D2.do_date, 120) AS GDOdt,
					D2.do_createby as GDO_createnm,
					CASE WHEN a1.trcd is null THEN 'PENDING' ELSE 'OK' END AS status_bv
				FROM sc_newtrh a
				LEFT OUTER JOIN newtrh a1 ON (a.trcd = a1.trcd)
				INNER JOIN msmemb b ON (a.dfno = b.dfno)
				LEFT OUTER JOIN ordivtrh D1 on (A.batchno = D1.batchscno)
				LEFT OUTER JOIN DO_NINGSIH D2 ON (D1.receiptno COLLATE SQL_Latin1_General_CP1_CI_AS = D2.no_kwitansi)
				 WHERE a.$field = ?
				 ORDER BY trcd ASC";
		//echo $qry;
		$qryParam = array($value);
		$hasil1 = $this->getRecordset($qry, $qryParam, $this->db2);
		return $hasil1;
	}

	function listTtpByIdV2($field, $value) {

		$qry = "SELECT a.trcd, a.orderno,
						(CONVERT(VARCHAR(10), a.etdt, 120)) AS etdt, 
						a.dfno, b.fullnm, a.totpay, a.tdp, a.tbv, a.sc_dfno, 
						a.loccd, 
						(CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod, 
						a.no_deposit, a.batchno,
						D2.no_do AS GDO,
					CONVERT(VARCHAR(10), D2.do_date, 120) AS GDOdt,
					D2.do_createby as GDO_createnm,
					CASE WHEN a1.trcd is null THEN 'PENDING' ELSE 'OK' END AS status_bv,
						ISNULL((SELECT CASE WHEN (LEFT(A.trcd, 2) = 'ID') AND LEFT(A.trcd, 3) <> 'IDH' 
						THEN A.totpay ELSE sum(D.payamt) END AS payamt
						FROM sc_newtrp D
						WHERE A.trcd=D.trcd AND d.paytype='01'), 0) AS cash,
						ISNULL((SELECT sum(D.payamt)
						FROM sc_newtrp D
						WHERE A.trcd=D.trcd AND d.paytype='10'), 0) AS pcash,
						ISNULL((SELECT sum(D.payamt)
						FROM sc_newtrp D
						WHERE A.trcd=D.trcd AND d.paytype='08'), 0) AS vcash
					FROM klink_mlm2010.dbo.sc_newtrh A 
					LEFT OUTER JOIN msmemb b ON (A.dfno = b.dfno)
					LEFT OUTER JOIN newtrh a1 ON (a.trcd = a1.trcd)
					LEFT OUTER JOIN ordivtrh D1 on (A.batchno = D1.batchscno)
					LEFT OUTER JOIN DO_NINGSIH D2 ON (D1.receiptno COLLATE SQL_Latin1_General_CP1_CI_AS = D2.no_kwitansi)
				 WHERE a.$field = ?
				 ORDER BY trcd ASC";
		//echo $qry;
		$qryParam = array($value);
		$hasil1 = $this->getRecordset($qry, $qryParam, $this->db2);
		return $hasil1;
	}

	function listTTPbyIDV3($field, $value) {
		$qry = "SELECT a.trcd, a.orderno, a.pricecode,
								a.dfno, a1.fullnm, a.totpay, a.tdp,a.tbv, a.sc_dfno, 
								a.loccd, (CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod,
								SUM(b.qtyord * c.dp) as total_dp,
								SUM(b.qtyord * c.bv) as total_bv	
						FROM klink_mlm2010.dbo.sc_newtrh a
						LEFT OUTER JOIN msmemb a1 ON (a.dfno = a1.dfno)
						LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd b 
							ON (a.trcd = b.trcd)
						LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c 
							ON (b.prdcd = c.prdcd AND a.pricecode = c.pricecode)
						WHERE a.$field = ?
						GROUP BY a.trcd, a.orderno,a.pricecode,
								a.dfno, a1.fullnm, a.totpay, a.tdp, a.tbv, a.sc_dfno, 
								a.loccd, (CONVERT(VARCHAR(10), a.bnsperiod, 120))   --, c.dp, c.bv
						ORDER BY trcd ASC";

		/* $qry = "SELECT
							x.trcd,
							x.orderno,
							x.pricecode,
							x.dfno,
							x.fullnm,
							x.totpay,
							x.tdp,
							x.tbv,
							x.sc_dfno,
							x.loccd,
							x.bnsperiod,
							ISNULL(SUM(b.qtyord * c.dp), 0) as total_dp,
							ISNULL(SUM(b.qtyord * c.bv), 0) as total_bv	
						FROM
						(  
							SELECT a.trcd, a.orderno, a.pricecode,
									a.dfno, a1.fullnm, a.totpay, a.tdp, a.tbv, 
									a.sc_dfno, 
									a.loccd, CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod
							FROM klink_mlm2010.dbo.sc_newtrh a
							LEFT OUTER JOIN msmemb a1 ON (a.dfno = a1.dfno)
								WHERE a.$field = ?
						) x
						LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd b ON (x.trcd = b.trcd)
						LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON
							(b.prdcd = c.prdcd AND x.pricecode = c.pricecode)
						GROUP BY x.trcd, x.orderno, x.pricecode,
							x.dfno, x.fullnm, x.totpay, x.tdp, x.tbv, x.sc_dfno, 
							x.loccd, x.bnsperiod"; */				
		$qryParam = array($value);
		$hasil1 = $this->getRecordset($qry, $qryParam, $this->db2);
		return $hasil1;
	}

	function detailTrxByTrcd($field, $value) {
		$header = $this->getHeaderSsr($field, $value);
		if($header == null) {
			$arr = jsonFalseResponse("Data tidak ditemukan");
			return $arr;
		}
		$product = $this->getListProductByTrcd($field, $value);
		$payment = $this->getListPayment($field, $value);

		$arr = array(
			"response" => "true",
			"header" => $header,
			"product" => $product,
			"payment" => $payment,
		);
		return $arr;

	}

	function getListPayment($field, $value) {
		$qry = "SELECT a.trcd, a.paytype, b.description,
				a.payamt, a.vchtype, a.docno
				FROM sc_newtrp a
				LEFT OUTER JOIN paytype b ON (a.paytype = b.id)
				WHERE $field = ?";

		$res = $this->getRecordset($qry, $value, $this->db2);
		return $res;
	}

	function stkssr($param) {
		$tipeSsr = "";
		/* if($param['tipessr'] == "all") {

		} else */ if($param['tipessr'] == "allwk") {
			$tipeSsr = "and a.createnm = ?";
		} else if($param['tipessr'] == "knet") {
			$tipeSsr = "and a.createnm = 'ECOMMERCE' and a.loccd = ?";
		} else if($param['tipessr'] == "apl") {
			$tipeSsr = "and a.trtype = 'SB1' and a.ttptype LIKE 'MEMB%' and a.createnm = ?";
		} else if($param['tipessr'] == "ms") {
			$tipeSsr = "and a.trtype = 'SB1' and a.ttptype = 'SUBSC' and a.batchno LIKE 'MSR%' and a.createnm = ?";
		} else if($param['tipessr'] == "stock") {
			$tipeSsr = "and a.trtype = 'SB1' and a.ttptype = 'SC' and a.batchno LIKE 'SSR%' and a.createnm = ?";
		} else if($param['tipessr'] == "pvr") {
			$tipeSsr = "and a.trtype = 'VP1' and a.batchno LIKE 'PVR%' and a.createnm = ?";
		} else {
			$tipeSsr = "and a.trtype = 'SB1' and a.batchno LIKE 'SSSR%' and a.createnm = ?";
		}

		if($param['tipe'] == "stk") {
			$qry = "SELECT DISTINCT(a.sc_dfno) as select_key, a.sc_dfno + ' -' + ' ' + b.fullnm as select_value
					FROM klink_mlm2010.dbo.sc_newtrh a
					LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.sc_dfno = b.loccd)
					WHERE CONVERT(VARCHAR(10), a.batchdt ,120) BETWEEN ? AND ? AND a.batchno is not null AND a.batchno != ''
					$tipeSsr";
		} else {
			$qry = "SELECT DISTINCT(a.batchno ) as select_key, a.sc_dfno, a.batchno + ' -' + ' ' + a.sc_dfno  + ' -' + ' ' + b.fullnm as select_value 
					FROM klink_mlm2010.dbo.sc_newtrh a
					LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.sc_dfno = b.loccd)
					WHERE CONVERT(VARCHAR(10), a.batchdt ,120) BETWEEN ? AND ? AND a.batchno is not null AND a.batchno != ''
					$tipeSsr
					ORDER BY a.sc_dfno ";
		}
        //echo $qry;
		$arrParam = array(
			$param['from'],
			$param['to'],
			$param['mainstk']
		);
		//print_r($arrParam);
		$res = $this->getRecordset($qry, $arrParam, $this->db2);
		return $res;
	}

	function rekapProduk($param) {

		if($param['tipessr'] == "allwk") {
			$tipeSsr = "b.createnm = '$param[mainstk]'";
		} else if($param['tipessr'] == "knet") {
			$tipeSsr = "b.createnm = 'ECOMMERCE' and b.loccd = '$param[mainstk]'";
		} else if($param['tipessr'] == "apl") {
			$tipeSsr = "b.trtype = 'SB1' and b.ttptype LIKE 'MEMB%' and b.createnm = '$param[mainstk]'";
		} else if($param['tipessr'] == "ms") {
			$tipeSsr = "b.trtype = 'SB1' and b.ttptype = 'SUBSC' and b.batchno LIKE 'MSR%' and b.createnm = '$param[mainstk]'";
		} else if($param['tipessr'] == "stock") {
			$tipeSsr = "b.trtype = 'SB1' and b.ttptype = 'SC' and b.batchno LIKE 'SSR%' and b.createnm = '$param[mainstk]'";
		} else if($param['tipessr'] == "pvr") {
			$tipeSsr = "b.trtype = 'VP1' and b.batchno LIKE 'PVR%' and b.createnm = '$param[mainstk]'";
		} else {
			$tipeSsr = "b.trtype = 'SB1' and b.batchno LIKE 'SSSR%' and b.createnm = '$param[mainstk]'";
		}

		$tipeSsr .= " AND CONVERT(VARCHAR(10), b.batchdt ,120) BETWEEN '$param[from]' AND '$param[to]'";

		if($param['tipe'] == "stk") {
			$tipeSsr .= " AND b.sc_dfno = '$param[parValue]'";
		} else if($param['tipe'] == "ssr") {
			$tipeSsr .= " AND b.batchno = '$param[parValue]'";
		}

		if($param['break_bundling'] == "1") {

			$qry = "SELECT 
						x1.prdcd,
						x1.prdnm,
						SUM(x1.qty) as qty
					FROM 
					(  
						SELECT 
							ISNULL(x.prdcdDet, x.prdcd) AS prdcd,
							ISNULL(x.prdcdNmDet, x.prdnm) AS prdnm,
							CASE 
							WHEN x.bundle_qty IS NULL THEN x.total_qty
							ELSE x.bundle_qty * x.total_qty
							END AS qty  
						FROM 
						(    
							SELECT a.prdcd, c.prdnm, SUM(a.qtyord) as total_qty, b1.prdcdDet , 
							b1.prdcdNmDet , b1.qty as bundle_qty 
							FROM klink_mlm2010.dbo.sc_newtrd a
							LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
							LEFT OUTER JOIN klink_mlm2010.dbo.newera_PRDDET b1 ON (a.prdcd = b1.prdcdCat)
							LEFT OUTER JOIN klink_mlm2010.dbo.msprd c ON (a.prdcd = c.prdcd)
							WHERE $tipeSsr
							GROUP BY a.prdcd, c.prdnm, b1.prdcdDet , b1.prdcdNmDet, b1.qty
						) x	
					) x1
					GROUP BY x1.prdcd, x1.prdnm
					ORDER BY x1.prdnm";
		} else {
			$qry = "SELECT a.prdcd, c.prdnm, SUM(a.qtyord) as qty
					FROM klink_mlm2010.dbo.sc_newtrd a
					LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
					LEFT OUTER JOIN klink_mlm2010.dbo.msprd c ON (a.prdcd = c.prdcd)
					WHERE $tipeSsr
					GROUP BY a.prdcd, c.prdnm";
		}

		/* echo "<pre>";
		echo $qry;
		echo "</pre>"; */

		$res = $this->getRecordsetArray($qry, null, $this->db2);
		return $res;
	}

	function stkPendingApprove($from, $to, $bns) {
		$qry = "SELECT a.loccd , b.fullnm , a.batchno , SUM(a.tdp) as total_dp, SUM(a.tbv) as total_bv,
							CONVERT(VARCHAR(10), a.batchdt , 120) AS batchdt ,
							CONVERT(VARCHAR(10), a.bnsperiod , 120) as bnsperiod 
						FROM klink_mlm2010.dbo.sc_newtrh a
						LEFT OUTER JOIN klink_mlm2010.dbo.mssc b ON (a.loccd = b.loccd )
						WHERE CONVERT(VARCHAR(10), a.etdt, 120) BETWEEN '$from' AND '$to'
						AND CONVERT(VARCHAR(10), a.bnsperiod , 120) = '$bns' 
						AND (a.batchno is not null and a.batchno <> '') --and a.loccd NOT IN ('BID06')
						AND (a.csno is null OR a.csno = '')
						GROUP BY a.loccd , b.fullnm, a.batchno, 
						CONVERT(VARCHAR(10), a.batchdt , 120),
						CONVERT(VARCHAR(10), a.bnsperiod , 120)
						ORDER BY a.loccd";
		//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}

	

	
}