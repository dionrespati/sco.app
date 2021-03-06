<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scan_voucher extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaction/scan_voucher_model');
        $this->folderView = 'transaction/scan_deposit/';
    }

    public function formScanDeposit()
    {
        $data['form_header'] = "Scan Deposit";
        $data['form_action'] = base_url('scan/list');
        $data['icon'] = "icon-edit";
        $data['form_reload'] = 'scan';

        if ($this->username != null) {
            //cek apakah group adalah ADMIN atau BID06
            $data['userlogin'] = $this->stockist;
            $this->setTemplate($this->folderView.'getDepositForm', $data);
        } else {
            //echo sessionExpireMessage(false);
            $this->setTemplate('includes/inline_login', $data);
        }
    }

    //$route['scan/list'] = 'transaction/scan_voucher/getDeposit';
    public function getDeposit()
    {
        if ($this->stockist != null) {
            $data = $this->input->post(null, true);
            if($this->stockist != "BID06") {
                $username = $this->stockist;
            } else {   
                $username = $data['idstkk_vchdep'];
            }    
            $data['list'] = $this->scan_voucher_model->show_deposit_list($username, $data['search']);
            
            if ($this->stockist === 'BID06') {
                $this->load->view($this->folderView.'listScanDepositDev', $data);
            } else {
                $this->load->view($this->folderView.'listScanDeposit', $data);
            }
        }
    }

    //$route['scan/list/detail/voucher/(:any)'] = 'transaction/scan_voucher/getListScan/$1';
    public function getListScan($id= '')
    {
        // echo "isi : $id";
        if ($this->stockist != null) {
            //$data['user'] = $this->stockist;
            $data['form_action'] = " ";
            $data['header_form'] = "Scan/Klaim Voucher";
            $data['dateNow'] = date('d F Y');
            $data['parameter']=1;
            $data['edit']='';
            $data['status']='';
            $kat='KOSONG';
            $data['kategori']='<input type="hidden"  class="span12 typeahead" id="kategori" name="kategori" value="all" readonly="readonly"/>';
            $selected1="";
            $selected2="";
            if ($id != '') {
                $data['submit'] = 'Edit';
                $data['id'] = $id;
                $data['LIST_DETAIL'] = $this->scan_voucher_model->getDataDetail($id); //for view data option select

                $result = $this->scan_voucher_model->getDataEdit($id);
                //$data['edit']='readonly="yes"';
                if ($result[0]->status==0) {
                    $data['status']='readonly="yes"';
                    $data['nosave']='yes';
                }
                $data['distributorcode'] = $result[0]->dfno;
                $data['createdt'] = date('d F Y', strtotime($result[0]->createdt));
                $data['sisa']=$result[0]->total_deposit;
                $data['no_trx'] = $result[0]->no_trx;

                $kat = $result[0]->kategori;
                if ($kat=='VC') {
                    $selected1 ='selected';
                } else {
                    $selected2 ='selected';
                }
            } else {
                $data['id'] = '';
                $data['submit'] = 'Add';
                $data['user'] = $this->stockist;
            }
        
            $data['label']='';
            $data['kategori']='
                                          <td>&nbsp;&nbsp;Jenis Voucher&nbsp;</td>
                                          <td>
                                              <select id="kategori2" name="kategori2" class="form-control" '.$data['edit'].' required="required" onchange="locker()">
                                                  ';
            $data['kategori'].='
                                                  <option value="VC" selected>Voucher Cash</option>
                                              </select>
                                          </td>
                                     
                                        <input type="hidden"  class="span12 typeahead" id="kategori" name="kategori" value="VC" readonly="readonly"/>';
            $tipe = 'sub';
            $mscode = '';
            
            if($this->stockist !== "BID06") {
                $data['user'] = $this->stockist;
                $data['edit']='readonly="yes"';
            } else {   
                if ($id === '') {
                    $data['user'] = $this->stockist;
                    $data['edit']='';
                } else {
                    $data['user'] = $result[0]->createnm;
                    $data['edit']='readonly="yes"';
                }
                
            }    
            $data['usergroup'] = $this->usergroup;
            $data['stk'] = $this->scan_voucher_model->get_stockist_info($tipe, $data['user']);
            /* echo '<pre>';
            print_r($data);
            echo '</pre>'; */
            //$this->load->view($this->folderView.'listDetailScan', $data);
            //echo "s";
            $this->load->view($this->folderView.'listDetailScanV2', $data);
            return $data['user'];
        } else {
            redirect('auth');
        }
    }

    //$route['scan/vch/delete'] = 'transaction/scan_voucher/hapusVchCash';
    public function hapusVchCash() {
        $data = $this->input->post(NULL, TRUE);
        $checkVch = $this->scan_voucher_model->hapusVchCash($data['voucher_key'], $data['id_member'], $data['id_deposit']);
        echo json_encode($checkVch);    
    }

    //$route['scan/list/detail/ttp/(:any)'] = 'transaction/scan_voucher/getTTPList/$1';;
    public function getTTPList($deposit)
    {
        $data['user'] = $this->stockist;
        $data['deposit'] = $deposit;
        $data['list'] = $this->scan_voucher_model->show_list_TTP($deposit);
        $result = $this->scan_voucher_model->getDataEdit($deposit);
        $data['nodeposit'] = $result[0]->no_trx;
        $data['sisaDeposit'] = $result[0]->total_deposit - $result[0]->total_keluar;

        /* if ($result[0]->status==1) {
            $data['status_generate'] = $result[0]->status;
        } else {
            $data['add'] = "";
        }  */
        $data['status'] = $result[0]->status;

        $this->load->view($this->folderView.'list_TTP', $data);
    }

    public function getVch()
    {
        $scan = $this->input->post('scan');
        $kaet = $this->input->post('kat');
        $idmemb = $this->input->post('idmemb');

        $threeDigit = substr($scan, 0, 3);
        $this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
        $check = $this->m_sales_stk->getTipePromoVch($threeDigit);
        if($check['response'] === "true") {
            $arrsx = $check['arrayData'][0];
            $resErr = jsonFalseResponse($arrsx->keterangan);
            echo json_encode($resErr);
            return;
        }

        //$checkVch = $this->scan_voucher_model->checkValidVchCashSatuan($scan, $idmemb);

        $checkVch = $this->scan_voucher_model->checkValidCashVoucher($idmemb, $scan, "C");
        if($checkVch['response'] == "true" && $checkVch['arrayData'][0]->VoucherAmt <= 0) {
            $arr = array(
                "response" => "false",
                "message" => "Voucher bernilai 0 tidak dapat diinput di scan voucher deposit"
            );
            echo json_encode($arr);
            return;
        }
        echo json_encode($checkVch);
        
        /* $nilai = $this->scan_voucher_model->getVch($scan, $idmemb);
        $kategori= strtolower($scan[0]);
        if ($kaet=='KOSONG') {
            $arr = array("response" => "kosong", "arraydata" => "g nemu", "scan"=>$scan);
        } else {
            if ($kategori=='p'&&$kaet=='VC') {
                $arr = array("response" => "CPC", "arraydata" => "g nemu", "scan"=>$scan);
            } else {
                if ($nilai==null) {
                    $arr = array("response" => "false", "arraydata" => "g nemu", "scan"=>$scan);
                } else {
                    if ($nilai[0]->status=='0' && $nilai[0]->claimstatus=='0') {
                        $sad=true;
                        $exp=strtotime($nilai[0]->YEY);
                        $exd=strtotime($nilai[0]->XD);
                        if ($exp < $exd) {
                            $sad=false;
                        }
                        $nilai[0]->category="Voucher Cash";
                        if ($kategori=='p') {
                            $nilai[0]->category="Voucher Product";
                        }
                        if ($sad==true) {
                            $arr = array("response" => "true", "arraydata" => $nilai[0], "scan"=>$scan, "expiri"=>$exp, "now"=>$exd, 'sad'=>$sad);
                        } else {
                            $arr = array("response" => "expired", "arraydata" => $nilai[0], "scan"=>$scan, "expiri"=>date("d-M-Y", strtotime($nilai[0]->YEY)), "expiri2"=>date("d-M-Y", strtotime($nilai[0]->XD))
                            ,"expiri3"=>$exp, "expiri4"=>$exd
                            );
                        }
                    } else {
                        $arr = array("response" => "claimed", "arraydata" => $nilai[0], "scan"=>$scan, "oleh"=>(isset($nilai[0]->stokis) ? $nilai[0]->stokis : $nilai[0]->stokis2), "tgl"=>(isset($nilai[0]->claim_date) ? date("d-M-Y", strtotime($nilai[0]->claim_date)) : date("d-M-Y", strtotime($nilai[0]->updatedt)))   );
                    }
                }
            }
        } */
        //echo json_encode($arr);
    }

    public function simpanScan()
    {
        $scan=$this->input->post('idpendaftar');
        if (!empty($scan)) {
            foreach ($scan as $k=>$v) {
                $this->scan_voucher_model->saveScan($scan[$k]);
            }
        }
        echo json_encode(true) ;
    }

    public function simpanScan2($submit='')
    {
        $this->load->library('my_counter');
        $this->load->library('uuid');
        $scan=$this->input->post('idpendaftar');
        $amt=$this->input->post('amt');
        $jv=$this->input->post('jv');
        $memvc=$this->input->post('memvc');
        $kategori=$this->input->post('kategori');
        $substockistcode=$this->input->post('substockistcode');
        $total_all=$this->input->post('total_all');
        $user = $this->session->userdata('username');
        $trxno=$this->input->post('trxno');
        $masalah=0;
        
        if (!empty($scan)) {          
            /* $checkVch = $this->scan_voucher_model->checkValidVchCash($scan, $memvc);
            
            //print_r($checkVch);
            if($checkVch['response'] == "false") {
                echo json_encode($checkVch);
                return;
            }   */     
            
            for($ix = 0; $ix < count($scan); $ix++) {
                $arr = $this->scan_voucher_model->checkValidCashVoucher($memvc[$ix], $scan[$ix], "C");
                if($arr['response'] == "false") {
                    echo json_encode($arr);
                    return;
                }
            }    
        }
       
         if ($submit=='') {
            $POX=$this->my_counter->getCounter2($kategori, $this->uuid->v4());
            //echo $POX;
            $X=$this->uuid->v4();

            $arr_data = array(
                'id'=>$X,
                'no_trx'=>$POX,
                'loccd' => $substockistcode,
                'total_deposit' => $total_all,
                'total_keluar' => 0,
                'status' => 1,
                'is_active' => 1,
                'createdt' => date("Y-m-d H:i:s") ,
                'kategori' =>$kategori,
                'createnm' => $substockistcode
            );
            $this->scan_voucher_model->addHeader($arr_data);
        } else {
            $X=$submit;
            $POX=$trxno;
            $arr_data = array(
                'total_deposit' => $total_all,
                'updatedt' => date("Y-m-d H:i:s") ,
                'updatenm' => $substockistcode
            );
           /*  echo "<pre>";
            print_r($arr_data);
            echo "</pre>"; */
            $this->scan_voucher_model->updateHeader($submit, $arr_data);
        } 
        if (!empty($scan)) {
            foreach ($scan as $k=>$v) {
                $arr_detail = array(
                    'id'=>$this->uuid->v4(),
                    'id_header'=>$X,
                    'no_trx'=>$POX,
                    'voucher_scan' => $scan[$k],
                    'nominal' => $amt[$k],
                    'status' => 1,
                    'is_active' => 1,
                    'kategori'=>$jv[$k],
                    'dfno'=>$memvc[$k],
                    'createdt' => date("Y-m-d") ,
                    'createnm' => $substockistcode
                );
                $this->scan_voucher_model->addDetail($arr_detail, $scan[$k]);
                /* echo "<pre>";
                print_r($arr_detail);
                echo "</pre>"; */
            }
        }
        //$arrRes = array()
        $resp = jsonTrueResponse(null, "Voucher berhasil ditambahkan ke $POX");
        echo json_encode($resp) ;
        
    }

    //$route['scan/ttp/view/(:any)/(:any)/(:any)'] = 'transaction/scan_voucher/viewTTP/$1/$2/$3';
    public function viewTTP($id = '', $deposit= '', $status='')
    {
        $data['user'] = $this->stockist;
        $data['form_action'] = site_url('/c_sales_subStockist/deleteTTPdeposit');
        $data['header_form'] = "View TTP";
        $data['dateNow'] = date('d F Y');
        $data['id'] = $id;
        $data['deposit'] = $deposit;
        $data['status']=$status;
        $result = $this->scan_voucher_model->getDataEditTTP($id);
        $data['dfno']=$result[0]->dfno;
        $data['sc_dfno']=$result[0]->sc_dfno;
        $data['nama_penuh']=$result[0]->nama_penuh;
        $data['fullnm']=$result[0]->fullnm;
        $data['trcd']=$result[0]->trcd;
        $data['bns']=$result[0]->bns;
        $data['orderno']=$result[0]->orderno;
        $data['tdp']=$result[0]->tdp;
        $data['trdt']=($result[0]->trdt);
        $data['desc']='Product Voucer';
        $data['LIST_DETAIL'] = $this->scan_voucher_model->getDataDetailProduk($id); //for view data option select
        $data['LIST_PAYMENT'] = $this->scan_voucher_model->getDataDetailPayment($id); //for view data option select
        $tipe = 'sub';
        $mscode = '';
        $data['listype'] = $this->scan_voucher_model->get_list_payment();
        $data['stk'] = $this->scan_voucher_model->get_stockist_info($tipe, $data['user']);
        $data['currentperiod'] = $this->scan_voucher_model->get_current_period();

        /* echo '<pre>';
        print_r($data['stk']);
        echo '</pre>'; */
        $this->load->view($this->folderView.'form_Ttp_Depo_Edit', $data);
        return $data['user'];
    }

    /* public function HapusDeposit($id, $pass)
    {
        if ($this->session->userdata('login') == true) {
            $userasli=$this->m_sales_substockist->cekauth($this->session->userdata('username'), $pass);
            if ($userasli==null) {
                echo "<br><div align=center class='alert alert-error'>No Record found..!!</div>";
            } else {
                $berhasil=$this->m_sales_substockist->HapusDeposit($id);
                if ($berhasil =true) {
                    $data['user'] = $this->session->userdata('username');
                    $username = $this->session->userdata('username');
                    $data['add'] = anchor('c_sales_pvr/getFormScan2', 'Deposit Baru', array('class'=>'btn btn-primary'));
                    $data['list'] = $this->m_sales_substockist->show_list_deposit($username);
                } else {
                    echo "<br><div align=center class='alert alert-error'>No Record found..!!</div>";
                }
            }
        } else {
            redirect('auth');
        }
    } */

    public function hapusDeposit() {
        $data = $this->input->post(null, true);
        $response = array(
            'res' => 'true',
            'message' => 'Voucher berhasil dihapus'
        );
        if ($this->stockist != null) {
            $this->scan_voucher_model->HapusDeposit($data['id']);
            echo json_encode($response);
        } else {
            // redirect('auth');
        }
    }

    //$route['scan/ttp/input/(:any)'] = 'transaction/scan_voucher/getFormTtpDeposit2/$1';
    /* public function getFormTtpDeposit2($id = '') {
        $data['user'] = $this->stockist;
        $data['form_action'] = site_url('/c_sales_subStockist/postFormTtpPvr');
        $data['header_form'] = "Input TTP Deposit Voucher";
        $data['dateNow'] = date('d F Y');
        $data['id'] = $id;

        $result = $this->scan_voucher_model->getDataEdit($id);
        $data['kategori']=$result[0]->kategori;
        $data['nodepo']=$result[0]->no_trx;
        $result2=$this->scan_voucher_model->getsisaBARU($id);
        foreach($result2 as $row) {
            $data['tdeposit']=$row->saldo;
            $data['tkeluar']=$row->payamt;
            $data['tersedia']=$row->saldo - $row->payamt;
            $this->scan_voucher_model->updateSaldo($id,$row->saldo,$row->payamt);
        }

        $data['desc']='Product Voucer';
        if($result[0]->kategori=='VC') {
            $data['desc']='Voucher Cash';
        }
        $data['jenis']=strrev(strtolower($result[0]->kategori));
        $tipe = 'sub';
        $mscode = '';

        $data['listype'] = $this->scan_voucher_model->get_list_payment();
        $data['stk'] = $this->scan_voucher_model->get_stockist_info($tipe,$data['user']);
        $data['currentperiod'] = $this->scan_voucher_model->getCurrentPeriod();

        $this->load->view($this->folderView.'form_ttp_depo',$data);
        return $data['user'];
    } */

    public function getFormTtpDeposit2($id = '') {
		if($this->username != null) {
			$this->load->model('transaction/Sales_stockist_model', 'm_sales_stk');
			$data['form_action'] = "scan/ttp/save";
			$data['currentperiod']= $this->m_sales_stk->getCurrentPeriod();
            $data['ins'] = "1";
            $data['sc_dfno'] = $this->stockist;
            $data['sc_dfnonm'] = $this->stockistnm;
            $data['sc_co'] = $this->stockist;
            $data['sc_conm'] = $this->stockistnm;
            $data['loccd'] = $this->stockist;
            $data['loccdnm'] = $this->stockistnm;
			$data['stockist'] = $this->stockist;
			$data['stockistnm'] = $this->stockistnm	;
			$data['pricecode'] = $this->pricecode;
			$data['listPay'] = $this->m_sales_stk->getListPaymentType();
			$data['start_tabidx'] = 7;
			$sctype = $this->m_sales_stk->getStockistInfo($data['stockist']);
		    $data['sctype'] = $sctype[0]->sctype;
			$data['co_sctype'] = $sctype[0]->sctype;
            $data['prd_voucher'] = 0;
            $data['head_form'] = "Input TTP Deposit Voucher";

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

            $data['iddepo']=$id;

            $result = $this->scan_voucher_model->getDataEdit($id);
            $data['kategori']="VC";
            $data['nodepo']=$result[0]->no_trx;
            $data['iddepo']=$id;
            $data['sisa_deposit']=$result[0]->total_deposit - $result[0]->total_keluar;
            $data['desc']='Product Voucer';
            if($data['kategori']=='VC') {
                $data['desc']='Voucher Cash';
            }
            $data['jenis']=strrev(strtolower($data['kategori']));

			$this->load->view('transaction/stockist/inputTTPSubForm2',$data);
			$this->load->view('transaction/stockist/viewProductPayment',$data);
			$this->load->view($this->folderView.'viewPaymentForm',$data);

		} else {
           jsAlert();
        }
	}

    public function postFormTtpPvr() {
        if($this->stockist != NULL) {
            $data['user'] = $this->stockist;
            $orderno = $this->input->post('orderno');
            $data['charge'] = $this->input->post('charge');
            $data['change_real'] = $this->input->post('change_real');
            $data['change'] = $this->input->post('change');
            $data['paynominal'] = $this->input->post('paynominal');
            $paynominal = $this->input->post('paynominal');
            $distributorcode = $this->input->post('distributorcode');
            $distributorname = $this->input->post('distributorname');
            $tipe_pay = $this->input->post('pay_type');
            $jenis = $this->input->post('jenis');
            $xhd = $this->input->post('xhd');
            $change_real = $this->input->post('change_real');

            $refnoo = $this->input->post('refno');
            $doublee = array_count_values($refnoo);

            $prdcdcode = $this->input->post('productcode');
            $productname = $this->input->post('productname');
            $qty = $this->input->post('qty');
            $dp = $this->input->post('dp_real');

            $j = 0;
            $jml_paytype = count($tipe_pay);
            for($s=0;$s < $jml_paytype;$s++) {
                $j = $j + 1;
                if($tipe_pay[$s] == '01') {
                    $pay_cash = $paynominal[$s] - $change_real;
                    $data['paynominals'] = $paynominal[$s];
                }
            }

            $total_dpR = 0;
            $jml_prod = count($prdcdcode);
            for($i=0;$i < $jml_prod;$i++) {
                $dpR = $dp[$i] * $qty[$i];
                $total_dpR += $dpR;
            }
            $data['tot_nominal'] = 0;
            for($i=0; $i < count($data['paynominal']);$i++) {
                $data['tot_nominal'] += $data['paynominal'][$i];
            }

            if($orderno == "" || $distributorcode == "" || $distributorname == "") {
                echo "ORDER NO ATAU DISTRIBUTOR CODE TIDAK BOLEH KOSONG";
            } elseif($total_dpR > $paynominal ) {
                echo "Total Pembayaran Harus Sama dengan Total DP";
            } else if(array_search('2',$doublee)) {
                echo "Kode Voucher tidak boleh sama";
            } else {
                $data['ordernooo'] = $this->scan_voucher_model->check_double_orderno1($orderno);
                if($data['ordernooo'] > 0) {
                    echo "ORDERNO SUDAH ADA";
                } else if ($xhd == '1') {
                    $type = 'xhd';
                    $data['cek_seQ'] = $this->scan_voucher_model->cek_seQ($type);
                    $data['idnoo'] = $this->scan_voucher_model->get_idno($type);
                    $data['savee'] =  $this->scan_voucher_model->save_input_sales_sub($data['idnoo'],$data['user']);
                    if($data['savee'] != 0) {
                        $this->load->view('sales/save_PvrSales_success',$data);
                    } else {
                        echo "gagal";
                    }
                } else {
                    $data['cek_seQ'] = $this->m_sales_substockist->cek_seQ($jenis);
                    $data['idnoo'] = $this->m_sales_substockist->get_idno($jenis);
                    $data['savee'] =  $this->m_sales_substockist->save_input_sales_sub($data['idnoo'],$data['user']);
                    if($data['savee'] != 0) {
                      $this->load->view('sales/save_PvrSales_success',$data);
                    } else {
                        echo "gagal";
                    }
                }
            }
            return $data['user'];
        } else {
            redirect('auth');
        }
    }

    //$route['scan/ttp/delete/(:any)'] = 'transaction/scan_voucher/hapusTtpVchDeposit/$1';
    public function hapusTtpVchDeposit($id) {
        $hapus = $this->scan_voucher_model->hapusTTPvchDeposit($id);
        echo json_encode($hapus);
    }

    //$route['scan/ttp/save'] = 'transaction/scan_voucher/saveTrxDepositVch';
	public function saveTrxDepositVch() {
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

				//check apakah distributor valid

				$ifMemberExist = $this->m_sales_stk->getValidDistributor($data['dfno']);
				if($ifMemberExist == null) {
					echo json_encode(jsonFalseResponse("ID Member tidak valid.."));
					return;
                }
                
                //cek apakah ada promo new member baru
                $products = $this->m_sales_stk->getListPrdPromoNewMember();
                //check valid product
                //$isprdVNM = 0;
				$sub_tot_bv = 0;
				$sub_tot_dp = 0;
				$total_dp = 0;
				$total_bv = 0;
				$jumPrd = count($data['prdcd']);
				for($i=0; $i<$jumPrd; $i++) {
					$prdcd = $data['prdcd'][$i];
                    $qty = $data['jum'][$i];
                    
                    if (in_array($data['prdcd'][$i], $products, TRUE)) {
                        echo json_encode(jsonFalseResponse("Pembelian produk promo new member tidak bisa diinput disini.."));
                        return;          
                    }

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

                

                //check apakah pembayaran kosong
				if(!isset($data['payChooseType'])) {
					echo json_encode(jsonFalseResponse("Pembayaran tidak boleh kosong.."));
					return;

				} else {
					/* $jumPay = count($data['payReff']);
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


                    } */

                    $checkVchDeposit = $this->scan_voucher_model->getsisaBARU($data['id_deposit']);
                    $sisa_saldo = $checkVchDeposit[0]->saldo - $checkVchDeposit[0]->payamt;
                    /*if($sisa_saldo <= 0) {
                        echo json_encode(jsonFalseResponse("Sisa saldo deposit voucher adalah : $sisa_saldo.."));
					    return;
                    } */

                    if($sisa_saldo > $data['total_all_dp']) {
                        $data['payChooseType'][0] = "08";
                        $data['payChooseValue'][0] = $data['total_all_dp'];
                    } else {
                        $cash = $data['total_all_dp'] - $sisa_saldo;
                        $data['payChooseType'][0] = "08";
                        $data['payChooseValue'][0] = $sisa_saldo;
                        $data['payChooseType'][1] = "01";
                        $data['payChooseValue'][1] = $cash;
                    }
                    //print_r($checkVchDeposit);
				}

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



				$data['no_deposit'] = $checkVchDeposit[0]->no_trx;
                $data['id_deposit'] = $checkVchDeposit[0]->id_header;

                /* echo "<pre>";
				print_r($data);
				echo "</pre>"; */

                $res = $this->m_sales_stk->saveTrx($data);
                if($res['response'] == "true") {
                    $this->scan_voucher_model->updateSisaSaldo($data['id_deposit'], $data['payChooseValue'][0]);
                }
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

    //$route['scan/deposit/recalculate/(:any)'] = 'transaction/scan_voucher/recalculateDeposit/$1';
    public function recalculateDeposit($id) {
        $res = $this->scan_voucher_model->koreksiDepositVoucher($id);
        echo json_encode($res);
    }

    //$route['scan/deposit/tescalculate/(:any)'] = 'transaction/scan_voucher/tescalculate/$1';
    public function tescalculate($id) {
        $res = $this->scan_voucher_model->koreksiDepositVoucher($id);
    }    
}

