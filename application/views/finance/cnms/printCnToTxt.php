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
      tmbh_spaceHeaderProductV2(10);
      echo "Description";
      tmbh_spaceHeaderProductV2(22);
      echo "Qty";
      tmbh_spaceHeaderProductV2(11);
      echo "DP";
      tmbh_spaceHeaderProductV2(8);
      echo "Gross DP\n";

      garisStripV2(9);
    }

    function tmbh_spaceHeaderProductV2($value) {
      $kosong='';

      for($x=1; $x <=$value; $x++) {
        $kosong .=" ";
      }

      echo $kosong;
    }


    function TotQtyV2($pengurang, $x) {
      $kos='';
      $d=strlen($x);
      $kiri=$pengurang - $d;

      for($v=1; $v <=$kiri; $v++) {
        $kos .=" ";
      }

      echo $kos;
      echo $x;
    }

    function tmbh_spaceDetailPersonalV2($no, $value) {
      $pjg=strlen($value);
      $kosong='';

      //Utk STOCK
      if($no==1) {
        $batas=13;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }
      }

      //Utk DESC
      elseif($no==2) {
        $batas=34;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }

        //$kosong = " ";
      }

      //Utk QTY
      elseif($no==3) {
        //$batas = 10;
        $batas=6;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }

        //$kosong = " ";
      }

      //Utk QTY
      elseif($no==4) {
        //$batas = 10;
        $batas=20;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }

        //$kosong = " ";
      }

      //Utk DP
      elseif($no==5) {
        //$batas = 10;
        $batas=15;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }

        //$kosong = " ";
      }

      elseif($no==7) {
        //$batas = 10;
        $batas=9;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }
      }

      //Utk GROSS DP
      elseif($no==6) {
        //$batas = 24;
        $batas=16;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }
      }

      //utk header
      elseif($no==9) {
        //$batas = 24;
        $batas=33;
        $sisa=$batas - $pjg;

        for($i=1; $i <=$sisa; $i++) {
          $kosong .=" ";
        }
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
        if($head->stk_addr2==""|| $head->co_stk_addr2=="") {
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

        $whcdnm = $head->wh_name;
        $deliverynm = $head->ship_desc;

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
        $main=strlen(substr($head->loccd, 0, 15))+strlen(substr($head->main_stk_name, 0, 15))+3;
        //echo "--------------------------------------------------------------------------------";
        echo "\n\n";
        echo "                                ".strtoupper($head->judul)."                     \n\n\n";
        echo "To    : ".substr($head->stk, 0, 15)." / ".substr($head->stk_name, 0, 15)."";
        spaceHeaderV2($to);
        echo "Trx No.       : ".$head->invoiceno."\n";

        echo "        ".substr($head->stk_addr1, 0, 33)."";
        spaceHeaderV2($addr1length);
        echo "Register No.  : ".$head->registerno."\n";

        echo "        ".$addr2."";
        spaceHeaderV2($addr2length);
        echo "Bonus Period  : ".date("Y-m-d", strtotime($head->bnsperiod))."\n";

        echo "         ".$addr3."";
        spaceHeaderV2($addr3length);
        echo "Date          : ".date("d-m-Y", strtotime($head->invoicedt))."\n";

        echo "Tel   : ".substr($head->stk_telp, 0, 18)."";
        spaceHeaderV2($tel);
        echo "Branch        : PT. K-link Nusantara\n\n";

        echo "C/O   : ".substr($head->sc_dfno, 0, 15)." / ".substr($head->co_stk_name, 0, 15)."";
        spaceHeaderV2($colength);
        echo "Warehouse     : ".$whcdnm."\n";

        echo "        ".substr($head->co_stk_addr1, 0, 33)."";
        spaceHeaderV2($coaddr1length);
        echo "Delivery By   : ".$deliverynm."\n";

        echo "         ".$coaddr2."";
        spaceHeaderV2($coaddr2length);
        echo "\n";

        echo "         ".$coaddr3."";
        spaceHeaderV2($coaddr3length);
        echo "Shipping To   : ".$head->shipto."\n";

        echo "Tel   : ".substr($head->co_stk_telp, 0, 18)."";
        spaceHeaderV2($telcolength);
        echo "REF           : $head->docno\n";
        echo "\n";

        echo "Main  : ".substr($head->loccd, 0, 15)." / ".substr($head->co_stk_name, 0, 15)."";
        spaceHeaderV2($main);
        echo "\n\n";
        
        //echo "         ".substr($head->co_stk_addr1, 0, 33)."";
        /* spaceHeaderV2($coaddr1length);
        echo "NO KW         : ".$head->receiptno."\n";
        echo "         ".$coaddr2." \n";
        echo "Tel    : ".substr($head->cotelhm, 0, 18)." \n";
        echo "\n\n\n"; */


        garisStripV2(8);
        echo "\n";
        titleHeaderData();
        echo "\n";

        $totqty=0;
        $totdp=0;


        foreach($getPrd as $prd) {
          //if($head->invoiceno==$prd->invoiceno) {
            echo $prd->prdcd;
            tmbh_spaceDetailPersonalV2(1, $prd->prdcd);

            echo substr($prd->prdnm, 0, 25);
            tmbh_spaceDetailPersonalV2(2, substr($prd->prdnm, 0, 25));

            tmbh_spaceDetailPersonalV2(3, $prd->qtyord);
            echo number_format($prd->qtyord, 0, ".", ",");

            tmbh_spaceDetailPersonalV2(5, $prd->dp);
            echo number_format($prd->dp, 0, ".", ",");

            tmbh_spaceDetailPersonalV2(6, number_format($prd->total_dp, 0, ".", ","));
            echo number_format($prd->total_dp, 0, ".", ",");

            $totdp+=$prd->total_dp;
            $totqty+=$prd->qtyord;
            echo "\n";
          //}

        }



        echo "\n";
        garisStripV2(8);
        echo "\n";

        $space='';

        for($v=1; $v <=11; $v++) {
          $space .=" ";
        }

        echo $space;
        echo "T O T A L       ";
        TotQty(23, number_format($totqty, 0, ".", ","));
        TotQty(29, number_format($totdp, 0, ".", ","));
        echo "\n";
        garisStripV2(8);
        echo "\n\n\n";
        garisStripV2(7);
        echo "\n";
        echo "Type";
        tmbh_spaceHeaderProductV2(5);
        echo "Payment";
        tmbh_spaceHeaderProductV2(10);
        echo "Ref No.";
        tmbh_spaceHeaderProductV2(20);
        echo "Amount\n";
        garisStripV2(7);
        echo "\n";

        if($payment !==null) {
          foreach($payment as $pay) {
            //if($head->invoiceno==$pay->trcd) {
              echo $pay->paytype;
              tmbh_spaceDetailPersonalV2(7, $pay->paytype);
              echo $pay->pay_desc;
              tmbh_spaceDetailPersonalV2(5, $pay->pay_desc);
              echo $pay->docno;
              tmbh_spaceDetailPersonalV2(4, $pay->docno);
              tmbh_spaceDetailPersonalV2(5, number_format($pay->payamt, 0, ".", ","));
              echo number_format($pay->payamt, 0, ".", ",");
              echo "\n";
            //}
          }
        }

        echo "\n\n";
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
