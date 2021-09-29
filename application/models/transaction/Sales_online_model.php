<?php
class Sales_online_model extends MY_Model {

	function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

	function getBnsMonth($user) {
        $view = "SELECT bonusmonth
                FROM webol_trans_ok WHERE idstk = '$user'
                GROUP BY bonusmonth
				ORDER BY CAST(RIGHT(bonusmonth,4) AS INTEGER) DESC, CAST(LEFT(bonusmonth,2) AS INTEGER) DESC ";
        return $this->getRecordset($view, null, $this->db2);
    }

    function getBnsMonthV2($user) {
        $view = "select bonusmonth
                from webol_trans_ok where idstk = '$user' and sentTo = '0' 
                group by bonusmonth
                order by CAST(RIGHT(bonusmonth,4) AS INTEGER) DESC, 
                CAST(LEFT(bonusmonth,2) AS INTEGER) DESC ";
        return $this->getRecordset($view, null, $this->db2);
    }

	function getListOnlineTrx($idstk,$bonusmonth,$tipe) {
        //$this->db = $this->load->database('alternate', true);
        if($tipe == 'xx')
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."'";
        }
        elseif($tipe == '1')
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."' and a.status = '".$tipe."'";
        }
        else
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."' and a.status = '".$tipe."'";
        }

        $rr = "SELECT a.orderno,a.id_memb,a.nmmember,a.flag_trx,
                    a.total_pay,a.bonusmonth,a.idstk,a.nmstkk,a.status,
                    b.CNno, b.KWno, 
                    CONVERT(VARCHAR(10), b.datetrans, 126) as datetrans,
                    c.no_do as do_no, c.do_date as do_date,
                    c.do_createby as do_createnm,
                    c.no_resi
                FROM webol_trans_ok a
                INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr b 
                ON (a.orderno COLLATE SQL_Latin1_General_CP1_CS_AS = b.orderno)
                LEFT OUTER JOIN klink_mlm2010.dbo.DO_NINGSIH c
                ON (b.KWno COLLATE SQL_Latin1_General_CP1_CS_AS = c.no_kwitansi)
                WHERE $zx and a.sentto = '0' ORDER BY bonusmonth";
        if($this->username == "BID06") {
            echo "<pre>";
            echo $rr;
            echo "</pre>";  
        }
        
        //echo "<br>";
        return $this->getRecordset($rr, null, $this->db2);
    }

    function getListOnlineTrxV2($data) {
        $tipe = $data['searchs'];
        $idstk = $data['kodestk'];
        $bonusmonth = $data['bnsmonth'];
        $from = $data['from'];
        $to = $data['to'];
        if($tipe == 'xx')
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."'";
        }
        elseif($tipe == '1')
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."' and a.status = '".$tipe."'";
        }
        else
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."' and a.status = '".$tipe."'";
        }

        if($from !== "" && $from !== "") {
            $zx .= " AND CONVERT(VARCHAR(10), b.datetrans, 126) BETWEEN '$from' AND '$to'";    
        }

        $rr = "SELECT a.orderno,a.id_memb,a.nmmember,a.flag_trx,
                    a.total_pay,a.bonusmonth,a.idstk,a.nmstkk,a.status,
                    b.CNno, b.KWno, 
                    CONVERT(VARCHAR(10), b.datetrans, 126) as datetrans,
                    c.no_do as do_no, c.do_date as do_date,
                    c.do_createby as do_createnm,
                    c.no_resi
                FROM webol_trans_ok a
                INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr b 
                ON (a.orderno COLLATE SQL_Latin1_General_CP1_CS_AS = b.orderno)
                LEFT OUTER JOIN klink_mlm2010.dbo.DO_NINGSIH c
                ON (b.KWno COLLATE SQL_Latin1_General_CP1_CS_AS = c.no_kwitansi)
                WHERE $zx and a.sentto = '0' ORDER BY bonusmonth";
        if($this->username == "BID06") {
            echo "<pre>";
            echo $rr;
            echo "</pre>";  
        }
        
        //echo "<br>";
        return $this->getRecordset($rr, null, $this->db2);
    }

    function olTrxDetailPrd($orderno) {

        $view = "SELECT orderno,prdcd,prdnm,qty,bvr,dpr, pricecode
                FROM webol_det_prod WHERE orderno = '$orderno' ORDER BY prdcd";
        // echo $view;
        return $this->getRecordset($view, null, $this->db2);
    }

	function olTrxHeader($orderno) {
        $rr =  "SELECT a.orderno,a.id_memb,a.nmmember,a.total_pay,c.datetrans,
                        a.total_bv,a.bonusmonth,a.idstk,a.nmstkk ,b.tel_hp,
                        c.userlogin as usr_login,
                        b.fullnm as nmsponsor, d.KWno, d.SSRno, e.tgl_ambil
                FROM klink_mlm2010.dbo.webol_trans_ok a
                    INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr d ON (a.orderno COLLATE SQL_Latin1_General_CP1_CS_AS = d.orderno)
                    INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr_sgo c on d.token = c.orderno
                    LEFT OUTER JOIN klink_mlm2010.dbo.msmemb
                        b on (c.userlogin = b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS)
                    LEFT OUTER JOIN klink_mlm2010.dbo.webol_logs_stockist e ON (a.orderno = e.orderno)
                WHERE a.orderno = '$orderno'
                and (b.dfno = c.userlogin COLLATE SQL_Latin1_General_CP1_CS_AS)";
        // echo $rr;
        return $this->getRecordset($rr, null, $this->db2);
    }

    function olTrxReport($idstk, $bonusmonth, $status) {
        $query = "SELECT a.orderno, a.id_memb, a.nmmember, a.flag_trx,
                        a.total_pay, a.bonusmonth, a.idstk, a.nmstkk,
                        a.status, b.CNno, b.KWno, c.GDO as do_no,
                        CONVERT(VARCHAR(10), c.etdt, 126) as do_date,
                        c.createnm as do_createnm,
                        CONVERT(VARCHAR(10), b.datetrans, 126) as datetrans,
                        d.prdcd, d.prdnm, d.qty, d.bvr, d.dpr, d.pricecode
                FROM klink_mlm2010.dbo.webol_trans_ok a
                INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr b ON (a.orderno = b.orderno)
                LEFT OUTER JOIN dbo.webol_det_prod d ON (b.orderno = d.orderno)
                LEFT OUTER JOIN klink_mlm2010.dbo.intrh c ON (b.KWno COLLATE SQL_Latin1_General_CP1_CS_AS = c.applyto)
                WHERE a.idstk = '$idstk'
                and a.bonusmonth = '$bonusmonth'
                and a.status = '$status'
                and a.sentto = '0'
                ORDER BY b.KWno";
        return $this->getRecordset($query, null, $this->db2);
    }

    function olTrxReportV2($data) {
        $tipe = $data['searchs'];
        $idstk = $data['kodestk'];
        $bonusmonth = $data['bnsmonth'];
        $from = $data['from'];
        $to = $data['to'];
        if($tipe == 'xx')
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."'";
        }
        elseif($tipe == '1')
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."' and a.status = '".$tipe."'";
        }
        else
        {
            $zx = "a.idstk = '".$idstk."' and a.bonusmonth = '".$bonusmonth."' and a.status = '".$tipe."'";
        }

        if($from !== "" && $from !== "") {
            $zx .= " AND CONVERT(VARCHAR(10), b.datetrans, 126) BETWEEN '$from' AND '$to'";    
        }

        $query = "SELECT a.orderno, a.id_memb, a.nmmember, a.flag_trx,
                        a.total_pay, a.bonusmonth, a.idstk, a.nmstkk,
                        a.status, b.CNno, b.KWno, c.GDO as do_no,
                        CONVERT(VARCHAR(10), c.etdt, 126) as do_date,
                        c.createnm as do_createnm,
                        CONVERT(VARCHAR(10), b.datetrans, 126) as datetrans,
                        d.prdcd, d.prdnm, d.qty, d.bvr, d.dpr, d.pricecode
                FROM klink_mlm2010.dbo.webol_trans_ok a
                INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr b ON (a.orderno = b.orderno)
                LEFT OUTER JOIN dbo.webol_det_prod d ON (b.orderno = d.orderno)
                LEFT OUTER JOIN klink_mlm2010.dbo.intrh c ON (b.KWno COLLATE SQL_Latin1_General_CP1_CS_AS = c.applyto)
                WHERE $zx 
                and a.sentto = '0'
                ORDER BY b.KWno";
        return $this->getRecordset($query, null, $this->db2);
    }   

    function productRecap($idstk, $bonusmonth, $status) {
        $query = "SELECT d.prdcd, d.prdnm, SUM(d.qty) as qty
                FROM klink_mlm2010.dbo.webol_trans_ok a
                INNER JOIN db_ecommerce.dbo.ecomm_trans_hdr b ON (a.orderno = b.orderno)
                LEFT OUTER JOIN dbo.webol_det_prod d ON (b.orderno = d.orderno)
                LEFT OUTER JOIN klink_mlm2010.dbo.intrh c ON (b.KWno COLLATE SQL_Latin1_General_CP1_CS_AS = c.applyto)
                WHERE a.idstk = '$idstk'
                and a.bonusmonth = '$bonusmonth'
                and a.status = '$status'
                and a.sentto = '0'
                GROUP BY d.prdcd, d.prdnm
                ORDER BY d.prdnm";
        return $this->getRecordset($query, null, $this->db2);
    }

    function olTrxReportDetail($orderno) {
        $query = "SELECT orderno, prdcd, prdnm, qty, bvr, dpr, pricecode FROM webol_det_prod
                    WHERE orderno = '$orderno'
                    ORDER BY prdcd";
        return $this->getRecordset($query, null, $this->db2);
    }

	function show_detail_product_promo($prdcd, $qty, $pricecode,$dp)
    {
        //$this->db = $this->load->database('alternate', true);

        $subs = substr($prdcd,0,1);
        $a = str_split($subs);
        //echo  $prdcd;
        //print_r($a);
        //echo "sdsds ".$subs;

        if(in_array('3',$a))
        {
            $sql = "SELECT
                      j.prdcddet AS prdcd,
                      '(*) ' + j.prdcdNmDet AS prdnm,
                      j.qty * $qty AS qty,
                      (h.dp * 0) + ($dp * $qty) AS totdp,
                      0 AS dp,
                      h.bv,
                      h.bv * $qty as totbv

                    FROM
                      newera_PRDDET j
                      INNER JOIN pricetab h ON (j.prdcdCat = h.prdcd)
                      AND (h.pricecode = '$pricecode')
                    WHERE
                      (j.prdcdCat = '$prdcd')
                    ORDER BY
                      j.prdcdCat";
            //echo $sql;
        }
        else
        {
            //original FROM ana
		    $sql = "SELECT j.prdcdDet as prdcd,
		    			   j.prdcdNmDet as prdnm,
		    			   j.qty * $qty as qty,
                		   (h.dp * j.qty) * $qty as totdp,
		     			   h.dp,
		    			   h.bv,
		    			   (h.bv * j.qty) * $qty as totbv
                FROM newera_PRDDET j inner JOIN pricetab h
                ON j.prdcdDet = h.prdcd and h.pricecode = '$pricecode'
                WHERE j.prdcdCat = '$prdcd'
                ORDER BY j.prdcdCat";
		    /*
		    //edit hilal @2017-02-02
		    $sql="SELECT A.prdcd,
		    			 A.prdnm,
		    			 $qty as qty,
		    			 (B.dp * $qty) as totdp,
		    			 B.dp,
		    			 B.bv,
		    			 (B.bv * $qty) as totbv
					FROM msprd A
						 INNER JOIN pricetab B ON A.prdcd=B.prdcd
					WHERE A.prdcd='$prdcd' AND B.pricecode='$pricecode'";
		    */
        }
         //echo $sql;
         //echo "<br>";
        $hasil = $this->getRecordset($sql, null, $this->db2);
        return $hasil;
    }

	function show_grouping_product_promo($ins, $unik,$orderno)
    {
        //$this->db = $this->load->database('alternate', true);
        $query = $this->executeQuery($ins, $this->db2);

        /*$qry = "SELECT prdcd, prdnm, SUM(qty) AS qty, SUM(dp) AS total_dp, SUM(bv) AS total_bv
                FROM WEB_SIS_TEMP_STOCKIST
                WHERE session_id = '".$unik."'
                GROUP BY prdcd, prdnm";*/
        $qry = "SELECT * FROM WEB_SIS_TEMP_STOCKIST
                WHERE session_id = '".$unik."' and idmember = '$orderno'
                ORDER BY idmember,session_id";
        //echo "dhfdf ".$qry;
        return $this->getRecordset($qry, null, $this->db2);
    }

    function delete_temp_WEB_SIS_TEMP_STOCKIST($unik)
    {
      $del = "DELETE FROM WEB_SIS_TEMP_STOCKIST
                WHERE session_id = '".$unik."'";
      $del = $this->executeQuery($del, $this->db2);
    }

	function logstk($secno, $orderno)
    {
        //$this->db = $this->load->database('alternate', true);

        date_default_timezone_set("Asia/Jakarta");
        $tgl_updt = date("Y-m-d H:i:s ");
        $cek = $this->input->post('check');
        //$orderno = $this->input->post('orderno');
        $jumlah=count($cek);

        $cek = "SELECT orderno,secno,status,idstk FROM webol_trans_ok
                WHERE secno = '$secno' and orderno = '$orderno'";
        //echo $cek;
        $res = $this->getRecordset($cek, null, $this->db2);
        //print_r($query);
        if($res != null)
        {
            //$rows = $query->row();
            $secnum = $res[0]->secno;
            $statuss = $res[0]->status;
            $stk = $res[0]->idstk;
            //echo "Secnum : ".$secnum. " secno : ".$secno;
            if($secnum !== $secno)
            {
                echo "<script>alert('Kode Security Salah')</script>";
            }
            elseif($statuss == '1')
            {
                echo "<script>alert('Data Sudah Di Cetak')</script>";
            }
            else
            {

                $insert = "insert into webol_logs_stockist (orderno,userlogin,tgl_ambil)
                            values('$orderno','$stk','$tgl_updt')";
                //echo $insert;
                $query = $this->executeQuery($insert, $this->db2);

                $update = "update webol_trans_ok set status = 1
                            WHERE orderno = '$orderno'";
				//echo $update;
                $query = $this->executeQuery($update, $this->db2);
            }
            return $res;
        }

    }

    function countPrdcd($orderno,$username){
        $qry = "SELECT COUNT(*) AS TOTREC
                FROM webol_det_prod A
                inner join webol_trans_ok c on a.orderno = c.orderno COLLATE SQL_Latin1_General_CP1_CI_AS
                WHERE A.orderno = '".$orderno."' AND c.idstk = '".$username."' and
	            A.prdcd COLLATE SQL_Latin1_General_CP1_CI_AS NOT IN
                (SELECT B.prdcd FROM newera_PRDCAT_REJECT B)";
         return $this->getRecordset($qry, null, $this->db2);
    }
}