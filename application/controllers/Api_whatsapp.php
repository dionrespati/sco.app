<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Api_whatsapp extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model("Api_whatsapp_model",'wa');
    }

    public function sendWhatsappCodConfirm($order_id) {
        //EM191199019100
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        $hasil = $vchapi->sendWhatsappCodConfirm($order_id);
        print_r($hasil);

        //$hasil = $vchapi->sendSalamSehat();

    }

    public function sendSalamSehat() {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();

        $loop = $this->wa->listSalamSehat();
        if($loop == null) {
            echo "Data kosong";
            return;
        }

        $upd = "";
        $count = 0;
        foreach($loop as $dta) {
            $no_hp = preg_replace("/[^A-Za-z0-9]/", "",$dta->TEL_HP);
            $out = ltrim($no_hp, "0");
            if(substr($out, 0, 2) == "62") {
                $no_hp2 = $out;
            } else {
                $no_hp2 = "62".$out;
            }
            
           
            $hasil = $vchapi->sendSalamSehat($no_hp2);
            if(array_key_exists("messages", $hasil)) {
                //$upd .= "'".$dta->DFNO."',";
                $count++;
                $dfno = $dta->DFNO;
                $resJ = json_encode($hasil);

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;
                $this->wa->updateSalamSehat($dfno, $resJ, $msgId);
            } /* else {
                $dfno = $dta->DFNO;
                $this->wa->updateSalamSehatGagal($dfno);
            } */

            echo $dta->DFNO." - ".$no_hp2;
            echo "<br />";
        }

        echo "Dapat response : ".$count;

        /* $upd = substr($upd, 0, -1);
        
        $updData = $this->wa->update */
        /* $hasil = $vchapi->sendSalamSehat("628128517949");
        if($hasil) */
    }

    public function getDataWhatsappDest($order_id) {
        
    }

    //$route['wa/token/get'] = 'webshop/Api_whatsapp/getTokenWhatsapp';
    public function getTokenWhatsapp() {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        
        $this->wa->hapusDataToken();

        $token = $vchapi->requestToken();
        if($token['response'] == "true") {
            echo json_encode($token);
        } else {
            echo "asasa";
        }
    }

    //$route['wa/message/send'] = 'webshop/Api_whatsapp/sendWhatsAppForm';
    public function sendWhatsAppForm() {
        if ($this->username != NULL) {
            $data['form_header'] = 'BroadCast Whatsapp';
            $data['icon'] = 'icon-list';
            $data['form_reload'] = 'wa/message/send';
            $this->load->model('webshop/m_api_sms', 'api_sms');
            $data['listBatchBlmTerkirim'] = $this->api_sms->listBatchBlmTerkirim();
            $this->setTemplate('backend/klinkpromo/formWa', $data);
          } else {
            redirect('backend', 'refresh');
          }
    }

    //$route['wa/listSend'] = 'webshop/Api_whatsapp/listSendWA';
    public function listSendWA() {
        $data = $this->input->post(NULL, TRUE);
        if($data['tipeAct'] == "info_bv") {
           $arrParam = array(
               "resend" => $data['resend']
           );

           
                $res = $this->wa->getInfoBvArrayReport($arrParam);

                    
                $arrTable = array(
                        "id" => "tbl1",
                        "header" => "Data List Info BV",
                        "column" => array(
                            "Id", "Kepada", "HP", "Period", "Tgl Create", "Tgl Kirim", "PBV", "GBV", "PGBV", "LGBV", "note", "Act"
                        ),
                        "record" => $res
                );
                echo generateTable($arrTable);
           
        } else if($data['tipeAct'] == "salam_sehat") {
            $arrParam = array(
                "resend" => $data['resend']
            );

            $res = $this->wa->getListSamaSehat($arrParam);

            $arrTable = array(
                "id" => "tbl1",
                "header" => "Data List Info Salam Sehat",
                "column" => array(
                    "ID Member", "Nama", "HP",  "Tgl Buat", "Status", "Kirim", "Tgl Kirim", "Response", "Act" 
                ),
                "record" => $res
            );
            echo generateTable($arrTable);
        } else if($data['tipeAct'] == "info_rank") {
            $arrParam = array(
                "resend" => $data['resend'],
            );

            if($data['periode'] !== null && $data['periode'] !== "") {
                $arrParam['period'] = $data['periode'];
            }

            $res = $this->wa->getListInfoPeringkat($arrParam);

            $arrTable = array(
                "id" => "tbl1",
                "header" => "Data List Info Peringkat",
                "column" => array(
                    "No", "ID", "Nama",  "No HP", "Peringkat", "Periode", "Status Kirim", "Tgl Kirim", "message ID", "Act" 
                ),
                "record" => $res
            );
            echo generateTable($arrTable);
        }  else if($data['tipeAct'] == "info_1000BV") {
            $arrParam = array(
                "resend" => $data['resend'],
                
            );

            if($data['periode'] !== null && $data['periode'] !== "") {
                $arrParam['period'] = $data['periode'];
            }

            //print_r($arrParam);

            $res = $this->wa->getListQualifier1000BV($arrParam);
            if($res == null) {
                echo setErrorMessage("No record found..");
                return;
            }    
            $arrTable = array(
                "id" => "tbl1",
                "header" => "Data List Blast 1000 BV Qualifier",
                "column" => array(
                    "No", "ID", "Nama", "No HP", "Period", "Jumlah BV", "Status Kirim", "Tgl Kirim", "message ID", "Act" 
                ),
                "record" => $res
            );
            echo generateTable($arrTable);

        }  //else if ($data['tipeAct'] == "PR20201111") {
        else if($data['tipeAct'] == "20201220") {    
            
                $arrParam = array(
                    //"max" => 20,
                    //"TEL_HP" => "'087780441874','081514707029', '081807278131'",
                    "resend" => $data['resend'],
                    "NMPROMO" => $data['tipeAct'],
                    "table" => "NH_PROMO2",
                    "cod" => "1"
                );
            
            $res = $this->wa->getBlastPromoNov($arrParam);
            /* echo "<pre>";          
            print_r($res);
            echo "</pre>"; */
            $arrTable = array(
                "id" => "tbl1",
                "header" => "Data List Blast Promo 20 Des 2020",
                "column" => array(
                    "Id","ID Member", "Nama", "HP", "Message ID", "Tgl Kirim", "Act"
                ),
                "record" => $res
            );
            echo generateTable($arrTable);
        } else if($data['tipeAct'] == "covid_img") {    
            $table = "klink_mlm2010.dbo.NH_WA_COVID2";
            $arrParam = array(
                "resend" => $data['resend'],
                "table" => $table
            );
            
            $res = $this->wa->getBlastListDataFromTable($arrParam);

            $arrTable = array(
                "id" => "tbl1",
                "header" => "Data List Blast Starterkit Covid",
                "column" => array(
                    "Id","ID Member", "Nama", "HP", "Message ID", "Tgl Kirim", "Act"
                ),
                "record" => $res
            );
            echo generateTable($arrTable);
        }    

    }

    //$route['wa/sendAction'] = 'webshop/Api_whatsapp/actionSendWa';
    public function actionSendWa() {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        $data = $this->input->post(NULL, TRUE);
        if($data['tipeAct'] == "info_bv") {
            $arrParam = array(
                "resend" => $data['resend']
            );
            $send = $vchapi->sendWhatsappInfoBv($arrParam);
            /* echo "<pre>";
            print_r($send);
            echo "<pre>"; */
        } else if ($data['tipeAct'] == "salam_sehat") {
            $this->sendSalamSehat();
        } else if($data['tipeAct'] == "info_1000BV") {
            $arrParam = array(
                "resend" => $data['resend'],
                "max" => 10
            );
            $res = $this->wa->getListQualifier1000BvArray($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->kirimQualifier1000Bv($res);
        }  else if($data['tipeAct'] == "20201220") {    
            
            $arrParam = array(
                "max" => 40,
                //"TEL_HP" => "'087780441874','081514707029', '081807278131'",
                "resend" => $data['resend'],
                "NMPROMO" => $data['tipeAct'],
                "table" => "NH_PROMO2",
                "cod" => "1"
            );
        
            $res = $this->wa->getBlastPromoNovArray($arrParam);
            /* echo "<pre>";          
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->sendPromo1111($res);
            $hasil2 = jsonTrueResponse($hasil, "Pesan WA sudah diproses");
            echo json_encode($hasil2);
            return;
        }
    }

    //$route['wa/scheduled/sendinfo_rank/(:any)'] = 'webshop/Api_whatsapp/scheduledWaSendRank/$1';

    //$route['wa/scheduled/send/(:any)'] = 'webshop/Api_whatsapp/scheduledWaSend/$1';
    public function scheduledWaSend($param) {
       
        if($param == "info_bv") {
            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();
            
            $arrParam = array(
                "resend" => "N"
            );
            $send = $vchapi->sendWhatsappInfoBv($arrParam);
            echo "<pre>";
            print_r($send);
            echo "<pre>";
            http_response_code(200);
            
        } else if($param == "salam_sehat")  {
            $this->sendSalamSehat();
        } else if($param == "info_rank") {
            $arrParam = array(
                "resend" => "N"
            );
            $res = $this->wa->getListInfoPeringkatToSend($arrParam);
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */

             $hasil = $this->kirimWaInfoPeringkat($res);

            /*if(array_key_exists("messages", $hasil)) {

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;

                $this->wa->updateSendPeringkat($data['id'], $msgId);
                $trueRes = jsonTrueResponse($msgId, "Whatsapp sudah diproses..");
                echo json_encode($trueRes);
            }  */   
        } else if($param == "info_1000BV") {
            $arrParam = array(
                "resend" => "N",
                "period" => "2021-01-01",
                "max" => 10
            );
            $res = $this->wa->getListQualifier1000BvArray($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->kirimQualifier1000Bv($res);
            $hasil2 = jsonTrueResponse($hasil, "Pesan WA sudah diproses");
            echo json_encode($hasil2);
            return;
        }  //else if($param == "PR20201111") {
            else if($param == "20201220") {    
                $arrParam = array(
                    "resend" => "N",
                    "max" => 40,
                    "table" => "NH_PROMO2",
                    "cod" => "1"
                );
           /*  $arrParam = array(
                "resend" => "N",
                "max" => 10,
                "cod" => "1"
            ); */
            
            $res = $this->wa->getBlastPromoNovArray($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->sendPromo1111($res);
            $hasil2 = jsonTrueResponse($hasil, "Pesan WA sudah diproses");
            echo json_encode($hasil2);
            return;
        } else if($param == "covid_img") {

            $table = "klink_mlm2010.dbo.NH_WA_COVID2";
            $arrParam = array(
                //"resend" => "N",
                "id" => 10768,
                "table" => $table,
                //"max" => 10
            );
            
            $res = $this->wa->getListDataFromTable($arrParam);

            echo "<pre>";
            print_r($res);
            echo "</pre>";

            if($res == null) {
                $hresp = jsonFalseResponse(null, "No Data");
                echo json_encode($hresp);
                return;
            }

            
            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();

            $parambody[0] = "akademiinspiradzi.com";

            $param = array(
                "table" => $table,
                "data" => $res,
                "template" => "akademi_inspiradzi_vid",
                //"template" => "imun_5_img",
                "header" => array(
                    //"image" => "https://www.k-net.co.id/assets/imun_5_img.jpg"
                    "video" => "https://www.k-net.co.id/assets/aina1000bv.mp4"
                    //"image" => "https://www.k-net.co.id/assets/COVID19mar2.jpg"
                    //"video" => "https://www.k-net.co.id/assets/salam_sehat_3.mp4"
                ),
                "body" => $parambody,
                "vchapi" => $vchapi    
            ); 

            
            $call = $this->runSendWa($param);  
                  
        } else if($param == "digital_network") {
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA";
            $arrParam = array(
                "resend" => "N",
                //"id" => 1,
                "table" => $table,
                //"JOINTDT" => " >= '2021-02-01'",
                "max" => 10,
                "ORDER BY" => "id"
            );

            $res = $this->wa->getListDataFromTable($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                echo jsonFalseResponse("Kosong");
                return;
            }

            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();

            $param = array(
                "table" => $table,
                "data" => $res,
                "template" => "digital_network",
                "header" => array(
                    "video" => "https://www.k-net.co.id/assets/wa_blast/digital_network.mp4"
                    
                ),
                "vchapi" => $vchapi    
            ); 

            
            $call = $this->runSendWa($param);

        } else if($param == "digital_network2") {
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA2";
            $arrParam = array(
                "resend" => "N",
                //"id" => 1,
                "table" => $table,
                //"JOINTDT" => " >= '2021-02-01'",
                "max" => 10,
                "ORDER BY" => "id"
            );

            $res = $this->wa->getListDataFromTable($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                echo jsonFalseResponse("Kosong");
                return;
            }

            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();

            $param = array(
                "table" => $table,
                "data" => $res,
                "template" => "digital_network",
                "header" => array(
                    "video" => "https://www.k-net.co.id/assets/wa_blast/digital_network2.mp4"
                    
                ),
                "vchapi" => $vchapi    
            ); 

            
            $call = $this->runSendWa($param);

        } else if($param == "newapp_blast_sms") {

            $table = "klink_mlm2010.dbo.NEW_APP_BLASTSMS";
            $arrParam = array(
                "resend" => "N",
                //"id" => 10768,
                "table" => $table,
                "JOINTDT" => " >= '2021-02-01'",
                "max" => 10,
                "ORDER BY" => "JOINTDT"
            );
            
            $res = $this->wa->getListDataFromTable($arrParam);
            if($res === null) {
                echo jsonFalseResponse("Kosong");
                return;
            }

            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */

            /* $arrParam['result'] = $res;
            $this->sendSMS($arrParam); */

            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();

            $parambody[0] = "akademiinspiradzi.com";

            $param = array(
                "table" => $table,
                "data" => $res,
                "template" => "akademi_inspiradzi_vid",
                "header" => array(
                    //"image" => "https://www.k-net.co.id/assets/imun_5_img.jpg"
                    "video" => "https://www.k-net.co.id/assets/aina1000bv.mp4"
                ),
                "body" => $parambody,
                "vchapi" => $vchapi    
            ); 

            
            $call = $this->runSendWa($param);  
                  
        }
    }

    //$route['wa/sendtemplate/(:any)'] = 'webshop/Api_whatsapp/kirimWaTemplate/$1';
    public function kirimWaTemplate($nama_template) {
        if($nama_template == "kmart_1") {
            $video = "kmart_1";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA";
            $jenis_template = "digital_network";
        } else if($nama_template == "kmart_2") {
            $video = "kmart_2";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA2";
            $jenis_template = "digital_network";
        } else if($nama_template == "kmart_3") {
            $video = "kmart_3series";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA3";
            $jenis_template = "kmart3_series";
        } else if($nama_template == "kmart_4") {
            $video = "kmart_4";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA4";
            $jenis_template = "kmart4_series";
        } else if($nama_template == "kmart_5") {
            $video = "kmart_5";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA5";
            $jenis_template = "kmart5_series";
        } else if($nama_template == "kmart_6") {
            $video = "kmart_6";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA6";
            $jenis_template = "kmart6_series";
        } else if($nama_template == "kmart_7") {
            $video = "kmart_7";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA7";
            $jenis_template = "kmart7_series";
        } else if($nama_template == "kmart_8") {
            $video = "kmart_8";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA8";
            $jenis_template = "kmart8_series";
        } else if($nama_template == "kmart_9") {
            $video = "kmart_9";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA9";
            $jenis_template = "kmart9_series";
        } else if($nama_template == "kmart_10") {
            $video = "kmart_10";
            $table = "klink_mlm2010.dbo.KMART_BLAST_WA10";
            $jenis_template = "kmart10_series";
        }

        //$table = "klink_mlm2010.dbo.KMART_BLAST_WA2";
            $arrParam = array(
                //"resend" => "N",
                //"id" => array(141459, 141460),
                "id" => array(1),
                //"id" => 1,
                "table" => $table,
                //"JOINTDT" => " >= '2021-02-01'",
                "max" => 15,
                "ORDER BY" => "id"
            );

            $res = $this->wa->getListDataFromTableV2($arrParam);
            if($res === null) {
                echo jsonFalseResponse("Kosong");
                return;
            }

            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */

            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();

            $param = array(
                "table" => $table,
                "data" => $res,
                "template" => $jenis_template,
                "header" => array(
                    "video" => "https://www.k-net.co.id/assets/wa_blast/$video.mp4"
                    
                ),
                "vchapi" => $vchapi    
            );
            
            echo "<pre>";
            print_r($param);
            echo "</pre>";

            
            $call = $this->runSendWa($param);
    }



    private function sendSMS($array) {
        $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
        foreach($array['result'] as $dta) {
            $text = "ANDA PERNAH GAGAL BERBISNIS DAN SULIT MENCAPAI 1000 BV? INI DIA SOLUSINYA! 1000 BV Itu Mudah! Terbukti 90% mereka yang sukses mencapai 1000 BV adalah mereka yang login di website ini! Cukup Login ke akademiinspiradzi.com dan anda bisa seperti mereka! Sekarang giliran Anda karena 1000 BV itu mudah!";
            $text .= " Just klik: https://youtu.be/swhJ_insPFE";

            $hp = $dta['hp'];        
            $no_hp2 = noHpConvert($hp);

            $hasil = smsTemplate($no_hp2, $text);
 
            //$hasil = "0-8MjAyMTAzMTIxMTQyMjg5NDg3NTczNTgU";
            $resp = explode("-", $hasil);

            /* echo $dta['id'];
            echo "<br />";
            echo $resp[1]; */

            $arrUpd= array(
                "response" => $hasil,
                "messageID" => $resp[1],
                "status_send_wa" => "Y",
                "send_wa_dt" => date("Y-m-d H:i:s")
            );
            
            $dbqryx->where('id',$dta['id']);
            $dbqryx->update($array['table'], $arrUpd);

            $trueRes = jsonTrueResponse(null, "Whatsapp sudah diproses..");
            echo json_encode($trueRes);

            sleep(2);
        }
    }

    private function runSendWa($param) {
        

        $table = $param['table'];
        $res = $param['data'];
        
        $templateName = $param['template'];
        $vchapi = $param['vchapi'];


        foreach($res as $resx) {

            $hp = $resx['hp'];        
            $no_hp2 = noHpConvert($hp);

            if(array_key_exists("header", $param)) {
                $paramHeader = $param['header'];
                $arrInput['header'] = array(
                    $paramHeader
                );
            } /* else {
                $arrInput['header'] = array();
            } */
            
            $arrInput['msidn'] = $no_hp2;
            $arrInput['namespace'] = "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168";
            $arrInput['templateName'] = $templateName;

            if(array_key_exists("body", $param)) {
                $arrInput['body'] = $param['body'];
            }    
            $template = $vchapi->setTemplateMedia($arrInput);

            /* echo "<pre>";
            echo print_r(json_encode($template));
            echo "</pre>"; */
            

            $hasil = $vchapi->sendRequestApi($template);

            echo json_encode($hasil);
            if(array_key_exists("messages", $hasil)) {
                //$dfno = $resx->DFNO;
                $resJ = json_encode($hasil);
                $msg = $hasil->messages;
                $msgId = $msg[0]->id;

                $arrUpd= array(
                    "response" => $resJ,
                    "messageID" => $msgId,
                    "status_send_wa" => "Y",
                    "send_wa_dt" => date("Y-m-d H:i:s")
                );

                $dbqryx  = $this->load->database("klink_mlm2010", TRUE);
                $dbqryx->where('id',$resx['id']);
                $dbqryx->update($table, $arrUpd);

                $trueRes = jsonTrueResponse(null, "Whatsapp sudah diproses..");
                echo json_encode($trueRes);
                
                echo "msg id: ".$msgId;
                echo date("Y-m-d H:i:s");
                echo "<br />";
            } 

            sleep(2); 
        } 
    }

    //$route['wa/scheduled/period/(:any)/(:any)'] = 'webshop/Api_whatsapp/scheduledWaSendPeriod/$1/$2';
    public function scheduledWaSendPeriod($table, $period) {
        if($table == "info_1000BV") {
            $arrParam = array(
                "resend" => "N",
                "max" => 10,
                "period" => $period
            );
            $res = $this->wa->getListQualifier1000BvArray($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->kirimQualifier1000Bv($res);
            $hasil2 = jsonTrueResponse($hasil, "Pesan WA sudah diproses");
            echo json_encode($hasil2);
            return;
        } else if($table == "info_rank") {
            $arrParam = array(
                "resend" => "N",
                "period" => $period
            );
            $res = $this->wa->getListInfoPeringkatToSend($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */

            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            

             $hasil = $this->kirimWaInfoPeringkat($res);

            if(array_key_exists("messages", $hasil)) {

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;

                $this->wa->updateSendPeringkat($data['id'], $msgId);
                $trueRes = jsonTrueResponse($msgId, "Whatsapp sudah diproses..");
                echo json_encode($trueRes);
            }    
        }
    }

    //$route['wa/sendActionById'] = 'webshop/Api_whatsapp/actionSendWaById';
    public function actionSendWaById() { 
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        $data = $this->input->post(NULL, TRUE);
        
        if($data['tipeAct'] == "info_bv") {
            $arrParam = array(
                "id" => $data['id']
            );
            $send = $vchapi->sendWhatsappInfoBv($arrParam);
            echo json_encode($send);
        } else if($data['tipeAct'] == "salam_sehat") { 

            $res = $this->wa->listSalamSehatById($data['id']);
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            foreach($res as $resx) {

                $hp = $resx->TEL_HP;        
                $no_hp2 = noHpConvert($hp);
                
                $arrInput = array(
                    "header" => array(
                        array(
                            //"image" => "https://www.k-net.co.id/assets/promo20201220.jpeg",
                            //"document" => "https://www.k-net.co.id/assets/files/KODE_ETIK_PT_K-LINK.pdf"
                            "video" => "https://www.k-net.co.id/assets/salam_sehat_3.mp4"
                            //https://www.k-net.co.id/assets/COVID19-maret.mp4
                            )
                    ),
                    "msidn" => $no_hp2,
                    "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
                    //"templateName" => "imunitas_2_vid",
                    //"templateName" => "imunitas_1_pdf",
                    "templateName" => "imun_5_vid",
                    //"templateName" => "video_1",
                    //"body" => $param
                );
        
                $template = $vchapi->setTemplateMedia($arrInput);
                /* echo "<pre>";
                echo json_encode($template);
                echo "</pre>"; */

                $hasil = $vchapi->sendRequestApi($template);
                if(array_key_exists("messages", $hasil)) {
                    $dfno = $resx->DFNO;
                    $resJ = json_encode($hasil);
                    $msg = $hasil->messages;
                    $msgId = $msg[0]->id;
                    $this->wa->updateSalamSehat($dfno, $resJ, $msgId);
                    $trueRes = jsonTrueResponse(null, "Whatsapp sudah diproses..");
                    echo json_encode($trueRes);
                } 
            }
        }  else if($data['tipeAct'] == "info_rank") { 
            $res = $this->wa->getListInfoPeringkatById($data['id']);
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->kirimWaInfoPeringkat($res);

             
        }  else if($data['tipeAct'] == "info_1000BV") {
            $arrParam = array(
                "id" => $data['id']
            );
            $res = $this->wa->getListQualifier1000BvArray($arrParam);
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->kirimQualifier1000Bv($res);
            $hasil2 = jsonTrueResponse($hasil, "Pesan WA sudah diproses");
            echo json_encode($hasil2);
            //return;
        } //else if($data['tipeAct'] == "PR20201111") {
            else if($data['tipeAct'] == "20201220") {    
            
            $arrParam = array(
                "id" => $data['id'],
                "NMPROMO" => $data['tipeAct'],
                "table" => "NH_PROMO2",
                "cod" => "1"
            );
            
            $res = $this->wa->getBlastPromoNovArray($arrParam);
            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */
            if($res === null) {
                $res = jsonFalseResponse("Data tidak ditemukan");
                echo json_encode($res);
                return;
            }

            $hasil = $this->sendPromo1111($res);
            $hasil2 = jsonTrueResponse($hasil, "Pesan WA sudah diproses");
            echo json_encode($hasil2);
            //return;
        }  else if($data['tipeAct'] == "covid_img") {
            $table = "klink_mlm2010.dbo.NH_WA_COVID2";
            $arrParam = array(
                "id" => $data['id'],
                "table" => $table,
            );
            
            $res = $this->wa->getListDataFromTable($arrParam);

            /* echo "<pre>";
            print_r($res);
            echo "</pre>"; */

            $this->load->library('whatsapp_api');
            $vchapi = new whatsapp_api();

            $param = array(
                "table" => $table,
                "data" => $res,
                "template" => "imun_5_vid",
                "header" => array(
                    //"image" => "https://www.k-net.co.id/assets/imun_5_img.jpg"
                    //"video" => "https://www.k-net.co.id/assets/COVID19-maret.mp4"
                    "video" => "https://www.k-net.co.id/assets/COVID19mar2.mp4"
                    //"video" => "https://www.k-net.co.id/assets/salam_sehat_3.mp4"
                ),
                "vchapi" => $vchapi    
            ); 
            
            $call = $this->runSendWa($param);
        }
    }

    public function sendPromo1111($dtax) {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();

        $arrSukses = array();

        /* echo "<pre>";
        print_r($dtax);
        echo "</pre>"; */
        
        foreach($dtax as $res) {
            $hp = $res['hp'];
            $no_hp = preg_replace("/[^A-Za-z0-9]/", "",$hp);
            $out = ltrim($no_hp, "0");
            if(substr($out, 0, 2) == "62") {
                $no_hp2 = $out;
            } else {
                $no_hp2 = "62".$out;
            }

            $arrInput = array(
                "header" => array(
                    array("image" => "https://www.k-net.co.id/assets/promo20201220.jpeg")
                ),
                "msidn" => $no_hp2,
                "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
                "templateName" => "layout",
                //"body" => $param
            );
    
            $template = $vchapi->setTemplateMedia($arrInput);

            $hasil = $vchapi->sendRequestApi($template);

             if(array_key_exists("messages", $hasil)) {

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;

                $paramUpd = array(
                    "messageid" => $msgId,
                    "status_send_wa" => "1"
                );

                 $recId = $res['id'];
                $this->wa->updateWaSendDataBv("klink_mlm2010.dbo.NH_PROMO2", $recId, $paramUpd); 
                
                $arrData = array(
                    "no_hp" => $no_hp2,
                    "messageId" => $msgId
                );
                array_push($arrSukses, $arrData);
            }   
        }     

        return $arrSukses;
    }

    public function kirimQualifier1000Bv($dtax) {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();

        $arrSukses = array();
        foreach($dtax as $res) {
            $hp = $res['hp'];
            $no_hp = preg_replace("/[^A-Za-z0-9]/", "",$hp);
            $out = ltrim($no_hp, "0");
            if(substr($out, 0, 2) == "62") {
                $no_hp2 = $out;
            } else {
                $no_hp2 = "62".$out;
            }

            $param[0] = trim($res['dfno']);
            $param[1] = trim($res['fullnm']);
            $param[2] = trim($res['period']);
            //$param[3] = $res->periode;

            $arrInput = array(
                //"media_link" => "https://www.k-net.co.id/assets/peringkat/".$array['media_link'],
                "msidn" => $no_hp2,
                "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
                "templateName" => "qualified_bv_2",
                "body" => $param
            );
    
            

            /* $hasil = $vchapi->setTemplateMedia($arrInput);
            echo "<pre>";
            echo json_encode($hasil);
            echo "</pre>"; */

            $hasil = $vchapi->sendTemplateMedia($arrInput);
            if(array_key_exists("messages", $hasil)) {

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;

                $paramUpd = array(
                    "messageid" => $msgId,
                    "status_send_wa" => "1"
                );

                $recId = $res['id'];
                $this->wa->updateWaSendDataBvQual($recId, $paramUpd);
                
                $arrData = array(
                    "no_hp" => $no_hp2,
                    "messageId" => $msgId
                );
                array_push($arrSukses, $arrData);
            } 
        }     

        return $arrSukses;
    }

    public function kirimWaInfoPeringkat($datax) {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();

        foreach($datax as $res) {
            $hp = $res->hp;
            $no_hp = preg_replace("/[^A-Za-z0-9]/", "",$hp);
            $out = ltrim($no_hp, "0");
            if(substr($out, 0, 2) == "62") {
                $no_hp2 = $out;
            } else {
                $no_hp2 = "62".$out;
            }

            $param[0] = $res->dfno;
            $param[1] = $res->fullnm;
            $param[2] = $res->ranknm;
            $param[3] = $res->periode;

            $arrParam = array(
                "body" => $param,
                "no_hp" => $no_hp2,
                "media_link" => $res->filenm
            );
           
            $hasil = $vchapi->sendInfoPeringkat($arrParam);
            //return $hasil;

            if(array_key_exists("messages", $hasil)) {

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;

                $recId = $res->id;
                $this->wa->updateSendPeringkat($recId, $msgId);
                $trueRes = jsonTrueResponse($msgId, "Whatsapp sudah diproses..");
                echo json_encode($trueRes);
                echo "<br />";
            }   
            //print_r($hasil);
            //echo json_encode($hasil);
            /* if(array_key_exists("messages", $hasil)) {

            }  */
        }    
    }

    //$route['wa/infobv/send'] = 'webshop/Api_whatsapp/sendInfoBV';
    public function sendInfoBV() {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        $send = $vchapi->sendWhatsappInfoBv();
        echo "<pre>";
        print_r($send);
        echo "<pre>";
    }

    //$route['wa/listbatch/(:any)'] = 'webshop/Api_whatsapp/listBatchWa/$1';
    public function listBatchWa($param) {
        if($param == "" || $param == null) {
            $res = jsonFalseResponse("Parameter required");
            echo json_encode($res);
            return;
        }

        if($param == "info_bv") {
            $tbl = "klink_mlm2010.dbo.wa_info_bv_dummy";
            $res = $this->wa->listBatchWaInfoBv($tbl);
        }

        echo json_encode($res);
    }

    

    //$route['wa/infobv/resend'] = 'webshop/Api_whatsapp/resendInfoBV';
    public function resendInfoBV() {
        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        $arrParam = array(
            "resend" => "y"
        );
        $send = $vchapi->sendWhatsappInfoBv($arrParam);
        echo "<pre>";
        print_r($send);
        echo "<pre>";
    }

    //$route['wa/replyConfirm'] = 'webshop/api_whatsapp/receiveReplyCodConfirm';
    public function receiveReplyCodConfirm() {
        $json = file_get_contents('php://input');

        // Converts it into a PHP object
        $data = json_decode($json);
        /* echo "<pre>";
        print_r($data);
        echo "</pre>"; */
        if(!property_exists($data, "messages")) {
            $arr = array("response" => "false", "messsage" => "Param messages required..");
            echo json_encode($arr);
            return;
        }

        $this->decodeReply($data);
    }

    public function decodeReply($var) {

        $this->load->library('whatsapp_api');
		$vchapi = new whatsapp_api();

        $formBody = $var->messages;
        foreach($formBody as $dtax) {
            if($dtax->type == "text") {
                $explodedStr = explode("|", $dtax->text->body);
                //cod confirm
                if(count($explodedStr) === 3 && ($explodedStr[0] === "COD" || $explodedStr[0] === "cod")) {
                   $hasil = $this->wa->updateConfimCod($explodedStr[2], $explodedStr[1], $formBody);
                   $no_tujuan = $dtax->from;
                  
                   //print_r($hasil);
                   $vchapi->sendReply($no_tujuan, $hasil['message']); 
                   
                } 
            }
        }
  
    }

    //$route['wa/teskirim'] = 'webshop/api_whatsapp/tesSendReply';
    public function tesSendReply() {
        $this->load->library('whatsapp_api');
		$vchapi = new whatsapp_api();
        $res = $vchapi->sendReply("6287780441874", "Terima Kasih atas konfirmasinya");
        echo json_encode($res);
    }

    //$route['wa/import'] = 'webshop/api_whatsapp/importWaReport';
    public function importWaReport() {
        if ($this->username != null) {
            //$this->load->library('csvreader');
            $data['form_header'] = "Import Whatsapp Delivery Report";
            $data['icon'] = "icon-pencil";
            $data['form_reload'] = 'wa/import';
            //$this->setTemplate($this->folderView.'sgoImportForm', $data);
            //echo "sds";
            $this->setTemplate('backend/klinkpromo/sgoImportForm', $data);
        } else {
            redirect('backend', 'refresh');
        }
    }

    //$route['wa/import/preview'] = 'webshop/api_whatsapp/previewReport';
    public function previewReport() {
        $this->load->library('csvreader');
        $fileName = $_FILES["myfile"]["tmp_name"];
        if ($fileName != "") {
            $this->csvreader->set_separator(",");
            $data['csvData'] = $this->csvreader->parse_file($fileName);
            //print_r($data['csvData']);
            //print_r($data['csvData'][1]);
            $arr = array();
            $i = 0;
            foreach($data['csvData'] as $field) {
             //if($field[14] == "failed") {   
                $arr[$i]['messageId'] = $field[0];   
                $arr[$i]['msidn'] = $field[2];
                $arr[$i]['sentDt'] = $field[10];
                $arr[$i]['status'] = $field[14];
                $arr[$i]['failReason'] = $field[15];
                $i++;
             //}
            }	

            $arrTable = array(
                "id" => "tbl1",
                "header" => "Whatsapp Delivery Report",
                "column" => array(
                    "Message ID", "No HP", "Sent Dt",  "Status", "Reason" 
                ),
                "record" => $arr
            );
            echo generateTable($arrTable);

            /* echo "<pre>";
            print_r($data['csvData']);
            echo "</pre>"; */
            //$this->load->view($this->folderView.'sgoPreviewFile', $data);
        } else {
            //echo "No Data Uploaded";
            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";
            
        }
    }

    //$route['wa/import/previewExcel'] = 'webshop/api_whatsapp/previewReportExcel';
    public function previewReportExcel() {
        $this->load->library('csvreader');
        $fileName = $_FILES["myfile"]["tmp_name"];
        if ($fileName != "") {
            $this->csvreader->set_separator(",");
            $data['csvData'] = $this->csvreader->parse_file($fileName);
            //print_r($data['csvData']);
            //print_r($data['csvData'][1]);
            $arr = array();
            $i = 0;
            foreach($data['csvData'] as $field) {
             if($field[14] == "failed") {   
                $arr[$i]['messageId'] = $field[0];   
                $arr[$i]['msidn'] = "'".$field[2];
                $arr[$i]['sentDt'] = $field[10];
                $arr[$i]['status'] = $field[14];
                $arr[$i]['failReason'] = $field[15];
                $i++;
             }
            }	

            $arrTable = array(
                "id" => "tbl1",
                "header" => "Whatsapp Delivery Report",
                "column" => array(
                    "Message ID", "No HP", "Sent Dt",  "Status", "Reason" 
                ),
                "record" => $arr,
                "datatable" => false
            );
            //echo generateTable($arrTable);

            header("Content-type: application/vnd.ms-excel; name='excel'");
            header("Content-Disposition: Attachment; filename=reportBnsStk.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            date_default_timezone_set("Asia/Jakarta"); 
            $res = $this->generateExcell($arrTable);
            echo $res;

            /* echo "<pre>";
            print_r($data['csvData']);
            echo "</pre>"; */
            //$this->load->view($this->folderView.'sgoPreviewFile', $data);
        } else {
            echo "No Data Uploaded";
        }
    }

    //$route['wa/import/save'] = 'webshop/api_whatsapp/saveReport';
    public function saveReport() {

    }

    //$route['wa/failed/save'] = 'webshop/api_whatsapp/saveFailed';
    public function saveFailed() {
        $this->load->library('csvreader');
        $fileName = $_FILES["myfile"]["tmp_name"];
        $formX = $this->input->post('template_name');
        if ($fileName != "") {
            $this->csvreader->set_separator(",");
            $data['csvData'] = $this->csvreader->parse_file($fileName);
            //print_r($data['csvData']);
            //print_r($data['csvData'][1]);
            $arr = array();
            $i = 0;
            $messageId = "";
            $messageArr = array();
            foreach($data['csvData'] as $field) {
             if($field[14] == "failed" && ($field[15] == "Unknown number" || $field[15] == "unknown contact" || $field[15] == "not a WhatsApp user")) {   
                $arr[$i]['messageId'] = $field[0];   
                $arr[$i]['msidn'] = $field[2];
                $arr[$i]['sentDt'] = $field[10];
                $arr[$i]['status'] = $field[14];
                $arr[$i]['failReason'] = $field[15];
                //print_r($arr);
                $messageId .= "'".$field[0]."',";
                array_push($messageArr, $field[0]);
                //echo $messageId;
                //echo "<br />";
                $i++;

             }
            }	

             /* echo "<pre>";
            print_r($arr);
            echo "</pre>"; */ 

            $messageId = substr($messageId, 0, -1);
            //$dataRow = $this->wa->listSalamSehatByMessageId($messageId);
            if($formX == "video_1") {
                $updRow = $this->wa->updateInvalidNoWa($messageId);
            } else if($formX == "peringkat_vid") {
                //echo $messageId;
                $updRow = $this->wa->updateInvalidNoWaPeringkat($messageId);
            }  else if($formX == "qualifed_bv") {
                $updRow = $this->wa->updateInvalidNoWaQual1000bv($messageId);
            } else if($formX == "PR20201111") {
                $updRow = $this->wa->updateInvalidNoWaPromo1111($messageId);
            } else if($formX == "imun_5_img" || $formX == "imun_5_vid") {
                //$updRow = $this->wa->updateInvalidNoWaPromo1111($messageId);

                $dbqryx  = $this->load->database("db_ecommerce", TRUE);
                $upd = array(
                    'status' => "X",
                );
                $dbqryx->where_in('messageID',$messageArr);
                $dbqryx->update('klink_mlm2010.dbo.NH_WA_COVID2',$upd);
                echo "jumlah yg gagal terkirim : ".$i;
                $updRow = 'x';
            } else if($formX == "NEW_APP_BLASTSMS") {
                $dbqryx  = $this->load->database("db_ecommerce", TRUE);
                $upd = array(
                    'status' => "X",
                );
                $dbqryx->where_in('messageID',$messageArr);
                $dbqryx->update('klink_mlm2010.dbo.NEW_APP_BLASTSMS',$upd);
                echo "jumlah yg gagal terkirim : ".$i;
                $updRow = 'x';
            }
            echo "Status update : ";
            echo "<br />";
            echo $updRow;

            //print_r($dataRow);

            /* $arrTable = array(
                "id" => "tbl1",
                "header" => "Whatsapp Failed Report",
                "column" => array(
                    "Message ID", "No HP"
                ),
                "record" => $dataRow,
            
            );
            echo generateTable($arrTable);  */

        } else {
            echo "No Data Uploaded";
        }
    }


    function generateExcell($arrParam) {
        $id = array_key_exists('id', $arrParam) ? $arrParam['id'] : "";
        $class = array_key_exists('class', $arrParam) ? $arrParam['class'] : "";
        $width = array_key_exists('width', $arrParam) ? $arrParam['width'] : "100%";

        if($arrParam['record'] == null) {
            echo "No record found..";
            return;
        }

        $field = array_keys($arrParam['record'][0]);
        $jum_field = count($field);
        $properties = null;

        $str = "<table id='$id' class='$class' width='$width'>";
        $str .= "<thead>";

        if(array_key_exists('header', $arrParam)) {    
            $str .= "<tr><th colspan='$jum_field'>$arrParam[header]</th></tr>";
        }	
        
        if(!array_key_exists('column', $arrParam)) {
            //$properties = get_object_vars($arrParam['record'][0]);
            $properties = $field;
            $str .= "<tr>";
            foreach($properties as $dta) {
                $str .= "<th>$dta</th>";
            }
            $str .= "</tr>";
        } else {
            $str .= "<tr>";
            foreach($arrParam['column'] as $dta) {
                $str .= "<th>$dta</th>";
            }
            $str .= "</tr>";
        }

        $str .= "</thead>";
        $str .= "<tbody>";
        if(array_key_exists('record', $arrParam)) {
            
            $jmsx = 0;
            if(array_key_exists('columnAlign', $arrParam)) {
                $jmsx = count($arrParam['columnAlign']);
            }

            $jmst = 0;
            if(array_key_exists('recordStyle', $arrParam)) {
                $jmst = count($arrParam['recordStyle']);
            }
            if($arrParam['record'] !== null) {
                
                //print_r($properties2);
                foreach($arrParam['record'] as $dta) {
                    $alignNumber = 0;
                    $str .= "<tr>";
                    //$rec = 0;
                    foreach($field as $paramx) {
                        $align = "";
                        if($jmsx > 0) {
                            $align = "align=".$arrParam['columnAlign'][$alignNumber];
                        }
                        $str .= "<td $align>";
                        
                        if($jmst > 0) {
                            if($arrParam['recordStyle'][$alignNumber] == "") {
                                $str .= $dta[$paramx];
                            } else if($arrParam['recordStyle'][$alignNumber] == "money") {
                                $str .= number_format($dta[$paramx], 0, ',', '.');
                            } 	
                        } else {
                            $str .= $dta[$paramx];
                        }
                        $str .= "</td>";
                        $alignNumber++;
                        //$rec++;
                    } 
                    $str .= "</tr>";
                }
            }
        }    
        $str .= "</tbody>";
        $str .= "</table>";
        return $str;
    }

    //$route['trx/confirm/(:any)'] = 'webshop/api_whatsapp/confirmPageCod/$1';
    public function confirmPageCod($orderno) {
        $data = $this->wa->getDetailTrx($orderno);
        /* echo "<pre>";
        print_r($data);
        echo "</pre>"; */

        if($data !== null) {
            $arr['status'] = "3";
            $arr['remark'] = $data['header']['messageID'];
            $arr['orderno'] = $orderno;

            /* echo "<pre>";
            print_r($arr);
            echo "</pre>"; */

            //$this->wa->updateStatusWhatsapp($arr);

            $data['ip_client'] = $_SERVER['REMOTE_ADDR'];
            $data['token'] = 'ay1uZXRfY29uZmlybWF0aW9uX2NvZA==';
            $this->load->view('nonmember_promo/konfirmasiCODwa', $data);
        } else {
            echo setErrorMessage("Data tidak ditemukan..");
        }

    }

    //$route['trx/confirmUpdate'] = 'webshop/api_whatsapp/saveConfirmCod';
    public function saveConfirmCod() {
        $data = $this->input->post(NULL, TRUE);
        
        $checkParam = $this->wa->checkParameter($data);

        if($checkParam['errCode'] !== "000") {
            echo json_encode($checkParam);
            return;
        }

        $checkOrderNo = $this->wa->getDataWhatsappDest($data['orderno']);

        if($checkOrderNo['response'] == "0") {
            $resp = array("errCode" => "105", "message" => $checkOrderNo['message']);
            echo json_encode($resp);
            return;
        }

        /* echo "<pre>";
        print_r($checkOrderNo);
        echo "</pre>"; */

        $headerCnf = $checkOrderNo['arrayData']['header'];

        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        $param = array(
            "from" => $headerCnf['hp_penerima'],
            "id" => "ABGHYod4BEGHTwIQROmh0YA39GPFU1q1Zxjp2Q",
            "text" => array("body" => $checkParam['param']),
            "timestamp" => $timestamp,
            "type" => "text"
        );

        $header = $checkOrderNo['arrayData'];
        $idmember = $header['header']['userlogin'];
        $hp_penerima = $header['header']['hp_penerima'];

        $arrParam = array (
            "orderno" => $data['orderno'],
            "status" => $data['kirim'],
            "reply" => $data['kirim'],
            "param" => json_encode($param),
            "from" => $hp_penerima,
            "user_ip" => $data['ip_address'],
            "idmember" => $idmember,
            "appname" => $data['appname']
        );


        /* echo "<pre>";
        print_r($arrParam);
        echo "</pre>"; */
        //$kirim = json_encode($arrParam);

        $this->load->library('whatsapp_api');
        $vchapi = new whatsapp_api();
        $url = "https://api.k-link.dev/api/open/CODUpdateConfirmStatus";
        $resApi = $vchapi->sendKonfirmCodApi($url, $arrParam);
        if($resApi->status == "success") {
            
            $tglConfirmn = $resApi->data->confirmeddate;
            $act_status = $data['kirim'] == "Y" ? "dikirim ke alamat yang tertera." : "dibatalkan pengirimannya";
            $proc_status = $data['kirim'] == "Y" ? "AKAN DIKIRIM" : "DIBATALKAN";

            $nama_penerima = strtoupper($headerCnf['penerima']);
            $paramBody[0] = trim($nama_penerima);
            $paramBody[1] = $tglConfirmn;
            $paramBody[2] = $headerCnf['orderno'];
            $paramBody[3] = $act_status;
            $paramBody[4] = trim($nama_penerima);
            $paramBody[5] = $proc_status;

            $arrInput = array(
                "msidn" => $headerCnf['hp_penerima'],
                //"msidn" => "6281807278131",
                //"msidn" => "6287780441874",
                "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
                "templateName" => "reply_cod_confirm",
                "body" => $paramBody
            );

            $template = $vchapi->setTemplateMedia($arrInput);
            $hasil = $vchapi->sendRequestApi($template);

            /* echo "<pre>";
            print_r($arrInput);
            print_r($template);
            print_r($hasil);
            print_r($resApi);
            echo "</pre>"; */
            

            echo "<script>alert('Terima Kasih atas konfirmasi nya..')</script>";
            $newURL = "https://www.k-net.co.id/trx/confirm/".$data['orderno'];
            //header('Location: '.$newURL);
            echo "<meta http-equiv='refresh' content='0;url=$newURL'>"; 
        }
        //
    }
    
}