<<<<<<< HEAD
<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Sales_generate extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->folderView = "transaction/generate/";
        $this->load->model('transaction/Sales_generate_model', 'm_sales_generate');
    }

    //$route['sales/generate'] = 'transaction/sales_generate/formGenerateScoTrx';
    public function formGenerateScoTrx()
    {
        $data['form_header'] = "Generate Sales TTP & PVR";
        $data['form_action'] = base_url('sales/generate/list');
        $data['icon'] = "icon-edit";
        $data['form_reload'] = 'sales/generate';

        if ($this->username != null) {
            //cek apakah group adalah ADMIN atau BID06
            if ($this->stockist == "BID06") {
                $data['mainstk_read'] = "";
                $data['idstkk_read'] = "";
            } else {
                $data['mainstk_read'] = "readonly=readonly";
                $data['idstkk_read'] = "";
            }

            $data['curr_period'] = $this->m_sales_generate->getCurrentPeriod();
            $this->setTemplate($this->folderView.'generateScoTrxForm', $data);
        } else {
            //echo sessionExpireMessage(false);
            $this->setTemplate('includes/inline_login', $data);
        }
    }

    //$route['sales/search/list'] = 'transaction/sales_generate/searchUngeneratedSales';
    public function searchUngeneratedSales()
    {
        if ($this->username != null) {
            $x = $this->input->post(null, true);
            /**
             * @Author: Ricky
              * @Date: 2019-08-16 09:56:38
              * @Desc: Temporarily disabled
              */
            // -- start comment -- //
            /* if($x['searchs'] == "stock" || $x['searchs'] == "apl") {
                $x['tipess'] = 'ID Stockist';
                $x['idstk'] =  $this->m_sales_generate->getGenerateByStk($x);
                $this->load->view($this->folderView.'listGenSalesScoStk',$x);
            } else if($x['searchs'] == "pvr") {
              $x['idstk'] =  $this->m_sales_generate->getGenerateByPVR($x);
                //print_r($x['idstk']);
                $this->load->view($this->folderView.'listGenSalesPvr',$x);
            } else {
                if($x['searchs'] == "sub") {
                    $x['tipess'] = 'Kode Sub Stk';
                    $x['namess'] = 'Nama Sub Stk';
                } else {
                    $x['tipess'] = 'Kode MS';
                    $x['namess'] = 'Nama MS';
                }
                $x['idstk'] =  $this->m_sales_generate->getGenerateBySUbMs($x);
                $this->load->view($this->folderView.'listGenSalesSco',$x);
            } */
            // -- end comment -- //
            $x['tipe'] = $this->input->post('searchs');
            $x['tipess'] = 'ID Stockist';
            $x['idstk'] =  $this->m_sales_generate->getGenerateByStk($x);
            $x['stockist'] = $this->stockist;
            $this->load->view($this->folderView.'listGenSalesScoStk', $x);
        } else {
            echo sessionExpireMessage();
        }
    }

    //$route['sales/detail/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'transaction/sales_generate/getDetailSales/$1/$2/$3/$4/$5';
    public function getDetailSales($scco, $scDfno, $tglbns, $blnbns, $thnbns)
    {
        $bnsperiod = $blnbns."/"."01"."/".$thnbns;
        $x['detailTtp'] = $this->m_sales_generate->get_details_salesttp($scDfno, $bnsperiod, $scco);
        //print_r($x['detailTtp']);
        $this->load->view($this->folderView.'detailSalesBySCdfno', $x);
    }

    //$route['sales/generate/preview'] = 'transaction/sales_generate/previewGenerate';
    function previewGenerate(){
        if($this->username != NULL) {
            $data = $this->input->post(NULL, TRUE);
            $x['tipeSales'] = $this->input->post('typee');
            $username = $this->session->userdata('username');
            $x['cek'] = $this->input->post('cek');
            $x['bnsperiod'] = date('Y-m-d',strtotime($this->input->post('bnsperiod')));
            $bnsperiod = $this->input->post('bnsperiod');
            $scDfno = $this->input->post('scDfno');
            $x['scDfno'] = $this->input->post('scDfno');
            $x['scCO'] = $this->input->post('scCO');

            $x['tipe'] = $this->input->post('ID_KW');
            $x['dari'] = $this->input->post('from');
            $x['ke'] = $this->input->post('to');
            $x['idstkk'] = $this->input->post('idstkk');

            $x['groupitem']= $this->m_sales_generate->getDetItem($x['dari'],$x['ke'],$x['idstkk'],$bnsperiod,$x['cek']);

            $x['groupprod']= $this->m_sales_generate->getDetItem2($x['dari'],$x['ke'],$x['idstkk'],$bnsperiod,$x['cek']);
            $x['groupmlm']= $this->m_sales_generate->getDetMLM($x['dari'],$x['ke'],$x['idstkk'],$bnsperiod,$x['cek']);
            $this->load->view('transaction/generate/previewList', $x);
            /* echo '<pre>';
            print_r($x);
            echo '</pre>'; */
        } else {
            redirect('auth');
        }
    }

    //$route['sales/generate/sales'] = 'transaction/sales_generate/generateSales';
    public function generateSales()
    {
        //$username = $this->session->userdata('username');
        $username = $this->stockist;
        $createdt = date('Y-m-d');
        $x['head'] = 'SSR';
        $x['trcd'] = $this->input->post('trcd');
        $x['tipechmlm'] = $this->input->post('tipechmlm');
        $x['scdfnomlm'] = $this->input->post('scdfnomlm');
        $x['tipech'] = $this->input->post('tipech');
        //$bnsx = explode('/', $this->input->post('bonusperiod'));
        $x['bonusperiod'] = $this->input->post('bonusperiod');
        $x['scCO']=$this->input->post('scCO');
        $x['sccomlm']=$this->input->post('sccomlm');
        $x['scCOxd']=$this->input->post('scCOxd');
        $SSR=0;
        $Application=0;
        $VCD=0;
        $MSR=0;
        $SSSR=0;
        $generates=0;
        $VPD=0;
        //ini menentukan apa yg mau di generate

        $arrayy = "";

        foreach ($x['tipechmlm'] as $k=>$v) {
            if ($x['tipechmlm'][$k]=="SSR") {
                $lastSSR = $this->m_sales_generate->get_SSRno('stock', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                foreach ($x['trcd'] as $y => $z) {
                    if ($x['tipech'][$y]=="SSR") {
                        foreach ($lastSSR as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            $generate = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$y], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                    }
                }
            }
            if ($x['tipechmlm'][$k]=="Application") {
                $lastApl = $this->m_sales_generate->get_SSRno('apl', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);

                foreach ($x['trcd'] as $a => $s) {
                    if ($x['tipech'][$a]=="Application") {
                        foreach ($lastApl as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            //$generate = $this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$a], $x['bonusperiod'], $username);
                            $generate = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$a], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                    }
                }
            }

            if ($x['tipechmlm'][$k]=="Voucher Cash (Deposit)") {
                $lastVCD = $this->m_sales_generate->get_SSRno('stock', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                $VCD++;
                $generates=0;
                foreach ($x['trcd'] as $d => $f) {
                    if ($x['tipech'][$d]=="Voucher Cash (Deposit)") {
                        foreach ($lastVCD as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            $generates =($this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$d], $x['bonusperiod'], $username));
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sdss=$d;
                    }
                }
                if ($generates > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                }
            }

            if ($x['tipechmlm'][$k]=="PVR") {
                $lastVCD = $this->m_sales_generate->get_SSRno('pvr', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                $VCD++;
                foreach ($x['trcd'] as $d => $f) {
                    if ($x['tipech'][$d]=="PVR") {
                        foreach ($lastVCD as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            //$generates = $this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$d], $x['bonusperiod'], $username);
                            $generates = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$d], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sdss=$d;
                    }
                }
                /* if ($generates > 0) {
                    $this->m_sales_generate->incoming_paymentV($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                } */
            }


            if ($x['tipechmlm'][$k]=="MSR") {
                $lastms = $this->m_sales_generate->get_SSRno('ms', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                foreach ($x['trcd'] as $g => $h) {
                    if ($x['tipech'][$g]=="MSR"&& $x['scCO'][$g]==$x['scdfnomlm'][$k]&& $x['scCOxd'][$g]==$x['sccomlm'][$k]) {
                        foreach ($lastms as $row) {
                            $new_id = $row->hasil;
                            $arrayy .= "'".$new_id."', ";
                            $x['new_id'] = $row->hasil;
                            //$generatemsr = $this->m_sales_generate->generate_sales_saveMS2($new_id, $x['trcd'][$g], $x['bonusperiod'], $username);
                            $generatemsr = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$g], $x['bonusperiod'], $username);
                        }
                        $sdss=$g;
                    }
                }
                /* if ($generatemsr > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                }  */
            }
            if ($x['tipechmlm'][$k]=="SSSR") {
                $lastsub = $this->m_sales_generate->get_SSRno('sub', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);

                foreach ($x['trcd'] as $j => $u) {
                    if ($x['tipech'][$j]=="SSSR") {
                        foreach ($lastsub as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            //$generate = $this->m_sales_generate->generate_sales_saveSub2($new_id, $x['trcd'][$j], $x['bonusperiod'], $username);
                            $generate = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$j], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sdss=$j;
                    }
                }

                /* if ($generate > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                } */
            }
            if ($x['tipechmlm'][$k]=="Voucher Product (Deposit)") {
                $lastVCD = $this->m_sales_generate->get_SSRno('voucher', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                $VPD++;
                foreach ($x['trcd'] as $r => $t) {
                    if ($x['tipech'][$r]=="Voucher Product (Deposit)") {
                        foreach ($lastVCD as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            $generates = $this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$r], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sds=$r;
                    }
                }
                if ($generates > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sds], $x['scCO'][$sds]);
                }
            }
        }
        $arrayy = substr($arrayy, 0, -2);
        if (isset($generatemsr)) {
        }

        //ini generate nomor SSR
        $x['generateRes4']=$this->m_sales_generate->get_data_GenerateStk($arrayy, $x['bonusperiod'], $username);

        $this->load->view('transaction/generate/genResult', $x);
    }

    //$route['sales/search/list/detail'] = 'transaction/sales_generate/getdetail';
    public function getdetail()
    {
        $explode=explode("|", $this->input->post('ID_KW'));
        $tipe = $explode[0];
        $sc_dfno = $explode[1];
        $sc_co = $explode[2];
        $dari = $this->input->post('from');
        $ke = $this->input->post('to');
        $idstkk = $this->input->post('mainstk');
        $bnsperiod = $this->input->post('bnsperiod');



        $data['result'] = $this->m_sales_generate->getDetailTrxV2($idstkk, $bnsperiod, $tipe, $sc_co, $sc_dfno);
        /* backToMainForm();
        echo "<pre>";
        print_r($data['result']);
        echo "<pre>";
        backToMainForm(); */

        $this->load->view('transaction/generate/previewListTtp', $data);
        
    }
}
=======
<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Sales_generate extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->folderView = "transaction/generate/";
        $this->load->model('transaction/Sales_generate_model', 'm_sales_generate');
    }

    //$route['sales/generate'] = 'transaction/sales_generate/formGenerateScoTrx';
    public function formGenerateScoTrx()
    {
        $data['form_header'] = "Generate Sales TTP & PVR";
        $data['form_action'] = base_url('sales/generate/list');
        $data['icon'] = "icon-edit";
        $data['form_reload'] = 'sales/generate';

        if ($this->username != null) {
            //cek apakah group adalah ADMIN atau BID06
            if ($this->stockist == "BID06") {
                $data['mainstk_read'] = "";
                $data['idstkk_read'] = "";
            } else {
                $data['mainstk_read'] = "readonly=readonly";
                $data['idstkk_read'] = "";
            }

            $data['curr_period'] = $this->m_sales_generate->getCurrentPeriod();
            $this->setTemplate($this->folderView.'generateScoTrxForm', $data);
        } else {
            //echo sessionExpireMessage(false);
            $this->setTemplate('includes/inline_login', $data);
        }
    }

    //$route['sales/search/list'] = 'transaction/sales_generate/searchUngeneratedSales';
    public function searchUngeneratedSales()
    {
        if ($this->username != null) {
            $x = $this->input->post(null, true);
            /**
             * @Author: Ricky
              * @Date: 2019-08-16 09:56:38
              * @Desc: Temporarily disabled
              */
            // -- start comment -- //
            /* if($x['searchs'] == "stock" || $x['searchs'] == "apl") {
                $x['tipess'] = 'ID Stockist';
                $x['idstk'] =  $this->m_sales_generate->getGenerateByStk($x);
                $this->load->view($this->folderView.'listGenSalesScoStk',$x);
            } else if($x['searchs'] == "pvr") {
              $x['idstk'] =  $this->m_sales_generate->getGenerateByPVR($x);
                //print_r($x['idstk']);
                $this->load->view($this->folderView.'listGenSalesPvr',$x);
            } else {
                if($x['searchs'] == "sub") {
                    $x['tipess'] = 'Kode Sub Stk';
                    $x['namess'] = 'Nama Sub Stk';
                } else {
                    $x['tipess'] = 'Kode MS';
                    $x['namess'] = 'Nama MS';
                }
                $x['idstk'] =  $this->m_sales_generate->getGenerateBySUbMs($x);
                $this->load->view($this->folderView.'listGenSalesSco',$x);
            } */
            // -- end comment -- //
            $x['tipe'] = $this->input->post('searchs');
            $x['tipess'] = 'ID Stockist';
            $x['idstk'] =  $this->m_sales_generate->getGenerateByStk($x);
            $x['stockist'] = $this->stockist;
            $this->load->view($this->folderView.'listGenSalesScoStk', $x);
        } else {
            echo sessionExpireMessage();
        }
    }

    //$route['sales/detail/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'transaction/sales_generate/getDetailSales/$1/$2/$3/$4/$5';
    public function getDetailSales($scco, $scDfno, $tglbns, $blnbns, $thnbns)
    {
        $bnsperiod = $blnbns."/"."01"."/".$thnbns;
        $x['detailTtp'] = $this->m_sales_generate->get_details_salesttp($scDfno, $bnsperiod, $scco);
        //print_r($x['detailTtp']);
        $this->load->view($this->folderView.'detailSalesBySCdfno', $x);
    }

    //$route['sales/generate/preview'] = 'transaction/sales_generate/previewGenerate';
    function previewGenerate(){
        if($this->username != NULL) {
            $data = $this->input->post(NULL, TRUE);
            $x['tipeSales'] = $this->input->post('typee');
            $username = $this->session->userdata('username');
            $x['cek'] = $this->input->post('cek');
            $x['bnsperiod'] = date('Y-m-d',strtotime($this->input->post('bnsperiod')));
            $bnsperiod = $this->input->post('bnsperiod');
            $scDfno = $this->input->post('scDfno');
            $x['scDfno'] = $this->input->post('scDfno');
            $x['scCO'] = $this->input->post('scCO');

            $x['tipe'] = $this->input->post('ID_KW');
            $x['dari'] = $this->input->post('from');
            $x['ke'] = $this->input->post('to');
            $x['idstkk'] = $this->input->post('idstkk');

            $x['groupitem']= $this->m_sales_generate->getDetItem($x['dari'],$x['ke'],$x['idstkk'],$bnsperiod,$x['cek']);

            $x['groupprod']= $this->m_sales_generate->getDetItem2($x['dari'],$x['ke'],$x['idstkk'],$bnsperiod,$x['cek']);
            $x['groupmlm']= $this->m_sales_generate->getDetMLM($x['dari'],$x['ke'],$x['idstkk'],$bnsperiod,$x['cek']);
            $this->load->view('transaction/generate/previewList', $x);
            /* echo '<pre>';
            print_r($x);
            echo '</pre>'; */
        } else {
            redirect('auth');
        }
    }

    //$route['sales/generate/sales'] = 'transaction/sales_generate/generateSales';
    public function generateSales()
    {
        //$username = $this->session->userdata('username');
        $username = $this->stockist;
        $createdt = date('Y-m-d');
        $x['head'] = 'SSR';
        $x['trcd'] = $this->input->post('trcd');
        $x['tipechmlm'] = $this->input->post('tipechmlm');
        $x['scdfnomlm'] = $this->input->post('scdfnomlm');
        $x['tipech'] = $this->input->post('tipech');
        //$bnsx = explode('/', $this->input->post('bonusperiod'));
        $x['bonusperiod'] = $this->input->post('bonusperiod');
        $x['scCO']=$this->input->post('scCO');
        $x['sccomlm']=$this->input->post('sccomlm');
        $x['scCOxd']=$this->input->post('scCOxd');
        $SSR=0;
        $Application=0;
        $VCD=0;
        $MSR=0;
        $SSSR=0;
        $generates=0;
        $VPD=0;
        //ini menentukan apa yg mau di generate

        $arrayy = "";

        foreach ($x['tipechmlm'] as $k=>$v) {
            if ($x['tipechmlm'][$k]=="SSR") {
                $lastSSR = $this->m_sales_generate->get_SSRno('stock', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                foreach ($x['trcd'] as $y => $z) {
                    if ($x['tipech'][$y]=="SSR") {
                        foreach ($lastSSR as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            $generate = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$y], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                    }
                }
            }
            if ($x['tipechmlm'][$k]=="Application") {
                $lastApl = $this->m_sales_generate->get_SSRno('apl', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);

                foreach ($x['trcd'] as $a => $s) {
                    if ($x['tipech'][$a]=="Application") {
                        foreach ($lastApl as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            //$generate = $this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$a], $x['bonusperiod'], $username);
                            $generate = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$a], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                    }
                }
            }

            if ($x['tipechmlm'][$k]=="Voucher Cash (Deposit)") {
                $lastVCD = $this->m_sales_generate->get_SSRno('stock', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                $VCD++;
                $generates=0;
                foreach ($x['trcd'] as $d => $f) {
                    if ($x['tipech'][$d]=="Voucher Cash (Deposit)") {
                        foreach ($lastVCD as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            $generates =($this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$d], $x['bonusperiod'], $username));
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sdss=$d;
                    }
                }
                if ($generates > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                }
            }

            if ($x['tipechmlm'][$k]=="PVR") {
                $lastVCD = $this->m_sales_generate->get_SSRno('pvr', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                $VCD++;
                foreach ($x['trcd'] as $d => $f) {
                    if ($x['tipech'][$d]=="PVR") {
                        foreach ($lastVCD as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            //$generates = $this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$d], $x['bonusperiod'], $username);
                            $generates = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$d], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sdss=$d;
                    }
                }
                /* if ($generates > 0) {
                    $this->m_sales_generate->incoming_paymentV($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                } */
            }


            if ($x['tipechmlm'][$k]=="MSR") {
                $lastms = $this->m_sales_generate->get_SSRno('ms', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                foreach ($x['trcd'] as $g => $h) {
                    if ($x['tipech'][$g]=="MSR"&& $x['scCO'][$g]==$x['scdfnomlm'][$k]&& $x['scCOxd'][$g]==$x['sccomlm'][$k]) {
                        foreach ($lastms as $row) {
                            $new_id = $row->hasil;
                            $arrayy .= "'".$new_id."', ";
                            $x['new_id'] = $row->hasil;
                            //$generatemsr = $this->m_sales_generate->generate_sales_saveMS2($new_id, $x['trcd'][$g], $x['bonusperiod'], $username);
                            $generatemsr = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$g], $x['bonusperiod'], $username);
                        }
                        $sdss=$g;
                    }
                }
                /* if ($generatemsr > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                }  */
            }
            if ($x['tipechmlm'][$k]=="SSSR") {
                $lastsub = $this->m_sales_generate->get_SSRno('sub', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);

                foreach ($x['trcd'] as $j => $u) {
                    if ($x['tipech'][$j]=="SSSR") {
                        foreach ($lastsub as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            //$generate = $this->m_sales_generate->generate_sales_saveSub2($new_id, $x['trcd'][$j], $x['bonusperiod'], $username);
                            $generate = $this->m_sales_generate->updateSSR($new_id, $x['trcd'][$j], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sdss=$j;
                    }
                }

                /* if ($generate > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sdss], $x['scCO'][$sdss]);
                } */
            }
            if ($x['tipechmlm'][$k]=="Voucher Product (Deposit)") {
                $lastVCD = $this->m_sales_generate->get_SSRno('voucher', $x['bonusperiod'], $username, $x['scdfnomlm'][$k]);
                $VPD++;
                foreach ($x['trcd'] as $r => $t) {
                    if ($x['tipech'][$r]=="Voucher Product (Deposit)") {
                        foreach ($lastVCD as $row) {
                            $new_id = $row->hasil;
                            $x['new_id'] = $row->hasil;
                            $generates = $this->m_sales_generate->generate_sales_save2($new_id, $x['trcd'][$r], $x['bonusperiod'], $username);
                            $arrayy .= "'".$new_id."', ";
                        }
                        $sds=$r;
                    }
                }
                if ($generates > 0) {
                    $this->m_sales_generate->incoming_paymentH($new_id, $username, $x['scCOxd'][$sds], $x['scCO'][$sds]);
                }
            }
        }
        $arrayy = substr($arrayy, 0, -2);
        if (isset($generatemsr)) {
        }

        //ini generate nomor SSR
        $x['generateRes4']=$this->m_sales_generate->get_data_GenerateStk($arrayy, $x['bonusperiod'], $username);

        $this->load->view('transaction/generate/genResult', $x);
    }

    //$route['sales/search/list/detail'] = 'transaction/sales_generate/getdetail';
    public function getdetail()
    {
        $explode=explode("|", $this->input->post('ID_KW'));
        $tipe = $explode[0];
        $sc_dfno = $explode[1];
        $sc_co = $explode[2];
        $dari = $this->input->post('from');
        $ke = $this->input->post('to');
        $idstkk = $this->input->post('mainstk');
        $bnsperiod = $this->input->post('bnsperiod');



        $data['result'] = $this->m_sales_generate->getDetailTrxV2($idstkk, $bnsperiod, $tipe, $sc_co, $sc_dfno);
        /* backToMainForm();
        echo "<pre>";
        print_r($data['result']);
        echo "<pre>";
        backToMainForm(); */

        $this->load->view('transaction/generate/previewListTtp', $data);
        
    }
}
>>>>>>> devel
