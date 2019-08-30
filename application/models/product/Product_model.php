<?php
class Product_model extends MY_Model {
		
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		
    }
	
	function getProductByID($id) {
		/*$qry = "SELECT 
		          db_ecommerce.dbo.master_prd_pricetab.pricecode,
		          db_ecommerce.dbo.master_prd_pricecode.pricecode_desc,
		          db_ecommerce.dbo.master_prd_pricetab.cat_inv_id,
		          db_ecommerce.dbo.master_prd_cat_inv.cat_inv_desc,
		          db_ecommerce.dbo.master_prd_pricetab.cp,
		          db_ecommerce.dbo.master_prd_pricetab.dp,
		          db_ecommerce.dbo.master_prd_pricetab.bv,
		          db_ecommerce.dbo.master_prd_pricetab.tax
		        FROM
		          db_ecommerce.dbo.master_prd_pricetab
		          INNER JOIN db_ecommerce.dbo.master_prd_cat_inv ON 
		              (db_ecommerce.dbo.master_prd_pricetab.cat_inv_id = db_ecommerce.dbo.master_prd_cat_inv.cat_inv_id)
		          INNER JOIN db_ecommerce.dbo.master_prd_pricecode ON 
		              (db_ecommerce.dbo.master_prd_pricetab.pricecode = db_ecommerce.dbo.master_prd_pricecode.pricecode)
		        WHERE
		          (db_ecommerce.dbo.master_prd_pricetab.cat_inv_id = '$id')"; */
				  
		$qry = "SELECT * FROM V_STK_PRODUCT a
		        WHERE a.prdcd LIKE'$id%' 
				and a.price_w is not null 
				and a.price_e is not null";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
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
				from msprd a
				     inner join pricetab b on a.prdcd=b.prdcd and b.pricecode='$pricecode' AND scstatus='1' AND a.webstatus = '1' AND a.status = '1'
				     AND a.prdcd = '$id'";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
	}
	
	function getProductByName($name) {
		$name = strtoupper($name);
		$qry = "SELECT * FROM V_STK_PRODUCT a 
		        WHERE a.prdnm LIKE '%$name%' 
				 and a.price_w is not null and a.price_e is not null";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
	}
	
	function getListFreeProduct($value) {
		$prdnm = "";
		if($value != "") {
			$prdnm .= " AND a.prdnm LIKE '%$value%'";
		} 
		$qry = "SELECT * FROM V_STK_PRODUCT a 
				WHERE a.price_w = 0 AND a.scstatus = '1'";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
	}
	
	function getListIndenProduct($value) {
		$prdnm = "";
		if($value != "") {
			$prdnm .= " AND a.prdnm LIKE '%$value%'";
		}
		
		$qry = "SELECT * FROM V_Ecomm_PriceList_Dion_Baru a 
				WHERE a.is_discontinue = '1' ";
		$res = $this->getRecordset($qry, NULL, $this->db3);
		return $res;
	}
	
	function getListPrdKnet($value, $stt) {
		$prdnm = "";
		if($value != "") {
			$prdnm .= " AND a.prdnm LIKE '%$value%'";
		}
		
		$qry = "SELECT * FROM V_Ecomm_PriceList_Dion_Baru a 
				WHERE a.ecomm_status = '$stt' and a.price_w is not null and a.price_e is not null";
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
		
		$qry = "SELECT a.*
			FROM V_STK_PRODUCT a 
			WHERE a.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS  IN (
				SELECT  
				b.cat_inv_id_parent 
				FROM db_ecommerce.dbo.master_prd_bundling b 
				GROUP BY b.cat_inv_id_parent
		    )";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
	}
	
}