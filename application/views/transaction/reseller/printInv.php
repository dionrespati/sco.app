<?php 
  header("Content-Type: plain/text");
  header("Content-Disposition: Attachment; filename=Invoice_Reseller.txt");
  header("Pragma: no-cache");

  //date_default_timezone_set("Asia/Jakarta");
  $tglPengambilan=date("d-m-Y");
  $waktu=date("h:i:s");

  $garis_strip = 8;
  //tadinya max_line = 64
  $max_line = 68;
  $total_panjang_header = 13;
  $total_panjang_footer = 14;
  $total_panjang_payment = 8;

  function headerPayment() {
    {
      echo "---------------------------------------------------------------------";
      echo "\n";
      echo "Type         Payment          Ref. No.                         Amount";  
      echo "\n";
      echo "---------------------------------------------------------------------";
      echo "\n";
    }
  }

  function jumlahEnter($sisa) {
    for($i=1;$i<=$sisa;$i++) {
      echo "\n";
    }
  }

  function footer() {
    $tglPengambilan=date("d-m-Y");
    $waktu=date("H:i:s");
    echo "Remarks       :";  
    echo "\n";                            
    echo "Purchase From :BID06";  
    echo "\n"; 
    echo "\n";                      
    echo "-------------------------                 ------------------------------- "; 
    echo "\n";
    echo "Received in Good Order By                 BID06  ".$tglPengambilan." ".$waktu."       ";
    echo "\n";
    echo "\n";
    echo "The product price includes 10% government tax (Harga sudah termasuk PPN 11%).";   
    echo "\n";                    
    echo "To make sure your bonus is correct, please check your member code and goods.";                        
    echo "\n";
    echo "Subsequent changes will not be entertained.                                   ";                      
    echo "\n";
    echo "Pastikan bulan bonus Anda sudah benar. Periksa kembali kode distributor dan   ";                      
    echo "\n";
    echo "barang yang telah Anda beli. Perubahan data setelah Anda meninggalkan counter ";                      
    echo "\n";
    echo "tidak dapat kami layani.";
  }

  function garisTotal($all_tot_qty, $all_tot_bv, $all_tot_dp, $garis_strip = 8) {
    $total = "TOTAL";
    
    garisStripV2($garis_strip);
    echo "\n";
    
    tmbh_spaceDetailPersonalV3(10, "");
    echo substr($total, 0, 20);

    tmbh_spaceDetailPersonalV3(20, substr($total, 0, 20));
    

    tmbh_spaceDetailPersonalV3(7, $all_tot_qty);
    echo $all_tot_qty;
    //tmbh_spaceDetailPersonalV3(10, $prd->bv);
    

    
    //tmbh_spaceDetailPersonalV3(5, $all_tot_qty);
    

    //tmbh_spaceDetailPersonalV3(14, $all_tot_qty);
    tmbh_spaceDetailPersonalV3(17, $all_tot_bv);
    echo $all_tot_bv;
    
    tmbh_spaceDetailPersonalV3(26, $all_tot_dp);
    echo $all_tot_dp;


    /*  
    echo $prd->prdcd;
    tmbh_spaceDetailPersonalV3(10, $prd->prdcd);

    echo substr($prd->prdnm, 0, 20);
    tmbh_spaceDetailPersonalV3(20, substr($prd->prdnm, 0, 20));

    tmbh_spaceDetailPersonalV3(10, $prd->qtyord);
    echo number_format($prd->qtyord, 0, ".", ",");

    tmbh_spaceDetailPersonalV3(10, $prd->bv);
    echo number_format($prd->bv, 0, ".", ",");

    $sub_tbv = number_format($prd->sub_total_bv, 0, ".", ",");
    tmbh_spaceDetailPersonalV3(10, $sub_tbv);
    echo $sub_tbv;

    $tdp = number_format($prd->dp, 0, ".", ",");
    tmbh_spaceDetailPersonalV3(10, $tdp);
    echo $tdp;

    $sub_tdp = number_format($prd->sub_total_dp, 0, ".", ",");
    tmbh_spaceDetailPersonalV3(10, $sub_tdp);
    echo $sub_tdp;
    */

    echo "\n";
    garisStripV2($garis_strip);
    echo "\n";
  }

  function garisStripV2($bnyk) {
    for($i=1; $i<=$bnyk; $i++) {
      echo "----------";
    }
  }

  function titleHeaderDataV2() {
    echo "Stock";
    tmbh_spaceHeaderProductV2(5);
    echo "Description";
    tmbh_spaceHeaderProductV2(13);
    echo "Qty";
    tmbh_spaceHeaderProductV2(5);
    echo "PV";
    tmbh_spaceHeaderProductV2(2);
    echo "Gross PV";
    tmbh_spaceHeaderProductV2(11);
    echo "DP";
    tmbh_spaceHeaderProductV2(5);
    echo "Gross DP\n";

    //garisStripV2(8);
  }

  function tmbh_spaceHeaderProductV2($value) {
    $kosong='';

    for($x=1; $x <=$value; $x++) {
      $kosong .=" ";
    }

    echo $kosong;
  }

  function spaceHeaderV2($pjg) {
    $space='';
    $batas=33;
    $sisa=$batas - $pjg;

    for($v=1; $v <=$sisa; $v++) {
      $space .=" ";
    }

    echo $space;
  }

  function tmbh_spaceDetailPersonalV3($batas, $value) {
    $pjg=strlen($value);
    $kosong='';
    $sisa=$batas - $pjg;

    for($i=1; $i <=$sisa; $i++) {
      $kosong .=" ";
    }

    echo $kosong;
  }

  $jum_data = count($arr);

  $i = 0;
  foreach($arr as $dta) {
    foreach($dta['header'] as $head) {
        echo "\n\n";
        echo "                                INVOICE RESELLER                            \n\n";
        echo "Reseller Code : ".$head->kode_reseller;
        spaceHeaderV2(strlen($head->kode_reseller));
        echo "Invoice No    : ".$head->invoiceno."\n";

        echo "Reseller Name : ".substr($head->reseler_name, 0, 15)."";
        spaceHeaderV2(strlen(substr($head->reseler_name, 0, 15)));
        echo "Register No.  : ".$head->registerno."\n";

        echo "Picking       : ";
        spaceHeaderV2(0);
        echo "Date          : ".$head->invoicedt."\n";

        //echo "Branch        : ".$head->branch."";
        echo "Branch        : AKADEMI INSPIRADZI";
        spaceHeaderV2(strlen("AKADEMI INSPIRADZI"));
        echo "Page          : 1\n";

        //echo "Warehouse     : ".$head->whnm."";
        echo "Warehouse     : K-LINK TOWER";
        spaceHeaderV2(strlen("K-LINK TOWER"));
        echo "Print Count   : 1\n";

        echo "Delivery      : ".strtoupper($head->ship_desc)."";
        spaceHeaderV2(strlen($head->ship_desc));
        echo "\n";
      }

      garisStripV2($garis_strip);
      echo "\n";
      titleHeaderDataV2();
      garisStripV2($garis_strip);
      echo "\n";

      $totqty=0;
      $totdp=0;
      $totbv=0;

      $jum_item_prd = count($dta['produk']);

      foreach($dta['produk'] as $prd) {
        
          echo $prd->prdcd;
          tmbh_spaceDetailPersonalV3(10, $prd->prdcd);

          echo substr($prd->prdnm, 0, 20);
          tmbh_spaceDetailPersonalV3(20, substr($prd->prdnm, 0, 20));

          tmbh_spaceDetailPersonalV3(10, $prd->qtyord);
          echo number_format($prd->qtyord, 0, ".", ",");

          tmbh_spaceDetailPersonalV3(10, $prd->bv);
          echo number_format($prd->bv, 0, ".", ",");

          $sub_tbv = number_format($prd->sub_total_bv, 0, ".", ",");
          tmbh_spaceDetailPersonalV3(10, $sub_tbv);
          echo $sub_tbv;

          $tdp = number_format($prd->dp, 0, ".", ",");
          tmbh_spaceDetailPersonalV3(13, $tdp);
          echo $tdp;

          $sub_tdp = number_format($prd->sub_total_dp, 0, ".", ",");
          tmbh_spaceDetailPersonalV3(13, $sub_tdp);
          echo $sub_tdp;

          $totdp += $prd->sub_total_dp;
          $totqty += $prd->qtyord;
          $totbv += $prd->sub_total_bv;
          echo "\n";

      }

      $all_tot_qty = number_format($totqty, 0, ".", ",");
      $all_tot_dp = number_format($totdp, 0, ".", ",");
      $all_tot_bv = number_format($totbv, 0, ".", ",");
      
      garisTotal($all_tot_qty, $all_tot_bv, $all_tot_dp);

      headerPayment();

      $jum_item_pay = count($dta['payment']);
      

      foreach($dta['payment'] as $pay) {
        echo $pay->paytype;
        tmbh_spaceDetailPersonalV3(13, $pay->paytype);

        echo substr($pay->pay_desc, 0, 25);
        tmbh_spaceDetailPersonalV3(17, substr($pay->pay_desc, 0, 25));

        echo $pay->docno;
        tmbh_spaceDetailPersonalV3(26, $pay->docno);

        $jum_byr = number_format($pay->payamt, 0, ".", ",");
        tmbh_spaceDetailPersonalV3(13, $jum_byr);
        echo $jum_byr;
        //
        echo "\n";
      }  

      $sisa = $max_line - ($total_panjang_header + $total_panjang_footer + $total_panjang_payment + $jum_item_prd + $jum_item_pay);
      
      jumlahEnter($sisa);
      footer();

      echo "\n";

      $i++;

      if($i < $jum_data) {
        echo "\f";
      }
      
  }     
 ?> 