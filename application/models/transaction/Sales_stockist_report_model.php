<?php
class Sales_stockist_report_model extends MY_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getListGeneratedSales($x)
    {
        //$x['from'],$x['to'],$x['idstkk'],$x['bnsperiod'],$x['searchs']
        //$username = $this->session->userdata('stockist');
        $froms = date('Y/m/d', strtotime($x['from']));
        $tos = date('Y/m/d', strtotime($x['to']));

        /*if($x['searchs'] == "stock"){
            $usertype = "a.sc_dfno = '".$x['mainstk']."' and a.sctype = '".$x['sctype']."'";
        }*/

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."1";

        $slc = "select * from V_HILAL_SCO_SSR_LIST a
        		WHERE a.loccd = '$x[sc_dfno]'
        		AND a.sctype = '".$x['sctype']."'
        		AND a.bnsperiod='$bonusperiod'
        		AND a.flag_batch <> '0'
        		AND (a.batchno <> '' OR a.batchno is not null)
        		ORDER BY a.batchdt desc";
        //echo $slc;
        return $this->getRecordset($slc, null, $this->db2);
    }

    public function getHeaderSsr($field, $value)
    {
        $qry = "SELECT top 1 a.trcd,
						a.sc_dfno, b.fullnm as sc_dfno_name,
						a.sc_co, c.fullnm as sc_co_name,
						a.loccd, d.fullnm as loccd_name,
						CONVERT(char(10), a.batchdt,126) as batchdt,
						CONVERT(char(10), a.bnsperiod,126) as bnsperiod,
						e.trcd as cn_no,
						CONVERT(char(10), e.createdt,126) as cn_dt,
						e.createnm as cn_createnm,
						f.trcd as kw_no,
						CONVERT(char(10), f.createdt,126) as kw_dt,
						f.createnm as kw_createnm,
						g.GDO as do_no,
						CONVERT(char(10), g.createdt,126) as do_dt,
						g.createnm as do_createnm
				FROM sc_newtrh a
				LEFT OUTER JOIN mssc b ON (a.sc_dfno = b.loccd)
				LEFT OUTER JOIN mssc c ON (a.sc_co = c.loccd)
				LEFT OUTER JOIN mssc d ON (a.loccd = d.loccd)
				LEFT OUTER JOIN ordivtrh e ON (a.batchno = e.batchscno)
				LEFT OUTER JOIN billivhdr f ON (e.receiptno = f.trcd)
				LEFT OUTER JOIN intrh g ON (e.receiptno = g.applyto)
				WHERE a.$field = '$value'";
        return $this->getRecordset($qry, null, $this->db2);
    }

    public function getListSummaryTtp($field, $value)
    {
        $slc = "SELECT a.trcd, a.orderno, a.dfno, b.fullnm, a.tdp, a.tbv
				FROM sc_newtrh a
				LEFT OUTER JOIN msmemb b ON (a.dfno = b.dfno)
				WHERE a.$field = '$value'";
        //echo $slc;
        return $this->getRecordset($slc, null, $this->db2);
    }

    public function getListSummaryProduct($field, $value)
    {
        $qry = "SELECT a.prdcd, b.prdnm, SUM(a.qtyord) as qtyord
				FROM sc_newtrd a
				INNER JOIN sc_newtrh c ON (a.trcd = c.trcd)
				LEFT OUTER JOIN msprd b ON (a.prdcd = b.prdcd)
				WHERE c.$field = '$value'
				GROUP BY a.prdcd, b.prdnm
				ORDER BY a.prdcd, b.prdnm";
        return $this->getRecordset($qry, null, $this->db2);
    }

    public function getVoucherReportList($field, $value)
    {
        $qry = "SELECT a.claimstatus, c.trcd, d.batchno, d.batchdt, d.loccd,
				       a.DistributorCode, b.fullnm, a.voucherkey, a.VoucherNo as VoucherNo,
				       a.vchtype,a.VoucherAmt,
				       CONVERT(char(10), d.createdt, 126) as tglklaim,
				       CONVERT(char(10), a.ExpireDate,126) as ExpireDate,
				       CONVERT(char(10), GETDATE(),126) as nowDate,
				       CASE
				           WHEN CONVERT(char(10), GETDATE(),126) > CONVERT(char(10), a.ExpireDate,126) THEN '1'
				           ELSE '0'
				       END AS status_expire
				FROM tcvoucher a
				INNER JOIN msmemb b ON (a.DistributorCode = b.dfno)
				LEFT JOIN sc_newtrp c ON (a.VoucherNo = c.docno)
				LEFT JOIN sc_newtrh d ON ( d.trcd =
					CASE
	                    WHEN c.trcd is null THEN '0'
	                    WHEN c.trcd is not null THEN c.trcd
	                END
				)
				WHERE a.$field = '".$value."'";
        //echo $qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    public function getGenerateRPTByCahyono($from, $to, $idstk, $searchBy, $status, $type="array")
    {
        $username = $this->session->userdata('username');
        $froms = date('Y-m-d', strtotime($from));
        $tos = date('Y-m-d', strtotime($to));
        $usertype="";

        if ($searchBy == "ALL") {
            $usertype="";
        } else {
            $usertype = "and batchno like '".$searchBy."%' ";
        }

        if ($status == "ALL") {
            $usertype2="";
        } else {
            $usertype2 = "and A.flag_batch like '".$status."%' ";
        }
//        echo "xd =".$searchBy;


//
//
//        $slc = "
//        SELECT batchno, batchdt, sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd, createnm, SUM(TOTDP) AS TOTDP, SUM (TOTBV) AS TOTBV, x_status
//        FROM V_HILAL_REPORT_SALES_STOCKIST A
//        WHERE
//        A.createnm ='$username'
//        AND
//        batchdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59'
//        $usertype
//        GROUP BY
//
//        batchno, batchdt, sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd, createnm, x_status
//        ";

        $slcD="SELECT SEP.*, ZEB.trcd2
				FROM (
					SELECT ey.batchno, ey.batchdt, ey.sc_dfno, ey.fullnm_DFNO, ey.sc_co,
					ey.fullnm_CO, ey.loccd, ey.createnm, ey.x_status,
					(ey.TOTDP) AS TOTDP,
					(ey.TOTBV) as TOTBV, SUM(CASH) as cash,
					SUM(vcash) as vcash
					FROM
					(
						SELECT A.batchno, A.batchdt, A.sc_dfno, A.fullnm_DFNO, A.sc_co, A.fullnm_CO, A.loccd,A.createnm,(A.TOTDP) AS TOTDP,  (A.TOTBV) AS TOTBV, A.x_status , SUM(C.payamt) AS CASH, sum(d.payamt) as vcash
						FROM V_HILAL_REPORT_SALES_STOCKIST A
						LEFT JOIN sc_newtrh B ON A.batchno=B.batchno
						LEFT JOIN sc_newtrp C ON a.trcd=C.trcd AND C.paytype='01'
						LEFT JOIN sc_newtrp D ON a.trcd=D.trcd AND D.paytype='08'
						WHERE A.createnm ='$username'
						AND A.batchdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59'
						$usertype
						$usertype2
						GROUP BY A.batchno, A.batchdt, A.sc_dfno, A.fullnm_DFNO, A.sc_co,
						A.fullnm_CO, A.loccd, A.createnm, A.x_status, C.paytype,(A.TOTDP),  (A.TOTBV)
					) ey
				GROUP BY ey.batchno, ey.batchdt, ey.sc_dfno, ey.fullnm_DFNO, ey.sc_co, ey.fullnm_CO, ey.loccd, ey.createnm, ey.x_status,ey.TOTDP,ey.TOTBV
				)  SEP
				LEFT JOIN sc_newtrp_vc_det ZEB ON SEP.batchno = ZEB.trcd
				GROUP BY
				batchno, batchdt, SEP.sc_dfno, fullnm_DFNO, SEP.Sc_co, fullnm_CO, loccd, SEP.createnm,
				x_status, TOTDP, TOTBV, cash,  vcash, trcd2";

        $slc3="SELECT sum(tdp) as TOTDP,
		            SUM(cash) as cash, sum(vcash) as vcash,
                    sc_dfno,  fullnm_DFNO, sc_co,  fullnm_CO,
                    loccd,  fullnm_LOCCS,
                    createnm,batchno, batchdt,trcd2, x_status,sum(tbv) as TOTBV

               FROM (
						SELECT a.trcd,a.tdp,
							CASE
								WHEN C.paytype='01' THEN sum(c.payamt)
								END as cash,
							CASE
								WHEN C.paytype='08' THEN sum(c.payamt)
								END as vcash,
							CASE
								WHEN A.flag_batch='0' THEN 'Inputed'
								WHEN A.flag_batch='1' THEN 'Generated'
								WHEN A.flag_batch='2' THEN 'Approved'
								END AS x_status,
							A.sc_dfno, B1.fullnm AS fullnm_DFNO,
							A.sc_co, B2.fullnm AS fullnm_CO,
							A.loccd, B3.fullnm AS fullnm_LOCCS,
							A.createnm,A.batchno, A.batchdt as batchdt,d.trcd2, tbv
						FROM sc_newtrh A
						LEFT JOIN sc_newtrp C ON a.trcd=C.trcd
						INNER JOIN mssc B1 ON A.sc_dfno=B1.loccd
						INNER JOIN mssc B2 ON A.sc_co=B2.loccd
						INNER JOIN mssc B3 ON A.loccd=B3.loccd
						LEFT JOIN sc_newtrp_vc_det d ON a.batchno = d.trcd
						WHERE A.createnm ='$username'
						AND A.createdt BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
						$usertype
						$usertype2
						GROUP BY a.trcd,a.tdp,
								A.sc_dfno, B1.fullnm,
						A.sc_co, B2.fullnm,
						A.loccd, B3.fullnm,
						A.createnm,A.batchno, A.batchdt,d.trcd2,C.paytype,A.flag_batch,tbv

					) sui
				GROUP BY
				sc_dfno,  fullnm_DFNO,
				sc_co,  fullnm_CO,
				loccd,  fullnm_LOCCS,
				createnm,batchno, batchdt,trcd2,x_status";

        $slc="SELECT a.* , b.trcd2
			  FROM (
				SELECT SUM(TOTDP) AS TOTDP, sum(cash) as cash, sum(vcash) as vcash,
				   sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd,
				   fullnm_LOCCS, createnm,batchno, batchdt,
				   x_status, sum(TOTBV) as TOTBV
				FROM
				(
					SELECT (tdp) as TOTDP, SUM(cash) as cash,
						sum(vcash) as vcash, sui.sc_dfno,
						fullnm_DFNO, sui.sc_co, fullnm_CO, sui.loccd,
						fullnm_LOCCS, sui.createnm,batchno,
						batchdt, x_status,(tbv) as TOTBV
					FROM (
						SELECT a.trcd,a.tdp,CASE WHEN C.paytype='01'
						THEN (c.payamt) END as cash,
						CASE WHEN C.paytype='08' THEN (c.payamt) END as vcash,
						CASE
								WHEN A.flag_batch='0' THEN 'Inputed'
								WHEN A.flag_batch='1' THEN 'Generated'
								WHEN A.flag_batch='2' THEN 'Approved'
							END AS x_status,
						A.sc_dfno, B1.fullnm AS fullnm_DFNO, A.sc_co, B2.fullnm AS fullnm_CO, A.loccd,
						B3.fullnm AS fullnm_LOCCS, A.createnm,A.batchno, A.batchdt as batchdt, tbv
						FROM sc_newtrh A
						LEFT JOIN sc_newtrp C ON a.trcd=C.trcd
						INNER JOIN mssc B1 ON A.sc_dfno=B1.loccd
						INNER JOIN mssc B2 ON A.sc_co=B2.loccd
						INNER JOIN mssc B3 ON A.loccd=B3.loccd
						WHERE A.createnm ='$username'
						AND A.batchdt BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
								$usertype
								$usertype2
					 ) sui
                    GROUP BY sui.tdp,sui.sc_dfno, fullnm_DFNO, sui.sc_co,
					fullnm_CO, sui.loccd, fullnm_LOCCS, sui.createnm,sui.batchno, sui.batchdt,x_status,tbv
) ey
GROUP BY  sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd, fullnm_LOCCS, createnm,batchno, batchdt, x_status
) a
LEFT JOIN sc_newtrp_vc_det b
on a.batchno = b.trcd
GROUP BY
 a.TOTDP, a.cash, a.vcash,a.sc_dfno, a.fullnm_DFNO, a.sc_co, a.fullnm_CO, a.loccd, a.fullnm_LOCCS, a.createnm,a.batchno, a.batchdt, a.x_status, a.TOTBV, b.trcd2

      ";


        $slc="
        SELECT SUM(TOTDP) as TOTDP, SUM(cash) as cash, SUM(vcash) as vcash, sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd, fullnm_LOCCS, createnm, batchno, batchdt, x_status, sum(TOTBV) as TOTBV, trcd2
FROM
(
SELECT a.* ,
	b.trcd2
from (
	SELECT trcd, (TOTDP) AS TOTDP,
    	   sum(cash) as cash,
           sum(vcash) as vcash,
           sc_dfno,
           fullnm_DFNO,
           sc_co,
           fullnm_CO,
           loccd,
           fullnm_LOCCS,
           createnm,
           batchno,
           batchdt,
           x_status,
           (TOTBV) as TOTBV
	FROM (
		SELECT trcd, (tdp) as TOTDP,
			SUM(cash) as cash,
			sum(vcash) as vcash,
			sui.sc_dfno,
			fullnm_DFNO,
			sui.sc_co,
			fullnm_CO,
			sui.loccd,
			fullnm_LOCCS,
			sui.createnm,
			batchno,
			batchdt,
			x_status,
			(tbv) as TOTBV
		from
		(
			SELECT 	a.trcd,a.tdp,
					CASE
						WHEN C.paytype='01' THEN (c.payamt)
					END AS cash,
					CASE
						WHEN (C.paytype='08' or C.paytype='10')  THEN (c.payamt)
					END AS vcash,

					CASE
						WHEN A.flag_batch='0' THEN 'Inputed'
						WHEN A.flag_batch='1' THEN 'Generated'
						WHEN A.flag_batch='2' THEN 'Approved'
					END AS x_status,
					A.sc_dfno,
				B1.fullnm AS fullnm_DFNO,
				A.sc_co,
				B2.fullnm AS fullnm_CO,
				A.loccd,
				B3.fullnm AS fullnm_LOCCS,
				A.createnm,
				A.batchno,
				A.batchdt as batchdt,
				tbv
			FROM sc_newtrh A
				LEFT JOIN sc_newtrp C ON a.trcd=C.trcd
				INNER JOIN mssc B1 ON A.sc_dfno=B1.loccd
				INNER JOIN mssc B2 ON A.sc_co=B2.loccd
				INNER JOIN mssc B3 ON A.loccd=B3.loccd
        WHERE A.createnm ='$username'
        AND A.batchdt BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
        $usertype
        $usertype2
		) sui
		GROUP BY sui.trcd, sui.tdp,
			 sui.sc_dfno,
			 fullnm_DFNO,
			 sui.sc_co,
			 fullnm_CO,
			 sui.loccd,
			 fullnm_LOCCS,
			 sui.createnm,
			 sui.batchno,
			 sui.batchdt,
			 x_status ,
			 tbv
	) ey
	GROUP BY ey.trcd,ey.TOTDP, sc_dfno, ey.TOTbv,
		 fullnm_DFNO,
		 sc_co,
		 fullnm_CO,
		 loccd,
		 fullnm_LOCCS,
		 createnm,
		 batchno,
		 batchdt,
		 x_status
) a
	LEFT JOIN sc_newtrp_vc_det b on a.batchno = b.trcd
GROUP BY a.trcd, a.TOTDP,
	     a.cash,
         a.vcash,
         a.sc_dfno,
         a.fullnm_DFNO,
         a.sc_co,
         a.fullnm_CO,
         a.loccd,
         a.fullnm_LOCCS,
         a.createnm,
         a.batchno,
         a.batchdt,
         a.x_status,
         a.TOTBV,
         b.trcd2
) final
GROUP BY
 sc_dfno, fullnm_DFNO, sc_co, fullnm_CO, loccd, fullnm_LOCCS, createnm, batchno, batchdt, x_status,  trcd2

        ";
        //echo $slc;
        return $this->get_recordset($slc, $type, "alternate");
    }
}
