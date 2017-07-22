<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	// Validate them
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('users');

		$area = $this->uri->segment(2);
		if ($area AND $area != 'index' AND !$this->users->validate_session('admin')) {
			redirect('admin');
		}
	}

	// Login Page
	public function index()
	{
		$this->load->helper('form');

		if (!empty($_POST)) {
			if ($this->users->login($_POST['email'], $_POST['password'])) {
				redirect('/admin/home');
			} else {
				$this->session->set_flashdata('error', 'Your email or password is incorrect. Please try again.');
			}
		}

		if ($this->users->validate_session('admin')) {
			redirect('/admin/home');
		}

		$this->load->admin_template('admin_login');
	}

	// Dashboard
	public function home()
	{
		$this->load->model('reviews');
		$count = $this->reviews->count_pending();

		$s = ($count->pending == 1) ? '' : 's';

		$data['pending_count'] = $count->pending;
		$data['pending_phrase'] = $count->pending . ' review' . $s . ' pending	';

		$this->load->admin_template('admin_home', $data);
	}

	// Manage Reviews
	public function reviews()
	{
		$this->load->helper('form');
		$this->load->model('reviews');
		$this->load->model('fundraisers');

		$fundraiserid = $this->input->get('fundraiserid');

		$data = array();
		$data['fundraiserid'] = $fundraiserid;
		$data['fundraisers_select'] = $this->fundraisers->get(0);
		$data['reviews'] = $this->reviews->get_reviews_for_fundraiser($fundraiserid, 0, 0, 0);

		$data['header_extra'] = '<link rel="stylesheet" type="text/css" href="../assets/datatables-bs-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="../assets/datatables-responsive-2.1.1.min.css" />';
		$data['footer_extra'] = '<script type="text/javascript" src="../assets/datatables-1.10.15.min.js"></script>
		<script type="text/javascript" src="../assets/datatables-2.1.1.responsive.min.js"></script>';

		$this->load->admin_template('admin_reviews', $data);
	}

	// Approve Reviews
	public function approve()
	{
		$this->load->helper('form');
		$this->load->model('reviews');

		$data = array('approve' => true);
		$data['reviews'] = $this->reviews->get(50, 0, 0);
		$data['header_extra'] = '<link rel="stylesheet" type="text/css" href="../assets/datatables-bs-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="../assets/datatables-responsive-2.1.1.min.css" />';
		$data['footer_extra'] = '<script type="text/javascript" src="../assets/datatables-1.10.15.min.js"></script>
		<script type="text/javascript" src="../assets/datatables-2.1.1.responsive.min.js"></script>';

		$this->load->admin_template('admin_reviews', $data);
	}

	// Process Reviews
	public function save_approved()
	{
		$this->load->helper('url');
		$fundraiserid = $this->input->post('fundraiserid', true);

		$action = $this->input->post('action');
		$reviewids = $this->input->post('reviewids', true);

		if (empty($reviewids)) {
			$this->session->set_flashdata('error', 'No reviews were selected.');
			redirect('admin/' . ($fundraiserid ? 'reviews' : 'approve') . ($fundraiserid ? '?fundraiserid=' . $fundraiserid : ''));
		}

		$this->load->model('fundraisers');
		$this->load->model('reviews');

		$deleted = array();
		$fundraiserids = array(); // store separate array to avoid updating counts twice
		foreach ($reviewids AS $reviewid => $fid) {
			if (!$fid) {
				continue;
			}

			$fundraiserids[] = $fid;
			if ($action == 'delete') {
				$deleted[] = $reviewid;
			} else {
				$this->reviews->update(
					array('approved' => 1),
					$reviewid
				);
			}
		}

		if (!empty($deleted)) {
			$this->reviews->delete($deleted);
			$deleted_count = sizeof($deleted);
			$this->session->set_flashdata('success', 'You have deleted ' . $deleted_count . ' review' . ($deleted_count != 1 ? 's' : ''));
		}

		if (!empty($fundraiserids)) {
			$fundraiserids = array_unique($fundraiserids);
			foreach ($fundraiserids AS $fid) {
				$this->fundraisers->update_count($fid);
			}
			$approved_count = sizeof($reviewids);
			$this->session->set_flashdata('success', 'You have approved ' . $approved_count . ' review' . ($approved_count != 1 ? 's' : ''));
		}

		redirect('admin/' . ($fundraiserid ? 'reviews' : 'approve') . ($fundraiserid ? '?fundraiserid=' . $fundraiserid : ''));
	}

	// Manage Fundraisers
	public function fundraisers($startat = 0)
	{
		$this->load->helper('form');
		$this->load->library('pagination');
		$this->load->model('fundraisers');

		$data = array();
		$data['header_extra'] = '<link rel="stylesheet" type="text/css" href="../assets/datatables-bs-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="../assets/datatables-responsive-2.1.1.min.css" />';
		$data['footer_extra'] = '<script type="text/javascript" src="../assets/datatables-1.10.15.min.js"></script>
		<script type="text/javascript" src="../assets/datatables-2.1.1.responsive.min.js"></script>';

		$data['fundraisers'] = $this->fundraisers->get(0, $startat);
		$this->load->admin_template('admin_fundraisers', $data);
	}

	// Save Fundraiser
	public function save_fundraiser() {
		$title = $this->input->post('title', true);
		$id = $this->input->post('fundraiserid', true);

		$this->load->model('fundraisers');

		$this->fundraisers->save(array('title' => $title), $id);

		$json = array('token' => $this->security->get_csrf_hash());

		if ($this->fundraisers->errors != '') {
			$json['errors'] = $this->fundraisers->errors;
		} else if (!$id) {
			$this->session->set_flashdata('success', 'Your fundraiser has been added.');
		}

		echo json_encode($json);
	}

	// Delete Fundraiser
	public function delete_fundraiser() {
		$id = $this->input->post('delete-fundraiserid', true);
		$this->load->model('fundraisers');
		$this->fundraisers->delete($id);

		$this->session->set_flashdata('warning', 'The fundraiser has been deleted.');
		redirect('admin/fundraisers');
	}

	// Update All Counts (hidden maintentance)
	public function update_all_counts() {

		$this->load->model('fundraisers');
		$fundraisers = $this->fundraisers->get(0);

		foreach ($fundraisers AS $fund) {
			$this->fundraisers->update_count($fund->id);
		}
	}

	// Logout
	public function logout()
	{
		session_destroy();
		redirect('admin');
	}
}
