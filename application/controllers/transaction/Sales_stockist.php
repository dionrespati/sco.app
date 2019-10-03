<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


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
		   		
        if($this->username != null) {
           $data['from'] 	= date("Y-m-d");
           $data['to'] 	= date("Y-m-d");
		   $data['sc_dfno'] 	= $this->stockist;			
		  // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);   
		   $this->setTemplate($this->folderView.'inputTTP', $data); 
        } else {
           $this->setTemplate('includes/inline_login', $data);
        } 
	}

	//$route['sales/stk/input/list'] = 'transaction/sales_stockist/getListInputSalesStockist';
	public function getListInputSalesStockist() {
		
		if($this->username != null) {
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['form'] = $this->input->post(NULL, TRUE);
			$data['stk_login'] = $this->stockist;
			if($data['form']['searchby'] == "receiptno") {
				$data['result'] = $this->m_sales_stk->getListSsrByKW($data['form'], "SB1");
				if($data['result'] ==  null) {
					echo setErrorMessage("Data ".$data['form']['paramValue']." tidak ditemukan atau bukan milik ".$this->stockist);
				} else {
					$this->load->view($this->folderView.'inputTTPListResultByKW',$data);
				}	
			} else {
				$data['result'] = $this->m_sales_stk->getListSalesStockist($data['form'], "SB1");
				$this->load->view($this->folderView.'inputTTPListResult',$data);
			}	
		} else {
           jsAlert();
        } 
	}

	//$route['sales/stk/delete/(:any)/(:any)'] = 'transaction/sales_stockist/deleteTrx/$1/$2';
	public function deleteTrx($param, $id) {
		if($this->username != null) {	
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['header'] = $this->m_sales_stk->getTrxByTrcdHead($param, $id);
			if($data['header'] == null) {
				$res = jsonFalseResponse("No Transaksi : $id tidak valid..");
				echo json_encode($res);
				return;
			} 

			$header = $data['header'][0];
			if($header->flag_batch == "1" OR $header->batchno != null) {
				$res = jsonFalseResponse("No Transaksi : $id sudah di generate dengan no : ".$header->batchno);
				echo json_encode($res);
				return;
			} 

			$res = $this->m_sales_stk->deleteTrx($id);
			echo json_encode($res);
			/* $res = jsonTrueResponse(null, "Data $id berhasil dihapus..");
			echo json_encode($res);
			return; */
		} else {
			$res = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
			echo json_encode($res);
			return;
		} 	
	}

    //$route['sales/stk/update/(:any)/(:any)'] = 'transaction/sales_stockist/updateTrx/$1/$2';
	public function updateTrx($param, $id) {
		if($this->username != null) {
			//$this->load->model("transaction/sales_member_model", "m_sales_member");
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['header'] = $this->m_sales_stk->getTrxByTrcdHead($param, $id);
			if($data['header'] != null) {
				$data['form_action'] = "sales/stk/save";
				$data['sctype'] = $data['header'][0]->sctype;
			    $data['co_sctype'] = $data['header'][0]->co_sctype;
					
				if($data['header'][0]->trtype == "VP1") {
					$data['prd_voucher'] = 1;
					$data['jenis_bayar'] = "pv";
					$data['listPay'] = $this->m_sales_stk->getListPaymentProductVoucher();
				} else {
					$data['prd_voucher'] = 0;
					$prefix_trcd = substr($data['header'][0]->trcd, 0 , 2);
					if($prefix_trcd == "ID") {
						$data['jenis_bayar'] = "id";
						$data['listPay'] = $this->m_sales_stk->getListPaymentTypeOnlyCash();
					} else {
						$data['jenis_bayar'] = "cv";
						$data['listPay'] = $this->m_sales_stk->getListPaymentType();
					}

					
				}
				
				$data['detail'] = $this->m_sales_stk->getDetailProduct("trcd", $data['header'][0]->trcd);
				$data['payment'] = $this->m_sales_stk->getDetailPayment("trcd", $data['header'][0]->trcd);
				$data['currentperiod']= $this->m_sales_stk->getCurrentPeriod();
				$data['ins'] = "2";
				$data['pricecode'] = $this->pricecode;
				

				$data['head_form'] = "Input TTP Pembelanjaan Member";
				$data['sc_dfno_readonly'] = "";
				$data['sc_co_readonly'] = "";

				if($data['ins'] == "2") {
					$data['submit_value'] = "Simpan Transaksi";
				} else {
					$data['submit_value'] = "Update Transaksi";
				}
				$jumPrd = count($data['detail']);
				$data['tot_dp'] = $data['header'][0]->tdp;
				$data['tot_bv'] = $data['header'][0]->tbv;
				$data['jum_rec'] = $jumPrd;

				$data['start_tabidx'] = 7;
				
				$this->load->view($this->folderView.'inputTTPSubForm2',$data);	
				$this->load->view($this->folderView.'viewProductPayment',$data);
				$this->load->view($this->folderView.'viewPaymentForm',$data);
			} else {
				jsAlert("Transaction ID : $id is Invalid..");
			}
			
		} else {
           jsAlert();
        } 
	}

	//$route['sales/stk/info/(:any)'] = 'transaction/sales_stockist/getStockistInfo/$1';
	public function getStockistInfo($ids) {
		if($this->username != null) {
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$hasil = jsonFalseResponse("Kode Stockist Salah..");
			$arr = $this->m_sales_stk->getStockistInfo($ids);
			if($arr != null) {
				$hasil = jsonTrueResponse($arr);
			}
			echo json_encode($hasil);
			//echo "string";
		} else {
           jsAlert();
        } 
	}
	
	
	
	//$route['sales/stk/input/form'] = 'transaction/sales_stockist/inputTrxForm';
	public function inputTrxForm() {
		if($this->username != null) {
			$this->load->model("transaction/sales_member_model", "m_sales_member");
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['currentperiod']= $this->m_sales_member->getCurrentPeriod();
			$data['form_action'] = "sales/stk/save";
			$data['ins'] = "1";
			$data['stockist'] = $this->stockist;
			$data['stockistnm'] = $this->stockistnm	;
			$data['pricecode'] = $this->pricecode;
			$sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
			$data['sctype'] = $sctype[0]->sctype;
			$data['listPay'] = $this->m_sales_stk->getListPaymentType();
			$data['start_tabidx'] = 5;
			$data['jenis_bayar'] = "";

			$this->load->view($this->folderView.'inputTTPForm',$data);	
			$this->load->view($this->folderView.'viewProductPayment',$data);
			$this->load->view($this->folderView.'viewPaymentForm',$data);
			
		} else {
           jsAlert();
        } 
	}
	
	//$route['sales/stk/save'] = 'transaction/sales_stockist/saveTrxStockist';
	public function saveTrxStockist() {
		if($this->username != null) {
			$this->load->library('form_validation');
			$data = $this->input->post(NULL, TRUE);
			
			if ($this->form_validation->run('inputTtpStockist') === TRUE) {
				$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
    			//check apakah produk ada yang kosong
				$jum = count($data['prdcd']);
				if($jum == 0) {
					echo json_encode(jsonFalseResponse("Produk tidak boleh kosong.."));
					return;
				} 

				//check apakah pembayaran kosong
				if(!isset($data['payChooseType'])) {
					echo json_encode(jsonFalseResponse("Pembayaran tidak boleh kosong.."));
					return; 
					
				} else {
					$jumPay = count($data['payReff']);
					for($i=0; $i < $jumPay; $i++) {
						if($data['payChooseType'][$i] == "01") {
							$data['payChooseValue'][$i] = floatval(str_replace('.', '', $data['payChooseValue'][$i]));
						} else {
							if($data['payChooseType'][$i] == "10") {
								$typeVchx = "P";
							} else if($data['payChooseType'][$i] == "08") {
								$typeVchx = "C";
							}
							$arr = $this->m_sales_stk->checkValidCashVoucher($data['dfno'],$data['payReff'][$i], $typeVchx);
							if($arr['response'] == "false") {
								echo json_encode($arr);
								return;
							} else {
								$datax = $arr['arrayData'][0];
								$data['payChooseValue'][$i] = $datax->VoucherAmt;
							}
						} 
						
						
					}
				}
				//check apakah distributor valid
				
				$ifMemberExist = $this->m_sales_stk->getValidDistributor($data['dfno']);
				if($ifMemberExist == null) {
					echo json_encode(jsonFalseResponse("ID Member tidak valid.."));
					return;
				}

				//check valid product
				$sub_tot_bv = 0;
				$sub_tot_dp = 0;
				$total_dp = 0;
				$total_bv = 0;
				$jumPrd = count($data['prdcd']);
				for($i=0; $i<$jumPrd; $i++) {
					$prdcd = $data['prdcd'][$i];
					$qty = $data['jum'][$i];

					$prdArr = $this->m_sales_stk->showProductPrice($prdcd, $data['pricecode']);
					if($prdArr['response'] == "false") {
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

				$arr = array(
					"table" => "sc_newtrh",
					"param" => "orderno",
					"value" => $data['orderno'],
					"db" => "klink_mlm2010",
				);

				if($data['ins'] == "1") {
					$checkOrderno = $this->m_sales_stk->checkExistingRecord($arr);	
					//CHECK apakah ORDERNO double
					if($checkOrderno != null) {
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
				
			} else {
				$this->form_validation->set_error_delimiters("","");
			    echo json_encode(jsonFalseResponse(validation_errors()));
			    //jsAlert(validation_errors());
			}
			
		} else {
           jsAlert();
        } 
	}
	
	//$route['sales/sub/ttp/input'] = 'transaction/sales_stockist/inputTtpSub';
	public function inputTtpSub() {
		$data['form_header'] = "Input TTP MS/Sub/Stockist";
        $data['form_action'] = "sales/sub/ttp/input";
        $data['icon'] = "icon-pencil";
		$data['form_reload'] = 'sales/sub/ttp/input';   		   
			
        if($this->username != null) {
        	$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
           $data['from'] 	= date("Y-m-d");
           $data['to'] 	= date("Y-m-d");
		   $data['sc_dfno'] 	= $this->stockist;	
		   $data['payment'] = null;	
		  // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);   
		   $this->setTemplate($this->folderView.'inputTTPSub', $data); 
        } else {
           $this->setTemplate('includes/inline_login', $data);
        } 
        //echo "sds";
	}
    //intval(str_replace(',', '', $myVal))
	//$route['sales/sub/input/form'] = 'transaction/sales_stockist/inputTrxFormSub';
	public function inputTrxFormSub() {
		if($this->username != null) {
			//$this->load->model("transaction/sales_member_model", "m_sales_member");
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['form_action'] = "sales/stk/save";
			$data['currentperiod']= $this->m_sales_stk->getCurrentPeriod();
			$data['ins'] = "1";
			
			$data['stockist'] = $this->stockist;
			$data['stockistnm'] = $this->stockistnm	;
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

			if($data['ins'] == "1") {
				$data['submit_value'] = "Simpan Transaksi";
			} else {
				$data['submit_value'] = "Update Transaksi";
			}
			$data['tot_dp'] = 0;
			$data['tot_bv'] = 0;
			$data['jum_rec'] = 1;

			$this->load->view($this->folderView.'inputTTPSubForm2',$data);	
			$this->load->view($this->folderView.'viewProductPayment',$data);
			$this->load->view($this->folderView.'viewPaymentForm',$data);
			
		} else {
           jsAlert();
        } 
	}

	//$route['sales/product/pvr/check'] = 'transaction/sales_stockist/showProductPriceForPvr';
	function showProductPriceForPvr() {
		
		if($this->username != null) {
		  $productcode = $this->input->post('productcode');
		  $prdcdcode = strtoupper($productcode);
		  $pricecode = $this->input->post('pricecode');
		  $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
		  $data = $this->m_sales_stk->showProductPriceForPvr($prdcdcode, $pricecode);
		  echo json_encode($data);
		} else {
		  $err = jsonFalseResponse("Sesi anda habis, silahkan login kembali..");
		  echo json_encode($err);
		}
	  }

	//$route['sales/vc/check/(:any)/(:any)/(:any)'] = 'transaction/sales_stockist/checkValidVoucherCash/$1/$2/$3';
	function checkValidVoucherCash($distributorcode,$vchnoo, $paytype)
    {
        $response = jsonFalseResponse("No Voucher salah atau tidak sesuai dengan Member ".$distributorcode);	
        if($this->username != null) {	
	        $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$arr = null;
			if($paytype == "10") {
				$arr = $this->m_sales_stk->checkValidCashVoucher($distributorcode,$vchnoo, "P");				
			} else {
				$arr = $this->m_sales_stk->checkValidCashVoucher($distributorcode,$vchnoo,"C");
			}
	        
			echo json_encode($arr);
		} else {
           jsAlert();
        } 
    }

	/*----------------
	 * PVR
	 * ---------------*/
	 
	 //$route['sales/pvr/input'] = 'transaction/sales_stockist/inputSalesPvr';
	 public function inputSalesPvr() {
	 	$data['form_header'] = "Input PVR MS/Sub/Stockist";
        $data['form_action'] = "sales/sub/ttp/input";
        $data['icon'] = "icon-pencil";
		$data['form_reload'] = 'sales/pvr/input';   		   
			
        if($this->username != null) {
        	$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
           $data['from'] 	= date("Y-m-d");
           $data['to'] 	= date("Y-m-d");
		   $data['sc_dfno'] 	= $this->stockist;	
		  	
		  // $data['stk_barcode_opt'] = $this->m_stock_barcode->getListStkbarMenu($this->groupid);   
		   $this->setTemplate($this->folderView.'inputPVRSub', $data); 
        } else {
           $this->setTemplate('includes/inline_login', $data);
        } 
	 }
	 
	 //$route['sales/pvr/input/list'] = 'transaction/sales_stockist/getListInputPvrSalesStockist';
	public function getListInputPvrSalesStockist() {
		
		if($this->username != null) {
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['form'] = $this->input->post(NULL, TRUE);
			$data['result'] = $this->m_sales_stk->getListSalesStockist($data['form'], "VP1");
			$this->load->view($this->folderView.'inputPvrListResult',$data);	
		} else {
           jsAlert();
        } 
	}
	
	//$route['sales/pvr/input/form'] = 'transaction/sales_stockist/inputTrxPvrForm';
	public function inputTrxPvrForm() {
		if($this->username != null) {
			$this->load->model("transaction/sales_member_model", "m_sales_member");
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['currentperiod']= $this->m_sales_member->getCurrentPeriod();
			$data['form_action'] = "sales/stk/save";
			$data['ins'] = "1";
			$data['stockist'] = $this->stockist;
			$data['stockistnm'] = $this->stockistnm	;
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

			if($data['ins'] == "1") {
				$data['submit_value'] = "Simpan Transaksi";
			} else {
				$data['submit_value'] = "Update Transaksi";
			}
			$data['tot_dp'] = 0;
			$data['tot_bv'] = 0;
			$data['jum_rec'] = 1;

			$this->load->view($this->folderView.'inputTTPSubForm2',$data);	
			$this->load->view($this->folderView.'viewProductPayment',$data);
			$this->load->view($this->folderView.'viewPaymentForm',$data);
		} else {
           jsAlert();
        } 
	}

	//$route['sales/pvr2/input/form'] = 'transaction/sales_stockist/inputTrxPvrForm2';
	public function inputTrxPvrForm2() {
		if($this->username != null) {
			$this->load->model("transaction/sales_member_model", "m_sales_member");
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['currentperiod']= $this->m_sales_member->getCurrentPeriod();
			$data['form_action'] = "sales/stk/save/pvr";
			$data['ins'] = "1";
			$data['stockist'] = $this->stockist;
			$data['stockistnm'] = $this->stockistnm	;
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

			if($data['ins'] == "1") {
				$data['submit_value'] = "Simpan Transaksi";
			} else {
				$data['submit_value'] = "Update Transaksi";
			}
			$data['tot_dp'] = 0;
			$data['tot_bv'] = 0;
			$data['jum_rec'] = 1;

			$this->load->view($this->folderView.'formInputPvr',$data);	
			/* $this->load->view($this->folderView.'viewProductPayment',$data);
			$this->load->view($this->folderView.'viewPaymentForm',$data); */
		} else {
           jsAlert();
        } 
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
		if($checkValidMember == null) {
		  echo json_encode($this->jsonFalseResponse("ID Member tidak valid atau TERMINATION/RESIGNATION"));
		  //echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
		  return;
		}
		$x['fullnm'] = $checkValidMember[0]->fullnm;

		//check valid voucher
		if(!array_key_exists('vchno', $data) || !isset($data['vchno'])) {
			echo json_encode($this->jsonFalseResponse("Pembayaran minimal menggunakan 1 produk voucher.."));
			//echo "<input type='button' name='btns' value='<< Kembali' onclick='backToMainForm()' />";
			return;
		}
		

		$jumVch = count($data['vchno']);
		$x['total_nilai_vch'] = 0;
		for($i=0; $i<$jumVch; $i++) {
		   $prefix = substr($data['vchno'][$i], 0, 3);
		   if($prefix == "XHD") {
			   $x['promo'] = "hydro";
			   $x['orderno'] = $data['vchno'][$i];
		   } else if($prefix == "XPV" || $prefix == "ZVO" || $prefix == "XPP") {
			   $x['promo'] = "hadiah";
			   $x['orderno'] = $data['vchno'][$i];
		   } else {
			  if($i == 0) { 
				$x['promo'] = "reguler";  
			  	$x['orderno'] = $data['vchno'][$i];
			  }
		   }	

		   $arr = $this->m_sales_stk->checkValidCashVoucher($idmember,$data['vchno'][$i], "P");
		   if($arr['response'] == "false") {
			 echo json_encode($arr);
			 return;
		   }

			$x['payChooseType'][$i] = "10";
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

		$jumPrd = count($data['productcode']);
		$x['total_nilai_prd'] = 0;
		for($i=0; $i<$jumPrd; $i++) {
			if($x['promo'] == "reguler") {
				$checkValidPrd = $this->m_sales_stk->showProductPriceForPvr($data['productcode'][$i], $data['pricecode']);
				if($checkValidPrd['response'] == "false") {
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
				
			} else {
				$checkValidPrd = $this->m_sales_stk->getListProdPromoByVchAndPrdcd($data['vchno'][0], $data['productcode'][$i]);
				if($checkValidPrd == null) {
					$err = $data['productcode'][$i]." / ".$data['productname'][$i]." tidak termasuk dalam voucher ".$data['vchno'][0];
					echo json_encode($this->jsonFalseResponse($err));
					return;
				}

				/* $harga = $checkValidPrd['arraydata'][0]; */
				$x['total_nilai_prd'] += $data['qty'][$i] * $data['dp_real'][$i];
				$x['prdcd'][$i] = $data['productcode'][$i];
				$x['jum'][$i] = $data['qty'][$i];
				$x['harga'][$i] = $data['dp_real'][$i];
				$x['poin'][$i] = $data['dp_real'][$i];
				$x['sub_tot_dp'][$i] = $data['qty'][$i] * $data['dp_real'][$i];
				$x['sub_tot_bv'][$i] = $data['qty'][$i] * 0;
			}
			
			

		}

		$x['cash_hrs_dibayar'] = 0;
		if($x['total_nilai_prd'] > $x['total_nilai_vch']) {
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

	//$route['sales/correction/(:any)'] = 'transaction/sales_stockist/koreksiTransaksi/$1';
	public function koreksiTransaksi($trcd) {
		$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
		$check = $this->m_sales_stk->cekHeaderTrx("trcd", $trcd);
		/* echo "<pre>";
		print_r($check);
		echo "</pre>"; */
		if($check == null) {
			$arr = jsonFalseResponse("Transaksi dengan nomor $trcd tidak ada / tidak valid");
			return $arr;
		}

		$header = $check['header'];
		if($header[0]->batchno != null && $header[0]->batchno != "") {
			$arr = jsonFalseResponse("Transaksi dengan nomor $trcd sudah di generate dengan no ".$header[0]->batchno.", silahkan di recover terlebih dahulu..");
			return $arr;
		}

		if($header[0]->csno != null && $header[0]->csno != "") {
			$arr = jsonFalseResponse("Transaksi dengan nomor $trcd sudah di proses dengan no ".$header[0]->csno);
			return $arr;
		}

		$res = $this->m_sales_stk->koreksiTransaksi($check);
		echo json_encode($res);
	}
	
}