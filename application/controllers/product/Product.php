<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller {
	public function __construct() {
	  parent::__construct();
		$this->folderView = "product/";
		$this->load->model('product/Product_model', 'm_product');
	}
	
	//$route['product/search'] = 'product/product/productSearch';
	public function productSearch() {		
		if($this->username != null) {
			$data['form_header'] = "Product Search";
			$data['form_action'] = base_url('product/search/list');
			$data['icon'] = "icon-search";
			$data['form_reload'] = 'product/search';
			$this->setTemplate($this->folderView.'productSearch', $data); 
		} else {
			echo sessionExpireMessage(false);
		} 
	}
	
	//$route['product/search/list'] = 'product/product/productSearchByParam';
	public function productSearchByParam() {
		$data = $this->input->post(NULL,TRUE);
		if($data['param'] == "prdcd") {
			$res['result'] = $this->m_product->getProductByID($data['paramValue']);		
		} else if ($data['param'] == "prdnm") {
			$res['result'] = $this->m_product->getProductByName($data['paramValue']);
		} else if($data['param'] == "P") {
			$res['result'] = $this->m_product->getListProductBundling($data['paramValue']);
		} else if($data['param'] == "F") {
			$res['result'] = $this->m_product->getListFreeProduct($data['paramValue']);
		} else if($data['param'] == "dis") {
			$res['result'] = $this->m_product->getListIndenProduct($data['paramValue']);
		} else if($data['param'] == "non_knet") {
			$res['result'] = $this->m_product->getListPrdKnet($data['paramValue'], "0");
		} else if($data['param'] == "knet") {
			$res['result'] = $this->m_product->getListPrdKnet($data['paramValue'], "1");
		}
		$this->load->view($this->folderView.'productDetailByID', $res);
	}
	
	//$route['product/id/(:any)/(:any)'] = 'product/product/productSearchByID/$1/$2';
	public function productSearchByID($id, $pricecode) {
		$res = jsonFalseResponse("Invalid product code..");
		$result = $this->m_product->getProductPriceByID($id, $pricecode);	
		if($result != null) {
			$res = jsonTrueResponse($result, "");
		}
		echo json_encode($res);	
	}

	// for produk bundling
	public function check_kode_bundlingFrm() {
		$data['form_header'] = "Check Kode Bundling";
		$data['form_action'] = "stk/barcode/trx/list";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'stk/barcode';

		if($this->username != null) {
			$data['from1'] 	= date("Y-m-d");
			$data['to1'] 	= date("Y-m-d");
			$data['from'] 	= date("Y-m-d");
			$data['to'] 	= date("Y-m-d");
			$data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);
			$this->setTemplate($this->folderView.'Chek_kode_bundlingFrm', $data);
		} else {
			$this->setTemplate('includes/inline_login', $data);
		}
	}

	//$route['product/bundling/checkCode'] = 'product/product/formCheckBundling';
	public function formCheckBundling() {
		if($this->username != null) {
			$data['form_header'] = "Check Kode Bundling";
			$data['icon'] = "icon-search";
			$data['form_reload'] = 'product/bundling/checkCode';
			$data['listBundle'] = $this->m_product->listBundleHeader();
			$this->setTemplate($this->folderView.'checkBundling', $data);
		} else {

			$this->setTemplate('includes/inline_login', $data);
		}
	}

	//$route['product/bundling/list/(:any)'] = 'product/product/listDetailBundle/$1';
	public function listDetailBundle($kode) {
		$res = $this->m_product->listDetailBundle($kode);
		$return = jsonFalseResponse("Tidak ada isi bundling..");
		if($res !== null) {
			$return = jsonTrueResponse($res);
		}
		echo json_encode($return);
	}

	//$route['product/bundling/code'] = 'product/product/searchBundlingCode';
	public function searchBundlingCode() {
		$arr = $this->input->post(NULL, TRUE);
            
		$jum = count($arr['qty']);
		$arrx = array();
		for($i = 0;  $i < $jum; $i++) {
			if($arr['qty'][$i] !== "0" && $arr['qty'][$i] !== "" && $arr['qty'][$i] !== " ") {
				$arrx['prdcd'][] = $arr['prdcd'][$i];
				$arrx['qty'][] = $arr['qty'][$i];
			} 
		}
            
		$arrx['promotype'] = $arr['promotype'];

		if($arr['source_type'] == "backend") {
			$arrx['source'] = "backend";
			$res = $this->m_product->cariBundlingKode($arrx);
			$arrTable = array(
				"id" => "tbl1",
				"header" => "Data Bundling",
				"column" => array(
						"Kode", "Nama Produk", "BV", "Dist Wil A", "Dist Wil B", "Dist TL", "Cust Wil A", "Cust Wil B", "Cust TL"
				),
				"columnAlign" => array(
						"center", "left", "right", "right","right","right","right","right","right"
				),

				"recordStyle" => array(
						"", "", "money","money","money","money","money","money","money"
				),
				"record" => $res,
				"datatable" => true,
			);
			echo generateTable($arrTable);
		} else if($arr['source_type'] == "knet") {
			$arrx['source'] = "knet";
			$res = $this->m_product->cariBundlingKode($arrx);
			echo "<pre>";
			print_r($res);
			echo "</pre>";
		}    
	}
}