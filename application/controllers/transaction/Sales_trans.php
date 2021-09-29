<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sales_trans extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->folderView = 'transaction/update_ssr_bonus/';
        $this->load->model('transaction/Sales_trans_model');
    }

    // $route['sales/generated/ssr-bonus-period'] = 'transaction/sales_trans/formSsrBonusPeriod';
    public function formSsrBonusPeriod() {
        $data['form_header'] = "Update SSR/MSR Bonus Period";
        $data['icon'] = "icon-edit";
        $data['form_reload'] = 'sales/generated/ssr-bonus-period';
        if($this->username != null) {
            $this->setTemplate($this->folderView.'formSsrBonusPeriod', $data);
        } else {
            //echo sessionExpireMessage(false);
            $this->setTemplate('includes/inline_login', $data);
        }
    }

    // $route['sales/generated/check-ssr'] = 'transaction/sales_trans/checkSsr';
    public function checkSsr() {
        $data = $this->input->post(null, true);
        $getSsr = $this->Sales_trans_model->checkSSR($data['ssr']);
        $getSum = $this->Sales_trans_model->getSumDP($data['ssr']);
        $result = jsonTrueResponse($getSsr);
        if ($getSsr && $getSum) {
          array_push($getSsr, $getSum[0]);
          $result = jsonTrueResponse($getSsr);
          if ($getSsr[0]->csno) {
            array_push($getSsr, $getSum[0]);
            $result = jsonTrueResponse($getSsr, 'Generated');
          }
        } else {
          $result = jsonFalseResponse('Invalid SSR...');
        }
        echo json_encode($result);
    }

    public function getPvrIp() {
      $no_ssr = $this->input->post('ssr');
      $getIp = $this->Sales_trans_model->checkIp($no_ssr);
      $result = jsonTrueResponse($getIp);
      if (!$getIp) {
        $result = jsonFalseResponse();
      }
      echo json_encode($result);
    }

    // $route['sales/generated/recover-ssr'] = 'transaction/sales_trans/recoverSsr';
    public function recoverSsr() {
        $data = $this->input->post(null, true);
        if (!$data['cn_no']) {
          $inc_pay = $this->Sales_trans_model->checkIp($data['ssr']);
          if ($inc_pay) {
            if (array_key_exists("trcd2", $inc_pay[0])) {
              $this->Sales_trans_model->recoverVoucher($inc_pay[0]->trcd2, $data['vch']);
            }
          }
          $arr = $this->Sales_trans_model->recoverSsr($data['ssr']);
          echo json_encode($arr);
        } else {
            echo json_encode(jsonFalseResponse("Nomor CN $data[cn_no] sudah ada..."));
        }
    }

    // $route['sales/generated/change-bonus-period'] = 'transaction/sales_trans/changeBonusPeriod';
    public function changeBonusPeriod() {
        $data = $this->input->post(null, true);
        $arr = $this->Sales_trans_model->changeBonusPeriod($data['bnsperiod'], $data['batchno'], $data['cn_no']);
        echo json_encode($arr);
    }
}