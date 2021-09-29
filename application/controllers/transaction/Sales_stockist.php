<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 'On'); */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_stockist extends MY_Controller {
  public function __construct() {
    parent::__construct();
    $this->folderView = "transaction/stockist/";
  }

  //$route['sales/stk/ttp/input'] = 'transaction/sales_stockist/inputTTP';
  public function inputTTP() {
    $data['form_header'] = "Input TTP Stockist";
    $data['form_action'] = "sales/stk/ttp/input";
    $data['icon'] = "icon-pencil";
    $data['form_reload'] = 'sales/stk/ttp/input';

    if ($this->username == null) {
        $this->setTemplate('includes/inline_login', $data);
        return;
    }

    $data['from'] = date("Y-m-d");
    $data['to'] = date("Y-m-d");
    $data['sc_dfno'] = $this->stockist;
    // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);
    $this->setTemplate($this->folderView.'inputTTP', $data);
    /* } else {
        $this->setTemplate('includes/inline_login', $data);
    } */
  }

  // $route['sales/stk/input/report'] = 'transaction/sales_stockist/reportGenerated';
  public function reportGenerated() {
    $data['form'] = $this->input->post(null, true);
    $data['type'] = 'stk';
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['result'] = $this->m_sales_stk->getListSalesStockistReport($data['form'], 'SB1');
    $this->load->view($this->folderView.'inputTTPListReport', $data);
  }

  // $route['sales/report/export'] = 'transaction/sales_stockist/reportToExcel';
  public function reportToExcel() {
    $data['form'] = $this->input->post(null, true);
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['kodestk'] = $this->stockist;

    if ($data['form']['type'] == 'stk') {
      $data['result'] = $this->m_sales_stk->getListSalesStockistReport($data['form'], 'SB1');
    } else {
      $data['result'] = $this->m_sales_stk->getListSalesStockistReport($data['form'], 'VP1');
    }

    $this->load->view($this->folderView.'inputTTPListReportExcel', $data);
  }

  // $route['sales/pvr/input/report'] = 'transaction/sales_stockist/reportPvrGenerated';
  public function reportPvrGenerated() {
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['form'] = $this->input->post(null, true);
    $data['type'] = 'pvr';
    $data['result'] = $this->m_sales_stk->getListSalesStockistReport($data['form'], 'VP1');
    $this->load->view($this->folderView.'inputTTPListReport', $data);
  }

  //$route['sales/stk/input/list'] = 'transaction/sales_stockist/getListInputSalesStockist';
  public function getListInputSalesStockist() {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['form'] = $this->input->post(NULL, TRUE);
    $data['stk_login'] = $this->stockist;

    if ($data['form']['searchby'] == "receiptno") {
      $data['result'] = $this->m_sales_stk->getListSsrByKW($data['form'], "SB1");
      if ($data['result'] == null) {
        echo setErrorMessage("Data ".$data['form']['paramValue']." tidak ditemukan atau bukan milik ".$this->stockist);
        return;
      }
      $this->load->view($this->folderView.'inputTTPListResultByKW', $data);

    } else if ($data['form']['searchby'] == "prdcd" || $data['form']['searchby'] == "prdnm") {
      $data['result'] = $this->m_sales_stk->getListSalesStockistByProduk($data['form'], "SB1");
      if ($data['result'] == null) {
        echo setErrorMessage("Data Produk ".$data['form']['paramValue']." tidak ditemukan di dalam list transaksi ".$this->stockist);
        return;
      }
      $this->load->view($this->folderView.'inputTTPListResult', $data);

    } else {
      $data['result'] = $this->m_sales_stk->getListSalesStockist($data['form'], "SB1");
      if ($data['result'] == null) {
        echo setErrorMessage("Data ".$data['form']['paramValue']." tidak ditemukan atau bukan milik ".$this->stockist);
        return;
      }
      $this->load->view($this->folderView.'inputTTPListResult', $data);
    }

  }

  //$route['sales/stk/delete/(:any)/(:any)'] = 'transaction/sales_stockist/deleteTrx/$1/$2';
  public function deleteTrx($param, $id) {
    if ($this->username == null) {
      $res = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
      echo json_encode($res);
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['header'] = $this->m_sales_stk->getTrxByTrcdHead($param, $id);

    if ($data['header'] == null) {
      $res = jsonFalseResponse("No Transaksi : $id tidak valid..");
      echo json_encode($res);
      return;
    }

    $header = $data['header'][0];

    if ($header->flag_batch == "1" || $header->batchno != null || $header->batchno != "") {
      $res = jsonFalseResponse("No Transaksi : $id sudah di generate dengan no : ".$header->batchno);
      echo json_encode($res);
      return;
    }

    $res = $this->m_sales_stk->deleteTrx($id);
    echo json_encode($res);
    /* $res = jsonTrueResponse(null, "Data $id berhasil dihapus..");
       echo json_encode($res);
       return; */
  }

  //$route['sales/stk/update/(:any)/(:any)'] = 'transaction/sales_stockist/updateTrx/$1/$2';
  public function updateTrx($param, $id) {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['header'] = $this->m_sales_stk->getTrxByTrcdHead($param, $id);

    if ($data['header'] == null) {
      jsAlert("Transaction ID : $id is Invalid..");
      return;
    }

    $data['form_action'] = "sales/stk/save";
    $data['sctype'] = $data['header'][0]->sctype;
    $data['co_sctype'] = $data['header'][0]->co_sctype;

    $data['prd_voucher'] = 0;
    $prefix_trcd = substr($data['header'][0]->trcd, 0, 2);

    if ($data['header'][0]->trtype == "VP1") {
      $data['prd_voucher'] = 1;
      $data['jenis_bayar'] = "pv";
      $data['listPay'] = $this->m_sales_stk->getListPaymentProductVoucher();

    } else if ($data['header'][0]->trtype != "VP1" && $prefix_trcd == "ID") {
      $data['jenis_bayar'] = "id";
      $data['listPay'] = $this->m_sales_stk->getListPaymentTypeOnlyCash();

    } else if ($data['header'][0]->trtype != "VP1" && $prefix_trcd == "CV") {
      $data['jenis_bayar'] = "cv";
      $data['listPay'] = $this->m_sales_stk->getListPaymentType();
    }

    $data['detail'] = $this->m_sales_stk->getDetailProduct("trcd", $data['header'][0]->trcd);
    $data['payment'] = $this->m_sales_stk->getDetailPayment("trcd", $data['header'][0]->trcd);
    $data['currentperiod'] = $this->m_sales_stk->getCurrentPeriod();
    $data['ins'] = "2";
    $data['pricecode'] = $this->pricecode;
    $data['head_form'] = "Input TTP Pembelanjaan Member";
    $data['sc_dfno_readonly'] = "";
    $data['sc_co_readonly'] = "";

    $data['submit_value'] = "Simpan Transaksi";

    if ($data['ins'] == "2") {
      $data['submit_value'] = "Update Transaksi";
    }

    $jumPrd = count($data['detail']);
    $data['tot_dp'] = $data['header'][0]->tdp;
    $data['tot_bv'] = $data['header'][0]->tbv;
    $data['jum_rec'] = $jumPrd;

    $data['start_tabidx'] = 7;

    $this->load->view($this->folderView.'inputTTPSubForm2', $data);
    $this->load->view($this->folderView.'viewProductPayment', $data);
    $this->load->view($this->folderView.'viewPaymentForm', $data);
  }

  //$route['sales/stk/info/(:any)'] = 'transaction/sales_stockist/getStockistInfo/$1';
  public function getStockistInfo($ids) {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $hasil = jsonFalseResponse("Kode Stockist Salah..");
    $arr = $this->m_sales_stk->getStockistInfo($ids);

    if ($arr != null) {
      $hasil = jsonTrueResponse($arr);
    }

    echo json_encode($hasil);

  }



  //$route['sales/stk/input/form'] = 'transaction/sales_stockist/inputTrxForm';
  public function inputTrxForm() {
    if ($this->username == null) {
        jsAlert();
        return;
    }

    $this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['currentperiod'] = $this->m_sales_member->getCurrentPeriod();
    $data['form_action'] = "sales/stk/save";
    $data['ins'] = "1";
    $data['stockist'] = $this->stockist;
    $data['stockistnm'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
    $data['sctype'] = $sctype[0]->sctype;
    $data['listPay'] = $this->m_sales_stk->getListPaymentType();
    $data['start_tabidx'] = 5;
    $data['jenis_bayar'] = "";

    $this->load->view($this->folderView.'inputTTPForm', $data);
    $this->load->view($this->folderView.'viewProductPayment', $data);
    $this->load->view($this->folderView.'viewPaymentForm', $data);
  }

  //$route['sales/stk/save'] = 'transaction/sales_stockist/saveTrxStockist';
  public function saveTrxStockist() {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->library('form_validation');
    $data = $this->input->post(NULL, TRUE);

    if ($this->form_validation->run('inputTtpStockist') === FALSE) {
      $this->form_validation->set_error_delimiters("", "");
      echo json_encode(jsonFalseResponse(validation_errors()));
      return;
    }

    //check apakah produk ada yang kosong
    $jum = count($data['prdcd']);
    if ($jum == 0) {
      echo json_encode(jsonFalseResponse("Produk tidak boleh kosong.."));
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $products = $this->m_sales_stk->getListPrdPromoNewMember();

    /* array(
      'CSVD01NM',
      'CSVD02NM',
      'CSVD03NM',
      'CSVD04NM',
      'CSVD05NM',
      'CSVD06NM',
      'CSVD07NM',
      'CSVD08NM',
      'CSVD09NM',
      'CSVD10NM',
      'CSVDTESNM'
    ); */

    //check valid product

    $sub_tot_bv = 0;
    $sub_tot_dp = 0;
    $total_dp = 0;
    $total_bv = 0;
    $jumPrd = count($data['prdcd']);

    for ($i = 0; $i < $jumPrd; $i++) {
      $prdcd = $data['prdcd'][$i];
      $qty = $data['jum'][$i];

      if ($data['prdcd'][$i] == "" || $data['prdcd'][$i] == " ") {
        echo json_encode(jsonFalseResponse("Ada field produk yang kosong,silahkan isi kode produk atau hapus field produk bila tidak terpakai"));
        return;
      }

      if (in_array($data['prdcd'][$i], $products, TRUE)) {
        echo json_encode(jsonFalseResponse("Pembelian produk promo new member ".$data['prdcd'][$i]." tidak bisa diinput disini.."));
        return;
      }

      $prdArr = $this->m_sales_stk->showProductPrice($prdcd, $data['pricecode']);
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

    $data['total_all_bv'] = (float) $total_bv;
    $data['total_all_dp'] = (float) $total_dp;

    //check apakah pembayaran kosong
    if (!isset($data['payChooseType'])) {
      /* echo json_encode(jsonFalseResponse("Pembayaran tidak boleh kosong.."));
      return;  */
      $data['payChooseType'][0] = "01";
      $data['payChooseValue'][0] = $data['total_all_dp'];
      $data['payReff'][0] = "/";

    } else {
        $jumPay = count($data['payReff']);
        $total_bayar_vch = 0;

        for ($i = 0; $i < $jumPay; $i++) {
          if ($data['payChooseType'][$i] == "01") {
            $data['payChooseValue'][$i] = floatval(str_replace('.', '', $data['payChooseValue'][$i]));
          } else {
            if ($data['payChooseType'][$i] == "10") {
              $typeVchx = "P";
            } else if ($data['payChooseType'][$i] == "08") {
              $typeVchx = "C";
            }

            $arr = $this->m_sales_stk->checkValidCashVoucher($data['dfno'], $data['payReff'][$i], $typeVchx);
            if ($arr['response'] == "false") {
              echo json_encode($arr);
              return;
            } else {
              $datax = $arr['arrayData'][0];
              $data['payChooseValue'][$i] = $datax->VoucherAmt;
              $total_bayar_vch += $datax->VoucherAmt;
            }
          }
        }

        $j = $i;
        if ($data['total_all_dp'] > $total_bayar_vch) {
          $sisa_cash = $data['total_all_dp'] - $total_bayar_vch;
          $data['payChooseType'][$j] = "01";
          $data['payChooseValue'][$j] = $sisa_cash;
          $data['payReff'][$j] = "/";
        }
    }

    //check apakah distributor valid

    $ifMemberExist = $this->m_sales_stk->getValidDistributor($data['dfno']);
    if ($ifMemberExist == null) {
      echo json_encode(jsonFalseResponse("ID Member tidak valid.."));
      return;
    }

    $arr = array("table" => "sc_newtrh",
      "param" => "orderno",
      "value" => $data['orderno'],
      "db" => "klink_mlm2010",
    );

    if ($data['ins'] == "1") {
      $checkOrderno = $this->m_sales_stk->checkExistingRecord($arr);
      //CHECK apakah ORDERNO double
      if ($checkOrderno != null) {
        echo jsonFalseResponse("No TTP sudah ada di database..");
        return;
      }
    }

    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    $data['no_deposit'] = "";
    $data['id_deposit'] = "";

    $res = $this->m_sales_stk->saveTrx($data);
    echo json_encode($res);
  }

  //$route['sales/sub/ttp/input'] = 'transaction/sales_stockist/inputTtpSub';
  public function inputTtpSub() {
    $data['form_header'] = "Input TTP MS/Sub/Stockist";
    $data['form_action'] = "sales/sub/ttp/input";
    $data['icon'] = "icon-pencil";
    $data['form_reload'] = 'sales/sub/ttp/input';

    if ($this->username == null) {
      $this->setTemplate('includes/inline_login', $data);
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['from'] = date("Y-m-d");
    $data['to'] = date("Y-m-d");
    $data['sc_dfno'] = $this->stockist;
    $data['payment'] = null;
    $data['username'] = $this->username;
    // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);
    $this->setTemplate($this->folderView.'inputTTPSub', $data);

  }

  //intval(str_replace(',', '', $myVal))
  //$route['sales/sub/input/form'] = 'transaction/sales_stockist/inputTrxFormSub';
  public function inputTrxFormSub() {
    if ($this->username == null) {
      $this->setTemplate('includes/inline_login', $data);
      return;
    }

    //$this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['form_action'] = "sales/stk/save";
    //echo "Stk : ".$this->stockist;
    $data['currentperiod'] = $this->m_sales_stk->getCurrentPeriod();
    $data['ins'] = "1";
    //$data['isPrevBnsACtive'] = $this->m_sales_stk->ifPrevBnsMonthStockistActive($this->stockist);
    $data['stockist'] = $this->stockist;
    $data['stockistnm'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $data['listPay'] = $this->m_sales_stk->getListPaymentType();
    $data['start_tabidx'] = 7;
    $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
    $data['sctype'] = $sctype[0]->sctype;
    $data['co_sctype'] = $sctype[0]->sctype;
    $data['prd_voucher'] = 0;
    $data['jenis_bayar'] = "";

    $data['head_form'] = "Input TTP Pembelanjaan Member";
    $data['sc_dfno_readonly'] = "";
    $data['sc_co_readonly'] = "";

    $data['submit_value'] = $data['ins'] == "1" ? "Simpan Transaksi" : "Update Transaksi";

    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 1;

    $data['sc_dfno'] = $this->stockist;
    $data['sc_dfnonm'] = $this->stockistnm;
    $data['sc_co'] = $this->stockist;
    $data['sc_conm'] = $this->stockistnm;
    $data['loccd'] = $this->stockist;
    $data['loccdnm'] = $this->stockistnm;

    $this->load->view($this->folderView.'inputTTPSubForm2', $data);
    $this->load->view($this->folderView.'viewProductPaymentTtp', $data);
    //$this->load->view($this->folderView.'viewPaymentForm',$data);
  }

  //$route['sales/sub/input/formV2/(:any)/(:any)'] = 'transaction/sales_stockist/inputTrxFormSubV3/$1/$2';
  public function inputTrxFormSubV3($stk, $co_stk) {
    if ($this->username == null) {
        $this->setTemplate('includes/inline_login', $data);
        return;
    }

    //$this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['form_action'] = "sales/stk/save";
    //echo "Stk : ".$this->stockist;
    $data['currentperiod'] = $this->m_sales_stk->getCurrentPeriod();
    $data['ins'] = "1";
    //$data['isPrevBnsACtive'] = $this->m_sales_stk->ifPrevBnsMonthStockistActive($this->stockist);
    $sctype = $this->m_sales_stk->getStockistInfo($stk);
    $data['stockist'] = $sctype[0]->loccd;
    $data['stockistnm'] = $sctype[0]->fullnm;
    $data['pricecode'] = $this->pricecode;
    $data['listPay'] = $this->m_sales_stk->getListPaymentType();
    $data['start_tabidx'] = 7;

    $data['sctype'] = $sctype[0]->sctype;
    //$data['co_sctype'] = $sctype[0]->sctype;
    $data['prd_voucher'] = 0;
    $data['jenis_bayar'] = "";

    $data['head_form'] = "Input TTP Pembelanjaan Member";
    $data['sc_dfno_readonly'] = "";
    $data['sc_co_readonly'] = "";

    $data['submit_value'] = $data['ins'] == "1" ? "Simpan Transaksi" : "Update Transaksi";

    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 1;

    $data['sc_dfno'] = $sctype[0]->loccd;
    $data['sc_dfnonm'] = $sctype[0]->fullnm;

    if ($stk === $co_stk) {
      $data['sc_co'] = $sctype[0]->loccd;
      $data['sc_conm'] = $sctype[0]->fullnm;
      $data['co_sctype'] = $sctype[0]->sctype;
    } else {
      $costk_type = $this->m_sales_stk->getStockistInfo($co_stk);
      $data['sc_co'] = $costk_type[0]->loccd;
      $data['sc_conm'] = $costk_type[0]->fullnm;
      $data['co_sctype'] = $costk_type[0]->sctype;
    }

    $data['loccd'] = $this->stockist;
    $data['loccdnm'] = $this->stockistnm;

    $this->load->view($this->folderView.'inputTTPSubForm2', $data);
    $this->load->view($this->folderView.'viewProductPaymentTtp', $data);
  }

  //$route['sales/product/pvr/check'] = 'transaction/sales_stockist/showProductPriceForPvr';
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
      $data = $this->m_sales_stk->showProductPriceForPvr($prdcdcode, $pricecode, $jenis, $jenis_promo);
      echo json_encode($data);
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data = $this->m_sales_stk->showProductPriceForPvr($prdcdcode, $pricecode, $jenis);
    echo json_encode($data);

  }

  //$route['sales/vc/check/(:any)/(:any)/(:any)'] = 'transaction/sales_stockist/checkValidVoucherCash/$1/$2/$3';
  function checkValidVoucherCash($distributorcode, $vchnoo, $paytype) {
    if ($this->username == null) {
      $err = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
      echo json_encode($err);
      return;
    }

    $threeDigit = substr($vchnoo, 0, 3);
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $check = $this->m_sales_stk->getTipePromoVch($threeDigit);

    if ($check['response'] === "true") {
      $arrsx = $check['arrayData'][0];
      $resErr = jsonFalseResponse($arrsx->keterangan);
      echo json_encode($resErr);
      return;
    }

    $response = jsonFalseResponse("No Voucher salah atau tidak sesuai dengan Member ".$distributorcode);
    $arr = null;

    if ($paytype == "10") {
      $arr = $this->m_sales_stk->checkValidCashVoucher($distributorcode, $vchnoo, "P");
    } else {
      $arr = $this->m_sales_stk->checkValidCashVoucher($distributorcode, $vchnoo, "C");
    }
    echo json_encode($arr);

  }

  /*----------------
    * PVR
    * ---------------*/

  //$route['sales/pvr/input'] = 'transaction/sales_stockist/inputSalesPvr';
  public function inputSalesPvr() {
    if ($this->username == null) {
      $this->setTemplate('includes/inline_login', $data);
      return;
    }

    //echo "S";
    $data['form_header'] = "Input PVR MS/Sub/Stockist";
    $data['form_action'] = "sales/sub/ttp/input";
    $data['icon'] = "icon-pencil";
    $data['form_reload'] = 'sales/pvr/input';
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['from'] = date("Y-m-d");
    $data['to'] = date("Y-m-d");
    $data['sc_dfno'] = $this->stockist;
    // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);
    $this->setTemplate($this->folderView.'inputPVRSub', $data);

  }

  //$route['sales/pvr/input/list'] = 'transaction/sales_stockist/getListInputPvrSalesStockist';
  public function getListInputPvrSalesStockist() {
    if ($this->username == null) {
      echo sessionExpireMessage();
      return;
    }

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['form'] = $this->input->post(NULL, TRUE);
    $data['stk_login'] = $this->stockist;

    if ($data['form']['searchby'] == "receiptno") {
      $data['result'] = $this->m_sales_stk->getListSsrByKW($data['form'], "SB1");
      if ($data['result'] == null) {
        echo setErrorMessage("Data ".$data['form']['paramValue']." tidak ditemukan atau bukan milik ".$this->stockist);
      } else {
        $this->load->view($this->folderView.'inputTTPListResultByKW', $data);
      }
    } else if ($data['form']['searchby'] == "prdcd" || $data['form']['searchby'] == "prdnm") {
        $data['result'] = $this->m_sales_stk->getListSalesStockistByProduk($data['form'], "VP1");
        $this->load->view($this->folderView.'inputPvrListResult', $data);
    } else {
        $data['result'] = $this->m_sales_stk->getListSalesStockist($data['form'], "VP1");
        $this->load->view($this->folderView.'inputPvrListResult', $data);
    }
  }

  //$route['sales/pvr/input/form'] = 'transaction/sales_stockist/inputTrxPvrForm';
  public function inputTrxPvrForm() {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['currentperiod'] = $this->m_sales_member->getCurrentPeriod();
    $data['form_action'] = "sales/stk/save";
    $data['ins'] = "1";
    $data['stockist'] = $this->stockist;
    $data['stockistnm'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
    $data['sctype'] = $sctype[0]->sctype;
    $data['co_sctype'] = $sctype[0]->sctype;
    $data['listPay'] = $this->m_sales_stk->getListPaymentProductVoucher();
    $data['start_tabidx'] = 7;
    $data['prd_voucher'] = 1;

    $data['head_form'] = "Input TTP Pembelanjaan Member";
    $data['sc_dfno_readonly'] = "";
    $data['sc_co_readonly'] = "";
    $data['jenis_bayar'] = "pv";

    $data['submit_value'] = $data['ins'] == "1" ? "Simpan Transaksi" : "Update Transaksi";

    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 1;

    $this->load->view($this->folderView.'inputTTPSubForm2', $data);
    $this->load->view($this->folderView.'viewProductPayment', $data);
    $this->load->view($this->folderView.'viewPaymentForm', $data);

  }

  //$route['sales/pvr2/input/form'] = 'transaction/sales_stockist/inputTrxPvrForm2';
  public function inputTrxPvrForm2() {
    if ($this->username == null) {
      jsAlert();
      return;
    }

    $this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['currentperiod'] = $this->m_sales_member->getCurrentPeriod();
    $data['form_action'] = "sales/stk/save/pvr";
    $data['form_head'] = "Input Product Voucher / PVR";
    $data['ins'] = "1";
    $data['stockist'] = $this->stockist;
    $data['stockistnm'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
    //print_r($sctype);
    $data['sctype'] = $sctype[0]->sctype;
    $data['pricecode'] = $sctype[0]->pricecode;
    $data['co_sctype'] = $sctype[0]->sctype;
    $data['listPay'] = $this->m_sales_stk->getListPaymentProductVoucher();
    $data['start_tabidx'] = 7;
    $data['prd_voucher'] = 1;
    $data['jenis_bayar'] = "pv";

    $data['submit_value'] = $data['ins'] == "1" ? "Simpan Transaksi" : "Update Transaksi";
    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 1;

    $this->load->view($this->folderView.'formInputPvr', $data);
      /* $this->load->view($this->folderView.'viewProductPayment',$data);
  $this->load->view($this->folderView.'viewPaymentForm',$data); */

  }

  //$route['sales/pvr2/save'] = 'transaction/sales_stockist/savePvrVersi2';
  public function savePvrVersi2() {
    $data = $this->input->post(NULL, TRUE);
    $this->load->library('form_validation');
    $x = array();
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    //id member tidak boleh mengandung spasi dan hanya angka dan huruf
    $this->form_validation->set_rules('distributorcode', 'Distributor', 'required|trim|alpha_numeric');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode($this->jsonFalseResponse("ID Member hanya boleh mengandung angka dan huruf.."));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
    }

    $idmember = trim(strtoupper($data['distributorcode']));
    //check valid member
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $checkValidMember = $this->m_sales_stk->getValidDistributor($idmember);

    if ($checkValidMember == null) {
        echo json_encode($this->jsonFalseResponse("ID Member tidak valid atau TERMINATION/RESIGNATION"));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
    }

    $x['fullnm'] = $checkValidMember[0]->fullnm;

    //check valid voucher
    if (!array_key_exists('vchno', $data) || !isset($data['vchno'])) {
        echo json_encode($this->jsonFalseResponse("Pembayaran minimal menggunakan 1 produk voucher.."));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
    }

    if (!array_key_exists('productcode', $data) || !isset($data['productcode'])) {
        echo json_encode($this->jsonFalseResponse("Produk masih kosong.."));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
    }


    $jumVch = count($data['vchno']);
    $x['total_nilai_vch'] = 0;
    $ifPromoGV = 0;
    $ifPromoHydro = 0;
    $ifPromoHadiah = 0;
    $ifReguler = 0;
    $arrPromoGV = "";

    for ($i = 0; $i < $jumVch; $i++) {
      $prefix = substr($data['vchno'][$i], 0, 3);

      if ($prefix == "XHD") {
        $x['promo'] = "hydro";
        $x['orderno'] = $data['vchno'][$i];
        $ifPromoHydro = 1;
      } else if ($prefix == "XPV" || $prefix == "ZVO" || $prefix == "XPP" || $prefix == "AYU") {
        $x['promo'] = "hadiah";
        $x['orderno'] = $data['vchno'][$i];
        $ifPromoHadiah = 1;
      } else if ($prefix == "GV1" || $prefix == "GV2" || $prefix == "GV3" || $prefix == "GV4") {
        $ifPromoGV = 1;
        $arrPromoGV.= "'".$prefix."',";
        //$vchPromo = $refnoo[$s];
      } else {
        if ($i == 0) {
            $x['promo'] = "reguler";
            $x['orderno'] = $data['vchno'][$i];
            $ifReguler = 1;
        }
      }

      $arr = $this->m_sales_stk->checkValidCashVoucher($idmember, $data['vchno'][$i], "P");

      if ($arr['response'] == "false") {
        echo json_encode($arr);
        return;
      }

      $x['payChooseType'][$i] = "10";
      $x['payReff'][$i] = $data['vchno'][$i];
      $x['payChooseValue'][$i] = $data['vch_amt_real'][$i];
      $x['total_nilai_vch'] += $data['vch_amt_real'][$i];

    }

    $arrPromoGV = substr($arrPromoGV, 0, -1);

    $x['bnsperiod'] = $data['bnsperiod'];
    $x['dfno'] = $data['distributorcode'];
    $x['sctype'] = $data['sctype'];
    $x['pricecode'] = $data['pricecode'];
    $x['ins'] = "1";

    $x['sc_dfno'] = $data['loccd'];
    $x['sc_co'] = $data['loccd'];
    $x['loccd'] = $data['loccd'];
    $x['remarks'] = "";
    $x['sctype'] = $data['sctype'];
    $x['sctype'] = $data['sctype'];
    $x['sctype'] = $data['sctype'];

    $jumPrd = count($data['productcode']);
    $x['total_nilai_prd'] = 0;

    for ($i = 0; $i < $jumPrd; $i++) {
      if ($data['productcode'][$i] == "" || $data['productcode'][$i] == " ") {
        echo json_encode(jsonFalseResponse("Ada field produk yang kosong,silahkan isi kode produk atau hapus field produk bila tidak terpakai"));
        return;
      }

      if ($ifReguler == 1) {
        $checkValidPrd = $this->m_sales_stk->showProductPriceForPvr($data['productcode'][$i], $data['pricecode'], $data['jenis_bayar']);

        if ($checkValidPrd['response'] == "false") {
          echo json_encode($this->jsonFalseResponse($checkValidPrd['message']));
          return;
        }

        $harga = $checkValidPrd['arraydata'][0];
        $x['total_nilai_prd'] += $data['qty'][$i] * $harga->dp;
        $x['prdcd'][$i] = $data['productcode'][$i];
        $x['jum'][$i] = $data['qty'][$i];
        $x['harga'][$i] = $harga->dp;
        $x['poin'][$i] = $harga->bv;
        $x['sub_tot_dp'][$i] = $data['qty'][$i] * $harga->dp;
        $x['sub_tot_bv'][$i] = $data['qty'][$i] * $harga->bv;

      } else if ($ifPromoHydro == 1 || $ifPromoHadiah == 1) {
        $checkValidPrd = $this->m_sales_stk->getListProdPromoByVchAndPrdcd($data['vchno'][0], $data['productcode'][$i], $data['pricecode'], $data['qty'][$i]);

        if ($checkValidPrd['response'] == "false") {
          //$err = $data['productcode'][$i]." / ".$data['productname'][$i]." tidak termasuk dalam voucher ".$data['vchno'][0];
          echo json_encode($checkValidPrd);
          return;
        }

        $harga = $checkValidPrd['arrayData'][0];
        $x['total_nilai_prd'] += $data['qty'][$i] * $harga->harga;
        $x['prdcd'][$i] = $data['productcode'][$i];
        $x['jum'][$i] = $data['qty'][$i];
        $x['harga'][$i] = $harga->harga;
        $x['poin'][$i] = $harga->poin;
        $x['sub_tot_dp'][$i] = $data['qty'][$i] * $harga->harga;
        $x['sub_tot_bv'][$i] = $data['qty'][$i] * $harga->poin;

      } else if ($ifPromoGV == 1) {
        $checkValidPrd = $this->m_sales_stk->checkPromoGV($data['productcode'][$i], $arrPromoGV, $data['pricecode']);

        if ($checkValidPrd['response'] == "false") {
          $prdX = $data['productcode'][$i];
          $res = array("response" => "false", "message" => "Kode Produk ".$data['productcode'][$i]." tidak boleh diinput di voucher yang berawalan $arrPromoGV");
          echo json_encode($res);
          return $res;
        }

        $harga = $checkValidPrd['arrayData'][0];
        $x['total_nilai_prd'] += $data['qty'][$i] * $harga->dp;
        $x['prdcd'][$i] = $data['productcode'][$i];
        $x['jum'][$i] = $data['qty'][$i];
        $x['harga'][$i] = $harga->dp;
        $x['poin'][$i] = $harga->dp;
        $x['sub_tot_dp'][$i] = $data['qty'][$i] * $harga->dp;
        $x['sub_tot_bv'][$i] = $data['qty'][$i] * 0;
      }
    }

      $x['cash_hrs_dibayar'] = 0;

      if ($x['total_nilai_prd'] > $x['total_nilai_vch']) {
        $x['cash_hrs_dibayar'] = $x['total_nilai_prd'] - $x['total_nilai_vch'];
      }

      $jumBayar = count($x['payChooseType']);
      $x['payChooseType'][$jumBayar] = "01";
      $x['payReff'][$jumBayar] = "CASH";
      $x['payChooseValue'][$jumBayar] = $x['cash_hrs_dibayar'];

      /* echo "<pre>";
      print_r($x);
      echo "</pre>"; */

      $x['no_deposit'] = "";
      $x['id_deposit'] = "";

      $save = $this->m_sales_stk->saveTrx($x);
      echo json_encode($save);

  }

  //$route['sales/sub/input/vcash'] = 'transaction/sales_stockist/inputVchCash';
  public function inputVchCash() {
    if ($this->username != null) {
      $this->load->model("transaction/sales_member_model", "m_sales_member");
      $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
      $data['currentperiod'] = $this->m_sales_member->getCurrentPeriod();
      $data['form_action'] = "sales/stk/save/vcash";
      $data['form_head'] = "Input Voucher Cash / Umroh";
      $data['ins'] = "1";
      $data['stockist'] = $this->stockist;
      $data['stockistnm'] = $this->stockistnm;
      $data['pricecode'] = $this->pricecode;
      $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
      //print_r($sctype);
      $data['sctype'] = $sctype[0]->sctype;
      $data['pricecode'] = $sctype[0]->pricecode;
      $data['co_sctype'] = $sctype[0]->sctype;
      $data['listPay'] = $this->m_sales_stk->getListPaymentProductVoucher();
      $data['start_tabidx'] = 7;
      $data['prd_voucher'] = 0;
      $data['jenis_bayar'] = "pv";

      if ($data['ins'] == "1") {
        $data['submit_value'] = "Simpan Transaksi";
      } else {
        $data['submit_value'] = "Update Transaksi";
      }

      $data['tot_dp'] = 0;
      $data['tot_bv'] = 0;
      $data['jum_rec'] = 1;

      $this->load->view($this->folderView.'formInputVchCash', $data);
      /* $this->load->view($this->folderView.'viewProductPayment',$data);
      $this->load->view($this->folderView.'viewPaymentForm',$data); */
    } else {
        jsAlert();
    }
  }

  //$route['sales/correction/(:any)'] = 'transaction/sales_stockist/koreksiTransaksi/$1';
  public function koreksiTransaksi($trcd) {
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $check = $this->m_sales_stk->cekHeaderTrx("trcd", $trcd);

    /* echo "<pre>";
    print_r($check);
    echo "</pre>"; */
    if ($check == null) {
      $arr = jsonFalseResponse("Transaksi dengan nomor $trcd tidak ada / tidak valid");
      return $arr;
    }

    $header = $check['header'];

    if ($header[0]->batchno != null && $header[0]->batchno != "") {
      $arr = jsonFalseResponse("Transaksi dengan nomor $trcd sudah di generate dengan no ".$header[0]->batchno.", silahkan di recover terlebih dahulu..");
      echo json_encode($arr);
      return $arr;
    }

    if ($header[0]->csno != null && $header[0]->csno != "") {
      $arr = jsonFalseResponse("Transaksi dengan nomor $trcd sudah di proses dengan no ".$header[0]->csno);
      echo json_encode($res);
      return $arr;
    }

    $res = $this->m_sales_stk->koreksiTransaksi($check);
    echo json_encode($res);
  }

  //$route['sales/vcash2/save'] = 'transaction/sales_stockist/saveVcashVersi2';
  public function saveVcashVersi2() {
      $data = $this->input->post(NULL, TRUE);
      $this->load->library('form_validation');
      $x = array();
      /* echo "<pre>";
      print_r($data);
      echo "</pre>"; */
      $products = array('CSVD01NM',
          'CSVD02NM',
          'CSVD03NM',
          'CSVD04NM',
          'CSVD05NM',
          'CSVD06NM',
          'CSVD07NM',
          'CSVD08NM',
          'CSVD09NM',
          'CSVD10NM',
          'CSVDTESNM'
      );

      //id member tidak boleh mengandung spasi dan hanya angka dan huruf
      $this->form_validation->set_rules('distributorcode', 'Distributor', 'required|trim|alpha_numeric');

      if ($this->form_validation->run() == FALSE) {
        echo json_encode($this->jsonFalseResponse("ID Member hanya boleh mengandung angka dan huruf.."));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
      }

      $idmember = trim(strtoupper($data['distributorcode']));
      //check valid member
      $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
      $checkValidMember = $this->m_sales_stk->getValidDistributor($idmember);

      if ($checkValidMember == null) {
        echo json_encode($this->jsonFalseResponse("ID Member tidak valid atau TERMINATION/RESIGNATION"));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
      }

      $x['fullnm'] = $checkValidMember[0]->fullnm;

      //check valid voucher
      if (!array_key_exists('vchno', $data) || !isset($data['vchno'])) {
        echo json_encode($this->jsonFalseResponse("Pembayaran minimal menggunakan 1 cash voucher.."));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
      }

      if (!array_key_exists('prdcd', $data) || !isset($data['prdcd'])) {
        echo json_encode($this->jsonFalseResponse("Produk masih kosong.."));
        //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
        return;
      }


      $jumVch = count($data['vchno']);
      $x['total_nilai_vch'] = 0;

      //accumulate voucher
      for ($i = 0; $i < $jumVch; $i++) {
        $x['orderno'] = $data['vchno'][$i];
        $arr = $this->m_sales_stk->checkValidCashVoucher($idmember, $data['vchno'][$i], "C");
        $isvchVNM = 0;
        $get_pref = substr($data['vchno'][$i], 0, 3);

        if ($get_pref === 'VNM') {
            $isvchVNM++;
        }

        if ($arr['response'] == "false") {
            echo json_encode($arr);
            return;
        }

        $x['payChooseType'][$i] = "08";
        $x['payReff'][$i] = $data['vchno'][$i];
        $x['payChooseValue'][$i] = $data['vch_amt_real'][$i];
        $x['total_nilai_vch'] += $data['vch_amt_real'][$i];

      }

      $x['bnsperiod'] = $data['bnsperiod'];
      $x['dfno'] = $data['distributorcode'];
      $x['sctype'] = $data['sctype'];
      $x['pricecode'] = $data['pricecode'];
      $x['ins'] = "1";

      $x['sc_dfno'] = $data['loccd'];
      $x['sc_co'] = $data['loccd'];
      $x['loccd'] = $data['loccd'];
      $x['remarks'] = "";
      $x['sctype'] = $data['sctype'];
      $x['sctype'] = $data['sctype'];
      $x['sctype'] = $data['sctype'];

      $isprdVNM = 0;
      $jumPrd = count($data['prdcd']);
      $x['total_nilai_prd'] = 0;

      for ($i = 0; $i < $jumPrd; $i++) {
        if ($data['prdcd'][$i] !== null && $data['prdcd'][$i] !== "") {
          $checkValidPrd = $this->m_sales_stk->showProductPriceForPvr($data['prdcd'][$i], $data['pricecode'], $data['jenis_bayar']);

          if (in_array($data['prdcd'][$i], $products, TRUE)) {
            $isprdVNM++;
          }

          if ($checkValidPrd['response'] == "false") {
            echo json_encode($this->jsonFalseResponse($checkValidPrd['message']));
            return;
          }

          $harga = $checkValidPrd['arraydata'][0];
          $x['total_nilai_prd'] += $data['jum'][$i] * $harga->dp;
          $x['prdcd'][$i] = $data['prdcd'][$i];
          $x['jum'][$i] = $data['jum'][$i];
          $x['harga'][$i] = $harga->dp;
          $x['poin'][$i] = $harga->bv;
          $x['sub_tot_dp'][$i] = $data['jum'][$i] * $harga->dp;
          $x['sub_tot_bv'][$i] = $data['jum'][$i] * $harga->bv;
        }

      }

      $x['cash_hrs_dibayar'] = 0;

      if ($x['total_nilai_prd'] > $x['total_nilai_vch']) {
          $x['cash_hrs_dibayar'] = $x['total_nilai_prd'] - $x['total_nilai_vch'];
      }

      $jumBayar = count($x['payChooseType']);
      $x['payChooseType'][$jumBayar] = "01";
      $x['payReff'][$jumBayar] = "CASH";
      $x['payChooseValue'][$jumBayar] = $x['cash_hrs_dibayar'];

      /* echo "<pre>";
      print_r($x);
      echo "</pre>"; */
      if ($isprdVNM > 0 && $isvchVNM < 1) {
        echo json_encode($this->jsonFalseResponse("Ada pembelian produk khusus non BV tapi tidak menggunakan voucher VNM"));
        return;
      } else {
        $x['no_deposit'] = "";
        $x['id_deposit'] = "";
        //if ($this->username != 'BID06') {
        $save = $this->m_sales_stk->saveTrx($x);
        echo json_encode($save);
        //}
        return;
      }
  }

  //$route['sales/sub/input/vcashKhusus'] = 'transaction/sales_stockist/inputVchCashKhusus';
  public function inputVchCashKhusus() {
    $data['form_header'] = "Input Voucher Cash Produk Khusus";
    $data['form_action'] = "sales/vcashKhusus/save";
    $data['icon'] = "icon-pencil";
    $data['form_reload'] = 'sales/sub/input/vcashKhusus';

    if ($this->username == null) {
        $this->setTemplate('includes/inline_login', $data);
        return;
    }


    $this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['currentperiod'] = $this->m_sales_member->getCurrentPeriod();
    $data['tipe_vchpromo'] = $this->m_sales_stk->getListTipePromoVch();
    $data['form_head'] = "Input Voucher Cash Produk Khusus";
    $data['ins'] = "1";
    $data['stockist'] = $this->stockist;
    $data['stockistnm'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
    //print_r($sctype);
    $data['sctype'] = $sctype[0]->sctype;
    $data['pricecode'] = $sctype[0]->pricecode;
    $data['co_sctype'] = $sctype[0]->sctype;
    $data['listPay'] = $this->m_sales_stk->getListPaymentProductVoucher();
    $data['start_tabidx'] = 7;
    $data['prd_voucher'] = 0;
    $data['jenis_bayar'] = "pv";

    if ($data['ins'] == "1") {
        $data['submit_value'] = "Simpan Transaksi";
    } else {
        $data['submit_value'] = "Update Transaksi";
    }

    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 0;

    $this->setTemplate($this->folderView.'formInputVchCashKhusus', $data);
    /* $this->load->view($this->folderView.'viewProductPayment',$data);
    $this->load->view($this->folderView.'viewPaymentForm',$data); */

  }

  //$route['sales/vcashKhusus/prdCheck'] = 'transaction/sales_stockist/CheckPrdVchCashKhusus';
  function CheckPrdVchCashKhusus() {

    if ($this->username == null) {
      $err = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
      echo json_encode($err);
      return;
    }

    $productcode = $this->input->post('productcode');
    $prdcdcode = strtoupper($productcode);
    $pricecode = $this->input->post('pricecode');
    $jenis = $this->input->post('jenis');
    $tipe_vchpromo = $this->input->post('tipe_vchpromo');

    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');

    if ($tipe_vchpromo !== "V") {
      $checkPrd = $this->m_sales_stk->checkListProdukKhusus($tipe_vchpromo, $prdcdcode);

      if ($checkPrd['response'] == "false") {
          echo json_encode($checkPrd);
          return;
      }
    }

    $data = $this->m_sales_stk->showProductPriceForPvr($prdcdcode, $pricecode, $jenis);
    echo json_encode($data);

  }


  //$route['sales/vcashKhusus/check'] = 'transaction/sales_stockist/checkValidVoucherCashKhusus';
  function checkValidVoucherCashKhusus() {

    if ($this->username == null) {
      $err = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
      echo json_encode($err);
      return;
    }

    $param = $this->input->post(NULL, TRUE);
    $response = jsonFalseResponse("No Voucher salah atau tidak sesuai dengan Member ".$param['distributorcode']);
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $arr = null;

    if ($param['tipe_vchpromo'] == "V") {
      if ($param['paytype'] == "10") {
        $arr = $this->m_sales_stk->checkValidCashVoucher($param['distributorcode'], $param['vchnoo'], "P");
      } else {
        $arr = $this->m_sales_stk->checkValidCashVoucher($param['distributorcode'], $param['vchnoo'], "C");
      }
    } else {
      if ($param['paytype'] == "10") {
        $arr = $this->m_sales_stk->checkValidCashVoucherKhusus($param['distributorcode'], $param['vchnoo'], "P");
      } else {
        $arr = $this->m_sales_stk->checkValidCashVoucherKhusus($param['distributorcode'], $param['vchnoo'], "C");
      }
    }

      echo json_encode($arr);

  }

  //$route['sales/vcashKhusus/save'] = 'transaction/sales_stockist/saveVchCashKhusus';
  public function saveVchCashKhusus() {
    $data = $this->input->post(NULL, TRUE);
    $this->load->library('form_validation');
    $x = array();
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */

    //id member tidak boleh mengandung spasi dan hanya angka dan huruf
    $this->form_validation->set_rules('distributorcode', 'Distributor', 'required|trim|alpha_numeric');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode($this->jsonFalseResponse("ID Member hanya boleh mengandung angka dan huruf.."));
      //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
      return;
    }

    $idmember = trim(strtoupper($data['distributorcode']));
    //check valid member
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $checkValidMember = $this->m_sales_stk->getValidDistributor($idmember);

    if ($checkValidMember == null) {
      echo json_encode($this->jsonFalseResponse("ID Member tidak valid atau TERMINATION/RESIGNATION"));
      //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
      return;
    }

    $x['fullnm'] = $checkValidMember[0]->fullnm;

    //check valid voucher
    if (!array_key_exists('vchno', $data) || !isset($data['vchno'])) {
      echo json_encode($this->jsonFalseResponse("Pembayaran minimal menggunakan 1 cash voucher.."));
      //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
      return;
    }

    if (!array_key_exists('prdcd', $data) || !isset($data['prdcd'])) {
      echo json_encode($this->jsonFalseResponse("Produk masih kosong.."));
      //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
      return;
    }


    $jumVch = count($data['vchno']);
    $x['total_nilai_vch'] = 0;

    for ($i = 0; $i < $jumVch; $i++) {
      $x['orderno'] = $data['vchno'][$i];
      /* $arr = $this->m_sales_stk->checkValidCashVoucher($idmember,$data['vchno'][$i], "C");
      if($arr['response'] == "false") {
      echo json_encode($arr);
      return;
      } */

      $arr = $this->m_sales_stk->checkValidCashVoucherKhusus($data['distributorcode'], $data['vchno'][$i], "C");
      if ($arr['response'] == "false") {
          echo json_encode($checkPrd);
          return;
      }

      $x['payChooseType'][$i] = "08";
      $x['payReff'][$i] = $data['vchno'][$i];
      $x['payChooseValue'][$i] = $data['vch_amt_real'][$i];
      $x['total_nilai_vch'] += $data['vch_amt_real'][$i];
    }

    $x['bnsperiod'] = $data['bnsperiod'];
    $x['dfno'] = $data['distributorcode'];
    $x['sctype'] = $data['sctype'];
    $x['pricecode'] = $data['pricecode'];
    $x['ins'] = "1";

    $x['sc_dfno'] = $data['loccd'];
    $x['sc_co'] = $data['loccd'];
    $x['loccd'] = $data['loccd'];
    $x['remarks'] = "";
    $x['sctype'] = $data['sctype'];
    $x['sctype'] = $data['sctype'];
    $x['sctype'] = $data['sctype'];

    $jumPrd = count($data['prdcd']);
    $x['total_nilai_prd'] = 0;

    for ($i = 0; $i < $jumPrd; $i++) {

      /* $checkValidPrd = $this->m_sales_stk->showProductPriceForPvr($data['prdcd'][$i], $data['pricecode'], $data['jenis_bayar']);
      if($checkValidPrd['response'] == "false") {
        echo json_encode($this->jsonFalseResponse($checkValidPrd['message']));
        return;
      } */
      if ($data['prdcd'][$i] !== null && $data['prdcd'][$i] !== "") {

        $checkPrd = $this->m_sales_stk->checkListProdukKhusus($data['tipe_vchpromo'], $data['prdcd'][$i]);
        if ($checkPrd['response'] == "false") {
          echo json_encode($checkPrd);
          return;
        }

        $checkValidPrd = $this->m_sales_stk->showProductPriceForPvr($data['prdcd'][$i], $data['pricecode'], $data['jenis_bayar']);
        if ($checkValidPrd['response'] == "false") {
          echo json_encode($checkValidPrd);
          return;
        }

        $harga = $checkValidPrd['arraydata'][0];
        $x['total_nilai_prd'] += $data['jum'][$i] * $harga->dp;
        $x['prdcd'][$i] = $data['prdcd'][$i];
        $x['jum'][$i] = $data['jum'][$i];
        $x['harga'][$i] = $harga->dp;
        $x['poin'][$i] = $harga->bv;
        $x['sub_tot_dp'][$i] = $data['jum'][$i] * $harga->dp;
        $x['sub_tot_bv'][$i] = $data['jum'][$i] * $harga->bv;
      }
   }

    $x['cash_hrs_dibayar'] = 0;
    if ($x['total_nilai_prd'] > $x['total_nilai_vch']) {
      $x['cash_hrs_dibayar'] = $x['total_nilai_prd'] - $x['total_nilai_vch'];
    }

    $jumBayar = count($x['payChooseType']);
    $x['payChooseType'][$jumBayar] = "01";
    $x['payReff'][$jumBayar] = "CASH";
    $x['payChooseValue'][$jumBayar] = $x['cash_hrs_dibayar'];

    /* echo "<pre>";
    print_r($x);
    echo "</pre>"; */

    $x['no_deposit'] = "";
    $x['id_deposit'] = "";

    $save = $this->m_sales_stk->saveTrx($x);
    echo json_encode($save);
  }

  //$route['sales/report/product'] = 'transaction/sales_stockist_report/rekapSalesProduct';
  public function rekapSalesProduct() {
    $data['form_header'] = "Input Voucher Cash Produk Khusus";
    $data['form_action'] = "sales/vcashKhusus/save";
    $data['icon'] = "icon-pencil";
    $data['form_reload'] = 'sales/sub/input/vcashKhusus';

    if ($this->username == null) {
      $this->setTemplate('includes/inline_login', $data);
      return;
    }

    $this->setTemplate($this->folderView.'formrekapSalesProduct', $data);
  }

  //$route['sales/report/product/list'] = 'transaction/sales_stockist_report/rekapSalesProductList';
  public function rekapSalesProductList() {}

  //$route['sales/input/promo'] = 'transaction/sales_stockist/inputProdukPromo';
  public function inputProdukPromo() {
    if ($this->username == null) {
        $this->setTemplate('includes/inline_login', $data);
        return;
    }

    //$this->load->model("transaction/sales_member_model", "m_sales_member");
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    $data['form_action'] = "sales/input/promo/save";
    //echo "Stk : ".$this->stockist;
    $data['currentperiod'] = $this->m_sales_stk->getCurrentPeriod();
    $data['ins'] = "1";
    //$data['isPrevBnsACtive'] = $this->m_sales_stk->ifPrevBnsMonthStockistActive($this->stockist);
    $data['stockist'] = $this->stockist;
    $data['stockistnm'] = $this->stockistnm;
    $data['pricecode'] = $this->pricecode;
    $data['listPay'] = $this->m_sales_stk->getListPaymentType();
    $data['start_tabidx'] = 7;
    $sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
    $data['sctype'] = $sctype[0]->sctype;
    $data['co_sctype'] = $sctype[0]->sctype;
    $data['prd_voucher'] = 0;
    $data['jenis_bayar'] = "";

    $data['head_form'] = "Input TTP Pembelanjaan Member";
    $data['sc_dfno_readonly'] = "";
    $data['sc_co_readonly'] = "";

    $data['submit_value'] = $data['ins'] == "1" ? "Pilih Produk Free/Promo" : "Pilih Produk Free/Promo";

    $data['tot_dp'] = 0;
    $data['tot_bv'] = 0;
    $data['jum_rec'] = 1;

    $data['sc_dfno'] = $this->stockist;
    $data['sc_dfnonm'] = $this->stockistnm;
    $data['sc_co'] = $this->stockist;
    $data['sc_conm'] = $this->stockistnm;
    $data['loccd'] = $this->stockist;
    $data['loccdnm'] = $this->stockistnm;

    $this->load->view($this->folderView.'inputTTPSubFormPromo', $data);
    $this->load->view($this->folderView.'viewProductPaymentTtpPromo', $data);
  }

  //$route['sales/promo/check'] = 'transaction/sales_stockist/checkSalesPromo';
  public function checkSalesPromo() {
    $data = $this->input->post(NULL, TRUE);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
     if($data['jenis_promo'] === "PRK") {
      //jika promo adalah promo pre order premium 8
      $qty = $data['qty'];
      $dt['res'] = $this->m_sales_stk->promoPreOrderPrem8V2($qty);
      $dt['max_qty'] = $qty;
      $this->load->view($this->folderView.'paketFreePromoPrem8', $dt);
    } 
  }

  //$route['sales/input/promo/save'] = 'transaction/sales_stockist/saveInputProdukPromo';
  public function saveInputProdukPromo() {
    
  }

  //$route['sales/promo/checksisa'] = 'transaction/sales_stockist/checkSisa';
  function checkSisa() {
    $data = $this->input->post(NULL, TRUE);
    /* echo "<pre>";
    print_r($data);
    echo "</pre>"; */
    $exclude = null;
    if (array_key_exists("excludePrd", $data)) {
        $exclude = set_list_array_to_string($data['excludePrd']);
    } 
    //echo "isi . ".$exclude;
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    if($data['jenis_promo'] === "PRK") {
      $arr = jsonFalseResponse("Null");
      $res = $this->m_sales_stk->promoPreOrderPrem8V2($data['sisa_klaim'], $exclude);
      if($res !== null) {
        $arr = jsonTrueResponse($res);
      }
    }
    echo json_encode($arr);
    
  }

  //$route['sales/promo/resetpromo'] = 'transaction/sales_stockist/ulangiInputFree';
  function ulangiInputFree() {
    $data = $this->input->post(NULL, TRUE);
    $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    if($data['jenis_promo'] === "PRK") {
      $arr = jsonTrueResponse("Null");
      $res = $this->m_sales_stk->promoPreOrderPrem8V2($data['sisa_klaim'], null);
      if($res !== null) {
        $arr = jsonTrueResponse($res);
      }            
    }
    echo json_encode($arr);  
  }

}