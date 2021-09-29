<?php
class Product_model extends MY_Model {
		
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		
    }
	
	function getProductByID($id) {

		//$param_id = $this->db->escape_like_str($id);
		$qry = "SELECT * FROM V_STK_PRODUCT a
		        WHERE a.prdcd LIKE ? 
				AND a.price_w IS NOT NULL 
				AND a.price_e IS NOT NULL AND a.status = '1' 
				AND a.webstatus='1' AND a.scstatus = '1' AND a.prdcd NOT LIKE 'GAT%'";
		//echo $qry;
		$product_code = strtoupper($id).'%';
		$param = array($product_code);
		$res = $this->getRecordset($qry, $param, $this->db2);
		return $res;
	}
	
	function getProductPriceByID($id, $pricecode) {			  
		$qry = "SELECT a.prdcd, 
				       a.prdnm,
				       a.category,
				       a.status,
				       a.webstatus,
				       a.scstatus,
				       b.dp ,
				       b.cp , 
				       b.bv,  
				       b.pricecode AS [pricecode]
				FROM msprd a
				INNER join pricetab b on a.prdcd=b.prdcd 
				     AND b.pricecode= ? AND scstatus='1' AND a.webstatus = '1' AND a.status = '1'
					 and b.flag_type != 'MK'
				     AND a.prdcd = ?";
		//echo $qry;
		$pricecode = trim(strtoupper($pricecode));
		$id = trim(strtoupper($id));
		$param = array($pricecode, $id);
		$res = $this->getRecordset($qry, $param, $this->db2);
		return $res;
	}
	
	function getProductByName($name) {
		$name = strtoupper($name);
		//$param_id = $this->db->escape_like_str($name);
		$qry = "SELECT * FROM V_STK_PRODUCT a 
		        WHERE a.prdnm LIKE ? 
				 and a.price_w is not null 
				 and a.price_e is not null and a.flag_type != 'MK'
				 AND a.status = '1' AND a.webstatus='1' AND a.scstatus = '1' AND a.prdcd NOT LIKE 'GAT%'";
		//echo $qry;
		$arrParam = array(
			"%".$name."%"
		);
		$res = $this->getRecordset($qry, $arrParam, $this->db2);
		return $res;
	}
	
	function getListFreeProduct($value) {
		$prdnm = "";
		$arrParam = null;
		if($value != "") {
			//$param_id = $this->db->escape_like_str($value);
			$prdnm .= " AND a.prdnm LIKE ?";
			$arrParam = array(
				"%".$value."%"
			);
		} 
		$qry = "SELECT * 
				FROM V_STK_PRODUCT a 
				WHERE a.price_w = 0 AND a.scstatus = '1' 
					AND a.status = '1' AND a.webstatus='1' 
					AND a.scstatus = '1' and a.flag_type != 'MK' $prdnm";
		//echo $qry;
		$res = $this->getRecordset($qry, $arrParam, $this->db2);
		return $res;
	}
	
	function getListIndenProduct($value) {
		/* $prdnm = "";
		if($value != "") {
			$prdnm .= " AND a.prdnm LIKE '%$value%'";
		} */
		
		$qry = "SELECT * FROM V_Ecomm_PriceList_Dion_Baru a 
				WHERE a.is_discontinue = '1' ";
		$res = $this->getRecordset($qry, NULL, $this->db3);
		return $res;
	}
	
	function getListPrdKnet($value, $stt) {
		/* $prdnm = "";
		if($value != "") {
			$prdnm .= " AND a.prdnm LIKE '%$value%'";
		} */
		
		$qry = "SELECT * FROM V_Ecomm_PriceList_Dion_Baru a 
				WHERE a.ecomm_status = '$stt' and a.price_w is not null 
				and a.price_e is not null";
		$res = $this->getRecordset($qry, NULL, $this->db3);
		return $res;
	}
	
	/*
	function getProductByName($name) {
		$name = strtoupper($name);
		$qry = "SELECT * FROM DION_msprd_pricetab a WHERE a.prdnm LIKE '%$name%' AND a.webstatus = '1' AND a.status = '1'";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
	}
	
	function getListFreeProduct($value) {
		$prdnm = "";
		if($value != "") {
			$prdnm .= " AND a.prdnm LIKE '%$value%'";
		} 
		$qry = "SELECT * FROM DION_msprd_pricetab a WHERE a.prdcd LIKE '%F' AND a.webstatus = '1' AND a.status = '1' AND a.bv = 0 $prdnm";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
	}
	*/
	function getListProductBundling($value) {
		$arrParam = null;
		$prdnm = "";
		if($value != "") {
			//$param_id = $this->db->escape_like_str($value);
			$prdnm .= " AND a.prdnm LIKE ?";
			$arrParam = array(
				"%".$value."%"
			);
		} 
		$qry = "SELECT a.*
			FROM V_STK_PRODUCT a 
			WHERE a.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS  IN (
				SELECT  
				b.cat_inv_id_parent 
				FROM db_ecommerce.dbo.master_prd_bundling b 
				GROUP BY b.cat_inv_id_parent
		    ) AND a.prdcd NOT LIKE 'GAT%' and a.flag_type != 'MK' AND a.status = '1' AND a.webstatus='1' 
			AND a.scstatus = '1' $prdnm";
		//echo $qry;
		$res = $this->getRecordset($qry, $arrParam, $this->db2);
		return $res;
	}

	function listBundleHeader() {
		$qry = "SELECT * FROM klink_mlm2010.dbo.bundle_map_header a WHERE a.status = '1'";
		$res = $this->getRecordsetArray($qry, null, $this->db2);
		return $res;
	}

	function cariBundlingKode($arr) {
		$jum = count($arr['prdcd']);
		
		$find = explode("|", $arr['promotype']);
		$promotype = $find[0];

		$i = 0;
		$total_qty = 0;
		$qryTemp = "";
		$awal = "";
		for($i = 0; $i < $jum; $i++) {	
			$total_qty += $arr['qty'][$i];
			if($i > 0) {
				$qryTemp = " AND a.prdcdCat IN ($arrTamp)";	
			} else {
				$awal = " a.prdcdCat LIKE '".$promotype."%' AND ";
			}
			$qry = "SELECT DISTINCT(a.prdcdCat) as kode
					FROM klink_mlm2010.dbo.newera_PRDDET a
					WHERE $awal (a.prdcdDet = '".$arr['prdcd'][$i]."' 
					AND a.qty = '".$arr['qty'][$i]."') $qryTemp";
			$res = $this->getRecordsetArray($qry, null, $this->db2);
			if($res !== null) {
				$arrTamp = set_list_array_to_string2($res, "kode");
			}
			/* echo $qry;
			echo "<br /><br />"; */
		}

		$qryx = "SELECT a.prdcdCat
				FROM klink_mlm2010.dbo.newera_PRDDET a 
				WHERE a.prdcdCat IN ($arrTamp)
				GROUP BY a.prdcdCat";
		//echo $qryx;
		$resAkhir = $this->getRecordsetArray($qryx, null, $this->db2);
		if($resAkhir !== null) {
			$arrTamp = set_list_array_to_string2($resAkhir, "prdcdCat");
			if($arr['source'] == "backend") {
				$hasil = $this->cekHargaByID($arrTamp);
			} else if($arr['source'] == "knet") {
				//echo "masuk";
				$hasil = $this->cekHargaKnet($arrTamp);
				//print_r($hasil);
			}
		} else {
			$hasil = null;
		}

		
		/* echo "<pre>";
		print_r($hasil);
		echo "</pre>"; */

		return $hasil;
		
		
	}

	function cekHargaByID($param) {
		$qry = "SELECT a.prdcd, a.prdnm, 
					CAST(a.bv AS INT) as bv,
					CAST(a.price_w AS INT) as 'price_w', 
					CAST(a.price_e AS INT) as 'price_e' , 
					CAST(a.price_tl AS INT) as 'price_tl', 
					CAST(a.price_cw AS INT) as 'price_cw', 
					CAST(a.price_ce AS INT) as 'price_ce', 
					CAST(a.price_ctl AS INT) as 'price_ctl'
				FROM klink_mlm2010.dbo.DION_msprd_pricetabV2 a
				WHERE a.prdcd IN ($param)";
		
		$res = $this->getRecordsetArray($qry, null, $this->db2);
		return $res;
	}

	function cekHargaKnet($param) {
		$qry = "SELECT a.prdcd, 
					a.prdnm, a.searchPrd,
					a.prdcdcat, 
					a.prdnmcatnm,
					a.img_url, 
					a.price_w, 
					a.price_e,
					a.price_cw, 
					a.price_ce, 
					a.bv,
					a.weight,
					a.ecomm_status,
					a.is_discontinue,
					a.max_order,
					a.is_bestseller, a.is_hotproduct, a.is_newproduct, a.is_product_training,
					a.prd_group_lp, a.prd_group_lp_desc, 
					a.family_groupcode, a.family_groupcode_desc,
					a.prd_start_on, a.prd_end_on
		FROM V_Ecomm_PriceList_Baru_Promo a
		WHERE a.prdcd IN ($param)
		and CONVERT(VARCHAR, GETDATE(), 20) >= CONVERT(VARCHAR, a.prd_start_on, 20)
		and CONVERT(VARCHAR, GETDATE(), 20) <= CONVERT(VARCHAR, a.prd_end_on, 20)";
		//echo $qry;
		$res = $this->getRecordset($qry, null, $this->db1);
		return $res;
	}

	function listDetailBundle($prdcd) {
		/* $qry = "SELECT DISTINCT(a.prdcdDet) as prdcd, b.prdnm
				FROM klink_mlm2010.dbo.newera_PRDDET a
				LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON (a.prdcdDet = b.prdcd)
				WHERE a.prdcdCat LIKE '$prdcd%'";
		$res = $this->getRecordsetArray($qry, null, $this->db2);
		return $res; */

		$listArr = array();
		$list = "SELECT DISTINCT(tipe), jum_max 
				 FROM klink_mlm2010.dbo.bundling_map a
				 WHERE a.bundle = '$prdcd' AND a.active = '1'";
		$res = $this->getRecordsetArray($list, null, $this->db2);	
		$i = 0;	
		
		foreach($res as $dtax) {
			$listArr[$i]['tipe'] = $dtax['tipe'];
			$listArr[$i]['jum_max'] = $dtax['jum_max'];

			$qry = "SELECT a.*, b.prdnm 
				FROM klink_mlm2010.dbo.bundling_map a 
				LEFT OUTER Join klink_mlm2010.dbo.msprd b 
				  ON (a.kode COLLATE SQL_Latin1_General_CP1_CS_AS = b.prdcd ) 
				WHERE a.bundle = '$prdcd' AND a.active = '1' AND a.tipe = '".$dtax['tipe']."'";
			$res = $this->getRecordsetArray($qry, null, $this->db2);
			if($res == null) {
				return null;
			}

			$y = 0;
			foreach($res as $dta1) {
				$listArr[$i]['listprd'][$y]['kode'] = $dta1['kode'];
				$listArr[$i]['listprd'][$y]['prdnm'] = $dta1['prdnm'];
				$y++;
			}	
			$i++;
		}

		return $listArr;
	}
	
}