<?php
class Do_stockist_model extends MY_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getListDOStkByData($from, $to, $loccd) {
        $qry = "SELECT a.trcd, a.createnm, a.shipto, a.shipby,
                CONVERT(VARCHAR(10), a.etdt, 120) as do_date, a.etdt
                FROM gdohdr a 
                WHERE a.shipto = '$loccd' 
                AND CONVERT(VARCHAR(10), a.etdt, 120) 
                    BETWEEN '$from' AND '$to'
                ORDER BY a.etdt DESC";
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getListSSRbyDO($trcd) {
        $qry = "SELECT a.applyto, b.ordtype, b.batchno, b.trcd,
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
                ORDER BY B.trcd";
        return $this->getRecordset($qry, null, $this->db2);
    }

    function getListInvoicebyDO($trcd) {
        $qry = "SELECT a.applyto, b.ordtype, b.batchno, b.trcd,
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
                ORDER BY B.trcd";
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
}