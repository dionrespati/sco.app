<?php
class Sales_generate_model extends MY_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function getIPno()
    {
        $this->db = $this->load->database($this->setDB(2), true);
        $seq_IP = $this->cek_seQIP();
        $y1=date("y");
        $m=date("m");
        $tbl = "SEQ_IPVCH"."$y1"."$m";

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
        $pref = "IPS";
        $pref2 = "/VC001";
        $next_seq = sprintf("%04s", $ss);
        $prefix = date('ym');
        $y =  strval($pref.$prefix.$next_seq.$pref2);
        return $y;
    }

    public function cek_seQIP(){
        $this->db = $this->load->database($this->setDB(2), true);
        $y1=date("y");
        $m=date("m");
        $tbl = "SEQ_IPVCH"."$y1"."$m";

        $this->db->trans_begin();

        $cek = "select * from $tbl";
        $query = $this->db->query($cek);
        if ($query->num_rows < 1) {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        } else {
            $input = "insert into $tbl (SeqVal) values('a')";
            $query = $this->db->query($input);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $query;
    }

    public function getGenerateByStk($x) {
        // -- change starts here -- //

        $username = $this->session->userdata('username');
        $froms = date('Y-m-d', strtotime($x['from']));
        $tos = date('Y-m-d', strtotime($x['to']));
        $usertype="";

        if ($x['searchs'] == "stock") {
            $usertype = "and a.loccd = ? and a.tipe = 'SSR' ";
        } elseif ($x['searchs']=="apl") {
            $usertype = "and a.sc_dfno = ? and a.tipe = 'Application' ";
        } elseif ($x['searchs']=="dpv") {
            $usertype = "and a.sc_dfno = ? and a.tipe = 'Voucher Product (Deposit)' ";
        } elseif ($x['searchs']=="dcv") {
            $usertype = "and a.sc_dfno = ? and a.tipe = 'Voucher Cash (Deposit)' ";
        } elseif ($x['searchs'] == "sub") {
            if ($x['mainstk'] != "") {
                $usertype = "and a.loccd = ? and a.tipe = 'SSSR' ";
            } else {
                $usertype = "AND  a.tipe = 'SSSR' ";
            }
        } elseif ($x['searchs'] == "ms") {
            if ($x['mainstk'] != "") {
                $usertype = "and a.loccd = ? and a.tipe='MSR' ";
            } else {
                $usertype = "and a.tipe='MSR'  ";
            }
        } elseif ($x['searchs'] == "pvr") {
            $usertype = "and a.sc_dfno = ? and a.tipe = 'PVR' ";
        }

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month']."/"."1"."/".$data['year'];

        
        
        $slc = "SELECT X.sc_dfno, X.sc_co, X.scdfno, 
                        X.loccd, X.createnm, X.scco, X.tipe, X.bnsperiod,
                        SUM(totpay) as totpay,
                        SUM(tbv) as tbv,
                        SUM(cash) AS cash, SUM(pcash) AS pcash, SUM(vcash) AS vcash
                FROM (
                        SELECT 	A.sc_dfno, A.sc_co, b.fullnm as scdfno, 
                                A.loccd, A.createnm, c.fullnm as scco, tipe, a.bnsperiod, 
                             a.totpay,
                             a.tbv,   
                            ISNULL((SELECT CASE WHEN (LEFT(A.trcd, 2) = 'ID' OR A.tipe = 'Application') AND LEFT(A.trcd, 3) <> 'IDH' 
                            THEN A.totpay ELSE sum(D.payamt) END AS payamt
                            FROM sc_newtrp D
                            WHERE A.trcd=D.trcd AND d.paytype='01'), 0) AS cash,
                            ISNULL((SELECT sum(D.payamt)
                            FROM sc_newtrp D
                            WHERE A.trcd=D.trcd AND d.paytype='10'), 0) AS pcash,
                            ISNULL((SELECT sum(D.payamt)
                            FROM sc_newtrp D
                            WHERE A.trcd=D.trcd AND d.paytype='08'), 0) AS vcash
                        FROM ALDI_22022018V2 a
                            LEFT join mssc B ON a.sc_dfno =b.loccd 
                            LEFT JOIN mssc C ON a.sc_co=c.loccd 
                            
                            WHERE A.createnm = ?
                        AND bnsperiod = ?
                        AND etdt BETWEEN  ? AND ? $usertype
                           
                ) X
                GROUP BY X.sc_dfno, X.sc_co, X.scdfno, 
                        X.loccd, X.createnm, X.scco, X.tipe, X.bnsperiod";
        if($this->username == "BID06") {
            /* echo "<pre>";
            echo $slc;
            echo "</pre>"; */
        }
        $dari = $froms." 00:00:00";
        $ke = $tos." 23:59:59";
        if($usertype == "") {
            $paramQry = array($x['mainstk'],$bonusperiod,$dari,$ke);
            //print_r($paramQry);
        } else {
            $paramQry = array($x['mainstk'],$bonusperiod,$dari,$ke,$x['mainstk']);
        }
        
        return $this->getRecordset($slc, $paramQry, $this->db2);
    }

    public function getGenerateByPVR($x) {
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
        //echo $qry;
        return $this->getRecordset($qry, null, $this->db1);
    }

    public function getGenerateBySUbMs($x) {
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
        //echo $slc;
        //and a.createnm = '".$x['mainstk']."'
        return $this->getRecordset($slc, null, $this->db1);
    }

    public function get_details_salesttp($scdfno, $bnsperiod, $scco) {
        //$bnsperiod = "01/".$bnsperiod;
        /*$slc = "select a.trcd,a.totpay,a.createdt,a.dfno,a.sc_dfno,a.nbv,a.bnsperiod,b.fullnm
                from sc_newtrh a
                   inner join klink_mlm2010.dbo.msmemb b on a.dfno = b.dfno
                where a.sc_dfno = '".$scdfno."' and a.sc_co = '".$scco."'
                and a.createnm = '".$this->stockist."' AND a.bnsperiod = '".$bnsperiod."' and a.batchno is null";
        */
        $slc = "SELECT a.trcd,a.totpay,a.createdt,a.dfno,
                    a.sc_dfno,a.nbv,a.bnsperiod,b.fullnm
                FROM klink_mlm2010.dbo.sc_newtrh a
                INNER JOIN klink_mlm2010.dbo.msmemb b ON a.dfno = b.dfno
                WHERE a.sc_dfno = ? AND a.sc_co = ?
                AND a.createnm = ? 
                AND a.bnsperiod = ? AND a.batchno IS NULL";
        //echo $slc;        
        $createnm = $this->stockist;
        $qryParam = array($scdfno, $scco, $createnm, $bnsperiod);        
        return $this->getRecordset($slc, $qryParam, $this->db1);
    }

    public function getDetailTrx($idstk, $bnsperiod, $searchBy, $scco, $scdfno) {
        $username = $this->session->userdata('username');
        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."01";
        $slc = " SELECT * FROM ALDI_22022018V2 A
              WHERE A.loccd ='$scco'
              AND bnsperiod = '$bonusperiod'
              AND sc_co LIKE '%$scco%'
              AND sc_dfno LIKE '%$scdfno%'
              AND tipe = '$searchBy'
              ORDER BY trcd ";
        //echo $slc;
        return $this->getRecordset($slc, null, $this->db2);
    }

    public function getDetailTrxV2($idstk, $bnsperiod, $searchBy, $scco, $scdfno) {
        $username = $this->session->userdata('username');
        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."01";
        $slc = " SELECT a.trcd, (CONVERT(VARCHAR(10), a.etdt, 120)) AS etdt,
                a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, a.loccd, (CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod
                FROM klink_mlm2010.dbo.ALDI_22022018V2 A
              WHERE A.loccd ='$scco'
              AND bnsperiod = '$bonusperiod'
              AND sc_co LIKE '%$scco%'
              AND sc_dfno LIKE '%$scdfno%'
              AND tipe = '$searchBy'
              ORDER BY trcd ";
        //echo $slc."<br />";
        //return $this->getRecordset($slc, null, $this->db2);
        $trcd = "";
        $query = $this->db->query($slc);
		if ($query !== FALSE) {
			if($query->num_rows() > 0)  {
            $nilai = $query->result();
            foreach($nilai as $dtax) {
                $trcd .= "'".$dtax->trcd."',";
            }

            $trcd = substr($trcd, 0, -1);
          }
        }
        $arr['header'] = $nilai;
        $arr['payment'] = array();
        $arr['listVch'] = array();
        $arr['listCash'] = array();
        $prd = "SELECT prdcd";

        $trp = "SELECT a.trcd, b.dfno, b.orderno, a.paytype, a.docno, a.payamt
                FROM sc_newtrp a
                LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd)
                WHERE a.trcd IN ($trcd) ";
        //echo $trp;
        $payment = $this->getRecordset($trp, null, $this->db2);
        if($payment != null) {
            $arr['payment'] = $payment;

            $tot_vch = 0;
            $tot_cash = 0;
            
            foreach($payment as $listPay) {
                if($listPay->paytype != "01") {
                    $tot_vch += $listPay->payamt;
                    array_push($arr['listVch'], $listPay);
                } else {
                    $tot_cash += $listPay->payamt;
                    array_push($arr['listCash'], $listPay);
                }
            }
            $arr['totalVch'] = $tot_vch;
            $arr['totalCash'] = $tot_cash;
        }

        
        return $arr;
    }

    public function getDetailTrxCheckTTPByCreateDt($idstk, $bnsperiod, $searchBy, $scco, $scdfno, $dari, $ke) {
        $username = $this->session->userdata('username');
        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."01";
        /* $slc = "SELECT a.trcd, (CONVERT(VARCHAR(10), a.etdt, 120)) AS etdt,
                a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, a.loccd, (CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod
                FROM klink_mlm2010.dbo.ALDI_22022018V2 A
              WHERE A.loccd ='$scco'
              AND bnsperiod = '$bonusperiod'
              AND sc_co = '$scco'
              AND sc_dfno = '$scdfno'
              AND tipe = '$searchBy'
              ORDER BY trcd "; */
        $slc = "SELECT a.trcd, a.orderno,
                    (CONVERT(VARCHAR(10), a.etdt, 120)) AS etdt, 
                    a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, 
                    a.sc_co,
                    a.loccd, a.createnm,
                    (CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod, 
                    a.tipe, 
                    ISNULL((SELECT CASE WHEN (LEFT(A.trcd, 2) = 'ID' OR A.tipe = 'Application') AND LEFT(A.trcd, 3) <> 'IDH' 
                    THEN A.totpay ELSE sum(D.payamt) END AS payamt
                    FROM sc_newtrp D
                    WHERE A.trcd=D.trcd AND d.paytype='01'), 0) AS cash,
                    ISNULL((SELECT sum(D.payamt)
                    FROM sc_newtrp D
                    WHERE A.trcd=D.trcd AND d.paytype='10'), 0) AS pcash,
                    ISNULL((SELECT sum(D.payamt)
                    FROM sc_newtrp D
                    WHERE A.trcd=D.trcd AND d.paytype='08'), 0) AS vcash
                FROM klink_mlm2010.dbo.ALDI_22022018V2 A 
                WHERE bnsperiod = ? 
                AND A.loccd = ?
                AND sc_co = ? 
                AND sc_dfno = ? AND tipe = ? 
                AND CONVERT(VARCHAR(10), a.etdt, 120) BETWEEN ? AND ?
                GROUP BY a.trcd, a.orderno, CONVERT(VARCHAR(10), a.etdt, 120), 
                a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, a.sc_co,
                a.loccd, a.createnm, CONVERT(VARCHAR(10), a.bnsperiod, 120), a.tipe
                ORDER BY a.trcd ";
        

        $qryParam = array($bonusperiod, $idstk, $scco, $scdfno, $searchBy, $dari, $ke);
        /* echo $slc."<br />";
        echo "<pre>";
        print_r($qryParam);
        echo "</pre>"; */
        return $this->getRecordset($slc, $qryParam, $this->db2);
    }

    public function getDetailTrxCheckTTP($idstk, $bnsperiod, $searchBy, $scco, $scdfno) {
        $username = $this->session->userdata('username');
        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."01";
        /* $slc = "SELECT a.trcd, (CONVERT(VARCHAR(10), a.etdt, 120)) AS etdt,
                a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, a.loccd, (CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod
                FROM klink_mlm2010.dbo.ALDI_22022018V2 A
              WHERE A.loccd ='$scco'
              AND bnsperiod = '$bonusperiod'
              AND sc_co = '$scco'
              AND sc_dfno = '$scdfno'
              AND tipe = '$searchBy'
              ORDER BY trcd "; */
        $slc = "SELECT a.trcd, a.orderno,
                    (CONVERT(VARCHAR(10), a.etdt, 120)) AS etdt, 
                    a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, 
                    a.sc_co,
                    a.loccd, a.createnm,
                    (CONVERT(VARCHAR(10), a.bnsperiod, 120)) as bnsperiod, 
                    a.tipe, 
                    ISNULL((SELECT CASE WHEN (LEFT(A.trcd, 2) = 'ID' OR A.tipe = 'Application') AND LEFT(A.trcd, 3) <> 'IDH' 
                    THEN A.totpay ELSE sum(D.payamt) END AS payamt
                    FROM sc_newtrp D
                    WHERE A.trcd=D.trcd AND d.paytype='01'), 0) AS cash,
                    ISNULL((SELECT sum(D.payamt)
                    FROM sc_newtrp D
                    WHERE A.trcd=D.trcd AND d.paytype='10'), 0) AS pcash,
                    ISNULL((SELECT sum(D.payamt)
                    FROM sc_newtrp D
                    WHERE A.trcd=D.trcd AND d.paytype='08'), 0) AS vcash
                FROM klink_mlm2010.dbo.ALDI_22022018V2 A 
                WHERE bnsperiod = ? 
                AND A.loccd = ?
                AND sc_co = ? 
                AND sc_dfno = ? AND tipe = ? 
                GROUP BY a.trcd, a.orderno, CONVERT(VARCHAR(10), a.etdt, 120), 
                a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, a.sc_co,
                a.loccd, a.createnm, CONVERT(VARCHAR(10), a.bnsperiod, 120), a.tipe
                ORDER BY a.trcd ";
        

        $qryParam = array($bonusperiod, $idstk, $scco, $scdfno, $searchBy);
        /* echo $slc."<br />";
        echo "<pre>";
        print_r($qryParam);
        echo "</pre>"; */
        return $this->getRecordset($slc, $qryParam, $this->db2);
        
    }

    public function get_SSRno($tipeSales, $bnsperiod, $username, $scDfnoo) {
        $this->load->dbforge();
        $this->db = $this->load->database($this->setDB(2), true);
        $m = date('m');
        $y = date('y');

        if ($scDfnoo == "" && $tipeSales == 'stock') {
            $scDfno = $username;
        } else {
            $scDfno = $scDfnoo;
        }
        $bonus = date("Y-d-m", strtotime($bnsperiod));
        if ($tipeSales == 'sub' || $tipeSales == 'stock' || $tipeSales == 'ms') {
            $prefixs = '02';
        } elseif ($tipeSales == 'apl') {
            $prefixs = '05';
        } else {
            $prefixs = '04';
        }

        $bran = 'B001';
        $this->dbforge->add_field(array(
        'hasil' => array(
            'type' => 'VARCHAR',
            'constraint' => '50',
        ),
    ));
        $today = date('Y-m-d');
        $this->dbforge->create_table('#TempTable');
        $ins ="INSERT INTO #TempTable
            EXEC getbatchno '".$bran."', '".$today."', '".$prefixs."', '".$scDfno."', '".$username."', 'ID', null";
        $qryIns = $this->db->query($ins);
        if ($qryIns > 0) {
            $sql = "SELECT hasil FROM #TempTable";
            $qrySlc = $this->db->query($sql);
        }

        if ($qrySlc->num_rows() > 0) {
            foreach ($qrySlc->result() as $data) {
                $nilai[] = $data;
            }
        }
        $this->dbforge->drop_table('#TempTable');
        return $nilai;
    }

    function generatePvr($newid,$trcd,$bonusperiod,$username) {
        
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        //$bnsperiod = date('d/m/Y', strtotime($bonusperiod));
        $updte = "update klink_mlm2010.dbo.sc_newtrh set batchno = '$newid',batchdt = '$createdt',
                        flag_batch='1',flag_approval = '0', flag_show = '1'
                         where trcd = '$trcd'
                        and bnsperiod = '$bonusperiod' ";
        //echo $updte;
        $query = $this->db->query($updte);
        return $query;
    }

    public function updateSSR($newid, $trcd, $bonusperiod, $username) {
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $updte = "UPDATE klink_mlm2010.dbo.sc_newtrh SET batchno = '".$newid."',
             batchdt = '".$createdt."',flag_batch='1',
             updatenm = '".$username."',updatedt = '".$updatedt."'
            WHERE trcd = '".$trcd."' AND bnsperiod = '".$bonusperiod."' AND flag_batch='0'";
        //echo $updte."</br>";
        $query = $this->db->query($updte);
        return $query;
    }

    public function updateSSRV3($newid, $trcd, $bonusperiod, $username) {
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $updte = "UPDATE klink_mlm2010.dbo.sc_newtrh SET batchno = '".$newid."',
             batchdt = '".$createdt."',flag_batch='1',
             updatenm = '".$username."',updatedt = '".$updatedt."'
            WHERE bnsperiod = '".$bonusperiod."' AND flag_batch='0' AND trcd IN ($trcd)"; 
        //echo $updte."</br>";
        $query = $this->db->query($updte);
        return $query;
    }

    public function updateSSRV2($newid, $trcd, $bonusperiod, $username) {
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $updte = "UPDATE klink_mlm2010.dbo.sc_newtrh SET batchno = '".$newid."',
             batchdt = '".$createdt."',flag_batch='1',
             updatenm = '".$username."',updatedt = '".$updatedt."'
            WHERE trcd IN ($trcd) 
             AND bnsperiod = '".$bonusperiod."' 
             AND flag_batch='0'";
        //echo $updte."</br>";
        $query = $this->db->query($updte);
        return $query;
    }

    public function generate_sales_save2($newid, $trcd, $bonusperiod, $username) {
        $this->db = $this->load->database($this->setDB(2), true);
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $bnsperiod = date('d/m/Y', strtotime($bonusperiod));

        $trcds = "";
        for ($i=0;$i < count($trcd);$i++) {
            $trcds .= "'".$trcd[$i]."', ";
        }
        $cek_depo ="SELECT total_deposit- total_keluar as sisa, no_trx
                    FROM deposit_H where no_trx in (SELECT docno COLLATE SQL_Latin1_General_CP1_CI_AI
                                                    FROM sc_newtrp
                                                    WHERE trcd = '$trcd' GROUP BY docno) ";
        //echo $cek_depo."</br>";
        $query_cek = $this->db->query($cek_depo);

        if ($query_cek->result() != null) {
            foreach ($query_cek->result() as $data) {
                $ss = $data->sisa;
                if ($ss == 0) {
                    $ssr = $this->updateSSR($newid, $trcd, $bonusperiod, $username);
                    //$query = $this->db->query($updte);
                    if ($ssr > 0) {
                        $uptrep = "UPDATE deposit_H SET status = 0 WHERE no_trx IN (
                        SELECT docno COLLATE SQL_Latin1_General_CP1_CI_AI FROM sc_newtrp WHERE trcd = '".$trcd."')";
                        $query2 = $this->db->query($uptrep);
                        return 1;
                    }
                } else {
                    return 0;
                }
            }
        } else {
            return $this->updateSSR($newid, $trcd, $bonusperiod, $username);
            /* if (!$query) {
                return 0;
            } else {
                return 1;
            } */
        }
    }

    public function generate_sales_saveDepositV2($newid, $trcd, $bonusperiod, $username) {
        $this->db = $this->load->database($this->setDB(2), true);
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $bnsperiod = date('d/m/Y', strtotime($bonusperiod));

        $trcds = "";
        for ($i=0;$i < count($trcd);$i++) {
            $trcds .= "'".$trcd[$i]."', ";
        }
        $cek_depo ="SELECT a.no_deposit, SUM(b.payamt) as jml_vch_cash, 
                        c.total_deposit, c.total_keluar, c.total_deposit - c.total_keluar as sisa
                    FROM klink_mlm2010.dbo.sc_newtrh a
                    LEFT OUTER JOIN klink_mlm2010.dbo.sc_newtrp b 
                        ON (a.trcd COLLATE SQL_Latin1_General_CP1_CI_AI = b.trcd AND b.docno COLLATE SQL_Latin1_General_CP1_CI_AI = a.no_deposit)
                    LEFT OUTER JOIN deposit_H c ON (a.no_deposit = c.no_trx)
                    WHERE a.trcd IN ($trcd)
                    GROUP BY a.no_deposit, c.total_deposit, c.total_keluar";
        //echo $cek_depo."</br>";
         $query_cek = $this->db->query($cek_depo);
         $resDepo = $query_cek->result();

        if ($resDepo != null) {
            foreach ($resDepo as $data) {
                $ss = $data->sisa;
                
                $ssr = $this->updateSSRV2($newid, $trcd, $bonusperiod, $username);
                $noDeposit = $data->no_deposit;
                $uptrep = "UPDATE klink_mlm2010.dbo.deposit_H 
                                SET status = 0 WHERE no_trx = '$noDeposit'";
                //echo $uptrep;
                //echo "<br />";
                $query2 = $this->db->query($uptrep);
                //return 1;
                //}
                
            }
            return 1;
        } else {
            return $this->updateSSRV2($newid, $trcd, $bonusperiod, $username);

        } 
    }

    public function get_data_GenerateStk($newid, $bonusperiod, $username, $type = "array") {
        $bnsperiod = date('Y-m-d', strtotime($bonusperiod));


        $slc = "SELECT sc_dfno, sc_co, batchno, updatedt, loccd, SUM(tdp) AS tdp, SUM(tbv) AS tbv, CONVERT(VARCHAR(10), bnsperiod, 23) as bnsperiod FROM (
                    SELECT sc_dfno,sc_co,batchno,CONVERT(varchar(10), updatedt, 120) AS updatedt,loccd,SUM(tdp) AS tdp,SUM(tbv) AS tbv,bnsperiod
                    FROM klink_mlm2010.dbo.sc_newtrh
                    WHERE batchno IN ($newid)
                    AND bnsperiod = '".$bnsperiod."' AND createnm = '".$username."' AND flag_batch='1'
                    GROUP BY sc_dfno,updatedt,sc_co,batchno,loccd,bnsperiod)
                    sup
                GROUP BY sc_dfno, sc_co, batchno, updatedt, loccd, bnsperiod";
        //echo $slc;
        //return $this->get_recordset($slc, $type, $this->setDB(2));
        return $this->getRecordset($slc, null, $this->db2);
    }

    public function incomingPaymentVchCash($new_id, $username, $sc_co, $scdfno) {
        $arrayy = "";
        $arrayy2 = "";
        $totet = 0;
        $nomorG = $this->getIPno();
        $tipes = 'P';
        $slc="SELECT a.trcd, b.batchno, a.payamt, a.docno, a.paytype, b.tdp 
              FROM klink_mlm2010.dbo.sc_newtrp a 
              INNER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd)
              WHERE b.batchno = '$new_id'
              ORDER BY a.paytype DESC";
        $result = $this->getRecordset($slc, null, $this->db2);
        $total_vch_cash = 0;
        $total_dp = 0;
        $total_cash = 0;
        $xd = 1;
        if ($result !== null) {
            foreach($result as $dta) {
                //if voucher cash
                if($dta->paytype != "01") {
                    $total_vch_cash += $dta->payamt;

                    $insert2 = "INSERT INTO klink_mlm2010.dbo.sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$dta->paytype', $dta->payamt, '$nomorG', 'ID',
                            '0','$tipes', '$dta->docno', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username')";
                    /* echo $insert2;
                    echo "<br />"; */
                    $this->db->query($insert2);
                    $xd++;
                } else {
                    $total_cash += $dta->payamt;
                }
                $total_dp += $dta->tdp;
            }

            if($total_cash > 0) {
                $insert3 = "INSERT INTO klink_mlm2010.dbo.sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '01', $total_cash, '$nomorG', 'ID',
                            '0','$tipes', '', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username')";
                $this->db->query($insert3);
                /* echo $insert3;
                echo "<br />"; */
                $xd++;
            }

            $refno = 'VC001';
            $vo = 'CVOUCHER';
            $insertBbhdr = "INSERT INTO klink_mlm2010.dbo.bbhdr (trcd, type, trtype, bankacccd, refno,
                description, amount, etdt, trdt, status,
                dfno, createnm, createdt,  PT_SVRID, custtype)
                VALUES (
                    '$nomorG', 'I', '$vo', '$refno', '$new_id',
                    'Transaksi Vch Cash', $total_vch_cash, GETDATE(), GETDATE(), 'O',
                    '$username', '$username', GETDATE(), 'ID', 'S'
                )";
            $this->db->query($insertBbhdr);
           /*  echo $insertBbhdr;
            echo "<br />"; */

        }
        
    }

    public function incoming_paymentH($new_id, $username, $sc_co, $scdfno) {
        $arrayy = "";
        $arrayy2 = "";
        $totet = 0;
        /* $slc="SELECT SUM(payamt) as total_deposit, docno, paytype from sc_newtrp WHERE trcd IN (
                SELECT trcd from klink_mlm2010.dbo.sc_newtrh where batchno ='$new_id'
                ) AND paytype != '01'
                GROUP BY docno, paytype"; */

        $slc = "SELECT SUM(a.payamt) as total_deposit, 
                    a.docno, a.paytype 
                FROM klink_mlm2010.dbo.sc_newtrp a
                INNER JOIN klink_mlm2010.dbo.sc_newtrh b ON (a.trcd = b.trcd) 
                WHERE b.batchno ='$new_id' AND  a.paytype != '01'
                GROUP BY a.docno, a.paytype"; 
        $query = $this->db->query($slc);
        
        if ($query->result() != null) {
            foreach ($query->result() as $data) {
                $tipes = 'P';
                $vo = 'PVOUCHER';
                $refno = 'B0000';
                $ss = $data->paytype;
                if ($ss == "08") {
                    $tipes = 'C';
                    $vo = 'CVOUCHER';
                    $refno = 'VC001';
                }
                $arrayy .= "'" . $data->docno . "', ";
                $arrayy2 .= "" . $data->docno . ", ";
                $totet = $totet + $data->total_deposit;
            }
            $arrayy = substr($arrayy, 0, -2);
            $nomorG = $this->getIPno();
            $insert2 = "INSERT INTO klink_mlm2010.dbo.bbhdr (trcd, type, trtype, bankacccd, refno,
                description, amount, etdt, trdt, status,
                dfno, createnm, createdt,  PT_SVRID, custtype)
                VALUES (
                    '$nomorG', 'I', '$vo', '$refno', '($arrayy2)',
                    'Transaksi Deposit', $totet, GETDATE(), GETDATE(), 'O',
                    '$username', '$username', GETDATE(), 'ID', 'S'
                )";
            $this->db->query($insert2);
            //echo $insert2."</br>";
            $xd = 1;
            $slc2 = "SELECT d.nominal, d.voucher_scan, d.kategori, b.paytype
            FROM
            klink_mlm2010.dbo.sc_newtrh a
             LEFT JOIN klink_mlm2010.dbo.sc_newtrp b
            ON a.trcd = b.trcd
             LEFT JOIN klink_mlm2010.dbo.deposit_H c
            ON b.docno= c.no_trx COLLATE SQL_Latin1_General_CP1_CS_AS
             LEFT JOIN klink_mlm2010.dbo.deposit_D d
            ON c.no_trx=d.no_trx
            WHERE a.batchno = '$new_id' AND c.no_trx IN ($arrayy)
            GROUP BY d.nominal, d.voucher_scan, d.kategori, b.paytype";
            $query2 = $this->db->query($slc2);
            foreach ($query2->result() as $data2) {
                $tipes = 'P';
                $ss = $data2->kategori;
                if ($ss == "VC") {
                    $tipes = 'C';
                }
                $insert2 = "INSERT INTO klink_mlm2010.dbo.sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$data2->paytype', $data2->nominal, '$nomorG', 'ID',
                            '0','$tipes', '$data2->voucher_scan', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username')";
                $this->db->query($insert2);
                //echo $insert2."</br>";
                $xd++;
            }
            $slc3 = "SELECT b.paytype, SUM(B.payamt) AS nominal
            FROM
            klink_mlm2010.dbo.sc_newtrh a
             LEFT JOIN klink_mlm2010.dbo.sc_newtrp b
            ON a.trcd = b.trcd
             LEFT JOIN klink_mlm2010.dbo.deposit_H c
            ON b.docno= c.no_trx COLLATE SQL_Latin1_General_CP1_CS_AS
             LEFT JOIN klink_mlm2010.dbo.deposit_D d
            ON c.no_trx=d.no_trx
            WHERE a.batchno = '$new_id' AND B.paytype='01'
            GROUP BY  b.paytype";
            $query3 = $this->db->query($slc3);
            foreach ($query3->result() as $data3) {
                $tipes = 'P';
                $ss = $data2->kategori;

                if ($ss == "VC") {
                    $tipes = 'C';
                }

                $insert3 = "INSERT INTO klink_mlm2010.dbo.sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$data3->paytype', $data3->nominal, '$nomorG', 'ID',
                            '0','$tipes', '', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username');";
                $this->db->query($insert3);
                 //echo $insert3."</br>";
                $xd++;
            }
        }
        return true;
    }

    public function incoming_paymentV($new_id, $username, $sc_co, $scdfno) {
        $arrayy = "";
        $arrayy2 = "";
        $totet=0;
        $slc = "SELECT b.docno, b.payamt, b.paytype
                from klink_mlm2010.dbo.sc_newtrh a
                LEFT JOIN klink_mlm2010.dbo.sc_newtrp b
                on a.trcd = b.trcd
                WHERE a.batchno = '$new_id'";
        $query = $this->db->query($slc);
        foreach ($query->result() as $data) {
            $tipes='P';
            $vo='PVOUCHER';
            $bankacccd='B0000';
            $ss = $data->paytype;
            $arrayy .= "'".$data->docno."', ";
            $arrayy2 .= "".$data->docno.", ";
            $totet=$totet+$data->payamt;
        }
        $arrayy = substr($arrayy, 0, -2);
        $nomorG=$this->getIPno();
        $refno = str_replace("IPS", "VC", $nomorG);
        $refno = str_replace("/VC001", "S", $refno);

        $insert2 = "INSERT INTO klink_mlm2010.dbo.bbhdr (trcd, type, trtype, bankacccd, refno,
                description, amount, etdt, trdt, status,
                dfno, createnm, createdt,  PT_SVRID,
                custtype
                )

                VALUES (
                    '$nomorG', 'I', '$vo', '$bankacccd', '$refno',
                    'FROM APPROVAL PVR', $totet, GETDATE(), GETDATE(), 'O',
                    '$username', '$username', GETDATE(), 'ID', 'S'
                )";
        $this->db->query($insert2);
        //echo $insert2."</br>";
        $xd=1;

        $slc2="SELECT  b.docno, b.payamt, b.paytype
            FROM
            klink_mlm2010.dbo.sc_newtrh a
             LEFT JOIN klink_mlm2010.dbo.sc_newtrp b
            ON a.trcd = b.trcd
            WHERE a.batchno = '$new_id'";
        $query2 = $this->db->query($slc2);
        foreach ($query2->result() as $data2) {
            $tipes='P';
            $insert2 = "INSERT INTO klink_mlm2010.dbo.sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$data2->paytype', $data2->payamt, '$nomorG', 'ID',
                            '0','$tipes', '$data2->docno', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username');
                             ";
            $this->db->query($insert2);
            //echo $insert2."</br>";
            $xd++;
        }
        return true;
    }

    public function getDetItemV2($dari,$ke,$sc_dfno,$idstkk,$bnsperiod, $tipe, $type = "array") {
        $froms = date('Y-m-d', strtotime($dari));
        $tos = date('Y-m-d', strtotime($ke));

        $bns = explode("/", $bnsperiod);
        $bns1 = $bns[1]."-".$bns[0]."-01";

        $slc = "SELECT c.prdnm, (a.bv) as bv, (a.dp) as dp,a.prdcd, SUM(a.qtyord) as qty
            FROM sc_newtrd a
            LEFT JOIN ALDI_22022018V2 b
            on a.trcd=b.trcd
            LEFT JOIN msprd c
            on a.prdcd=c.prdcd
            WHERE
            b.loccd ='$idstkk'
            AND
            b.bnsperiod= '$bns1'
            AND
            b.etdt
            BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
            AND
            b.tipe = '$tipe'
             AND
            b.sc_dfno = '$sc_dfno'
            GROUP BY c.prdnm,  a.prdcd, a.dp,a.bv";
        //echo $slc;
        return $this->get_recordset($slc, $type, $this->setDB(2));
    }

    public function getRekapProduk($dari,$ke,$sc_dfno,$idstkk,$bnsperiod, $tipe, $type = "array") {
        $froms = date('Y-m-d', strtotime($dari));
        $tos = date('Y-m-d', strtotime($ke));

        $bns = explode("/", $bnsperiod);
        $bns1 = $bns[1]."-".$bns[0]."-01";

        $qry = "SELECT a.prdcd, c.prdnm, SUM(a.qtyord) as total_qty, 
                    d.dp, d.bv,
                    SUM(a.qtyord) * d.dp as total_dp,
                    SUM(a.qtyord) * d.bv as total_bv
                FROM sc_newtrd a
                INNER JOIN ALDI_22022018V2 b ON (a.trcd = b.trcd)
                LEFT OUTER JOIN msprd c ON (a.prdcd = c.prdcd)
                LEFT OUTER JOIN pricetab d 
                    ON (a.prdcd = d.prdcd AND b.pricecode = d.pricecode)
                WHERE b.loccd ='$idstkk' AND b.bnsperiod= '$bns1'
                    AND b.etdt BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
                    AND b.tipe = '$tipe' AND b.sc_dfno = '$sc_dfno'
                GROUP BY a.prdcd, c.prdnm, d.dp, d.bv";
        //echo $qry;
        return $this->get_recordset($qry, $type, $this->setDB(2));
    }

    public function getDetItem($dari, $ke, $username, $bnsperiod, $x, $type = "array") {
        $m = date('m');
        $y = date('Y');
        //$username = $this->stockist;
        $froms = date('Y-m-d', strtotime($dari));
        $tos = date('Y-m-d', strtotime($ke));

        /* $folderGets = explode('/', $bnsperiod);
        echo '<pre>';
        print_r($folderGets);
        echo '</pre>';
        $data['month'] = $folderGets[1];
        $data['year'] = $folderGets[2];
        $bonusperiod = $data['year']."-".$data['month']."-"."01"; */


        $arrayy = "";
        $arraysep = "";
        for ($i=0;$i < count($x);$i++) {
            $pieces = explode("|", $x[$i]);
            $arrayy .= "'".$pieces[0]."', ";
            $arraysep .= "'".$pieces[1]."', ";
        }
        $arrayy = substr($arrayy, 0, -2);
        $arraysep = substr($arraysep, 0, -2);

        $slc = "SELECT c.prdnm, (a.bv) as bv, (a.dp) as dp,a.prdcd, SUM(a.qtyord) as qty
            FROM sc_newtrd a
            LEFT JOIN ALDI_22022018V2 b
            on a.trcd=b.trcd
            LEFT JOIN msprd c
            on a.prdcd=c.prdcd
            WHERE
            b.loccd ='$username'
            AND
            b.bnsperiod= '$bnsperiod'
            AND
            b.etdt
            BETWEEN '$froms 00:00:00' AND '$tos 23:59:59'
            AND
            b.tipe in ($arrayy)
             AND
            b.sc_dfno in ($arraysep)
            GROUP BY c.prdnm,  a.prdcd, a.dp,a.bv";
        //echo $slc;
        return $this->get_recordset($slc, $type, $this->setDB(2));
    }

    public function getDetItem2($dari, $ke, $username, $bnsperiod, $x, $type = "array") {
        $m = date('m');
        $y = date('Y');
        //$username = $this->stockist;
        $froms = date('Y-m-d', strtotime($dari));
        $tos = date('Y-m-d', strtotime($ke));

        $arrayy = "";
        $arraysep = "";
        for ($i=0;$i < count($x);$i++) {
            $pieces = explode("|", $x[$i]);
            $arrayy .= "'".$pieces[0]."', ";
            $arraysep .= "'".$pieces[1]."', ";
        }
        $arrayy = substr($arrayy, 0, -2);
        $arraysep = substr($arraysep, 0, -2);

        $slc = "SELECT trcd, sc_dfno, sc_co, tipe
                FROM ALDI_22022018V2 b
                WHERE b.loccd ='$username'
                AND b.bnsperiod= '$bnsperiod'
                AND b.etdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59'
                AND b.tipe in ($arrayy)
                AND b.sc_dfno in ($arraysep)";
        return $this->get_recordset($slc, $type, $this->setDB(2));
    }

    public function getDetMLM($dari, $ke, $username, $bnsperiod, $x, $type = "array") {
        $m = date('m');
        $y = date('Y');
        //$username = $this->stockist;
        $froms = date('Y-m-d', strtotime($dari));
        $tos = date('Y-m-d', strtotime($ke));

        $arrayy = "";
        $arraysep = "";
        for ($i=0;$i < count($x);$i++) {
            $pieces = explode("|", $x[$i]);
            $arrayy .= "'".$pieces[0]."', ";
            $arraysep .= "'".$pieces[1]."', ";
        }
        $arrayy = substr($arrayy, 0, -2);
        $arraysep = substr($arraysep, 0, -2);

        $slc = "SELECT A.sc_dfno, A.sc_co, A.loccd, tipe, SUM(totpay) AS totpay, bnsperiod, SUM(tbv) AS tbv
                FROM ALDI_22022018V2 a WHERE a.loccd ='$username'
                AND a.bnsperiod= '$bnsperiod'
                AND a.etdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59'
                AND a.tipe in ($arrayy)
                AND a.sc_dfno in ($arraysep)
                GROUP BY A.sc_dfno, A.sc_co, A.loccd, tipe, bnsperiod
                ORDER BY A.sc_dfno, A.sc_co, A.loccd";
        return $this->get_recordset($slc, $type, $this->setDB(2));
    }

    function generate_sales_saveMS2($newid,$scdfno,$bonusperiod,$username){
        $this->db = $this->load->database($this->setDB(2), true);
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        // $bnsperiod = date('d/m/Y', strtotime($bonusperiod));

        $updte = "UPDATE sc_newtrh set batchno = '".$newid."',
                        batchdt = '".$createdt."',flag_batch='1',updatenm = '".$username."',updatedt = '".$updatedt."'
                        WHERE trcd = '".$scdfno."' AND createnm = '".$username."'
                        AND bnsperiod = '".$bonusperiod."' AND flag_batch='0' ";
        //echo "query ".$updte."<br>";
         $query = $this->db->query($updte);

        $uptrep="UPDATE deposit_H SET status = 0 WHERE no_trx IN (
                  SELECT docno COLLATE SQL_Latin1_General_CP1_CI_AI FROM sc_newtrp WHERE trcd = '".$scdfno."')";
        $query2 = $this->db->query($uptrep);
        // echo "query ".$uptrep."<br>";
        if(!$query || !$query) {
            return 0;
        } else {
            return 1;
        }
    }

    function generate_sales_saveSub2($newid,$scdfno,$bonusperiod,$username){
        $this->db = $this->load->database($this->setDB(2), true);
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $bnsperiod = date('d/m/Y', strtotime($bonusperiod));

        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[2];
        $bonusperiods = $data['month']."/"."1"."/".$data['year'];
        $updte = "UPDATE sc_newtrh set batchno = '".$newid."',
                        batchdt = '".$createdt."',flag_batch='1',updatenm = '".$username."',updatedt = '".$updatedt."'
                        WHERE trcd = '".$scdfno."'
                         AND flag_batch='0'";
        echo "query ".$updte."<br>";
        // $query = $this->db->query($updte);

        if(!$query) {
            return 0;
        } else {
            return 1;
        }
    }

    function updateBonusPeriod($trcd, $bonusperiod)
    {
      $this->db = $this->load->database($this->setDB(2), true);

      $query = "UPDATE sc_newtrh SET bnsperiod = '$bonusperiod 00:00:00' WHERE trcd IN ($trcd)";

      $update = $this->db->query($query);

      return (!$update ? 0 : 1);
    }

    function summaryProductBySSR($idstk, $bnsperiod, $searchBy, $scco, $scdfno, $dari, $ke) {
        $username = $this->session->userdata('username');
        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."01";

		$qry = "SELECT a.prdcd, c.prdnm, d.pricecode,
					SUM(a.qtyord) as total_qty, d.dp, d.bv,
					SUM(a.qtyord * d.dp) as total_dp,
					SUM(a.qtyord * d.bv) as total_bv
				FROM sc_newtrd a
				LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd)
				LEFT OUTER JOIN msprd c ON (a.prdcd = c.prdcd)
				LEFT OUTER JOIN pricetab d ON (a.prdcd = d.prdcd AND b.pricecode = d.pricecode)
				WHERE bnsperiod = ? 
                AND A.loccd = ?
                AND sc_co = ? 
                AND sc_dfno = ? AND tipe = ? 
                AND CONVERT(VARCHAR(10), a.etdt, 120) BETWEEN ? AND ?
				GROUP BY a.prdcd, c.prdnm, d.pricecode, d.dp, d.bv";
		$qryParam = array($bonusperiod, $idstk, $scco, $scdfno, $searchBy, $dari, $ke);
        echo $qry."<br />";
        echo "<pre>";
        print_r($qryParam);
        echo "</pre>";
        //return $this->getRecordset($qry, $qryParam, $this->db2);
	}
}
