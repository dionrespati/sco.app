<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_receipt extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->folderView = "transaction/payment/";
    $this->load->model('transaction/payment_receipt_model', 'kw');
	}

  //$route['payment/receipt'] = 'transaction/payment_receipt/index';
  public function index() {
    $data['form_header'] = "Payment Receipt";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'payment/receipt';
		//echo "stk : ".$this->stockist;
		if($this->username != null) {	
      $data['from'] = date("Y-m-d");
      $data['to'] = date("Y-m-d");
      $data['sc_dfno'] = $this->stockist;
			$this->setTemplate($this->folderView.'paymentReceipt', $data); 
		} else {
			//echo sessionExpireMessage(false);
			$this->setTemplate('includes/inline_login', $data);
    }  
  }

  //$route['payment/receipt/findregister'] = 'transaction/payment_receipt/findRegister';
  public function findRegister() {
    $data = $this->input->post(NULL, TRUE);
    
    $data['listInv'] = $this->kw->getListTrx($data['tipe_trx'], $data['paramValue']);
    $data['listIncPay'] = $this->kw->getListIncomingPay($data['tipe_trx'], $data['paramValue']);
    
    $data['listKw'] = $this->kw->getListTrxSdhJadiKW($data['tipe_trx'], $data['paramValue']);
    $this->load->view($this->folderView.'paymentReceiptResult', $data); 
  }

  //$route['payment/receipt/findIncPayByInv'] = 'transaction/payment_receipt/findIncPayByInv';
  public function findIncPayByInv() {
    $data = $this->input->post(NULL, TRUE);
    $param = substr($data['listInv'], 0, -1);
    $result = $this->kw->getListIncomingPay($data['tipe_trx'], $data['regnox'], $param);
    $resp = jsonFalseResponse("Tidak ada Incoming Payment");
    if($result !== null) {
      $resp = jsonTrueResponse($result);
    }
    echo json_encode($resp);
  }

  //$route['payment/receipt/save'] = 'transaction/payment_receipt/simpanKW';
  public function simpanKW() {
    $data = $this->input->post(NULL, TRUE);
    $param = substr($data['listInv'], 0, -1);
    $result = $this->kw->simpanKW($data['tipe_trx'], $data['regnox'], $param);
    echo json_encode($result);
  }

  //$route['payment/print'] = 'transaction/payment_receipt/printKw';
  public function printKw() {
    $data = $this->input->post(NULL, TRUE);

    /* $no_kw = set_list_array_to_string($data['cek']);
    $data['result'] = $this->kw->getDataByKw($data['tipe'], $no_kw);
    echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    $count = count($data['cek']);
    $data['arr'] = array();
    for($i=0; $i < $count; $i++) {
      $result = $this->kw->getDataByKw($data['tipe'], $data['cek'][$i]);
      array_push($data['arr'], $result);
    }
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    $data['printuser'] = $this->username;
    $this->load->view($this->folderView.'printKw', $data);
  }

  function form_report(){
    if($this->username != null) {
      $data['form_header'] = "Payment Receipt Report";
      $data['icon'] = "icon-pencil";
      $data['form_reload'] = 'payment/receipt/report';
      $data['username'] = $this->username;
      $this->setTemplate($this->folderView.'recoverIncPayVc', $data);
    } else {
      redirect('backend', 'refresh');
    }
  }

  //$route['payment/receipt/finddata'] = 'transaction/payment_receipt/findData';
  public function findData() {
    $data = $this->input->post(NULL, TRUE);
    $data['listInv'] =NULL;
    $data['listIncPay'] = NULL;
    
    if($data['trxtype'] == "stk") {
      $tipe_trx="3";
    } else if($data['trxtype'] == "inv") {
      $tipe_trx="2";
    } else {
      $tipe_trx="1";
    }

    //$data['listKw'] = $this->kw->getListTrxSdhJadiKW($tipe_trx, '2204080003');
    $data['listKw'] = $this->kw->getListKWReport($tipe_trx, $data['statement'], $data['from'],$data['to']);
    $this->load->view($this->folderView.'recoverIncPayList', $data); 
  }

  function getDetailIncPayVc($trxno) {
    //$data = $this->input->post(NULL, TRUE);
    $data['kw_header'] = $this->kw->getBillivHdr("trcd", $trxno);

    //if($data['trxtype'] == "stk") {
      $data['kw_header'] = $this->kw->getBillivHdr("trcd", $trxno);

      if($data['kw_header'] != null) {
        $data['tipe_trx'] = "stk";
        $data['product'] = $this->kw->getKWProduct("billivprd", "trcd", $trxno);
        $data['listPay'] = $this->kw->getKWListPay("billivdetp", "trcd", $trxno);
        $data['listCn'] = $this->kw->getOrdTrhHeader("stk", "receiptno", $trxno);
        $this->load->view($this->folderView.'getTrxByKW', $data);

      } else {
        $data['kw_header'] = $this->kw->getBillHdr("trcd", $trxno);
          if($data['kw_header'] != null) {
            $data['tipe_trx'] = "inv";
            $data['product'] = $this->kw->getKWProduct("billprd", "trcd", $trxno);
            $data['listPay'] = $this->kw->getKWListPay("billdetp", "trcd", $trxno);
            $data['listCn'] = $this->kw->getOrdTrhHeader("inv", "receiptno", $trxno);
            $this->load->view($this->folderView.'getTrxByKW', $data);

          } else {
            echo setErrorMessage("No $data[trxno] tidak ditemukan..");
          }
      }
     
   //}
  }

  function getDetailIncPayVc2() {
    $data = $this->input->post(NULL, TRUE);
    if($data['trxtype'] == "stk") {
      $data['kw_header'] = $this->kw->getBillivHdr("trcd", $data['trxno']);

      if($data['kw_header'] != null) {
        $data['tipe_trx'] = "stk";
        $data['product'] = $this->kw->getKWProduct("billivprd", "trcd", $data['trxno']);
        $data['listPay'] = $this->kw->getKWListPay("billivdetp", "trcd", $data['trxno']);
        $data['listCn'] = $this->kw->getOrdTrhHeader("stk", "receiptno", $data['trxno']);
        $this->load->view($this->folderView.'getTrxByKW', $data);

      } else {
        $data['kw_header'] = $this->kw->getBillHdr("trcd", $data['trxno']);
          if($data['kw_header'] != null) {
            $data['tipe_trx'] = "inv";
            $data['product'] = $this->kw->getKWProduct("billprd", "trcd", $data['trxno']);
            $data['listPay'] = $this->kw->getKWListPay("billdetp", "trcd", $data['trxno']);
            $data['listCn'] = $this->kw->getOrdTrhHeader("inv", "receiptno", $data['trxno']);
            $this->load->view($this->folderView.'getTrxByKW', $data);
          } else {
            echo setErrorMessage("No $data[trxno] tidak ditemukan..");
          }
      }
     
    }
  }

  //$route['payment/receipt/cancel'] = 'transaction/payment_receipt/formCancelPayReceipt';
  public function formCancelPayReceipt() {
    if($this->username != null) {
      $data['form_header'] = "Payment Receipt Cancel";
      $data['icon'] = "icon-pencil";
      $data['form_reload'] = 'payment/receipt/cancel';
      $data['username'] = $this->username;
      $this->setTemplate($this->folderView.'formCancelPayReceipt', $data);
    } else {
      redirect('backend', 'refresh');
    }
  }

  //$route['payment/receipt/cancel/save'] = 'transaction/payment_receipt/saveCancelPayReceipt';
  public function saveCancelPayReceipt() {
    $data = $this->input->post(NULL, TRUE);
    $res = $this->kw->batalKw($data['no_kw']);

  }
 
}