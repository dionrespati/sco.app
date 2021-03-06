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
				WHERE a.loccd = ?";
		//echo $qry;
		$param = array($loccd);
		$res = $this->getRecordset($qry, $param, $this->setDB(2));
		return $res;
	}
	
	function updateAddressStockist($data) {
		$arr = jsonFalseResponse("Update Data Stockist gagal..");
		$addr1 = cleanAddress($data['addr1']);
		$addr2 = cleanAddress($data['addr2']);
		$addr3 = cleanAddress($data['addr3']);
		$tel_hp = onlyNumeric($data['tel_hp']);
		$tel_hm = onlyNumeric($data['tel_hm']);
		$tel_of = onlyNumeric($data['tel_of']);
		$kabupaten = trim($data['kabupaten']);
		$kecamatan = trim($data['kecamatan']);
		$kelurahan = trim($data['kelurahan']);
		$postcd = onlyAlphaNum($data['postcd']);
		$loccd = onlyAlphaNum($data['loccd']);
		
		$arr = array(
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"tel_hp" => $tel_hp,
			"tel_hm" => $tel_hm,
			"tel_of" => $tel_of,
			"kabupaten" => $kabupaten,
			"KEC_JNE" => $kecamatan,
			"kecamatan" => $kecamatan,
			"kelurahan" => $kelurahan,
			"postcd" => $postcd
		);
		/* echo "<pre>";
		print_r($arr);
		echo "</pre>"; */
		$this->db->where('loccd', $loccd);
		$res = $this->db->update('klink_mlm2010.dbo.mssc', $arr);
		if($res > 0) {
			$arr = jsonTrueResponse(null, "Update Data Stockist berhasil..");
		} 
		return $arr;
	}

	function showListProvince($type = "array") {
		$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT a.kode_provinsi as kode, a.provinsi as nama
				FROM db_ecommerce.dbo.master_wil_provinsi a
				GROUP BY a.kode_provinsi, a.provinsi
				ORDER BY a.provinsi";
		$res = $this->getRecordset($qry, NULL, $this->setDB(2));
		return $res;  
	} 
	
	function listKabupatenByProvince($province) {
    	//$this->db = $this->load->database('db_ecommerce', true);
		//SELECT C.kode_provinsi, A.kode_kabupaten, B.kabupaten , A.kode_kab_JNE, A.kab_JNE
		//GROUP BY C.kode_provinsi, C.provinsi, A.kode_kabupaten, B.kabupaten --, A.kode_kab_JNE, A.kab_JNE
		//ORDER BY C.provinsi, B.kabupaten, A.kab_JNE"
        $qry = "SELECT C.kode_provinsi, A.kode_kabupaten as kode, B.kabupaten as nama 
				FROM db_ecommerce.dbo.master_wil_kecamatan A
				INNER JOIN db_ecommerce.dbo.master_wil_kabupaten B 
					ON A.kode_kabupaten=B.kode_kabupaten
				INNER JOIN db_ecommerce.dbo.master_wil_provinsi C 
					ON B.kode_provinsi=C.kode_provinsi
				WHERE A.kode_kec_JNE IS NOT NULL and c.kode_provinsi = ?
				GROUP BY C.kode_provinsi, C.provinsi, A.kode_kabupaten, B.kabupaten
				ORDER BY C.provinsi, B.kabupaten";
	    $province = trim(strtoupper($province));
		$paramQry = array($province);
		$res = $this->getRecordset($qry, $paramQry, $this->setDB(2));
		return $res; 
	}	
	
	function listKecamatanByKabupaten($kabupaten) {
    	//$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT A.kode_kabupaten, A.kode_kab_JNE, A.kode_kecamatan, 
		            a.kode_kec_JNE as kode, a.kec_JNE as nama, a.kecamatan
				FROM db_ecommerce.dbo.master_wil_kecamatan A
				INNER JOIN db_ecommerce.dbo.master_wil_kabupaten B ON A.kode_kabupaten=B.kode_kabupaten
				WHERE A.kode_kec_JNE IS NOT NULL and a.kode_kabupaten = ?
				GROUP BY A.kode_kabupaten, A.kode_kab_JNE, A.kode_kecamatan, 
						a.kode_kec_JNE, a.kec_JNE, a.kecamatan
				ORDER BY  a.kec_JNE, A.kode_kabupaten, A.kode_kab_JNE, A.kode_kecamatan, 
						a.kode_kec_JNE, a.kecamatan";
		//echo $qry;
		$kabupaten = trim(strtoupper($kabupaten));
		$paramQry = array($kabupaten);
		$res = $this->getRecordset($qry, $paramQry, $this->setDB(2));
		return $res; 
	}

	function listKelurahannByKecamatan($kecamatan) {
    	$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT C.kode_kelurahan as kode, C.kelurahan as nama, C.kodepos
				FROM db_ecommerce.dbo.master_wil_kecamatan A
				INNER JOIN db_ecommerce.dbo.master_wil_kabupaten B ON A.kode_kabupaten=B.kode_kabupaten
				INNER JOIN db_ecommerce.dbo.master_wil_kelurahan C ON A.kode_kecamatan=C.kode_kecamatan
				WHERE A.kode_kec_JNE IS NOT NULL and a.kode_kec_JNE = ?
				GROUP BY C.kode_kelurahan, C.kelurahan, C.kodepos
				ORDER BY  C.kode_kelurahan, C.kelurahan, C.kodepos";
		$kecamatan = trim(strtoupper($kecamatan));
	    $paramQry = array($kecamatan);
		$res = $this->getRecordset($qry, $paramQry, $this->setDB(2));
		return $res; 
	}		
	
	function showKodepos($kelurahan) {
    	//$this->db = $this->load->database('db_ecommerce', true);
        $qry = "SELECT top 1 C.kode_kelurahan as kode, C.kelurahan as nama, C.kodepos
				FROM db_ecommerce.dbo.master_wil_kelurahan C 
				WHERE C.kode_kelurahan = ?";
		$kelurahan = trim(strtoupper($kelurahan));
		$paramQry = array($kelurahan);
		$res = $this->getRecordset($qry, $paramQry, $this->setDB(2));
		return $res;
	}

	function getTaxStk($idstk, $year){

		$qry = "SELECT a.Nomor_Bukti_Potong, a.Kode_Pajak, a.NPWP_Pemotong, a.Nama_Pemotong,
				a.Tanggal_Bukti_Potong, a.Bulan_Bonus, a.IDMEMBER, a.KDSTOCKIST 
				FROM [dbo].[DATA_PAJAK_JOIN_STK] a  
				where a.KDSTOCKIST = '$idstk' and (a.IDMEMBER is null OR a.IDMEMBER = '')
				and Tanggal_Bukti_Potong like '%$year%'
				UNION
				SELECT a.Nomor_Bukti_Potong, a.Kode_Pajak, a.NPWP_Pemotong, a.Nama_Pemotong,
				a.Tanggal_Bukti_Potong, a.Bulan_Bonus, a.IDMEMBER, a.KDSTOCKIST 
				FROM [dbo].[DATA_PAJAK_JOIN_STK] a  
				where a.IDMEMBER = '$idstk' AND (a.KDSTOCKIST IS NULL OR a.KDSTOCKIST = '')
				and Tanggal_Bukti_Potong like '%$year%'
				ORDER BY a.Bulan_Bonus DESC";
		$res = $this->getRecordset($qry, null, $this->setDB(2));
		return $res;
	}

	function getDetTaxStk($no){

		$qry = "select *
				from v_hilal_pajak_detail
				where Nomor_Bukti_Potong like '%$no%'";
		$res = $this->getRecordset($qry, null, $this->setDB(2));
		return $res;
	}
	
}