<?php

class Fundraisers extends CI_Model
{
	public $errors = '';
	public $fields = array(
		'slug' => '',
		'title' => '',
		'time_added' => 0
	);

	/** Get Fundraisers
	 * @param int $limit
	 * @param int $startat
	 * @return mixed
	 */
	public function get($limit = 20, $startat = 0)
	{
		$query = $this->db->order_by('title')->get('fundraisers', $limit, $startat);

		return $query->result();
	}

	/** Get fundraiser by id
	 * @param $id
	 * @return mixed
	 */
	public function get_id($id)
	{
		$query = $this->db->where('id', $id)->get('fundraisers');
		return $query->row();
	}

	/** Check for errors
	 * @param int $id
	 */
	public function check_errors($id = 0)
	{
		if (trim($this->fields['title']) == '') {
			$this->errors .= '<div>Please enter a title for the fundraiser.</div>';
		}

		if (!$id) {
			$query = $this->db->get_where('fundraisers', array(
				'title' => $this->fields['title']
			));
			$existing = $query->row();
		}

		if (isset($existing->id)) {
			$this->errors .= '<div>There is already a fundraiser named ' . $this->fields['title']. '. Please choose a different name.</div>';
		}
	}

	/** Get a count of fundraisers
	 * @param bool $votes
	 * @return mixed
	 */
	public function count($votes = true)
	{
		$this->db->select("COUNT(*) AS count");

		if ($votes) {
			$this->db->where('rating_votes >', 0);
		}
		$query = $this->db->get('fundraisers');
		return $query->row();
	}

	/** Get a list for the homepage
	 * @param int $limit
	 * @param int $startat
	 * @return mixed
	 */
	public function get_homepage_list($limit = 10, $startat = 1)
	{
		$query = $this->db->select('fundraisers.*, reviews.*, fundraisers.id AS id')
			->join('reviews', 'fundraisers.lastreviewid = reviews.id', 'left')
			->where('rating_votes != 0')
			->order_by('rating_avg', 'DESC')
			->order_by('rating_votes', 'DESC')
			->order_by('fundraisers.id', 'DESC')
			->limit($limit, $startat)
			->get('fundraisers');
		return $query->result();
	}

	/** Save the fundraiser
	 * @param $values
	 * @param int $id
	 * @return bool|int
	 */
	public function save($values, $id = 0)
	{
		$this->fields['title'] = htmlspecialchars($values['title']);
		$this->fields['time_added'] = time();
		$this->fields['slug'] = $this->create_slug($values['title']);

		$this->check_errors($id);

		if ($this->errors != '') {
			return false;
		}

		if ($id) {
			$this->db->where('id', $id);
			$this->db->update('fundraisers', $this->fields);
			return $id;
		}

		$this->db->insert('fundraisers', $this->fields);
		return $this->db->insert_id();
	}

	/** Delete the fundraiser
	 * @param $id
	 */
	public function delete($id)
	{
		$this->db->delete('fundraisers', array('id' => $id));
		$this->db->delete('reviews', array('fundraiserid' => $id));
	}

	/**
	 * Returns a seo-friendly url, lowercase, X max chars long
	 * @param $title
	 * @return string
	 */
	public function create_slug($title) {
		$title = preg_replace(array(
			'/[[:space:]]+/',
			'/[^a-z_0-9 -]/i'
		), array(
			'-',
			''
		), $title);

		$title = substr(strtolower($title), 0, 100);

		return $title;
	}

	/** Update counters and vote avg
	 * @param $id
	 */
	public function update_count($id)
	{
		$id = intval($id);
		$query = $this->db->select('COUNT(*) AS votes, SUM(rating) AS total, MAX(id) AS lastreviewid')
			->from('reviews')
			->where('fundraiserid', $id)
			->where('approved', 1)
			->get();
		$counts = $query->row();

		$data = array(
			'rating_votes' => intval($counts->votes),
			'rating_total' => intval($counts->total),
			'rating_avg' => ($counts->votes ? ($counts->total / $counts->votes) : 0),
			'lastreviewid' => intval($counts->lastreviewid)
		);

		$this->db->where('id', $id)->update('fundraisers', $data);
	}
}