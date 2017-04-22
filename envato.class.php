<?php 
/**
* Class Name:			Envato API PHP Class
* Description:			Envato API PHP wrapper to deal with Envato API endpoints.
* Version:				1.0.0
* Author:				Roudy Hermez
* Author URI:			http://roudy.ca
* License:				MIT
* License URI:			https://opensource.org/licenses/MIT
*/

class Envato {
	
	
	public  $api_url = 'https://api.envato.com/';
	public  $redirect_uri;
	private $client_id;
	private $client_secret;
	private $access_token;
	private $personal_token;
	
	public function  __construct($client_id,$client_secret,$redirect_uri){
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri  = $redirect_uri;
	}
	

	public function set_access_token($access_token){
		$this->access_token   = $access_token;
	}
	
	public function set_personal_token($personal_token){
		$this->personal_token = $personal_token;
	}
	
	// Envato Login Url 
	function login_url() {
		return $this->api_url.'authorization?response_type=code&client_id='.$this->client_id.'&redirect_uri='.$this->redirect_uri;
	}

	// Function to Get Tokens
	function get_tokens($code) {
		$url = $this->api_url.'token';
		$params = array(
			'grant_type'    => 'authorization_code',
			'code'          => $code,
			'redirect_uri'  => $this->redirect_uri,
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
		$result = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($result, true);
		return $data;
		
		/*
		$refresh_token = $data['refresh_token'];
		$token_type    = $data['token_type'];
		$access_token  = $data['access_token'];
		$expires_in    = $data['expires_in'];
		*/
	}
	
	
	// Function to Extend The Token Life
	function refresh_token($refresh_token) {
		$url = $this->api_url.'token';
		$params = array(
			'grant_type'    => 'refresh_token',
			'refresh_token' => $refresh_token,
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
		$data = json_decode($result, true);
		return $data;
		
		/*
		$token_type    = $data['token_type'];
		$access_token  = $data['access_token'];
		$expires_in    = $data['expires_in'];
		*/
	}
	
	
	// Function to Execute Api Calls
	function curl_get_data($url,$parameters = array()) {
		if (count($parameters) == 0) {
			$call = $this->api_url.$url;
		} else {
			$call = $this->api_url.$url.'?'.http_build_query($parameters);
		}
		$headers = array(
		    'Authorization: Bearer ' . $this->access_token
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, "support");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $call);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$results = curl_exec($ch);
		curl_close($ch);
		return $results;
	}
	
	
	
	function search_item($parameters = array()) {
		$url = 'v1/discovery/search/search/item';
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function search_comment($parameters = array()) {
		$url = 'v1/discovery/search/search/comment';
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function active_threads_by_site($site) {
		if (empty($site)) {
			$site = 'codecanyon';
		}
		$url = 'v1/market/active-threads:'.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function site_items_number($site) {
		if (empty($site)) {
			$site = 'codecanyon';
		}
		$url = 'v1/market/number-of-files:'.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function forum_posts_by_user($username) {
		$url = 'v1/market/forum_posts:'.$username.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function thread_status($thread_id) {
		$url = 'v1/market/thread-status:'.$thread_id.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function total_users() {
		$url = 'v1/market/total-users.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function total_items() {
		$url = 'v1/market/total-items.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function item_prices($item_id) {
		$url = 'v1/market/item-prices:'.$item_id.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function user_details_by_username($username) {
		$url = 'v1/market/user:'.$username.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function user_items_by_site($username) {
		$url = 'v1/market/user-items-by-site:'.$username.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function popular_items_by_site($site) {
		$url = 'v1/market/popular:'.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function featured_items_by_site($site) {
		$url = 'v1/market/features:'.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function categories_by_site($site) {
		$url = 'v1/market/categories:'.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	
	function new_site_files($site,$category) {
		$url = 'v1/market/new-files:'.$site.','.$category.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function new_user_files($username,$site) {
		$url = 'v1/market/new-files-from-user:'.$username.','.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function random_new_files_by_site($site) {
		$url = 'v1/market/random-new-files:'.$site.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function user_badges($username) {
		$url = 'v1/market/user-badges:'.$username.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function earnings_sales_by_month() {
		$url = 'v1/market/private/user/earnings-and-sales-by-month.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function user_statement() {
		$url = 'v1/market/private/user/statement.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function download_purchase($purchase_code) {
		$url = 'v1/market/private/user/download-purchase:'.$purchase_code.'.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function user_account() {
		$url = 'v1/market/private/user/account.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
	function user_username() {
		$url = 'v1/market/private/user/username.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data['username'];
	}
	
	function user_email() {
		$url = 'v1/market/private/user/email.json';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data['email'];
	}
	
	function item_details_by_id($item_id) {
		$url = 'v2/market/catalog/item';
		$parameters = array('id' => $item_id);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function author_sale_by_code($purchase_code) {
		$url = 'v2/market/author/sale';
		$parameters = array('code' => $purchase_code);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function author_sales($page) {
		$url = 'v3/market/author/sales';
		$parameters = array('page' => $page);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function buyer_purchase_by_code($purchase_code) {
		$url = 'v3/market/buyer/purchase';
		$parameters = array('code' => $purchase_code);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function buyer_purchases($page) {
		$url = 'v3/market/buyer/purchases';
		$parameters = array('page' => $page);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	function collection_by_id($id) {
		$url = 'v2/market/catalog/collection';
		$parameters = array('id' => $id);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	
	function user_collection_by_id($id) {
		$url = 'v3/market/user/collection';
		$parameters = array('id' => $id);
		$result = $this->curl_get_data($url,$parameters);
		$data = json_decode($result, true);
		return $data;
	}
	
	
	function user_collections() {
		$url = 'v3/market/user/collections';
		$result = $this->curl_get_data($url);
		$data = json_decode($result, true);
		return $data;
	}
	
}
?>
