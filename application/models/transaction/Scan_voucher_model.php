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
                FROM sc_newtrh a 
                LEFT JOIN deposit_H b on a.id_deposit=b.id
                LEFT JOIN msmemb c on a.dfno=c.dfno
                LEFT JOIN sc_newtrp d on a.trcd = d.trcd AND d.paytype = '01'
                LEFT JOIN sc_newtrp e on a.trcd = e.trcd AND e.paytype != '01'
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
}
