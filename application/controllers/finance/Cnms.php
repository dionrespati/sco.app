<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cnms extends MY_Controller {
  public function __construct() {
    parent::__construct();
    $this->folderView = "finance/cnms/";
    $this->load->model('finance/cnms_model', 'cn');
  }

  //$route['bo/cnmsn/register'] = 'finance/cnms/formRegister';  
  public function formRegister() {
    $data['form_header'] = "Transaksi CN/MS";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'bo/cnmsn/register';
		//echo "stk : ".$this->stockist;
		if($this->username != null) {	
      $data['from'] = date("Y-m-d");
      $data['to'] = date("Y-m-d");
      $data['sc_dfno'] = $this->stockist;
			$this->setTemplate($this->folderView.'registerCn', $data); 
		} else {
			//echo sessionExpireMessage(false);
			$this->setTemplate('includes/inline_login', $data);
		} 
  }

  //$route['bo/cnmsn/newregister'] = 'finance/cnms/newregister';
  public function newregister() {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->model('transaction/reseller_model', 'reseller');

    $data['head_form'] = "New Register";
    $data['stk'] = "";
    $data['stkname'] = "";
    $data['pricecode'] = "";
    $data['listWh'] = $this->reseller->listWh();
    $data['currentperiod'] = $this->cn->getCurrentPeriod();
    $this->load->view($this->folderView.'newRegister', $data);
  }

  //$route['bo/cnmsn/list'] = 'finance/cnms/listTrx';
  public function listTrx() {
    $data = $this->input->post(NULL, TRUE);
    if($data['searchby'] == "invoiceno") {
        $data['trxno'] = $data['paramValue'];
        $this->load->model('finance/be_trans_klink_model', 'm_trans');
        $data['cn_header'] = $this->m_trans->getCNheader("a.invoiceno", $data['trxno']);

        if($data['cn_header'] == null) {
          echo setErrorMessage("No CN $data[trxno] tidak ditemukan");
        } else {
          $data['urut_cn'] = "cn";
          $data['back_button'] = "";
          $data['onlinetype'] = $data['cn_header'][0]->onlinetype;
          if($this->username === "DION") {
            $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($data['trxno'], $data['onlinetype']);
          } else {
            $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($data['trxno'], $data['onlinetype']);
          }
          $data['cn_ip'] = $this->cn->getIncPayByCnno($data['trxno']);
          //$urlDetailTtp = "transklink/list/batchno/".$data['cn_header'][0]->batchno;
          //$data['list_ttp'] = "<input type=\"button\" class=\"btn btn-mini btn-success\" value=\"View List TTP\" onclick=\"All.ajaxShowDetailonNextForm('".$urlDetailTtp."')\" />";
          //$no_cn = $data['cn_header'][0]->invoiceno;
          if($data['cn_header'][0]->onlinetype == "O") {
            $data['list_ttp'] = $this->m_trans->listTtpById("csno", $data['trxno']);
          } else {
            $data['list_ttp'] = $this->m_trans->listTtpById("trcd2", $data['trxno']);
          }
          $data['back_button'] = "";
          $this->load->view($this->folderView.'trxByCnNo', $data);
        }
    } else if($data['searchby'] == "registerno") {

      $data['trxno'] = $data['paramValue'];
      $this->load->model('finance/be_trans_klink_model', 'm_trans');
      $data['result'] = $this->m_trans->getOrdivByRegNo($data['trxno']);

      if ($data['result']['response'] == "false") {
        echo setErrorMessage("No Register tidak ditemukan..");
      } else {
        $this->load->view($this->folderView.'getTrxByRegisterno', $data);
      }
    } else if($data['searchby'] == "batchscno") {
      $data['trxno'] = $data['paramValue'];
        $this->load->model('finance/be_trans_klink_model', 'm_trans');
        $data['cn_header'] = $this->m_trans->getCNheader("a.batchscno", $data['trxno']);

        if($data['cn_header'] == null) {
          echo setErrorMessage("No CN $data[trxno] tidak ditemukan");
        } else {
          $data['urut_cn'] = "cn";
          $data['back_button'] = "";
          $data['onlinetype'] = $data['cn_header'][0]->onlinetype;
          $no_cnms = $data['cn_header'][0]->trcd;
          if($this->username === "DION") {
            $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($no_cnms, $data['onlinetype']);
          } else {
            $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($no_cnms, $data['onlinetype']);
          }
          
          $data['cn_ip'] = $this->cn->getIncPayByCnno($no_cnms);
          //$urlDetailTtp = "transklink/list/batchno/".$data['cn_header'][0]->batchno;
          //$data['list_ttp'] = "<input type=\"button\" class=\"btn btn-mini btn-success\" value=\"View List TTP\" onclick=\"All.ajaxShowDetailonNextForm('".$urlDetailTtp."')\" />";
          //$no_cn = $data['cn_header'][0]->invoiceno;
          if($data['cn_header'][0]->onlinetype == "O") {
            $data['list_ttp'] = $this->m_trans->listTtpById("csno", $no_cnms);
          } else {
            $data['list_ttp'] = $this->m_trans->listTtpById("trcd2", $no_cnms);
          }
          
          $this->load->view($this->folderView.'trxByCnNo', $data);
        }
    } else if($data['searchby'] == "receiptno") {
      
    } else {
      $data['hasil'] = $this->cn->searchRegisterByParam($data);
      if($data['hasil'] === null) {
        echo setErrorMessage("Data tidak ditemukan..");
      } else {
        $this->load->view($this->folderView.'listRegister', $data);
      }
    }
    
  }

  //$route['bo/cnmsn/id/(:any)'] = 'finance/cnms/viewCn/$1';
  public function viewCn($cn) {
    $data['trxno'] = $cn;
    $this->load->model('finance/be_trans_klink_model', 'm_trans');
    $data['cn_header'] = $this->m_trans->getCNheader("a.invoiceno", $data['trxno']);

    if($data['cn_header'] == null) {
      echo setErrorMessage("No CN $data[trxno] tidak ditemukan");
    } else {
      $data['urut_cn'] = "cn";
      $data['back_button'] = "";
      $data['onlinetype'] = $data['cn_header'][0]->onlinetype;
      if($this->username === "DION") {
        $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($data['trxno'], $data['onlinetype']);
      } else {
        $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($data['trxno'], $data['onlinetype']);
      }
      $data['cn_ip'] = $this->cn->getIncPayByCnno($data['trxno']);
      if($data['cn_header'][0]->onlinetype == "O") {
        $data['list_ttp'] = $this->m_trans->listTtpById("csno", $data['trxno']);
      } else {
        $data['list_ttp'] = $this->m_trans->listTtpById("trcd2", $data['trxno']);
      }
      
      $data['back_button'] = "All.back_to_form(' .nextForm1',' .mainForm')";
      $this->load->view($this->folderView.'trxByCnNo', $data);
    }
  }

  //$route['bo/cnmsn/edit/(:any)'] = 'finance/cnms/viewCnWithEdit/$1';
  public function viewCnWithEdit($cn) {
    $data['trxno'] = $cn;
    $this->load->model('finance/be_trans_klink_model', 'm_trans');
    $data['cn_header'] = $this->m_trans->getCNheader("a.invoiceno", $data['trxno']);

    if($data['cn_header'] == null) {
      echo setErrorMessage("No CN $data[trxno] tidak ditemukan");
    } else {
      $data['urut_cn'] = "cn";
      $data['back_button'] = "";
      $data['onlinetype'] = $data['cn_header'][0]->onlinetype;
      if($this->username === "DION") {
        $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($data['trxno'], $data['onlinetype']);
      } else {
        $data['cn_prod'] = $this->m_trans->getCNSumProductCheck($data['trxno'], $data['onlinetype']);
      }
      $data['cn_ip'] = $this->cn->getIncPayByCnno($data['trxno']);
      if($data['cn_header'][0]->onlinetype == "O") {
        $data['list_ttp'] = $this->m_trans->listTtpById("csno", $data['trxno']);
      } else {
        $data['list_ttp'] = $this->m_trans->listTtpById("trcd2", $data['trxno']);
      }
      
      $data['back_button'] = "All.back_to_form(' .nextForm1',' .mainForm')";
      $data['edit'] = true;
      $this->load->view($this->folderView.'trxByCnNo', $data);
    }
  }

  //$route['bo/cnmsn/list'] = 'finance/cnms/listTrx';

  //$route['bo/cnmsn/register/save'] = 'finance/cnms/saveRegister';
  public function saveRegister() {
    $data = $this->input->post(NULL, TRUE);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    if($data['stk'] == "") {
      echo json_encode(jsonFalseResponse("Kode Stokis harus diisi.."));
      return;
    }
    $res = $this->cn->saveRegister($data);
    echo json_encode($res);
  }

  //$route['bo/cnmsn/updateInv/(:any)'] = 'finance/cnms/updateInv/$1';
  public function updateInv($registerno) {
    $data['result'] = $this->cn->getDataRegisterByID($registerno);
    if($data['result'] === null) {
      echo setErrorMessage("No Register tidak ada..");
      backToMainForm();
      return;
    }

    $data['onlinetype'] = $data['result'][0]->onlinetype;
    if($data['onlinetype'] === "O") {
      $kodestk = $data['result'][0]->dfno;
      $bnsperiod = $data['result'][0]->bnsperiod;
      
      $data['username'] = $this->username;
      if($this->username == "DION" || $this->username == "BID06") {
        $data['listSSR'] = $this->cn->listSSRBelumJadiCnV2($kodestk, $bnsperiod);
        $data['listCN'] = $this->cn->listCNDariRegister($registerno);
        $this->load->view($this->folderView.'listSSRtoProsesV2', $data);
      } else {
        $data['listSSR'] = $this->cn->listSSRBelumJadiCn($kodestk, $bnsperiod);
        $data['listCN'] = $this->cn->listCNDariRegister($registerno);
        $this->load->view($this->folderView.'listSSRtoProsesV2', $data);
      }
      
    } else {
      $kodestk = $data['result'][0]->dfno;
      $bnsperiod = $data['result'][0]->bnsperiod;
      
      $data['username'] = $this->username;
      //if($this->username == "DION" || $this->username == "BID06") {
        $data['listSSR'] = $this->cn->listSSRBelumJadiCn($kodestk, $bnsperiod);
        $data['listCN'] = $this->cn->listCNDariRegister($registerno);
        $this->load->view($this->folderView.'listManualtoProsesV2', $data);
      /* } else {
        $data['listSSR'] = $this->cn->listSSRBelumJadiCn($kodestk, $bnsperiod);
        $data['listCN'] = $this->cn->listCNDariRegister($registerno);
        $this->load->view($this->folderView.'listSSRtoProses', $data);
      } */
    }
    

    /* $data['ins'] = "1";
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

    $data['start_tabidx'] = 1;

    $data['listPayType'] = $this->reseller->listActivePayType();
    $this->load->view($this->folderView.'updateInv', $data);
    $this->load->view($this->folderView.'viewPaymentForm', $data); */
  }

  //$route['bo/cnmsn/rekapcn/(:any)'] = 'finance/cnms/rekapCN/$1';
  //$route['bo/cnmsn/rekapcn/(:any)/(:any)'] = 'finance/cnms/rekapCN/$1/$2';
  public function rekapCN($registerno, $batchno) {
    $data['reg'] = $this->cn->getDataRegisterByID($registerno);
    $data['rekapPrd'] = $this->cn->rekapProdukSSR($batchno);
    if($data['rekapPrd'] === null) {
      echo setErrorMessage("Produk tidak ada..");
      backToNextForm();
      return;
    }

    $data['listVch'] = $this->cn->checkIfAnyVoucherCashPrd($batchno);
    $this->load->model('transaction/reseller_model', 'reseller');
    $data['listPayType'] = $this->reseller->listActivePayType();
    $data['batchno'] = $batchno;
    //if($this->username == "DION" || $this->username == "BID06") {
    
    if($data['listVch'] !== null) {  
      $this->load->view($this->folderView.'rekapCNV2', $data);
    } else {
      $this->load->view($this->folderView.'rekapCN', $data);
    }
  }

  //$route['bo/cnmsn/listInv/(:any)'] = 'finance/cnms/listInv/$1';
  public function listInv($registerno) {
    $this->load->model('transaction/be_trans_klink_model', 'm_trans');
    $data['result'] = $this->m_trans->getOrdivByRegNo($registerno);

    if ($data['result']['response'] == "false") {
      echo setErrorMessage("No Register tidak ditemukan..");
    } else {
      $data['back_button'] = "All.back_to_form(' .nextForm1',' .mainForm')";
      $this->load->view($this->folderView.'getTrxByRegisterno', $data);
    }
  }

  //$route['bo/cnmsn/incById'] = 'finance/cnms/getIncPayById'
  public function getIncPayById() {
    $data = $this->input->post(NULL, TRUE);
    $res = $this->cn->getIncomingByID($data['incPay'], $data['dfno']);
    if($res === null) {
      $resp = jsonFalseResponse("No Incoming Payment tidak ditemukan..");
    } else {
      $resp = jsonTrueResponse($res);
    }
    echo json_encode($resp);
  }

  //$route['bo/cnmsn/listIncPayV2'] = 'finance/cnms/listIncPayV2';
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

    
    $hasil = $this->cn->listIncPayByIDV2($data['dfno'], $exclude_inv);
    if($hasil !== null) {
      $res = jsonTrueResponse($hasil);
    }
    echo json_encode($res);
  }

  //$route['bo/cnmsn/cn/save'] = 'finance/cnms/saveCN';
  public function saveCN() {
    $data = $this->input->post(NULL, TRUE);

    $rekapSSR = $this->cn->rekapSSR($data['nossr']);
    $rekap_total_ssr_header = (int) $rekapSSR[0]->total_dp;
    $data['tdp'] = (int) $rekapSSR[0]->total_dp;
    $data['tbv'] = (int) $rekapSSR[0]->total_bv;

    $total_nilai_ssr = (int) $data['total_ssr'];
    /* 
    echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    if($rekap_total_ssr_header !== $total_nilai_ssr) {
      $resp = jsonFalseResponse("Total DP rekap produk dan summary tidak sama");
      echo json_encode($resp);
      return;
    }
    
    $total_bayar = 0;
    for($i=0; $i < count($data['byrPayType']); $i++) {
      $total_bayar += $data['byrAmount'][$i]; 
    }

    if($data['total_ssr'] > $total_bayar) {
      $resp = jsonFalseResponse("Selisih pembayaran, Total SSR/MSR : $data[total_ssr], Total Pembayaran : $total_bayar");
      echo json_encode($resp);
      return;
    }

    $hasil = $this->cn->saveCNMS($data);
    echo json_encode($hasil);
  }


  //$route['bo/cnmsn/printv/(:any)'] = 'finance/cnms/printCn/$1';
  public function printCn($no_cn) {
    $arrx = array();
    array_push($arrx, $no_cn);
    $data['result'] = $this->cn->getDataCNtoPrint($arrx);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    if($this->username == "DION") {
      $this->load->view($this->folderView.'printCnToTxtV2', $data);
    } else {
      //$this->load->view($this->folderView.'printCnToTxt', $data);
      $this->load->view($this->folderView.'printCnToTxtV2', $data);
    }
  }

  //$route['bo/cnmsn/print'] = 'finance/cnms/printCnV';
  public function printCnV() {
    $data = $this->input->post(NULL, TRUE);
    $data['result'] = $this->cn->getDataCNtoPrint($data['cekx']);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    if($this->username == "DION") {
      $this->load->view($this->folderView.'printCnToTxtV2', $data);
    } else {
      //$this->load->view($this->folderView.'printCnToTxt', $data);
      $this->load->view($this->folderView.'printCnToTxtV2', $data);
    }
    
  }

  //$route['bo/cnmsn/pvr/approve'] = 'finance/cnms/pvrApprove';
  public function pvrApprove() {
    $data = $this->input->post(NULL, TRUE);  
    $data['result'] = $this->cn->pvrApprove($data['batchno'], $data['dfno']);
    echo json_encode($data['result']);
  }

  //$route['bo/cnmsn/manual'] = 'finance/cnms/cnmsManual';
  public function cnmsManual() {
    $data['form_header'] = "Transaksi CN/MS Manual";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'bo/cnmsn/manual';
		//echo "stk : ".$this->stockist;
		if($this->username != null) {	
      $data['from'] = date("Y-m-d");
      $data['to'] = date("Y-m-d");
      $data['sc_dfno'] = $this->stockist;

      $data['ins'] = "1";
      $data['submit_value'] = $data['ins'] == "1" ? "Simpan Transaksi" : "Update Transaksi";

      $data['tot_dp'] = 0;
      $data['tot_bv'] = 0;
      $data['jum_rec'] = 1;
      
      $data['form_action'] = "bo/cnmsn/manual/save";
      $data['prd_voucher'] = 0;
      $data['jenis_bayar'] = "bo";
      $data['start_tabidx'] = 4;
      $data['jenis_promo'] = "reguler";
			$this->setTemplate($this->folderView.'cnmsManual', $data); 
		} else {
			//echo sessionExpireMessage(false);
			$this->setTemplate('includes/inline_login', $data);
		} 
  }
  
  //$route['bo/cnmsn/formedit/(:any)'] = 'finance/cnms/editTtpManual/$1';
  public function editTtpManual($id) {
    $data['resEdit'] = $this->cn->getDataTtpByTrcd($id);

    /* $data['form_header'] = "Transaksi CN/MS Manual";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'bo/cnmsn/manual'; */
		//echo "stk : ".$this->stockist;


		if($this->username != null) {	
      $data['from'] = date("Y-m-d");
      $data['to'] = date("Y-m-d");
      $data['sc_dfno'] = $this->stockist;

      $data['ins'] = "2";
      $data['submit_value'] = $data['ins'] == "1" ? "Simpan Transaksi" : "Update Transaksi";

      $data['tot_dp'] = $data['resEdit']['header'][0]->tdp;
      $data['tot_bv'] = $data['resEdit']['header'][0]->tbv;
      $data['jum_rec'] = count($data['resEdit']['detail']);;
      
      $data['form_action'] = "bo/cnmsn/manual/save";
      $data['prd_voucher'] = 0;
      $data['jenis_bayar'] = "bo";
      $data['start_tabidx'] = 4;
      $data['jenis_promo'] = "reguler";
			$this->load->view($this->folderView.'cnmsManualEdit', $data); 
		} else {
			//echo sessionExpireMessage(false);
			$this->setTemplate('includes/inline_login', $data);
		} 
  }

  //$route['bo/cnmsn/manual/check/(:any)'] = 'finance/cnms/checkCNManual/$1';
  public function checkCnManual($id) {
    $res = $this->cn->getInfoCNmanual($id);
    echo json_encode($res);
  }

  //$route['bo/cnms/product/check] = 'finance/cnms/checkProdukCNManual';
  public function checkProdukCNManual() {
    $data = $this->input->post(NULL, TRUE);
    $res = $this->cn->showProductPriceForPvr($data);
    echo json_encode($res);
  }

  //$route['bo/cnmsn/manual/save'] = 'finance/cnms/saveCnMsManual';
  public function saveCnMsManual() {
    $data = $this->input->post(NULL, TRUE);
    
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');

    //check apakah produk ada yang kosong
     $jum = count($data['prdcd']);
    if ($jum == 0) {
      echo json_encode(jsonFalseResponse("Produk tidak boleh kosong.."));
      return;
    }

    if($data['dfno'] === "" || $data['dfno'] === " " || $data['dfno'] === null) {
      echo json_encode(jsonFalseResponse("ID Member harus diisi.."));
      return;
    }

    if($data['orderno'] === "" || $data['orderno'] === " " || $data['orderno'] === null) {
      echo json_encode(jsonFalseResponse("No TTP harus diisi.."));
      return;
    }

    if($data['cnno'] === "" || $data['cnno'] === " " || $data['cnno'] === null) {
      echo json_encode(jsonFalseResponse("No CN harus diisi.."));
      return;
    }

    //cek valid distributor
    $ifMemberExist = $this->m_sales_stk->getValidDistributor($data['dfno']);
    if ($ifMemberExist == null) {
      echo json_encode(jsonFalseResponse("ID Member tidak valid.."));
      return;
    }

    //cek no TTP double
    if ($data['ins'] == "1") {
      $arr = array("table" => "newtrh",
        "param" => "orderno",
        "value" => $data['orderno'],
        "db" => "klink_mlm2010",
      );
      $checkOrderno = $this->m_sales_stk->checkExistingRecord($arr);
      //CHECK apakah ORDERNO double
      if ($checkOrderno != null) {
        echo jsonFalseResponse("No TTP sudah ada di database..");
        return;
      }
    } 

    
    $check = $this->cn->getInfoCNmanual($data['cnno']);
     if($check['response'] === "false") {
      echo $check;
      return;
    }

    $resp = $check['arrayData'];

    $data['pricecode'] = $resp[0]['pricecode'];
    $data['branch'] = $resp[0]['branch'];
    $data['whcd'] = $resp[0]['whcd'];
    $data['receiptno'] = $resp[0]['receiptno'];
    $data['batchno'] = $resp[0]['batchno'];
    $data['ship'] = $resp[0]['ship'];
    $data['shipto'] = $resp[0]['shipto'];

    $sub_tot_bv = 0;
    $sub_tot_dp = 0;
    $total_dp = 0;
    $total_bv = 0;

    for ($i = 0; $i < $jum; $i++) {
      if($data['prdcd'][$i] !== "") {
        $prdcd = $data['prdcd'][$i];
        $qty = $data['jum'][$i];

        $arr['productcode'] = $data['prdcd'][$i]; 
        $arr['pricecode'] = $data['pricecode']; 
        $arr['jenis'] = $data['jenis_bayar']; 
        $arr['jenis_promo'] = $data['jenis_promo'];
        $arr['cnno'] = $data['cnno'];
        $arr['qty'] = $data['jum'][$i];

        $prdArr = $this->cn->showProductPriceForPvr($arr);
        if ($prdArr['response'] == "false") {
          echo json_encode($prdArr);
          return;
        }

        $resPrd = $prdArr['arraydata'][0];
        $data['harga'][$i] = $resPrd->dp;
        $data['poin'][$i] = $resPrd->bv;

        $data['sub_tot_bv'][$i] = $qty * $resPrd->bv;
        $data['sub_tot_dp'][$i] = $qty * $resPrd->dp;

        $total_dp += $qty * $resPrd->dp;
        $total_bv += $qty * $resPrd->bv;
      }
    }  

    $data['total_all_bv'] = (float) $total_bv;
    $data['total_all_dp'] = (float) $total_dp;
    $data['nourut'] = $resp[0]['totinv'] + 1;
    $data['trtype'] = "SB1";
    $data['ttptype'] = "";
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    //$res = $this->cn->simpanCNManual($data);
    $arrdata = array(
      "cnno" => $data['cnno'],
    );

    $cek_seQ = $this->cn->cek_seQ($data['jenis_bayar']);
    $trcd = $this->cn->get_idno($data['jenis_bayar']);

    $res = $this->cn->simpanCNManual($data, $trcd);
    echo json_encode($res);
  }

  //$route['bo/cnmsn/manual/update'] = 'finance/cnms/updateCnMSManual';
  public function updateCnMSManual() {
    $data = $this->input->post(NULL, TRUE);
    
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');

    //check apakah produk ada yang kosong
     $jum = count($data['prdcd']);
    if ($jum == 0) {
      echo json_encode(jsonFalseResponse("Produk tidak boleh kosong.."));
      return;
    }

    if($data['dfno'] === "" || $data['dfno'] === " " || $data['dfno'] === null) {
      echo json_encode(jsonFalseResponse("ID Member harus diisi.."));
      return;
    }

    if($data['orderno'] === "" || $data['orderno'] === " " || $data['orderno'] === null) {
      echo json_encode(jsonFalseResponse("No TTP harus diisi.."));
      return;
    }

    if($data['cnno'] === "" || $data['cnno'] === " " || $data['cnno'] === null) {
      echo json_encode(jsonFalseResponse("No CN harus diisi.."));
      return;
    }

    //cek valid distributor
    $ifMemberExist = $this->m_sales_stk->getValidDistributor($data['dfno']);
    if ($ifMemberExist == null) {
      echo json_encode(jsonFalseResponse("ID Member tidak valid.."));
      return;
    }

    //cek no TTP double
    if ($data['ins'] == "1") {
      $arr = array("table" => "newtrh",
        "param" => "orderno",
        "value" => $data['orderno'],
        "db" => "klink_mlm2010",
      );
      $checkOrderno = $this->m_sales_stk->checkExistingRecord($arr);
      //CHECK apakah ORDERNO double
      if ($checkOrderno != null) {
        echo jsonFalseResponse("No TTP sudah ada di database..");
        return;
      }
    } 

    
    $check = $this->cn->getInfoCNmanual($data['cnno']);
     if($check['response'] === "false") {
      echo $check;
      return;
    }

    $resp = $check['arrayData'];

    $data['pricecode'] = $resp[0]['pricecode'];
    $data['branch'] = $resp[0]['branch'];
    $data['whcd'] = $resp[0]['whcd'];
    $data['receiptno'] = $resp[0]['receiptno'];
    $data['batchno'] = $resp[0]['batchno'];
    $data['ship'] = $resp[0]['ship'];
    $data['shipto'] = $resp[0]['shipto'];

    $sub_tot_bv = 0;
    $sub_tot_dp = 0;
    $total_dp = 0;
    $total_bv = 0;

    for ($i = 0; $i < $jum; $i++) {
      if($data['prdcd'][$i] !== "") {
        $prdcd = $data['prdcd'][$i];
        $qty = $data['jum'][$i];

        $arr['productcode'] = $data['prdcd'][$i]; 
        $arr['pricecode'] = $data['pricecode']; 
        $arr['jenis'] = $data['jenis_bayar']; 
        $arr['jenis_promo'] = $data['jenis_promo'];
        $arr['cnno'] = $data['cnno'];
        $arr['qty'] = $data['jum'][$i];

        $prdArr = $this->cn->showProductPriceForPvr($arr, "update");
        if ($prdArr['response'] == "false") {
          echo json_encode($prdArr);
          return;
        }

        $resPrd = $prdArr['arraydata'][0];
        $data['harga'][$i] = $resPrd->dp;
        $data['poin'][$i] = $resPrd->bv;

        $data['sub_tot_bv'][$i] = $qty * $resPrd->bv;
        $data['sub_tot_dp'][$i] = $qty * $resPrd->dp;

        $total_dp += $qty * $resPrd->dp;
        $total_bv += $qty * $resPrd->bv;
      }
    }  

    $data['total_all_bv'] = (float) $total_bv;
    $data['total_all_dp'] = (float) $total_dp;
    $data['nourut'] = $resp[0]['totinv'] + 1;
    $data['trtype'] = "SB1";
    $data['ttptype'] = "";
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    $this->cn->deleteTTpByTrcd($data['trcd']);
    $res = $this->cn->simpanCNManual($data, $data['trcd']);
    echo json_encode($res);
    /* $arrdata = array(
      "cnno" => $data['cnno'],
    );

    echo "<pre>";
    print_r($data);
    echo "</pre>"; */
  }
  //$route['bo/cnmsn/manual/hapus/(:any)'] = 'finance/cnms/hapusTtpManual';
  function hapusTtpManual($trcd) {
    $res = $this->cn->deleteTTpByTrcd($trcd);
    echo json_encode($res);
  }
}
?>