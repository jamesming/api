<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rest extends CI_Controller {
	
	function __construct(){
      parent::__construct();
		
			$this->table = $this->uri->segment(3);	
			
			$this->id = $this->uri->segment(4);	
	}

	/* index
	*
	*		
	*  Usage:  /api/index.php/rest/index/:table/:id
	*
	*
	*/
	function index(){


				
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		
		switch ($request_method){
			
			case 'get':
			
				if( $this->id ){
					
					$data = $this->input->get();
					
					$categories = $this->query->get_table_by_id( $this->table, $this->id );	
										
				}else{
					
					$data = $this->input->get();
					
					$categories = $this->query->get_table_rows( $this->table );					
					
				};
				
				echo json_encode(  $categories  );
				
				break;
				
			case 'post':
			
				$this->my_database_model->create_generic_table($this->table );
			
				$parameters = json_decode(file_get_contents('php://input'), true);
				
				foreach( $parameters  as  $key => $value){
					$set_what[$key] = $value;
				};
				
				$post_array['table'] = $this->table;
				
				$post_array['set_what'] = $set_what;
			 
				echo $this->query->insert( $post_array );
				
				break;
				
			case 'put':
			
				$parameters = json_decode(file_get_contents('php://input'), true);
				
				foreach( $parameters  as  $key => $value){
					$key == 'id' || $set_what_array[$key] = $value;
				};
				
				$this->query->put_table_by_id( 
									$this->table, 
									$this->id  = $parameters['id'],
									$set_what_array
						 );

				
				break;
				
			case 'delete':
				
				$this->my_database_model->delete_from_table(
				$this->table, 
				$where_array = array(
					'id' => $this->id
				));
				
				$this->remove();
				
				break;				
				
		}		
		
	}
	
	
	function upload(){
		
		$prefix_file_name = 'file';
		
		$destination_filename = $prefix_file_name . '.png';
		
		$this->my_database_model->create_generic_table($this->table );
		
		$path_array = array(
			'folder'=> $this->table,
			'image_id' => $this->id
		);

		$upload_path = $this->tools->set_directory_for_upload( $path_array );
		

		$config['upload_path'] = './' . $upload_path;
		$config['allowed_types'] = 'bmp|jpeg|gif|jpg|png';
		$config['overwrite'] = 'TRUE';
		$config['file_name'] = $destination_filename;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload("file_name")){
					echo $this->upload->display_errors();
					exit;
		}
		else{
			
			$original_path = $upload_path  . '/' . $destination_filename;
			
			$this->resize(
				$original_path, 
				$suffix = '_thumb', 
				$target_width = $this->input->post('thumb_width'), 
				$target_height = $this->input->post('thumb_height')
			);
			
			$thumb_path = $upload_path  . '/' . $prefix_file_name . '_thumb.png';
			
			$this->resize(
				$original_path, 
				$suffix = '_full', 
				$target_width = $this->input->post('full_width'), 
				$target_height = $this->input->post('full_height')
			);
			
			$full_path = $upload_path  . '/' . $prefix_file_name . '_full.png';				
			
		?>

<script type="text/javascript" language="Javascript">
	
<?php     

$image_information = getimagesize($thumb_path);
$width_of_file = $image_information[0];
$height_of_file = $image_information[1];
$marginLeft = $width_of_file /2;
$marginTop  = $height_of_file /2;										

?>

window.parent.<?php  echo $this->input->post('php_callback_dom_el');   ?>
.css({
	width:'<?php  echo $width_of_file   ?>', 
	height: '<?php  echo $height_of_file   ?>', 
	'margin-left':'-<?php echo $marginLeft;    ?>px', 
	'margin-top':'-<?php echo $marginTop;    ?>px'
})
.attr({
		image_id	: <?php  echo $this->id   ?>
	 ,src: '<?php  echo base_url() . $thumb_path . "?random=" . rand(5,124344523)   ?>'
	
})
.parent().attr({
	'href':'<?php  echo base_url() . $original_path . "?random=" . rand(5,124344523)   ?>'	
	,'rel':'gallery'	
	,'title':'<?php  echo $this->id   ?>'	
})
.addClass('pirobox_gall');



window.parent.$.piroBox_ext({
								piro_speed :700,
								bg_alpha : 0.9,
								piro_scroll : true,
								piro_drag :null,
								piro_nav_pos: 'bottom'
							});



/* 
*
*  The following will be called if cropping
*
*/
window.parent.$('#launchModal').click();
								

</script>

<?php

		}		
		
	}
	
	function remove(){
		
		$dir_path = 'uploads/' . $this->table . '/' 
		. $this->id . '/';
		
		$this->tools->recursiveDelete($dir_path);
		
		
	}
	
	
	function resize($path, $suffix, $target_width, $target_height){
		
			$image_information = getimagesize($path);
			
 			$width_of_file = $image_information[0];
			$height_of_file = $image_information[1];
			
			if( $width_of_file > $height_of_file){
				
				$this->tools->clone_and_resize_append_name_of(
					$suffix, 
					$path, 
					$target_width,
					$height = $this->tools->get_new_size_of (
												$what = 'height', 
												$target_width, 
												$width_of_file, 
												$height_of_file 
												)
					);				
					
			}else{
				
				$this->tools->clone_and_resize_append_name_of(
					$suffix, 
					$path, 
					$width = $this->tools->get_new_size_of (
												$what = 'width', 
												$target_height, 
												$width_of_file, 
												$height_of_file 
												), 
					$target_height
					);				
				
			};


		
	}

	/* create_table
	*
	*  
	*
	*/
	function create_table(){
		
		$this->my_database_model->create_generic_table($this->table );
		
		
		$fields_array = array(
		                      'name' => array(
		                                               'type' => 'varchar(255)'
		                                    )/*,
		                      'image_type_id' => array(
		                                               'type' => 'int(11)'
		                                    ),*/
		//                      'county' => array(
		//                                               'type' => 'varchar(255)'
		//                                    ),
		//                      'city' => array(
		//                                               'type' => 'varchar(255)'
		//                                    ),
		//                      'state' => array(
		//                                               'type' => 'varchar(255)'
		//                                    )
		              ); 
	              
		$this->my_database_model->add_column_to_table_if_not_exist(
			$this->table, 
			$fields_array
		);
	   
	
	}

}