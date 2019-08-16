<?php
class Sales_generate_model extends MY_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getGenerateByStk($x)
    {
        //$x['from'],$x['to'],$x['idstkk'],$x['bnsperiod'],$x['searchs']
        //$username = $this->session->userdata('stockist');


        /* if($x['searchs'] == "stock"){
            $usertype = "a.sc_dfno = '".$x['mainstk']."' and a.sctype = '1' AND a.ttptype = 'SC'";
        }else{
            $usertype = "a.sc_dfno = '".$x['mainstk']."' and a.sctype = '1' AND (a.ttptype = 'MEMBP' or a.ttptype = 'MEMB')";
        }

        $trxdate = "";
        if($x['from'] != "" && $x['from'] != " " && $x['from'] != null) {
            //echo "tgl : ".$x['from'];
            $froms = date('Y/m/d', strtotime($x['from']));
            $tos = date('Y/m/d', strtotime($x['to']));
            $trxdate = " and (CONVERT(VARCHAR(10), a.etdt, 111) between '".$froms."' and '".$tos."')";
        }

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month']."/"."1"."/".$data['year'];

        $slc = "select a.trcd,a.dfno,a.totpay, a.nbv, a.etdt,a.bnsperiod,a.sc_dfno,b.fullnm,a.sc_co
                    from sc_newtrh a
                        inner join klink_mlm2010.dbo.msmemb b on (a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS)
                    where
                    a.bnsperiod = '".$bonusperiod."'
                    and a.trtype = 'SB1'
                    and a.loccd = '".$x['mainstk']."'
                    and (a.flag_batch = '0')
                    and (a.batchno = '' or a.batchno is null)
                    and $usertype $trxdate
                    order by a.trcd";
       //a.createnm = '".$x['mainstk']."' and
       echo $slc."<br>".$query; */

        // -- change starts here -- //

        $username = $this->session->userdata('username');
        $froms = date('Y-m-d', strtotime($x['from']));
        $tos = date('Y-m-d', strtotime($x['to']));
        $usertype="";

        if ($x['searchs'] == "stock") {
            $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe = 'SSR' ";
        } elseif ($x['searchs']=="apl") {
            $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe = 'Application' ";
        } elseif ($x['searchs']=="dpv") {
            $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe = 'Voucher Product (Deposit)' ";
        } elseif ($x['searchs']=="dcv") {
            $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe = 'Voucher Cash (Deposit)' ";
        } elseif ($x['searchs'] == "sub") {
            if ($x['mainstk'] != "") {
                $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe = 'SSSR' ";
            } else {
                $usertype = "AND  a.tipe = 'SSSR' ";
            }
        } elseif ($x['searchs'] == "ms") {
            if ($x['mainstk'] != "") {
                $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe='MSR' ";
            } else {
                $usertype = "and a.tipe='MSR'  ";
            }
        }

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month']."/"."1"."/".$data['year'];

        $slc = "SELECT A.sc_dfno, A.sc_co, b.fullnm as scdfno,
                      A.loccd,c.fullnm as scco,
                      tipe,
                      SUM(totpay) as totpay,
                      bnsperiod,
                      SUM(tbv) as tbv,
                      SUM(d.payamt) as cash,
                      SUM(e.payamt) as vcash
                FROM ALDI_22022018 A
                LEFT join mssc B ON a.sc_dfno =b.loccd
                LEFT JOIN mssc C ON a.sc_co=c.loccd
                LEFT JOIN sc_newtrp D ON a.trcd=d.trcd AND d.paytype='01'
                LEFT JOIN sc_newtrp E ON a.trcd=E.trcd AND E.paytype='08'
                WHERE A.loccd = '".$x['mainstk']."'
                AND bnsperiod= '$bonusperiod'
                AND etdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59' $usertype
                GROUP BY A.sc_dfno, A.sc_co, b.fullnm , A.loccd,c.fullnm , tipe, bnsperiod ";
        echo $slc;
        return $this->getRecordset($slc, null, $this->db2);
    }

    public function getGenerateByPVR($x)
    {
        //$froms = date('Y/m/d', strtotime($x['from']));
        //$tos = date('Y/m/d', strtotime($x['to']));

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month']."/"."1"."/".$data['year'];

        $trxdate = "";
        if ($x['from'] != "" && $x['from'] != " " && $x['from'] != null) {
            //echo "tgl : ".$x['from'];
            $froms = date('Y/m/d', strtotime($x['from']));
            $tos = date('Y/m/d', strtotime($x['to']));
            $trxdate = " and (CONVERT(VARCHAR(10), a.etdt, 111) between '".$froms."' and '".$tos."')";
        }

        $qry = "SELECT a.trcd,a.dfno,a.totpay,a.etdt,a.bnsperiod,b.fullnm, a.sc_dfno, a.sc_co
                    FROM sc_newtrh a
                    INNER JOIN klink_mlm2010.dbo.msmemb b ON (a.dfno = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS)
                    WHERE a.sc_dfno = '".$this->stockist."'
                    AND a.bnsperiod = '".$bonusperiod."'
                    AND a.sc_co = '".$this->stockist."'
                    AND (a.ttptype = 'SUBSC' or a.ttptype = 'SC' OR a.ttptype = 'REDEMPTION')
                    AND (a.batchno = '' OR a.batchno is null)
                    AND a.ttptype <> 'MEMB'
                    AND a.ttptype <> 'MEMBP' AND a.trtype = 'VP1' AND a.pricecode != '1609P'
                    $trxdate
                    ORDER BY a.etdt";
        //and a.createnm = '".$this->stockist."'
        echo $qry;
        return $this->getRecordset($qry, null, $this->db1);
    }

    public function getGenerateBySUbMs($x)
    {
        //$x['from'],$x['to'],$x['idstkk'],$x['bnsperiod'],$x['searchs']
        //$username = $this->session->userdata('stockist');
        //$froms = date('Y/m/d', strtotime($x['from']));
        //$tos = date('Y/m/d', strtotime($x['to']));
        if ($x['idstkk'] == "") {
            if ($x['searchs'] == "sub") {
                $usertype = "a.loccd = '".$x['mainstk']."' AND a.sctype = '2' AND a.ttptype = 'SUBSC'";
            } else {
                $usertype = "a.loccd = '".$x['mainstk']."' AND a.sctype = '3' AND a.ttptype = 'SUBSC'";
            }
        } else {
            if ($x['searchs'] == "sub") {
                //$sctype = $this->cekSctype($idstk);
                $usertype = "a.sc_dfno = '".$x['idstkk']."' AND a.sctype = '2' AND a.ttptype = 'SUBSC'";
            } else {
                //$sctype = $this->cekSctype($idstk);
                $usertype = "a.sc_dfno = '".$x['idstkk']."' AND a.sctype = '3' AND a.ttptype = 'SUBSC'";
            }
        }

        $trxdate = "";
        if ($x['from'] != "" && $x['from'] != " " && $x['from'] != null) {
            //echo "tgl : ".$x['from'];
            $froms = date('Y/m/d', strtotime($x['from']));
            $tos = date('Y/m/d', strtotime($x['to']));
            $trxdate = " AND (CONVERT(VARCHAR(10), a.etdt, 111) BETWEEN '".$froms."' AND '".$tos."')";
        }

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month']."/"."1"."/".$data['year'];

        $slc = "SELECT a.sc_dfno, b.fullnm, a.bnsperiod,SUM(a.totpay) AS totpay,
                COUNT(a.trcd) AS ttp,SUM(a.nbv) AS totbv,a.sc_co, a.loccd
                FROM sc_newtrh a
                INNER JOIN klink_mlm2010.dbo.mssc b ON a.sc_dfno=b.loccd
                WHERE a.bnsperiod = '".$bonusperiod."'

                AND a.trtype = 'SB1'
                AND (a.batchno = '' OR a.batchno IS NULL)
                AND a.flag_batch = '0' AND $usertype $trxdate
                GROUP BY a.bnsperiod,a.sc_dfno, b.fullnm,a.sc_co, a.loccd";
        echo $slc;
        //and a.createnm = '".$x['mainstk']."'
        return $this->getRecordset($slc, null, $this->db1);
    }

    public function get_details_salesttp($scdfno, $bnsperiod, $scco)
    {
        //$bnsperiod = "01/".$bnsperiod;
        /*$slc = "select a.trcd,a.totpay,a.createdt,a.dfno,a.sc_dfno,a.nbv,a.bnsperiod,b.fullnm
                from sc_newtrh a
                   inner join klink_mlm2010.dbo.msmemb b on a.dfno = b.dfno
                where a.sc_dfno = '".$scdfno."' and a.sc_co = '".$scco."'
                and a.createnm = '".$this->stockist."' AND a.bnsperiod = '".$bnsperiod."' and a.batchno is null";
        */
        $slc = "SELECT a.trcd,a.totpay,a.createdt,a.dfno,a.sc_dfno,a.nbv,a.bnsperiod,b.fullnm
                FROM sc_newtrh a
                INNER JOIN klink_mlm2010.dbo.msmemb b ON a.dfno = b.dfno
                WHERE a.sc_dfno = '".$scdfno."' AND a.sc_co = '".$scco."'
                AND a.createnm = '".$this->stockist."' AND a.bnsperiod = '".$bnsperiod."' AND a.batchno IS NULL";
        //echo $slc;
        return $this->getRecordset($slc, null, $this->db1);
    }
}
