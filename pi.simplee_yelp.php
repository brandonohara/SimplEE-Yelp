<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	require_once(PATH_THIRD."simplee_yelp/config.simplee_yelp.php");
	
	$plugin_info = array(
		'pi_name' 			=> SIMPLEE_YELP_NAME,
		'pi_version' 		=> SIMPLEE_YELP_VERSION,
		'pi_author' 		=> 'Brandon OHara',
		'pi_author_url' 	=> 'http://brandonohara.com/',
		'pi_description' 	=> '',
	    'pi_usage'        	=> Simplee_yelp::usage()
	);

	class Simplee_yelp {
		public $plugin_name = SIMPLEE_YELP_NAME;
    
		public function __construct(){
			
		}
		
		private function _request($path, $params = array()){
			$unsigned_url = "http://api.yelp.com/v2/" . $path . "?" . http_build_query($params);
		    $token = new OAuthToken(YELP_TOKEN, YELP_TOKEN_SECRET);
		    $consumer = new OAuthConsumer(YELP_CONSUMER_KEY, YELP_CONSUMER_SECRET_KEY);
		    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
		
		    $oauthrequest = OAuthRequest::from_consumer_and_token(
		        $consumer, 
		        $token, 
		        'GET', 
		        $unsigned_url
		    );
		    
		    $oauthrequest->sign_request($signature_method, $consumer, $token);
		    $signed_url = $oauthrequest->to_url();
		    
		    // Send Yelp API Call
		    $ch = curl_init($signed_url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    $data = curl_exec($ch);
		    curl_close($ch);
		    
		    return json_decode($data, true);
		}
		
		public function search(){
			$params = array();
			$params['offset'] = ee()->TMPL->fetch_param("offset", 0);
			$params['limit'] = ee()->TMPL->fetch_param("limit", 10);
			$params['term'] = ee()->TMPL->fetch_param("term", "");
			$params['location'] = ee()->TMPL->fetch_param("location", "");
			
			//set categories
			$delimiter = ee()->TMPL->fetch_param("category_delimiter", "|");
			$categories = explode($delimiter, ee()->TMPL->fetch_param("category", ""));
			$params['category_filter'] = implode(",", $categories);;
			
			$data = $this->_request("search", $params);
			
			$total = count($data['businesses']);
			if($total == 0)
				return ee()->TMPL->no_results();
			
			$count = 0;
			$businesses = array();
			foreach($data['businesses'] as $row){
				$business = new Yelp_business($row);
				$business->count = $count++;
				$business->total_count = $total;
				array_push($businesses, json_decode(json_encode($business), true));
			}
			return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $businesses);
		}
		
		public function business(){
			$id = ee()->TMPL->fetch_param("id", NULL);
			
			if(!$id)
				return ee()->TMPL->no_results();
				
			$data = $this->_request("business/".$id);
			
			if(!$data)
				return ee()->TMPL->no_results();
			
			$business = new Yelp_business($data);
			return ee()->TMPL->parse_variables_row(ee()->TMPL->tagdata, json_decode(json_encode($business), true));
		}
		
		public function p($item){
			echo "<pre>";
			print_r($item);
			echo "</pre>";
		}
		
		public static function usage(){
	        return "";
	    }
	}

/* End of file pi.simplee_yelp.php */
/* Location: ./system/expressionengine/third_party/simplee_yelp/pi.simplee_yelp.php */