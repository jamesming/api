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
 
class My_twitter_model extends CI_Model  {
	
		private $fbconfig;

    function __construct()
    {

        parent::__construct();

				$this->load->helper('twitter');
				
				$this->tweet = new tweet();
				
				
				$this->tweet->enable_debug(TRUE);
				
				// If you already have a token saved for your user
				// (In a db for example) - See line #37
				// 
				// You can set these tokens before calling logged_in to try using the existing tokens.
				// $tokens = array('oauth_token' => 'foo', 'oauth_token_secret' => 'bar');
				// $this->tweet->set_tokens($tokens);
				
				
				if ( !$this->tweet->logged_in() )
				{
					// This is where the url will go to after auth.
					// ( Callback url )
					
					$this->tweet->set_callback(site_url('home/twitter'));
					
					// Send the user off for login!
					$this->tweet->login();
				}
				else
				{
					// You can get the tokens for the active logged in user:
					//$tokens = $this->tweet->get_tokens();
					// 
					// These can be saved in a db alongside a user record
					// if you already have your own auth system.
					
					
				}
				

		}
		
		
		
		function userInfo(){
			
			$userInfo = $this->tweet->call('get', 'account/verify_credentials');
			return $userInfo;
		
		}
		
		
		function logout(){
			
			$this->tweet->logout();
			
		}
		
		
		function auth(){
			$tokens = $this->tweet->get_tokens();
			echo "Token"."<br />";
			echo '<pre>';print_r(  $tokens   );echo '</pre>';  
			echo "<hr />";

			
			echo "user"."<br />";
			$user = $this->tweet->call('get', 'account/verify_credentials');
			echo '<pre>';print_r(  $user  );echo '</pre>';  
			echo "<hr />";
			
/*			
			echo "friendships"."<br />";
			$friendship 	= $this->tweet->call('get', 'friendships/show', array('source_screen_name' => $user->screen_name, 'target_screen_name' => 'elliothaughin'));
			echo '<pre>';print_r(  $friendship  );echo '</pre>';
			echo "<hr />";
			
			
			echo "timeline"."<br />";
			$timeline = $this->tweet->call('get', 'statuses/home_timeline');
			echo '<pre>';print_r( $timeline   );echo '</pre>';	
			echo "<hr />";
			

			$this->tweet->call('post', 'friendships/create', array('screen_name' => 'elliothaughin', 'follow' => TRUE));

			
			$this->tweet->call('post', 'statuses/update', array('status' => 'Cloudy in New York City'));
			
			*/

		}
     
}