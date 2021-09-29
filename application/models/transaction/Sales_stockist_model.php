<?php
class Sales_stockist_model extends MY_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getListSsrByKW($data)
    {
        $qry = "SELECT a.batchscno, a.invoiceno,
                    a.dfno, b.fullnm as dfno_name, a.sc_dfno,
                    a.loccd, CONVERT(char(10), a.etdt,126) as cn_date, a.tdp, a.tbv, c.GDO,
                    a.receiptno, CONVERT(char(10), c.etdt,126) as gdo_dt,
                    c.createnm as gdo_createnm
                FROM ordivtrh a
                LEFT OUTER JOIN mssc b ON (a.dfno = b.loccd)
                LEFT OUTER JOIN intrh c ON (a.receiptno = c.applyto)
                WHERE a.receiptno = ? and a.loccd = ?";
        $params = array(
            onlyAlphaNum($data['paramValue']),
            $this->stockist
        );
        return $this->getRecordset($qry, $params, $this->db2);
    }

    public function getListSalesStockist($data, $tipe)
    {
        $param = "";
        //if($data['flag_batch'])
        if ($data['searchby'] == "dfno" || $data['searchby'] == "trcd" || $data['searchby'] == "orderno") {
            $s = "a.".$data['searchby'];
            $param .= "$s = ? and a.flag_batch = ?";
            $arrParam = array(
                $data['paramValue'],
                $data['flag_batch']
            );
        } elseif ($data['searchby'] == "sc_dfno") {
            if($this->stockist == "BID06") {
                $param .= "a.loccd = ? and a.flag_batch = ?
                and CONVERT(char(10), a.etdt,126) BETWEEN ? AND ?";
                $arrParam = array(
                    $data['paramValue'],
                    $data['flag_batch'],
                    $data['from'],
                    $data['to'],
                );
            } else {
                $param .= "a.sc_dfno = ? AND a.loccd = ? and a.flag_batch = ?
                and CONVERT(char(10), a.etdt,126) BETWEEN ? AND ?";
                $arrParam = array(
                    $data['paramValue'],
                    $data['loccd'],
                    $data['flag_batch'],
                    $data['from'],
                    $data['to'],
                );
            }          
        } elseif ($data['searchby'] == "") {
            $param .= "a.loccd = ? and a.flag_batch = ?
                      and CONVERT(char(10), a.etdt,126) BETWEEN ? AND ?";
            $arrParam = array(
                $data['loccd'],
                $data['flag_batch'],
                $data['from'],
                $data['to'],
            );
        } else {
            $s = "a.".$data['searchby'];
            $param .= "$s = ?";
            $arrParam = array(
                $data['paramValue'],
            );
        }
        $qry = "SELECT
                  a.orderno,
                  a.trcd,
                  a.pricecode,
                  CONVERT(char(10), a.etdt,126) as etdt,
                  a.bnsperiod,
                  a.dfno,
                  b.fullnm,
                  a.loccd,
                  a.sc_dfno,
                  a.sc_co,
                  a.tdp,
                  a.tbv,
                  a.entrytype,
                  a.batchno,
                  a.flag_batch, a.no_deposit, a.id_deposit,
                  CASE
                    WHEN flag_batch = '0' THEN 'Pending'
                    WHEN flag_batch = '1' THEN 'Generated'
                    WHEN flag_batch = '2' THEN 'Approved'
                  end AS flag_batch_stt
                FROM  klink_mlm2010.dbo.sc_newtrh a
                   LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
                WHERE $param AND a.trtype = ? ORDER BY a.trcd";
        //echo $qry;
        array_push($arrParam, $tipe);
        return $this->getRecordset($qry, $arrParam, $this->db2);
    }

    public function getListSalesStockistByProduk($data, $tipe)
    {
        /* if($data['searchby'] == "trcd" || $data['searchby'] == "orderno") {
            $s = "a.".$data['searchby'];
            $param .= "$s = '$data[paramValue]' and a.flag_batch = '".$data['flag_batch']."'";
        } else if($data['searchby'] == "sc_dfno") {
            $param .= "a.sc_dfno = '$data[paramValue]' AND a.loccd = '$data[loccd]' and a.flag_batch = '".$data['flag_batch']."'
                      and CONVERT(char(10), a.etdt,126) BETWEEN '$data[from]' AND '$data[to]'";
        } else if($data['searchby'] == "") {
            $param .= "a.loccd = '$data[loccd]' and a.flag_batch = '".$data['flag_batch']."'
                      and CONVERT(char(10), a.etdt,126) BETWEEN '$data[from]' AND '$data[to]'";
        } else {
            $s = "a.".$data['searchby'];
            $param .= "$s = '$data[paramValue]'";
        } */
        if ($data['searchby'] == "prdnm") {
            $param = "a.loccd = ?' and a.flag_batch = ?
                     AND (a2.prdnm LIKE ? OR a2.prdnm LIKE ?)
                      and CONVERT(char(10), a.etdt,126) BETWEEN ? AND ?";
            $arrParam = array(
                $data['loccd'],
                $data['flag_batch'],
                '%'.$data['paramValue'],
                '%'.$data['paramValue'].'%',
                $data['from'],
                $data['to'],
            );
        } else {
            $param = "a.loccd = ? and a.flag_batch = ?
                     AND (a2.prdcd LIKE ? OR a2.prdcd LIKE ?)
                      and CONVERT(char(10), a.etdt,126) BETWEEN ? AND ?";
            $arrParam = array(
                $data['loccd'],
                $data['flag_batch'],
                $data['paramValue'],
                $data['paramValue'].'%',
                $data['from'],
                $data['to'],
            );
        }



        $qry = "SELECT
                  a.orderno,
                  a.trcd,
                  a.pricecode,
                  CONVERT(char(10), a.etdt,126) as etdt,
                  a.bnsperiod,
                  a.dfno,
                  b.fullnm,
                  a.loccd,
                  a.sc_dfno,
                  a.sc_co,
                  a.tdp,
                  a.tbv,
                  a.entrytype,
                  a.batchno,
                  a.flag_batch, a.no_deposit, a.id_deposit,
                  CASE
                    WHEN flag_batch = '0' THEN 'Pending'
                    WHEN flag_batch = '1' THEN 'Generated'
                    WHEN flag_batch = '2' THEN 'Approved'
                  end AS flag_batch_stt,
                  a1.prdcd, a1.qtyord, a1.dp, a1.bv, a3.dp as dp_pricetab, a3.bv as bv_pricetab
                    FROM klink_mlm2010.dbo.sc_newtrh a
                    LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd a1 ON (a.trcd = a1.trcd)
                    LEFT OUTER JOIN klink_mlm2010.dbo.msprd a2 ON (a1.prdcd = a2.prdcd)
                    LEFT OUTER JOIN klink_mlm2010.dbo.pricetab a3 ON (a1.prdcd = a3.prdcd AND a1.pricecode = a3.pricecode)
                    LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno)
                WHERE $param AND a.trtype = ? ORDER BY a.trcd";
        //echo $qry;
        array_push($arrParam, $tipe);
        return $this->getRecordset($qry, $arrParam, $this->db2);
    }

   

    public function getTrxByTrcdHead($param, $id)
    {
        $qry = "SELECT a.trcd, a.orderno, a.batchno, a.trtype, a.ttptype, a.trtype,
                     a.etdt, a.batchdt, a.remarks, a.createdt, a.createnm, a.updatedt, a.updatenm, a.dfno, b.fullnm as distnm,
                     a.loccd, c.fullnm as loccdnm, c.sctype as sctype,
                     a.sc_co, c.fullnm as sc_conm, c.sctype as co_sctype,
                     a.sc_dfno, c.fullnm as sc_dfnonm, c.sctype as loccd_sctype,
                     a.tdp, a.tbv, a.bnsperiod, a.batchno, a.flag_batch,
                     CONVERT(char(10), a.etdt,126) as tglinput
                FROM sc_newtrh a
                    LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b ON a.dfno = b.dfno
                    LEFT OUTER JOIN klink_mlm2010.dbo.mssc c ON a.sc_dfno = c.loccd
                    LEFT OUTER JOIN klink_mlm2010.dbo.mssc d ON a.sc_co = d.loccd
                    LEFT OUTER JOIN klink_mlm2010.dbo.mssc e ON a.loccd = e.loccd
                WHERE a.$param = ?";
        return $this->getRecordset($qry, $id, $this->db2);
    }

    public function getDetailProduct($param, $id)
    {
        $qry = "SELECT A.trcd, A.prdcd, b.prdnm, A.qtyord, A.bv, A.dp, (A.qtyord*A.bv) AS TOTBV, (A.qtyord*A.dp) AS TOTDP
                  FROM sc_newtrd A
                  LEFT OUTER JOIN klink_mlm2010.dbo.msprd b ON a.prdcd=b.prdcd
                  WHERE a.$param = ?";
        return $this->getRecordset($qry, $id, $this->db2);
    }

    public function getDetailPayment($param, $id)
    {
        $qry = "SELECT a.paytype, docno, payamt, b.description
                  FROM sc_newtrp A
                  LEFT OUTER JOIN klink_mlm2010.dbo.paytype b ON a.paytype=b.id
                  WHERE a.$param = ?";
        return $this->getRecordset($qry, $id, $this->db2);
    }



    /* function getCurrentPeriod()
    {
        $qry = "SELECT a.currperiodSCO as lastperiod,
                DATEADD(month, 1, a.currperiodSCO) as nextperiod
                from klink_mlm2010.dbo.syspref a"; //edit by hilal 28-06-2014

        $res = $this->getRecordset($qry, null, $this->db2);
        return $res;
    }  */

    public function getStockistInfo($idstockist)
    {
        $qry = "SELECT loccd, fullnm, sctype, pricecode
                FROM mssc WHERE loccd = ?";
        $res = $this->getRecordset($qry, $idstockist, $this->db2);
        return $res;
    }

    public function getListPaymentType()
    {
        $qry = "SELECT id, description
                FROM paytype WHERE id IN ('01', '08')"; //edit by hilal 28-06-2014

        $res = $this->getRecordset($qry, null, $this->db2);
        return $res;
    }

    public function getListPaymentTypeOnlyCash()
    {
        $qry = "SELECT id, description
                FROM paytype WHERE id IN ('01')"; //edit by hilal 28-06-2014

        $res = $this->getRecordset($qry, null, $this->db2);
        return $res;
    }

    public function getListPaymentProductVoucher()
    {
        $qry = "SELECT id, description
                FROM paytype WHERE id IN ('01','10')"; //edit by hilal 28-06-2014

        $res = $this->getRecordset($qry, null, $this->db2);
        return $res;
    }

    public function checkValidCashVoucherKhusus($distributorcode, $vchnoo, $vchtype) {

        $threeDigit = substr($vchnoo, 0, 3);

        $check = $this->getTipePromoVch($threeDigit);
        if($check['response'] === "false") {
            return $check;
        }

        return $this->checkValidCashVoucher($distributorcode, $vchnoo, $vchtype);
    }

    public function checkValidCashVoucher($distributorcode, $vchnoo, $vchtype)
    {
        $datee = date("m/d/Y");
        $month = date("m");
        $year = date("Y");

        $threeDigit = substr($vchnoo, 0, 3);
        // $vchnoo1 = substr($vchnoo,0,1);

        /*$qry = "SELECT a.claimstatus,
                       a.DistributorCode, a.VoucherNo as VoucherNo,
                       a.vchtype,a.VoucherAmt,
                       MONTH(a.ExpireDate) as monthExpire,
                       year(a.ExpireDate) as yearExpire
                FROM klink_mlm2010.dbo.tcvoucher a
                WHERE a.VoucherNo = '".$vchnoo."' and a.DistributorCode = '".$distributorcode."'
                and a.claimstatus = '0'
                and MONTH(a.ExpireDate) >= '".$month."' and year(a.ExpireDate) = '".$year."'
                and a.vchtype = 'C'";*/
        $fieldCek = "VoucherNo";
        if ($vchtype == "C") {
            $fieldCek = "voucherkey";
        }

        $qry = "SELECT a.claimstatus,
                       a.DistributorCode,
                       CASE
                         WHEN a.vchtype = 'C' THEN a.voucherkey
                         WHEN a.vchtype = 'P' THEN a.VoucherNo
                         ELSE a.VoucherNo
                       END AS VoucherNo,
                       a.vchtype,a.VoucherAmt, a.vchtype, a.loccd,
                       CONVERT(char(10), a.claim_date,126) as claim_date,
                       CONVERT(char(10), a.ExpireDate,126) as ExpireDate,
                       CONVERT(char(10), GETDATE(),126) as nowDate,
                       CASE
                           WHEN CONVERT(char(10), GETDATE(),126) >= CONVERT(char(10), a.ExpireDate,126) THEN '1'
                           ELSE '0'
                       END AS status_expire,
                       b.docno, c.trcd, c.etdt, c.dfno,
                       CASE
                            WHEN c.trcd is not null THEN d.fullnm
                            WHEN e.no_trx is not NULL THEN F.fullnm
                        END AS stokisname,
                        e.no_trx, a.status_open, a.openstatus_dt, g.fullnm
                FROM tcvoucher a
                LEFT OUTER JOIN sc_newtrp b ON (b.docno = a.$fieldCek)
                LEFT OUTER JOIN sc_newtrh c ON (c.trcd = b.trcd AND c.dfno = a.DistributorCode)
                LEFT OUTER JOIN deposit_D e ON (a.voucherkey COLLATE SQL_Latin1_General_CP1_CI_AI = e.voucher_scan)
                LEFT OUTER JOIN mssc d ON (c.createnm COLLATE SQL_Latin1_General_CP1_CS_AS= d.loccd)
                LEFT OUTER JOIN mssc f ON (a.loccd COLLATE SQL_Latin1_General_CP1_CS_AS = f.loccd)
                LEFT OUTER JOIN msmemb g ON (a.DistributorCode COLLATE SQL_Latin1_General_CP1_CS_AS = g.dfno)
                WHERE a.$fieldCek = ?
                    AND a.DistributorCode = ?
                    AND a.vchtype = ?
                ";
        //echo $qry;
        $arrparam = array(
            $vchnoo, $distributorcode, $vchtype
        );
        $res = $this->getRecordset($qry, $arrparam, $this->db2);

        /* $arrData = $res['arraydata']; */
        if ($res == null) {
            $response = jsonFalseResponse("Voucher $vchnoo tidak valid..");
            return $response;
        }


        if ($res[0]->no_trx != null) {
            $response = array("response" => "false", "arrayData" => $res,"message" => "Voucher ".$vchnoo." sudah diinput di voucher cash deposit ".$res[0]->no_trx.", tgl :".$res[0]->claim_date.", Stockist : ".$res[0]->loccd." / ".$res[0]->stokisname);
            return $response;
        }

        if ($res[0]->VoucherNo == $res[0]->docno && $res[0]->DistributorCode == $res[0]->dfno) {
            $response = array("response" => "false", "arrayData" => $res,"message" => "Voucher ".$vchnoo." sudah pernah di klaim pada ".$res[0]->claim_date.", Stockist : ".$res[0]->loccd." - ".$res[0]->stokisname);
            return $response;
        }

        if ($res[0]->status_open == "0" || $res[0]->status_open == NULL || $res[0]->status_open == "") {
            $response = array("response" => "false", "arrayData" => $res,"message" => "Voucher ".$vchnoo." harus di aktifkan dulu melalui k-net di menu Sales & Bonus -> Aktivasi Voucher Cash/Product");
            return $response;
        }

        if ($res[0]->docno == null && $res[0]->trcd == null && $res[0]->no_trx == null && $res[0]->loccd == "BID06") {
            $qryTrx = "SELECT b.invoiceno as trcd, b.dfno, CONVERT(VARCHAR(10),b.tgltrans, 120) as createdt,
                                c.loccd, d.fullnm as loccd_name
                                FROM KL_TVOCASH a
                                INNER JOIN KL_TEMPTRANS b ON (a.grupunik COLLATE SQL_Latin1_General_CP1_CS_AS = b.grupunik)
                                LEFT OUTER JOIN newtrh c ON (b.invoiceno COLLATE SQL_Latin1_General_CP1_CS_AS = c.trcd)
                                LEFT OUTER JOIN mssc d ON (c.loccd = d.loccd)
                                WHERE a.voucherno = ?";
            $arrparam2 = array(
                $vchnoo
            );
            $kmart_result = $this->getRecordset($qryTrx, $arrparam2, $this->db2);
            if ($kmart_result != null) {
                $noinvoice = $kmart_result[0]->trcd;
                $response = array(
                    "response" => "false", "arrayData" => $res,
                    "message" => "Voucher ".$vchnoo." sudah pernah di klaim pada ".$res[0]->claim_date.", Stockist : BID06 - PT K-LINK NUSANTARA, no trx : ".$noinvoice
                );
                return $response;
            }
        }

        $res2 = null;
        if ($res != null && $threeDigit == "XPV" || $threeDigit == "ZVO" || $threeDigit == "XPP" || $threeDigit == "XHD" || $threeDigit == "AYU") {
            //$detProd = "SELECT * FROM TWA_KLPromo_Oct17_D WHERE Voucherno = '$vchnoo'";
            $res2 = $this->getListProdPromo($vchnoo);
        }

        if ($res[0]->status_expire == '1') {
            $response = jsonFalseResponse("Voucher ".$vchnoo." sudah expire pada tanggal : ".$res[0]->ExpireDate."");
            return $response;
        }

        return array("response" => "true", "arrayData" => $res, "detProd" => $res2);
    }

    public function getListProdPromo($vchnoo)
    {
        $detProd = "SELECT * FROM TWA_KLPromo_Oct17_D WHERE Voucherno = ?";
        $res2 = $this->getRecordset($detProd, $vchnoo, $this->db2);
        return $res2;
    }

    public function getListProdPromoByVchAndPrdcd($vchnoo, $prdcd, $pricecode, $qty)
    {
        /* $detProd = "SELECT * FROM TWA_KLPromo_Oct17_D WHERE Voucherno = '$vchnoo' AND prdcd = '$prdcd'";
        $params = array(
            $vchnoo,
            $prdcd
        );
        $res2 = $this->getRecordset($detProd, $params, $this->db2);
        return $res2; */

        $qry = "SELECT a.prdcd, CAST(a.qtyord AS INT) as qty, 
                    b.dp as harga, a.qtyord * b.dp as total_harga,
                    b.bv as poin, a.qtyord * b.bv as total_poin 
                FROM TWA_KLPromo_Oct17_D a
                LEFT OUTER JOIN pricetab b ON (a.prdcd = b.prdcd AND b.pricecode = ?)
                WHERE Voucherno = ?
                and a.prdcd = ?";
        $arrParam = array(
            $pricecode, $vchnoo, $prdcd
        ); 
        $hasil = $this->getRecordset($qry, $arrParam, $this->db2);
        if($hasil == null) {
            return array("response" => "false", "message" => "Kode produk $prdcd tidak termasuk dalam voucher promo ini..");
        }
         
        $qtyord = $hasil[0]->qty;
        if($qtyord != $qty) {
            return array("response" => "false", "message" => "Kode produk $prdcd jumlah qty seharus nya $qtyord");
        }

        $harga = $hasil[0]->harga;
        if($harga == null) {
            return array("response" => "false", "message" => "Harga untuk Kode produk $prdcd tidak ada..");
        }

        return array("response" => "true", "arrayData" => $hasil);
    }

    function checkPromoGV($prdcd, $kodeGV, $pricecode) {
        $qry = "SELECT a.*, b.prdnm, c.dp, 0 as bv
                FROM TWA_PROMONOP19_VCH a
                INNER JOIN msprd b ON (a.prdcd = b.prdcd)
                INNER JOIN pricetab c ON (a.prdcd = c.prdcd AND c.pricecode = ?)
                where a.kodeV IN (?) AND a.prdcd = ?";
          $arrParam = array(
            $pricecode, $kodeGV, $prdcd
        ); 
         $hasil = $this->getRecordset($qry, null, $this->db2);
         if($hasil == null) {
            return array("response" => "false", "message" => "Kode produk $prdcd tidak termasuk dalam voucher promo ini..");
         }
         return array("response" => "true", "arrayData" => $hasil); 
    }

    public function checkPaymentAndProduct($arr)
    {
        $prdcd = $arr['prdcd'];
        $jum = $arr['jum'];
        $payChooseType = $arr['payChooseType'];
        $payReff = $arr['payReff'];
        $pricecode = $arr['pricecode'];

        $jum = count($prdcd);

        $total_harga = 0;
        for ($i=0; $i < $jum; $i++) {
            $qty = $jum[$i];
            if ($qty == "0" || $qty == "") {
                return jsonFalseResponse("Kode $prdcd[$i] kuantiti produk nya minimal harus 1..");
            } else {
                $qry = "SELECT a.prdcd, $qty * a.dp as total_harga
                        FROM pricetab a
                        WHERE a.prdcd = ? AND a.pricecode = ?";
                $arrParam = array(
                    $prdcd[$i], $pricecode
                );
                $res2 = $this->getRecordset($qry, $arrParam, $this->db2);

                if ($res2 == null) {
                    return jsonFalseResponse("Kode $prdcd[$i] tidak ada harga nya..");
                } else {
                    $total_harga += $res2[0]->total_harga;
                }
            }
        }
    }

    public function saveTrx($data)
    {
        /*---------------------------------------------------------------
         * PROSES PEMBAYARAN, SET values utk multiple insert ke sc_newtrp
         * --------------------------------------------------------------*/
        $jumPay = count($data['payChooseType']);
        $totalNilaiVoucher = 0;
        $totalCash = 0;
        $totalBayar = 0;
        //set tipe2 voucher
        $cv = 0;
        $pv = 0;
        $pv_hadiah = 0;
        $pv_hydro = 0;
        //end
        $qryAddPay = "";
        $qryUpdVoucher = "";
        $j = 0;
        $no_bv = false;
        for ($i=0;$i<$jumPay;$i++) {
            $j++;
            //Jika pembayaran menggunakan Voucher Cash / Voucher Produk
            if ($data['payChooseType'][$i] == "08" || $data['payChooseType'][$i] == "10") {

                        //Bila dibayar pakai Voucher Product
                //BV = 0
                if ($data['payChooseType'][$i] == "10") {
                    $no_bv = true;
                    $pref_vch = strtoupper(substr($data['payReff'][$i], 0, 3));
                    if ($pref_vch == "XPV" || $pref_vch == "ZVO" || $pref_vch == "XPP") {
                        $pv_hadiah++;
                    } elseif ($pref_vch == "XHD") {
                        $pv_hydro++;
                    } else {
                        $pv++;
                    }
                } else {
                    $cv++;
                }

                //$payChooseValue = intval(str_replace('.', '', $data['payChooseValue'][$i]));
                $payChooseValue = $data['payChooseValue'][$i];
                $data['payChooseValue'][$i] = $payChooseValue;
                $totalNilaiVoucher += $payChooseValue;
                $totalBayar += $payChooseValue;
                $qryAddPay .= "('DION_TRCD', ".$j.",'".$data['payChooseType'][$i]."','".$data['payReff'][$i]."',".$data['payChooseValue'][$i].",0,'','0','ID','1'), ";

                $qryUpdVoucher .= "'".$data['payReff'][$i]."', ";

            //trcd,seqno,paytype,docno,payamt,deposit,notes,trcd2,PT_SVRID,voucher
            } else {
                //$cash = intval(str_replace('.', '', $data['payChooseValue'][$i]));
                $cash = $data['payChooseValue'][$i];
                $payChooseValue = $data['payChooseValue'][$i];
                $totalCash += $cash;
                $data['payChooseValue'][$i] = $cash;
                $totalBayar += $cash;
            }
        }
        //END
        /*---------------------------------------------------------------
         * DETAIL PRODUK, SET values utk multiple insert ke sc_newtrd
         * --------------------------------------------------------------*/
        $jum = count($data['prdcd']);
        $totBV = 0;
        $totDP = 0;
        $qryAddProduct = "";
        for ($i=0;$i<$jum;$i++) {
            /* $dp = intval(str_replace('.', '', $data['harga'][$i]));
            $bv = intval(str_replace('.', '', $data['poin'][$i])); */
            $dp = $data['harga'][$i];
            $bv = $data['poin'][$i];
            $totBV += $data['jum'][$i] * $bv;
            $totDP += $data['jum'][$i] * $dp;
            if ($no_bv == true) {
                $data['poin'][$i] = 0;
                $data['sub_tot_bv'][$i] = 0;
            } else {
                $data['poin'][$i] = $bv;
                $data['sub_tot_bv'][$i] = $totBV;
            }
            $data['harga'][$i] = $dp;
            $data['sub_tot_dp'][$i] = $totDP;
            $qryAddProduct .= "('DION_TRCD','".$data['prdcd'][$i]."', ".$data['jum'][$i].", 0, 0, ".$data['harga'][$i].", ".$data['poin'][$i].", ".$data['poin'][$i].", ";
            $qryAddProduct .= "0,0,0,0,0,0,'0',0,0,'ID','".$data['pricecode']."'), ";
        }
        $qryAddProduct = substr($qryAddProduct, 0, -2);
        //END
        /*----------------------------------------
         * Pembayaran campuran antara cash dan voucher
         * nilai cash yang diinput dihitung setelah
         * menghitung total nilai voucher
         * ---------------------------------------*/
        $change = ($totalCash + $totalNilaiVoucher) - $totDP;
        $sisaCash = $totalCash - $change;
        if ($sisaCash > 0) {
            $qryAddPay .= "('DION_TRCD', ".$j.",'01','',".$sisaCash.",0,'','0','ID','0'), ";
        }
        $qryAddPay = substr($qryAddPay, 0, -2);
        $qryUpdVoucher = substr($qryUpdVoucher, 0, -2);
        //END
        if ($totDP > $totalBayar) {
            $return = jsonFalseResponse("Pembayaran kurang, total harga produk : $totDP, total pembayaran : $totalBayar");
        } else {
            //$return = jsonTrueResponse($data, "ok");
            $jenis = "id";
            if ($cv > 0) {
                $jenis = "cv";
            } elseif ($pv > 0) {
                $jenis = "pv";
            } elseif ($pv_hadiah > 0) {
                $jenis = "pv_hadiah";
            } elseif ($pv_hydro > 0) {
                $jenis = "pv_hydro";
            }
            //$res['cek_seQ'] = $this->cek_seQ($jenis);
            //$data['idnoo'] = $this->get_idno($jenis);

            $qryRes = array(
                        "jenis" => $jenis,
                        "qryProduct" => $qryAddProduct,
                        "qryPayment" => $qryAddPay,
                        "updVoucher" => $qryUpdVoucher,
                        "data" => $data,
                        "totDP" => $totDP,
                        "totBV" => $totBV
                    );


            $return = $this->insertTrxStockist($qryRes);
            return $return;
        }


        return $return;
    }

    public function insertTrxStockist($arrQuery)
    {
        $trcd = "";
        $db_qryx = $this->load->database('klink_mlm2010', true);
        //$db_qryx = $this->load->database('klink_mlm2010', true);
        $db_qryx->trans_begin();
        $datax = $arrQuery['data'];
        if ($datax['ins'] == "1") {
            $cek_seQ = $this->cek_seQ($arrQuery['jenis']);
            $trcd = $this->get_idno($arrQuery['jenis']);
        //$trcd = "PV09090";
        } else {
            $trcd = $datax['trcd'];

            //delete sc_newtrh
            $del_1 = "DELETE FROM sc_newtrh WHERE trcd = '$trcd'";
            $exe_del1 = $db_qryx->query($del_1);

            //delete sc_newtrd
            $del_2 = "DELETE FROM sc_newtrd WHERE trcd = '$trcd'";
            $exe_del2 = $db_qryx->query($del_2);

            //delete sc_newtrp
            $del_3 = "DELETE FROM sc_newtrp WHERE trcd = '$trcd'";
            $exe_del3 = $db_qryx->query($del_3);
        }

        $pref_trcd = substr($trcd, 0, 2);
        //$cek_seQ = $this->cek_seQ($arrQuery['jenis']);
        //$trcd = $this->get_idno($arrQuery['jenis']);



        //INSERT PRODUCT
        $prd = str_replace('DION_TRCD', $trcd, $arrQuery['qryProduct']);
        $insDet = "insert into sc_newtrd
                        (trcd,prdcd,qtyord,qtyship,qtyremain,dp,pv,bv,taxrate,sp,sb,scdisc,seqno,scdiscamt,syn2web,qty_used,qty_avail,
                        PT_SVRID,pricecode) VALUES $prd";
        //echo $insDet;
        $query1 = $db_qryx->query($insDet);



        //INSERT PAYMENT
        $pyment = str_replace('DION_TRCD', $trcd, $arrQuery['qryPayment']);
        $insDetTrf = "insert into sc_newtrp (trcd,seqno,paytype,docno,payamt,deposit,notes,trcd2,PT_SVRID,voucher)
                        VALUES $pyment";
        //echo $insDetTrf;
        $query2 = $db_qryx->query($insDetTrf);

        if ($arrQuery['updVoucher'] != "") {
            $jenis = $arrQuery['jenis'];
            if ($jenis == "pv" || $jenis == "pv_hadiah" || $jenis == "pv_hydro") {
                $field_upd_vch = "VoucherNo";
            } elseif ($jenis == "cv") {
                $field_upd_vch = "voucherkey";
            }
            //UPDATE VOUCHER
            $updVc = "UPDATE tcvoucher SET claimstatus = '1',
                            updatenm = '".$this->stockist."',
                            claim_date = '$this->dateTime',
                            updatedt = '$this->dateTime',
                            loccd = '$this->stockist'
                          WHERE $field_upd_vch IN ($arrQuery[updVoucher]) AND DistributorCode = '".$datax['dfno']."'";
            //echo $updVc;
            $query2 = $db_qryx->query($updVc);
        }
        //INSERT HEADER
        $folderGets = explode('/', $datax['bnsperiod']);
        $x['month'] = $folderGets[0];
        $x['year'] = $folderGets[1];
        $bonusperiod = $x['month']."/"."1"."/".$x['year'];
        $stockistid = $this->session->userdata('stockist');
        $sctypee = $this->session->userdata('group_scoapp');
        $createdt = date('Y-m-d H:i:s');
        $trdt = date('Y-m-d H:i:s');

        $ttptype = "SC";
        //SUBSC = untuk sub / mobile stockist, SC = untuk stockist
        if ($datax['sctype'] == "3") {
            $ttptype = "SUBSC";
        }

        //set trtype VP1 jika pembayaran non BV menggunakan Voucher Product

        if ($pref_trcd == "PV") {
            $trxtype = "VP1";
            $totalBV = 0;
        } else {
            $trxtype = "SB1";
            $totalBV = $arrQuery['totBV'];
        }

        $insHead = "insert into sc_newtrh
                (trcd,trtype,trdt,dfno,loccd,tdp,taxrate,taxamt,discamt,shcharge,
                othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                orderno,type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                sc_co,bnsperiod,remarks,othdisc,flag_batch,batchstatus,flag_recover,
                system,ttptype,entrytype,flag_show,flag_approval,id_deposit, no_deposit)
                values
                ('".$trcd."','$trxtype','".$trdt."','".$datax['dfno']."',
                '".$datax['loccd']."',".$arrQuery['totDP'].",10,0,0,0,0,".$totalBV.",".$totalBV.",
                ".$totalBV.",".$totalBV.",".$arrQuery['totDP'].",'WH001','B001','".$datax['pricecode']."',
                '01','01','03',".$arrQuery['totDP'].",0,0,".$arrQuery['totDP'].",'".$stockistid."','".$stockistid."',
                '0',0,0,0,0,'0','".$createdt."','".$datax['orderno']."','0',0,0,'".$datax['sctype']."','1','0','0','0',
                0,'0','0','0','1','ID','".$datax['sc_dfno']."',
                '".$datax['sc_co']."','".$bonusperiod."','".$datax['remarks']."',
                0,'0','0','0','0','$ttptype',4,'0','0', '".$datax['id_deposit']."', '".$datax['no_deposit']."')";
        //echo $insHead;
        $query3 = $db_qryx->query($insHead);

        if ($db_qryx->trans_status() === false) {
            $db_qryx->trans_rollback();
            $return = array("response" => "false", "message" => "Data sales gagal disimpan..");
            return $return;
        } else {
            $db_qryx->trans_commit();

            $arrx = array(
                         "trcd" => $trcd,
                         "orderno" => $datax['orderno'],
                         "dfno" => $datax['dfno'],
                         "fullnm" => $datax['fullnm'],
                         "totalDP" => $arrQuery['totDP'],
                         "totalBV" => $totalBV,
                         "pref_trcd" => $pref_trcd,
                         "sc_dfno" => $datax['sc_dfno'],
                         //"sc_name" => $datax['sc_name'],
                         "sc_co" => $datax['sc_co'],
                         //"sc_co_name" => $datax['sc_co_name'],
                         "ins" => $datax['ins']
                    );
            $return = array("response" => "true", "message" => "Data sales berhasil disimpan..", "data" => $arrx);
            return $return;
        }
    }

    public function cek_seQ($tipe_pay) // dipake
    {
        $this->db = $this->load->database('klink_mlm2010', true);
        $y1=date("y");
        $m=date("m");

        //$this->db->trans_begin();

        //if(in_array('p',$tipe_pay))
        if ($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $tbl = "SEQ_PV"."$y1"."$m";
        } elseif ($tipe_pay == 'cv') {
            $tbl = "SEQ_CV"."$y1"."$m";
        } elseif ($tipe_pay == 'pv_hydro') {
            $tbl = "SEQ_ID"."$y1"."$m";
        } else {
            $tbl = "SEQ_ID"."$y1"."$m";
        }

        $cek = "select * from $tbl";

        $query = $this->db->query($cek);
        if ($query->num_rows < 1) {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        } else {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        }

        /*if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        } */

        return $query;
    }

    public function get_idno($tipe_pay) // dipake
    {
        $this->db = $this->load->database('klink_mlm2010', true);
        $y1=date("y");
        $m=date("m");

        //$this->db->trans_begin();

        //if(in_array('p',$tipe_pay))
        if ($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $tbl = "SEQ_PV"."$y1"."$m";
        } elseif ($tipe_pay == 'cv') {
            $tbl = "SEQ_CV"."$y1"."$m";
        } elseif ($tipe_pay == 'pv_hydro') {
            $tbl = "SEQ_ID"."$y1"."$m";
        } else {
            $tbl = "SEQ_ID"."$y1"."$m";
        }

        $qry = "SELECT * FROM $tbl
                    WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";

        $query = $this->db->query($qry);
        if ($query == null) {
            $ss = 0;
        } else {
            foreach ($query->result() as $data) {
                $ss = $data->SeqID;
            }
        }
        $jumlah = $query->num_rows();

        $next_seq = sprintf("%06s", $ss);
        $prefix = date('ym');

        if ($tipe_pay == 'pv' || $tipe_pay == 'pv_hadiah') {
            $y =  strval("PV".$prefix.$next_seq);
        } elseif ($tipe_pay == 'cv') {
            $y =  strval("CV".$prefix.$next_seq);
        } elseif ($tipe_pay == 'pv_hydro') {
            $y =  strval("IDH".$prefix.$next_seq);
        } else {
            $y =  strval("ID".$prefix.$next_seq);
        }

        return $y;
    }

    public function deleteTrx($trcd)
    {
        $db_qryx = $this->load->database('klink_mlm2010', true);

        $db_qryx->trans_begin();

        $qry = "SELECT a.trcd, a.paytype, a.docno, a.vchtype, b.dfno
                FROM klink_mlm2010.dbo.sc_newtrp a
                LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
                WHERE a.trcd = ? and a.paytype != '01'";
        $arrParam = array($trcd);        
        $listVch = $this->getRecordset($qry, $arrParam, $this->db2);

        //prosedur untuk mengaktifkan voucher cash/produk yang sudah claim/input agar bs diinput ulang
        if ($listVch != null) {
            foreach ($listVch as $dta) {
                //paytype = 08 (vch cash/deposit/umroh)
                //paytype = 10 (vch produk, P, ZVO, XPP, XPV, XHD)
                if ($dta->paytype == "08") {
                    $upd = "UPDATE klink_mlm2010.dbo.tcvoucher
                                SET claimstatus = '0', claim_date = '', loccd = ''
                            WHERE DistributorCode = ? and voucherkey = ?";
                } elseif ($dta->paytype == "10") {
                    $upd = "UPDATE klink_mlm2010.dbo.tcvoucher
                                SET claimstatus = '0', claim_date = '', loccd = ''
                            WHERE DistributorCode = ? and VoucherNo = ?";
                }
                /* echo $upd;
                echo "<br />"; */
                $arrParam2 = array($dta->dfno, $dta->docno);
                $db_qryx->query($upd, $arrParam2);
            }
        }

        $trh = "DELETE FROM klink_mlm2010.dbo.sc_newtrh WHERE trcd = ?";
        $trd = "DELETE FROM klink_mlm2010.dbo.sc_newtrd WHERE trcd = ?";
        $trp = "DELETE FROM klink_mlm2010.dbo.sc_newtrp WHERE trcd = ?";

        /* echo $trh;
        echo "<br />";
        echo $trd;
        echo "<br />";
        echo $trp;
        echo "<br />"; */

        $db_qryx->query($trh, $arrParam);
        $db_qryx->query($trd, $arrParam);
        $db_qryx->query($trp, $arrParam);

        if ($db_qryx->trans_status() === false) {
            $db_qryx->trans_rollback();
            $return = array("response" => "false", "message" => "Data transaksi $trcd gagal dihapus..");
            return $return;
        } else {
            $db_qryx->trans_commit();
            $return = array("response" => "true", "message" => "Data transaksi $trcd berhasil dihapus..");
            return $return;
        }
    }

    public function getListPrdPromoNewMember() {
        $qry = "SELECT prdcd FROM msprd a WHERE a.prdcd LIKE 'CSVD%NM'";
        $hasil = $this->getRecordset($qry, null, $this->db2);
        $returnArr = array();
        foreach($hasil as $dta) {
            array_push($returnArr, $dta->prdcd);
        }
        return $returnArr;
    }

    public function showProductPriceForPvr($productcode, $pricecode, $jenis, $jenis_promo = "reguler")
    {
        $qry = "SELECT  b.prdcd,b.prdnm, b.webstatus, b.scstatus, b.status,
                    c.bv,c.dp, d.cat_inv_id_parent as bundling, b1.pvr_exclude_status
                from klink_mlm2010.dbo.pricetab c
                LEFT OUTER JOIN klink_mlm2010.dbo.msprd b
                    on c.prdcd=b.prdcd
                LEFT OUTER JOIN klink_mlm2010.dbo.product_exclude_sales b1
                    on (b.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS = b1.prdcd)
                LEFT OUTER JOIN db_ecommerce.dbo.master_prd_bundling d
                    ON (b.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS = d.cat_inv_id_parent)
                where c.pricecode = ? and
                      c.prdcd = ?";
        //return $this->get_data_json_result($qry);
        $arrParam = array($pricecode, $productcode);
        $hasil = $this->getRecordset($qry, $arrParam, $this->db2);
        if ($hasil == null) {
            $arr = array("response" => "false", "message" => "Kode produk salah");
            return $arr;
        }

        //jika produk adalah, tipe product exclude yang tidak boleh diinput dalam pembelanjaan PVR
        $produkname = $hasil[0]->prdnm;
        if ($jenis == "pv" && $hasil[0]->pvr_exclude_status == "1") {
            $arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput dalam pembelanjaan PVR");
            return $arr;
        }
        /* $produkname = $hasil[0]->prdnm;
        if($hasil[0]->pvr_exclude_status == "1") {

        } */

        //jika produk adalah produk free / yang harga nya 0
        //tidak bisa untuk inputan PVR
        $harga = (int) $hasil[0]->dp;
        if ($jenis == "pv" && $harga == 0) {
            $arr = array("response" => "false", "message" => "Produk $productcode / $produkname adalah kode produk FREE");
            return $arr;
        }

        //jika produk adalah produk bundling
        //tidak bisa untuk inputan PVR
        if ($jenis == "pv" && $hasil[0]->bundling !== null) {
            $arr = array("response" => "false", "message" => "Produk $productcode / $produkname adalah produk bundling/paket");
            return $arr;
        }

        //jika produk status nya sudah
        if($jenis_promo == "reguler") {
            if ($hasil[0]->scstatus !== "1" || $hasil[0]->webstatus !== "1" || $hasil[0]->status !== "1") {
                $arr = array("response" => "false", "message" => "Produk $productcode / $produkname tidak dapat diinput untuk stokis..");
                return $arr;
            }
        }

        $arr = array("response" => "true", "arraydata" => $hasil);
        return $arr;
    }

    public function showProductPrice($productcode, $pricecode)
    {
        $qry = "SELECT  b.prdcd,b.prdnm, b.webstatus, b.scstatus, b.status,
                  c.bv,c.dp
                from klink_mlm2010.dbo.pricetab c
                LEFT JOIN klink_mlm2010.dbo.msprd b
                  on c.prdcd=b.prdcd
                where c.pricecode = ? and
                      c.prdcd = ?";
        //return $this->get_data_json_result($qry);
        $arrParam = array($pricecode, $productcode);
        $hasil = $this->getRecordset($qry, $arrParam, $this->db2);
        if ($hasil == null) {
            $arr = array("response" => "false", "message" => "Kode produk salah");
            return $arr;
        }

        if ($hasil[0]->scstatus !== "1" || $hasil[0]->webstatus !== "1" || $hasil[0]->status !== "1") {
            $arr = array("response" => "false", "message" => "Kode produk $productcode tidak dapat diinput untuk stokis..");
            return $arr;
        }

        $arr = array("response" => "true", "arraydata" => $hasil);
        return $arr;
    }

    public function cekHeaderTrx($field, $value)
    {
        $qry = "SELECT a.trcd, a.trtype,a.batchno, a.csno, a.tdp, a.pay1amt,
                  a.tpv, a.tbv, a.nbv, a.pricecode, a.ndp, a.totpay,
                   SUM(b.qtyord * c.dp) as total_dp,
                   CASE
                   WHEN a.trtype = 'VP1' THEN 0 ELSE
                      SUM(b.qtyord * c.bv)
                   END as total_bv
                FROM klink_mlm2010.dbo.sc_newtrh a
                LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrd b ON (a.trcd = b.trcd)
                LEFT OUTER JOIN klink_mlm2010.dbo.pricetab c ON (b.prdcd = c.prdcd AND a.pricecode = c.pricecode)
                WHERE a.$field = ?
                GROUP BY a.trcd, a.trtype, a.batchno, a.csno, a.tdp, a.pay1amt, a.tpv,
                  a.tbv, a.nbv, a.pricecode, a.ndp, a.totpay";
        //echo $qry;
        $arrParam = array($value);
        $hasil = $this->getRecordset($qry, $arrParam, $this->db2);
        $hasil2 = null;
        if ($hasil != null) {
            $pref = substr($hasil[0]->trcd, 0, 2);
            $pref1 = substr($hasil[0]->trcd, 0, 3);
            if ($pref == "PV" || $pref = "CV" || $pref1 == "IDH") {
                $pay = "SELECT ISNULL(SUM(a.payamt), 0) as total_bayar
                    FROM sc_newtrp a WHERE a.trcd = '$value'";
                $hasil2 = $this->getRecordset($pay, null, $this->db2);
            } else {
                $totdp = $hasil[0]->tdp;
                $pay = "SELECT ISNULL(SUM(a.payamt), $totdp) as total_bayar
                    FROM sc_newtrp a WHERE a.trcd = '$value'";
                $hasil2 = $this->getRecordset($pay, null, $this->db2);
            }
        }


        return $arr = array(
            "header" => $hasil,
            "payment" => $hasil2
        );
    }

    public function koreksiTransaksi($arr)
    {
        $db_qryx = $this->load->database('klink_mlm2010', true);

        $array = $arr['header'];
        $trcd = $array[0]->trcd;
        $total_dp_prd = $array[0]->total_dp;
        $total_bv_prd = $array[0]->total_bv;

        $db_qryx->trans_begin();

        if ($array[0]->ndp != $total_dp_prd || $array[0]->tdp != $total_dp_prd ||
          $array[0]->totpay != $total_dp_prd
          || $array[0]->tbv != $array[0]->total_bv || $array[0]->tpv != $total_bv_prd
          || $array[0]->nbv != $total_bv_prd) {
            $qry = "UPDATE sc_newtrh SET tdp = ?, 
                     ndp = ?, totpay = ?,
                     pay1amt = ?, tbv = ?, 
                    tpv = ?, nbv = ?
                    WHERE trcd = ?";
            $arrParam = array(
                $total_dp_prd,
                $total_dp_prd,
                $total_dp_prd,
                $total_bv_prd,
                $total_bv_prd,
                $total_bv_prd,
                $trcd
            );        
            $db_qryx->query($qry, $arrParam);
            //echo $qry;
        }

        $payment = $arr['payment'][0];
        $nilai_vch = 0;
        if ($payment->total_bayar != $array[0]->total_dp) {
            $trp = "SELECT LEFT(a.docno, 2) as tp_docno, a.docno,
                        a1.dfno, a.paytype, a.payamt,
                        CASE
                         WHEN a.paytype = '10' THEN b.VoucherAmt
                         WHEN a.paytype = '08' THEN c.VoucherAmt
                        ELSE 0
                        END AS nilai_vch
                    FROM sc_newtrp a
                    INNER JOIN sc_newtrh a1 ON (a.trcd = a1.trcd)
                    LEFT OUTER JOIN tcvoucher b ON (a.docno = b.VoucherNo AND a1.dfno = b.DistributorCode AND a.paytype = '10')
                    LEFT OUTER JOIN tcvoucher c ON (a.docno = c.voucherkey AND a1.dfno = c.DistributorCode AND a.paytype = '08')
                    WHERE a.trcd = ? and a.paytype != '01'
                    ORDER BY a.paytype DESC";
            $arrParam2 = array(
                $trcd
            );
            $res = $db_qryx->query($trp, $arrParam2);
            $res2 = $res->result();
            if ($res2 != null) {
                foreach ($res2 as $dtax) {
                    if ($dtax->tp_docno != "VC" && $dtax->payamt != $dtax->nilai_vch && ($dtax->nilai_vch != null || $dtax->nilai_vch != "")) {
                        $upd = "UPDATE sc_newtrp 
                                  SET payamt = ? 
                                WHERE trcd = ? AND docno = ?";
                        $arrParam3 = array(
                            $dtax->nilai_vch,
                            $trcd,
                            $dtax->docno
                        );
                        $db_qryx->query($upd, $arrParam3);
                        //echo $upd;
                    }
                    $nilai_vch += $dtax->nilai_vch;
                }

                $sisa_cash = $total_dp_prd - $nilai_vch;

                $saf = "SELECT trcd FROM sc_newtrp WHERE trcd = ? AND paytype = '01'";
                $arrChs = array(
                    $trcd
                );
                $resCash = $db_qryx->query($saf, $arrChs);
                $hasilCsh = $resCash->result();
                if($hasilCsh == null) {
                    //echo "s";
                    $insDetTrf = "insert into sc_newtrp (trcd,paytype, payamt,PT_SVRID, voucher, vchtype)
                        VALUES (?, ?, ?, ?, ?, ?)";
                    $arrParCsh = array(
                        $trcd, '01', $sisa_cash, 'ID', '0', 'C'
                    );
                    $db_qryx->query($insDetTrf, $arrParCsh);
                } else {
                    $updCash = "UPDATE sc_newtrp 
                                SET payamt = ? 
                            WHERE trcd = ? AND paytype = '01'";
                    //echo $updCash;
                    $arrParam4 = array(
                        $sisa_cash,
                        $trcd
                    );
                    $db_qryx->query($updCash, $arrParam4);
                }

                
            }

            if ($db_qryx->trans_status() === false) {
                $db_qryx->trans_rollback();
                $return = array("response" => "false", "message" => "Data transaksi $trcd gagal dikoreksi..");
                return $return;
            } else {
                $db_qryx->trans_commit();
                $return = array("response" => "true", "message" => "Data transaksi $trcd berhasil dikoreksi..");
                return $return;
            }
        }
    }

    public function getListTipePromoVch() {
        $qry = "SELECT * FROM tipe_vch WHERE active_status = '1'";
        $hasil2 = $this->getRecordset($qry, null, $this->db2);
        return $hasil2;
    }

    public function getTipePromoVch($pref) {
        $qry = "SELECT * FROM tipe_vch WHERE prefix_vch = '$pref'";
        $hasil2 = $this->getRecordset($qry, null, $this->db2);


        if($hasil2 === null) {
            $response = jsonFalseResponse("Jenis voucher khusus ini tidak ada..");
            return $response;
        }

        if($hasil2[0]->active_status !== "1") {
            $response = jsonFalseResponse("Jenis voucher khusus sudah tidak berlaku/expire.");
            return $response;
        }

        return jsonTrueResponse($hasil2, "ok");

        
    }

    public function checkListProdukKhusus($tipe_promo, $prdcd) {
        $qry = " SELECT * 
                 FROM tipe_vch_product_allowed a 
                 WHERE a.prefix_vch = '$tipe_promo' AND a.prdcd = '$prdcd'";
        $hasil2 = $this->getRecordset($qry, null, $this->db2);
        if($hasil2 !== null) {
           return jsonTrueResponse($hasil2);
        }

        $res2 = $this->getTipePromoVch($tipe_promo);
        if($res2['response'] == "true") {
            $arr = $res2['arrayData'];
            $msg = $arr[0]->keterangan;
            return jsonFalseResponse($msg);
        }

        return $res2;
    }

		public function promoPreOrderPrem8V2($skema, $exclude_prd = null) {

			$listSkema = "SELECT DISTINCT(a.qty) as skema FROM db_ecommerce.dbo.promo_premium8 a WHERE qty <= $skema";
			//echo $listSkema;
			$res = $this->getRecordset($listSkema, null, $this->db2);
			$arrPush = array();
			$i = 0;

			$where = "";
			if($exclude_prd !== null) {
					$where .= " AND a.free_kode_prd NOT IN ($exclude_prd)";
			}
			if($res === null) {
					return null;
			}
			foreach($res as $dta) {
					//array_push($arrPush, $arr);
					$skema_qty = $dta->skema;
					$qry = "SELECT a.free_kode_prd , a.qty as skema, a.free_nama_prd as prdnm , a.free_qty_prd
									FROM db_ecommerce.dbo.promo_premium8 a
									WHERE a.qty = $skema_qty AND a.status = '1' $where";
					//echo $qry;
					$res2 =  $this->getRecordset($qry, null, $this->db2);
					if($res2 !== null) {

							$arrPush[$i]['skema'] = $skema_qty;
							$arrPush[$i]['max_qty'] = floor($skema / $skema_qty);
							$arrPush[$i]['listPrd'] = array();
							foreach($res2 as $dataPrd) {
									$arrPrd = array(
											"prdcd" => $dataPrd->free_kode_prd,
											"prdnm" => $dataPrd->prdnm,
											"skema" => $dataPrd->skema,
											"max_qty" => $arrPush[$i]['max_qty']
									);
									array_push($arrPush[$i]['listPrd'], $arrPrd);
							}
							$i++;
					}

			}
			return $arrPush;
	}
}
