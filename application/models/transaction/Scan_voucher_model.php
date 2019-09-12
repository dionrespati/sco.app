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
        $qry = "SELECT a.*, b.fullnm FROM deposit_H a
                LEFT JOIN msmemb b
                ON a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS
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
        $qry = "SELECT *,c.fullnm as member, a.trcd as transaksi, d.payamt as cash, e.payamt as voucher
                FROM
                sc_newtrh a
                LEFT JOIN
                deposit_H b
                on a.id_deposit=b.id
                LEFT JOIN
                msmemb c
                on a.dfno=c.dfno
                LEFT JOIN
                sc_newtrp d
                on a.trcd = d.trcd AND d.paytype = '01'
                LEFT JOIN
                sc_newtrp e
                on a.trcd = e.trcd AND e.paytype != '01'
                where b.id = '$stk'
                ORDER BY transaksi DESC";
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
        $this->db = $this->load->database($this->db2, true);
        $this->db->select('*');
        $this->db->where('VoucherKey', $data);
        $this->db->where('claimstatus', '1');
        $query = $this->db->get('tcvoucher a');
        $rowcount = $query->num_rows();

        if ($rowcount > 0) {
            return false;
        } else
            return true;
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
        $qry = "SELECT a.*, b.fullnm, c.fullnm AS nama_penuh,   CONVERT(CHAR(4), a.bnsperiod, 100) + CONVERT(CHAR(4), a.bnsperiod, 120) AS bns
                FROM sc_newtrh a
                LEFT JOIN msmemb b ON a.dfno = b.dfno
                LEFT JOIN mssc c ON a.sc_dfno = c.loccd
                WHERE a.trcd = '$id'";
        return $this->getRecordset($qry, NULL, $this->db2);
    }

    function HapusDeposit($id) {
        $this->db = $this->load->database($this->db2, true);
        $this->db->select('*');
        $this->db->where('id_deposit', $id);
        $query = $this->db->get('sc_newtrh a');
        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            return false;
        } else {
            $slc2 = "SELECT * FROM deposit_D WHERE id_header = '$id'";
            $query2 = $this->db->query($slc2);
            foreach ($query2->result() as $data2) {
                $tipes = 'VoucherNo';
                $ss = $data2->kategori;
                if ($ss == "Voucher Cash") {
                    $tipes = 'voucherkey';
                }
                $insert2 = "UPDATE tcvoucher SET status=0, claimstatus =0 WHERE voucherkey = '$data2->voucher_scan'";
                $this->db->query($insert2);
            }
            $del = "DELETE FROM deposit_H
              WHERE id='$id'";
            $this->db->query($del);
            $del = "DELETE FROM deposit_D
              WHERE id_header='$id'";
            $this->db->query($del);
            return true;
        }
    }

    function getsisaBARU($stk) {
        $qry = "SELECT a.id_header, a.saldo, sum(c.payamt) as payamt FROM
            (SELECT id_header, sum (nominal) as saldo
            FROM deposit_D
            WHERE id_header ='$stk'
            GROUP BY id_header) a
            LEFT JOIN sc_newtrh b
            ON a.id_header=b.id_deposit
            LEFT JOIN sc_newtrp c
            on b.trcd=c.trcd AND c.paytype='08'
            GROUP BY a.id_header, a.saldo";
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
        $this->db = $this->load->database('alternate', true);

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
}
