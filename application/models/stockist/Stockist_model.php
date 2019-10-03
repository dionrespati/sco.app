<?php
class Stockist_model extends MY_Model {
		
	function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getStockistInfo($loccd) {
		$qry = "SELECT top 1 a.loccd, a.sctype, a.fullnm, 
					a.addr1, a.addr2, a.addr3,
					a.dfno,
					a.tel_hm, a.tel_hp, a.tel_of,
					a.[state], c.[description] as statenm,
					a.limitkit, a.arkit,
					a.limitkit - a.arkit as sisa_kuota,
					a.sfno as uplinestk, b.fullnm as uplinenm,
					a.lastkitno, a.latitude, a.longitude,
					a.kabupaten, a.KEC_JNE, a.kecamatan, a.kelurahan, a.postcd,
					e1.kode_provinsi as kode_provinsi,
					e.kode_kabupaten, 
					d.kode_kecamatan
				FROM mssc a 
				INNER JOIN mssc b ON (a.sfno = b.loccd)
				INNER JOIN [state] c ON (a.[state] = c.st_id)
				LEFT OUTER JOIN db_ecommerce.dbo.master_wil_kecamatan d
					ON (a.KEC_JNE COLLATE SQL_Latin1_General_CP1_CI_AS = d.kode_kec_JNE)
				LEFT OUTER JOIN db_ecommerce.dbo.master_wil_kabupaten e 
					ON (d.kode_kabupaten=e.kode_kabupaten)
				LEFT OUTER JOIN db_ecommerce.dbo.master_wil_provinsi e1
					ON (e1.kode_provinsi = e.kode_provinsi)
				LEFT OUTER JOIN db_ecommerce.dbo.master_wil_kelurahan f 
					ON (d.kode_kecamatan = f.kode_kecamatan)
				WHERE a.loccd = '$loccd'";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res;
	}
	
	function updateAddrStockist() {
		$arr = jsonFalseResponse("Update Data Stockist gagal..");
		$data = $this->input->post(NULL, TRUE);
		if($this->stockist == "BID06") {
			
		}
		
		$qry = "UPDATE mssc
			    SET addr1 = '$data[addr1]',
			        addr2 = '$data[addr2]',
			        addr3 = '$data[addr3]',
			        tel_hp = '$data[tel_hp]',
			        tel_hm = '$data[tel_hm]',
			        tel_of = '$data[tel_of]',
					kabupaten = '$data[kabupaten]',
					KEC_JNE = '$data[kecamatan]',
					kecamatan = '$data[kecamatan]',
					kelurahan = '$data[kelurahan]',
					postcd = '$data[postcd]'
				WHERE loccd = '$data[loccd]'";
		$res = $this->executeQuery($qry, $this->setDB(2));
		if($res > 0) {
			$arr = jsonTrueResponse(null, "Update Data Stockist berhasil..");
		} 
		return $arr;
	}

	function showListProvince($type = "array") {
		$this->db = $this->load->database('db_ecommerce', true);
        $qry = "select a.kode_provinsi as kode, a.provinsi as nama
				from db_ecommerce.dbo.master_wil_provinsi a
				group by a.kode_provinsi, a.provinsi
				order by a.provinsi";
		//echo $qry;
        //return $this->get_recordset($qry, $type, 'db_ecommerce');
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res;  
	} 
	
	function listKabupatenByProvince($province) {
    	$this->db = $this->load->database('db_ecommerce', true);
		//SELECT C.kode_provinsi, A.kode_kabupaten, B.kabupaten , A.kode_kab_JNE, A.kab_JNE
		//GROUP BY C.kode_provinsi, C.provinsi, A.kode_kabupaten, B.kabupaten --, A.kode_kab_JNE, A.kab_JNE
		//ORDER BY C.provinsi, B.kabupaten, A.kab_JNE"
        $qry = "SELECT C.kode_provinsi, A.kode_kabupaten as kode, B.kabupaten as nama 
				FROM db_ecommerce.dbo.master_wil_kecamatan A
					 INNER JOIN db_ecommerce.dbo.master_wil_kabupaten B ON A.kode_kabupaten=B.kode_kabupaten
				     INNER JOIN db_ecommerce.dbo.master_wil_provinsi C ON B.kode_provinsi=C.kode_provinsi
				WHERE A.kode_kec_JNE IS NOT NULL and c.kode_provinsi='$province'
				GROUP BY C.kode_provinsi, C.provinsi, A.kode_kabupaten, B.kabupaten
				ORDER BY C.provinsi, B.kabupaten";
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res; 
	}	
	
	function listKecamatanByKabupaten($kabupaten) {
    	$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT A.kode_kabupaten, A.kode_kab_JNE, A.kode_kecamatan, 
		            a.kode_kec_JNE as kode, a.kec_JNE as nama, a.kecamatan
				FROM db_ecommerce.dbo.master_wil_kecamatan A
				     INNER JOIN db_ecommerce.dbo.master_wil_kabupaten B ON A.kode_kabupaten=B.kode_kabupaten
				WHERE A.kode_kec_JNE IS NOT NULL and a.kode_kabupaten='$kabupaten'
				GROUP BY A.kode_kabupaten, A.kode_kab_JNE, A.kode_kecamatan, a.kode_kec_JNE, a.kec_JNE, a.kecamatan
				ORDER BY  a.kec_JNE, A.kode_kabupaten, A.kode_kab_JNE, A.kode_kecamatan, a.kode_kec_JNE, a.kecamatan";
		//echo $qry;
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res; 
	}

	function listKelurahannByKecamatan($kecamatan) {
    	$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT C.kode_kelurahan as kode, C.kelurahan as nama, C.kodepos
				FROM db_ecommerce.dbo.master_wil_kecamatan A
				     INNER JOIN db_ecommerce.dbo.master_wil_kabupaten B ON A.kode_kabupaten=B.kode_kabupaten
				     INNER JOIN db_ecommerce.dbo.master_wil_kelurahan C ON A.kode_kecamatan=C.kode_kecamatan
				WHERE A.kode_kec_JNE IS NOT NULL and a.kode_kec_JNE='$kecamatan'
				GROUP BY C.kode_kelurahan, C.kelurahan, C.kodepos
				ORDER BY  C.kode_kelurahan, C.kelurahan, C.kodepos";
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res; 
	}		
	
	function showKodepos($kelurahan) {
    	$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT top 1 C.kode_kelurahan as kode, C.kelurahan as nama, C.kodepos
				FROM db_ecommerce.dbo.master_wil_kelurahan C 
				WHERE C.kode_kelurahan='$kelurahan'";
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res;
	}
	
}