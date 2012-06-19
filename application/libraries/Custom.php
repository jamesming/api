<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Custom Library Related to SceneCredit
 * @autoloaded YES
 * @path \system\application\libraries\Custom.php
 * @package BackEnd
 * @author James Ming <jamesming@gmail.com>
 * @copyright 2010 Prospace LLC
 * @version 1.0
 * 
 */
class Custom {

private $CI;			// CodeIgniter instance



	/**
	 * register_process
	 * @package BackEnd
	 * @author James Ming <jamesming@gmail.com>
	 * @access public
	 */
	 
	function register_process( $post_array ){	
		
		
		if( $post_array['email'] == ''){
			return array(
				'is'=>'false',
				'key'=>'email',
				'message'=>'Email field must not be blank.'
			);
		};

		if( $post_array['password'] == ''){
			return array(
				'is'=>'false',
				'key'=>'password',
				'message'=>'Password field must not be blank.'
			);
		};
		

		$table = 'users';
		
	  $where_array = array('email' => $post_array['email']);

	  if( $this->CI->my_database_model->check_if_exist($where_array, $table)){
	   	
	   			$validation['is'] = 'false';
	   			$validation['key']='email';
	   			$validation['message']='You have already registered on this site.';
	   	
	   			return $validation;


	  }else{
	  	
	  	
	  			$this->CI->load->helper('security');
	  	
					$insert_what = array(
					                        'email' => $post_array['email'],
					                        'password' =>   do_hash(  $post_array['password'], 'md5' )
					                );
					
					$user_id = $this->CI->my_database_model->insert_table(
													$table, 
													$insert_what
													); 
									
					
//					$this->CI->load->library('email');
//
//					$this->CI->email->from('jamesming#gmail.com', 'James Ming');
//					$this->CI->email->to($post_array['email']);
//					// $this->CI->email->cc('another@another-example.com');
//					// $this->CI->email->bcc('them@their-example.com');
//					
//					$this->CI->email->subject('Email Test');
//					$this->CI->email->message( base_url().'index.php/home/validate_account/' . $user_id);
//					
//					$this->CI->email->send();
					
					
					/** SEND EMAIL OUT	
					** $url = '<?php echo base_url    ?>index.php/home/validate_account/' . $user_id;
					**/
					
					$validation['is'] = 'true';				
					$validation['key']= 'email';								
					$validation['message']='Thank you for registering.  Please check your email to continue the registration process.';							
													
		   		return 	$validation	;							
					  	
		}



	
	}
	
	/**
	 * login_process
	 * @package BackEnd
	 * @author James Ming <jamesming@gmail.com>
	 * @access public
	 */
	
	
	public function login_process(  $post_array ){

		$table = 'users';
		
		if( $post_array['email'] == ''){
			return array(
				'is'=>'false',
				'key'=>'email',
				'message'=>'Email field must not be blank.'
			);
		};

		if( $post_array['password'] == ''){
			return array(
				'is'=>'false',
				'key'=>'password',
				'message'=>'Password field must not be blank.'
			);
		};
		
	  if( !$this->CI->my_database_model->check_if_exist(
	  	$where_array = array('email' => $post_array['email']), 
	  	$table
	  )){
	   	
			return array(
				'is'=>'false',
				'key'=>'email',
				'message'=>'Account is not found in system.'
			);

	  }


		$where_array = array(
			'email' => $post_array['email'],
			'password' => md5($post_array['password'])
		);
		
		$users = $this->CI->my_database_model->select_from_table( 
			$table, 
			$select_what =  'id', 
			$where_array, 
			$use_order = FALSE, 
			$order_field = '', 
			$order_direction = 'desc', 
			$limit = 1
			);

	  if( count( $users ) > 0 ){
		
					return array(
						'is'=>'true',
						'id'=>$users[0]->id
					);	
		
		}else{
		
			return array(
				'is'=>'false',
				'key'=>'password',
				'message'=>'Wrong password submitted.'
			);		
		
		};
						
	}
		
	
	/**
	 * check_for_duplicate_email
	 * @package BackEnd
	 * @author James Ming <jamesming@gmail.com>
	 * @access public
	 */
	 
	public function check_for_duplicate_email($email){	

		$table = 'users';
		
		$where_array = array('email' => $email);

	  if( $this->CI->my_database_model->check_if_exist($where_array, $table)){
	   	
				echo 'true';

		}else{
	   	
				echo 'false';	
		}

	}
	
	
	/**
	 * check_available_url
	 * @package BackEnd
	 * @author James Ming <jamesming@gmail.com>
	 * @access public
	 */
	 
	public function check_available_profile_url( $profile_url, $user_id = 0){	

		$table = 'users';
		
		$where_array = array('profile_url' => $profile_url);
		
	  if( $this->CI->my_database_model->check_if_exist($where_array, $table)){
	  	
	  		if( $user_id != 0){
	  			
				   		$where_array = array(
				   			'id' => $user_id,
				   			'profile_url' => $profile_url
				   		);
				   		
				  		if( $this->CI->my_database_model->check_if_exist($where_array, $table)){
				  			
									$response['status'] = 'true';
									$response['message'] = '\'' .$profile_url . '\' is still available';	

							}else{
								
									$response['status'] = 'false';
									$response['message'] = 'This URL is unavailable.';

				   		}	  
				   					
	  		}else{
	  			
							$response['status'] = 'false';
							$response['message'] = 'This URL is unavailable.';
	  			
	  		};

		}else{

				$response['status'] = 'true';
				$response['message'] = 'Available';
				
		}
		
		return $response;	
		

	}
	
	
	


function custom(){
	
	$this->CI =& get_instance();	
	
}

function intersect_array($array1,$array2) 
{ 
      
//		$array1 = array(1,2,3,4,5,6,7,8,9,12,3);
//		$array2 = array(5,6,7,11,12); 
         
    $result = array(); 
    foreach ($array1 as $val) { 
      if (($key = array_search($val, $array2, TRUE))!==false) { 
         $result[] = $val; 
         unset($array2[$key]); 
      } 
    } 
    return $result; 
} 

	
}


/* End of file Tool.php */ 
/* Location: \system\application\libraries\Custom.php */
