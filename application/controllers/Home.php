<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	// Main Index
	public function index($startat = 0)
	{
		$this->load->helper('form');
		$this->load->library('pagination');
		$this->load->model('fundraisers');
		$this->load->model('reviews');

		$config = array();
		$config['per_page'] = 100;

		$data = array();
		$data['title_length'] = 52;

		// For the main list
		$data['fundraisers'] = $this->fundraisers->get_homepage_list($config['per_page'], $startat);

		// For the add-review select menu
		$data['fundraisers_select'] = $this->fundraisers->get(0);

		// Get total
		$fundraisers_total = $this->fundraisers->count();

		// Pagination
		$config['base_url'] = base_url() . 'home/index/';
		$config['total_rows'] = $fundraisers_total->count;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] ='</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="disabled"><li class="active"><a href="#">';
		$config['cur_tag_close'] = '<span class="sr-only"></span></a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tagl_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tagl_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tagl_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tagl_close'] = '</li>';
		$this->pagination->initialize($config);

		$data['pages'] = $this->pagination->create_links();

		$this->load->template('home', $data);
	}

	// View Fundraiser
	public function fundraiser($id, $slug, $rating = 0, $startat = 0)
	{
		$id = intval($id);
		$this->load->helper('form');
		$this->load->library('pagination');
		$this->load->model('fundraisers');
		$this->load->model('reviews');

		$fundraiser = $this->fundraisers->get_id($id);

		if ($fundraiser->slug != $slug) {
			redirect('home/fundraiser/' . $fundraiser->id . '/' . $fundraiser->slug);
		}

		$config['per_page'] = 20;

		$reviews_total = $this->reviews->count_fundraiser_reviews($id, $rating);

		$scores = $this->reviews->get_review_details($id);

		$totalvotes = 0;
		$details = array();
		foreach ($scores AS $score) {
			$details[$score['rating']] = $score['score'];
			$totalvotes += $score['score'];
		}

		$data = array();
		$data['fundraiser'] = $fundraiser;
		$data['fundraisers_select'] = $this->fundraisers->get(0);
		$data['reviews'] = $this->reviews->get_reviews_for_fundraiser($id, $config['per_page'], $startat, $rating);
		$data['reviews_details'] = $details;
		$data['details'] = $details;
		$data['totalvotes'] = $totalvotes;
		$data['rating'] = $rating;

		// Pagination
		$config['base_url'] = base_url() . 'home/fundraiser/' . $id . '/' . $fundraiser->slug . '/' . $rating;
		$config['total_rows'] = $reviews_total->count;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] ='</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="disabled"><li class="active"><a href="#">';
		$config['cur_tag_close'] = '<span class="sr-only"></span></a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tagl_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tagl_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tagl_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tagl_close'] = '</li>';
		$this->pagination->initialize($config);

		$data['pages'] = $this->pagination->create_links();

		$this->load->template('fundraiser', $data);
	}

	// Save New Review
	public function save_review()
	{
		$details = $this->input->post('details', true);
		$email = $this->input->post('email', true);
		$fundraiserid = $this->input->post('fundraiserid', true);
		$fundraisertitle = $this->input->post('fundraisertitle', true);
		$rating = $this->input->post('rating', true);
		$name = $this->input->post('name', true);

		if ($fundraisertitle) {
			$this->load->model('fundraisers');
			$fundraiserid = $this->fundraisers->save(array('title' => $fundraisertitle));
		}

		$this->load->model('reviews');

		$this->reviews->save(array(
			'details' => $details,
			'email' => $email,
			'fundraiserid' => $fundraiserid,
			'rating' => $rating,
			'name' => $name
		));

		$json = array('token' => $this->security->get_csrf_hash());

		if (!empty($this->reviews->errors)) {
			$json['errors'] = $this->reviews->errors;
		}

		echo json_encode($json);
	}
}
