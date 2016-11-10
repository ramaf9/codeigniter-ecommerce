<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/libraries/REST_Controller.php'; // rest_api library

class General extends REST_Controller {

    public $data;

    public function __construct()
    {
        parent::__construct();
        // $this->load->helper('cookie');
        //
        // $cookie_products = get_cookie('products');
        // $cookie_products = !empty($cookie_products) ? explode(',', $cookie_products) : [];
        //
        // if (!empty($cookie_products)) {
        //     foreach ($cookie_products as $cp) {
        //         $exploded_cp = explode('-', $cp);
        //         if (!empty($exploded_cp[0]) && !empty($exploded_cp[1])) {
        //             $this->data['cart'][$exploded_cp[0]] = ['id' => $exploded_cp[0], 'quantity' => $exploded_cp[1]];
        //         }
        //     }
        // }
        //
        // $this->data['general'] = $this->general_model->get_data();
        // $this->data['categories'] = $this->category_model->get_data_with_products();
        // $this->data['pages'] = $this->page_model->get_data();
        // $this->data['user'] = $this->user_model->get_user();
    }
}

class Admin extends REST_Controller {

    public $data;
    public $token;

    public function __construct()
    {
        parent::__construct();
        $token = $this->input->get_request_header('authorization', TRUE);
		if ($token != null) {
			$token = explode("Bearer ", $token);
			$this->token = $this->jwt->decode($token[1],secret);
            if(!isset($this->token->role) || $this->token->role != 'admin'){
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_auth
    			], REST_Controller::HTTP_FORBIDDEN);
            }
		}
        else{
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_token
    			], REST_Controller::HTTP_FORBIDDEN);
        }

        // $this->data['general'] = $this->general_model->get_data();
        // $this->data['categories'] = $this->category_model->get_data();
        //
        // if (empty($this->session->userdata['admin_user_id']) && !in_array('login', $this->uri->segment_array())) {
        //     redirect('admin/users/login');
        // }
    }
}

class Buyer extends REST_Controller {

    public $data;
    public function __construct()
    {
        parent::__construct();
        $token = $this->input->get_request_header('authorization', TRUE);
        if ($token != null) {
			$token = explode("Bearer ", $token);
			$this->token = $this->jwt->decode($token[1],secret);

            if(!isset($this->token->role) || $this->token->role != 'buyer'){
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_auth
    			], REST_Controller::HTTP_FORBIDDEN);
            }
		}
        else{
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_token
    			], REST_Controller::HTTP_FORBIDDEN);
        }
        // $this->load->helper('cookie');
        //
        // $cookie_products = get_cookie('products');
        // $cookie_products = !empty($cookie_products) ? explode(',', $cookie_products) : [];
        //
        // if (!empty($cookie_products)) {
        //     foreach ($cookie_products as $cp) {
        //         $exploded_cp = explode('-', $cp);
        //         if (!empty($exploded_cp[0]) && !empty($exploded_cp[1])) {
        //             $this->data['cart'][$exploded_cp[0]] = ['id' => $exploded_cp[0], 'quantity' => $exploded_cp[1]];
        //         }
        //     }
        // }
        //
        // $this->data['general'] = $this->general_model->get_data();
        // $this->data['categories'] = $this->category_model->get_data_with_products();
        // $this->data['pages'] = $this->page_model->get_data();
        // $this->data['user'] = $this->user_model->get_user();
    }
}

class Seller extends REST_Controller {

    public $data;
    public function __construct()
    {
        parent::__construct();
        $token = $this->input->get_request_header('authorization', TRUE);
        if ($token != null) {
			$token = explode("Bearer ", $token);
			$this->token = $this->jwt->decode($token[1],secret);

            if(!isset($this->token->role) || $this->token->role != 'seller'){
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_auth
    			], REST_Controller::HTTP_FORBIDDEN);
            }
		}
        else{
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_token
    			], REST_Controller::HTTP_FORBIDDEN);
        }
        // $this->load->helper('cookie');
        //
        // $cookie_products = get_cookie('products');
        // $cookie_products = !empty($cookie_products) ? explode(',', $cookie_products) : [];
        //
        // if (!empty($cookie_products)) {
        //     foreach ($cookie_products as $cp) {
        //         $exploded_cp = explode('-', $cp);
        //         if (!empty($exploded_cp[0]) && !empty($exploded_cp[1])) {
        //             $this->data['cart'][$exploded_cp[0]] = ['id' => $exploded_cp[0], 'quantity' => $exploded_cp[1]];
        //         }
        //     }
        // }
        //
        // $this->data['general'] = $this->general_model->get_data();
        // $this->data['categories'] = $this->category_model->get_data_with_products();
        // $this->data['pages'] = $this->page_model->get_data();
        // $this->data['user'] = $this->user_model->get_user();
    }
}

class LoggedIn extends REST_Controller {

    public $data;
    public function __construct()
    {
        parent::__construct();
        $token = $this->input->get_request_header('authorization', TRUE);
        if ($token != null) {
			$token = explode("Bearer ", $token);
			$this->token = $this->jwt->decode($token[1],secret);

            if(!isset($this->token->role) || !$this->token->logged_in){
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_auth
    			], REST_Controller::HTTP_FORBIDDEN);
            }
		}
        else{
                // send failed response
    			$this->set_response([
    				'status' => FALSE,
    				'error' => err_token
    			], REST_Controller::HTTP_FORBIDDEN);
        }
        // $this->load->helper('cookie');
        //
        // $cookie_products = get_cookie('products');
        // $cookie_products = !empty($cookie_products) ? explode(',', $cookie_products) : [];
        //
        // if (!empty($cookie_products)) {
        //     foreach ($cookie_products as $cp) {
        //         $exploded_cp = explode('-', $cp);
        //         if (!empty($exploded_cp[0]) && !empty($exploded_cp[1])) {
        //             $this->data['cart'][$exploded_cp[0]] = ['id' => $exploded_cp[0], 'quantity' => $exploded_cp[1]];
        //         }
        //     }
        // }
        //
        // $this->data['general'] = $this->general_model->get_data();
        // $this->data['categories'] = $this->category_model->get_data_with_products();
        // $this->data['pages'] = $this->page_model->get_data();
        // $this->data['user'] = $this->user_model->get_user();
    }
}
