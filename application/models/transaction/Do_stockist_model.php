<?php
class Do_stockist_model extends MY_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getListDOStkByData($from, $to, $loccd) {
        /* $qry = "SELECT a.trcd, a.createnm, a.shipto, a.shipby,
                CONVERT(VARCHAR(10), a.etdt, 120) as do_date, a.etdt
                FROM gdohdr a 
                WHERE a.shipto = '$loccd' 
                AND CONVERT(VARCHAR(10), a.etdt, 120) 
                    BETWEEN '$from' AND '$to'
                ORDER BY a.etdt DESC"; */

        $qry = "SELECT a.no_do as trcd, a.do_createby as createnm, 
                    a.id_stockist as shipto, a.warehouse_name as shipby,
                CONVERT(VARCHAR(10), a.do_date, 120) as do_date, a.do_date as etdt,
                a.no_resi
                FROM klink_mlm2010.dbo.DO_NINGSIH a 
                WHERE a.id_stockist = '$loccd' 
                AND CONVERT(VARCHAR(10), a.do_date, 120) 
                BETWEEN '$from' AND '$to'
                GROUP BY a.no_do, a.do_createby, a.id_stockist, a.warehouse_name,
                CONVERT(VARCHAR(10), a.do_date, 120), a.do_date, a.no_resi
                ORDER BY a.do_date DESC";        
        //echo $qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getListSSRbyDO($trcd) {
        /* $qry = "SELECT a.applyto, b.ordtype, b.batchno, b.trcd,
                    CASE WHEN 
                        b.batchscno is null OR b.batchscno = ''
                        THEN b.trcd 
                        ELSE b.batchscno 
                    END AS batchscno, 
                    count(c.trcd) as jumlah_ttp,
                    case when CONVERT(VARCHAR(10), c.batchdt, 120) is null 
                    then CONVERT(VARCHAR(10), b.etdt, 120) else CONVERT(VARCHAR(10), c.batchdt, 120) 
                    end as etdt,
                    b.ndp as total_dp, b.nbv as total_bv, b.dfno, d.fullnm
                FROM intrh a 
                LEFT OUTER JOIN ordivtrh b ON (a.applyto = b.receiptno)
                LEFT OUTER JOIN sc_newtrh c ON (b.trcd = c.csno)
                LEFT OUTER JOIN mssc d ON (b.dfno = d.loccd)
                WHERE a.GDO = '$trcd'
                GROUP BY a.applyto, b.ordtype, b.trcd, b.batchno, b.batchscno,
                    c.batchdt, b.etdt, b.trtype, b.OLstatus, b.dfno, d.fullnm,
                    b.ndp, b.nbv
                ORDER BY B.trcd"; */
        //echo $qry;

        $qry = "SELECT a.no_kwitansi as applyto, b.batchscno, b.ndp as total_dp,
                b.nbv as total_bv, b.dfno, d.fullnm, b.ordtype, b.batchno, b.trcd,
                COUNT(c.trcd) as jumlah_ttp, 
                CONVERT(VARCHAR(10), c.batchdt, 120) as etdt
                FROM klink_mlm2010.dbo.DO_NINGSIH a
                INNER JOIN ordivtrh b ON (a.no_kwitansi COLLATE SQL_Latin1_General_CP1_CS_AS = b.receiptno)
                LEFT OUTER JOIN sc_newtrh c ON (b.trcd COLLATE SQL_Latin1_General_CP1_CS_AS= c.csno)
                LEFT OUTER JOIN mssc d ON (b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS = d.loccd)
                WHERE a.no_do = '$trcd'
                GROUP BY a.no_kwitansi, b.batchscno, b.ndp,
                b.nbv, b.dfno, d.fullnm, b.ordtype, b.batchno, b.trcd,
                CONVERT(VARCHAR(10), c.batchdt, 120)";

        return $this->getRecordset($qry, null, $this->db2);
    }

    function getListInvoicebyDO($trcd) {
        /* $qry = "SELECT a.applyto, b.ordtype, b.batchno, b.trcd,
                    b.invoiceno as batchscno,
                    COUNT(c.trcd) as jumlah_ttp,
                    case when CONVERT(VARCHAR(10), c.batchdt, 120) is null 
                    then CONVERT(VARCHAR(10), b.etdt, 120) else CONVERT(VARCHAR(10), c.batchdt, 120) 
                    end as etdt,
                    b.ndp as total_dp, b.nbv as total_bv, b.dfno, d.fullnm
                FROM intrh a 
                LEFT OUTER JOIN ordtrh b ON (a.applyto = b.receiptno)
                LEFT OUTER JOIN newtrh c ON (b.invoiceno = c.trcd)
                LEFT OUTER JOIN msmemb d ON (b.dfno = d.dfno)
                WHERE a.GDO = '$trcd'
                GROUP BY a.applyto, b.ordtype, b.trcd, b.batchno, b.invoiceno,
                    c.batchdt, b.etdt, b.trtype, b.dfno, d.fullnm,
                    b.ndp, b.nbv
                ORDER BY B.trcd"; */

        $qry = "SELECT a.no_kwitansi as applyto, 
                    b.trcd as batchscno, b.ndp as total_dp,
                b.nbv as total_bv, b.dfno, d.fullnm, b.ordtype, b.trcd as batchno, b.trcd,
                COUNT(c.trcd) as jumlah_ttp,
                CONVERT(VARCHAR(10), c.batchdt, 120) as etdt
                FROM klink_mlm2010.dbo.DO_NINGSIH a
                INNER JOIN ordtrh b ON (a.no_kwitansi COLLATE SQL_Latin1_General_CP1_CS_AS = b.receiptno)
                LEFT OUTER JOIN newtrh c ON (b.trcd COLLATE SQL_Latin1_General_CP1_CS_AS= c.trcd)
                LEFT OUTER JOIN msmemb d ON (b.dfno = d.dfno)
                WHERE a.no_do = '$trcd'
                GROUP BY a.no_kwitansi, b.trcd, b.ndp,
                b.nbv, b.dfno, d.fullnm, b.ordtype, b.batchno, b.trcd,
                CONVERT(VARCHAR(10), c.batchdt, 120)";        
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getListTTPbySSR($trcd) {
        $qry = "SELECT a.applyto, b.ordtype, b.trcd, b.batchno, b.batchscno, 
                    count(c.trcd) as jumlah_ttp,
                    case when CONVERT(VARCHAR(10), c.batchdt, 120) is null 
                      then CONVERT(VARCHAR(10), b.etdt, 120) else CONVERT(VARCHAR(10), c.batchdt, 120) 
                    end as etdt,
                    b.ndp as total_dp, b.nbv as total_bv, b.dfno, d.fullnm
                FROM intrh a 
                LEFT OUTER JOIN ordivtrh b ON (a.applyto = b.receiptno)
                LEFT OUTER JOIN sc_newtrh c ON (b.trcd = c.csno)
                LEFT OUTER JOIN mssc d ON (b.dfno = d.loccd)
                WHERE a.GDO = '$trcd'
                GROUP BY a.applyto, b.ordtype, b.trcd, b.batchno, b.batchscno,
                    c.batchdt, b.etdt, b.trtype, b.OLstatus, b.dfno, d.fullnm,
                    b.ndp, b.nbv
                ORDER BY B.trcd";
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getDOheader($do) {
		$qry = "SELECT a.trcd, a.whcd, a.shipto, a.createnm,
				CONVERT(VARCHAR(10), a.etdt, 21) as etdt,
				a.shipby, COUNT(b.applyto) as total_kw, b.trtype
				FROM gdohdr a
				LEFT OUTER JOIN intrh b ON (a.trcd = b.GDO)
				WHERE a.trcd = '$do'
				GROUP BY a.trcd, a.whcd, a.shipto, a.createnm,
				CONVERT(VARCHAR(10), a.etdt, 21),
				a.shipby, b.trtype";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
  }

  function getDODetail($do) {
		$qry = "SELECT a.prdcd, b.prdnm, a.qtyord
				FROM gdoprd a
				INNER JOIN msprd b ON (a.prdcd = b.prdcd)
				WHERE a.trcd = '$do'
				ORDER BY b.prdnm";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
  }

  function listInvoiceByDO($do) {
		$qry = "SELECT b.applyto,
				CASE
					WHEN c.invoiceno is null THEN d.invoiceno ELSE c.invoiceno
				END AS invoiceno,
				CASE
					WHEN c.invoiceno is null THEN CONVERT(VARCHAR(10), d.invoicedt, 21)
					ELSE CONVERT(VARCHAR(10), c.invoicedt, 21)
				END AS invoicedt,
				CASE
					WHEN c.invoiceno is null THEN d.createnm
					ELSE c.createnm
				END AS createnm,
				CASE
					WHEN c.invoiceno is null THEN d.totpay
					ELSE c.totpay
				END AS totpay,
				CASE
					WHEN c.invoiceno is null THEN d.tbv
					ELSE c.tbv
				END AS tbv,
				CASE
					WHEN c.invoiceno is null THEN d.dfno
					ELSE c.dfno
				END AS dfno
				FROM intrh b
				LEFT OUTER JOIN ordivtrh c ON (b.applyto = c.receiptno)
				LEFT OUTER JOIN ordtrh d ON (b.applyto = d.receiptno)
				WHERE b.GDO = '$do'";
		$res = $this->getRecordset($qry, NULL, $this->db2);
		return $res;
  }
}