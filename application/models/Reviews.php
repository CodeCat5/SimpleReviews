<?php

class Reviews extends CI_Model
{
	public $errors = '';
	public $fields = array(
		'fundraiserid' => 0,
		'rating' => 0,
		'time_added' => 0,
		'approved' => 0,
		'name' => '',
		'email' => '',
		'details' => ''
	);

	/** Get reviews
	 * @param int $limit
	 * @param int $startat
	 * @param int $approved
	 * @return mixed
	 */
	public function get($limit = 10, $startat = 0, $approved = 1)
	{
		$this->db->select('reviews.*, fundraisers.title AS fundraiser_title')
			->from('reviews')
			->join('fundraisers', 'reviews.fundraiserid = fundraisers.id')
			->where('approved', $approved)
			->limit($limit, $startat);
		$query = $this->db->get();
		return $query->result();
	}

	/** Get reviews for fundraiser
	 * @param $fundraiserid
	 * @param int $limit
	 * @param int $startat
	 * @param int $rating
	 * @return mixed
	 */
	public function get_reviews_for_fundraiser($fundraiserid, $limit = 10, $startat = 0, $rating = 0)
	{
		$query = $this->db->select('reviews.*, fundraisers.title AS fundraiser_title')
			->from('reviews')
			->join('fundraisers', 'reviews.fundraiserid = fundraisers.id')
			->where('fundraiserid', $fundraiserid)
			->where('approved', 1);

		if ($rating) {
			$query->where('rating', $rating);
		}
		if ($limit) {
			$query->limit($limit, $startat);
		}

		return $query->order_by('id', 'DESC')->get()->result();
	}

	/** Get score for reviews
	 * @param $id
	 * @return mixed
	 */
	public function get_review_details($id) {
		$query = $this->db->select('rating, COUNT(*) AS score')
			->where('fundraiserid', $id)
			->where('approved', 1)
			->group_by('rating');

		return $query->get('reviews')->result_array();
	}

	/** Get last reviews
	 * @param $fundraiserids
	 * @return mixed
	 */
	public function get_last($fundraiserids)
	{
		$query = $this->db->where_in('fundraiserid', $fundraiserids)
			->where('approved', 1)
			->get('reviews');

		return $query->result();
	}

	/** Count number of pending reviews
	 * @return mixed
	 */
	public function count_pending() {
		$query = $this->db->select("COUNT(*) AS pending")
			->where('approved', 0)
			->get('reviews');

		return $query->row();
	}

	/** Count reviews for fundraiser
	 * @param $id
	 * @param int $rating
	 * @return mixed
	 */
	public function count_fundraiser_reviews($id, $rating = 0) {
		$query = $this->db->select("COUNT(*) AS count")
			->where('fundraiserid', $id)
			->where('approved', 1);

		if ($rating) {
			$query->where('rating', $rating);
		}

		return $query->get('reviews')->row();
	}

	/** Check for errors
	 * @param bool $isnew
	 */
	public function check_errors($isnew = false) {

		if (!filter_var($this->fields['email'], FILTER_VALIDATE_EMAIL)) {
			$this->errors .= '<div>Please enter a valid email address.</div>';
		}
		if (!$this->fields['fundraiserid']) {
			$this->errors .= '<div>Please select a fundraiser.</div>';
		}
		if (!$this->fields['name']) {
			$this->errors .= '<div>Please enter your name.</div>';
		}
		if (!$this->fields['details']) {
			$this->errors .= '<div>Please enter your review for this fundraiser.</div>';
		}
		if (!$this->fields['rating']) {
			$this->errors .= '<div>Please select a rating for this fundraiser.</div>';
		}

		if ($isnew AND $this->fields['fundraiserid'] AND $this->fields['email']) {
			$query = $this->db->get_where('reviews', array(
				'fundraiserid' => $this->fields['fundraiserid'],
				'email' => $this->fields['email'],
			), 1);
			$existing = $query->row_array();

			if (isset($existing['id'])) {
				$this->errors .= '<div>You have already submitted a review for this fundraiser. Only 1 review per fundraiser is allowed.</div>';
			}

			$data = array();
			$data['secret'] = $this->config->item('google_secret');
			$data['response'] = trim($this->input->post('gtoken'));
			$data['remoteip'] = $this->input->ip_address();

			$verify = curl_init();
			curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($verify, CURLOPT_POST, true);
			curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($verify);
			$status = json_decode($response, true);
			if (empty($status['success'])) {
				$this->errors .= '<div>Please click the button below to prove that you are not a bot.</div>';
			}
		}
	}

	/** Save the review
	 * @param $values
	 */
	public function save($values)
	{
		foreach ($this->fields AS $field => $default) {
			$this->fields[$field] = (isset($values[$field])) ? htmlspecialchars($values[$field]) : $default;
		}

		$this->check_errors(true);

		if ($this->errors == '') {
			$this->fields['time_added'] = time();
			$this->db->insert('reviews', $this->fields);
		}
	}

	/** Update review
	 * @param $data
	 * @param $id
	 */
	public function update($data, $id)
	{
		$this->db->where('id', $id)->update('reviews', $data);
	}

	/** Delte review
	 * @param $ids
	 */
	public function delete($ids)
	{
		$ids = array_map('intval', $ids);

		$this->db->where_in('id', $ids)->delete('reviews');
	}
}