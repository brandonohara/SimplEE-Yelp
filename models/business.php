<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Yelp_business {
    
		public function __construct($row){
			foreach($row as $key => $value){
				$this->$key = $value;
			}
			
			//set latitude & longitude
			$this->latitude = $row['location']['coordinate']['latitude'];
			$this->longitude = $row['location']['coordinate']['longitude'];
		}
		
	}