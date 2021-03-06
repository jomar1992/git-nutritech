<?php
class Shop_cart_model extends CI_Model
{
	public function check_duplicate($user_id, $tmp_user_id, $item_id, $promo_id){
		$query =$this->db->get_where('shop_cart_tmp', array(
			'user_id' => $user_id,
			'tmp_user_id' => $tmp_user_id,
			'item_id' => $item_id,
			'promo_id' => $promo_id
		));
		return $query->num_rows();
	}

	public function remove_expired_promo($user_id, $tmp_user_id){
		$this->db->delete('shop_cart_tmp', array('user_id' => $user_id,
			'tmp_user_id' => $tmp_user_id,
			'promo_expire' => 'Y'
		));
	}

	public function update_expired_promos(){
		$this->db->select('shop_cart_tmp.*, promo_items.promo_period_from, promo_items.promo_period_to');
		$this->db->from('shop_cart_tmp');
		$this->db->join('promo_items', 'promo_items.promo_id = shop_cart_tmp.promo_id', 'INNER');
		$query = $this->db->where(array('promo_expire' => 'N',
			'promo_period_to <' => date('Y-m-d')
		))->get();
		$results = $query->result_array();
		foreach ($results as $key) {
			$this->db->where(array('id' => $key['id']));
			$this->db->update('shop_cart_tmp', array('promo_expire' => 'Y'));
		}
	}

	public function remove_previous_cart($user_id, $tmp_user_id){
		$this->db->delete('shop_cart_tmp', array('user_id' => $user_id,
			'tmp_user_id <>' => $tmp_user_id,
		));
	}

	public function update_cart($user_id, $tmp_user_id, $item_id, $promo_id, $qty = 0){
		$query =$this->db->get_where('shop_cart_tmp', array(
			'user_id' => $user_id,
			'tmp_user_id' => $tmp_user_id,
			'item_id' => $item_id,
			'promo_id' => $promo_id
		));

		$result = $query->row();
		if ($qty == 0){
			$qty = $result->quantity + 1;
			if ($qty > 10) {
				$qty = 10;
			}
		}

		$array = array('quantity' => $qty);
		$this->db->where('id', $result->id);
		$this->db->update('shop_cart_tmp', $array);
	}

	public function create_cart($user_id, $tmp_user_id, $retained, $item_id, $promo_id){
		$this->load->model('distributor_model');
		$distributor = $this->distributor_model->fetch_distributor_by_user_id($user_id);

		$rate = $this->distributor_model->fetch_distributor_rate($distributor->distributor_id);
		if($promo_id > 0){
			$this->load->model('promo_item_model');
			$amount = $this->promo_item_model->getamount($promo_id, $retained, $rate);
		} else {
			$this->load->model('item_model');
			$amount = $this->item_model->getamount($item_id, $retained, $rate);
		}

		$details = array(
			'user_id' => $user_id,
			'promo_id' =>  $promo_id,
			'item_id' => $item_id,
			'quantity' =>  1,
			'amount' =>  $amount,
			'date_cart' =>  date('Y-m-d H:i:s'),
			'promo_expire' =>  'N',
			'tmp_user_id' =>  $tmp_user_id,
			'amount_retained' =>  $retained
		);

		$this->db->set($details)->insert('shop_cart_tmp');
	}

	public function delete_cart($user_id, $tmp_user_id, $item_id, $promo_id){
		$this->db->delete('shop_cart_tmp', array(
			'user_id' => $user_id,
			'promo_id' => $promo_id,
			'item_id' => $item_id,
			'tmp_user_id' => $tmp_user_id
		));
	}

	public function total_cart($user_id, $tmp_user_id){
		$this->db->select('sum(amount * quantity) as amount');
		$query = $this->db->get_where('shop_cart_tmp', array('user_id' => $user_id, 
			'tmp_user_id' => $tmp_user_id));
		$result = $query->row();

		return $result->amount;
	}

	public function count_cart($user_id, $tmp_user_id){
		$this->db->select('sum(quantity) as cart_count');
		$query = $this->db->get_where('shop_cart_tmp', array('user_id' => $user_id, 
			'tmp_user_id' => $tmp_user_id));
		$result = $query->row();

		return $result->cart_count;
	}

	public function fetch_cart($user_id, $tmp_user_id){
		$this->db->select('shop_cart_tmp.amount, 
			shop_cart_tmp.promo_id, 
			shop_cart_tmp.item_id,
			shop_cart_tmp.amount,
			shop_cart_tmp.quantity, 
			promo_items.promo_description,
			items.item_description');
		$this->db->from('shop_cart_tmp');
		$this->db->join('promo_items', 'promo_items.promo_id = shop_cart_tmp.promo_id', 'LEFT');
		$this->db->join('items', 'items.item_id = shop_cart_tmp.item_id', 'LEFT');
		$this->db->where(
			array('shop_cart_tmp.user_id' => $user_id, 
				'shop_cart_tmp.tmp_user_id' => $tmp_user_id,
				'shop_cart_tmp.promo_expire' => 'N'
			));

		$query = $this->db->get();
		return $query->result_array();
	}
}