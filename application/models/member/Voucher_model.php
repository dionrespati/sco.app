<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Voucher_model extends MY_Model {
	public function __construct() {
	    parent::__construct();
    }

    function _get_data_json_result1($qry, $db = 'klink_mlm2010')
    {
        $this->db = $this->load->database($db, true);
        $query = $this->db->query($qry);

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $res)
            {
                //$arr = array("response" => "true", "distributorname" => $res->fullnm);
                $nilai[] = $res;
            }
            $arr = array("response" => "true", "arraydata" => $nilai);
        }
        else
        {
            $arr = array("response" => "false");
        }

	   return $arr;
    }

	function getListVcrByParam($isdate, $param1, $param2, $param3, $searchby){
		$join = null;

		if($isdate == 0) {
			if($searchby == "trcd"){
				$param = " a.invoiceno = ? ";
			}elseif($searchby == "receiptno"){
				$param = " b.trcd = ? ";
			}elseif($searchby == "vcno"){
				$param = "S.formno = ? ";
				$join = " INNER JOIN klink_mlm2010.dbo.starterkit S ON A.invoiceno=S.sold_trcd ";
			}
			$paramQry = array($param1);
		} elseif($isdate == 1) {
			$param = " b.createdt BETWEEN ? AND ? ";
			$paramQry = array($param2, $param3);
		}
		$qry = "SELECT a.category, a.ordtype, a.invoiceno, a.invoicedt, a.dfno, c.fullnm,
					   b.trcd, b.createdt, b.applyto
				FROM klink_mlm2010.dbo.ordivtrh a
					 INNER JOIN klink_mlm2010.dbo.billivhdr b on a.receiptno=b.trcd
				     INNER JOIN klink_mlm2010.dbo.mssc c ON a.dfno=c.loccd
				     $join
				WHERE a.ordtype = '5' AND $param
				ORDER BY a.invoiceno";
		//echo $qry;

		$result = $this->getRecordset($qry,$paramQry,$this->db1);

		//var_dump($result);
		return $result;
	}

	function getListProductForSK($orderno) {
		$qry = "SELECT b.invoiceno, b.prdcd, c.prdnm, c.kit,
					b.pricecode, b.dp, b.bv, b.qtyord,
				    COUNT(d.formno) as jml_active,
				    b.qtyord - COUNT(d.formno) as sisa_qty
				FROM klink_mlm2010.dbo.ordivtrd b
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd c
					ON (b.prdcd = c.prdcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.starterkit d
					ON (b.invoiceno = d.sold_trcd) AND d.prdcd = b.prdcd
				WHERE b.invoiceno = ? AND c.kit = '1'
				GROUP BY b.invoiceno, b.prdcd, c.prdnm,
					b.pricecode, b.dp, b.bv, b.qtyord, c.kit
				";
		$param = array($orderno);
	    $result = $this->getRecordset($qry, $param, $this->db1);
		if($result == null) {
			$qry = "SELECT b.invoiceno, b.prdcd, c.prdnm, c.kit,
					b.pricecode, b.dp, b.bv, b.qtyord,
				    COUNT(d.formno) as jml_active,
				    b.qtyord - COUNT(d.formno) as sisa_qty
				FROM klink_mlm2010.dbo.ordtrd b
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd c
					ON (b.prdcd = c.prdcd)
				LEFT OUTER JOIN klink_mlm2010.dbo.starterkit d
					ON (b.invoiceno = d.sold_trcd) AND d.prdcd = b.prdcd
				WHERE b.invoiceno = ? AND c.kit = '1'
				GROUP BY b.invoiceno, b.prdcd, c.prdnm,
					b.pricecode, b.dp, b.bv, b.qtyord, c.kit";
	        $result = $this->getRecordset($qry,$param,$this->db1);
		}
		return $result;
	}

	function getStarterkitBrOrderno($orderno, $prdcd){
		 $qry = "SELECT a.sold_trcd, a.formno, a.updatenm, a.updatedt,
		                a.activate_dfno, b.fullnm, a.status,
						CONVERT (VARCHAR(30),a.activate_dt,103) as activate_dt
				 FROM klink_mlm2010.dbo.starterkit a
				     LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b on a.activate_dfno=b.dfno
				 WHERE A.sold_trcd = ? AND a.prdcd = ?
				 ORDER BY a.formno";
		//echo $qry;
		$param = array($orderno, $prdcd);
		$result = $this->getRecordset($qry, $param, $this->db1);
		//var_dump($result);
		return $result;
	}

	function getValidVoucher($vch_start, $qty) {
		  $bagian1 = substr($vch_start, 0, 6);
          $counter = substr($vch_start, 6, 6);
          $value = "";
          $baru = $qty + $counter - 1;

          $data = array();
          $arrayKe = 0;
          for($i = $counter; $i <= $baru; $i++)
          {

            $next_id = sprintf("%06s",$i);
        	$y =  strval($bagian1.$next_id);
        	$value .= "'".$y."',";
            array_push($data,$y);
        	$arrayKe++;
          }
          $value=substr($value,0,-1);
          $arrayKe--;

          $cekAvailableVoucher = "SELECT a.sold_trcd, a.formno, a.updatenm,
		  							CONVERT(VARCHAR(10), a.updatedt, 111) as updatedt, a.activate_dfno,
									  b.fullnm, a.status
                                  FROM klink_mlm2010.dbo.starterkit a
                                  LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b on a.activate_dfno=b.dfno
								  WHERE a.formno IN ? AND a.status != '0'";
		  $paramQry = array(array($value));
		  $result = $this->getRecordset($cekAvailableVoucher,null,$this->db1);
		  if($result != null) {
		  	 $arr = array("response" => "false", "res" => $result);
		  } else {
		  	$arr = array("response" => "true", "res" => $data[$arrayKe]);
		  }
		  return $arr;
	}

	function updateReleaseVoucher($form) {
		$value = "";
		for($i = $form['vch_start']; $i <= $form['vch_end']; $i++)
        {
           $value .= "'".$i."',";
        }
        $value=substr($value,0,-1);

        $tgl_skrg = date("Y-m-d");

		$updStarterkit = "UPDATE starterkit SET sold_trcd = '$form[trxno]',
								 status='1', updatenm = '$this->username',
    	                         updatedt='$tgl_skrg', PT_SVRID = 'ID',
								 prdcd='$form[productcode]', sold_trcdnewera = '$form[trxno]'
    					  WHERE formno IN($value)";
		$res = $this->executeQuery($updStarterkit, $this->db2);
		 if($res > 0) {
		  	 $arr = jsonTrueResponse(null, "Voucher berhasil di release..");
		 } else {
		  	$arr = jsonFalseResponse("Voucher gagal di release..");
		 }
		 return $arr;


	}

	function getDetailVoucher($formno) {
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
							  a.formno = ?";
		$paramQry = array($formno);
        $result = $this->getRecordset($cekVoucherNum, $paramQry, $this->db2);
		//var_dump($result);
		return $result;
    }

    function listVoucherPromo($stockist, $vch_status)
    {
        $query = "SELECT a.formno, a.vchkey, b.prdnm,
                        CASE
                        WHEN a.status = '0' THEN 'BLM DIRELEASE'
                        WHEN a.status = '1' THEN 'SDH DIRELEASE'
                        WHEN a.status = '2' THEN 'SDH DIPAKAI'
                        END AS 'status' , a.sold_trcd as no_mm,
                    a.activate_dfno, a.activate_by
									FROM klink_mlm2010.dbo.starterkit a
									LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcd = b.prdcd)
									WHERE a.loccd = '$stockist'
									AND a.status = '$vch_status'
											AND a.prdcd IN (
											SELECT a.prdcd
											FROM msprd a WHERE a.sk_stockist = '1'
											OR
											a.prdcd IN ('SKBJ01', 'SKBJ02', 'SKCL01',
											'SKLIP-1F','SKLIP-2F','SKLIP-3F','SKLIP-4F')
									)";

        $hasil = $this->_get_data_json_result1($query);

        if($hasil['response'] == "false") {
            return array(
                "response"  => "false",
                "arraydata" => null,
                "message"   => "Tidak ada voucher"
            );
        }

        return array(
            "response"  => "true",
            "arraydata" => $hasil['arraydata']
        );
    }

    function checkVchStk($voucherno, $vchkey, $stk) {
        $qry = " SELECT a.formno, a.vchkey, a.sold_trcd, a.prdcd, a.[status],
                 CASE WHEN a.status = '0' THEN 'Belum direlease'
                 WHEN a.status = '1' THEN 'Sudah direlease'
                 WHEN a.status = '2' THEN 'Sudah terpakai'
                 END AS ket_status, a.loccd,
                 a.activate_dfno
                 FROM starterkit a
                 WHERE a.formno = '$voucherno' and a.vchkey = '$vchkey' AND
                  a.loccd = '$stk'";
        //echo $qry;
        $hasil = $this->_get_data_json_result1($qry);

        if($hasil['response'] == "false") {
            $arr = array("response" => "false", "arraydata" => null, "message" => "Voucher tidak valid / Voucher Key Salah / Bukan milik $stk");
            return $arr;
        }

        $dtx = $hasil['arraydata'][0];
        if($hasil['response'] == "true" && $dtx->status == "2") {
            $arr = array("response" => "false", "arraydata" => $hasil['arraydata'], "message" => "Voucher sudah dipakai dengan id member ".$dtx->activate_dfno);
            return $arr;
        }

        if($hasil['response'] == "true" && $dtx->status == "1") {
            $arr = array("response" => "false", "arraydata" => $hasil['arraydata'], "message" => "Voucher sudah di release, bisa untuk dipakai..");
            return $arr;
        }

        if($hasil['response'] == "true" && $dtx->status == "0") {
            $arr = array("response" => "true", "arraydata" => $hasil['arraydata'], "message" => "Voucher siap untuk di release");
            return $arr;
        }
    }
}