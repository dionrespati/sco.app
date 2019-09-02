<?php
class Sales_stockist_report_model extends MY_Model {
		
	function __construct() {
        // Call the Model constructor
        parent::__construct();
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
        		WHERE a.loccd = '$x[sc_dfno]' 
        		AND a.sctype = '".$x['sctype']."' 
        		AND a.bnsperiod='$bonusperiod'
        		AND a.flag_batch <> '0' 
        		AND (a.batchno <> '' OR a.batchno is not null)
        		ORDER BY a.batchdt desc";
       //echo $slc;
       return $this->getRecordset($slc, null, $this->db2);
    }
	
	function getHeaderSsr($field, $value) {
		$qry = "SELECT top 1 a.trcd, a.orderno, a.batchno,
						a.sc_dfno, b.fullnm as sc_dfno_name,
						a.sc_co, c.fullnm as sc_co_name,
						a.loccd, d.fullnm as loccd_name,
						a.createnm,
						CONVERT(char(10), a.etdt,126) as etdt,
						CONVERT(char(10), a.batchdt,126) as batchdt,
						CONVERT(char(10), a.bnsperiod,126) as bnsperiod,
						e.trcd as cn_no,
						CONVERT(char(10), e.createdt,126) as cn_dt,
						e.createnm as cn_createnm,
						f.trcd as kw_no,
						CONVERT(char(10), f.createdt,126) as kw_dt,
						f.createnm as kw_createnm,
						g.GDO as do_no,
						CONVERT(char(10), g.createdt,126) as do_dt,
						g.createnm as do_createnm,
						a.dfno, a1.fullnm as distnm, a.tdp, a.tbv
				FROM sc_newtrh a
				LEFT OUTER JOIN msmemb a1 ON (a.dfno = a1.dfno)
				LEFT OUTER JOIN mssc b ON (a.sc_dfno = b.loccd)
				LEFT OUTER JOIN mssc c ON (a.sc_co = c.loccd)
				LEFT OUTER JOIN mssc d ON (a.loccd = d.loccd)
				LEFT OUTER JOIN ordivtrh e ON (a.batchno = e.batchscno)
				LEFT OUTER JOIN billivhdr f ON (e.receiptno = f.trcd)
				LEFT OUTER JOIN intrh g ON (e.receiptno = g.applyto)
				WHERE a.$field = '$value'";
		return $this->getRecordset($qry, null, $this->db2);
	}
	
	function getListSummaryTtp($field, $value) {
		$slc = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm, a.tdp, a.tbv
				FROM sc_newtrh a
				LEFT OUTER JOIN msmemb b ON (a.dfno = b.dfno)
				WHERE a.$field = '$value'";
       //echo $slc;
       return $this->getRecordset($slc, null, $this->db2);
	}
	
	function getListSummaryProduct($field, $value) {
		$qry = "SELECT a.prdcd, b.prdnm, SUM(a.qtyord) as qtyord
				FROM sc_newtrd a
				INNER JOIN sc_newtrh c ON (a.trcd = c.trcd)
				LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
				WHERE c.$field = '$value'
				GROUP BY a.prdcd, b.prdnm
				ORDER BY a.prdcd, b.prdnm";
		return $this->getRecordset($qry, null, $this->db2);
	}

	function getListProductByTrcd($field, $value) {
		$qry = "SELECT a.prdcd, b.prdnm, a.qtyord, c.dp, c.bv
				FROM sc_newtrd a
				INNER JOIN sc_newtrh a1 ON (a.trcd = a1.trcd)
				LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
				LEFT OUTER JOIN pricetab c ON (a.prdcd = c.prdcd AND a1.pricecode = c.pricecode)
				WHERE a.$field = '$value'
				ORDER BY a.prdcd, b.prdnm";
		return $this->getRecordset($qry, null, $this->db2);
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
				WHERE a.$field = '".$value."'";	
		//echo $qry;		
		return $this->getRecordset($qry, null, $this->db2);
	}

	function getVoucherUmrohReport($idmember, $novoucher, $tipe) {
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
					a.VoucherNo, a.voucherkey, a.loccd
				FROM klink_mlm2010.dbo.tcvoucher a
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.DistributorCode = b.dfno)
				WHERE (a.VoucherNo = '$novoucher' OR a.voucherkey = '$novoucher') 
				AND a.DistributorCode = '$idmember'";
			//echo $qry;
		$headerVch = $this->getRecordset($qry, null, $this->db2);
		if($headerVch != null) {
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;
			$vch_deposit = 2;

			$qryTrx = "SELECT a.trcd, b.dfno, CONVERT(VARCHAR(10),b.etdt, 120) as createdt, b.loccd 
						FROM sc_newtrp a
						LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd) 
						WHERE a.docno = '".$headerVch[0]->VoucherNo."' OR a.docno = '".$headerVch[0]->voucherkey."'
						ORDER BY a.trcd DESC";
			$trxClaim = $this->getRecordset($qryTrx, null, $this->db2);
			$arr['trx'] = $trxClaim;
			$arr['deposit_vch'] = $vch_deposit;
		} else {
			$arr['response'] = "false";
			$arr['message'] = "Data voucher tidak ditemukan";
		}
		return $arr;
	}

	function getVoucherReportListV2($idmember, $novoucher, $tipe) {
		if($tipe == "vc_prd" || $tipe == "vc_prm") {
			$vc = "VoucherNo";
			$vcas = "a.VoucherNo as voucherno, ";
		} else if($tipe == "vc_c") {
			$vc = "voucherkey";
			$vcas = "a.voucherkey as voucherno, ";
		}

		
		$qry = "SELECT a.DistributorCode, b.fullnm, $vcas a.vchtype,
					CONVERT(VARCHAR(10),a.ExpireDate, 120) as ExpireDate,
					CONVERT(VARCHAR(10),a.claim_date, 120) as claim_date,
					a.claimstatus,
					CASE WHEN a.claimstatus = '0' THEN 'Belum diklaim' 
					WHEN a.claimstatus = '1' THEN 'Sudah diklaim' 
					END as status_claim,
					a.VoucherAmt as nilaivoucher, a.loccd,
					CASE WHEN CONVERT(VARCHAR(10),a.ExpireDate, 111) > GETDATE() THEN 0 else 1
					end as status_exp
				FROM klink_mlm2010.dbo.tcvoucher a
				LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.DistributorCode = b.dfno)
				WHERE a.$vc = '$novoucher' AND a.DistributorCode = '$idmember'";
				
		//echo $qry;
		$headerVch = $this->getRecordset($qry, null, $this->db2);
		if($headerVch != null) {
			$arr['response'] = "true";
			$arr['voucher'] = $headerVch;
			$vch_deposit = 0;
			$qryTrx = "SELECT a.trcd, b.dfno, CONVERT(VARCHAR(10),b.etdt, 120) as createdt, b.loccd 
						FROM sc_newtrp a
						LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd) 
						WHERE a.docno = '$novoucher'
						ORDER BY a.trcd DESC";
			$trxClaim = $this->getRecordset($qryTrx, null, $this->db2);
			if($trxClaim == null) {
				$qryTrx2 = "SELECT a.voucher_scan, a.createnm as loccd, 
							a.no_trx as trcd, a.dfno, 
							CONVERT(VARCHAR(10),a.createdt , 120) as createdt
							FROM deposit_D a WHERE a.voucher_scan = '$novoucher'";
				$trxClaim2 = $this->getRecordset($qryTrx2, null, $this->db2);

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
		} else {
			$arr['response'] = "false";
			$arr['message'] = "Data voucher tidak ditemukan";
		}
		return $arr;
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
        $froms = date('Y-m-d', strtotime($from));
        $tos = date('Y-m-d', strtotime($to));
        $usertype="";

        if($searchBy == "ALL") {
            $usertype="";
        }
        else {
            $usertype = "and a.batchno like '".$searchBy."%' ";
        }

        if($status == "ALL"){
            $usertype2="";
        }
        else {
            $usertype2 = "and A.flag_batch like '".$status."%' ";
		}
		
		$kodestk = "";
		if($idstkk != "" && $idstkk != null) {
			$kodestk = "and a.sc_dfno = '$idstkk' ";
		}

        $slc="SELECT SUM(TOTDP) as TOTDP, SUM(cash) as cash, 
					SUM(vcash) as vcash, sc_dfno, fullnm_DFNO, 
					sc_co, fullnm_CO, loccd, fullnm_LOCCS, createnm, batchno, batchdt, x_status, 
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
							sc_co,
							fullnm_CO,
							loccd,
							fullnm_LOCCS,
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
									sui.sc_co,
									fullnm_CO,
									sui.loccd,
									fullnm_LOCCS,
									sui.createnm,
									batchno,
									batchdt,
									x_status,
									(tbv) as TOTBV
								from
								(
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
										A.sc_co,
										B2.fullnm AS fullnm_CO,
										A.loccd,
										B3.fullnm AS fullnm_LOCCS,
										A.createnm,
										A.batchno,
										CONVERT(VARCHAR(10),A.batchdt, 120) as batchdt,
										A.tbv
									FROM sc_newtrh A
										LEFT JOIN sc_newtrp C ON a.trcd=C.trcd
										INNER JOIN mssc B1 ON A.sc_dfno=B1.loccd
										INNER JOIN mssc B2 ON A.sc_co=B2.loccd
										INNER JOIN mssc B3 ON A.loccd=B3.loccd
										LEFT OUTER JOIN ordivtrh D1 on (A.batchno = D1.batchscno)
										LEFT OUTER JOIN intrh D2 ON (D1.receiptno = D2.applyto)
								WHERE A.createnm ='$username'
								AND A.batchdt BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
								$usertype
								$usertype2
								$kodestk
								) sui
								GROUP BY sui.trcd, sui.tdp,
									sui.sc_dfno,
									fullnm_DFNO,
									sui.sc_co,
									fullnm_CO,
									sui.loccd,
									fullnm_LOCCS,
									sui.createnm,
									sui.batchno,
									sui.batchdt,
									x_status ,
									tbv
							) ey
				GROUP BY ey.trcd,ey.TOTDP, sc_dfno, ey.TOTbv,
					fullnm_DFNO,
					sc_co,
					fullnm_CO,
					loccd,
					fullnm_LOCCS,
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
					a.sc_co,
					a.fullnm_CO,
					a.loccd,
					a.fullnm_LOCCS,
					a.createnm,
					a.batchno,
					a.batchdt,
					a.x_status,
					a.TOTBV,
					b.trcd2
			) final
			GROUP BY
			sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd, 
			fullnm_LOCCS, createnm, batchno, batchdt, x_status,  trcd2";
        //echo $slc;
		//return $this->get_recordset($slc, $type, "alternate");
		return $this->getRecordset($slc, null, $this->db2);
	}
	
	function listTtpById($field, $value) {

		$qry = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm,
				   a.totpay, a.tbv, 
				   CONVERT(VARCHAR(10),a.etdt, 120) as etdt,
				   CONVERT(VARCHAR(10),a.bnsperiod, 120) as bnsperiod, 
				   a.no_deposit, a.batchno,
				   CONVERT(VARCHAR(10), a.batchdt, 120) as batchdt,
				   D2.GDO, 
    			   CONVERT(VARCHAR(10), D2.etdt, 120) AS GDOdt, 
				   D2.createnm as GDO_createnm,
				   CASE WHEN a1.trcd is null THEN 'PENDING' ELSE 'OK' END AS status_bv
				 FROM sc_newtrh a
				 LEFT OUTER JOIN newtrh a1 ON (a.trcd = a1.trcd)
				 INNER JOIN msmemb b ON (a.dfno = b.dfno)
				 LEFT OUTER JOIN ordivtrh D1 on (A.batchno = D1.batchscno)
				 LEFT OUTER JOIN intrh D2 ON (D1.receiptno = D2.applyto)
				 WHERE a.$field = '$value'
				 ORDER BY trcd ASC";
		//echo $qry;
		$hasil1 = $this->getRecordset($qry, null, $this->db2);
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
		        WHERE $field = '$value'";
		$res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}
}