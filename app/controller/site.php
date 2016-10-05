<?php
class Site extends AppController{
	
	function about(){
    	return array(
    		'pageTitle'=>"iPrank.TV - About us",
    		'pageCanonical'=>_about(),
    		'pageDescription'=>"Introduction to iPrank.TV",
    	);
	}
	
	function tou(){
		return array(
			'pageTitle'=>"iPrank.TV - Terms of use",
			'pageCanonical'=>_tou(),
			'pageDescription'=>"Terms of use for iPrank.TV",
		);
	}
	
	function privacy() {
		return array(
			'pageTitle'=>"iPrank.TV - Privacy policy",
			'pageCanonical'=>_privacy(),
			'pageDescription'=>"Privacy policy for iPrank.TV",
		);
	}
	
	function faq() {
		return array(
			'pageTitle'=>"iPrank.TV - Help center",
			'pageCanonical'=>_faq(),
			'pageDescription'=>"Get help from iPrank.TV",
		);
	}
	
	function page_not_found() {
		send_http_status(404);
		return array(
			'pageTitle'=>"iPrank.TV - 404 not found",
			'pageDescription'=>"The page requested is missing on iPrank.TV",
		);
		
	}
}
