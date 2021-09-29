<?php

    class Tax extends MY_Controller{

        public function __construct(){

            parent::__construct();
            $this->folderView = "stockist/";
            $this->load->model('stockist/Stockist_model', 'm_stockist');
        }

        public function index(){

            $data['form_header'] = "Print Tax Stockist";
            $data['form_action'] = base_url('scan/list');
            $data['icon'] = "icon-print";
            $data['form_reload'] = 'tax/print';

            if ($this->username != null) {
            //cek apakah group adalah ADMIN atau BID06
            $data['userlogin'] = $this->stockist;
            $this->setTemplate($this->folderView.'formTax', $data);
            }else{
            //echo sessionExpireMessage(false);
            $this->setTemplate('includes/inline_login', $data);
            }
        }

        public function getTaxStk(){

            $data = $this->input->post(null, true);
            
            $data['result'] = $this->m_stockist->getTaxStk($data['idstk'], $data['year']);

            //print_r($data['result']);

            $this->load->view($this->folderView.'detTaxStk', $data);
        }

        public function printTaxToPDF(){

            //$this->load->library('Fpdf');
            $this->load->helper('fpdf');
            $x['no'] = $this->input->post('no_bukti_potong');

            $x['pjk'] = $this->m_stockist->getDetTaxStk($x['no']);
            //print_r($x['pjk']);
            $this->load->view($this->folderView.'taxPDF', $x);
        }
    }