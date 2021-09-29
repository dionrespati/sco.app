<?php defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp_api {
    //public $userid = "q2pync74d2aef2srfsgbdbcr";
    public $userid = "KLINKWA";
    //public $password = "8yv28rMQTK";
    public $password = "KLINKWA724";
    public $sender = "K-LINK_wa";
    public $division = "IT";
    public $klink_number = "628111989984";
    public $url = "https://messaging.jatismobile.com/index.ashx?";
    //public $url_send_message = "https://messaging.jatismobile.com/";
    public $url_send_message = "https://interactive.jatismobile.com/";
    public $interactive_username = "klink";
    public $interactive_userpwd = "klink876$";

    public $url_send_media_msg = "https://interactive.jatismobile.com/v1/messages";

    public function __construct($params = null){
        
    }

    public function getToken() {

        $CI = & get_instance();
        $CI->load->model("webshop/Api_whatsapp_model",'wa');

        $checkToken = $CI->wa->checkExpireToken();
        if($checkToken['response'] == "true") {
            $return = array(
                "response" => "true",
                "token" => $checkToken['arrayData']['token'],
                "expire" => $checkToken['arrayData']['expire'],
            );
            return $return;
        }

        $this->requestToken();
        
    }

    public function requestToken() {

        $urlx = $this->url_send_message."wa/users/login";
        $username = $this->interactive_username;
        $password = $this->interactive_userpwd;

        $auth = base64_encode($username.":".$password);
        $curl = curl_init();

        $header = array(
            "Authorization: Basic $auth",
            "Content-Type: application/json",
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "<pre>";
            print_r($arr);
            echo "<br />";
            print_r($info);
            echo "</pre>";
            return 0;
        } else {
            $arrx = json_decode($response);
            $users = $arrx->users;
            $token = $users[0]->token;
            $expire = substr($users[0]->expires_after, 0, 10);
            $CI = & get_instance();
            $CI->load->model("webshop/Api_whatsapp_model",'wa');
            $dbqryx  = $CI->load->database("db_ecommerce", TRUE);
            $arr = array(
                "token" => $token,
                "expire" => $expire,
            );
            $dbqryx->insert('whatsapp_token', $arr);
            $return = array(
                "response" => "true",
                "token" => $token,
                "expire" => $expire
            );

            smsTemplate("087780441874", "Token baru di request : $token");
            return $return;
        }
    }

    public function sendWaQualifier1000Bv($arrparam = null) {
        $CI = & get_instance();
        $CI->load->model("webshop/Api_whatsapp_model","wa");
        
        $result = $CI->wa->getListQualifier1000BvArray($arrParam);
        if($result === null) {
            return jsonFalseResponse("No Message to be sent..");
        }

        $arrReturn = array();
        $sentCount = 0;
        $sentFailed = 0;
        foreach($result as $dtax) {
            $hasil = $this->whatsappQualifier1000Bv($dtax);
            $res = $this->explodeResponseMessage($hasil);
            /* echo "<pre>";
            echo $hasil;
            echo "<br />";
            print_r($res);
            echo "</pre>"; */
            if($res['Status'] === "1") {
                $paramUpd = array(
                    "messageid" => $hasil,
                    "status_send_wa" => $res['Status']
                );
                $CI->wa->updateWaSendDataBvQual($dtax['id'], $paramUpd);
                $sentCount++;
            } else {
                $paramUpd = array(
                    "messageid" => "",
                    "status_send_wa" => $res['Status']
                );
                $CI->wa->updateWaSendDataBvQual($dtax['id'], $paramUpd);
                $sentFailed++;
            }   
        }

        $arrReturn = array(
            "response" => "true",
            "sent_success" => $sentCount,
            "sentFailed" => $sentFailed,
        );

        return $arrReturn;
    }

    public function sendWhatsappInfoBv($arrParam = null) {
        $CI = & get_instance();
        $CI->load->model("webshop/Api_whatsapp_model","wa");
        
        $result = $CI->wa->getInfoBvArray($arrParam);
        if($result === null) {
            return jsonFalseResponse("No Message to be sent..");
        }

        $arrReturn = array();
        $sentCount = 0;
        $sentFailed = 0;
        foreach($result as $dtax) {
            $hasil = $this->whatsappInfoBvSendApi($dtax);
            $res = $this->explodeResponseMessage($hasil);
            /* echo "<pre>";
            echo $hasil;
            echo "<br />";
            print_r($res);
            echo "</pre>"; */
            if($res['Status'] === "1") {
                $paramUpd = array(
                    "messageid" => $hasil,
                    "status_send_wa" => $res['Status']
                );
                $CI->wa->updateWaSendData($dtax['id'], $paramUpd);
                $sentCount++;
            } else {
                $paramUpd = array(
                    "messageid" => "",
                    "status_send_wa" => $res['Status']
                );
                $CI->wa->updateWaSendData($dtax['id'], $paramUpd);
                $sentFailed++;
            }   
        }

        $arrReturn = array(
            "response" => "true",
            "sent_success" => $sentCount,
            "sentFailed" => $sentFailed,
        );

        return $arrReturn;
    }

    public function sendWhatsappInfoBvTesting($arrParam = null) {
        $CI = & get_instance();
        $CI->load->model("webshop/Api_whatsapp_model","wa");
        
        $result = $CI->wa->getInfoBvArrayTesting($arrParam);
        if($result === null) {
            return jsonFalseResponse("No Message to be sent..");
        }

        $arrReturn = array();
        $sentCount = 0;
        $sentFailed = 0;
        foreach($result as $dtax) {
            $hasil = $this->whatsappInfoBvSendApi($dtax);
            $res = $this->explodeResponseMessage($hasil);
            /* echo "<pre>";
            echo $hasil;
            echo "<br />";
            print_r($res);
            echo "</pre>"; */
            if($res['Status'] === "1") {
                $paramUpd = array(
                    "messageid" => $hasil,
                    "status_send_wa" => $res['Status']
                );
                $CI->wa->updateWaSendData($dtax['id'], $paramUpd);
                $sentCount++;
            } else {
                $paramUpd = array(
                    "messageid" => "",
                    "status_send_wa" => $res['Status']
                );
                $CI->wa->updateWaSendData($dtax['id'], $paramUpd);
                $sentFailed++;
            }   
        }

        $arrReturn = array(
            "response" => "true",
            "sent_success" => $sentCount,
            "sentFailed" => $sentFailed,
        );

        return $arrReturn;
    }

    public function whatsappInfoBvSendApi($arr) {
        $arrParam = array(); 
        $no_hp = preg_replace('/\D/', '', $arr['hp']);
        //$no_hp = $arr['hp'];
        $arrParam[0] = $arr['kepada'];
        $arrParam[1] = $arr['period'];
        $arrParam[2] = $arr['tgl_kirim'];
        $arrParam[3] = $arr['PBV'];
        $arrParam[4] = $arr['GBV'];
        $arrParam[5] = $arr['PGBV'];
        $arrParam[6] = $arr['LGBV'];
        $arrParam[7] = $arr['note'];
        /* $arrParam[0] = $arr['kepada'];
        $arrParam[1] = $arr['PBV'];
        $arrParam[2] = "dsds";
        $arrParam[3] = "22"; */
        
        $messageParam = array(
            //"templateid" => "1610",
            "templateid" => "1722",
            "parameters" => $arrParam
        );
        $message = json_encode($messageParam);

        return $this->broadcastMessage($no_hp, $message);
    }

    public function whatsappQualifier1000Bv($arr) {
        $arrParam = array(); 
        $no_hp = preg_replace('/\D/', '', $arr['hp']);
        //$no_hp = $arr['hp'];
        $arrParam[0] = $arr['dfno'];
        $arrParam[1] = $arr['fullnm'];
        $arrParam[2] = $arr['period'];
        /*$arrParam[3] = $arr['PBV'];
        $arrParam[4] = $arr['GBV'];
        $arrParam[5] = $arr['PGBV'];
        $arrParam[6] = $arr['LGBV'];
        $arrParam[7] = $arr['note'];
        $arrParam[0] = $arr['kepada'];
        $arrParam[1] = $arr['PBV'];
        $arrParam[2] = "dsds";
        $arrParam[3] = "22"; */
        
        $messageParam = array(
            //"templateid" => "1610",
            "templateid" => "1722",
            "parameters" => $arrParam
        );
        $message = json_encode($messageParam);

        return $this->broadcastMessage($no_hp, $message);
    }

    public function sendWhatsappCodConfirm($order_id) {
        $CI = & get_instance();
        $CI->load->model("webshop/Api_whatsapp_model",'wa');
        $data = $CI->wa->getDataWhatsappDest($order_id);
        /* echo "<pre>";
        print_r($data);
        echo "</pre>"; */
        
       if($data['response'] === "1") {
            $param = $data['arrayData'];
            $hasil = $this->whatsappCoDConfirmSendApiV2($param);

            if(array_key_exists("messages", $hasil)) {
                //$upd .= "'".$dta->DFNO."',";

                $msg = $hasil->messages;
                $msgId = $msg[0]->id;
                //$this->wa->updateSalamSehat($dfno, $resJ, $msgId);

                $arr['status'] = "1";
                $arr['remark'] = $msgId;
                $arr['orderno'] = $order_id;

                $CI->wa->updateStatusWhatsapp($arr);
            }
            /* $res = $this->explodeResponseMessage($hasil);
            if($res['Status'] === "1") {
                $arr['status'] = $res['Status'];
                $arr['remark'] = $res['MessageId'];
                $arr['orderno'] = $order_id;
                $CI->wa->updateStatusWhatsapp($arr);
            } else {
                $arr['status'] = $res['Status'];
                $arr['remark'] = "";
            }
           
            return $arr; */
            //echo "masuk sini";
        
            //$CI->wa->updateStatusWhatsapp($arr);
        } else {
           echo "<pre>";
           print_r($data); 
           echo "</pre>";
        }
    }

    public function explodeResponseMessage($hasil) {
        $result = array();
        $resHasil = explode("&", $hasil);
        $jumArray = count($resHasil);
        if($jumArray > 1) {
            $status = explode("=", $resHasil[0]);
            $result[$status[0]] = $status[1];
            $message = explode("=", $resHasil[1]);
            $result[$message[0]] = $message[1];
        } else {
            $status = explode("=", $resHasil[0]);
            $result[$status[0]] = $status[1];
            $result['messageId'] = "";
        }
        return $result;
        
    }

    public function sendSalamSehat($no_hp) {
        $arrInput = array(
            "media_link" => "https://www.k-net.co.id/assets/salam_sehat_3.mp4",
            "msidn" => $no_hp,
            "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
            "templateName" => "video_1",
        );

        $hasil = $this->sendTemplateMedia($arrInput);
        return $hasil;
    }

    public function sendInfoPeringkat($array) {
        $arrInput = array(
            "media_link" => "https://www.k-net.co.id/assets/peringkat/".$array['media_link'],
            "msidn" => $array['no_hp'],
            "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
            "templateName" => "peringkat_vid",
            "body" => $array['body']
        );

        $hasil = $this->sendTemplateMedia($arrInput);
        return $hasil;
    }

    public function send1000BvQual($array) {
        $arrInput = array(
            //"media_link" => "https://www.k-net.co.id/assets/peringkat/".$array['media_link'],
            "msidn" => $array['no_hp'],
            "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
            "templateName" => "qualifed_bv",
            "body" => $array['body']
        );

        $hasil = $this->sendTemplateMedia($arrInput);
        return $hasil;
    }

    public function setTemplateMedia($arrInput) {
        $countx = 0;
        if(array_key_exists('header', $arrInput)) {

            $arrHeader = array();
            foreach($arrInput['header'] as $dtxc) {
                
                if(array_key_exists('image', $dtxc)) {
                    $linkArr['link'] = $dtxc['image'];
                    $arrPushHeader['type'] = "image";
                    $arrPushHeader['image'] =  $linkArr;
                } else if(array_key_exists('video', $dtxc)) {
                    $linkArr['link'] = $dtxc['video'];;
                    $arrPushHeader['type'] = "video";
                    $arrPushHeader['video'] = $linkArr;
                } else if(array_key_exists('document', $dtxc)) {
                    $linkArr['link'] = $dtxc['document'];;
                    $arrPushHeader['type'] = "document";
                    $arrPushHeader['document'] = $linkArr;
                } else if(array_key_exists('text', $dtxc)) {
                    $arrPushHeader['type'] = "text";
                    $arrPushHeader['text'] = $dtxc;
                }
                array_push($arrHeader, $arrPushHeader);
            }

            $arrComponents[$countx] = array(
                "type" => "header",
                "parameters" => $arrHeader
            );   
            $countx++;
        }    

        if(array_key_exists('body', $arrInput)) {
            $arrBody = array();
            foreach($arrInput['body'] as $dta) {
                $arrX1 = array(
                    "type" => "text",
                    "text" => $dta
                );
                array_push($arrBody, $arrX1);
            }

            $arrComponents[$countx] = array(
                "type" => "body",
                "parameters" => $arrBody
            );   
        }

        if(!array_key_exists('header', $arrInput) && !array_key_exists('body', $arrInput)) {
            $arrComponents = null;
        }
        
        $arr = array(
            "to" => $arrInput['msidn'],
            "recipient_type" => "individual",
            "type" => "template",
            "template" => array(
                "namespace" => $arrInput['namespace'],
                "language" => array(
                     "policy" => "deterministic", 
                     "code" => "id" 
                   ), 
                "name" => $arrInput['templateName'],
                "components" => $arrComponents
                     
             )

        );

        /* $arr['to'] = $arrInput['msidn'];
        $arr['recipient_type'] = "individual";
        $arr['type'] = "template";
        
        $isiTemplate['namespace'] = $arrInput['namespace'];
        $isiTemplate['language'] = array(
            "policy" => "deterministic", 
            "code" => "id" 
        );
        $isiTemplate['name'] = $arrInput['templateName'];
        if(empty($arrComponents)) {
            if($arrComponents !== null) {
                $isiTemplate['components'] = $arrComponents;
            }
        }

        $arr['template'] = $isiTemplate; */
        
        //$arr['recipient_type'] = "individual";

        /* echo "<pre>";
        print_r($arr);
        echo "</pre>"; */
        return $arr;
    }

    public function sendTemplateMedia($arrInput) {

        $countx = 0;
        if(array_key_exists('media_link', $arrInput)) {
            $arrComponents[$countx] = array(
                "type" => "header",
                "parameters" => array(array(
                    "type" => "video",
                    "video" => array(
                        //"link" => "https://www.k-net.co.id/assets/salam_sehat_2.mp4"
                        //"link" => "https://www.k-net.co.id/assets/salam_sehat_3.mp4"
                        "link" => $arrInput['media_link']
                    )
                ))
            );   
            $countx++;
        }    

        if(array_key_exists('body', $arrInput)) {
            $arrBody = array();
            foreach($arrInput['body'] as $dta) {
                $arrX1 = array(
                    "type" => "text",
                    "text" => $dta
                );
                array_push($arrBody, $arrX1);
            }

            $arrComponents[$countx] = array(
                "type" => "body",
                "parameters" => $arrBody
            );   
        }
        
        $arr = array(
            "to" => $arrInput['msidn'],
            "recipient_type" => "individual",
            "type" => "template",
            "template" => array(
                "namespace" => $arrInput['namespace'],
                "language" => array(
                     "policy" => "deterministic", 
                     "code" => "id" 
                   ), 
                "name" => $arrInput['templateName'],
                "components" => $arrComponents
                     
             )

        );

        /* echo "<pre>";
        print_r(json_encode($arr));
        echo "</pre>"; */

        $getToken = $this->getToken();
        if($getToken['response'] !== "true") {
            return jsonFalseResponse("Token invalid / expire");
        }

        $token = $getToken['token'];
        
        $curl = curl_init();

        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        );

        $urlx = $this->url_send_media_msg;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => json_encode($arr),
        ));

        $response = curl_exec($curl);
        //curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $info = curl_getinfo($curl);
        $err = curl_error($curl);

        if(!$err) {
            //echo $response; 
            return json_decode($response);
        } else {
            return 0;
        }
    }

    public function sendRequestApi($arr) {
        $getToken = $this->getToken();
        if($getToken['response'] !== "true") {
            return jsonFalseResponse("Token invalid / expire");
        }

        $token = $getToken['token'];
        
        $curl = curl_init();

        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        );

        $urlx = $this->url_send_media_msg;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => json_encode($arr),
        ));

        $response = curl_exec($curl);
        //curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $info = curl_getinfo($curl);
        $err = curl_error($curl);

        if(!$err) {
            //echo $response; 
            return json_decode($response);
        } else {
            return 0;
        }
    }

    public function whatsappCoDConfirmSendApiV2($arr) {
        $no_hp = $arr['header']['hp_penerima'];

        $bayar = number_format($arr['header']['total_bayar'],0,",",".");
        $arrParam = array(); 
        $arrParam[0] = $arr['header']['orderno'];
        $arrParam[1] = $arr['header']['tgl_transaksi'];
        $arrParam[2] = $bayar;
        $arrParam[3] = "COD";
        $arrParam[4] = $arr['header']['no_resi'];
        $linkURLReply = "https://www.k-net.co.id/trx/confirm/".$arr['header']['orderno'];
        $arrParam[5] = $linkURLReply;

        /* $messageParam = array(
             "templateid" => "2748",
             "parameters" => $arrParam
        );
        $message = json_encode($messageParam);
        return $this->broadcastMessage($no_hp, $message); */

        $no_hp = preg_replace("/[^A-Za-z0-9]/", "",$no_hp);
            $out = ltrim($no_hp, "0");
            if(substr($out, 0, 2) == "62") {
                $no_hp2 = $out;
            } else {
                $no_hp2 = "62".$out;
            }

        
        $arrInput = array(
            "msidn" => $no_hp2,
            "namespace" => "510bfb8a_5ea1_4c60_80bf_f8b7f3ce9168",
            "templateName" => "cod_send_confirmv2",
            "body" => $arrParam
        );

        
        $template = $this->setTemplateMedia($arrInput);

        $hasil = $this->sendRequestApi($template);
        return $hasil;
        
    }

    public function whatsappCoDConfirmSendApi($arr) {
        $no_hp = $arr[0]->tel_hp;
        $arrParam = array(); 
        $arrParam[0] = $arr[0]->datetrans;
        $arrParam[1] = $arr[0]->total_bayar;
        $arrParam[2] = $arr[0]->orderno;
        $arrParam[3] = $arr[0]->conote_new;
        $link_ya = "https://wa.me/".$this->klink_number."?text=COD%7CY%7C".$arr[0]->orderno;
        $arrParam[4] = $link_ya;
        $link_no = "https://wa.me/".$this->klink_number."?text=COD%7CN%7C".$arr[0]->orderno;
        $arrParam[5] = $link_no;
        $arrParam[6] = "COD|Y|".$arr[0]->orderno;
        $arrParam[7] = "COD|N|".$arr[0]->orderno;

        $messageParam = array(
             "templateid" => "2182",
             "parameters" => $arrParam
        );
        $message = json_encode($messageParam);
        /* echo "<pre>";
        print_r($messageParam);
        echo "NO HP : ".$no_hp;
        echo "</pre>"; */

        return $this->broadcastMessage($no_hp, $message);

        
    }

    public function broadcastMessage($no_hp, $message) {
        $paramSent = "userid=".$this->userid;
        $paramSent .= "&password=".$this->password;
        $paramSent .= "&sender=".urlencode($this->sender);
        $paramSent .= "&msisdn=".$no_hp;
        $paramSent .= "&message=".urlencode($message);
        $paramSent .= "&division=".$this->division;
        $paramSent .= "&batchname=".$this->division;
        $paramSent .= "&uploadby=".$this->sender;
        $paramSent .= "&channel="."2";
        $paramSent .= "&type="."wa";

        $urlx = $this->url.$paramSent;
        //echo $urlx;
        //$urlx = "https://www.k-net.co.id/vch/dist/check";
        $hasil = file_get_contents($urlx);
        return $hasil;
    }

    public function broadcastMessageV2($no_hp, $message) {
        $paramSent = "userid=".$this->userid;
        $paramSent .= "&password=".$this->password;
        $paramSent .= "&sender=".urlencode($this->sender);
        $paramSent .= "&msisdn=".$no_hp;
        $paramSent .= "&message=".urlencode($message);
        $paramSent .= "&division=".$this->division;
        $paramSent .= "&batchname=".$this->division;
        $paramSent .= "&uploadby=".$this->sender;
        $paramSent .= "&channel="."2";
        $paramSent .= "&type="."wa";

        $urlx = $this->url.$paramSent;
        echo $urlx;
        //$urlx = "https://www.k-net.co.id/vch/dist/check";
        //$hasil = file_get_contents($urlx);
        //return $hasil;
    }

    public function sendReply($notujuan, $pesan) {
        $getToken = $this->getToken();
        if($getToken['response'] !== "true") {
            return jsonFalseResponse("Token invalid / expire");
        }

        $token = $getToken['token'];

        $param = array (
             "recipient_type" => "individual", 
             "to" => $notujuan, 
             "type" => "text", 
             "text" => array(
                  "body" => $pesan)
        );  
        
        $urlx = $this->url_send_message."wa/messages";
        //https://interactive.jatismobile.com/wa/messages
        $curl = curl_init();

        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => json_encode($param),
        ));

        

        $response = curl_exec($curl);
        //curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $info = curl_getinfo($curl);
        $err = curl_error($curl);


        /* echo "<pre>";
        print_r($header);
        print_r(json_decode($response));
        print_r($param);
        echo "</pre>"; */

        if(!$err) {
            return json_decode($response);
        } else {
            return 0;
        }
    }

    public function sendKonfirmCodApi($urlx, $arr) {
        $curl = curl_init();

        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => json_encode($arr),
        ));

        $response = curl_exec($curl);
        //curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $info = curl_getinfo($curl);
        $err = curl_error($curl);

        if(!$err) {
            return json_decode($response);
        } else {
            return 0;
        }
    }

}    


