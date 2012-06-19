<?php

/**
 * Access for Users Table
 * @autoloaded YES
 * @path /system/application/models/my_payment_model.php
 * @package BackEnd
 * @author James Ming <jamesming@gmail.com>
 * @copyright 2010 Prospace LLC
 * @version 1.0
 * @todo method to delete a record
 * 
 */
 
class My_facebook_model extends CI_Model  {
	
		private $fbconfig;

    function __construct()
    {

        parent::__construct();

				$this->fbconfig = array();
								
		    $this->fbconfig['appid' ]     = "127320740693830";
		    $this->fbconfig['secret']     = "0bc45d5a82dd94e9184feb78b393041d";
		    $this->fbconfig['baseurl']    = "http://scenecredit.com/index.php/home/facebook";
		    
				
				$this->load->helper('facebook');
				
		    $this->facebook = new Facebook(array(
		      'appId'  => $this->fbconfig['appid'],
		      'secret' => $this->fbconfig['secret'],
		      'cookie' => true,
		    ));
		    
		    

			    $this->user = $this->facebook->getUser();


			    $this->loginUrl   = $this->facebook->getLoginUrl(
			            array(
			                'scope'         => 'email,
													                publish_stream,
													                user_birthday,
													                user_location,
													                user_work_history,
													                user_about_me,
													                user_hometown',
			                'redirect_uri'  => $this->fbconfig['baseurl'],
			                'display' => 'popup'
			            )
			    );


		}
		
		
		function get_logout_url($next_url){
		    return $this->facebook->getLogoutUrl(
		    	$params=array(), $next_url 
		    );
		}




     
}