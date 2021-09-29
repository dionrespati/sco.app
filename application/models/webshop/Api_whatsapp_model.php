<?php
class Api_whatsapp_model extends MY_Model {
	function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getInfoBv($arrParam = null) {
        $str = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "a.status_send_wa != '1'";
                } else {
                    $str .= "a.status_send_wa = '1'";
                }
            }

            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    
        }   

        /* $qry = "SELECT a.id, a.kepada, 'PBV:' +  a.PBV + ' / PGBV:' + a.PGBV as info_bv, a.GBV, a.note, a.hp
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        $qry = "SELECT a.id,a.kepada, a.hp,
                 CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                 a.PBV, a.GBV, a.PGBV, a.LGBV, note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str";        
        //echo $qry;
        $hasil = $this->getRecordset($qry, null, $this->db2);
        return $hasil;
    }

    function getInfoBvArray($arrParam = null) {
        $str = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "a.status_send_wa != '1'";
                } else {
                    $str .= "a.status_send_wa = '1'";
                }
            }

            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    
        }   

        /* $qry = "SELECT a.id, a.kepada, 'PBV:' +  a.PBV + ' / PGBV:' + a.PGBV as info_bv, a.GBV, a.note, a.hp
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        /* $qry = "SELECT a.id, a.kepada, a.hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, a.LGBV, note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        /* $qry = "SELECT TOP 1 a.id, a.kepada, '087780441874' as hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, a.LGBV, LEN(CAST(a.LGBV as varchar(max))) as note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */
        //CASE WHEN a.note is null OR a.note = '' THEN '-' ELSE a.note END AS note
        $tgl = date("d/m/Y");
        $qry = "SELECT TOP 50 a.id, a.kepada, 
                    a.hp, 
                CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period, 
                CONVERT(VARCHAR(16), a.createdt, 103) as tgl_kirim,
                CASE WHEN a.PBV is null OR a.PBV = '' THEN '-' ELSE a.PBV END AS PBV, 
                CASE WHEN a.GBV is null OR a.GBV = '' THEN '-' ELSE a.GBV END AS GBV,
                CASE WHEN a.PGBV is null OR a.PGBV = '' THEN '-' ELSE a.PGBV END AS PGBV,
                CASE WHEN a.LGBV is null OR a.LGBV = '' THEN '-' ELSE a.LGBV END AS LGBV, 
                CASE WHEN a.note is null OR a.note = '' THEN '-' ELSE a.note END AS note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE a.hp is not null AND a.hp != '' AND $str";
        //echo $qry;
        /* $qry = "SELECT 	a.id, a.kepada, a.hp,
                        CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                        a.PBV, a.GBV, a.PGBV, 
                        LTRIM(RTRIM((SUBSTRING(CAST(a.LGBV as varchar(max)), 1, CHARINDEX('leg6', lower(CAST(a.LGBV as varchar(max)))))))) as LGBV, 
                        note
                FROM wa_info_bv_detail a
                where lower(CAST(a.LGBV as varchar(max))) like '%leg6%' AND $str"; */
        //echo $qry;
        $hasil = $this->getRecordsetArray($qry, null, $this->db2);
        return $hasil;
    }

    function getInfoBvArrayTesting($arrParam = null) {
        $str = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "a.status_send_wa != '1'";
                } else {
                    $str .= "a.status_send_wa = '1'";
                }
            }

            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    
        }   

        /* $qry = "SELECT a.id, a.kepada, 'PBV:' +  a.PBV + ' / PGBV:' + a.PGBV as info_bv, a.GBV, a.note, a.hp
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        /* $qry = "SELECT a.id, a.kepada, a.hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, a.LGBV, note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        /* $qry = "SELECT TOP 1 a.id, a.kepada, '087780441874' as hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, a.LGBV, LEN(CAST(a.LGBV as varchar(max))) as note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */
        //CASE WHEN a.note is null OR a.note = '' THEN '-' ELSE a.note END AS note
        $tgl = date("d/m/Y");
        $qry = "SELECT TOP 3 a.id, a.kepada, 
                    a.hp, 
                CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period, 
                CONVERT(VARCHAR(15), a.createdt, 103) as tgl_kirim,
                CASE WHEN a.PBV is null OR a.PBV = '' THEN '-' ELSE a.PBV END AS PBV, 
                CASE WHEN a.GBV is null OR a.GBV = '' THEN '-' ELSE a.GBV END AS GBV,
                CASE WHEN a.PGBV is null OR a.PGBV = '' THEN '-' ELSE a.PGBV END AS PGBV,
                CASE WHEN a.LGBV is null OR a.LGBV = '' THEN '-' ELSE a.LGBV END AS LGBV, 
                CASE WHEN a.note is null OR a.note = '' THEN '-' ELSE a.note END AS note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE a.hp is not null AND a.hp != '' AND $str";
        //echo $qry;
        /* $qry = "SELECT 	a.id, a.kepada, a.hp,
                        CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                        a.PBV, a.GBV, a.PGBV, 
                        LTRIM(RTRIM((SUBSTRING(CAST(a.LGBV as varchar(max)), 1, CHARINDEX('leg6', lower(CAST(a.LGBV as varchar(max)))))))) as LGBV, 
                        note
                FROM wa_info_bv_detail a
                where lower(CAST(a.LGBV as varchar(max))) like '%leg6%' AND $str"; */
        //echo $qry;
        $hasil = $this->getRecordsetArray($qry, null, $this->db2);
        foreach($hasil as $dta) {
            //$hasil['id']
        }

        return $hasil;
    }

    function getInfoBvArrayReport($arrParam = null) {
        $str = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "a.status_send_wa != '1'";
                } else {
                    $str .= "a.status_send_wa = '1'";
                }
            }

            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    
        }   

        /* $qry = "SELECT a.id, a.kepada, 'PBV:' +  a.PBV + ' / PGBV:' + a.PGBV as info_bv, a.GBV, a.note, a.hp
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        /* $qry = "SELECT a.id, a.kepada, a.hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, a.LGBV
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        $qry = "SELECT a.id, a.kepada, 
                    a.hp, 
                CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period, 
                CONVERT(VARCHAR(16), a.createdt, 120) as tgl_kirim,
                CONVERT(VARCHAR(16), a.send_wa_dt, 120) as tgl_kirim_wa,
                CASE WHEN a.PBV is null OR a.PBV = '' THEN '-' ELSE a.PBV END AS PBV, 
                CASE WHEN a.GBV is null OR a.GBV = '' THEN '-' ELSE a.GBV END AS GBV,
                CASE WHEN a.PGBV is null OR a.PGBV = '' THEN '-' ELSE a.PGBV END AS PGBV,
                CASE WHEN a.LGBV is null OR a.LGBV = '' THEN '-' ELSE a.LGBV END AS LGBV, 
                CASE WHEN a.note is null OR a.note = '' THEN '-' ELSE a.note END AS note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE a.hp is not null AND a.hp != '' AND $str";

        /*$qry = "SELECT 	a.id, a.kepada, a.hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, 
                    LTRIM(RTRIM((SUBSTRING(CAST(a.LGBV as varchar(max)), 1, CHARINDEX('leg6', lower(CAST(a.LGBV as varchar(max)))))))) as LGBV
                FROM wa_info_bv_detail a
                where $str"; */
        //echo $qry;
        //$hasil = $this->getRecordsetArray($qry, null, $this->db2);
        $this->db = $this->load->database("db_ecommerce", true);
        $query = $this->db->query($qry, null); 
        $hasil = null;
		if($query->num_rows() > 0)  {
            $hasil = $query->result_array();
            if($hasil !== null) {
                $i=0;
                foreach($hasil as $dta) {
                    $href = "<a class='btn btn-mini btn-primary' onclick=\"javascript:sendWa('".$dta['id']."')\">Send WA</a>";
                    $hasil[$i]['btn'] = $href;
                    $i++;
                }   
            }
        } 

        return $hasil;
    }

    /* function sendWaById($param) {
        $qry = "SELECT a.id, a.kepada, 
                    a.hp, 
                CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period, 
                CONVERT(VARCHAR(10), a.createdt, 103) as tgl_kirim,
                CASE WHEN a.PBV is null OR a.PBV = '' THEN '-' ELSE a.PBV END AS PBV, 
                CASE WHEN a.GBV is null OR a.GBV = '' THEN '-' ELSE a.GBV END AS GBV,
                CASE WHEN a.PGBV is null OR a.PGBV = '' THEN '-' ELSE a.PGBV END AS PGBV,
                CASE WHEN a.LGBV is null OR a.LGBV = '' THEN '-' ELSE a.LGBV END AS LGBV, 
                CASE WHEN a.note is null OR a.note = '' THEN '-' ELSE a.note END AS note
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE a.id = $param";
        $hasil = $this->getRecordsetArray($qry, null, $this->db2);
        return $hasil;
    } */

    function listBatchWaInfoBv($tbl) {
        $qry = "SELECT DISTINCT(batch_kirim) as batch_kirim
                FROM $tbl a";
        $hasil = $this->getRecordset($qry, null, $this->db2);
        if($hasil == null) {
            return jsonFalseResponse("No batch found..");
        }

        return jsonTrueResponse($hasil);
    }

    function updateWaSendData($id, $paramUpd) {
        $qry = "UPDATE a
                SET a.send_wa_dt = GETDATE(), 
                    a.messageid = '$paramUpd[messageid]',
                    a.status_send_wa = '$paramUpd[status_send_wa]'
                FROM klink_mlm2010.dbo.wa_info_bv_detail a
                WHERE a.id = $id";
        $query = $this->db->query($qry);
        return $query;
    }

    function updateWaSendDataBv($tbl, $id, $paramUpd) {
        $qry = "UPDATE a
                SET a.send_wa_dt = GETDATE(), 
                    a.messageid = '$paramUpd[messageid]',
                    a.status_send_wa = '$paramUpd[status_send_wa]'
                FROM $tbl a
                WHERE a.id = $id";
        $query = $this->db->query($qry);
        return $query;
    }

    function updateWaSendDataBvQual($id, $paramUpd) {
        $qry = "UPDATE a
                SET a.send_wa_dt = GETDATE(), 
                    a.messageid = '$paramUpd[messageid]',
                    a.status_send_wa = '$paramUpd[status_send_wa]'
                FROM klink_mlm2010.dbo.wa_q1000bv a
                WHERE a.id = $id";
        $query = $this->db->query($qry);
        return $query;
    }

    function getDataWhatsappDest($order_id) {
        /* $qry = "SELECT a.orderno, a.userlogin, a.confirmeddate, CONVERT(VARCHAR(10), a.datetrans, 120) as datetrans, a.confirmedby,
                a.total_pay + a.payShip as total_bayar,
                a1.tel_hp1 as tel_hp, a.wa_sent_remark, a.wa_sent_stt, a1.receiver_name, a1.tel_hp1, a1.addr1,
                a.conote_new
                from db_ecommerce.dbo.ecomm_trans_hdr_sgo a 
                LEFT OUTER JOIN db_ecommerce.dbo.ecomm_trans_shipaddr_sgo a1 
                     ON (a.orderno = a1.orderno)  
                LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b 
                    ON (a.userlogin COLLATE SQL_Latin1_General_CP1_CS_AS = b.dfno)
                WHERE a.orderno = ?"; */

        /* $qry = "SELECT a.orderno, a.userlogin, a.confirmeddate, CONVERT(VARCHAR(10), a.datetrans, 120) as datetrans, a.confirmedby,
                a.total_pay + a.payShip as total_bayar,
                a1.tel_hp1 as tel_hp, a.wa_sent_remark, a.wa_sent_stt, 
                a1.receiver_name, a1.tel_hp1, a1.addr1,
                a.conote_new, a.datetrans, b.tel_hp AS tel_hp_pembelanja,
                c.reply, c.param, c.messageId, a.remarks, c.[from], a.wa_sent_remark
                from db_ecommerce.dbo.ecomm_trans_hdr_sgo a 
                LEFT OUTER JOIN db_ecommerce.dbo.ecomm_trans_shipaddr_sgo a1 
                    ON (a.orderno = a1.orderno)  
                LEFT OUTER JOIN klink_mlm2010.dbo.msmemb b 
                    ON (a.userlogin COLLATE SQL_Latin1_General_CP1_CS_AS = b.dfno)
                LEFT OUTER JOIN db_ecommerce.dbo.ecomm_trans_cod_confirm c ON (a.orderno = c.orderno)
                WHERE a.orderno = ?";
        $hasil = $this->getRecordset($qry, $order_id, $this->db1); */

        $hasil = $this->headerTrxInfo($order_id);

        if(empty($hasil)) {
            return array("response" => "0", "message" => "Orderno tidak valid..");
        }

        if($hasil['header']['status'] == "1" && $hasil['header']['confirmstatus'] == "1") {
            $arrres = array($hasil['header']['keterangan_status'], $hasil['header']['confirmedby']);
            $arrresX = implode(" oleh ", $arrres);
            return array("response" => "0", "message" => $arrresX);
        }

        if($hasil['header']['status'] == "9" || $hasil['header']['status'] == "10" || $hasil['header']['status'] == "8") {
            return array("response" => "0", "message" => $hasil['header']['keterangan_status']);
        }

        /* if($hasil == null) {
            return array("response" => "0", "message" => "Orderno tidak valid..");
        } */

        $wa_sent_stt = $hasil['header']['wa_sent_stt'];
        if($wa_sent_stt === null || $wa_sent_stt === "" || $wa_sent_stt === "0") {
            return array("response" => "1", 
              "message" => "Whatsapp berhasil dikirim..", 
              "remark" => $hasil['header']['messageID'],
              "arrayData" => $hasil);
        }

        return array("response" => "2", 
         "message" => "Whatsapp sudah terkirim..", 
         "remark" => $hasil['header']['messageID'],
         "arrayData" => $hasil ); 
    }

    function updateStatusWhatsapp($arr) {
        $qry = "UPDATE db_ecommerce.dbo.ecomm_trans_hdr_sgo 
                SET wa_sent_stt = ?, wa_sent_remark = ?
                WHERE orderno = ?";
        $arrParam = array($arr['status'], $arr['remark'], $arr['orderno']);
        $query = $this->db->query($qry, $arrParam);
        return $query;
    }

    function updateConfimCod($trxno, $status, $allParam) {
        //1  = PAKET tidak dibatalkan
        //10 = PAKET dibatalkan
        $confirm = "10";
        $confirmStatus = "0";
        if($status == "Y" || $status == "y") {
            $confirm = "1";
            $confirmStatus = "1";
        }

        $checkTrx = $this->checkTrxCodStatus($trxno, $status);
        if($checkTrx['response'] == "true") {

            $tgl = date('Y-m-d H:i:s', $allParam[0]->timestamp);

            $qry = "UPDATE db_ecommerce.dbo.ecomm_trans_hdr_sgo 
                    SET status = ?, confirmstatus = ?, confirmeddate = ?
                    WHERE orderno = ?";
            $arrParam = array($confirm, $confirmStatus, $tgl, $trxno);
            $query = $this->db->query($qry, $arrParam);
            //echo $qry;
            //print_r($arrParam);

            $qry2 = "insert into ecomm_trans_cod_confirm VALUES (?, ?, ?, ?, ?, ?)";
            $arrParam2 = array($trxno, $status, $tgl, json_encode($allParam), $allParam[0]->id, $allParam[0]->from);
            $query2 = $this->db->query($qry2, $arrParam2);
            //echo $qry2;
            //print_r($arrParam2);

            return $checkTrx;
        }    

        return $checkTrx;
        /* $query = $this->db->query($qry, $arrParam);
        return $query; */
    }

    function checkTrxCodStatus($orderno, $status) {
        $qry = "SELECT a.orderno, a.[status], a.confirmstatus, a.print_count,
                  a.is_cod, b.receiver_name, c.orderno as trxno
                FROM ecomm_trans_hdr_sgo a
                LEFT OUTER JOIN ecomm_trans_shipaddr_sgo b ON (a.orderno = b.orderno)
                LEFT OUTER JOIN ecomm_trans_hdr c ON (a.orderno = c.token)
                WHERE a.orderno = '$orderno'";
        $hasil = $this->getRecordset($qry, $orderno, $this->db1);
        if($hasil == null) {
            return jsonFalseResponse("Transaksi tidak ditemukan..");
        }

        if($hasil[0]->is_cod !== "1") {
            return jsonFalseResponse("Transaksi $orderno bukan COD..");
        }

        if($hasil[0]->trxno !== null) {
            return jsonFalseResponse("Transaksi COD $orderno sudah masuk dalam sistem");
        }

        /* if($hasil[0]->print_count > 0) {
            return jsonFalseResponse("Transaksi $orderno Sedang dalam proses pengiriman..");
        } */

        if($hasil[0]->status === "1" && $hasil[0]->confirmstatus === "1") {
            return jsonFalseResponse("Transaksi COD $orderno sudah dikonfirmasi untuk dikirim");
        }

        if(($hasil[0]->status === "8" || $hasil[0]->status === "7")) {
            return jsonFalseResponse("Transaksi COD $orderno ini dalam proses retur / ditolak oleh penerima");
        }

        if($hasil[0]->status === "9") {
            return jsonFalseResponse("Transaksi COD $orderno dibatalkan");
        }

        if($hasil[0]->status === "10") {
            return jsonFalseResponse("Transaksi COD $orderno sudah diconfirm untuk dibatalkan pengiriman nya oleh penerima..");
        }

        if($hasil[0]->status === "0" && $hasil[0]->confirmstatus === "0") {
            if($status == "Y") {
                return jsonTrueResponse("Terima kasih atas konfirmasinya. Transaksi COD $orderno akan segera diproses untuk pengirimannya..");
            } else {
                return jsonTrueResponse("Terima kasih atas konfirmasinya. Transaksi COD $orderno akan di batalkan pengirimannya");
            }
        }

        
    }

    function checkExpireToken() {
        $qry = "SELECT * FROM  db_ecommerce.dbo.whatsapp_token WHERE expire > GETDATE()";
        $hasil = $this->getRecordset($qry, null, $this->db1);
        if($hasil == null) {
            $del = "DELETE FROM db_ecommerce.dbo.whatsapp_token WHERE expire <= GETDATE()";
            $query = $this->db->query($del);
            return jsonFalseResponse("token sudah expire");
        } else {
            $arr = array(
                "token" => $hasil[0]->token,
                "expire" => $hasil[0]->expire
            );
            return jsonTrueResponse($arr, "ada");
        }
    }

    function hapusDataToken() {
        $del = "DELETE FROM db_ecommerce.dbo.whatsapp_token";
        $query = $this->db->query($del);
    }

    function getTokenWa() {
        $qry = "SELECT * FROM db_ecommerce.dbo.whatsapp_token WHERE expire > GETDATE()";
        $hasil = $this->getRecordset($qry, null, $this->db1);
        return $hasil;
    }

    function listSalamSehat() {
        //IDSPAAA90748
        $qry = "SELECT top 20 *
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.STATUS = 'A' and (a.kirim is null OR a.kirim != 'y')";
        //and (a.kirim is null OR a.kirim != 'y')        
        //a.DFNO in ('IDSPAAA90748','IDSPAAA66834')";
        $hasil = $this->getRecordset($qry, null, $this->db1);
        return $hasil;
    }

    function listSalamSehatById($idmember) {
        //IDSPAAA90748
        $qry = "SELECT *
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.DFNO = '$idmember'";
        //a.DFNO in ('IDSPAAA90748','IDSPAAA66834')";
        $hasil = $this->getRecordset($qry, null, $this->db1);
        return $hasil;
    }

    function listSalamSehatByMessageId($idmember) {
        //IDSPAAA90748
        $qry = "SELECT a.messageId, a.TEL_HP
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.messageId IN ($idmember)";
        //echo $qry;
        //a.DFNO in ('IDSPAAA90748','IDSPAAA66834')";
        $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil;
    }

    function updateInvalidNoWa($messageid) {
        $qry = "UPDATE a SET a.STATUS = 'X', a.kirim = 'n'
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.messageId IN ($messageid)";
        //echo $qry;
        $upd = $this->executeQuery($qry, "klink_mlm2010");
        return $upd;
    }

    function updateInvalidNoWaPeringkat($messageid) {
        $qry = "UPDATE a SET a.status = 'X'
                FROM klink_mlm2010.dbo.wa_info_rank a
                WHERE a.messageId IN ($messageid)";
        //echo $qry;
        $upd = $this->executeQuery($qry, "klink_mlm2010");
        return $upd;
    }

    function updateInvalidNoWaQual1000bv($messageid) {
        $qry = "UPDATE a SET a.status = 'X'
                FROM klink_mlm2010.dbo.wa_q1000bv a
                WHERE a.messageId IN ($messageid)";
        //echo $qry;
        $upd = $this->executeQuery($qry, "klink_mlm2010");
        return $upd;
    }

    function updateInvalidNoWaPromo1111($messageid) {
        $qry = "UPDATE a SET a.status = 'X'
        FROM klink_mlm2010.dbo.NH_PROMO a
        WHERE a.messageid IN ($messageid)";
        //echo $qry;
        $upd = $this->executeQuery($qry, "klink_mlm2010");
        return $upd;
    }


    function updateSalamSehat($id, $paramUpd, $msgid) {
        $qry = "UPDATE a
                SET a.kirim = 'y', a.response = '$paramUpd', a.tglkirim = GETDATE(), a.messageId = '$msgid'
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.DFNO = '$id'";
        $query = $this->db->query($qry);
        return $query;
    }

    function updateSendPeringkat($id, $messageId) {
        $qry = "UPDATE a SET a.send_wa_dt = GETDATE(), a.status_send_wa = 1, a.messageid = '$messageId'
                FROM klink_mlm2010.dbo.wa_info_rank a
                WHERE a.id = $id";
        $query = $this->db->query($qry);
        return $query;
    }

    function updateSalamSehatGagal($id) {
        $qry = "UPDATE a
                SET a.kirim = 't', a.tglkirim = GETDATE()
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.DFNO = '$id'";
        $query = $this->db->query($qry);
        return $query;
    }


    function getListSamaSehat($par) {

        $param = "";
        if($par['resend'] == "y") {
            $param .= " AND a.kirim = 'y'";
        } else {
            $param .= " AND (a.kirim is null OR a.kirim != 'y')";    
        }

        $qry = "SELECT a.DFNO, a.FULLNM, a.TEL_HP, CONVERT(VARCHAR(16), a.TglBuat, 120) as tglbuat, a.STATUS, a.kirim, CONVERT(VARCHAR(16), a.tglkirim, 120),
                    a.response
                FROM klink_mlm2010.dbo.NH_WA_ASS a
                WHERE a.status = 'A' $param";
        /* $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil; */

        $this->db = $this->load->database("klink_mlm2010", true);
        $query = $this->db->query($qry, null); 
        $hasil = null;
		if($query->num_rows() > 0)  {
            $hasil = $query->result_array();
            if($hasil !== null) {
                $i=0;
                foreach($hasil as $dta) {
                    $href = "<a class='btn btn-mini btn-primary' onclick=\"javascript:sendSalamSehat('".$dta['DFNO']."')\">Send WA</a>";
                    $hasil[$i]['btn'] = $href;
                    $i++;
                }   
            }
        } 

        return $hasil;
    }

    function getListQualifier1000BV($arrParam) {
        $res = $this->getListQualifier1000BvArray($arrParam);
        $i=0;
        if($res !== null) {
            foreach($res as $dta) {
                $href = "<a class='btn btn-mini btn-primary' onclick=\"javascript:sendInfo1000BvQual('".$dta['id']."')\">Send WA</a>";
                $res[$i]['btn'] = $href;
                $i++;    
            }
        }
        return $res;
    }

    function listTipePromo() {
        $qry = "SELECT DISTINCT(a.NMPROMO) as tipe_promo 
        FROM klink_mlm2010.dbo.nh_promo a";
        $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil;
    }

    function getBlastPromoNov($arrParam) {
        $res = $this->getBlastPromoNovArray($arrParam);
        $i=0;
        if($res !== null) {
            foreach($res as $dta) {
                $href = "<a class='btn btn-mini btn-primary' onclick=\"javascript:sendWaV2('".$dta['id']."')\">Send WA</a>";
                $res[$i]['btn'] = $href;
                $i++;    
            }
        }
        return $res;
    }

    function getBlastPromoNovArray($arrParam) {
        $str = "";
        $top = "";
        $table = $arrParam['table'];
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('max', $arrParam)) {
                $top = " TOP $arrParam[max] ";
            }

            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "(a.status_send_wa != 1 OR a.status_send_wa is null OR a.status_send_wa = '')";
                } else {
                    $str .= "a.status_send_wa = 1";
                }
            }


            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    

            if(array_key_exists('period', $arrParam)) {
                $str .= " AND CONVERT(VARCHAR(10), a.PERIOD, 120) = '$arrParam[period]'";
            }

            if(array_key_exists('NHPROMO', $arrParam)) {
                $str .= " AND a.NMPROMO = '$arrParam[NHPROMO]'";
            }

            if(array_key_exists('cod', $arrParam)) {
                $str .= " AND a.cod = '$arrParam[cod]'";
            }

            if(array_key_exists('TEL_HP', $arrParam)) {
                $str .= " AND a.TEL_HP IN ($arrParam[TEL_HP])";
            }
        } 

        $qry = "SELECT $top  a.id, a.DFNO, a.FULLNM, a.TEL_HP as hp, a.messageID,
                    CONVERT(VARCHAR(16), a.send_wa_dt, 120) as send_wa_dt 
                FROM klink_mlm2010.dbo.$table a WHERE $str";
        //echo $qry;
        $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil;
    }

    function getBlastListDataFromTable($arrParam) {
        $res = $this->getListDataFromTable($arrParam);
        $i=0;
        if($res !== null) {
            foreach($res as $dta) {
                $href = "<a class='btn btn-mini btn-primary' onclick=\"javascript:sendWaV2('".$dta['id']."')\">Send WA</a>";
                $res[$i]['btn'] = $href;
                $i++;    
            }
        }
        return $res;
    }

    function getListDataFromTable($arrParam) {
        $str = "";
        $top = "";
        $table = $arrParam['table'];
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('max', $arrParam)) {
                $top = " TOP $arrParam[max] ";
            }

            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "(a.status_send_wa != 'Y' OR a.status_send_wa is null OR a.status_send_wa = '')";
                } else {
                    $str .= "a.status_send_wa = 'Y'";
                }
            }


            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    

            if(array_key_exists('period', $arrParam)) {
                $str .= " AND CONVERT(VARCHAR(10), a.PERIOD, 120) = '$arrParam[period]'";
            }

            if(array_key_exists('NHPROMO', $arrParam)) {
                $str .= " AND a.NMPROMO = '$arrParam[NHPROMO]'";
            }

            if(array_key_exists('cod', $arrParam)) {
                $str .= " AND a.cod = '$arrParam[cod]'";
            }

            if(array_key_exists('TEL_HP', $arrParam)) {
                $str .= " AND a.TEL_HP IN ($arrParam[TEL_HP])";
            }

            if(array_key_exists('JOINTDT', $arrParam)) {
                $str .= " AND CONVERT(VARCHAR(10), a.JOINTDT, 120) $arrParam[JOINTDT]";
            }    

            $order = "";
            if(array_key_exists('ORDER BY', $arrParam)) {
                $order .= " ORDER BY a.".$arrParam['ORDER BY'];
            }    
        } 

        $qry = "SELECT $top  a.id, a.DFNO, a.FULLNM, CONVERT(VARCHAR(10), a.JOINTDT, 120) as JOINTDT, a.TEL_HP as hp, a.messageID,
                    CONVERT(VARCHAR(16), a.send_wa_dt, 120) as send_wa_dt 
                FROM $table a WHERE $str";
        $qry .= " AND (a.status != 'X' OR a.status is null OR a.status = '') $order";
        //echo $qry;
        //$qry .= " ORDER BY a.TTLBV";
        //echo $qry;
        $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil;
    }

    function getListDataFromTableV2($arrParam) {
        $str = "";
        $top = "";
        $table = $arrParam['table'];
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('max', $arrParam)) {
                $top = " TOP $arrParam[max] ";
            }

            if (array_key_exists('id', $arrParam)) {

                if(is_array($arrParam['id'])) {
                    $varId = "";
                   foreach($arrParam['id'] as $dtaID) { 
                    $varId .= $dtaID.",";
                   }
                   $varId = substr($varId, 0, -1);

                   $str .= "a.id IN ($varId)";
                } else {
                    $str .= "a.id = '$arrParam[id]' ";
                }
                
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "(a.status_send_wa != 'Y' OR a.status_send_wa is null OR a.status_send_wa = '')";
                } else {
                    $str .= "a.status_send_wa = 'Y'";
                }
            }


            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    

            if(array_key_exists('period', $arrParam)) {
                $str .= " AND CONVERT(VARCHAR(10), a.PERIOD, 120) = '$arrParam[period]'";
            }

            if(array_key_exists('NHPROMO', $arrParam)) {
                $str .= " AND a.NMPROMO = '$arrParam[NHPROMO]'";
            }

            if(array_key_exists('cod', $arrParam)) {
                $str .= " AND a.cod = '$arrParam[cod]'";
            }

            if(array_key_exists('TEL_HP', $arrParam)) {
                $str .= " AND a.TEL_HP IN ($arrParam[TEL_HP])";
            }

            if(array_key_exists('JOINTDT', $arrParam)) {
                $str .= " AND CONVERT(VARCHAR(10), a.JOINTDT, 120) $arrParam[JOINTDT]";
            }    

            $order = "";
            if(array_key_exists('ORDER BY', $arrParam)) {
                $order .= " ORDER BY a.".$arrParam['ORDER BY'];
            }    
        } 

        $qry = "SELECT $top  a.id, a.DFNO, a.FULLNM, CONVERT(VARCHAR(10), a.JOINTDT, 120) as JOINTDT, a.TEL_HP as hp, a.messageID,
                    CONVERT(VARCHAR(16), a.send_wa_dt, 120) as send_wa_dt 
                FROM $table a WHERE $str";
        $qry .= " AND (a.status != 'X' OR a.status is null OR a.status = '') $order";
        //echo $qry;
        //$qry .= " ORDER BY a.TTLBV";
        //echo $qry;
        $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil;
    }

    function getListQualifier1000BVToSend($arrParam) {

    }  
    
    function getListQualifier1000BvArray($arrParam) {
        $str = "";
        $top = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('max', $arrParam)) {
                $top = " TOP $arrParam[max] ";
            }

            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "(a.status_send_wa != 1 OR a.status_send_wa is null OR a.status_send_wa = '')";
                } else {
                    $str .= "a.status_send_wa = 1";
                }
            }


            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    

            if(array_key_exists('period', $arrParam)) {
                $str .= " AND CONVERT(VARCHAR(10), a.period, 120) = '$arrParam[period]'";
            }
        } 

        $qry = "SELECT $top a.id, a.dfno, a.fullnm, a.hp, 
                  DATENAME(month, a.period) + '-'+ CAST(YEAR(a.period) as CHAR) as period,
                  a.jumlah_bv, a.status_send_wa, a.send_wa_dt, a.messageid
                FROM klink_mlm2010.dbo.wa_q1000bv a WHERE a.status = 'A' 
                AND a.hp is not null AND a.hp != ''
                AND $str";
        //echo $qry;
        $hasil = $this->getRecordsetArray($qry, null, $this->db1);
        return $hasil;
    } 

    function getListInfoPeringkat($arrParam) {
        $str = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "(a.status_send_wa != 1 OR a.status_send_wa is null OR a.status_send_wa = '')";
                } else {
                    $str .= "a.status_send_wa = 1";
                }
            }

            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    


            if (array_key_exists('period', $arrParam)) {
                $str .= " AND a.period = '$arrParam[period]'";
                //$param_dfno++;
            } 
        }   

        /* $qry = "SELECT a.id, a.kepada, 'PBV:' +  a.PBV + ' / PGBV:' + a.PGBV as info_bv, a.GBV, a.note, a.hp
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        /* $qry = "SELECT a.id, a.kepada, a.hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, a.LGBV
                FROM klink_mlm2010.dbo.wa_info_bv_detail a 
                WHERE $str"; */

        $qry = "SELECT a.id, a.dfno, a.fullnm, a.hp, b.ranknm, RIGHT(a.period, 2) + '/' + LEFT(a.period, 4) as periode,
                a.status_send_wa, a.send_wa_dt, a.messageid
                FROM klink_mlm2010.dbo.wa_info_rank a
                LEFT OUTER JOIN klink_mlm2010.dbo.msrank b ON (a.rank = b.[level])
                WHERE a.status = 'A' AND $str";
        //echo $qry;
        /*$qry = "SELECT 	a.id, a.kepada, a.hp,
                    CAST(MONTH(a.period) as varchar) + '/' + CAST(YEAR(a.period) as varchar) as period,
                    a.PBV, a.GBV, a.PGBV, 
                    LTRIM(RTRIM((SUBSTRING(CAST(a.LGBV as varchar(max)), 1, CHARINDEX('leg6', lower(CAST(a.LGBV as varchar(max)))))))) as LGBV
                FROM wa_info_bv_detail a
                where $str"; */
       // echo $qry;
        //$hasil = $this->getRecordsetArray($qry, null, $this->db2);
        $this->db = $this->load->database("db_ecommerce", true);
        $query = $this->db->query($qry, null); 
        $hasil = null;
		if($query->num_rows() > 0)  {
            $hasil = $query->result_array();
            if($hasil !== null) {
                $i=0;
                foreach($hasil as $dta) {
                    $href = "<a class='btn btn-mini btn-primary' onclick=\"javascript:sendInfoPeringkat('".$dta['id']."')\">Send WA</a>";
                    $hasil[$i]['btn'] = $href;
                    $i++;
                }   
            }
        } 

        return $hasil;
    }

    function getListInfoPeringkatToSend($arrParam) {
        $str = "";
        if($arrParam == null) {
            $str = " a.send_wa_dt is null OR a.send_wa_dt = ''";
        } else {
            if (array_key_exists('id', $arrParam)) {
                $str .= "a.id = '$arrParam[id]' ";
            }

            $param_dfno = 0;
            if (array_key_exists('dfno', $arrParam)) {
                $str .= "a.dfno = '$arrParam[dfno]' ";
                $param_dfno++;
            }    

            $param_resend = 0;
            if(array_key_exists('resend', $arrParam)) {

                if($param_dfno > 0) {
                    $str .= "AND ";
                }

                $param_resend++;
                if($arrParam['resend'] == "n" || $arrParam['resend'] == "N") {
                    $str .= "(a.status_send_wa != 1 OR a.status_send_wa is null OR a.status_send_wa = '')";
                } else {
                    $str .= "a.status_send_wa = 1";
                }
            }

            $param_batch = 0;
            if(array_key_exists('batch', $arrParam)) {
                $param_batch++;

                if($param_resend > 0) {
                    $str .= "AND ";
                }

                if($arrParam['resend'] !== "" && $arrParam['resend'] !== null) {
                    $str .= "a.batch_kirim = '$arrParam[resend]'";
                }
            }    

            if(array_key_exists('period', $arrParam)) {
                $str .= " AND a.period = '$arrParam[period]'";
            }    
        }   


        $qry = "SELECT TOP 20 a.id, a.dfno, a.fullnm, a.hp, b.ranknm, 
                RIGHT(a.period, 2) + '/' + LEFT(a.period, 4) as periode,
                b.shortnm + '.mp4' as filenm
                FROM klink_mlm2010.dbo.wa_info_rank a
                LEFT OUTER JOIN klink_mlm2010.dbo.msrank b ON (a.rank = b.[level])
                WHERE a.status = 'A' AND $str";
        //echo $qry;
        $hasil = $this->getRecordset($qry, null, $this->db1);
        return $hasil;
    }

    function getListInfoPeringkatById($idmember) {
        //IDSPAAA90748
        $qry = "SELECT a.id, a.dfno, a.fullnm, a.hp, b.ranknm, 
                  RIGHT(a.period, 2) + '/' + LEFT(a.period, 4) as periode,
                b.shortnm + '.mp4' as filenm
                FROM klink_mlm2010.dbo.wa_info_rank a 
                LEFT OUTER JOIN klink_mlm2010.dbo.msrank b ON (a.rank = b.[level])
                WHERE a.id = $idmember";
        //a.DFNO in ('IDSPAAA90748','IDSPAAA66834')";
        $hasil = $this->getRecordset($qry, null, $this->db1);
        return $hasil;
    }

    function headerTrxInfo($orderno) {
        $qry = "SELECT TOP 1 a.userlogin, a1.fullnm as nama_penjual,
                    CONVERT(VARCHAR(10), a.datetrans, 120) as datetrans,
                b.receiver_name, b.tel_hp1 AS receiver_phone, b.addr1 as receiver_addr,
                b.kel_code, b.kab_code, b.prov_code,
                a.id_lp, a.total_pay, a.totPayDP, a.totPayCP, a.payShip, a.[status], a.wa_sent_stt,
                a.confirmstatus, CONVERT(VARCHAR(16), a.confirmeddate, 120) as confirmeddate, a.confirmedby, a.is_cod,
                a.is_free_sip_from_member, a.profit_member, a.wa_sent_remark,
                b.conoteJNE, a.orderno,
                CASE 
                  WHEN a.status = '1' THEN 'Sudah Dikonfirmasi untuk dikirim'  
                  WHEN a.status = '8' THEN 'Penerima menolak Paket'
                  WHEN a.status = '9' THEN 'Konfirmasi Menolak'
                  WHEN a.status = '7' THEN 'Dikembalikan oleh Kurir'
                  WHEN a.status = '10' THEN 'Sudah Dikonfirm untuk Batal/Tidak dikirim'
                  WHEN a.status = '0' OR a.status = '' OR a.status is null THEN 'Butuh Konfirmasi Penerima'
                END AS keterangan_status,
                c.reply, c.param, c.messageId, a.remarks, c.[from], a.wa_sent_remark  
                FROM db_ecommerce.dbo.ecomm_trans_hdr_sgo a
                INNER JOIN klink_mlm2010.dbo.msmemb a1 ON (a.userlogin COLLATE SQL_Latin1_General_CP1_CS_AS = a1.dfno)
                INNER JOIN db_ecommerce.dbo.ecomm_trans_shipaddr_sgo b ON (a.orderno = b.orderno)
                LEFT OUTER JOIN db_ecommerce.dbo.ecomm_trans_cod_confirm c ON (a.orderno = c.orderno)
                WHERE a.orderno = '$orderno'
                ORDER BY c.date DESC";
        $hasil = $this->getRecordset($qry, null, $this->db1);
        $arr = array();
        if($hasil !== null) {
            $alamat =  $hasil[0]->receiver_addr. " Kel.".$hasil[0]->kel_code.", Kab. ".$hasil[0]->kab_code.", Prov. ".$hasil[0]->prov_code;   
            //$arr['result'] = $hasil;

            $no_hp = preg_replace("/[^A-Za-z0-9]/", "",$hasil[0]->receiver_phone);
            $out = ltrim($no_hp, "0");
            if(substr($out, 0, 2) == "62") {
                $no_hp2 = $out;
            } else {
                $no_hp2 = "62".$out;
            }

            $arr['header'] = array(
                "penjual" => strtoupper($hasil[0]->nama_penjual),
                "tgl_transaksi" => $hasil[0]->datetrans,
                "alamat" => strtoupper($alamat),
                "keterangan_status" => strtoupper($hasil[0]->keterangan_status),
                "status" => $hasil[0]->status,
                "confirmstatus" => $hasil[0]->confirmstatus,
                "confirmedby" => strtoupper($hasil[0]->confirmedby),
                "confirmeddt" => $hasil[0]->confirmeddate,
                "penerima" => strtoupper($hasil[0]->receiver_name),
                "hp_penerima" => $no_hp2,
                "messageID" => $hasil[0]->wa_sent_remark,
                "remarks" => $hasil[0]->remarks,
                "confirmstatus" => $hasil[0]->confirmstatus,
                "wa_sent_stt" => $hasil[0]->wa_sent_stt,
                "no_resi" => $hasil[0]->conoteJNE,
                "userlogin" => $hasil[0]->userlogin,
                "orderno" => $hasil[0]->orderno,
            );

            $totBayar = 0;
            $payShip = $hasil[0]->payShip;
            $profit_member = $hasil[0]->profit_member;
            $totPayCP = $hasil[0]->totPayCP;

            if($hasil[0]->is_cod == "1"){
                if($hasil[0]->id_lp == "CUST"){
                    if($hasil[0]->is_free_sip_from_member == "1"){
                        if($profit_member > $payShip){
                            $totBayar = $totPayCP;
                        }else{
                            $selisih= $payShip - $profit_member;
                            $totBayar = $totPayCP + $selisih;
                        }
                    }else{
                        $totBayar = $totPayCP + $payShip;
                    }
                }else{
                    $totBayar = $hasil[0]->totPayDP + $payShip;
                }

            }else{
                $totBayar = $list->totPayDP;
            }
            $arr['header']['total_bayar'] = $totBayar;
        }

        return $arr;
    }

    function getDetailTrx($orderno) {
        
        $arr = $this->headerTrxInfo($orderno);

        if(!empty($arr)) {
            $qryDet = "SELECT a.prdcd, a.prdnm, a.qty
                        FROM db_ecommerce.dbo.ecomm_trans_det_prd_sgo a
                        WHERE a.orderno = '$orderno'";
            $hasil2 = $this->getRecordsetArray($qryDet, null, $this->db1);
            
             $arr['produk'] = $hasil2;
        } else {
            $arr = null;
        }

        return $arr;
    }

    function checkParameter($data) {
        if(!array_key_exists("token", $data)) {
            $arrx = array(
                "errCode" => "100",
                "message" => "Missing Parameter Token"
            );
            return $arrx;
        }

        if(!array_key_exists("orderno", $data)) {
            $arrx = array(
                "errCode" => "102",
                "message" => "Missing Parameter orderno"
            );
            return $arrx;
        }

        if(!array_key_exists("kirim", $data)) {
            $arrx = array(
                "errCode" => "102",
                "message" => "Missing Parameter kirim"
            );
            return $arrx;
        }

        if(!array_key_exists("ip_address", $data)) {
            $arrx = array(
                "errCode" => "102",
                "message" => "Missing Parameter ip_address"
            );
            return $arrx;
        }

        $kirim = strtoupper($data['kirim']);
        if($kirim !== "Y" && $kirim !== "N") {
            $arrx = array(
                "errCode" => "103",
                "message" => "Parameter Kirim should contain Y / N"
            );
            return $arrx;
        }

        if($data['token'] !==  "ay1uZXRfY29uZmlybWF0aW9uX2NvZA==") {
            $arrx = array(
                "errCode" => "104",
                "message" => "Invalid Token"
            );
            return $arrx;
        }

        $body = array("COD", $kirim, $data['orderno']);
        $parBody = implode("|", $body);

        return $arrx = array(
            "errCode" => "000",
            "message" => "Param complete",
            "param" => $parBody 
        );
    }

}