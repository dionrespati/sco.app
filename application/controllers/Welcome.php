<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$array = array(
		 "title" => "Testing",
		 "columns" => array("satu", "dua", "tiga"),
		);
		
		echo createTable($array);
	}
	
	public function formWalikan($tes) {
		
		
		
		$vokal = array("a","i","u","e","o");
		$konsonan = array(
		   "h" => "p",
		   "p" => "h",
		   "n" => "dh",
		   "dh" => "n",
		   "c" => "j",
		   "j" => "c",
		   "r" => "y",
		   "y" => "r",
		   "k" => "ny",
		   "ny" => "k",
		   "d" => "m",
		   "m" => "d",
		   "t" => "g",
		   "g" => "t",
		   "s" => "b",
		   "b" => "s",
		   "w" => "th",
		   "th" => "w",
		   "l" => "ng",
		   "ng" => "l"
		);
		
		$arr1 = str_split($tes);
		$jum = count($arr1);
		$str = "";
		for($i=0;$i<$jum;$i++) {
			$kata = strtolower($arr1[$i]);
			if($i < $jum-1) {
			    $kataNext = strtolower($arr1[$i+1]);
				$kata2 = $kata.$kataNext;
			}	
			//echo $kata."<br />";
			
			if(array_key_exists($kata2, $konsonan)) {
				echo $konsonan[$kata2]."-";
			} else {
				if(in_array($kata, $vokal)) {
					//echo "sd";
					$str .= $kata." ";
				} else if(array_key_exists($kata, $konsonan)) {
					//echo "ds";
					//$urut = $arr1[$i];
					$str .= $konsonan[$kata]." ";
				}
			}
		}
		echo $str."<br />";
	}
}
