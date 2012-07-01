<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


 
class Query {

	private $CI;	
	
	function query(){
		
		$this->CI =& get_instance();	
		
	}
	
	
	function get_table_by_id( $table, $id){
		
		return $this->CI->tools->object_to_array($this->CI->my_database_model->select_from_table( 
			$table, 
			$select_what = '*',    
			array(
				'id' => $id
			), 
			$use_order = FALSE, 
			$order_field = '', 
			$order_direction = 'asc', 
			$limit = -1, 
			$use_join = FALSE
			));
		
	}	
	
	
	function get_table_rows( $table){
		
		return $this->CI->tools->object_to_array($this->CI->my_database_model->select_from_table( 
			$table, 
			$select_what = '*',    
			array(), 
			$use_order = FALSE, 
			$order_field = '', 
			$order_direction = 'asc', 
			$limit = -1, 
			$use_join = FALSE
			));
		
	}
	
	function put_table_by_id( $table, $id, $set_what_array){
		
		$this->add_column_if_not_exist($table, $set_what_array);		
		
		return $this->CI->my_database_model->update_table_where(
					$table, 
					$where_array = array('id' => $id),
					$set_what_array
					);
		
	}	

	function insert($post_array){

		$this->add_column_if_not_exist($post_array['table'], $post_array['set_what']);		
		
		return $this->CI->my_database_model->insert_table(
									$post_array['table'], 
									$post_array['set_what']
									); 					

	}

	function update($post_array){

		$this->add_column_if_not_exist($post_array['table'],  $post_array['set_what']);

		return $this->CI->my_database_model->update_table_where(
					$post_array['table'], 
					$where_array = array('id'=>$post_array['id']),
					$post_array['set_what']
					);	
	}
	

	
	function add_column_if_not_exist($table, $set_what_array){
		
			foreach( $set_what_array  as  $key => $value){
				$fields_array = array(
							$key => array('type' => 'varchar(255)')                                          
            	); 

				$this->CI->my_database_model->add_column_to_table_if_not_exist(
					$table, 
					$fields_array
				);    					
			};
 	
		
	}
	
}