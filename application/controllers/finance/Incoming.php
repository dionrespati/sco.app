<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Incoming extends MY_Controller {
  public function __construct() {
    parent::__construct();
    $this->folderView = "finance/incoming/";
    $this->load->model('finance/incoming_model', 'incoming');
  }

  //$route['inc/pay'] = 'finance/incoming/formIncPayment';
  public function formIncPayment() {
    $data['form_header'] = "Incoming Payment";
    $data['form_action'] = "inc/pay";
    $data['icon'] = "icon-pencil";
    $data['form_reload'] = "inc/pay";

    if ($this->username == null) {
        $this->setTemplate('includes/inline_login', $data);
        return;
    }

    $data['from'] = date("Y-m-d");
    $data['to'] = date("Y-m-d");
    $data['sc_dfno'] = $this->stockist;
    $data['listBank'] = $this->incoming->getListBank();
    $this->setTemplate($this->folderView.'formIncPay', $data);
  }

  //$route['inc/pay/form'] = 'finance/incoming/createIncPay';
  public function createIncPay() {
    $data['listBank'] = $this->incoming->getListBank();
    $data['from'] = date("Y-m-d");
    $data['to'] = date("Y-m-d");
    $this->load->view($this->folderView.'createIncPay', $data);
  }

  //$route['inc/pay/list'] = 'finance/incoming/listIncPay';
  public function listIncPay() {
    $data = $this->input->post(NULL, TRUE);
    $data['result'] = $this->incoming->getListIncomingPayment($data);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    if($data['result'] === null || $data['result'] === "" ) {
      echo setErrorMessage("Data Incoming Payment tidak ditemukan..");
    } else {
      $this->load->view($this->folderView.'listIncPay', $data);
    }
    
  }

  //$route['inc/pay/id'] = 'finance/incoming/listIncPayById';
  public function listIncPayById() {
    $data = $this->input->post(NULL, TRUE);
    $trcd = $data['trcd'];
    $data['result'] = $this->incoming->getVcipDetail($trcd);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    if($data['result']['response'] == "false") {
      echo setErrorMessage("Data Incoming Payment tidak ditemukan..");
    } else {
      $this->load->view($this->folderView.'incPayDet', $data);
    }
    
  }

  //$route['inc/pay/save'] = 'finance/incoming/saveIncPay';
  public function saveIncPay() {
    $data = $this->input->post(NULL, TRUE);
    $data['dfno'] = strtoupper($data['dfno']);
    if($data['amount'] <= 0) {
      
    }

    if($data['inc_refno'] == "") {
      echo json_encode(jsonFalseResponse("No Manual / Referensi harus diisi.."));
      return;
    }

    $check_ref = $this->incoming->checkBbhdr("refno", $data['inc_refno']);
    if($check_ref !== null) {
      $trcd = $check_ref[0]->trcd;
      echo json_encode(jsonFalseResponse("No Manual / Referensi sudah ada untuk no Incoming $trcd"));
      return;
    }

    if($data['customer_type'] == "S") {
      $check = $this->incoming->getFullName("mssc", "loccd", $data['dfno']);
      if($check === null) {
        echo json_encode(jsonFalseResponse("Kode Stokis salah / tidak ditemukan.."));
        return;
      }
    } 

    if($data['customer_type'] == "M") {
      $check = $this->incoming->getFullName("msmemb", "dfno", $data['dfno']);
      if($check === null) {
        echo json_encode(jsonFalseResponse("ID Member salah / tidak ditemukan.."));
        return;
      }
    }

    $data['inc_fullnm'] = strtoupper($data['inc_fullnm']);
    $data['tgl_input'] = $data['tgl_input'] !== "" ? $data['tgl_input'] : date("Y-m-d");
    $data['tgl_mutasi'] = $data['tgl_mutasi'] !== "" ? $data['tgl_mutasi'] : $data['tgl_input'];

    //echo json_encode(jsonTrueResponse(null, "Siapp untuk disimpan..."));


    $res = $this->incoming->saveIncomingPayment($data);
    echo json_encode($res);
  }

  //$route['inc/pay/update'] = 'finance/incoming/updateIncPay';
}