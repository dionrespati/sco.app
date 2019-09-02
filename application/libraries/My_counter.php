<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    Class My_counter
    {
		function __construct()
		{
		}

    	function Counter($DOC)
		{
			$CI =&get_instance();

			$this->db = $CI->load->database('klink_mlm2010',TRUE);

			$query = 'SELECT VALUE FROM CAHYONO_COUNTER WHERE DOC_CODE = ?';
			$result = $this->db->query($query,array($DOC));
			$row = $result->row();

			$row->VALUE;

			$no = $DOC.'-'.str_pad($row->VALUE,5,"0000000",STR_PAD_LEFT);

			return $no;
		}

    	function getCounter($DOC)
		{
			$CI =&get_instance();

			$this->db = $CI->load->database('klink_mlm2010',TRUE);

			$query = 'SELECT VALUE FROM CAHYONO_COUNTER WHERE DOC_CODE = ?';
			$result = $this->db->query($query,array($DOC));
			$row = $result->row();

			$row->VALUE;

			$no = $DOC.'-'.str_pad($row->VALUE,5,"0000000",STR_PAD_LEFT);

			$value = $row->VALUE + 1;

			$this->db->where(array('DOC_CODE'=>$DOC));
			$this->db->update('CAHYONO_COUNTER', array('VALUE'=>$value));

			return $no;
		}

		function Counter2($DOC)
		{
			$CI =&get_instance();

			$waktu = date('y').date('m').date('d');

			$this->db = $CI->load->database('klink_mlm2010',TRUE);

			$this->db->where(array('DOC_CODE'=>$DOC,'DATE'=>date('d'),'MONTH'=>date('m'),'YEAR'=>date('y')));
			$count = $this->db->count_all_results('CAHYONO_COUNTER');

			if($count == 0) {
				$value = 1;
				$no = $DOC.$waktu.str_pad($value,3,"00",STR_PAD_LEFT);
			}else{
				$query = 'SELECT VALUE FROM CAHYONO_COUNTER WHERE DOC_CODE = ? AND DATE = ? AND MONTH = ? and YEAR = ?';
				$result = $this->db->query($query,array($DOC,date('d'),date('m'),date('y')));
				$row = $result->row();

				$value = $row->VALUE+1;

				$no = $DOC.$waktu.str_pad($value,3,"00",STR_PAD_LEFT);
			}
			return $no;
		}

		function getCounter2($DOC,$uuid = '')
		{
			$CI =&get_instance();
			$waktu = date('y').date('m').date('d');

			$this->db = $CI->load->database('klink_mlm2010',TRUE);
			$this->db->where(array('DOC_CODE'=>$DOC,'DATE'=>date('d'),'MONTH'=>date('m'),'YEAR'=>date('y')));
			$count = $this->db->count_all_results('CAHYONO_COUNTER');
			if($count == 0) {
				$value = 1;
				$no = $DOC.$waktu.str_pad($value,3,"00",STR_PAD_LEFT);

				$this->db->insert('CAHYONO_COUNTER',array('ID_COUNTER' =>$uuid,'VALUE'=>$value,'DOC_CODE'=>$DOC,'DATE'=>date('d') ,'MONTH'=>date('m'),'year'=>date('y'),'CRT_NM'=>'SYSTEM'));
			}else{
				$query = 'SELECT VALUE FROM CAHYONO_COUNTER WHERE DOC_CODE = ? AND DATE= ? AND MONTH = ? AND YEAR = ?';
				$result = $this->db->query($query,array($DOC,date('d'),date('m'),date('y')));
				$row = $result->row();


				$value = $row->VALUE+1;

				$no = $DOC.$waktu.str_pad($value,3,"00",STR_PAD_LEFT);

				$this->db->where(array('DOC_CODE'=>$DOC,'DATE'=>date('d'),'MONTH'=>date('m'),'year'=>date('y')));
				$this->db->update('CAHYONO_COUNTER', array('VALUE'=>$value));
			}

			return $no;
		}
	}
?>
