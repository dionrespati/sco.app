<?php 
  header("Content-Type: plain/text");
  header("Content-Disposition: Attachment; filename=cn.txt");
  header("Pragma: no-cache");




  //date_default_timezone_set("Asia/Jakarta");
  $tglPengambilan=date("d-m-Y");
  $waktu=date("h:i:s");


  if($result==null) {
    echo "no data";
  } 
  else {
    function garisStripV2($bnyk) {
      for($i=1; $i<=$bnyk; $i++) {
        echo "----------";
      }
    }

    function titleHeaderDataV2() {
      echo "Stock";
      tmbh_spaceHeaderProductV2(7);
      echo "Description";
      tmbh_spaceHeaderProductV2(26);
      echo "Qty";
      tmbh_spaceHeaderProductV2(11);
      echo "DP";
      tmbh_spaceHeaderProductV2(7);
      echo "Gross DP\n";

      garisStripV2(8);
    }

    function headerPayment() {
      {
        echo "-------------------------------------------------------------------";
        echo "\n";
        echo "Type   Payment             Ref. No.                         Amount";  
        echo "\n";
        echo "-------------------------------------------------------------------";
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


    

    function tmbh_spaceDetailPersonalV3($batas, $value) {
      $pjg=strlen($value);
      $kosong='';
      $sisa=$batas - $pjg;
  
      for($i=1; $i <=$sisa; $i++) {
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


    $a=0;
    foreach($result as $dtax) {
      $header = $dtax['header'];
      $getPrd = $dtax['produk'];
      $payment = $dtax['payment'];

      foreach($header as $head) {
        /* if($head->stk_addr2==""|| $head->co_stk_addr2=="") {
          $addr2="                    ";
          $coaddr2="                    ";
        }

        else {
          $addr2=substr($head->stk_addr2, 0, 33);
          $coaddr2=substr($head->co_stk_addr2, 0, 33);
        }

        if($head->stk_addr3==""|| $head->co_stk_addr3=="") {
          $addr3="                    ";
          $coaddr3="                    ";
        }

        else {
          $addr3=substr($head->stk_addr3, 0, 33);
          $coaddr3=substr($head->co_stk_addr3, 0, 33);
        }

        

        $to=strlen(substr($head->stk, 0, 15))+strlen(substr($head->stk_name, 0, 15))+3;
        $addr1length=strlen(substr($head->stk_addr1, 0, 33));
        $addr2length=strlen($addr2);
        $addr3length=strlen($addr3);
        $tel=strlen(substr($head->stk_telp, 0, 18));
        $colength=strlen(substr($head->sc_dfno, 0, 15))+strlen(substr($head->co_stk_name, 0, 15))+3;
        $coaddr1length=strlen(substr($head->co_stk_addr1, 0, 33));
        $coaddr2length=strlen($coaddr2);
        $coaddr3length=strlen($coaddr3);
        $telcolength=strlen(substr($head->co_stk_telp, 0, 18));
        $main=strlen(substr($head->loccd, 0, 15))+strlen(substr($head->main_stk_name, 0, 15))+3; */
        //echo "--------------------------------------------------------------------------------";
        //echo "\n\n";

        $whcdnm = $head->wh_name;
        $deliverynm = $head->ship_desc;
        echo "                                ".strtoupper($head->judul)."                     \n\n";

        $kosong = "        ";
        $max_show_leftw = 32;
        $max_left_w = 35;
        $to_stk = substr($head->stk, 0, $max_show_leftw);
        echo "To    : ".$to_stk;
        tmbh_spaceDetailPersonalV3($max_left_w, $to_stk);
        echo "Trx No.       : ".$head->invoiceno."\n";

        $to_stkname = substr($head->stk_name, 0, $max_show_leftw);
        echo "        ".$to_stkname;
        tmbh_spaceDetailPersonalV3($max_left_w, $to_stkname);
        echo "Register No.  : ".$head->registerno."\n";

        $to_stkaddr1 = substr($head->stk_addr1, 0, $max_show_leftw);
        echo "        ".$to_stkaddr1;
        tmbh_spaceDetailPersonalV3($max_left_w, $to_stkaddr1);
        echo "Bonus Period  : ".date("Y-m-d", strtotime($head->bnsperiod))."\n";

        $to_stkaddr2 = substr($head->stk_addr2, 0, $max_show_leftw);
        echo "        ".$to_stkaddr2;
        tmbh_spaceDetailPersonalV3($max_left_w, $to_stkaddr2);
        echo "SSR No        : ".$head->ssr_no."\n";

        $to_stkaddr3 = substr($head->stk_addr3, 0, $max_show_leftw);
        echo "        ".$to_stkaddr3;
        tmbh_spaceDetailPersonalV3($max_left_w, $to_stkaddr3);
        echo "Date          : ".date("d-m-Y", strtotime($head->invoicedt))."\n";

        $to_telp = substr($head->stk_telp, 0, $max_show_leftw);
        echo "Tel   : ".$to_telp;
        tmbh_spaceDetailPersonalV3($max_left_w, $to_telp);
        echo "Branch        : PT. K-link Nusantara\n";

        
        echo $kosong;
        tmbh_spaceDetailPersonalV3($max_left_w, "");
        echo "Warehouse     : ".$whcdnm."\n";

        $co_stk = substr($head->sc_dfno, 0, $max_show_leftw);
        echo "C/O   : ".$co_stk;
        tmbh_spaceDetailPersonalV3($max_left_w, $co_stk);
        echo "Delivery By   : ".$deliverynm."\n";

        $co_stkname = substr($head->co_stk_name, 0, $max_show_leftw);
        echo "        ".$co_stkname;
        tmbh_spaceDetailPersonalV3($max_left_w, $co_stkname);
        echo "Shipping To   : ".$head->shipto."\n";

        $co_stkaddr1 = substr($head->co_stk_addr1, 0, $max_show_leftw);
        echo "        ".$co_stkaddr1;
        tmbh_spaceDetailPersonalV3($max_left_w, $co_stkaddr1);
        echo "REF           : ".$head->docno."\n";

        $co_stkaddr2 = substr($head->co_stk_addr2, 0, $max_show_leftw);
        echo "        ".$co_stkaddr2."\n";
        tmbh_spaceDetailPersonalV3($max_left_w, $co_stkaddr2);
        //echo "REF           : ".$head->docno."\n";

        $co_stkaddr3 = substr($head->co_stk_addr3, 0, $max_show_leftw);
        echo "        ".$co_stkaddr3."\n";
        //tmbh_spaceDetailPersonalV3($max_left_w, $co_stkaddr3);
        //echo "              : ";

        $co_telp = substr($head->co_stk_telp, 0, $max_show_leftw);
        echo "Tel   : ".$co_telp."\n\n";

        $main = substr($head->loccd, 0, $max_show_leftw);
        echo "Main  : ".$main."\n";

        $main_stkname = substr($head->main_stk_name, 0, $max_show_leftw);
        echo "        ".$main_stkname;
        tmbh_spaceDetailPersonalV3($max_left_w, $main_stkname);
        echo "\n";

        $main_stkaddr1 = substr($head->main_stk_addr1, 0, $max_show_leftw);
        echo "        ".$main_stkaddr1;
        tmbh_spaceDetailPersonalV3($max_left_w, $main_stkaddr1);
        echo "\n";

        $main_stkaddr2 = substr($head->main_stk_addr2, 0, $max_show_leftw);
        echo "        ".$main_stkaddr2;
        tmbh_spaceDetailPersonalV3($max_left_w, $main_stkaddr2);
        //echo "REF           : ".$head->docno."\n";

        $main_stkaddr3 = substr($head->main_stk_addr3, 0, $max_show_leftw);
        echo "        ".$main_stkaddr3."\n";
        //tmbh_spaceDetailPersonalV3($max_left_w, $co_stkaddr3);
        //echo "              : ";

        $main_telp = substr($head->main_stk_telp, 0, $max_show_leftw);
        echo "Tel   : ".$main_telp."\n\n";

        garisStripV2(8);
        echo "\n";
        titleHeaderDataV2();
        echo "\n";

        $totqty=0;
        $totdp=0;


        foreach($getPrd as $prd) {
          //if($head->invoiceno==$prd->invoiceno) {
            echo $prd->prdcd;
            tmbh_spaceDetailPersonalV3(12, $prd->prdcd);

            echo substr($prd->prdnm, 0, 35);
            tmbh_spaceDetailPersonalV3(35, substr($prd->prdnm, 0, 35));

            tmbh_spaceDetailPersonalV3(8, $prd->qtyord);
            echo number_format($prd->qtyord, 0, ".", ",");

            $dps = number_format($prd->dp, 0, ".", ",");
            tmbh_spaceDetailPersonalV3(13, $dps);
            echo $dps;

            tmbh_spaceDetailPersonalV3(15, number_format($prd->total_dp, 0, ".", ","));
            echo number_format($prd->total_dp, 0, ".", ",");

            $totdp+=$prd->total_dp;
            $totqty+=$prd->qtyord;
            echo "\n";
          //}

        }



        echo "\n";
        garisStripV2(8);
        echo "\n";

        /* $space='';

        for($v=1; $v <=11; $v++) {
          $space .=" ";
        }

        echo $space; */

        tmbh_spaceDetailPersonalV3(12, "");
        
        $tot_jud = substr("T O T A L", 0, 35);;
        echo $tot_jud;
        tmbh_spaceDetailPersonalV3(35, $tot_jud);

        $show_tot_qty = number_format($totqty, 0, ".", ",");
        tmbh_spaceDetailPersonalV3(5, $show_tot_qty);
        echo $show_tot_qty;

        $show_tot_dp = number_format($totdp, 0, ".", ",");
        tmbh_spaceDetailPersonalV3(28, $show_tot_dp);
        echo $show_tot_dp;
        //TotQty(23, number_format($totqty, 0, ".", ","));
        //TotQty(29, number_format($totdp, 0, ".", ","));
        echo "\n";
        garisStripV2(8);
        echo "\n\n\n";

        headerPayment();

        if($payment !==null) {
          foreach($payment as $pay) {
            //if($head->invoiceno==$pay->trcd) {
              /* echo $pay->paytype;
              tmbh_spaceDetailPersonalV3(7, $pay->paytype);
              echo $pay->pay_desc;
              tmbh_spaceDetailPersonalV3(5, $pay->pay_desc);
              echo $pay->docno;
              tmbh_spaceDetailPersonalV3(4, $pay->docno);
              tmbh_spaceDetailPersonalV3(5, number_format($pay->payamt, 0, ".", ","));
              echo number_format($pay->payamt, 0, ".", ",");
              echo "\n"; */
            //}

            echo $pay->paytype;
            tmbh_spaceDetailPersonalV3(7, $pay->paytype);

            echo substr($pay->pay_desc, 0, 25);
            tmbh_spaceDetailPersonalV3(20, substr($pay->pay_desc, 0, 25));

            echo $pay->docno;
            tmbh_spaceDetailPersonalV3(26, $pay->docno);
            $jum_byr = number_format($pay->payamt, 0, ".", ",");
            tmbh_spaceDetailPersonalV3(14, $jum_byr);
            echo $jum_byr;
            //
            echo "\n";
          }
        }

        echo "\n";
        //echo "NO KW : ".$head->receiptno."\n";
        echo "Note : ".$head->note."\n\n";
        echo "The product price includes 11% government tax.  \n\n";
        echo "Trust the above are in good order and conditions.\n";
        echo "Kindly duly signed the duplicate copy of this letter to signify your acceptance. \n\n\n\n\n\n";
        garisStripV2(3);
        tmbh_spaceHeaderProductV2(17);
        garisStripV2(3);
        echo "\n";
        echo "Recipient's Chop & Sign";
        tmbh_spaceHeaderProductV2(24);
        echo "".$head->createnm."  ".$tglPengambilan." ".$waktu."\n";
        tmbh_spaceHeaderProductV2(47);
        echo "K-LINK INTERNATIONAL SDN.BHD.";

        if($a==0) {
          //echo "\n";
          echo "\f";
        }

        else {
          $a++;
        }
      }
    }  
  } 
?>
