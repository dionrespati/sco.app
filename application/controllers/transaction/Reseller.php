<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reseller extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->folderView = "transaction/reseller/";
    $this->load->model('transaction/reseller_model', 'reseller');
	}

  //$route['reseller'] = 'transaction/reseller/inputTrxReseller';
  public function inputTrxReseller() {
    $data['form_header'] = "Transaksi Reseller";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'reseller';
		//echo "stk : ".$this->stockist;
		if($this->username != null) {	
      $data['from'] = date("Y-m-d");
      $data['to'] = date("Y-m-d");
      $data['sc_dfno'] = $this->stockist;
			$this->setTemplate($this->folderView.'inputTrx', $data); 
		} else {
			//echo sessionExpireMessage(false);
			$this->setTemplate('includes/inline_login', $data);
		} 
  }

  //$route['reseller/search'] = 'transaction/reseller/cariTrxReseller';
  public function cariTrxReseller() {
    if($this->username != null) {	
      $data = $this->input->post(NULL, TRUE);
      $data['hasil'] = $this->reseller->searchResellerByParam($data);
      if($data['hasil'] === null) {
        echo setErrorMessage("Data tidak ditemukan..");
      } else {
        $this->load->view($this->folderView.'searchReseller', $data);
      }
    } else {
			//echo sessionExpireMessage(false);
			$this->setTemplate('includes/inline_login', $data);
		}   
  }

  //$route['reseller/newregister'] = 'transaction/reseller/formNewRegister';
  public function formNewRegister() {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $data['head_form'] = "New Register";
    $data['stk'] = $this->stockist;
    $data['stkname'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $data['listWh'] = $this->reseller->listWh();
    $data['currentperiod'] = $this->reseller->getCurrentPeriod();
    $this->load->view($this->folderView.'newRegister', $data);
    //$this->load->view($this->folderView.'viewPaymentForm', $data);
  }

  //$route['reseller/id/(:any)'] = 'transaction/reseller/getDataReseller/$1';
  public function getDataReseller($idreseller) {
    $res = jsonFalseResponse("Kode Reseller tidak ditemukan..");
    $hasil = $this->reseller->getDataReseller($idreseller);
    if($hasil !== null) {
      $res = jsonTrueResponse($hasil);
    }
    echo json_encode($res);
  }

  //$route['reseller/listIncPay/(:any)'] = 'transaction/reseller/listIncPay/$1';
  public function listIncPay($idmember) {
    $res = jsonFalseResponse("Kode Reseller tidak ditemukan..");
    $hasil = $this->reseller->listIncPayByID($idmember);
    if($hasil !== null) {
      $res = jsonTrueResponse($hasil);
    }
    echo json_encode($res);
  }

  //$route['reseller/listIncPayV2'] = 'transaction/reseller/listIncPayV2';
  public function listIncPayV2() {
    $data = $this->input->post(NULL, TRUE);
    $res = jsonFalseResponse("Incoming Payment tidak ditemukan..");

    $exclude_inv = ""; 
    if($data['incpayment'] !== "") {
      $exclude_inv = substr($data['incpayment'], 0, -1);
    }
    /* if(count($data['incpayment']) > 0) {
      $incpayment = set_list_array_to_string($data['incpayment']);
    } */

    
    $hasil = $this->reseller->listIncPayByIDV2($data['idmember'], $exclude_inv);
    if($hasil !== null) {
      $res = jsonTrueResponse($hasil);
    }
    echo json_encode($res);
  }

  //$route['reseller/saveregister'] = 'transaction/reseller/saveRegister';
  public function saveRegister() {
    $data = $this->input->post(NULL, TRUE);

    if($data['kode_reseller'] === "" || $data['kode_reseller'] === null) {
      $res = jsonFalseResponse("Kode Reseller harus diisi..");
      echo json_encode($res);
      return;
    }
    
    $checkReseller = $this->reseller->getDataReseller($data['kode_reseller']);
    if($checkReseller === null) {
      $res = jsonFalseResponse("Kode Reseller tidak ditemukan..");
      echo json_encode($res);
      return;
    }

    $save = $this->reseller->saveRegister($data);
    echo json_encode($save);
  }

  //$route['reseller/updateInv/(:any)'] = 'transaction/reseller/updateInv/$1';
  public function updateInv($registerno) {
    $data['result'] = $this->reseller->getDataRegisterByID($registerno);

    $data['ins'] = "1";
    $data['submit_value'] = "Simpan Transaksi";
    $data['detail'] = null;
    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 1;
    $data['prd_voucher'] = 0;
    $data['jenis_bayar'] = "";

    if ($data['ins'] == "2") {
      $data['submit_value'] = "Update Transaksi";
    }

    //$jumPrd = count($data['detail']);
    /* $data['tot_dp'] = $data['header'][0]->tdp;
    $data['tot_bv'] = $data['header'][0]->tbv;
    $data['jum_rec'] = $jumPrd; */

    $data['start_tabidx'] = 1;

    $data['listPayType'] = $this->reseller->listActivePayType();
    $this->load->view($this->folderView.'updateInv', $data);
    $this->load->view($this->folderView.'viewPaymentForm', $data);
  }

  //$route['reseller/listInv/(:any)'] = 'transaction/reseller/listInv/$1';
  public function listInv($registerno) {
    $this->load->model('transaction/be_trans_klink_model', 'm_trans');
    $data['result'] = $this->m_trans->getOrdivByRegNo($registerno);

    if ($data['result']['response'] == "false") {
      echo setErrorMessage("No Register tidak ditemukan..");
    } else {
      $this->load->view($this->folderView.'getTrxByRegisterno', $data);
    }
  }

  //$route['reseller/previewInvReseller'] = 'transaction/reseller/previewInvReseller';
  public function previewInvReseller() {
    $data = $this->input->post(NULL, TRUE);
    
    $arr['header']['registerno'] = $data['registerno'];
    //$data['result'] = $this->m_trans->getOrdivByRegNo($arr['header']['registerno']);
    
    $arr['header']['pricecode'] = $data['pricecode'];
    $arr['header']['pricecode_desc'] = $data['pricecode_desc'];
    $arr['header']['kode_reseller'] = $data['kode_reseller'];
    $arr['header']['nama_reseller'] = $data['nama_reseller'];
    $arr['header']['registerdt'] = $data['registerdt'];
    $arr['header']['dfno'] = $data['dfno'];
    $arr['header']['nama_member'] = $data['nama_member'];
    $arr['header']['loccd'] = "BID06";
    $arr['header']['whcd'] = $data['whcd'];
    $arr['header']['whnm'] = $data['whnm'];
    $arr['header']['ship'] = $data['ship'];
    $arr['header']['ship_desc'] = $data['ship_desc'];
    $arr['header']['createnm'] = $data['createnm'];
    $arr['header']['bnsperiod'] = $data['bnsperiod'];

    $arr['header']['total_dp_dist'] = 0;
    $arr['header']['total_dp_cust'] = 0;
    $arr['header']['total_bv'] = 0;
    $arr['header']['total_payment'] = 0;
    $arr['produk'] = array();

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');

    $prd = array();
    for($i=0; $i < count($data['prdcd']); $i++) {
      $checkValidPrd = $this->reseller->showProductPriceForPvr($data['prdcd'][$i], $data['pricecode'], $data['jenis']);
      if ($checkValidPrd['response'] == "false") {
        echo json_encode($this->jsonFalseResponse($checkValidPrd['message']));
        return;
      }

      $harga = $checkValidPrd['arraydata'][0];
      $arr['header']['total_dp_dist'] += $data['jum'][$i] * $harga->dp;
      $arr['header']['total_dp_cust'] += $data['jum'][$i] * $harga->cp;
      $arr['header']['total_bv'] += $data['jum'][$i] * $harga->bv;

      $prdx = array(
        "prdcd" => $data['prdcd'][$i],
        "prdnm" => $harga->prdnm,
        "qtyord" => $data['jum'][$i],
        "qtyship" => 0,
        "qtyremain" => 0,
        "dp" => $harga->dp,
        "cp" => $harga->cp,
        "pv" => $harga->bv,
        "bv" => $harga->bv,
        "pricecode" => $arr['header']['pricecode']
      );

      array_push($arr['produk'], $prdx);
    }

    $nilai_dp = $arr['header']['total_dp_cust'];

    $arr['trf'] = 0;
    $arr['non_trf'] = 0;
    $arr['total_jenis_pembayaran'] = 0;
    $arr['payment'] = array();
    for($i=0; $i < count($data['byrPayType']); $i++) {

      if($data['byrPayType'][$i] == "03") {
        $arr['trf'] += $data['byrAmount'][$i];
      } else {
        $arr['non_trf'] += $data['byrAmount'][$i];
      }

      $pay = array(
        "paytype" => $data['byrPayType'][$i],
        "payTypeName" => $data['byrPayName'][$i],
        "docno" => $data['byrIncPay'][$i],
        "payamt" => $data['byrAmount'][$i],
        "trcd2" => $arr['header']['registerno'],
        "vchtype" => "C",
      );
      $arr['header']['total_payment'] += $data['byrAmount'][$i];
      array_push($arr['payment'], $pay);
      $arr['total_jenis_pembayaran'] += 1;
    } 

    $arr['new_payment'] = array();

    $nilai_dp = $arr['header']['total_dp_cust'];
    $reseller_fee = $nilai_dp - $arr['header']['total_dp_dist'];
    $arr['header']['reseller_fee'] = $reseller_fee;
    $sisa_reseller_fee = $reseller_fee;
    $x = 0;
    $pembagian = round($arr['header']['reseller_fee'] / $arr['total_jenis_pembayaran']);
    //echo " Pembagian : ".$pembagian;
    $sisa_pembagian = 0;
    $ketemu = 0;
    foreach($arr['payment'] as $dta) {

      /* if($dta['payamt'] > $arr['header']['reseller_fee'] && $ketemu === 0) {
        $nilai_inc = $dta['payamt'] - $arr['header']['reseller_fee'];
        $pay2 = array(
          "paytype" => $dta['paytype'],
          "payTypeName" => $dta['payTypeName'],
          "docno" => $dta['docno'],
          "payamt" => $nilai_inc,
          "trcd2" => $arr['header']['registerno'],
          "vchtype" => "C",
        );
        array_push($arr['new_payment'], $pay2);

        $pay3 = array(
          "paytype" => "RF02",
          "payTypeName" => "Reseler Fee",
          "docno" => $dta['docno'],
          "payamt" => $arr['header']['reseller_fee'],
          "trcd2" => $arr['header']['registerno'],
          "vchtype" => "C",
        );
        array_push($arr['new_payment'], $pay3);
        $ketemu = 1;
      } else { */
        $pay2 = array(
          "paytype" => $dta['paytype'],
          "payTypeName" => $dta['payTypeName'],
          "docno" => $dta['docno'],
          "payamt" => $dta['payamt'],
          "trcd2" => $arr['header']['registerno'],
          "vchtype" => "C",
        );
        array_push($arr['new_payment'], $pay2);
      //}

      

      /*$pay3 = array(
        "paytype" => "RF02",
        "payTypeName" => "Reseler Fee",
        "docno" => $dta['docno'],
        "payamt" => $pembagian,
        "trcd2" => $arr['header']['registerno'],
        "vchtype" => "C",
      );
      array_push($arr['new_payment'], $pay3); */
      //$sisa_pembagian = $reseller_fee - $pembagian;

    }
    
    if($arr['header']['total_dp_dist'] != $arr['header']['total_payment']) {
      echo "Total Pembayaran tidak sama dengan total harga reseller..";
      echo "<input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm2',' .nextForm1')\"/>";
      return;
    }
    
    /* echo "<pre>";
    print_r($arr);
    echo "</pre>"; */


    $save = $this->reseller->simpanTrxInvoiceReseller($arr);
    echo json_encode($save);
    

    //echo "<input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm2',' .nextForm1')\"/>";
 
    //$this->load->view($this->folderView.'saveInvoice', $save);

    
  }

  //$route['reseller/product/pvr/check'] = 'transaction/reseller/showProductPriceForPvr';
  function showProductPriceForPvr() {
    if ($this->username == null) {
      $err = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
      echo json_encode($err);
      return;
    }

    $productcode = $this->input->post('productcode');
    $prdcdcode = strtoupper($productcode);
    $pricecode = $this->input->post('pricecode');
    $jenis = $this->input->post('jenis');
    $jenis_promo = $this->input->post('jenis_promo');

    
    //Jika Promo adalah pre order k-ion nano prem 8
    if($jenis_promo == "PRK") {
      $listPrdYgBoleh = array(
          "PRKP8BG", "PRKP8BM", "PRKP8BR", "PRKP8TC", "PRKP8WM"
      );

      if (!in_array($prdcdcode, $listPrdYgBoleh)) {
          $err = jsonFalseResponse("Hanya boleh input kode produk Pre Order Premium 8");
          echo json_encode($err);
          return;
      }

      $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
      $data = $this->reseller->showProductPriceForPvr($prdcdcode, $pricecode, $jenis, $jenis_promo);
      echo json_encode($data);
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data = $this->reseller->showProductPriceForPvr($prdcdcode, $pricecode, $jenis);
    echo json_encode($data);

  }

  //$route['reseller/invReseller/(:any)'] = 'transaction/reseller/invReseller/$1';
  public function invReseller($inv) {

  }

  //$route['reseller/saveInvReseller'] = 'transaction/reseller/saveInvReseller';
  public function saveInvReseller() {
    $data = $this->input->post(NULL, TRUE);
    $input = json_decode($data['inputData'], true);
    
    echo "<pre>";
    print_r($input);
    echo "</pre>";

    $save = $this->reseller->simpanTrxInvoiceReseller($input);

  }

  //$route['reseller/inv/print'] = 'transaction/reseller/printInv';
  public function printInv() {
    $data = $this->input->post(NULL, TRUE);
    $incpayment = set_list_array_to_string($data['cek']);

    $count = count($data['cek']);
    $data['arr'] = array();
    for($i=0; $i < $count; $i++) {
      $result = $this->reseller->getTrxInvoice($data['cek'][$i]);
      array_push($data['arr'], $result);
    }

    /* echo "<pre>";
    print_r($data['arr']);
    echo "</pre>"; */

    $this->load->view($this->folderView.'printInv', $data);
    
  }

  //$route['reseller/produk'] = 'transaction/reseller/listPrd';
  public function listPrd() {
    if($this->username != null) {
			$data['form_header'] = "Data Reseller";
			$data['form_action'] = base_url('reseller/produk');
			$data['icon'] = "icon-search";
			$data['form_reload'] = 'reseller/produk';
			$this->setTemplate($this->folderView.'productSearch', $data); 
		} else {
			echo sessionExpireMessage(false);
		}
  }

  //$route['reseller/produk/list'] = 'transaction/reseller/listPrdAct';
  public function listPrdAct() {
    $data = $this->input->post(NULL, TRUE);
    if($data['param'] == "nama_reseller") {
      $result = $this->reseller->listNamaReseller($data);
      $this->showTableNamaReseller($result);
    } else {
      $result = $this->reseller->listPrdReseller($data);
      $this->showTable($result);
    }
  }

  //$route['reseller/produk/all'] = 'transaction/reseller/listAllPrd';
  public function listAllPrd() {
    $result = $this->reseller->listPrdReseller();
    //$this->reseller->searchResellerByParam($data);

    $this->showTable($result);
  }

  public function showTable($result) {
    $arrTable = array(
        "id" => "tbl1",
        "header" => "List Produk Reseller",
        "column" => array(
            "Kode", "Nama Produk", "BV", "Harga Wil A", "Harga wil B", "Harga TL"
        ),

        "columnAlign" => array(
            "center", "left", "right", "right", "right", "right" 
        ),
        "recordStyle" => array(
          "", "","money","","money","money","money"
      ),
        "record" => $result
    );
    echo generateTable($arrTable);
  }

  //$route['reseller/name/all'] = 'transaction/reseller/listAllReseller';
  public function listAllReseller() {
    $result = $this->reseller->listNamaReseller();
    //$this->reseller->searchResellerByParam($data);

    $this->showTableNamaReseller($result);
  }

  //$route['reseller/name/list'] = 'transaction/reseller/listResellerAct';
  public function listResellerAct() {
    $data = $this->input->post(NULL, TRUE);
    $result = $this->reseller->listNamaReseller($data);
    $this->showTableNamaReseller($result);
  }


  public function showTableNamaReseller($result) {
    $arrTable = array(
        "id" => "tbl1",
        "header" => "List Produk Reseller",
        "column" => array(
            "Kode Reseller", "Nama Reseller", "Kode Referal", "Nama Referal"
        ),

        "columnAlign" => array(
            "center", "left", "center", "left"
        ),
        "record" => $result
    );
    echo generateTable($arrTable);
  }
}