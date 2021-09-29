<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sales_stockist_report extends MY_Controller {
	public function __construct() {
	    parent::__construct();
		$this->folderView = "transaction/stockist_report/";
		$this->load->model('transaction/Sales_stockist_report_model', 'm_ssr');
	}	

    //$route['sales/generated/report'] = 'transaction/sales_stockist_report/formListGeneratedSales';
	public function formListGeneratedSales() {
		$data['form_header'] = "Sales Report";
        $data['form_action'] = 'sales/generated/report/list';
        $data['icon'] = "icon-search";
		$data['form_reload'] = 'sales/generated/report';   		   
		$data['sc_dfno'] = $this->stockist;		
		$data['main_stk'] = $this->stockist;

		if($this->username == null) {
			$this->setTemplate('includes/inline_login', $data);
			return;
		}
		
		$data['exportExcell'] = site_url('sales/report/excel');
		$data['from'] 	= date("Y-m-d");
		$data['to'] 	= date("Y-m-d");
		$data['curr_period'] = $this->m_ssr->getCurrentPeriod();
		$data['readonly_stk'] = "";
		if($this->stockist != "BID06") {
			$data['readonly_stk'] = "readonly=readonly";
		}
		$this->setTemplate($this->folderView.'formListGeneratedSales', $data); 
	}
	//$route['sales/generated/report/list'] = 'transaction/sales_stockist_report/getListGeneratedSales';
	public function getListGeneratedSales() {
		if($this->username != null) {
			$data = $this->input->post(NULL, TRUE);
			//$data['result'] = $this->m_ssr->getListGeneratedSales($data['form']);
			//print_r($data['result']);
			//$this->load->view($this->folderView.'listGeneratedSales',$data);	
			$bns = $data['year_bns']."-".$data['month_bns']."-01";
			if($data['searchs'] !== "ECOMMERCE") {
				$arr = array(
					"from" => trim($data['from']),
					"to" => trim($data['to']),
					"main_stk" => trim($data['main_stk']),
					"idstkk" => trim($data['idstkk']),
					"searchs" => trim($data['searchs']),
					"statuses" => trim($data['statuses']),
					"bnsperiod" => trim($bns)
				);
				$data['idstk'] =  $this->m_ssr->getGenerateRPTByCahyono($arr);
				/* echo "<pre>";
				print_r($data['idstk'][0]);
				echo "</pre>"; */
				$data['tipe'] = $data['searchs'];
				$data['action1'] = site_url('sco/sales/generate/pdf');
				$data['action2'] = site_url('sales/report/excel');
				$this->load->view($this->folderView.'salesReport',$data);	
		    } else {
				$arr = array(
					"main_stk" => trim($data['main_stk']),
					"searchs" => trim($data['searchs']),
					"bnsperiod" => trim($bns)
				);

				$data['result'] =  $this->m_ssr->getListKnetTrx($arr);
				/* echo "<pre>";
				print_r($data['result']);
				echo "</pre>"; */

				$arrTable = array(
					"id" => "tbl1",
					"header" => "Data List Trx K-NET Stockist $data[main_stk]",
					"column" => array(
						"No Trx", "ID Member", "No SSR", "Tgl Trx", "DP", "BV", "Kirim", 
						"Tipe"
					),
		
					"columnAlign" => array(
						"center", "left", "center", "center", "right", "right", "Center", "Center"
					),

					"recordStyle" => array(
						"","","","","money","money","",""
					),

					"record" => $data['result']['data']
				);
				echo generateTable($arrTable);

				echo "<table id='ths' class='table table-bordered table-striped'>";
				echo "<tr>";
				echo "<th width='20%'>Tipe</th>";
				echo "<th width='20%'>Jml Trx</th>";
				echo "<th width='20%'>Total DP</th>";
				echo "<th width='20%'>Total BV</th>";
				echo "</tr>";
				foreach($data['result']['rekap'] as $dtxa) {
					echo "<tr>";
					echo "<td>$dtxa[tipe]</td>";
					echo "<td align=right>".number_format($dtxa['total_jum_trx'], 0, '.', ',')."</td>";
					echo "<td align=right>".number_format($dtxa['total_dp'], 0, '.', ',')."</td>";
					echo "<td align=right>".number_format($dtxa['total_bv'], 0, '.', ',')."</td>";
					echo "</tr>";
				}
				echo "</table>";
			}
		} else {
           jsAlert();
        } 
	}

	//$route['sales/report/excel'] = 'transaction/sales_generate/ssrExportExcel';
	public function ssrExportExcel() {
		if($this->username != null) {
			$data = $this->input->post(NULL, TRUE);
			//$data['result'] = $this->m_ssr->getListGeneratedSales($data['form']);
			//print_r($data['result']);
			//$this->load->view($this->folderView.'listGeneratedSales',$data);	
			$bns = $data['year_bns']."-".$data['month_bns']."-01";
			
			$arr = array(
				"from" => trim($data['from']),
				"to" => trim($data['to']),
				"main_stk" => trim($data['main_stk']),
				"idstkk" => trim($data['idstkk']),
				"searchs" => trim($data['searchs']),
				"statuses" => trim($data['statuses']),
				"bnsperiod" => trim($bns)
			);
			$data['idstk'] =  $this->m_ssr->getGenerateRPTByCahyono($arr);
			$data['tipe'] = $data['searchs'];

			/* echo "<pre>";
			print_r($data['idstk']);
			echo "</pre>"; */

			$data['action1'] = site_url('sco/sales/generate/pdf');
            $data['action2'] = site_url('sco/sales/report/excel'); 
			$this->load->view($this->folderView.'salesReportExcell',$data);	
		} else {
           jsAlert();
        } 
	}
	
	//$route['sales/generated/ssr/(:any)'] = 'transaction/sales_stockist_report/getDetailTrxBySSR/$1';
	public function getDetailTrxBySSR($ssrno) {
		$data['header'] = $this->m_ssr->getHeaderSsr("batchno", $ssrno);
		$data['listTTP'] = $this->m_ssr->getListSummaryTtp("batchno", $ssrno);
		$data['summaryProduct'] = $this->m_ssr->getListSummaryProduct("batchno", $ssrno);
		$this->load->view($this->folderView.'summaryTrxBySsrNo',$data);
	}
	
	//$route['sales/voucher/report'] = 'transaction/sales_stockist_report/voucherReport';
	public function voucherReport() {
		$data['form_header'] = "Voucher Report";
        $data['form_action'] = 'sales/voucher/report/list';
        $data['icon'] = "icon-search";
		$data['form_reload'] = 'sales/voucher/report';   		   
		$data['sc_dfno'] 	= $this->stockist;		

		if($this->username == null) {
			$this->setTemplate('includes/inline_login', $data);
			return;
		}

		//$data['from'] 	= date("Y-m-d");
		//$data['to'] 	= date("Y-m-d");
		$data['curr_period'] = $this->m_ssr->getCurrentPeriod();
		$this->setTemplate($this->folderView.'voucherReport', $data); 
        
	}
	
    //$route['sales/voucher/report/list'] = 'transaction/sales_stockist_report/voucherReportList';
    public function voucherReportList() {
    	if($this->username != null) {
            $x = $this->input->post(NULL, TRUE);
            //$username = $this->session->userdata('username');          
            /* if($x['searchBy'] == "VoucherNo") {
                $x['result'] =  $this->m_ssr->getVoucherReportList($x['searchBy'], $x['paramVchValue']);
				//print_r($x['result']);
                $this->load->view($this->folderView.'listVchReportByVoucherNo',$x);
            } else if($x['searchBy'] == "DistributorCode") {
            	$x['result'] =  $this->m_ssr->getVoucherReportList($x['searchBy'], $x['paramVchValue']);
                //print_r($x['result']);
                $this->load->view($this->folderView.'listVchReportByIdMember',$x);
			}  */
			$x['usergroup'] = $this->usergroup;
			$x['btnBack'] = "";
			if($x['kategori'] == "vc_umr" && $x['voucherno'] !== "") {
				$x['result'] =  $this->m_ssr->getVoucherUmrohReport($x['memberid'], $x['voucherno'], $x['kategori'], $x['usergroup']);	
				$this->load->view($this->folderView.'listVchReportStk',$x);
				
			} else if($x['kategori'] == "vc_reg" && $x['voucherno'] !== "") {
				$x['result'] =  $this->m_ssr->getDetailVoucher($x['voucherno'], $x['memberid'], $x['usergroup']);	
				$this->load->view('member/voucher/voucherDetailByFormNo',$x);
		    } else {

				if($x['usergroup'] == "ADMIN" && $x['voucherno'] == "") {
					$x['result'] =  $this->m_ssr->getListVchByDist($x['memberid'], $x['kategori'], $x['usergroup']);
					/* echo "<pre>";
					print_r($x['result']);
					echo "</pre>"; */

					$arrTable = array(
						"id" => "tbl1",
						"header" => "List Voucher Memner",
						"column" => array(
							"No Voucher", "Nilai Vch", "Tgl Exp", "Status Klaim", "Lokasi Klaim", "Status Open", "Act"
						),
						"columnAlign" => array(
							"center","right", "center", "center","center","center","center"
						),
			
						"recordStyle" => array(
							"","money","","","","",""
						),
						"record" => $x['result']
					 );
					echo generateTable($arrTable);
				} else {
					//echo "sds";
					$x['result'] =  $this->m_ssr->getVoucherReportListV2($x['memberid'], $x['voucherno'], $x['kategori'], $x['usergroup']);
					$this->load->view($this->folderView.'listVchReportStk',$x);
				}	
				
			}
			
			
        }else{
            echo sessionExpireMessage(false);
        }
    }

	//$route['sales/voucher/no/(:any)'] = 'transaction/sales_stockist_report/getDetailVoucherNo/$1';
	function getDetailVoucherNo($id) {
		$x['result'] =  $this->m_ssr->getVoucherReportList("VoucherNo", $id);
		$this->load->view($this->folderView.'listVchReportByVoucherNo',$x);
	}

	//$route['sales/reportstk/(:any)/(:any)'] = 'transaction/sales_stockist_report/listTTP/$1/$2';
  function listTTP($field, $value) {
	
	if($field == "batchno") {
		$data['back_button'] = "All.back_to_form(' .nextForm1',' .mainForm')";
		$data['result'] = $this->m_ssr->listTtpByIdV2($field, $value);
		$data['rekapPrd'] = $this->m_ssr->summaryProductBySSR($value);
		$this->load->view($this->folderView.'listTTP', $data);
	} else if($field == "trcd") {
		$data['back_button'] = "All.back_to_form(' .nextForm2',' .nextForm1')";
		$data['result'] = $this->m_ssr->detailTrxByTrcd($field, $value);
		$this->load->view($this->folderView.'detailTrx', $data);
	}
	
	
  }

  //$route['sales/report/product'] = 'transaction/sales_stockist_report/rekapSalesProduct';
	public function rekapSalesProduct() {
		$data['form_header'] = "Stockist Product Sales Report";
        $data['form_action'] = "sales/report/product/excell";
        $data['icon'] = "icon-list";
		$data['form_reload'] = 'sales/report/product';
		$data['mainstk_read'] = $this->stockist;

        if($this->username == null) {
			$this->setTemplate('includes/inline_login', $data);
			return;
		}

		$data['btfrom'] = date("Y-m")."-01";
		$data['btto'] = date("Y-m-d");
		$this->setTemplate($this->folderView.'formrekapSalesProduct',$data);
	}

	//$route['sales/report/stkssr'] = 'transaction/sales_stockist_report/stkssr';
	public function stkssr() {
		$form = $this->input->post(NULL, TRUE);
		//print_r($form);
		$data['result'] = $this->m_ssr->stkssr($form);
		$arr = jsonFalseResponse("Data tidak ditemukan");
		if($data['result'] !== null) {
			$arr = jsonTrueResponse($data['result']);
		}
		echo json_encode($arr);
	}

	//$route['sales/report/product/list'] = 'transaction/sales_stockist_report/rekapSalesProductList';
	public function rekapSalesProductList() {
		$form = $this->input->post(NULL, TRUE);
		$arr = array(
			"from" => $form['from'], 
			"to"   => $form['to'],
			"tipe" => $form['stkssr'], 
			"mainstk" => $form['mainstk'], 
			"tipessr" => $form['searchs'],
			"parValue" => $form['parValue'],
			"break_bundling" => $form['bundling'],
		);

		/* echo "<pre>";
		print_r($arr);
		echo "</pre>"; */

		 $res = $this->m_ssr->rekapProduk($arr);

		$arrTable = array(
			"id" => "tbl1",
			"header" => "Rekap Sales Produk",
			"column" => array(
				"Kode Produk", "Nama Produk", "Qty"
			),
			"columnAlign" => array(
				"center","left", "right"
			),

			"recordStyle" => array(
				"","","money"
			),
			"record" => $res
		 );
		echo generateTable($arrTable);
		
	}

	//$route['sales/report/product/excell'] = 'transaction/sales_stockist_report/rekapSalesProductListExcell';
	public function rekapSalesProductListExcell() {
		$form = $this->input->post(NULL, TRUE);
		$arr = array(
			"from" => $form['from'], 
			"to"   => $form['to'],
			"tipe" => $form['stkssr'], 
			"mainstk" => $form['mainstk'], 
			"tipessr" => $form['searchs'],
			"parValue" => $form['parValue'],
			"break_bundling" => $form['bundling'],
		);

		/* echo "<pre>";
		print_r($arr);
		echo "</pre>"; */

		 $res = $this->m_ssr->rekapProduk($arr);

		$arrTable = array(
			"id" => "tbl1",
			"header" => "Rekap Sales Produk",
			"column" => array(
				"Kode Produk", "Nama Produk", "Qty"
			),
			"columnAlign" => array(
				"center","left", "right"
			),

			"recordStyle" => array(
				"","","money"
			),
			"record" => $res,
			"datatable" => false
		 );
		//echo generateExcell($arrTable);

		header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: Attachment; filename=reportBnsStk.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
		date_default_timezone_set("Asia/Jakarta"); 
		$res = generateExcell($arrTable);
        echo $res;
		
	}
	
}