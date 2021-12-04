<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lbc extends MY_Controller {
	public function __construct() {
	  parent::__construct();
		$this->folderView = "member/";
		$this->load->model('member/Lbc_model', 'm_lbc');
	}
	
	//$route['lbc'] = 'member/lbc/regLbcMember';
	public function regLbcMember() {
		$data['form_header'] = "LBC Registration";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'lbc';
		$data['form_action'] = 'lbc/save';	   
		
		if($this->username == null) {	
		  $this->setTemplate('includes/inline_login', $data);
		  return;
		} 
		$this->setTemplate($this->folderView.'lbc', $data); 
	}
	
	//$route['lbc/id/(:any)'] = 'member/lbc/checkLbcByID/$1';
	public function checkLbcByID($id) {
		$arr = $this->_checkLbcByID($id);
		echo json_encode($arr);
	}
	
	private function _checkLbcByID($id) {
		$arr = array("response" => "false",  "message" => "Member tidak terdaftar / TERMINATION..");
		//Cek apakah ID valid / Tidak termination / Member bukan laki-laki
		$check = $this->m_lbc->checkValidID($id);
	  if($check == null) {
			$arr = array("response" => "false",  "message" => "Member tidak terdaftar / TERMINATION..");
			return $arr;
		}
		
		if($check[0]->sex == "M") {
			$arr = array("response" => "false", "message" => "Hanya wanita yang diperbolehkan..!");
			return $arr;
		} 

		$checkBV = $this->m_lbc->check400BV($id);
		if($checkBV[0]->hasil <= 0) {
			$arr = array("response" => "false",  "message" => "Syarat pembelanjaan 3 bulan terakhir 400 BV tidak terpenuhi..!");
			return $arr;
		} 

		$exp = $this->m_lbc->checkLbcExpireDate($id);
		if($exp['response'] == "true") {
			$arr = jsonTrueResponse($check, $exp['message']);
		} else {
			$arr = jsonFalseResponse($exp['message']);
		}
		return $arr;
	}
	
	//$route['lbc/save'] = 'member/lbc/saveRegLbc';
	public function saveRegLbc() {
		$data = $this->input->post(NULL, TRUE);
		//check valid ID Member and terms
		$err = $this->_checkLbcByID($data['idmember']);
		if($err['response'] == "true") {
			$save = $this->m_lbc->saveRegLbc($data);	
			echo json_encode($save);
		} else {
			echo json_encode($err);
		}
		
	}
	
	//$route['lbc/report'] = 'member/lbc/lbcReport';
	public function lbcReport() {
		
	}
	
	//$route['lbc/report/list'] = 'member/lbc/lbcReportList';
	public function lbcReportList() {
		
	}
}