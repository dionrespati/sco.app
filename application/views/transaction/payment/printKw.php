<?php 
  header("Content-Type: plain/text");
  header("Content-Disposition: Attachment; filename=Reseller_receipt.txt");
  header("Pragma: no-cache");

  //date_default_timezone_set("Asia/Jakarta");
  $tglPengambilan=date("d-m-Y");
  $waktu=date("h:i:s");

  $garis_strip = 8;
  //tadinya max_line = 64
  $max_line = 68;
  $total_panjang_header = 13;
  $total_panjang_footer = 5;
  $total_panjang_payment = 8;

  function garisStripV2($bnyk) {
    for($i=1; $i<=$bnyk; $i++) {
      echo "----------";
    }
  }


  function headerPayment() {
    {
      echo "------------------------------------------------------------------------";
      echo "\n";
      echo "Type         Payment             Ref. No.                         Amount";  
      echo "\n";
      echo "------------------------------------------------------------------------";
      echo "\n";
    }
  }

  function jumlahEnter($sisa) {
    for($i=1;$i<=$sisa;$i++) {
      echo "\n";
    }
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

  function tmbh_spaceDetailPersonalV4($batas, $value) {
    $pjg=strlen($value);
    $kosong='';
    $sisa=$batas - $pjg;

    for($i=1; $i <=$sisa; $i++) {
      $kosong .=" ";
    }

    echo $kosong;
  }

  function titleHeaderDataV2() {
    echo "No.";
    tmbh_spaceHeaderProductV2(5);
    echo "Invoice #";
    tmbh_spaceHeaderProductV2(13);
    tmbh_spaceHeaderProductV2(21);
    tmbh_spaceHeaderProductV2(23);
    echo "Amount\n";

    //garisStripV2(8);
  }

  function garisTotal($all_tot_dp, $garis_strip = 8) {
    $total = "TOTAL";
    
    garisStripV2($garis_strip);
    echo "\n";
    
    tmbh_spaceDetailPersonalV3(8, "");
    echo substr($total, 0, 20);

    
    tmbh_spaceDetailPersonalV3(50, $total);

    tmbh_spaceDetailPersonalV3(22, $all_tot_dp);
    echo $all_tot_dp;

    echo "\n";
    garisStripV2($garis_strip);
    echo "\n";
  }

  function footer($printuser) {
    $tglPengambilan=date("d-m-Y");
    $waktu=date("H:i:s");   
    echo "\n";
    echo "\n";          
    echo "       Received By                                       Cashier";  
    echo "\n"; 
    echo "\n";                      
    echo "\n";
    echo "\n";
    echo "(-------------------------)                 (          ".$printuser."          )"; 
    echo "\n";
  }

  $jum_data = count($arr);

  $i = 0;
  foreach($arr as $dta) {
    foreach($dta['header'] as $head) {
        echo "\n\n";
        if($tipe ==  "1") {
          $judul = "                                RESELLER RECEIPT                           \n\n";
          $dfnox = "Reseller Code : ";
          $fullnmx = "Reseller Name : ";
        } else if($tipe == "2") {
          $judul = "                                MEMBER RECEIPT                             \n\n";
          $dfnox = "Member Code   : ";
          $fullnmx = "Member Name   : ";
        } else {
          $judul = "                                STOCKIST RECEIPT                             \n\n";
          $dfnox = "Stockist Code : ";
          $fullnmx = "Stockist Name : ";
        } 
        echo $judul;
        echo $dfnox.$head->kode_reseller;
        spaceHeaderV2(strlen($head->kode_reseller));
        echo "Receipt No    : ".$head->no_kw."\n";

        echo $fullnmx.substr($head->nama_reseler, 0, 15)."";
        spaceHeaderV2(strlen(substr($head->nama_reseler, 0, 15)));
        echo "Register No.  : ".$head->registerno."\n";

        echo "                ";
        spaceHeaderV2(0);
        echo "Date          : ".$head->createdt."\n";
        echo "\n";
      }

      garisStripV2($garis_strip);
      echo "\n";
      titleHeaderDataV2();
      garisStripV2($garis_strip);
      echo "\n";

      $totdp=0;

      $jum_inv = 1;
      foreach($dta['listinv'] as $listinv) {
        $nourut = $jum_inv.".";
        echo $nourut;
        tmbh_spaceDetailPersonalV3(8, $nourut);

        $invno = substr($listinv->invoiceno, 0, 20);
        echo $invno;
        tmbh_spaceDetailPersonalV3(50, $invno);

        $tdp = number_format($listinv->tdp, 0, ".", ",");
        tmbh_spaceDetailPersonalV4(22, $tdp);
        echo $tdp;
        

        $totdp += $listinv->tdp;
        $jum_inv++;
        echo "\n";
      }

      $totdp_num = number_format($totdp, 0, ".", ",");
      garisTotal($totdp_num);
      headerPayment();

      $jum_item_pay = count($dta['payment']);
      

      foreach($dta['payment'] as $pay) {
        echo $pay->paytype;
        tmbh_spaceDetailPersonalV3(13, $pay->paytype);

        echo substr($pay->description, 0, 25);
        tmbh_spaceDetailPersonalV3(20, substr($pay->description, 0, 25));

        echo $pay->docno;
        tmbh_spaceDetailPersonalV3(26, $pay->docno);

        $jum_byr = number_format($pay->payamt, 0, ".", ",");
        tmbh_spaceDetailPersonalV3(13, $jum_byr);
        echo $jum_byr;
        //
        echo "\n";
      }  

      echo "\n";
      echo "\n";
      echo "\n";
      echo "In words : ".penyebut($totdp)." rupiah";

      echo "\n";
      echo "\n";
      
      footer($printuser);

      echo "\n";

      $i++;

      if($i < $jum_data) {
        echo "\f";
      }


    }   

  ?>