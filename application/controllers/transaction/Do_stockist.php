<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Do_stockist extends MY_Controller {
	public function __construct() {
	  parent::__construct();
		$this->folderView = "transaction/do/";
		//$this->load->model('transaction/m_incoming_payment', 'incoming_payment');
	}

	//$route['do/stk'] = 'transaction/do_stockist/formGetListDO';  
	public function formGetListDO() {
		$data['form_header'] = "DO Stockist";
		$data['form_action'] = "do/stk/list";
		$data['icon'] = "icon-search";
		$data['form_reload'] = 'do/stk';   		   
		   		
    if($this->username != null) {
			$data['from'] 	= date("Y-m-d");
			$data['to'] 	= date("Y-m-d");
			$data['sc_dfno'] 	= $this->stockist;			
		  // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);   
		  $this->setTemplate($this->folderView.'formDoStockist', $data); 
		} else {
			$this->setTemplate('includes/inline_login', $data);
		} 
	}

	//$route['do/stk/list'] = 'transaction/do_stockist/getListDOStk';
	public function getListDOStk() {
		$data = $this->input->post(NULL, TRUE);
		$this->load->model('transaction/do_stockist_model', 'do_stk');
		if($data['searchby'] == "") {
			$data['result'] = $this->do_stk->getListDOStkByData($data['from'], $data['to'], $data['loccd']);
			$this->load->view($this->folderView.'listDoStkByDate',$data);	
		}
	}

	//$route['do/stk/gdo/(:any)'] = 'transaction/do_stockist/listSSRbyGDO/$1';
	public function listSSRbyGDO($no_do) {
		$this->load->model('transaction/do_stockist_model', 'do_stk');
		$data['back_button'] = "All.back_to_form(' .nextForm1',' .mainForm')";
		$data['no_do'] = $no_do;
		$prefix_inv = substr($no_do, 0, 4);
		
		$data['result'] = $this->do_stk->getListSSRbyDO($no_do);
		if($data['result'] == null) {
			$data['result'] = $this->do_stk->getListInvoicebyDO($no_do);
		}
		
		$this->load->view($this->folderView.'listSSRbyDO',$data);
		/* echo "<pre>";
		print_r($data['result']);
		echo "</pre>"; */

	}

	//$route['do/stk/trx/(:any)/(:any)'] = 'transaction/do_stockist/listTTPbySSR/$1/$1';
	public function listTTPbySSR($field, $value) {
		//$this->load->model('transaction/do_stockist_model', 'do_stk');
		$this->load->model('transaction/Sales_stockist_report_model', 'm_ssr');
		if($field == "batchno") {
			$data['back_button'] = "All.back_to_form(' .nextForm2',' .nextForm1')";
			$data['result'] = $this->m_ssr->listTtpByIdV2($field, $value);
			$data['rekapPrd'] = $this->m_ssr->summaryProductBySSR($value);
			$this->load->view('transaction/stockist_report/listTTP', $data);
		} else if($field == "trcd") {
			$data['back_button'] = "All.back_to_form(' .nextForm3',' .nextForm2')";
			$data['result'] = $this->m_ssr->detailTrxByTrcd($field, $value);
			$this->load->view('transaction/stockist_report/detailTrx', $data);
		}
	}
}   