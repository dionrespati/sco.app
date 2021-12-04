<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_trans_model extends MY_Model {
    function __construct() {
        parent::__construct();
    }

    function checkSSR($no_ssr) {
        $query = "SELECT
                      hdr.loccd,
                      hdr.csno,
                      hdr.no_deposit,
                      hdr.bnsperiod,
                      det.trcd2,
                      CONVERT(VARCHAR(10), hdr.bnsperiod, 120) AS bnsperiod,
                      CONVERT(VARCHAR(10), hdr.batchdt, 120) AS batchdate
                  FROM klink_mlm2010.dbo.sc_newtrh hdr
                  LEFT OUTER JOIN dbo.sc_newtrp_vc_det det ON (det.trcd = hdr.batchno)
                  WHERE hdr.batchno = ?
                  GROUP BY hdr.loccd, hdr.csno, hdr.batchdt, hdr.bnsperiod, hdr.no_deposit, hdr.bnsperiod, det.trcd2";
        return $this->getRecordset($query, $no_ssr, $this->db2);
    }

    function getSumDP($no_ssr) {
      $query = "SELECT
        SUM(hdr.tdp) AS total_dp,
        SUM(hdr.tbv) AS total_bv
        FROM dbo.sc_newtrh hdr
        WHERE hdr.batchno = ?";
      return $this->getRecordset($query, $no_ssr, $this->db2);
    }

    function checkIp($no_ssr) {
      $type = $this->checkCvch($no_ssr);
      if ($type) {
        $query = "SELECT a.trcd, a.refno, a.dfno
         FROM bbhdr a
         WHERE a.refno LIKE '$no_ssr%'";
        return $this->getRecordset($query, null, $this->db2);
      } else {
        $query = "SELECT * FROM dbo.sc_newtrp_vc_det b WHERE b.trcd = ?";
        return $this->getRecordset($query, $no_ssr, $this->db2);
      }
    }

    function checkSsrMsr($ssr) {
      $qry = "SELECT a.loccd, SUM(a.tdp) as total_dp, SUM(a.tbv) as total_bv,
                CONVERT(VARCHAR(10), a.bnsperiod, 120) as bnsperiod,
                a.batchno, a.csno, CONVERT(VARCHAR(10), a.batchdt, 120) as batchdate,
                a.no_deposit, a.id_deposit, SUM(b.payamt) as jum_vch, b.trcd2
              FROM klink_mlm2010.dbo.sc_newtrh a
              LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrp_vc_det b ON (a.batchno = b.trcd AND b.paytype = '08')
              WHERE a.batchno = ?
              GROUP BY a.loccd, CONVERT(VARCHAR(10), a.bnsperiod, 120),
              a.batchno, a.csno, CONVERT(VARCHAR(10), a.batchdt, 120),
              a.no_deposit, a.id_deposit,b.trcd2";
      return $this->getRecordset($qry, $ssr, $this->db2);
    }

    function checkCvch($no_ssr) {
      $checkVoucher = "SELECT a.trcd, a.dfno, a.docno,
                            SUM(a.amount) as total_voucher
                          FROM klink_mlm2010.dbo.cvchpaydet a
                          WHERE a.docno = ?
                          GROUP BY a.trcd, a.dfno, a.docno";
      return $this->getRecordset($checkVoucher, $no_ssr, $this->db2);
    }

    function recoverSSR($no_ssr) {
        $db_qryx = $this->load->database('klink_mlm2010', true);
        $db_qryx->trans_begin();
        /*--------------------------------------------------------
        INSERT DATA YANG AKAN DI RECOVER KE TABLE SSR_RECOVERY_TBL
        ----------------------------------------------------------*/
        $qry = "INSERT INTO ssr_recover_tbl (trcd, batchno, total_dp, total_bv)
                SELECT a.trcd, a.batchno, a.tdp, a.tbv
                FROM klink_mlm2010.dbo.sc_newtrh a
                WHERE a.batchno = '$no_ssr'";
        $db_qryx->query($qry);
        /*--------------------------------------------------------
        RECOVER SALES REPORT
        ----------------------------------------------------------*/
        $voucher_result = $this->checkCvch($no_ssr);
        if ($voucher_result) {
          $deleteIp = "DELETE FROM klink_mlm2010.dbo.bbhdr WHERE refno LIKE '$no_ssr%'";
          $db_qryx->query($deleteIp);

          $deleteVoucher = "DELETE FROM klink_mlm2010.dbo.cvchpaydet WHERE docno = '$no_ssr'";
          $db_qryx->query($deleteVoucher);
        }

        $upd = "UPDATE klink_mlm2010.dbo.sc_newtrh
          SET flag_batch = '0',
          flag_approval = '0',
          flag_recover = '1',
          batchdt = null,
          batchno = null
          WHERE batchno = '$no_ssr'";
        $db_qryx->query($upd);
        if ($db_qryx->trans_status() === FALSE) {
            $db_qryx->trans_rollback();
            return array("response" => "false", "message" => "Recover Gagal");
        } else {
            $db_qryx->trans_commit();
            return array("response" => "true", "message" => "Recover Sales Report $no_ssr berhasil..");
        }
    }

    function recoverVoucher($ips, $vch) {
        $db_qryx = $this->load->database('klink_mlm2010', true);
        $db_qryx->trans_begin();

        $sql = "DELETE FROM klink_mlm2010.dbo.bbhdr WHERE trcd = '$ips'";
        $exeSql = $db_qryx->query($sql);

        $sql1 = "DELETE FROM klink_mlm2010.dbo.sc_newtrp_vc_det WHERE trcd2 = '$ips'";
        $exeSql1 = $db_qryx->query($sql1);

        if ($vch != '') {
          $sql2 = "UPDATE klink_mlm2010.dbo.deposit_H SET status='1' WHERE no_trx = '$vch'";
          $exeSql2 = $db_qryx->query($sql2);
        }

        if ($db_qryx->trans_status() === FALSE) {
            $db_qryx->trans_rollback();
            return array("response" => "false", "message" => "Recover $ips Gagal");
        } else {
            $db_qryx->trans_commit();
            return array("response" => "true", "message" => "Recover $ips berhasil..");
        }
    }

    function changeBonusPeriod($bonusperiod, $batchno, $cn_no) {
        $db_qryx = $this->load->database('klink_mlm2010', true);
        $db_qryx->trans_begin();
        $params = array($bonusperiod, $batchno);
        $query = "UPDATE a
                    SET a.bnsperiod = ?
                FROM klink_mlm2010.dbo.sc_newtrh a
                WHERE a.batchno = ?
                AND (a.csno IS NULL OR a.csno = '')";
        $db_qryx->query($query, $params);

        if ($db_qryx->trans_status() === FALSE || $cn_no != '') {
            $db_qryx->trans_rollback();
            return array("response" => "false", "message" => "Gagal update periode bonus");
        } else {
            $db_qryx->trans_commit();
            return array("response" => "true", "message" => "Berhasil update periode bonus");
        }
    }
}