<?php
	if(!defined("SIMPLEE_YELP_NAME")){
		define("SIMPLEE_YELP_NAME", "SimplEE Yelp");
		define("SIMPLEE_YELP_VERSION", "1.0.0");
		define("SIMPLEE_YELP_EE_NAME", "Simplee_yelp");
		
		define("YELP_CONSUMER_KEY", "");
		define("YELP_CONSUMER_SECRET_KEY", "");
		define("YELP_TOKEN", "");
		define("YELP_TOKEN_SECRET", "");
	}
	
	require_once("libraries/OAuth.php");
	require_once("models/business.php");
	
	