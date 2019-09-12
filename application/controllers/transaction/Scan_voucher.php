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
            if ($this->stockist == "BID06") {
                $data['mainstk_read'] = "";
                $data['idstkk_read'] = "";
            } else {
                $data['mainstk_read'] = "readonly=readonly";
                $data['idstkk_read'] = "";
            }

            $this->setTemplate($this->folderView.'getDepositForm', $data);
        } else {
            //echo sessionExpireMessage(false);
            $this->setTemplate('includes/inline_login', $data);
        }
    }

    public function getDeposit()
    {
        if ($this->stockist != null) {
            $data = $this->input->post(null, true);
            $username = $this->stockist;
            $data['list'] = $this->scan_voucher_model->show_deposit_list($username, $data['search']);
            $this->load->view($this->folderView.'listScanDeposit', $data);
        } else {
        }
    }

    public function getListScan($id= '')
    {
        // echo "isi : $id";
        if ($this->stockist != null) {
            $data['user'] = $this->stockist;
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
                $data['edit']='readonly="yes"';
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
            }
            $data['label']='';
            $data['kategori']='
                                        <tr>
                                          <td class="form_title" >Jenis Voucher.&nbsp;</td>
                                          <td >
                                              <select id="kategori2" name="kategori2" class="form-control" '.$data['edit'].' required="required" onchange="locker()">
                                                  <option value="KOSONG" ></option>';
            $data['kategori'].='
                                                  <option value="VC" selected>Voucher Cash</option>
                                              </select>
                                          </td>
                                      </tr>
                                        <input type="hidden"  class="span12 typeahead" id="kategori" name="kategori" value="VC" readonly="readonly"/>';
            $tipe = 'sub';
            $mscode = '';
            $data['stk'] = $this->scan_voucher_model->get_stockist_info($tipe, $data['user']);

            /* echo '<pre>';
            print_r($data);
            echo '</pre>'; */
            $this->load->view($this->folderView.'listDetailScan', $data);
            return $data['user'];
        } else {
            redirect('auth');
        }
    }

    public function getTTPList($deposit)
    {
        $data['user'] = $this->stockist;
        $data['deposit'] = $deposit;
        $data['list'] = $this->scan_voucher_model->show_list_TTP($deposit);
        $result = $this->scan_voucher_model->getDataEdit($deposit);
        $data['nodeposit'] = $result[0]->no_trx;

        /* if ($result[0]->status==1) {
            $data['add'] = anchor('c_sales_pvr/getFormTtpDeposit2/'.$deposit, 'TTP Baru', array('class'=>'btn btn-primary'));
        } else {
            $data['add'] = "";
        } */
        $data['status'] = $result[0]->status;

        $this->load->view($this->folderView.'list_TTP', $data);
    }

    public function getVch()
    {
        $scan = $this->input->post('scan');
        $kaet = $this->input->post('kat');
        $idmemb = $this->input->post('idmemb');
        $nilai = $this->scan_voucher_model->getVch($scan, $idmemb);
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
        }
        echo json_encode($arr);
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
            foreach ($scan as $k=>$v) {
                if ($this->scan_voucher_model->CekVoucherTrue($scan[$k]) ==false) {
                    $masalah++;
                }
            }
        }
        if ($masalah==0) {
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
                }
            }
            echo json_encode($POX) ;
        } else {
            echo json_encode(false) ;
        }
    }

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

    public function getFormTtpDeposit2($id = '') {
        $data['user'] = $this->stockist;
        $data['form_action'] = site_url('/c_sales_subStockist/postFormTtpPvr');
        $data['header_form'] = "Input TTP";
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
        $data['currentperiod'] = $this->scan_voucher_model->get_current_period();

        $this->load->view($this->folderView.'form_ttp_depo',$data);
        return $data['user'];
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
}
