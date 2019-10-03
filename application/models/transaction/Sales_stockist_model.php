<?php
class Sales_stockist_model extends MY_Model {
		
	function __construct() {
        // Call the Model constructor
        parent::__construct();
	}
	
	function getListSsrByKW($data) {
		$qry = "SELECT a.batchscno, a.invoiceno, 
					a.dfno, b.fullnm as dfno_name, a.sc_dfno, 
					a.loccd, CONVERT(char(10), a.etdt,126) as cn_date, a.tdp, a.tbv, c.GDO, 
					a.receiptno, CONVERT(char(10), c.etdt,126) as gdo_dt, 
					c.createnm as gdo_createnm
				FROM ordivtrh a
				LEFT OUTER JOIN mssc b ON (a.dfno = b.loccd) 
				LEFT OUTER JOIN intrh c ON (a.receiptno = c.applyto)
				WHERE a.receiptno = '".$data['paramValue']."' and a.loccd = '".$this->stockist."'";
		return $this->getRecordset($qry, null, $this->db2);
	}
	
	function getListSalesStockist($data, $tipe) {
		$param = "";
		//if($data['flag_batch'])

		if($data['searchby'] == "trcd" || $data['searchby'] == "orderno") {
			$s = "a.".$data['searchby'];
			$param .= "$s = '$data[paramValue]' and a.flag_batch = '".$data['flag_batch']."'";
		} else if($data['searchby'] == "sc_dfno") {
			$param .= "a.sc_dfno = '$data[paramValue]' AND a.loccd = '$data[loccd]' and a.flag_batch = '".$data['flag_batch']."' 
			          and CONVERT(char(10), a.etdt,126) BETWEEN '$data[from]' AND '$data[to]'";
		} else if($data['searchby'] == "") {
			$param .= "a.loccd = '$data[loccd]' and a.flag_batch = '".$data['flag_batch']."' 
			          and CONVERT(char(10), a.etdt,126) BETWEEN '$data[from]' AND '$data[to]'";
	    } else {
			$s = "a.".$data['searchby'];
			$param .= "$s = '$data[paramValue]'";
		}
    	$qry = "SELECT 
				  a.orderno,
				  a.trcd,
				  a.pricecode,
				  CONVERT(char(10), a.etdt,126) as etdt,
				  a.bnsperiod,
				  a.dfno,
				  b.fullnm,
				  a.loccd,
				  a.sc_dfno,
				  a.sc_co,
				  a.tdp,
				  a.tbv,
				  a.entrytype,
				  a.batchno,
				  a.flag_batch, a.no_deposit, a.id_deposit,
				  CASE 
				    WHEN flag_batch = '0' THEN 'Pending'
					WHEN flag_batch = '1' THEN 'Generated'
					WHEN flag_batch = '2' THEN 'Approved'
				  end AS flag_batch_stt
				FROM  klink_mlm2010.dbo.sc_newtrh a
				   LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)  
				WHERE $param AND a.trtype = '$tipe' ORDER BY a.trcd";	
		//echo $qry;		
		return $this->getRecordset($qry, null, $this->db2);
	}	

	function getTrxByTrcdHead($param, $id) {
		$qry = "SELECT a.trcd, a.orderno, a.batchno, a.trtype, a.ttptype, a.trtype,
	                 a.etdt, a.batchdt, a.remarks, a.createdt, a.createnm, a.updatedt, a.updatenm, a.dfno, b.fullnm as distnm,
	                 a.loccd, c.fullnm as loccdnm, c.sctype as sctype,
	                 a.sc_co, c.fullnm as sc_conm, c.sctype as co_sctype,
	                 a.sc_dfno, c.fullnm as sc_dfnonm, c.sctype as loccd_sctype,
	                 a.tdp, a.tbv, a.bnsperiod, a.batchno, a.flag_batch,
	                 CONVERT(char(10), a.etdt,126) as tglinput
                FROM sc_newtrh a
	                LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON a.dfno = b.dfno
					LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON a.sc_dfno = c.loccd
					LEFT OUTER JOIN klink_mlm2010.dbo.mssc d ON a.sc_co = d.loccd
					LEFT OUTER JOIN klink_mlm2010.dbo.mssc e ON a.loccd = e.loccd
				WHERE a.$param = '$id'";
          return $this->getRecordset($qry, null, $this->db2);
	}
	
	function getDetailProduct($param, $id) {
		  $qry = "SELECT A.trcd, A.prdcd, b.prdnm, A.qtyord, A.bv, A.dp, (A.qtyord*A.bv) AS TOTBV, (A.qtyord*A.dp) AS TOTDP
				  FROM sc_newtrd A
				  LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON a.prdcd=b.prdcd
				  WHERE a.$param = '$id'";	
		  return $this->getRecordset($qry, null, $this->db2);
	}
	
	function getDetailPayment($param, $id) {
		  $qry = "SELECT a.paytype, docno, payamt, b.description
				  FROM sc_newtrp A
				  LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON a.paytype=b.id
				  WHERE a.$param = '$id'";	
		  return $this->getRecordset($qry, null, $this->db2);
	}
	
	
	
	/* function getCurrentPeriod()
    {
        $qry = "SELECT a.currperiodSCO as lastperiod, 
                DATEADD(month, 1, a.currperiodSCO) as nextperiod 
                from klink_mlm2010.dbo.syspref a"; //edit by hilal 28-06-2014
		
        $res = $this->getRecordset($qry, null, $this->db2);
		return $res;
    }  */
    
    function getStockistInfo($idstockist) {
    	$qry = "SELECT loccd, fullnm, sctype, pricecode 
		        FROM mssc WHERE loccd = '$idstockist'";
        $res = $this->getRecordset($qry, null, $this->db2);
		return $res;
    }
    
    function getListPaymentType() {
    	$qry = "SELECT id, description
    	        FROM paytype WHERE id IN ('01', '08')"; //edit by hilal 28-06-2014
		
        $res = $this->getRecordset($qry, null, $this->db2);
		return $res;
	}
	
	function getListPaymentTypeOnlyCash() {
    	$qry = "SELECT id, description
    	        FROM paytype WHERE id IN ('01')"; //edit by hilal 28-06-2014
		
        $res = $this->getRecordset($qry, null, $this->db2);
		return $res;
    }
    
    function getListPaymentProductVoucher() {
    	$qry = "SELECT id, description
    	        FROM paytype WHERE id IN ('01','10')"; //edit by hilal 28-06-2014
		
        $res = $this->getRecordset($qry, null, $this->db2);
		return $res;
    }
    
    function checkValidCashVoucher($distributorcode,$vchnoo, $vchtype) {
    	$datee = date("m/d/Y");
        $month = date("m");
        $year = date("Y");
		
		$threeDigit = substr($vchnoo, 0, 3); 
       // $vchnoo1 = substr($vchnoo,0,1);
       
        /*$qry = "SELECT a.claimstatus, 
                       a.DistributorCode, a.VoucherNo as VoucherNo,
                       a.vchtype,a.VoucherAmt,
                       MONTH(a.ExpireDate) as monthExpire,
                       year(a.ExpireDate) as yearExpire
                FROM klink_mlm2010.dbo.tcvoucher a
                WHERE a.VoucherNo = '".$vchnoo."' and a.DistributorCode = '".$distributorcode."'
                and a.claimstatus = '0' 
                and MONTH(a.ExpireDate) >= '".$month."' and year(a.ExpireDate) = '".$year."'
                and a.vchtype = 'C'";*/
		$fieldCek = "VoucherNo";
		if($vchtype == "C") {
			$fieldCek = "voucherkey";
		}	
				
		$qry = "SELECT a.claimstatus, 
				       a.DistributorCode, a.VoucherNo as VoucherNo,
				       a.vchtype,a.VoucherAmt, a.vchtype, a.loccd,
					   CONVERT(char(10), a.claim_date,126) as claim_date,
				       CONVERT(char(10), a.ExpireDate,126) as ExpireDate,
				       CONVERT(char(10), GETDATE(),126) as nowDate,
				       CASE 
				           WHEN CONVERT(char(10), GETDATE(),126) >= CONVERT(char(10), a.ExpireDate,126) THEN '1'
				           ELSE '0'
				       END AS status_expire
                FROM tcvoucher a
                WHERE a.$fieldCek = '".$vchnoo."' 
					AND a.DistributorCode = '".$distributorcode."' 
					AND a.vchtype = '$vchtype'
                ";
        //echo $qry;
		$res = $this->getRecordset($qry, null, $this->db2);

		/* $arrData = $res['arraydata']; */
		if($res == null) {
			$response = jsonFalseResponse("Voucher $vchnoo tidak valid..");
			return $response;
		}

		if($res[0]->status_expire == '1') {
			$response = jsonFalseResponse("Voucher ".$vchnoo." sudah expire pada tanggal : ".$res[0]->ExpireDate."");
			return $response;
		}

		if($res[0]->claimstatus == "1") {
			$response = array("response" => "false", "arrayData" => $res,"message" => "Voucher ".$vchnoo." sudah pernah di klaim pada ".$res[0]->claim_date.", Stockist : ".$res[0]->loccd);
			return $response;
		}
		
		$res2 = null;
		if($res != null && $threeDigit == "XPV" || $threeDigit == "ZVO" || $threeDigit == "XPP" || $threeDigit == "XHD") {
			//$detProd = "SELECT * FROM TWA_KLPromo_Oct17_D WHERE Voucherno = '$vchnoo'";
			$res2 = $this->getListProdPromo($vchnoo);
		}
		
		return array("response" => "true", "arrayData" => $res, "detProd" => $res2);
	}

	function getListProdPromo($vchnoo) {
		$detProd = "SELECT * FROM TWA_KLPromo_Oct17_D WHERE Voucherno = '$vchnoo'";
		$res2 = $this->getRecordset($detProd, null, $this->db2);
		return $res2;
	}

	function getListProdPromoByVchAndPrdcd($vchnoo, $prdcd) {
		$detProd = "SELECT * FROM TWA_KLPromo_Oct17_D WHERE Voucherno = '$vchnoo' AND prdcd = '$prdcd'";
		$res2 = $this->getRecordset($detProd, null, $this->db2);
		return $res2;
	}
	
	function checkPaymentAndProduct($arr) {
		$prdcd = $arr['prdcd'];
		$jum = $arr['jum'];
		$payChooseType = $arr['payChooseType'];
		$payReff = $arr['payReff'];
		$pricecode = $arr['pricecode'];

		$jum = count($prdcd);

		$total_harga = 0;
		for($i=0; $i < $jum; $i++) {
			$qty = $jum[$i];
			if($qty == "0" || $qty == "") {
				return jsonFalseResponse("Kode $prdcd[$i] kuantiti produk nya minimal harus 1..");
			} else {
				$qry = "SELECT a.prdcd, $qty * a.dp as total_harga
						FROM pricetab a
						WHERE a.prdcd = '".$prdcd[$i]."' AND a.pricecode = '$pricecode'";
				$res2 = $this->getRecordset($qry, null, $this->db2);

				if($res2 == null) {
					return jsonFalseResponse("Kode $prdcd[$i] tidak ada harga nya..");
				} else {
					$total_harga += $res2[0]->total_harga;
				}
			}

			
		}
		
		
	}
    
    function saveTrx($data) {
    	
				
				/*---------------------------------------------------------------
				 * PROSES PEMBAYARAN, SET values utk multiple insert ke sc_newtrp
				 * --------------------------------------------------------------*/
				$jumPay = count($data['payChooseType']);
				$totalNilaiVoucher = 0;
				$totalCash = 0;
				$totalBayar = 0;
				//set tipe2 voucher
				$cv = 0;
				$pv = 0;
				$pv_hadiah = 0;
				$pv_hydro = 0;
				//end
				$qryAddPay = "";
				$qryUpdVoucher = "";
				$j = 0;
				$no_bv = false;
				for($i=0;$i<$jumPay;$i++) {
					$j++;
					//Jika pembayaran menggunakan Voucher Cash / Voucher Produk
					if($data['payChooseType'][$i] == "08" || $data['payChooseType'][$i] == "10") {
						
						//Bila dibayar pakai Voucher Product
						//BV = 0
						if($data['payChooseType'][$i] == "10") {
							$no_bv = true;
							$pref_vch = strtoupper(substr($data['payReff'][$i], 0, 3));
							if($pref_vch == "XPV" || $pref_vch == "ZVO" || $pref_vch == "XPP") {
								$pv_hadiah++;
							} else if($pref_vch == "XHD") {
								$pv_hydro++;
							} else {
								$pv++;
							}
							
							
						} else {
							$cv++;							
						}
						
						//$payChooseValue = intval(str_replace('.', '', $data['payChooseValue'][$i]));
						$payChooseValue = $data['payChooseValue'][$i];
						$data['payChooseValue'][$i] = $payChooseValue;
						$totalNilaiVoucher += $payChooseValue;
						$totalBayar += $payChooseValue;
						$qryAddPay .= "('DION_TRCD', ".$j.",'".$data['payChooseType'][$i]."','".$data['payReff'][$i]."',".$data['payChooseValue'][$i].",0,'','0','ID','1'), ";
							
						$qryUpdVoucher .= "'".$data['payReff'][$i]."', ";
						
						//trcd,seqno,paytype,docno,payamt,deposit,notes,trcd2,PT_SVRID,voucher
						
					} else {
						//$cash = intval(str_replace('.', '', $data['payChooseValue'][$i]));
						$cash = $data['payChooseValue'][$i];
						$payChooseValue = $data['payChooseValue'][$i];
						$totalCash += $cash;
						$data['payChooseValue'][$i] = $cash;
						$totalBayar += $cash;
					}
					
				}
				//END
				/*---------------------------------------------------------------
				 * DETAIL PRODUK, SET values utk multiple insert ke sc_newtrd
				 * --------------------------------------------------------------*/
				$jum = count($data['prdcd']);
				$totBV = 0;
				$totDP = 0;
				$qryAddProduct = "";
				for($i=0;$i<$jum;$i++) {
					/* $dp = intval(str_replace('.', '', $data['harga'][$i]));
					$bv = intval(str_replace('.', '', $data['poin'][$i])); */
					$dp = $data['harga'][$i];
					$bv = $data['poin'][$i];
					$totBV += $data['jum'][$i] * $bv;
					$totDP += $data['jum'][$i] * $dp;
					if($no_bv == true) {
						$data['poin'][$i] = 0;
						$data['sub_tot_bv'][$i] = 0;
					} else {
						$data['poin'][$i] = $bv;
						$data['sub_tot_bv'][$i] = $totBV;
					}
					$data['harga'][$i] = $dp;
					$data['sub_tot_dp'][$i] = $totDP;
					$qryAddProduct .= "('DION_TRCD','".$data['prdcd'][$i]."', ".$data['jum'][$i].", 0, 0, ".$data['harga'][$i].", ".$data['poin'][$i].", ".$data['poin'][$i].", ";
					$qryAddProduct .= "0,0,0,0,0,0,'0',0,0,'ID','".$data['pricecode']."'), ";
				}	
				$qryAddProduct = substr($qryAddProduct, 0, -2);
				//END
				/*----------------------------------------
				 * Pembayaran campuran antara cash dan voucher
				 * nilai cash yang diinput dihitung setelah
				 * menghitung total nilai voucher
				 * ---------------------------------------*/
				$change = ($totalCash + $totalNilaiVoucher) - $totDP;
				$sisaCash = $totalCash - $change;
				if($sisaCash > 0) {
					$qryAddPay .= "('DION_TRCD', ".$j.",'01','',".$sisaCash.",0,'','0','ID','0'), ";
				}	
				$qryAddPay = substr($qryAddPay, 0, -2);
				$qryUpdVoucher = substr($qryUpdVoucher, 0, -2);
				//END
				if($totDP > $totalBayar) {
					$return = jsonFalseResponse("Pembayaran kurang, total harga produk : $totDP, total pembayaran : $totalBayar");
				} else {
					//$return = jsonTrueResponse($data, "ok");
					$jenis = "id";
					if($cv > 0) {
						$jenis = "cv";
					} else if($pv > 0) {
						$jenis = "pv";
					} else if($pv_hadiah > 0) {
						$jenis = "pv_hadiah";	
					} else if($pv_hydro > 0) {
						$jenis = "pv_hydro";
					}
					//$res['cek_seQ'] = $this->cek_seQ($jenis);
                    //$data['idnoo'] = $this->get_idno($jenis);
                    
                    $qryRes = array(
						"jenis" => $jenis,
						"qryProduct" => $qryAddProduct,
						"qryPayment" => $qryAddPay,
						"updVoucher" => $qryUpdVoucher,
						"data" => $data,
						"totDP" => $totDP,
						"totBV" => $totBV
					);
                    
					
						$return = $this->insertTrxStockist($qryRes);
						return $return;
					
                  	
			   }
			
    	
    	return $return;
    }
    
    function insertTrxStockist($arrQuery) {
    		$trcd = "";
    		$db_qryx = $this->load->database('klink_mlm2010', true);
    		//$db_qryx = $this->load->database('klink_mlm2010', true);
			$db_qryx->trans_begin();
			$datax = $arrQuery['data'];
  			if($datax['ins'] == "1") {
  				$cek_seQ = $this->cek_seQ($arrQuery['jenis']);
                $trcd = $this->get_idno($arrQuery['jenis']);
                //$trcd = "PV09090";
  			} else {
  				$trcd = $datax['trcd'];
				
				//delete sc_newtrh
				$del_1 = "DELETE FROM sc_newtrh WHERE trcd = '$trcd'";
				$exe_del1 = $db_qryx->query($del_1);
				
				//delete sc_newtrd
				$del_2 = "DELETE FROM sc_newtrd WHERE trcd = '$trcd'";
				$exe_del2 = $db_qryx->query($del_2);
				
				//delete sc_newtrp
				$del_3 = "DELETE FROM sc_newtrp WHERE trcd = '$trcd'";
				$exe_del3 = $db_qryx->query($del_3);
  			}
			
			$pref_trcd = substr($trcd, 0, 2);
    		//$cek_seQ = $this->cek_seQ($arrQuery['jenis']);
            //$trcd = $this->get_idno($arrQuery['jenis']);
            
			
			
			//INSERT PRODUCT
            $prd = str_replace('DION_TRCD', $trcd, $arrQuery['qryProduct']);
            $insDet = "insert into sc_newtrd 
                        (trcd,prdcd,qtyord,qtyship,qtyremain,dp,pv,bv,taxrate,sp,sb,scdisc,seqno,scdiscamt,syn2web,qty_used,qty_avail,
                        PT_SVRID,pricecode) VALUES $prd";
            //echo $insDet;
			$query1 = $db_qryx->query($insDet);
            
            
            
			//INSERT PAYMENT
			$pyment = str_replace('DION_TRCD', $trcd, $arrQuery['qryPayment']);
			$insDetTrf = "insert into sc_newtrp (trcd,seqno,paytype,docno,payamt,deposit,notes,trcd2,PT_SVRID,voucher) 
                        VALUES $pyment";
			//echo $insDetTrf;
			$query2 = $db_qryx->query($insDetTrf);
			
			if($arrQuery['updVoucher'] != "") {
				//UPDATE VOUCHER
				$updVc = "UPDATE tcvoucher SET claimstatus = '1', 
							updatenm = '".$this->stockist."', 
							updatedt = '$this->dateTime' 
				          WHERE VoucherNo IN ($arrQuery[updVoucher])";
				//echo $updVc;
				$query2 = $db_qryx->query($updVc); 
			}
			//INSERT HEADER
			 $folderGets = explode('/', $datax['bnsperiod']);
	        $x['month'] = $folderGets[0];
	        $x['year'] = $folderGets[1];
	        $bonusperiod = $x['month']."/"."1"."/".$x['year'];
			$stockistid = $this->session->userdata('stockist');
			$sctypee = $this->session->userdata('group_scoapp');
			$createdt = date('Y-m-d H:i:s');
			$trdt = date('Y-m-d');
			
			$ttptype = "SC";
			//SUBSC = untuk sub / mobile stockist, SC = untuk stockist
			if($datax['sctype'] == "3" || $datax['sctype'] == "2") {
				$ttptype = "SUBSC";
			} 
			
			//set trtype VP1 jika pembayaran non BV menggunakan Voucher Product
			
			if($pref_trcd == "PV") {
				$trxtype = "VP1";
				$totalBV = 0;
			} else {
				$trxtype = "SB1";
				$totalBV = $arrQuery['totBV'];
			}
			
			$insHead = "insert into sc_newtrh 
                (trcd,trtype,trdt,dfno,loccd,tdp,taxrate,taxamt,discamt,shcharge,
                othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                orderno,type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                sc_co,bnsperiod,remarks,othdisc,flag_batch,batchstatus,flag_recover,
                system,ttptype,entrytype,flag_show,flag_approval,id_deposit, no_deposit)
                values
                ('".$trcd."','$trxtype','".$trdt."','".$datax['dfno']."',
                '".$datax['loccd']."',".$arrQuery['totDP'].",10,0,0,0,0,".$totalBV.",".$totalBV.",
                ".$totalBV.",".$totalBV.",".$arrQuery['totDP'].",'WH001','B001','".$datax['pricecode']."',
                '01','01','03',".$arrQuery['totDP'].",0,0,".$arrQuery['totDP'].",'".$stockistid."','".$stockistid."',
                '0',0,0,0,0,'0','".$createdt."','".$datax['orderno']."','0',0,0,'".$datax['sctype']."','1','0','0','0',
                0,'0','0','0','1','ID','".$datax['sc_dfno']."',
                '".$datax['sc_co']."','".$bonusperiod."','".$datax['remarks']."',
            	0,'0','0','0','0','$ttptype',4,'0','0', '".$datax['id_deposit']."', '".$datax['no_deposit']."')";
            //echo $insHead;
            $query3 = $db_qryx->query($insHead);
            
            if ($db_qryx->trans_status() === FALSE) {
                    $db_qryx->trans_rollback();
			        $return = array("response" => "false", "message" => "Data sales gagal disimpan..");
					return $return; 
            } else {
                    $db_qryx->trans_commit();
			        
			        $arrx = array(
			 			"trcd" => $trcd, 
			 			"orderno" => $datax['orderno'],
			 			"dfno" => $datax['dfno'], 
			 			"fullnm" => $datax['fullnm'], 
			 			"totalDP" => $arrQuery['totDP'], 
			 			"totalBV" => $totalBV,
			 			"pref_trcd" => $pref_trcd,
			 			"ins" => $datax['ins']
			    	); 
			        $return = array("response" => "true", "message" => "Data sales berhasil disimpan..", "data" => $arrx);
					return $return; 
            }
    }
    
    function cek_seQ($tipe_pay) // dipake
    {
        $this->db = $this->load->database('klink_mlm2010', true);
        $y1=date("y");
        $m=date("m");
        
        //$this->db->trans_begin();
        
        //if(in_array('p',$tipe_pay))
        if($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $tbl = "SEQ_PV"."$y1"."$m";
        } elseif($tipe_pay == 'cv') {
        	$tbl = "SEQ_CV"."$y1"."$m";
        } else if($tipe_pay == 'pv_hydro') {
            $tbl = "SEQ_ID"."$y1"."$m";
        } else {
			$tbl = "SEQ_ID"."$y1"."$m";
		}
 
        $cek = "select * from $tbl";
        
        $query = $this->db->query($cek);
        if($query->num_rows < 1)
        {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        }
        else
        {
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

	function get_idno($tipe_pay) // dipake
    {
        $this->db = $this->load->database('klink_mlm2010', true);
        $y1=date("y");
        $m=date("m");
        
        //$this->db->trans_begin();
        
        //if(in_array('p',$tipe_pay))
        if($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $tbl = "SEQ_PV"."$y1"."$m";
        } elseif($tipe_pay == 'cv'){
        	$tbl = "SEQ_CV"."$y1"."$m";
        } else if($tipe_pay == 'pv_hydro') {
            $tbl = "SEQ_ID"."$y1"."$m";
        } else {
			$tbl = "SEQ_ID"."$y1"."$m";
		}
 
        $qry = "SELECT * FROM $tbl 
           		 WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";
        
        $query = $this->db->query($qry);
        if($query == null)
        {
            $ss = 0;
        }
        else
        {
            foreach($query->result() as $data)
            {
                $ss = $data->SeqID;
            }  
        }
         $jumlah = $query->num_rows();
         
       	$next_seq = sprintf("%06s",$ss);
        $prefix = date('ym');
        
        if($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $y =  strval("PV".$prefix.$next_seq);
		} elseif($tipe_pay == 'cv'){
			$y =  strval("CV".$prefix.$next_seq);
		} else if($tipe_pay == 'pv_hydro') {
            $y =  strval("IDH".$prefix.$next_seq);
        } else{
            $y =  strval("ID".$prefix.$next_seq);
        } 
       
        return $y;
	}
	
	function deleteTrx($trcd) {
		$db_qryx = $this->load->database('klink_mlm2010', true);	

		$db_qryx->trans_begin();

		$qry = "SELECT a.trcd, a.paytype, a.docno, a.vchtype, b.dfno 
				FROM klink_mlm2010.dbo.sc_newtrp a
				LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
				WHERE a.trcd = '$trcd' and a.paytype != '01'";
		$listVch = $this->getRecordset($qry, null, $this->db2);

		//prosedur untuk mengaktifkan voucher cash/produk yang sudah claim/input agar bs diinput ulang
		if($listVch != null) {
			foreach($listVch as $dta) {
				//paytype = 08 (vch cash/deposit/umroh)
				//paytype = 10 (vch produk, P, ZVO, XPP, XPV, XHD)
				if($dta->paytype == "08") {
					$upd = "UPDATE klink_mlm2010.dbo.tcvoucher 
								SET claimstatus = '0', claim_date = '', loccd = ''
							WHERE DistributorCode = '$dta->dfno' and VoucherNo = '$dta->docno'";
				} else if($dta->paytype == "10") {
					$upd = "UPDATE klink_mlm2010.dbo.tcvoucher 
								SET claimstatus = '0', claim_date = '', loccd = ''
							WHERE DistributorCode = '$dta->dfno' and voucherkey = '$dta->docno'";
				}
				/* echo $upd;
				echo "<br />"; */
				$db_qryx->query($upd);
			}
		}

		$trh = "DELETE FROM klink_mlm2010.dbo.sc_newtrh WHERE trcd = '$trcd'";
		$trd = "DELETE FROM klink_mlm2010.dbo.sc_newtrd WHERE trcd = '$trcd'";
		$trp = "DELETE FROM klink_mlm2010.dbo.sc_newtrp WHERE trcd = '$trcd'";

		/* echo $trh;
		echo "<br />";
		echo $trd;
		echo "<br />";
		echo $trp;
		echo "<br />"; */

		$db_qryx->query($trh);
		$db_qryx->query($trd);
		$db_qryx->query($trp); 

		if ($db_qryx->trans_status() === FALSE) {
			$db_qryx->trans_rollback();
			$return = array("response" => "false", "message" => "Data transaksi $trcd gagal dihapus..");
			return $return; 
		} else {
			$db_qryx->trans_commit();
			$return = array("response" => "true", "message" => "Data transaksi $trcd berhasil dihapus..");
			return $return;  
		}
	}

	function showProductPriceForPvr($productcode, $pricecode) {

		$qry = "SELECT  b.prdcd,b.prdnm, b.webstatus, b.scstatus, b.status, 
					c.bv,c.dp, d.cat_inv_id_parent as bundling, b1.pvr_exclude_status
				from klink_mlm2010.dbo.pricetab c
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd b 
					on c.prdcd=b.prdcd
				LEFT OUTER JOIN klink_mlm2010.dbo.product_exclude_sales b1 
					on (b.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS = b1.prdcd)
				LEFT OUTER JOIN db_ecommerce.dbo.master_prd_bundling d 
					ON (b.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS = d.cat_inv_id_parent)
				where c.pricecode='$pricecode' and
					  c.prdcd='$productcode'";
		//return $this->get_data_json_result($qry);
		$hasil = $this->getRecordset($qry, null, $this->db2);
		if($hasil == null) {
			$arr = array("response" => "false", "message" => "Kode produk salah");
			return $arr;
		} 

		//jika produk adalah, tipe product exclude yang tidak boleh diinput dalam pembelanjaan PVR
		$produkname = $hasil[0]->prdnm;
		if($hasil[0]->pvr_exclude_status == "1") {
			$arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput dalam pembelanjaan PVR");
			return $arr;
		}

		//jika produk adalah produk free / yang harga nya 0
		$harga = (int) $hasil[0]->dp;
		if($harga == 0) {
			$arr = array("response" => "false", "message" => "Produk $productcode / $produkname adalah kode produk FREE");
			return $arr;
		}
	
		//jika produk adalah produk bundling
		if($hasil[0]->bundling !== null) {
			$arr = array("response" => "false", "message" => "Produk $productcode / $produkname adalah produk bundling/paket");
			return $arr;
		}

		//jika produk status nya sudah 
		if($hasil[0]->scstatus !== "1" || $hasil[0]->webstatus !== "1" || $hasil[0]->status !== "1") {
			$arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput untuk stokis..");
			return $arr;
		}
	
		$arr = array("response" => "true", "arraydata" => $hasil);
		return $arr;
	
	}

	function showProductPrice($productcode, $pricecode) {
		$qry = "SELECT  b.prdcd,b.prdnm, b.webstatus, b.scstatus, b.status, 
		          c.bv,c.dp
				from klink_mlm2010.dbo.pricetab c
				LEFT JOIN klink_mlm2010.dbo.msprd b 
				  on c.prdcd=b.prdcd
				where c.pricecode='$pricecode' and
					  c.prdcd='$productcode'";
		//return $this->get_data_json_result($qry);
		$hasil = $this->getRecordset($qry, null, $this->db2);
		if($hasil == null) {
			$arr = array("response" => "false", "message" => "Kode produk salah");
			return $arr;
		} 

		if($hasil[0]->scstatus !== "1" || $hasil[0]->webstatus !== "1" || $hasil[0]->status !== "1") {
			$arr = array("response" => "false", "message" => "Kode produk $productcode tidak dapat diinput untuk stokis..");
			return $arr;
		}
	
		$arr = array("response" => "true", "arraydata" => $hasil);
		return $arr;
	
	}

	function cekHeaderTrx($field, $value) {
		$qry = "SELECT a.trcd, a.trtype,a.batchno, a.csno, a.tdp, a.pay1amt,
		          a.tpv, a.tbv, a.nbv, a.pricecode, a.ndp, a.totpay, 
		           SUM(b.qtyord * c.dp) as total_dp,
				   CASE 
				   WHEN a.trtype = 'VP1' THEN 0 ELSE
				      SUM(b.qtyord * c.bv) 
				   END as total_bv
		        FROM klink_mlm2010.dbo.sc_newtrh a 
				LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd b ON (a.trcd = b.trcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (b.prdcd = c.prdcd AND a.pricecode = c.pricecode)
				WHERE a.$field = '$value'
				GROUP BY a.trcd, a.trtype, a.batchno, a.csno, a.tdp, a.pay1amt, a.tpv, 
				  a.tbv, a.nbv, a.pricecode, a.ndp, a.totpay";
		//echo $qry;
		$hasil = $this->getRecordset($qry, null, $this->db2);
		$hasil2 = null;
		if($hasil != null) {
			$pref = substr($hasil[0]->trcd, 0, 2);
			$pref1 = substr($hasil[0]->trcd, 0, 3);
			if($pref == "PV" || $pref = "CV" || $pref1 == "IDH") {
				$pay = "SELECT ISNULL(SUM(a.payamt), 0) as total_bayar 
					FROM sc_newtrp a WHERE a.trcd = '$value'";
				$hasil2 = $this->getRecordset($pay, null, $this->db2);
			} else {
				$totdp = $hasil[0]->tdp;
				$pay = "SELECT ISNULL(SUM(a.payamt), $totdp) as total_bayar 
					FROM sc_newtrp a WHERE a.trcd = '$value'";
				$hasil2 = $this->getRecordset($pay, null, $this->db2);
			}
			
		}
		
		
		return $arr = array(
			"header" => $hasil,
			"payment" => $hasil2
		);

	}

	function koreksiTransaksi($arr) {
		$db_qryx = $this->load->database('klink_mlm2010', true);

		$array = $arr['header'];
		$trcd = $array[0]->trcd;
		$total_dp_prd = $array[0]->total_dp;
		$total_bv_prd = $array[0]->total_bv;

		$db_qryx->trans_begin();

		if($array[0]->ndp != $total_dp_prd || $array[0]->tdp != $total_dp_prd || 
		  $array[0]->totpay != $total_dp_prd 
		  || $array[0]->tbv != $array[0]->total_bv || $array[0]->tpv != $total_bv_prd
		  || $array[0]->nbv != $total_bv_prd) {
			$qry = "UPDATE sc_newtrh SET tdp = '$total_dp_prd', ndp = '$total_dp_prd', totpay = '$total_dp_prd'
					, pay1amt = '$total_dp_prd', tbv = '$total_bv_prd', tpv = '$total_bv_prd', nbv = '$total_bv_prd'
					WHERE trcd = '$trcd'";
			$db_qryx->query($qry);
			//echo $qry;
		}

		$payment = $arr['payment'][0];
		$nilai_vch = 0;
		if($payment->total_bayar != $array[0]->total_dp) {
			$trp = "SELECT LEFT(a.docno, 2) as tp_docno, a.docno, 
						a1.dfno, a.paytype, a.payamt, 
						CASE 
						 WHEN a.paytype = '10' THEN b.VoucherAmt 
						 WHEN a.paytype = '08' THEN c.VoucherAmt 
						ELSE 0
						END AS nilai_vch 
					FROM sc_newtrp a 
					INNER JOIN sc_newtrh a1 ON (a.trcd = a1.trcd)
					LEFT OUTER JOIN tcvoucher b ON (a.docno = b.VoucherNo AND a1.dfno = b.DistributorCode AND a.paytype = '10')
					LEFT OUTER JOIN tcvoucher c ON (a.docno = c.voucherkey AND a1.dfno = c.DistributorCode AND a.paytype = '08')
					WHERE a.trcd = '$trcd' and a.paytype != '01'
					ORDER BY a.paytype DESC";
			$res = $db_qryx->query($trp);
			$res2 = $res->result();
			if($res2 != null) {
				foreach($res2 as $dtax) {
					if($dtax->tp_docno != "VC" && $dtax->payamt != $dtax->nilai_vch && ($dtax->nilai_vch != null || $dtax->nilai_vch != "")) {
						$upd = "UPDATE sc_newtrp SET payamt = '$dtax->nilai_vch' WHERE trcd = '$trcd' AND docno = '$dtax->docno'";
						$db_qryx->query($upd);
						//echo $upd;
					} 
					$nilai_vch += $dtax->nilai_vch;
				}
				
				$sisa_cash = $total_dp_prd - $nilai_vch;
				$updCash = "UPDATE sc_newtrp SET payamt = '$sisa_cash' WHERE trcd = '$trcd' AND paytype = '01'";
				//echo $updCash;
				$db_qryx->query($updCash);
				
			}

			if ($db_qryx->trans_status() === FALSE) {
				$db_qryx->trans_rollback();
				$return = array("response" => "false", "message" => "Data transaksi $trcd gagal dikoreksi..");
				return $return; 
			} else {
				$db_qryx->trans_commit();
				$return = array("response" => "true", "message" => "Data transaksi $trcd berhasil dikoreksi..");
				return $return;  
			}
			
		}
	}
}