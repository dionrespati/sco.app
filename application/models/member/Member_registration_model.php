<?php
class Member_registration_model extends MY_Model {
		
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		
    }
	
	function getListMemberByMM($mmno) {
		$qry = "SELECT a.orderno, a.dfno, a.batchno, 
				       a.batchdt,  a.tdp, a.sc_dfno, a.sc_co, a.loccd,
				       CONVERT(VARCHAR(10), a.batchdt, 20) as batchdt
				FROM sc_newtrh a
				--INNER JOIN msmemb b ON (a.dfno = b.dfno)
				WHERE a.ttptype = 'MEMB'
				AND a.batchno = '$mmno";
		$result = $this->getRecordset($slc,null,$this->db2);
	    return $result;
	}
	
	public function cekValidVoucher($voucherno,$voucherkey) {
        $qry = "SELECT a.formno, a.activate_dfno, a.activate_by, a.status, b.fullnm,
                  CONVERT(VARCHAR(10), a.activate_dt, 20) as activate_dt, prdcd 
                FROM starterkit a
                LEFT OUTER JOIN msmemb b ON (a.activate_dfno = b.dfno) 
                WHERE a.formno = '".$voucherno."' and a.vchkey = '".$voucherkey."'";
        $result = $this->getRecordset($qry,null,$this->db2);
	    return $result;
    }
	
	function cekLimitKit($idstockist) {
        $qry = "select a.limitkit-a.arkit as limitstock,a.arkit,a.limitkit
                from mssc a where a.loccd = ?";
		$paramQry = array($idstockist);
        $result = $this->getRecordset($qry,$paramQry,$this->db2);
	    return $result;
    }

	function showStockistByArea($state) {
        
        $cc = "SELECT loccd, fullnm,state 
               FROM mssc 
               WHERE loccd = ? and sctype != '3' 
                AND fullnm not in('TERMINATION','CANCEL','BUSSINESS & DEVELOPMENT','INTERNET ID','CENCEL',
				    'UNITED STATES OF AME', 'THAILAND', 'SWITZERLAND', 
                    'SPANYOL', 'SRILANKA', 'SINGAPORE', 'JAPAN',
					'CANADA','INTERNET CODE','UNITED ARAB EMIRATES')";

        $paramQry = array($state);
        $result = $this->getRecordset($qry,$paramQry,$this->db2);
	    return $result;
    }
    
    function getListState() {
        $qry = "select st_id, description 
                FROM state where cn_id = 'ID' and description not IN ('TEMPORARY','UNITED STATES OF AME', 
                       'THAILAND', 'SWITZERLAND', 'SPANYOL', 'SRILANKA', 'SINGAPORE', 'JAPAN',
					   'CANADA','INTERNET CODE','UNITED ARAB EMIRATES')";
        $result = $this->getRecordset($qry,null,$this->db2);
	    return $result;
    }
    
    function getListStockistByState($state) {
        $qry = "SELECT loccd, fullnm 
                FROM mssc where state = ? and sctype != '3' 
                    AND fullnm not in('TERMINATION','CANCEL','BUSSINESS & DEVELOPMENT','INTERNET ID','CENCEL', 
                       'UNITED STATES OF AME', 'THAILAND', 'SWITZERLAND', 
                       'SPANYOL', 'SRILANKA', 'SINGAPORE', 'JAPAN',
					   'CANADA','INTERNET CODE','UNITED ARAB EMIRATES') 
                    AND scstatus='1' 
                    AND loccd NOT IN ('JNE', 'MKT', 'PR', 'IDJD01','PT MASS')
                    AND loccd NOT LIKE 'WR%'
		        ORDER BY sctype, fullnm ";
		$paramQry = array($state);
        $result = $this->getRecordset($qry, $paramQry, $this->db2);
	    return $result;
    }
    
    function getListBank() {
        $qry = "SELECT * 
                FROM klink_mlm2010.dbo.bank 
                WHERE bankid NOT IN('VCA','NA','BLK','CIC','BKU') 
                AND web_status='1' ORDER BY bankid";
        $result = $this->getRecordset($qry,null,$this->db2);
	    return $result;
    }
	
	function showLastkitno($stk) {
        $qry = "SELECT memberprefix, lastcodememb, lastkitno 
                FROM klink_mlm2010.dbo.mssc 
                WHERE loccd = ?";
        $paramQry = array($stk);
        $result = $this->getRecordset($qry,$paramQry,$this->db2);
	    if($result != null) {
	    	return $result;
	    } else {
	    	$input = "INSERT  into klink_mlm2010.dbo.mssc (lastkitno) VALUES(1)";
	    	$s = $this->executeQuery($input, $this->db2);
			return null;
	    }
        
        //echo $sql;
    }

	function setLastKitNo($stk) {
        $sql = "UPDATE klink_mlm2010.dbo.mssc 
                SET lastkitno = lastkitno + 1 
                WHERE loccd = ?";
         $paramQry = array($stk); 
        /*echo $sql;
        print_r($paramQry);    */    
        $this->db->query($sql, $paramQry);
        $hasil = $this->db->affected_rows();
        return $hasil;
    }
	
	function cek_seQ() {
        $this->db = $this->load->database($this->db2, true);
         $y1=date("y");
         $m=date("m");
        
        $this->db->trans_begin();
 
        $tbl = "SEQ_MEMB"."$y1"."$m";
        
        $cek = "select * from $tbl";
        
        $query = $this->db->query($cek);
        if($query->num_rows < 1)
        {
            $input = "INSERT INTO $tbl (SeqVal) VALUES('a')";
            $query = $this->db->query($input);
           
        }
        else
        {
            $input = "INSERT INTO $tbl (SeqVal) VALUES('a')";
            $query = $this->db->query($input);
        }
        
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }      
        
        return $query;
    }
	
	function get_idno()
    {
        $this->db = $this->load->database($this->db2, true);
        $y1=date("y");
         $m=date("m");
        
        $this->db->trans_begin();
 
        $tbl = "SEQ_MEMB"."$y1"."$m";
        $qry = "SELECT * FROM $tbl 
           		 WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";
        
        //echo $qry;
        $query = $this->db->query($qry);
        if($query == null)
        {
            $ss = 0;
        }
        else
        {
            foreach($query->result() as $data)
            {
                $ss = $data->SeqID;
            }  
        }
         $jumlah = $query->num_rows();
         
       	$next_seq = sprintf("%06s",$ss);
        $prefix = date('ym');
        $y =  strval($prefix.$next_seq);
         
          if ($query->result() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }      
       
         return $y;
    }

    function insert_new_memberV2($data) {
        $lastkit = $this->showLastkitno($this->stockist);   
        if($lastkit != null) {
            if($lastkit[0]->lastkitno < 99999) {
                $arr = $this->inputMember($lastkit, $data);
            } 	else {
                $setLastKid = $this->setLastKitToZero($this->stockist);
                $lastkit = $this->showLastkitno($this->stockist);
                $arr = $this->inputMember($lastkit, $data);						
            }
        } else {
            return jsonFalseResponse("Error Lastkitno..");
        }    
    }

    function getNewIdMember($lastkit) {
        $memberprefix1 = $lastkit[0]->memberprefix;
        if($lastkit[0]->memberprefix == '9999' or $lastkit[0]->memberprefix == '999') {
            $memberprefix = substr($this->stockist,2,3);
            $memberprefix1 = $memberprefix."A";
        } 

        $counter = $lastkit[0]->lastkitno + 1;
        $next_id = sprintf("%05s",$counter);
        $alph = chr($lastkit[0]->lastcodememb);
        $new_id = strval("ID".$memberprefix1.$alph.$next_id);

        return $new_id;
    }

    function accumulateInputPost($data) {
        $data['voucherno'] = $this->input->post('voucherno');
        $data['voucherkey'] = $this->input->post('voucherkey');
        $data['idsponsor'] = strtoupper($this->input->post('idsponsor'));
        $data['nmsponsor'] = strtoupper($this->input->post('nmsponsor'));
        
        $data['idrekrut'] = strtoupper($this->input->post('idrekrut'));
        $data['nmrekrut'] = strtoupper($this->input->post('nmrekrut'));
        
        $data['tgllahir'] = $this->input->post('tgllahir');
        $data['noapl'] = $this->input->post('noapl');
        $data['nmmember'] = strtoupper($this->input->post('nmmember'));
        $data['noktp'] = strtoupper($this->input->post('noktp'));
        $data['sex'] = $this->input->post('sex');
        $data['addr1'] = strtoupper($this->input->post('addr1'));
        $data['addr2'] = strtoupper($this->input->post('addr2'));
        $data['addr3'] = strtoupper($this->input->post('addr3'));
        $data['area'] = $this->input->post('area');
        $data['tel_hm'] = $this->input->post('tel_hm');
        $data['tel_hp'] = $this->input->post('tel_hp');
        $data['bnstmt'] = $this->input->post('bnstmt');
        $data['kdpos'] = $this->input->post('kdpos');
        $data['email'] = $this->input->post('email');
        $data['bankid'] = $this->input->post('bankid');
        $data['banknm'] = $this->input->post('banknm');
        $data['norek'] = $this->input->post('norek'); 
        //$data['username'] = $this->input->post('username');
        $data['regtype'] = $this->input->post('regtype');
        $data['kdpos'] = $this->input->post('kdpos');
        $data['choosevoucher'] = $this->input->post('chosevoucher');
        
        $search_for = "'";
        $replace = "`";
        
        $data['nmsponsor'] = str_replace($search_for,$replace,$data['nmsponsor']);
        $data['nmmember'] = str_replace($search_for,$replace,$data['nmmember']);
        $data['addr1'] = str_replace($search_for,$replace,$data['addr1']);
        $data['addr2'] = str_replace($search_for,$replace,$data['addr2']);
        $data['addr3'] = str_replace($search_for,$replace,$data['addr3']);
        $data['period'] = $this->get_current_period();
        $data['bonusperiod'] = date('m/d/Y', strtotime($period[0]->lastperiod));
        $data['createdt'] = date('Y-m-d H:i:s');
        $birth = explode("/", $data['tgllahir']);
        $thn = substr($birth[2], 2, 2);
        $data['now'] = date("Y-m-d");
        $data['password'] = $birth[0]."".$birth[1]."".$thn;
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['alamat3'] = $data['addr3']." ".$data['kdpos'];
        //$postcode = '000004';
        $data['postcode'] = $data['kdpos'];
        
      
        $data['tgllahir'] = $birth[2]."-".$birth[1]."-".$birth[0];

        $data['pemilik_rek'] = "";
        if($data['norek'] != "") {
            $data['pemilik_rek'] = $data['nmmember'];
        }

        return $data;
    }

    function inputMember($lastkit, $data) {
        $usr = $this->stockist;
        $new_id = $this->getNewIdMember($lastkit);
        $form = $this->accumulateInputPost($data);

        if($form['regtype']=="" || $form['regtype']=="0") {
            $test = $this->showKitPrdcd($usr);
            $regtype = $test[0]->kitprdcd;
            $totdp = $test[0]->dp;
            $totbv = $test[0]->bv;
            $pricecode = $test[0]->pricecode;
            //echo "door ".$regtype."<br>";
        } else {
            $test = $this->showKitPrdcdV($usr,$data['regtype']);
            $regtype = $test[0]->prdcd;
            $totdp = $test[0]->dp;
            $totbv = $test[0]->bv;
            $pricecode = $test[0]->pricecode;
        }

        if($data['choosevoucher'] == '1') {
            $ttptype = "MEMB";
            $ordtypee = 'V';
            $kitstatus = '1';
            $flag_approval = '1';
            $csno = "IDBL";
            $batchno = "IDBL";
            $flag_batch = '2';

            $insStarterkit = " UPDATE starterkit 
                               SET activate_dfno='".$new_id."',
                                  activate_by = '".$usr."',activate_fromip = '".$form['ip']."',
                                  activate_dt='".$form['createdt']."',status = '2'
                               WHERE formno = '".$form['voucherno']."' AND vchkey = '".$form['voucherkey']."'";
        } else {
            $ttptype = "MEMBP";
            $ordtypee = 'P';
            $kitstatus = '0';
            $flag_approval = '0';
            $csno = "";
            $batchno = "";
            $flag_batch = '0';

            $insStarterkit = "INSERT INTO starterkit 
                        (formno,vchkey,createdt,createnm,
                        status,activate_by,activate_dfno,activate_fromip,activate_dt,
                        actseq,updatenm,updatedt,prdcd,PT_SVRID) 
                        VALUES
                        ('".$new_id."','".$new_id."','".$form['createdt']."','".$usr."','2','".$usr."','".$new_id."',
                        '".$form['ip']."','".$form['createdt']."',0,'".$usr."','".$form['createdt']."','".$regtype."','ID')";
        }

        $qryTrx = $this->load->database($this->db2, true);

        $qryTrx->trans_begin();

        $y1=date("y");
        $m=date("m");

        $tbl = "SEQ_MEMB"."$y1"."$m";

        $input = "INSERT INTO $tbl (SeqVal) VALUES('a')";
        $query = $qryTrx->query($input);

        $qry = "SELECT * FROM $tbl 
                WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";
            
        $query = $qryTrx->query($qry);
        if($query == null) {
        $ss = 0;
        } else {
            foreach($query->result() as $data)
            {
                $ss = $data->SeqID;
            }  
        }
        $jumlah = $qryTrx->num_rows();
        
        $next_seq = sprintf("%06s",$ss);
        $prefix = date('ym');
        $trcd =  strval($prefix.$next_seq);

        $insSc_newtrh = "INSERT INTO sc_newtrh 
                            (trcd,trtype,trdt,dfno,loccd,tdp,taxrate,taxamt,discamt,shcharge,
                            othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                            paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                            updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                            type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                            syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                            sc_co,bnsperiod,othdisc,flag_batch,batchstatus,flag_recover,
                            system,ttptype,entrytype,flag_show,flag_approval,csno,batchno,batchdt)
                            VALUES
                            ('".$trcd."','SB1','".$form['createdt']."','".$new_id."',
                            '".$usr."',".$totdp.",10,0,0,0,0,".$totbv.",".$totbv.",
                            ".$totbv.",".$totbv.",".$totdp.",'WH001','B001','".$pricecode."',
                            '01','01','03',".$totdp.",0,0,".$totdp.",'".$usr."','".$usr."',
                            '0',0,0,0,0,'0','".$form['createdt']."','0',0,0,'1','1','0','0','0',
                            0,'0','0','0','1','ID','".$usr."','".$usr."','".$form['bonusperiod']."',
                            0,'$flag_batch','0','0','0','$ttptype',4,'1','$flag_approval','$csno','$batchno','".$form['createdt']."')";

        $insSc_newtrd = "INSERT INTO sc_newtrd 
                        (trcd,prdcd,qtyord,qtyship,qtyremain,dp,pv,bv,taxrate,sp,sb,scdisc,seqno,scdiscamt,
                            syn2web,qty_used,qty_avail,PT_SVRID,pricecode) VALUES ('".$trcd."', '".$regtype."',1,0,0,".$totdp.",
                        ".$totbv.",".$totbv.",0,0,0,0,0,0,'0',0,0,'ID','".$pricecode."')";      
                        
        $insMsmemb = "INSERT into msmemb 
                        (dfno, sfno, sfno_reg, memberid,password,
                         sponsorid, sponsorregid, fullnm,idno,birthdt,
                         sex,citizen,addr1,addr2,addr3,
                         state,postcd,country,tel_hm,tel_hp,
                         email,jointdt,regtype,bankaccno,bankaccnm,
                        bankid,branch,loccd,trdt,etdt,
                        createnm,status,autobns,activitystatusid,sentstm,
                        auto_totdnln,iplastupd,PT_SVRID,allowmemberlogin,bnsstmsc,
                        loccd_et,loccd_co,formno,vchkey,dfnotemp,
                        banknm,ordtype,kit_status,birth2dt,benbirthdt,trcd,entrytype)
        VALUES ('$new_id','".$form['idsponsor']."','".$form['idrekrut']."','$new_id','$form[password]',
                '".$form['idsponsor']."','".$form['idsponsor']."','".$nmmember."','".$form['noktp']."','".$form['tgllahir']."',
                '".$form['sex']."','ID','".$addr1."','".$addr2."','".$addr3."',
                '".$form['area']."','$postcode','ID','".$form['tel_hm']."','".$data['tel_hp']."',
                '".$form['email']."','$now','".$regtype."','".$form['norek']."','".$nmmembery."',
                '".$form['bankid']."','B001','".$usr."','$now','$now',
                '".$usr."','1',0,1,'2',
                0,'$ip','ID','1','".$form['bnstmt']."',
                '".$usr."','".$usr."','".$data['voucherno']."','".$data['voucherkey']."','".$data['noapl']."',
                '".$form['banknm']."', '".$ordtypee."','".$kitstatus."','$now','$now','".$idnoo."','$group_stk')";
    }

    function insert_new_member($new_id,$usr,$idnoo, $group_stk)
    {
        $this->db = $this->load->database($this->db2, true);
        
        $data['voucherno'] = $this->input->post('voucherno');
        $data['voucherkey'] = $this->input->post('voucherkey');
        $data['idsponsor'] = strtoupper($this->input->post('idsponsor'));
        $data['nmsponsor'] = strtoupper($this->input->post('nmsponsor'));
        
        $data['idrekrut'] = strtoupper($this->input->post('idrekrut'));
        $data['nmrekrut'] = strtoupper($this->input->post('nmrekrut'));
        
        $data['tgllahir'] = $this->input->post('tgllahir');
        $data['noapl'] = $this->input->post('noapl');
        $data['nmmember'] = strtoupper($this->input->post('nmmember'));
        $data['noktp'] = strtoupper($this->input->post('noktp'));
        $data['sex'] = $this->input->post('sex');
        $data['addr1'] = strtoupper($this->input->post('addr1'));
        $data['addr2'] = strtoupper($this->input->post('addr2'));
        $data['addr3'] = strtoupper($this->input->post('addr3'));
        $data['area'] = $this->input->post('area');
        $data['tel_hm'] = $this->input->post('tel_hm');
        $data['tel_hp'] = $this->input->post('tel_hp');
        $data['bnstmt'] = $this->input->post('bnstmt');
        $data['kdpos'] = $this->input->post('kdpos');
        $data['email'] = $this->input->post('email');
        $data['bankid'] = $this->input->post('bankid');
        $data['banknm'] = $this->input->post('banknm');
        $data['norek'] = $this->input->post('norek'); 
        //$data['username'] = $this->input->post('username');
        $data['regtype'] = $this->input->post('regtype');
        $data['kdpos'] = $this->input->post('kdpos');
        $data['choosevoucher'] = $this->input->post('chosevoucher');
        
        $search_for = "'";
        $replace = "`";
        
        $nmsponsor = str_replace($search_for,$replace,$data['nmsponsor']);
        $nmmember = str_replace($search_for,$replace,$data['nmmember']);
        $addr1 = str_replace($search_for,$replace,$data['addr1']);
        $addr2 = str_replace($search_for,$replace,$data['addr2']);
        $addr3 = str_replace($search_for,$replace,$data['addr3']);
        
        //$bonusperiod = date("m")."/"."1"."/".date("Y");
        $period = $this->get_current_period();
        $bonusperiod = date('m/d/Y', strtotime($period[0]->lastperiod));
        
        //$bonusperiod = $this->showKitPrdcd();
        
        //echo "bonus period ".$bonusperiod."";
        
        $createdt = date('Y-m-d H:i:s');
        
        $birth = explode("/", $data['tgllahir']);
        $thn = substr($birth[2], 2, 2);
        $now = date("Y-m-d");
        $password = $birth[0]."".$birth[1]."".$thn;
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $alamat3 = $data['addr3']." ".$data['kdpos'];
        //$postcode = '000004';
        $postcode = $data['kdpos'];
        
      
        $tgllahir = $birth[2]."-".$birth[1]."-".$birth[0];
        
        
        if($data['regtype']=="" || $data['regtype']=="0")
        {
            $test = $this->showKitPrdcd($usr);
            $regtype = $test[0]->kitprdcd;
            $totdp = $test[0]->dp;
            $totbv = $test[0]->bv;
            $pricecode = $test[0]->pricecode;
            //echo "door ".$regtype."<br>";
        }
        else
        {
            $test = $this->showKitPrdcdV($usr,$data['regtype']);
            $regtype = $test[0]->prdcd;
            $totdp = $test[0]->dp;
            $totbv = $test[0]->bv;
            $pricecode = $test[0]->pricecode;
        }
        
        if($data['choosevoucher'] == '1')
        {
            $ordtypee = 'V';
            $kitstatus = '1';
        }
        else
        {
            $ordtypee = 'P';
            $kitstatus = '0';
        }
        
        if($data['norek']!="")
        {
            $nmmembery = $nmmember;
        }
        else
        {
            $nmmembery = "";
        }
        
       
        //$alamat3 = $this->input->post('addr3')." ". $this->input->post('postcode');
                $insMsmemb = "INSERT into msmemb 
                                (dfno, sfno, sfno_reg, memberid,password,
                                 sponsorid, sponsorregid, fullnm,idno,birthdt,
                                 sex,citizen,addr1,addr2,addr3,
                                 state,postcd,country,tel_hm,tel_hp,
                                 email,jointdt,regtype,bankaccno,bankaccnm,
                                bankid,branch,loccd,trdt,etdt,
                                createnm,status,autobns,activitystatusid,sentstm,
                                auto_totdnln,iplastupd,PT_SVRID,allowmemberlogin,bnsstmsc,
                                loccd_et,loccd_co,formno,vchkey,dfnotemp,
                                banknm,ordtype,kit_status,birth2dt,benbirthdt,trcd,entrytype)
                VALUES ('$new_id','".$data['idsponsor']."','".$data['idrekrut']."','$new_id','$password',
                        '".$data['idsponsor']."','".$data['idsponsor']."','".$nmmember."','".$data['noktp']."','".$tgllahir."',
                        '".$data['sex']."','ID','".$addr1."','".$addr2."','".$addr3."',
                        '".$data['area']."','$postcode','ID','".$data['tel_hm']."','".$data['tel_hp']."',
                        '".$data['email']."','$now','".$regtype."','".$data['norek']."','".$nmmembery."',
                        '".$data['bankid']."','B001','".$usr."','$now','$now',
                        '".$usr."','1',0,1,'2',
                        0,'$ip','ID','1','".$data['bnstmt']."',
                        '".$usr."','".$usr."','".$data['voucherno']."','".$data['voucherkey']."','".$data['noapl']."',
                        '".$data['banknm']."', '".$ordtypee."','".$kitstatus."','$now','$now','".$idnoo."','$group_stk')";
                        
               
        /* echo "QUERY MSMEMB ".$insMsmemb."";
        echo "<br /><br />"; */
        $query1 = $this->db->query($insMsmemb);
        
        if($data['choosevoucher'] == '1')
        {
            $insSc_newtrh = "INSERT INTO sc_newtrh 
                            (trcd,trtype,trdt,dfno,loccd,tdp,taxrate,taxamt,discamt,shcharge,
                            othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                            paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                            updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                            type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                            syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                            sc_co,bnsperiod,othdisc,flag_batch,batchstatus,flag_recover,
                            system,ttptype,entrytype,flag_show,flag_approval,csno,batchno,batchdt)
                            VALUES
                            ('".$idnoo."','SB1','".$createdt."','".$new_id."',
                            '".$usr."',".$totdp.",10,0,0,0,0,".$totbv.",".$totbv.",
                            ".$totbv.",".$totbv.",".$totdp.",'WH001','B001','".$pricecode."',
                            '01','01','03',".$totdp.",0,0,".$totdp.",'".$usr."','".$usr."',
                            '0',0,0,0,0,'0','".$createdt."','0',0,0,'1','1','0','0','0',
                            0,'0','0','0','1','ID','".$usr."','".$usr."','".$bonusperiod."',
                            0,'2','0','0','0','MEMB',4,'1','1','IDBL','IDBL','".$createdt."')";
        
            //echo "QUERY NEWTRH ".$insSc_newtrh."";
            //echo "<br /><br />";
            $query2 = $this->db->query($insSc_newtrh);
            
            
            $insStarterkit = " UPDATE starterkit 
                               SET activate_dfno='".$new_id."',
                                  activate_by = '".$usr."',activate_fromip = '".$ip."',
                                  activate_dt='".$createdt."',status = '2'
                               WHERE formno = '".$data['voucherno']."' AND vchkey = '".$data['voucherkey']."'";
        
           /*  echo "QUERY STARTERKIT ".$insStarterkit."";
            echo "<br /><br />"; */
           $query3 = $this->db->query($insStarterkit);
            
        }
        else
        {
            $insSc_newtrh = "insert into sc_newtrh 
                            (trcd,trtype,trdt,dfno,loccd,tdp,taxrate,taxamt,discamt,shcharge,
                            othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                            paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                            updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                            type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                            syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                            sc_co,bnsperiod,othdisc,flag_batch,batchstatus,flag_recover,
                            system,ttptype,entrytype,flag_show,flag_approval)
                            VALUES
                            ('".$idnoo."','SB1','".$createdt."','".$new_id."',
                            '".$usr."',".$totdp.",10,0,0,0,0,".$totbv.",".$totbv.",
                            ".$totbv.",".$totbv.",".$totdp.",'WH001','B001','".$pricecode."',
                            '01','01','03',".$totdp.",0,0,".$totdp.",'".$usr."','".$usr."',
                            '0',0,0,0,0,'0','".$createdt."','0',0,0,'1','1','0','0','0',
                            0,'0','0','0','1','ID','".$usr."','".$usr."','".$bonusperiod."',
                            0,'0','0','0','0','MEMBP',4,'1','0')";
        
            //echo "QUERY NEWTRH ".$insSc_newtrh."";
            //echo "<br /><br />";
            $query2 = $this->db->query($insSc_newtrh);
            
            
            $insStarterkit = "insert into starterkit 
                        (formno,vchkey,createdt,createnm,
                        status,activate_by,activate_dfno,activate_fromip,activate_dt,
                        actseq,updatenm,updatedt,prdcd,PT_SVRID) 
                        VALUES
                        ('".$new_id."','".$new_id."','".$createdt."','".$usr."','2','".$usr."','".$new_id."',
                        '".$ip."','".$createdt."',0,'".$usr."','".$createdt."','".$regtype."','ID')";
        
            //echo "QUERY STARTERKIT ".$insStarterkit."";
            //echo "<br /><br />";
            $query3 = $this->db->query($insStarterkit);
        }
        
        $insSc_newtrd = "insert into sc_newtrd 
                        (trcd,prdcd,qtyord,qtyship,qtyremain,dp,pv,bv,taxrate,sp,sb,scdisc,seqno,scdiscamt,
                        syn2web,qty_used,qty_avail,PT_SVRID,pricecode) 
                        VALUES
                        ('".$idnoo."', '".$regtype."',1,0,0,".$totdp.",
                        ".$totbv.",".$totbv.",0,0,0,0,0,0,'0',0,0,'ID','".$pricecode."')";
        
        //echo "QUERY NEWTRD ".$insSc_newtrd."";
        //echo "<br /><br />";
        $query4 = $this->db->query($insSc_newtrd);   

                    
        // return $query4;           
        
        if(!$query1)
        {
            return 0;
        }
        else
        {
            if(!$query2)
            {
                return 0;
            }
            else
            {
                if(!$query3)
                {
                    return 0;
                }
                else
                {
                    if(!$query4)
                    {
                        return 0;
                    }
                    else
                    {
                        return 1;
                    }   
                }   
            }   
        }   
    }
    
    function show_new_member($new_id)
    {
        $this->db = $this->load->database($this->db2, true);
        
        $qry = "SELECT a.dfno, a.fullnm, a.password, a.sponsorid, a.createnm,
                    b.fullnm as sponsorname, a.sfno_reg, c.fullnm as rekruiternm
                FROM klink_mlm2010.dbo.msmemb a 
                INNER join klink_mlm2010.dbo.msmemb b 
                    ON a.sponsorid=b.dfno COLLATE SQL_Latin1_General_CP1_CS_AS
                INNER join klink_mlm2010.dbo.msmemb c 
                    ON a.sfno_reg=c.dfno COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.dfno = ?";
        $paramQry = array($new_id);
        $result = $this->getRecordset($qry,$paramQry,$this->db2);
        return $result;
        
    }
	
	function DecrementingLastKitNo($stk)
    {
        $this->db = $this->load->database($this->db2, true);
        
        $sql = "UPDATE klink_mlm2010.dbo.mssc 
                SET lastkitno = lastkitno - 1 
                WHERE loccd = ?";
        $paramQry = array($stk);
        $query = $this->db->query($sql, $paramQry);
        if(!$query)
        {
            return 0;
        }
        else
        {
            return 1;
        } 
    }
    
    function setLastKitToZero($stk)
    {
        $this->db = $this->load->database($this->db2, true);  
        $sql = "UPDATE klink_mlm2010.dbo.mssc 
                SET lastkitno = 0, lastcodememb = lastcodememb + 1 
                WHERE loccd = ?";
        //echo $sql;
        //echo "<br /><br />";
        $paramQry = array($stk);
        $query = $this->db->query($sql, $paramQry);
        if(!$query)
        {
            return 0;
        }
        else
        {
            return 1;
        } 
    }
	
	function update_limitkit($username)
    {
        $this->db = $this->load->database($this->db2, true);
        $upd = "UPDATE klink_mlm2010.dbo.mssc 
                SET arkit = arkit + 1 
                WHERE loccd = ?";
        $paramQry = array($username);
        $query = $this->db->query($upd, $paramQry);
        if(!$query)
        {
            return 0;
        }
        else
        {
            return 1;
        } 
    }
	
	public function get_current_periodV2()
    {
        $qry = "SELECT CONVERT(VARCHAR(10), a.currperiodSCO, 120) as lastperiod 
        FROM syspref a";
        $result = $this->getRecordset($qry,null,$this->db2);
        return $result;
    }

    public function get_current_period()
    {
        $qry = "SELECT a.currperiodSCO as lastperiod 
                FROM klink_mlm2010.dbo.syspref a";
        $result = $this->getRecordset($qry,null,$this->db2);
        return $result;
    }
	
	public function showKitPrdcd($username)
    {
        $this->db = $this->load->database($this->db2, true);
        
        $qry = "SELECT a.pricecode,a.loccd,a.kitprdcd,c.bv,c.dp
                FROM klink_mlm2010.dbo.mssc a
                INNER JOIN klink_mlm2010.dbo.pricetab c 
                    ON a.kitprdcd = c.prdcd COLLATE SQL_Latin1_General_CP1_CS_AS
                WHERE a.loccd = ? AND a.pricecode=c.pricecode";
        $paramQry = array($username);        
        $result = $this->getRecordset($qry,$paramQry,$this->db2);
        return $result;
    }
    
    
    public function showKitPrdcdV($username,$prdcd)
    {
        $this->db = $this->load->database($this->db2, true);
        
        $sql = "SELECT a.pricecode,c.prdcd,c.bv,c.dp
                FROM klink_mlm2010.dbo.mssc a
                INNER JOIN klink_mlm2010.dbo.pricetab c 
                  ON (a.pricecode = c.pricecode COLLATE SQL_Latin1_General_CP1_CS_AS)
                WHERE a.loccd = ? AND c.prdcd = ?";
        $paramQry = array($username, $prdcd);
        $result = $this->getRecordset($sql,$paramQry,$this->db2);
        return $result;
    }

    public function listProdukSkPromoStk($pricecode, $arrPrd = null) {
        $qty = 1;
        $prdcode = "";
        if($arrPrd !== null) {
            $qty = $arrPrd['qty'];
            $prdcode = " AND a.prdcd = '$arrPrd[product]'";
        }
        $qry = "SELECT a.prdcd, a.prdnm, $qty as qty, b.dp, b.bv, 
                   $qty * b.dp as total_dp, $qty * b.bv as total_bv, a.sk_stockist
                FROM klink_mlm2010.dbo.msprd a
                INNER JOIN klink_mlm2010.dbo.pricetab b ON (a.prdcd = b.prdcd AND b.pricecode = '$pricecode')
                AND a.sk_stockist = '1' $prdcode";
        $result = $this->getRecordset($qry,null,$this->db2);
        return $result;
    }

    public function checkIdMember($idmember) {
        $qry = "SELECT a.dfno, a.fullnm, a.tel_hp
                FROM klink_mlm2010.dbo.msmemb a
                WHERE a.dfno = ?";
        $paramQry = array($idmember);
        $result = $this->getRecordset($qry,$paramQry,$this->db2);
        return $result;
    }



    public function simpanPembelianSK($data) {
        $ttptype = "MEMBP";
        $ordtypee = 'P';
        $kitstatus = '0';
        $flag_approval = '0';
        $csno = "";
        $batchno = "";
        $flag_batch = '0';


        //SEQ MEMB
        /* $y1=date("y");
        $m=date("m");

        $tbl = "SEQ_MEMB"."$y1"."$m";

        $input = "INSERT INTO $tbl (SeqVal) VALUES('a')";
        $query = $qryTrx->query($input);

        $qry = "SELECT * FROM $tbl 
                WHERE SeqID = ( SELECT MAX(SeqID) FROM $tbl )";
            
        $query = $qryTrx->query($qry);
        if($query == null) {
        $ss = 0;
        } else {
            foreach($query->result() as $data)
            {
                $ss = $data->SeqID;
            }  
        }
        $jumlah = $qryTrx->num_rows();

        $next_seq = sprintf("%06s",$ss);
        $prefix = "PS".date('ym');
        $trcd =  strval($prefix.$next_seq); */

        $trcd = "PSTES01";

        $tgl = date("Y-m-d");
        $dfno = $data['dfno'];
        $usr = $data['usr'];
        $pricecode = $data['pricecode'];
        $new_id = "TEST";

        $bnsx = $this->get_current_periodV2();
        $bonusperiod = $bnsx[0]->lastperiod;

        $jum = count($data['prdcd']);
        $insSc_newtrd = "";
        $arrPrd = array();
        $arrxP = array(); 

        $totbv = 0;
        $totdp = 0;

        $insSc_newtrd = "";

        for($i = 0; $i < $jum; $i++) {
            $prdcd = $data['prdcd'][$i];
            $qty = $data['jum'][$i];

            $arrPrd = array(
                "product" => $prdcd,
                "qty" => $qty
            );
            $prd = $this->listProdukSkPromoStk($pricecode, $arrPrd);

            if($prd == null) {
                return jsonFalseResponse("Kode Produk Salah");
            }

            $totalbv = $prd[0]->bv;
            $totaldp = $prd[0]->dp;

            $insSc_newtrd .= "INSERT INTO sc_newtrd 
            (trcd,prdcd,qtyord,qtyship,qtyremain,dp,pv,bv,taxrate,sp,sb,scdisc,seqno,scdiscamt,
                syn2web,qty_used,qty_avail,PT_SVRID,pricecode) VALUES ('".$trcd."', '".$prdcd."',$qty,0,0,".$totaldp.",
            ".$totalbv.",".$totalbv.",0,0,0,0,0,0,'0',0,0,'ID','".$pricecode."');";  

            $arrx['prdcd'] = $prdcd;
            $arrx['qty'] = $qty;
            array_push($arrxP, $arrx);

            $totbv += $qty * $totalbv;
            $totdp += $qty * $totaldp;

        }

        echo $insSc_newtrd;
        echo "<br />";

        /* echo "<pre>";
        print_r($arrxP);
        echo "</pre>"; */
        $insStarterkit = "";
        foreach($arrxP as $dataVch) {
            for($j = 0; $j < $dataVch['qty']; $j++) {
                $insStarterkit .= "INSERT INTO starterkit 
                (formno,vchkey,createdt,createnm,
                status, actseq,updatenm,updatedt,prdcd,PT_SVRID, referal_code) 
                VALUES
                ('".$new_id."','".$new_id."','".$tgl."','".$usr."','1',0,
                 '".$usr."','".$tgl."','".$dataVch['prdcd']."','ID', '$dfno');";

                
            }
        }
        
        echo $insStarterkit;
        echo "<br />";

        $insSc_newtrh = "INSERT INTO sc_newtrh 
                    (trcd,trtype,trdt,dfno,loccd,tdp,taxrate,taxamt,discamt,shcharge,
                    othcharge,tpv,tbv,npv,nbv,ndp,whcd,branch,pricecode,
                    paytype1,paytype2,paytype3,pay1amt,pay2amt,pay3amt,totpay,createnm,
                    updatenm,post,sp,sb,taxable,taxableamt,ordtype,createdt,
                    type,scdiscrate,scdiscamt,sctype,scdisc,generate,statusbo,
                    syn2web,n_bc,status,autorecon,first_trx,bc,PT_SVRID,sc_dfno,
                    sc_co,bnsperiod,othdisc,flag_batch,batchstatus,flag_recover,
                    system,ttptype,entrytype,flag_show,flag_approval,csno,batchno,batchdt)
                    VALUES
                    ('".$trcd."','SB1','".$tgl."','".$dfno."',
                    '".$usr."',".$totdp.",10,0,0,0,0,".$totbv.",".$totbv.",
                    ".$totbv.",".$totbv.",".$totdp.",'WH001','B001','".$pricecode."',
                    '01','01','03',".$totdp.",0,0,".$totdp.",'".$usr."','".$usr."',
                    '0',0,0,0,0,'0','".$tgl."','0',0,0,'1','1','0','0','0',
                    0,'0','0','0','1','ID','".$usr."','".$usr."','".$bonusperiod."',
                    0,'$flag_batch','0','0','0','$ttptype',4,'1','$flag_approval','$csno','$batchno','".$tgl."')"; 
        
        echo $insSc_newtrh;
        echo "<br />";
    }
	
}  