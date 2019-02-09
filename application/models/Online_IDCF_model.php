<?php 
	class Online_idcf_model extends CI_Model{
		
		public function insert_idcf($idcf_details){
			$this->db->insert("shop_idcf",$idcf_details);
		}
		public function fetch_sponsor_info($server_ip,$id){
			$url = 'http://'.$server_ip.'/nutritech_api/sponsor/'.$id;
			$qstring = array('X-API-KEY' => '12345');
			$query = http_build_query($qstring);
			$ch    = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			$response = curl_exec($ch);
			curl_close($ch);

			$distributor = json_decode($response, TRUE);
			return $distributor;
		}
	}
?>
