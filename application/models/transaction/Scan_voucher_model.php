<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scan_voucher_model extends MY_Model {
    public function __construct(){
        parent::__construct();
    }

    function show_deposit_list($stockist, $type) {
        $status = ($type == 'generated') ? 0 : 1;
        $qry = "SELECT a.no_trx, a.id, a.total_deposit, a.total_keluar, a.[status],
        a.loccd, a.createnm, c.fullnm as stokis,
        CONVERT(VARCHAR(10), a.createdt, 120) as createdt
                FROM deposit_H a
                LEFT JOIN mssc c
                ON a.loccd = c.loccd COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.createnm = '$stockist' AND a.status = '$status'
                ORDER BY a.createdt DESC";
        //echo $qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getDataDetail($no_po) {
        /* $result = array();
        $this->db->select('a.*, b.fullnm')
            ->from('deposit_D a')
            ->where('a.id_header', $no_po)
            ->join('msmemb b', 'a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS', 'left')
            ->order_by('a.voucher_scan', 'ASC');
        $q = $this->db->get();
        foreach ($q->result() as $row) {
            $result[] = $row;
        }
        return $result; */

        $qry = "SELECT a.*, b.fullnm FROM deposit_D a
                LEFT JOIN msmemb b
                ON a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.id_header = '$no_po'
                ORDER BY a.voucher_scan ASC";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function getDataEdit($id) {
        /* $this->db = $this->db2;
        $this->db->select('a.*, b.fullnm');
        $this->db->where('a.id', $id);
        $this->db->join('msmemb b', 'a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS', 'left');
        return $this->db->get('deposit_H a'); */
        /* $qry = "SELECT a.*, b.fullnm 
                FROM deposit_H a
                LEFT JOIN msmemb b
                ON a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.id = '$id'"; */

        $qry = "SELECT a.* 
                FROM deposit_H a
                WHERE a.id = '$id'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function get_stockist_info($tipe, $stockistcode) {
        // $this->db = $this->load->database($this->db2, true);
        if ($tipe == 'sub') {
            $qry = "SELECT a.loccd,a.fullnm,a.pricecodePvr as pricecode,a.sfno,b.fullnm as uplinesub
                FROM mssc a,mssc b
                WHERE a.loccd = '" . $stockistcode . "'
                and a.sfno = b.loccd COLLATE SQL_Latin1_General_CP1_CS_AS";
            return $this->getRecordset($qry, NULL, $this->db2);
        }
    }

    function get_current_period() {
        $qry = "SELECT a.currperiodSCO as lastperiod,
                DATEADD(month, 1, a.currperiodSCO) as nextperiod
                from klink_mlm2010.dbo.syspref a";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function show_list_TTP($stk) {
        $this->db = $this->load->database($this->db2, true);
        $qry = "SELECT a.trcd, a.orderno, a.dfno, a.trtype, a.bnsperiod, a.tdp, a.tbv, a.batchno, a.createdt,
                    CONVERT(CHAR(10), a.bnsperiod, 120) as bnsperiod,
                    c.fullnm as member, a.trcd as transaksi, d.payamt as cash, e.payamt as voucher,
                    b.total_deposit, b.total_keluar
                FROM sc_newtrh a
                LEFT JOIN deposit_H b on a.id_deposit=b.id
                LEFT JOIN msmemb c on a.dfno=c.dfno
                LEFT JOIN sc_newtrp d on a.trcd = d.trcd AND d.paytype = '01'
                LEFT JOIN sc_newtrp e on a.trcd = e.trcd AND e.paytype != '01'
                WHERE b.id = '$stk'
                ORDER BY a.trcd DESC";
        $query = $this->db->query($qry);

        $total_cash = 0;
        $total_vch = 0;
        $total_belanja = 0;
        if ($query->num_rows() > 0) {
            $hasil = $query->result();
            foreach ($hasil as $data) {
                $total_belanja += (float) $data->tdp;
                $total_cash += (float) $data->cash;
                $total_vch += (float) $data->voucher;
                $nilai[] = $data;
            }
        } else {
            $nilai = null;
        }

       /*  $depositVch = $this->hitungTotalVchCash($stk);
        $deposit_in = $depositVch[0]->jumlah_deposit; */

        $stt_balance = "0";
        $kode = "kosong";
        $stt_balance_msg = "Deposit Voucher balance";
        if($nilai !== null) {
            $cashPlusVch = $total_cash + $total_vch;
            if($nilai[0]->total_keluar != $total_vch) {
                $stt_balance = "1";
                $stt_balance_msg = "Deposit Voucher tidak balance";
                $kode = "x";
            } else if($nilai[0]->total_keluar > $nilai[0]->total_deposit) {
                $nilai_deposit = $nilai[0]->total_deposit;
                $stt_balance = "1";
                $stt_balance_msg = "Deposit Voucher tidak balance, total nilai deposit : $nilai_deposit, penggunaan voucher cash : $total_vch";
                $kode = "y";
            }  else if($total_belanja != $cashPlusVch) {
                $stt_balance = "1";
                $stt_balance_msg = "Transaksi tidak balance, total nilai pembelanjaan : $total_belanja, penggunaan voucher cash dan cash : $cashPlusVch";
                $kode = "z";
            }

            $arrTotal = array(
                "arrayData" => $nilai,
                "total_cash" => $total_cash,
                "total_vch" => $total_vch,
                "total_belanja" => $total_belanja,
                "total_deposit_in" => $nilai[0]->total_deposit,
                "total_deposit_out" => $nilai[0]->total_keluar,
                "stt_balance" => $stt_balance,
                "stt_balance_msg" => $stt_balance_msg,
                "kode" => $kode
            );
        } else {
            $arrTotal = array(
                "arrayData" => $nilai,
                "total_cash" => $total_cash,
                "total_vch" => $total_vch,
                "total_belanja" => $total_belanja,
                "total_deposit_in" => 0,
                "total_deposit_out" => 0,
                "stt_balance" => $stt_balance,
                "stt_balance_msg" => $stt_balance_msg,
            );    
        }

        
        return $arrTotal;
    }

    function getVch($vch, $idmemb) {
        $kategori = strtolower($vch[0]);
        $by = 'VoucherKey';
        if ($kategori == 'p') {
            $by = 'VoucherNo';
        }
        /* $this->db->select('a.*, b.fullnm, b.dfno,DATEADD(MONTH, DATEDIFF(MONTH, 0,  GETDATE() ), 0)   as XD, c.fullnm as stokis, d.fullnm as stokis2,DATEADD(MONTH, DATEDIFF(MONTH, 0, a.ExpireDate ), 0) as YEY ');
        $this->db->where($by, $vch);
        $this->db->where('a.DistributorCode', $idmemb);
        $this->db->join('msmemb b', 'a.DistributorCode = b.dfno', 'left');
        $this->db->join('mssc c', 'a.loccd = c.loccd collate SQL_Latin1_General_CP1_CI_AI', 'left');
        $this->db->join('mssc d', 'a.updatenm = d.loccd collate SQL_Latin1_General_CP1_CI_AI', 'left'); */

        $qry = "SELECT a.*, b.fullnm, b.dfno,DATEADD(MONTH, DATEDIFF(MONTH, 0,  GETDATE() ), 0)   as XD, c.fullnm as stokis, d.fullnm as stokis2,DATEADD(MONTH, DATEDIFF(MONTH, 0, a.ExpireDate ), 0) as YEY
                FROM tcvoucher a
                LEFT JOIN msmemb b
                ON a.DistributorCode = b.dfno
                LEFT JOIN mssc c
                ON a.loccd = c.loccd collate SQL_Latin1_General_CP1_CI_AI
                LEFT JOIN mssc d
                ON a.updatenm = d.loccd collate SQL_Latin1_General_CP1_CI_AI
                WHERE a.DistributorCode = '$idmemb' AND a.$by = '$vch'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function saveScan($id) {
        $qryTrx = $this->load->database($this->db2, true);
        $kategori = strtolower($id[0]);
        $by = 'VoucherKey';
        if ($kategori == 'p') {
            $by = 'VoucherNo';
        }
        $qryTrx->trans_begin();

        $qryTrx->where($by, $id);
        $qryTrx->set('updatedt', date("Y-m-d h:i:s"));
        $qryTrx->set('claim_date', date("Y-m-d h:i:s"));
        $qryTrx->set('loccd', $this->stockist);
        $qryTrx->set('claimstatus', '1');
        $qryTrx->set('status', '1');
        $qryTrx->update('tcvoucher');

        // $qryTrx->trans_complete();

        if ($qryTrx->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $qryTrx->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, delete the data from the database
            $qryTrx->trans_commit();
            return TRUE;
        }
    }

    function CekVoucherTrue($data) {
        //$this->db = $this->load->database($this->db2, true);
        $this->db->select('VoucherKey, DistributorCode');
        $this->db->where('VoucherKey', $data);
        $this->db->where('claimstatus', '1');
        $query = $this->db->get('klink_mlm2010.dbo.tcvoucher a');
        $rowcount = $query->num_rows();

        if ($rowcount > 0) {
            return false;
        } else {
            return true;
        }    
    }

    function checkValidVchCash($data, $idmemb) {
        $jum = count($data);
        $checkVchDouble = $this->hitungDoubleNilai($data);
        if($checkVchDouble['response'] == "false") {
            return $checkVchDouble;
        }
        
        for ( $i=0; $i < $jum; $i++ ) {
            $hasil = $this->checkValidVchCashSatuan($data[$i], $idmemb[$i]);
            if($hasil['response'] == "false") {
                return $hasil;
            }
        }    

        return jsonTrueResponse(null, "OK");
    }

    function hitungDoubleNilai($arr) {
        $hasil = array_count_values($arr);
        foreach($hasil as $dta => $val) {
            if($val > 1) {
                return jsonFalseResponse("Voucher Cash $dta ada $val kali..");
            }
        }
        return jsonTrueResponse(null, "OK");

    }

    function checkVchCashDeposit($novch, $idmember, $iddeposit) {
        $qry = "SELECT a.id_header, a.voucher_scan, a.dfno, a.no_trx
                FROM klink_mlm2010.dbo.deposit_D a
                WHERE a.id_header='$iddeposit' AND a.voucher_scan='$novch' AND a.dfno='$idmember'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function hapusVchCash($novch, $idmember, $iddeposit) {
        $check = $this->checkVchCashDeposit($novch, $idmember, $iddeposit);
        if($check == null) {
            return jsonFalseResponse("Voucher $novch dengan ID member $idmember tidak ada di deposit ini..");
        }

        $db_qryx = $this->load->database('klink_mlm2010', true);
        $db_qryx->trans_begin();

        $delete = "DELETE FROM klink_mlm2010.dbo.deposit_D 
                   WHERE id_header='$iddeposit' AND voucher_scan='$novch' AND dfno='$idmember'";
        //echo $delete."<br />";
        $db_qryx->query($delete);

        $hitungUlang = $this->hitungTotalVchCash($iddeposit);
        //print_r($hitungUlang);
        $jumDeposit = $hitungUlang[0]->jumlah_deposit;

        $updDeposit = "UPDATE a 
                          SET a.total_deposit = '$jumDeposit'
                       FROM klink_mlm2010.dbo.deposit_H a
                       WHERE a.id = '$iddeposit'";
        //echo $updDeposit."<br />";
        $db_qryx->query($updDeposit);

        $vchDeposit = $check[0]->no_trx;
        $updTcVch = "UPDATE a
                        SET a.status = '0', a.claimstatus = '0', a.loccd = '', a.claim_date = '',
                        a.remarks = 'PREV VC $vchDeposit'
                     FROM klink_mlm2010.dbo.tcvoucher a 
                     WHERE a.voucherkey = '$novch' AND a.DistributorCode = '$idmember'";
        //echo $updTcVch."<br />";
        $db_qryx->query($updTcVch);

        if($this->stockist == "BID06") {
            echo $delete."<br />";
            echo $updDeposit."<br />";
            echo $updTcVch."<br />";
        }

        
        if ($db_qryx->trans_status() === FALSE) {
            $db_qryx->trans_rollback();
            $return = array("response" => "false", "message" => "Voucher $novch gagal di hapus..");
            return $return; 
        } else {
            $db_qryx->trans_commit();
            $arrx = array(
                "jumlah_deposit" => $jumDeposit,
                "id_header" => $novch,
                "vc_deposit" => $vchDeposit
            );
            $return = array("response" => "true", "arrayData" => $arrx, "message" => "Voucher $novch berhasil di hapus..");
            return $return; 
        }    

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
        //print_r($arrparam);
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

    function checkValidVchCashSatuan($vch, $idmember) {
        $qry = "SELECT a.DistributorCode, b.fullnm, a.VoucherKey, a.VoucherAmt, 
                    a.[status], a.claimstatus, 
                    a.loccd, CONVERT(VARCHAR(10), a.claim_date, 120) as claim_date,
                    a.ExpireDate, 
                    CASE WHEN a.ExpireDate <= GETDATE() THEN '1' ELSE '0'
                    END AS status_exp, a.status_open, a.openstatus_dt
                FROM tcvoucher a
                LEFT OUTER JOIN msmemb b ON (a.DistributorCode = b.dfno)
                WHERE a.VoucherKey = '$vch'";
            $row = $this->getRecordset($qry, NULL, $this->db2);
            if($row == null) {
                return jsonFalseResponse("Voucher $vch invalid / salah..");
            }

            if($row[0]->DistributorCode != $idmember) {
                return jsonFalseResponse("Voucher $vch bukan milik member : ".$idmember);
            }

            if($row[0]->status_exp == "1") {
                return jsonFalseResponse("Voucher $vch sudah expire sejak tanggal : ".$row[0]->ExpireDate);
            }

            if ($row[0]->status_open == "0") {
                $response = array("response" => "false", "arrayData" => $res,"message" => "Voucher ".$vch." harus di aktifkan dulu melalui k-net di menu Sales & Bonus -> Aktivasi Voucher Cash/Product");
                return $response;
            }

            if($row[0]->claimstatus == "1") {
                $idstk = $row[0]->loccd; 
                $claim_date = $row[0]->claim_date;
                return jsonFalseResponse("Voucher $vch sudah di klaim di $idstk pada tanggal : $claim_date");
            }

            if($row[0]->VoucherAmt <= 0) {
                return jsonFalseResponse("Voucher $vch bernilai 0");
            }

            return jsonTrueResponse($row, "OK");
    }

    function addHeader($data) {
        $this->db = $this->load->database($this->db2, true);
        $this->db->trans_begin();
        $this->db->insert('deposit_H', $data);
        // $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, delete the data from the database
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function updateHeader($id, $data) {
        $qryTrx = $this->load->database($this->db2, true);
        $qryTrx->trans_begin();

        $qryTrx->where('id', $id);
        $qryTrx->update('deposit_H', $data);
        // $qryTrx->trans_complete();

        if ($qryTrx->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, delete the data from the database
            $qryTrx->trans_commit();
            return TRUE;
        }
    }

    function addDetail($data, $x) {
        $this->db = $this->load->database($this->db2, true);
        $this->saveScan($x);
        $this->db->trans_begin();
        $this->db->insert('deposit_D', $data);
        // $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, delete the data from the database
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function getDataDetailProduk($no_po) {
        $qry = "SELECT a.*, b.prdnm FROM sc_newtrd a
                LEFT JOIN msprd b
                ON a.prdcd = b.prdcd COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.trcd = '$no_po'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    public function getDataDetailPayment($no_po) {
        $qry = "SELECT * FROM sc_newtrp a WHERE a.trcd = '$no_po'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function get_list_payment() {
        $qry = "SELECT id, description, chargeamt,vchtype FROM paytype
                WHERE activestatus = '1' and description in('Cash','Product Voucher')"; //,'Cash Voucher')";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function getDataEditTTP($id) {
        /* $this->db->select('a.*, b.fullnm, c.fullnm as nama_penuh,   CONVERT(CHAR(4), a.bnsperiod, 100) + CONVERT(CHAR(4), a.bnsperiod, 120) as bns ');
        $this->db->where('a.trcd', $id);
        $this->db->join('msmemb b', 'a.dfno = b.dfno', 'left');
        $this->db->join('mssc c', 'a.sc_dfno = c.loccd', 'left');
        return $this->db->get('sc_newtrh a');
 */
        /* $qry = "SELECT a.*, b.fullnm, c.fullnm AS nama_penuh,   
                   CONVERT(CHAR(4), a.bnsperiod, 100) + CONVERT(CHAR(4), a.bnsperiod, 120) AS bns
                FROM sc_newtrh a
                LEFT JOIN msmemb b ON a.dfno = b.dfno
                LEFT JOIN mssc c ON a.sc_dfno = c.loccd
                WHERE a.trcd = '$id'"; */
        $qry = "SELECT a.trcd, a.dfno, b.fullnm, a.sc_dfno, a.sc_co, a.loccd, a.createnm, 
                    c.fullnm AS nama_penuh,   
                CONVERT(CHAR(10), a.bnsperiod, 121) AS bns,
                CONVERT(CHAR(10), a.createdt, 121) AS createdt,
                a.pricecode, a.orderno, a.remarks, a.tdp, CONVERT(CHAR(10), a.trdt, 121) AS trdt
                FROM sc_newtrh a
                LEFT JOIN msmemb b ON a.dfno = b.dfno
                LEFT JOIN mssc c ON a.sc_dfno = c.loccd
                WHERE a.trcd = '$id'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function sumTrxByDepositId($id) {
        $qry = "SELECT count(a.trcd) as jum FROM sc_newtrh a WHERE a.id_deposit = '$id'";
        $hasil = $this->getRecordset($qry, NULL, $this->db2);
        return $hasil[0]->jum;
    }

    function reactivateVoucherCashInDeposit($id) {
        $qry = "SELECT kategori, dfno, no_trx, voucher_scan 
                FROM klink_mlm2010.dbo.deposit_D 
                WHERE id_header = '$id'";
        $query2 = $this->db->query($qry);
        $result = $query2->result();
        if($result != null) {
            foreach ($result as $data2) {
                $updateVch = "UPDATE klink_mlm2010.dbo.tcvoucher 
                                SET status = 0, claimstatus = 0, remarks = 'PREV $data2->no_trx'
                              WHERE voucherkey = '$data2->voucher_scan' AND DistributorCode = '$data2->dfno'";
                $this->db->query($updateVch);
            }
        }
    }

    function deleteDepositVoucher($id) {
        $del = "DELETE FROM klink_mlm2010.dbo.deposit_H WHERE id='$id'";
        $this->db->query($del);
        $del = "DELETE FROM klink_mlm2010.dbo.deposit_D WHERE id_header='$id'";
        $this->db->query($del);
    }

    function HapusDeposit($id) {
        
        $rowcount = $this->sumTrxByDepositId($id);
        if ($rowcount > 0) {
            return jsonFalseResponse("Sudah ada $rowcount TTP dalam deposit voucher ini..");
        } else {
            $this->reactivateVoucherCashInDeposit($id);
            $this->deleteDepositVoucher($id);
            return jsonTrueResponse(null, "Deposit Voucher berhasil dihapus..");
        }
    }

    function getsisaBARU($stk) {
        /* $qry = "SELECT a.id_header, a.saldo, sum(c.payamt) as payamt FROM
            (SELECT id_header, sum (nominal) as saldo
            FROM deposit_D
            WHERE id_header ='$stk'
            GROUP BY id_header) a
            LEFT JOIN sc_newtrh b
            ON a.id_header=b.id_deposit
            LEFT JOIN sc_newtrp c
            on b.trcd=c.trcd AND c.paytype='08'
            GROUP BY a.id_header, a.saldo"; */

        $qry = "SELECT a.id_header, a.no_trx, a.saldo, ISNULL(sum(c.payamt), 0) as payamt  
                FROM
                (
                    SELECT x.id_header, x.no_trx, sum (x.nominal) as saldo
                    FROM klink_mlm2010.dbo.deposit_D x
                    WHERE id_header = '$stk'
                    GROUP BY x.id_header, x.no_trx
                ) a
                LEFT JOIN klink_mlm2010.dbo.sc_newtrh b ON a.id_header=b.id_deposit
                LEFT JOIN klink_mlm2010.dbo.sc_newtrp c ON b.trcd=c.trcd AND c.paytype='08'
                GROUP BY a.id_header, a.no_trx, a.saldo";
        $query = $this->db->query($qry);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $data) {
                $nilai[] = $data;
            }
        } else {
            $nilai = null;
        }
        return $nilai;
    }

    function updateSisaSaldo($id, $pengurangan) {
        $qryTrx = $this->load->database($this->db2, true);
        $upd = "UPDATE deposit_H 
                SET total_keluar = ISNULL(total_keluar, 0) + $pengurangan WHERE id = '$id'";
        $query = $qryTrx->query($upd);
        return $query;
    }

    function updateSaldo($data1, $data2, $data3) {
        $this->db->trans_begin();
        $this->db->set('total_deposit', $data2);
        $this->db->set('total_keluar', $data3);
        $this->db->where('id', $data1);
        $this->db->update('deposit_H');

        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            //if everything went right, delete the data from the database
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function check_double_orderno1($orderno) {
        $qry = "SELECT orderno, trcd FROM sc_newtrh
                WHERE orderno = '" . $orderno . "'";
        //echo $qry;
        return $this->get_data_result2($qry);
    }

    function cek_seQ($tipe_pay) {
        $this->db = $this->load->database('alternate', true);
        $y1 = date("y");
        $m = date("m");

        $this->db->trans_begin();

        //if(in_array('p',$tipe_pay))
        if ($tipe_pay == 'pv') {
            $tbl = "SEQ_PV" . "$y1" . "$m";
        } elseif ($tipe_pay == 'cv') {
            $tbl = "SEQ_CV" . "$y1" . "$m";
        } elseif ($tipe_pay == 'xhd') {
            $tbl = "SEQ_ID" . "$y1" . "$m";
        } else {
            return FALSE;
        }

        $cek = "SELECT * FROM $tbl";

        $query = $this->db->query($cek);
        if ($query->num_rows < 1) {
            $input = "INSERT INTO $tbl (SeqVal) VALUES('a')";
            $query = $this->db->query($input);
        } else {
            $input = "INSERT INTO $tbl (SeqVal) VALUES('a')";
            $query = $this->db->query($input);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $query;
    }

    function get_idno($tipe_pay) {
        $this->db = $this->load->database('alternate', true);
        $y1 = date("y");
        $m = date("m");

        $this->db->trans_begin();

        if ($tipe_pay == 'pv') {
            $tbl = "SEQ_PV" . "$y1" . "$m";
        } elseif ($tipe_pay == 'cv') {
            $tbl = "SEQ_CV" . "$y1" . "$m";
        } else {
            $tbl = "SEQ_ID" . "$y1" . "$m";
        }

        $qry = "SELECT * FROM $tbl WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";

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

        if ($tipe_pay == 'pv') {
            $y = strval("PV" . $prefix . $next_seq);
        } elseif ($tipe_pay == 'cv') {
            $y = strval("CV" . $prefix . $next_seq);
        } else {
            $y = strval("ID" . $prefix . $next_seq);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $y;
    }

        function save_input_sales_sub($idnoo, $username) {
        $this->db = $this->load->database('klink_mlm2010', true);

        $createdt = date('Y-m-d H:i:s');
        $substockistcode = $this->input->post('substockistcode');
        $substkname = $this->input->post('substkname');
        $trxdate = $this->input->post('trxdate');
        $uplinesub = $this->input->post('uplinesub');
        $pricecode = $this->input->post('pricecode');
        $uplinesubnm = $this->input->post('uplinesubnm');
        $bnsperiod = $this->input->post('bnsperiod');
        $orderno = $this->input->post('orderno');
        $remark = $this->input->post('remark');
        $distributorcode = $this->input->post('distributorcode');
        $tipe_pay = $this->input->post('pay_type');
        $jenis = $this->input->post('jenis');
        $vchnoo = $this->input->post('vchnoo1');

        $search_for = "'";
        $replace = "`";

        $substkname = str_replace($search_for, $replace, $substkname);
        $uplinesubnm = str_replace($search_for, $replace, $uplinesubnm);
        $remark = str_replace($search_for, $replace, $remark);

        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month'] . "/" . "1" . "/" . $data['year'];

        $prdcdcode = $this->input->post('productcode');
        $productname = $this->input->post('productname');
        $qty = $this->input->post('qty');
        $dp = $this->input->post('dp_real');
        $total_bv = 0;
        $bv = 0;
        $totalDp = $this->input->post('totalDp_real');
        $total_net_real = $this->input->post('total_net_real');
        $paynominal = $this->input->post('paynominal');
        $refno = $this->input->post('refno');
        $change_real = $this->input->post('change_real');
        $change = $this->input->post('change');

        $aa = substr($idnoo, 2, 10);
        $this->db->trans_begin();

        $jx = 0;
        $jml_paytypex = count($tipe_pay);
        if ($jx > 0) { // jika $jx > 0, artinya salah satu PVR sudah pernah diinput, maka return 0 = error
            return 0;
        } else { // jika $jx = 0, artinya semua PVR belum pernah diinput, maka lanjutkan insert
            $checkSC = "SELECT loccd,sctype,fullnm FROM mssc WHERE loccd = '$substockistcode'";
            $sctypes = $this->db->query($checkSC);
            if ($sctypes->num_rows() > 0) {
                $row = $sctypes->row();
                $sctypee = $row->sctype;
                $aaa = strval("PV" . $aa);
                $bv = 0;
                $total_bv = 0;
                $insHead = "INSERT INTO sc_newtrh
                  (trcd, trtype, trdt, dfno, loccd, tdp, taxrate, taxamt, discamt, shcharge,
                  othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                  paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                  updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                  orderno,type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                  syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                  sc_co,bnsperiod,remarks,othdisc,flag_batch,batchstatus,flag_recover,
                  system,ttptype,entrytype,flag_show,flag_approval)
                  VALUES
                  ('" . $idnoo . "','SB1','" . $createdt . "','" . $distributorcode . "',
                  '" . $substockistcode . "'," . $totalDp . ",10,0,0,0,0,0,0,
                    0,0," . $totalDp . ",'WH001','B001','" . $pricecode . "',
                  '01','01','03'," . $totalDp . ",0,0," . $totalDp . ",'" . $username . "','" . $username . "',
                  '0',0,0,0,0,'0','" . $createdt . "','" . $orderno . "','0',0,0,'$sctypee','1','0','0','0',
                  0,'0','0','0','1','ID','" . $substockistcode . "','" . $substockistcode . "','" . $bonusperiod . "','" . $remark . "',
                  0,'0','0','0','0','SC',4,'0','0')";
                $query1 = $this->db->query($insHead);
                $jml_trx = count($prdcdcode);
                for ($i = 0; $i < $jml_trx; $i++) {
                    if ($prdcdcode[$i] != "" && $dp[$i] != "" && $qty[$i] != "") {
                        $productcode = strtoupper($prdcdcode[$i]);
                        $insDet = "INSERT INTO sc_newtrd
                          (trcd,prdcd,qtyord,qtyship,qtyremain,dp,pv,bv,taxrate,sp,sb,scdisc,seqno,scdiscamt,syn2web,qty_used,qty_avail,
                          PT_SVRID,pricecode) VALUES
                          ('" . $idnoo . "', '" . $productcode . "'," . $qty[$i] . ",0,0," . $dp[$i] . ",0,0,0,0,0,0,0,0,'0',0,0,'ID','$pricecode')";
                        $query2 = $this->db->query($insDet);
                    }
                }
                $j = 0;
                $jml_paytype = count($tipe_pay);
                for ($s = 0; $s < $jml_paytype; $s++) {
                    $j = $j + 1;
                    if ($tipe_pay[$s] == '01') {
                        $reffno = '';
                        $insDetTrf = "INSERT INTO sc_newtrp (trcd,seqno,paytype,docno,payamt,deposit,notes,trcd2,PT_SVRID,voucher)
                          VALUES ('" . $idnoo . "'," . $j . ",'" . $tipe_pay[$s] . "','" . $reffno . "'," . $paynominal[$s] . ",0,'" . $remark . "','0','ID','0')";
                        $query3 = $this->db->query($insDetTrf);
                    } else {
                        $amtVch = $this->cekAmtVch($refno[$s]);
                        $insDetTrf = "INSERT INTO sc_newtrp (trcd,seqno,paytype,docno,payamt,deposit,notes,trcd2,PT_SVRID,voucher)
                        VALUES ('" . $idnoo . "'," . $j . ",'" . $tipe_pay[$s] . "','" . $refno[$s] . "','0',0,'" . $remark . "','0','ID','1')";
                         $query3 = $this->db->query($insDetTrf);

                        $updtpvr = "UPDATE tcvoucher SET claimstatus = '1', STATUS = '1', updatedt = '" . $createdt . "',claim_date ='" . $createdt . "',loccd ='" . $username . "'
                        WHERE VoucherNo = '" . $refno[$s] . "'";
                         $querypvr = $this->db->query($updtpvr);
                    }
                }
            }
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            if (!$query1) {
                return 0;
            } else {
                if (!$query2) {
                    return 0;
                } else {
                    if (!$query3) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
            }
        }
    }

    function hapusTTPvchDeposit($id) {
        $qry = "SELECT a.trcd, a.batchno, a.id_deposit, a.no_deposit, b.no_trx, b.id, b.[status]
                FROM klink_mlm2010.dbo.sc_newtrh a
                LEFT OUTER JOIN klink_mlm2010.dbo.deposit_H b ON (a.id_deposit = b.id)
                WHERE a.trcd = '$id' ";
        $query2 = $this->db->query($qry);
        $hasil = $query2->result();

        $nodeposit = "";

        if($hasil == null) {
            $arr = jsonFalseResponse("Transaksi $id tidak ada di dalam sistem / sudah dihapus..");
            return;
        }

        if($hasil != null && ($hasil[0]->id_deposit == null || $hasil[0]->id_deposit == "" || $hasil[0]->no_deposit == null || $hasil[0]->no_deposit == "")) {
            $arr = jsonFalseResponse("Transaksi $id bukan transaksi voucher deposit..");
            return;
        }

        $ssrno = $hasil[0]->batchno;
        if($hasil != null && $hasil[0]->batchno != null && $hasil[0]->batchno != "") {
            
            $arr = jsonFalseResponse("Transaksi $id sudah digenerate dengan No SSR : $ssrno..");
            return;
        }

        $nodeposit = $hasil[0]->no_deposit;
        if($hasil != null && $hasil[0]->status == 0) {
            
            $arr = jsonFalseResponse("Voucher Deposit $nodeposit status nya sudah di generate..");
            return;
        }

        $id_deposit = $hasil[0]->id_deposit;

        $db_qryx = $this->load->database('klink_mlm2010', true);

        $db_qryx->trans_begin();

        $del = "DELETE FROM sc_newtrh WHERE trcd = '$id'";
        $db_qryx->query($del);
        
        $del2 = "DELETE FROM sc_newtrd WHERE trcd = '$id'";
        $db_qryx->query($del2);
        
        $del3 = "DELETE FROM sc_newtrp WHERE trcd = '$id'";
        $db_qryx->query($del3);

        $checkAmt = "SELECT a.id, a.no_trx, a.total_deposit, a.total_keluar, SUM(c.payamt) as jum_vch
                    FROM deposit_H a
                    LEFT OUTER JOIN sc_newtrh b ON (a.no_trx = b.no_deposit)
                    LEFT OUTER JOIN sc_newtrp c ON (b.trcd = c.trcd AND c.paytype != '01')
                    WHERE a.id = '$id_deposit' and a.no_trx = '$nodeposit' 
                    GROUP BY a.id, a.no_trx, a.total_deposit, a.total_keluar";
         //echo $checkAmt;
         $resCheckAmt = $db_qryx->query($checkAmt);
        $hasilCheckAmt = $resCheckAmt->result();
        //print_r($hasilCheckAmt);
        if($hasilCheckAmt !== null) {
            $deposit_out = $hasilCheckAmt[0]->jum_vch;
            //jika di sc_newtrp 
            $sisa_deposit = 0;
            if($deposit_out != null) {
                $sisa_deposit = $deposit_out;
            }
            $upd = "UPDATE a
                    SET a.total_keluar = $sisa_deposit
                    FROM deposit_H a
                    WHERE a.id = '$id_deposit' and a.no_trx = '$nodeposit'";
            $resUpd = $db_qryx->query($upd);
        }

        if ($db_qryx->trans_status() === FALSE) {
            $db_qryx->trans_rollback();
            $return = array("response" => "false", "message" => "Transaksi $id gagal di hapus..");
            return $return; 
        } else {
            $db_qryx->trans_commit();
            $return = array("response" => "true", "message" => "Transaksi $id berhasil di hapus..");
            return $return; 
        }
    }

    function recalculateDeposit($id) {
        $qryTrx = $this->load->database('klink_mlm2010', true);

        //hitung ulang total nilai voucher yg ada di deposit_D
        $qry1 = "SELECT ISNULL(SUM(a.nominal), 0) as jumlah_deposit, 
                   a.no_trx, a.id_header
                FROM klink_mlm2010.dbo.deposit_D a
                WHERE a.id_header = '$id'
                GROUP BY a.no_trx, a.id_header";
        $resQry1 = $qryTrx->query($qry1);
        $hasil = $resQry1->result();

        $qryTrx->trans_begin();

        $nominalDepositBaru = 0;
        if($hasil != null) {
            $nominalDepositBaru = $hasil[0]->jumlah_deposit; 
        }

        $qryTrx->set('total_deposit', $nominalDepositBaru);
        $qryTrx->where('id', $id);
        $qryTrx->update('klink_mlm2010.dbo.deposit_H');
        
         $qry2 = "SELECT a.id, a.no_trx, b.trcd, a.total_deposit, 
                    tdp, ndp, totpay, a.total_keluar, 
                    ISNULL(SUM(c.payamt), 0) as jml_vch_cash, 
                    ISNULL(SUM(d.payamt), 0) as jml_cash
                  FROM klink_mlm2010.dbo.deposit_H a
                  LEFT OUTER JOIN sc_newtrh b ON (a.id = b.id_deposit)
                  LEFT OUTER JOIN sc_newtrp c ON (b.trcd = c.trcd AND c.paytype = '08')
                  LEFT OUTER JOIN sc_newtrp d ON (b.trcd = d.trcd AND d.paytype = '01')
                  WHERE a.id = '$id'
                  GROUP BY a.id, a.no_trx, b.trcd, a.total_deposit, 
                    tdp, ndp, totpay, a.total_keluar
                  ORDER by trcd";
         //echo $qry2;
         $resQry2 = $qryTrx->query($qry2);
         $hasil2 = $resQry2->result();

         if($hasil2 != null) {

            /* $totalJumlahScanVch = $nominalDepositBaru;
            //$no_deposit = $hasil[0]->no_trx;
            if($totalJumlahScanVch == 0) {
                $return = jsonFalseResponse("jumlah nilai total voucher cash di deposit : $totalJumlahScanVch");
                return $return;
            } */

            //cek per TTP, nilai transaksi dan total bayar harus sama
            $no_trx_deposit = $hasil2[0]->no_trx;
            $total_deposit = $hasil2[0]->total_deposit;
            $sisa_deposit = $total_deposit;
            $tidak_selisih = 0;
            $jum_trx = 0;
            $tot_vch_usage = 0;
            foreach($hasil2 as $dtax) {
                $jum_bayar = $dtax->jml_vch_cash + $dtax->jml_cash; 
                //$sisa_deposit = $sisa_deposit - $dtax->jml_vch_cash;
                if($dtax->tdp != $jum_bayar && $sisa_deposit >=  $dtax->tdp) {
                    /* echo "Total TTP       : ".$dtax->tdp."<br />";
                    echo "Penggunaan vch  : ".$dtax->tdp."<br />";
                    echo "<pre>";
                    print_r($ins);
                    echo "</pre>"; */
                    $qryTrx->where('trcd', $dtax->trcd);
                    $qryTrx->delete('klink_mlm2010.dbo.sc_newtrp');

                    $ins['trcd'] = $dtax->trcd;
                    $ins['seqno'] = 1;
                    $ins['paytype'] = "08";
                    $ins['docno'] = $dtax->no_trx;
                    $ins['payamt'] = $dtax->tdp;
                    $ins['voucher'] = "1";
                    $ins['vchtype'] = "C";
                    $qryTrx->insert("klink_mlm2010.dbo.sc_newtrp", $ins);
                    $tot_vch_usage += $dtax->tdp; 
                    $sisa_deposit = $sisa_deposit - $dtax->tdp;
                } else if($sisa_deposit <= $dtax->tdp) {
                    $qryTrx->where('trcd', $dtax->trcd);
                    $qryTrx->delete('klink_mlm2010.dbo.sc_newtrp');

                    $ins['trcd'] = $dtax->trcd;
                    $ins['seqno'] = 1;
                    $ins['paytype'] = "08";
                    $ins['docno'] = $dtax->no_trx;
                    $ins['payamt'] = $sisa_deposit;
                    $ins['voucher'] = "1";
                    $ins['vchtype'] = "C";
                    $qryTrx->insert("klink_mlm2010.dbo.sc_newtrp", $ins);
                    $tot_vch_usage += $sisa_deposit; 

                    $sisa_cash = $dtax->tdp - $sisa_deposit;
                    if($sisa_cash > 0) {
                        $ins2['trcd'] = $dtax->trcd;
                        $ins2['seqno'] = 2;
                        $ins2['paytype'] = "01";
                        $ins2['docno'] = "/";
                        $ins2['payamt'] = $sisa_cash;
                        $ins2['voucher'] = "0";
                        //$ins2['vchtype'] = "C";
                        
                        $qryTrx->insert("klink_mlm2010.dbo.sc_newtrp", $ins2);
                    }
                    
                } else {
                    $tot_vch_usage += $dtax->tdp; 
                    $sisa_deposit = $sisa_deposit - $dtax->tdp;
                }
                
            }
        
        } else {
            $total_keluar_seharusnya = 0;
        }
        

         $total_keluar_seharusnya = $tot_vch_usage;
         $qryTrx->set('total_keluar', $total_keluar_seharusnya);
         $qryTrx->where('id', $id);
         $qryTrx->update('klink_mlm2010.dbo.deposit_H');

         if ($qryTrx->trans_status() === FALSE) {
            $qryTrx->trans_rollback();
            $return = array("response" => "false", "message" => "Deposit Voucher $no_trx_deposit gagal di recalculate");
            return $return; 
         } else {
            $qryTrx->trans_commit();
            $return = array("response" => "true", "message" => "Deposit Voucher $no_trx_deposit berhasil di recalculate");
            return $return; 
         }
    }

    function hitungTotalVchCash($id) {
        $qry1 = "SELECT ISNULL(SUM(a.nominal), 0) as jumlah_deposit, 
                   a.no_trx, a.id_header
                FROM klink_mlm2010.dbo.deposit_D a
                WHERE a.id_header = '$id'
                GROUP BY a.no_trx, a.id_header";
        return $this->getRecordset($qry1, NULL, $this->db2);
    }

    function listTtpDepositVch($id) {
        $qry2 = "SELECT a.id, a.no_trx, b.trcd, a.total_deposit, 
                    b.tdp, b.ndp, b.totpay, a.total_keluar
                FROM klink_mlm2010.dbo.deposit_H a
                LEFT OUTER JOIN sc_newtrh b ON (a.id = b.id_deposit)
                WHERE a.id = '$id'
                ORDER by b.tdp";
        return $this->getRecordset($qry2, NULL, $this->db2);
    }

    function sumAmountTtpDeposit($id) {
        $qry2 = "SELECT ISNULL(SUM(a.tdp), 0) as total_dp
                FROM sc_newtrh a
                WHERE a.id_deposit = '$id'";
        $hasil = $this->getRecordset($qry2, NULL, $this->db2);
        if($hasil == null) {
            return 0;
        } else {
            return $hasil[0]->total_dp;
        }
    }

    
    function koreksiDepositVoucher($id) {
        $qryTrx = $this->load->database('klink_mlm2010', true);

        //hitung ulang total nilai voucher yg ada di deposit_D
        $hasil = $this->hitungTotalVchCash($id);
        if($hasil == null) {
            $return = array("response" => "false", "message" => "Tidak ada voucher cash di deposit ini..");
            return $return; 
        }

        $qryTrx->trans_begin();

        $nominalDepositBaru = 0;
        if($hasil != null) {
            $nominalDepositBaru = $hasil[0]->jumlah_deposit; 
            $no_trx_deposit = $hasil[0]->no_trx;
        }

        $qryTrx->set('total_deposit', $nominalDepositBaru);
        $qryTrx->where('id', $id);
        $qryTrx->update('klink_mlm2010.dbo.deposit_H');

        $deposit_berjalan = $nominalDepositBaru;
        $total_trx = 0;
        $total_cash = 0;
        $total_deposit = 0;

        $hasilListTtp = $this->listTtpDepositVch($id);
        if($hasilListTtp != null) {
            if($hasilListTtp[0]->trcd !== null) {
                foreach($hasilListTtp as $dta) {
                    if($deposit_berjalan >= $dta->tdp) {
                        //echo "Trx : ".$dta->trcd.", Nilai Trx : ".$dta->tdp.", Deposit : ".$dta->tdp.", Cash : 0";
                        //hapus sc_newtrp dgn trcd ini
                        //insert baru dengan tipe vch cash no deposit, nilai = nilai_trx

                        $qryTrx->where('trcd', $dta->trcd);
                        $qryTrx->delete('klink_mlm2010.dbo.sc_newtrp');

                        $ins['trcd'] = $dta->trcd;
                        $ins['seqno'] = 1;
                        $ins['paytype'] = "08";
                        $ins['docno'] = $dta->no_trx;
                        $ins['payamt'] = $dta->tdp;
                        $ins['voucher'] = "1";
                        $ins['vchtype'] = "C";
                        $qryTrx->insert("klink_mlm2010.dbo.sc_newtrp", $ins);

                        $deposit_berjalan -= $dta->tdp;
                        $total_deposit += $dta->tdp;
                        $total_cash += 0;
                        //echo "<br />";
                    } else {
                        $cash = $dta->tdp - $deposit_berjalan;
                        //hapus sc_newtrp dgn trcd ini
                        //insert baru dengan tipe vch cash no deposit, nilai = $deposit_berjalan    
                        //insert baru dengan tipe cash, nilai = $cash    
                        //echo "Trx : ".$dta->trcd.", Nilai Trx : ".$dta->tdp.", Deposit : ".$deposit_berjalan.", Cash : ".$cash;

                        $qryTrx->where('trcd', $dta->trcd);
                        $qryTrx->delete('klink_mlm2010.dbo.sc_newtrp');

                        $ins['trcd'] = $dta->trcd;
                        $ins['seqno'] = 1;
                        $ins['paytype'] = "08";
                        $ins['docno'] = $dta->no_trx;
                        $ins['payamt'] = $deposit_berjalan;
                        $ins['voucher'] = "1";
                        $ins['vchtype'] = "C";
                        $qryTrx->insert("klink_mlm2010.dbo.sc_newtrp", $ins);

                        $ins2['trcd'] = $dta->trcd;
                        $ins2['seqno'] = 2;
                        $ins2['paytype'] = "01";
                        $ins2['docno'] = "/";
                        $ins2['payamt'] = $cash;
                        $ins2['voucher'] = "0";
                        //$ins2['vchtype'] = "C";
                        
                        $qryTrx->insert("klink_mlm2010.dbo.sc_newtrp", $ins2);

                        $total_deposit += $deposit_berjalan;
                        $total_cash += $cash;
                        $deposit_berjalan = 0;
                    }
                    $total_trx += $dta->tdp;
                }
            }
        } 

        /*  echo "Total Trx           : ".$total_trx."<br />";
        echo "Sisa deposit akhir  : ".$deposit_berjalan."<br />";
        echo "Total pakai deposit : ".$total_deposit."<br />";
        echo "Total Cash          : ".$total_cash."<br />"; */
        

         $total_keluar_seharusnya = $total_deposit;
         $qryTrx->set('total_keluar', $total_keluar_seharusnya);
         $qryTrx->where('id', $id);
         $qryTrx->update('klink_mlm2010.dbo.deposit_H');    

         if ($qryTrx->trans_status() === FALSE) {
            $qryTrx->trans_rollback();
            $return = array("response" => "false", "message" => "Deposit Voucher $no_trx_deposit gagal di recalculate");
            return $return; 
         } else {
            $qryTrx->trans_commit();
            $return = array("response" => "true", "message" => "Deposit Voucher $no_trx_deposit berhasil di recalculate");
            return $return; 
         }
    }
}
