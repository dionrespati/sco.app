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

    public function getGenerateByStk($x) {
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
                $usertype = "and a.loccd = '".$x['mainstk']."' and a.tipe='MSR' ";
            } else {
                $usertype = "and a.tipe='MSR'  ";
            }
        } elseif ($x['searchs'] == "pvr") {
            $usertype = "and a.sc_dfno = '".$x['mainstk']."' and a.tipe = 'PVR' ";
        }    

        $folderGets = explode('/', $x['bnsperiod']);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['month']."/"."1"."/".$data['year'];

        $slc = "SELECT A.sc_dfno, A.sc_co, 
                    b.fullnm as scdfno, A.loccd,
                    c.fullnm as scco, tipe, 
                    SUM(totpay) as totpay, 
                    bnsperiod, 
                    SUM(tbv) as tbv, 
                    SUM(ISNULL(d.payamt, A.totpay)) as cash, 
                    SUM(ISNULL(e.payamt, 0)) as vcash,
                    SUM(ISNULL(f.payamt, 0)) as pvch 
                FROM ALDI_22022018 A 
                LEFT join mssc B ON a.sc_dfno =b.loccd 
                LEFT JOIN mssc C ON a.sc_co=c.loccd 
                LEFT JOIN sc_newtrp D ON a.trcd=d.trcd AND d.paytype='01' 
                LEFT JOIN sc_newtrp E ON a.trcd=E.trcd AND E.paytype='08' 
                LEFT JOIN sc_newtrp F ON a.trcd=F.trcd AND F.paytype='10'
                WHERE A.loccd = '".$x['mainstk']."'
                AND bnsperiod= '$bonusperiod'
                AND etdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59' $usertype
                GROUP BY A.sc_dfno, A.sc_co, b.fullnm , A.loccd,c.fullnm , tipe, bnsperiod ";
        //echo $slc;
        return $this->getRecordset($slc, null, $this->db2);
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
        echo $qry;
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
        echo $slc;
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
        $slc = "SELECT a.trcd,a.totpay,a.createdt,a.dfno,a.sc_dfno,a.nbv,a.bnsperiod,b.fullnm
                FROM sc_newtrh a
                INNER JOIN klink_mlm2010.dbo.msmemb b ON a.dfno = b.dfno
                WHERE a.sc_dfno = '".$scdfno."' AND a.sc_co = '".$scco."'
                AND a.createnm = '".$this->stockist."' AND a.bnsperiod = '".$bnsperiod."' AND a.batchno IS NULL";
        return $this->getRecordset($slc, null, $this->db1);
    }

    public function getDetailTrx($idstk, $bnsperiod, $searchBy, $scco, $scdfno) {
        $username = $this->session->userdata('username');
        $folderGets = explode('/', $bnsperiod);
        $data['month'] = $folderGets[0];
        $data['year'] = $folderGets[1];
        $bonusperiod = $data['year']."-".$data['month']."-"."01";
        $slc = " SELECT * FROM ALDI_22022018 A
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
                a.dfno, a.fullnm, a.totpay, a.tbv, a.sc_dfno, a.loccd
                FROM klink_mlm2010.dbo.ALDI_22022018 A
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
        
        $prd = "SELECT prdcd";

        $trp = "SELECT a.trcd, b.dfno, b.orderno, a.paytype, a.docno, a.payamt 
                FROM sc_newtrp a
                LEFT OUTER JOIN sc_newtrh b ON (a.trcd = b.trcd) 
                WHERE a.trcd IN ($trcd) ";
        //echo $trp;
        $payment = $this->getRecordset($trp, null, $this->db2);
        $arr = array(
            "header" => $nilai,
            "payment" => $payment
        );
        return $arr;
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

    public function updateSSR($newid, $trcd, $bonusperiod, $username) {
        $createdt = date('Y-m-d');
        $updatedt = date('Y-m-d H:i:s');

        $updte = "UPDATE klink_mlm2010.dbo.sc_newtrh SET batchno = '".$newid."',
            batchdt = '".$createdt."',flag_batch='1',updatenm = '".$username."',updatedt = '".$updatedt."'
            WHERE trcd = '".$trcd."' AND createnm = '".$username."'
            AND bnsperiod = '".$bonusperiod."' AND flag_batch='0'";
        //echo $updte."</br>";
        $query = $this->db->query($updte);
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
                if ($ss==0) {
                    $this->updateSSR($newid, $trcd, $bonusperiod, $username);
                    //$query = $this->db->query($updte);
                    $uptrep="UPDATE deposit_H SET status = 0 WHERE no_trx IN (
                  SELECT docno COLLATE SQL_Latin1_General_CP1_CI_AI FROM sc_newtrp WHERE trcd = '".$trcd."')";
                    // $query2 = $this->db->query($uptrep);
                    echo $query2."</br>";
                   /*  if (!$query) {
                        return 0;
                    } else {
                        return 1;
                    } */
                } else {
                    return 0;
                }
            }
        } else {
            $this->updateSSR($newid, $trcd, $bonusperiod, $username);
            /* if (!$query) {
                return 0;
            } else {
                return 1;
            } */
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

    public function incoming_paymentH($new_id, $username, $sc_co, $scdfno) {
        $arrayy = "";
        $arrayy2 = "";
        $totet = 0;
        $slc="SELECT SUM(payamt) as total_deposit, docno, paytype from sc_newtrp WHERE trcd IN (
        SELECT trcd from sc_newtrh where batchno ='$new_id'
        ) AND paytype != '01'
         GROUP BY docno, paytype";
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
            $insert2 = "INSERT INTO bbhdr (trcd, type, trtype, bankacccd, refno,
                description, amount, etdt, trdt, status,
                dfno, createnm, createdt,  PT_SVRID, custtype)
                VALUES (
                    '$nomorG', 'I', '$vo', '$refno', '($arrayy2)',
                    'Transaksi Deposit', $totet, GETDATE(), GETDATE(), 'O',
                    '$username', '$username', GETDATE(), 'ID', 'S'
                )";
            // $this->db->query($insert2);
            echo $insert2."</br>";
            $xd = 1;
            $slc2 = "SELECT d.nominal, d.voucher_scan, d.kategori, b.paytype
            FROM
             sc_newtrh a
             LEFT JOIN sc_newtrp b
            ON a.trcd = b.trcd
             LEFT JOIN deposit_H c
            ON b.docno= c.no_trx COLLATE SQL_Latin1_General_CP1_CS_AS
             LEFT JOIN deposit_D d
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
                $insert2 = "INSERT INTO sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$data2->paytype', $data2->nominal, '$nomorG', 'ID',
                            '0','$tipes', '$data2->voucher_scan', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username')";
                // $this->db->query($insert2);
                echo $insert2."</br>";
                $xd++;
            }
            $slc3 = "SELECT b.paytype, SUM(B.payamt) AS nominal
            FROM
             sc_newtrh a
             LEFT JOIN sc_newtrp b
            ON a.trcd = b.trcd
             LEFT JOIN deposit_H c
            ON b.docno= c.no_trx COLLATE SQL_Latin1_General_CP1_CS_AS
             LEFT JOIN deposit_D d
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

                $insert3 = "INSERT INTO sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$data3->paytype', $data3->nominal, '$nomorG', 'ID',
                            '0','$tipes', '', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username');";
                // $this->db->query($insert3);
                echo $insert3."</br>";
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
                from sc_newtrh a
                LEFT JOIN sc_newtrp b
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

        $insert2 = "INSERT INTO bbhdr (trcd, type, trtype, bankacccd, refno,
                description, amount, etdt, trdt, status,
                dfno, createnm, createdt,  PT_SVRID,
                custtype
                )

                VALUES (
                    '$nomorG', 'I', '$vo', '$bankacccd', '$refno',
                    'FROM APPROVAL PVR', $totet, GETDATE(), GETDATE(), 'O',
                    '$username', '$username', GETDATE(), 'ID', 'S'
                )";
        // $this->db->query($insert2);
        echo $insert2."</br>";
        $xd=1;

        $slc2="SELECT  b.docno, b.payamt, b.paytype
            FROM
             sc_newtrh a
             LEFT JOIN sc_newtrp b
            ON a.trcd = b.trcd
            WHERE a.batchno = '$new_id'";
        $query2 = $this->db->query($slc2);
        foreach ($query2->result() as $data2) {
            $tipes='P';
            $insert2 = "INSERT INTO sc_newtrp_vc_det (trcd, seqno, paytype, payamt, trcd2, PT_SVRID,
                            voucher, vchtype,
                            vhcno, status, sc_co, sc_dfno, trxdt, createnm)
                            VALUES
                            (
                            '$new_id', '$xd', '$data2->paytype', $data2->payamt, '$nomorG', 'ID',
                            '0','$tipes', '$data2->docno', '1',
                            '$sc_co', '$scdfno', GETDATE(), '$username');
                             ";
            // $this->db->query($insert2);
            echo $insert2."</br>";
            $xd++;
        }
        return true;
    }

    public function getDetItem($dari, $ke, $idstkk, $bnsperiod, $x, $type = "array") {
        $m = date('m');
        $y = date('Y');
        $username = $this->stockist;
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
            LEFT JOIN ALDI_22022018 b
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
        return $this->get_recordset($slc, $type, $this->setDB(2));
    }

    public function getDetItem2($dari, $ke, $idstkk, $bnsperiod, $x, $type = "array") {
        $m = date('m');
        $y = date('Y');
        $username = $this->stockist;
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
                FROM ALDI_22022018 b
                WHERE b.loccd ='$username'
                AND b.bnsperiod= '$bnsperiod'
                AND b.etdt BETWEEN  '$froms 00:00:00' AND '$tos 23:59:59'
                AND b.tipe in ($arrayy)
                AND b.sc_dfno in ($arraysep)";
        return $this->get_recordset($slc, $type, $this->setDB(2));
    }

    public function getDetMLM($dari, $ke, $idstkk, $bnsperiod, $x, $type = "array") {
        $m = date('m');
        $y = date('Y');
        $username = $this->stockist;
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
                FROM ALDI_22022018 a WHERE a.loccd ='$username'
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
}
