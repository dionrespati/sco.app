<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Stock_barcode extends MY_Controller {
	public function __construct() {
	    parent::__construct();
		$this->folderView = "transaction/barcode/";
		$this->load->model('transaction/Stock_barcode_model', 'm_stock_barcode');
	}

  function detailScaProduk($id)
    {
        $header = "header";
        $detail = "detail";
        $xplode = explode("-",$id);
        $trcd = $xplode[0];
        $prdcd = $xplode[1];

        $arr = $this->m_stock_barcode->detailScaProduk($trcd,$prdcd);
        echo json_encode($arr);
    }

	function detailTransaction2($id)
    {
        $header = "header";
        $detail = "detail";
        $arr = $this->m_stock_barcode->getInformation($id);
        $det = $this->m_stock_barcode->getDataDetV2($id);
		
        $dataDet = array();
		
        foreach($det as $key => $value){
			
            $data['prdcd'] = $value['prdcd'];
            $data['trcd'] = $value['trcd'];
            $data['prdnm'] = $value['prdnm'];
            $data['qtyord'] = $value['qtyord'];
            $data['jumlah_sdh_dibarcode'] = $this->m_stock_barcode->getBarcodeV2($id,$value['prdcd']);
            array_push($dataDet, $data);
			// print_r($value);    
        }
		// print_r($dataDet);
		// die();
        $return['header'] = $dataDet;
        $return['detail'] = $arr;
        $return['response'] = 'true';
		// die('disini');
        echo json_encode($return);
    }

    // function detailTransaction2($id)
    // {
    //   // die('controller');
    //     $header = "header";
    //     $detail = "detail";
    //     $arr = $this->m_stock_barcode->detailTransaction($id);
    //     echo json_encode($arr);
    // }

  function detailTransaction()
    {
        $header = "header";
        $detail = "detail";
        $id = $this->input->post('no_ttp');
        $trcd = $this->m_stock_barcode->cekTrcd($id);
        if(!empty($trcd)){
            foreach($trcd as $key => $value){
                $id_trcd = $value->trcd;
            }
            $arr = $this->m_stock_barcode->detailTransaction($id_trcd);
        }else{
            $arr = array("response" => "false");
        }
        echo json_encode($arr);
    }

	function detailTransactionRange()
    {
        $header = "header";
        $detail = "detail";
		$from = $this->input->post('from');
        $to = $this->input->post('to');
		// $loccd = $this->input->post('loccd');
		$username = $this->username;
        // $username = 'BID06';
		// print_r($to);
		// die('disini');
		// die($username);
        $arr['arraydata'] = $this->m_stock_barcode->getDataDetailRange($to, $from, $username);
        if($arr['arraydata'] == NULL){
			$arr['response'] ='false';
		}else{
			$arr['response'] ='true';
		}
        echo json_encode($arr);
    }

	public function postTraceBarcode($barcode)
    {
		
        $data['user'] = $this->username;
		if($data['user'] == "BID06") {
			$arr = $this->m_stock_barcode->getTraceBarcode($barcode);
		} else{
		    $arr = $this->m_stock_barcode->getTraceBarcode($barcode);
		}
        //  $arr['no']=1;
        echo json_encode($arr);
    }


    public function formStockBarcode() {
			$data['form_header'] = "Stock Barcode";
			$data['form_action'] = "stk/barcode/trx/list";
			$data['icon'] = "icon-pencil";
			$data['form_reload'] = 'stk/barcode';
			$data['username'] = $this->username;
      if($this->username != null) {
           $data['from1'] 	= date("Y-m-d");
           $data['to1'] 	= date("Y-m-d");
           $data['from'] 	= date("Y-m-d");
           $data['to'] 	= date("Y-m-d");
		
		   $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);

		   if($this->username == "BID06" || $this->username == "IDSMG81") {
				 //echo "sd";
			   $this->setTemplate($this->folderView.'stockBarcodeFormV2', $data);
		   } else {
			   //$this->setTemplate($this->folderView.'stockBarcodeForm', $data);
				 $this->setTemplate($this->folderView.'stockBarcodeFormV2', $data);
		   }
			} else {
					$this->setTemplate('includes/inline_login', $data);
			}
	}

	
	//$route['stk/barcode/check/(:any)/(:any)'] = 'transaction/stock_barcode/getDataFullName/$1/$2';
	public function getDataFullName($type, $value) {
		$res = $this->m_stock_barcode->checkMsMember($type, $value);
		echo json_encode($res);
	}

	//$route['stk/barcode/simpan'] = 'transaction/stock_barcode/simpanBarcode';
	public function simpanBarcode() {
		$data = $this->input->post(NULL, TRUE);

		/* echo "<pre>";
		print_r($data);
		echo "</pre>"; */

		$res = $this->m_stock_barcode->simpanBarcodeV2($data);
		echo json_encode($res);
	}

	public function fromTraceBarcode() {
		$data['form_header'] = "Trace Barcode";
		$data['form_action'] = "stk/barcode/trx/list";
		$data['icon'] = "icon-pencil";
		$data['form_reload'] = 'stk/barcode/trace';
		$data['username'] = $this->username;
		if($this->username != null) {
			$data['from1'] 	= date("Y-m-d");
			$data['to1'] 	= date("Y-m-d");
			$data['from'] 	= date("Y-m-d");
			$data['to'] 	= date("Y-m-d");
			$data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);
			$this->setTemplate($this->folderView.'traceBarcodeForm', $data);
		 } else {
			$this->setTemplate('includes/inline_login', $data);
		 }
        
	}

	//$route['stk/barcode/wh/list'] = 'transaction/stock_barcode/getListWH';
	public function getListWH() {
		$res = jsonFalseResponse("Tidak ada warehouse");
	    if($this->username != null) {

			$arr = $this->m_stock_barcode->getListWH();
			if($arr != null) {
				$res = jsonTrueResponse($arr);
			}
		} else {
			$res = jsonFalseResponse("Silahkan Reload form dan login kembali..");
		}
		echo json_encode($res);
	}

	//$route['stk/barcode/trx/list'] = 'transaction/stock_barcode/getListTrx';
	public function getListTrx() {
		$data = $this->input->post(NULL, TRUE);
		//trx stockist/sub stockist
		if($this->groupid == "3" || $this->groupid == "4") {

			$data['result'] = $this->m_stock_barcode->getListTTPStockist($this->stockist, $data['from'], $data['to']);
            $this->load->view($this->folderView.'listDOStk',$data);
		}
		//trx warehouse master
		elseif($this->groupid == "8") {

		}
		//trx warehouse branch
		elseif($this->groupid == "9") {

			$data['result'] = $this->m_stock_barcode->getListDOStk($data['sendTo'], $data['from'], $data['to']);
			//print_r($data['result']);
			$this->load->view($this->folderView.'listDoWhToStk',$data);
		}
		//trx stockist Master BID06 or Admin Backoffice BID06
		elseif($this->groupid == "1" || $this->groupid == "2") {
			//STOCK MOVEMENT FROM WH HQ to WH BRANCH
			if($data['trx_type'] == "stock_move")
			{
				$data['result'] = $this->m_stock_barcode->getListDOWHtoWH($data['sendTo'], $data['from'], $data['to']);
				//print_r($data['result']);
				$this->load->view($this->folderView.'listDoWhToWh',$data);
			}
			//GENERATED PACKING LIST
			elseif($data['trx_type'] == "gen_pl") {
				$data['result'] = $this->m_stock_barcode->getPackingListByDest($data['sendTo'], $data['from'], $data['to']);
				$this->load->view($this->folderView.'listGeneratedPL',$data);
			}
			//BARCODE TRACKING
			elseif($data['trx_type'] == "bc_track") {
				$data['result'] = $this->m_stock_barcode->trackingBarcode($data['sendTo']);
				//print_r($data['result']);
				$this->load->view($this->folderView.'barcodeTracking',$data);
			}
			//SALES STOCKIST / INVOICE
			else
			{
			    if($data['trx_type'] == "sales_sc") {
			    	$dotype = "S";
			    } else {
			    	$dotype = "M";
			    }
			    $arr = $this->m_stock_barcode->getListDOWHtoStk($dotype, $data['sendTo'], $data['from'], $data['to']);
				//print_r($arr);
			}
		}
	}



	//$route['stk/barcode/trx/id/(:any)'] = 'transaction/stock_barcode/getDetailProductByTrxId/$1';
	public function getDetailProductByTrxId($id) {
		//trx stockist/sub stockist
		if($this->groupid == "3" || $this->groupid == "4") {
			$data['result'] = $this->m_stock_barcode->getDataProductByTTP($id);
			//print_r($data['result']);
			if($data['result']['response'] == "false") {
				echo "<script>alert('Data is empty or invalid..')</script>";
			} else {
				//print_r($data['result']);
				$this->load->view($this->folderView.'listDOStk_detail',$data);
			}
		}
		//trx warehouse master
		elseif($this->groupid == "8") {

		}
		//trx warehouse branch
		elseif($this->groupid == "9") {
			$data['result'] = $this->m_stock_barcode->getDataDetailDO($id);
			//print_r($data['result']);
			if($data['result']['response'] == "false") {
				echo "<script>alert('Data is empty or invalid..')</script>";
			} else {
				$this->load->view($this->folderView.'listDoWhToStk_detail',$data);
			}
		}
		//trx stockist Master BID06 or Admin Backoffice BID06
		elseif($this->groupid == "1" || $this->groupid == "2") {
			$data['result'] = $this->m_stock_barcode->showSummaryPackingListByID($id);
			$this->load->view($this->folderView.'showSummaryPackingList',$data);
		}
	}

	//$route['stk/barcode/process/(:any)/(:any)'] = 'transaction/stock_barcode/getListProductBarcode/$1/$2';
	function getListProductBarcode($trcd, $prdcd)
    {
        $arr = jsonFalseResponse("Data not found..");
        //TRX STOCKIST / SUB STOCKIST
        if($this->groupid == "3" || $this->groupid == "4") {

			$data['result'] = $this->m_stock_barcode->getListProductBarcode($this->stockist, $trcd, $prdcd);
			if($data['result'] != null) {
				$arr = jsonTrueResponse($data['result']);
			}
		}
		//TRX WAREHOUSE MASTER
		elseif($this->groupid == "8") {

		}
		//TRX WAREHOUSE BRANCH
		elseif($this->groupid == "9") {
			$data['result'] = $this->m_stock_barcode->listPrdBarcodeStk($trcd,$prdcd);
            if($data['result'] != null) {
				$arr = jsonTrueResponse($data['result']);
			}
		}
		//TRX HQ / BID06
		elseif($this->groupid == "1" || $this->groupid == "2") {
			//$data['result'] = $this->m_stock_barcode->listPrdBarcodeStk($trcd,$prdcd);
			$data['result'] = $this->m_stock_barcode->getDataBarcode($trcd, $prdcd);
            if($data['result'] != null) {
				$arr = jsonTrueResponse($data['result']);
			}
		}

        echo json_encode($arr);
    }

	//$route['stk/barcode/prepare/pl'] = 'transaction/stock_barcode/preparePackingList';
	function preparePackingList() {
		if($this->username != null) {
			$data['form'] = $this->input->post(NULL, TRUE);
			$dataarr = set_list_array_to_string($data['form']['pilih']);
			//print_r($pilih);
			$data['result'] = $this->m_stock_barcode->groupingDO($dataarr);
			if($data['result']['response'] == "false") {
				jsAlert("Data not found..");
			} else {
				//print_r($data);
				$this->load->view($this->folderView.'preparePackingList',$data);
			}

		} else  {
	       	jsAlert();
	    }
	}

	//$route['stk/barcode/generate/pl'] = 'transaction/stock_barcode/generatePackingList';
	function generatePackingList() {
		$listDO = $this->input->post('listDO');
		$listDO2 = str_replace("`", "'", $listDO);
		$arr = array("response" => "false", "message" => "INSERT PACKING LIST FAILED");
		$id = $this->m_stock_barcode->generatePLNo();
		$header = $this->m_stock_barcode->insertHeaderPL($id, $listDO);
		if($header) {
		   $detail = $this->m_stock_barcode->insertDetailSummaryPL($id);
		   if($detail) {
		   	  $upd = $this->m_stock_barcode->updatePLonGDOhdr($id, $listDO2);
		   	    $shipinfo = array(
				  "trcdGroup" => $id,
				  "loccd" => $this->session->userdata('kodegudang'),
				  "loccdTo" => $this->input->post('sendTo'),
				  "info" => $this->input->post('info')
				);
		   	  $arr = array("response" => "true", "shipinfo" => $shipinfo);
			  $data['result'] = $this->m_stock_barcode->showSummaryPackingListByID($id);
			  $this->load->view($this->folderView.'showSummaryPackingList',$data);
		   } else {

		   }
		} else {
		   $this->m_stock_barcode->deletePL("Hilal_BC_newtrh_wh", $id);
		}
	}


  public function saveBarcode()
    {
        $trcd = $trcd = $this->input->post('trcd');
	    $arr = $this->m_stock_barcode->saveInputBarcode();

    echo json_encode($arr);
    }

	public function saveBarcode_bak() {

        $trcd = $trcd = $this->input->post('trcd');
        $arr = jsonFalseResponse("Trx No. $trcd sudah pernah di barcode..!!");
		if($this->groupid == "3" || $this->groupid == "4") {

			$arr = $this->m_stock_barcode->saveInputBarcode($trcd);
		}
		//trx warehouse master
		elseif($this->groupid == "8") {
			$check = $this->m_stock_barcode->validateSaveBarcodeBID06($trcd);
        	$arr = $this->m_stock_barcode->saveInputBarcodeBID06($check);
		}
		//trx warehouse branch
		elseif($this->groupid == "9") {
			$arr = $this->m_stock_barcode->saveBarcodeWHToStk();
		}
		//trx stockist Master BID06 or Admin Backoffice BID06
		elseif($this->groupid == "1" || $this->groupid == "2") {

		}

        echo json_encode($arr);

	}

}