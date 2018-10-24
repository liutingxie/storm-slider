<?php
class Storm_Sliders {

	public static $count = null;

	// Return the count of found sliders
	public static function count() {
		return self::$count;
	}

	public static function limit() {
		// Get sliders per page value
		$per_page = get_option('storm-screen-setting');
		if( empty($per_page) || $per_page < 1 ) {
			$per_page = 20;
		}

		return $per_page;
	}

	public static function getSliders() {
		global $wpdb;

		$where = '';
		$order = '';

		if( isset($_GET['paged']) ){

			$paged  = esc_html($_GET['paged']);
			$order = "ORDER BY id ASC";
		}
		else
		{
			$paged = 1;
		}

		if( isset($_GET['search']) ){
			$search_tag = esc_html(stripslashes($_GET['search']));
		}
		else
		{
			$search_tag = '';
		}

		if( $search_tag ){
			$where = "WHERE name LIKE '%".$search_tag."%' ";
		}

		$per_page = self::limit();

		$limit = $per_page * $paged - $per_page;

		$sliders = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM ".$wpdb->prefix.STORM_DB_TABLE." $where $order LIMIT $limit, $per_page", ARRAY_A);
	 	$found_count = $wpdb->get_col("SELECT FOUND_ROWS()");

	 	self::$count = (int) $found_count[0];

	 	foreach ($sliders as $key => $slider) {
	 		$sliders[$key]['data'] = json_decode($slider['data'], true);
	 	}

	  	return $sliders;
	}

	public static function importSlider($name, $data) {
		global $wpdb;

		// Add
		$wpdb->insert($wpdb->prefix . STORM_DB_TABLE, array(
			'name' => $name,
			'data' => json_encode($data),
			'date_create' => current_time('mysql'),
			'date_modified' => current_time('mysql')
		), array('%s', '%s', '%s', '%s'));

		return $wpdb->insert_id;
	}

	public static function add(){
		global $wpdb;

		$title = 'New Slider';

		$data = array(
			'slider' => array( 'name' => $title, 'new' => 'true' ),
			'layers' => array()
		);

		// Add
		$wpdb->insert($wpdb->prefix . STORM_DB_TABLE, array(
			'name' => $title,
			'data' => json_encode($data),
			'date_create' => current_time('mysql'),
			'date_modified' => current_time('mysql')
		), array('%s', '%s', '%s', '%s'));

		return $wpdb->insert_id;

	}

	public static function remove($id){

		if(empty($id)) return false;

		global $wpdb;

		$table_name = $wpdb->prefix . STORM_DB_TABLE;

		// Delete

		$wpdb->delete($table_name, array('id' => $id), array('%d'));

		return true;
	}

	public static function update($id, $data) {
		if(empty($id)) return false;

		global $wpdb;

		$table_name = $wpdb->prefix . STORM_DB_TABLE;

		// Update
		$wpdb->update($table_name, array(
			'name' => $data['slider']['name'],
			'data' => json_encode($data),
			'date_modified' => current_time('mysql')
		), array('id' => $id), array('%s', '%s', '%s'));

		return true;
	}

	public static function _getByid($id) {
		if(empty($id))  return false;

		global $wpdb;

		$table_name = $wpdb->prefix . STORM_DB_TABLE;

		//get slider
		$slider = $wpdb->get_row("SELECT * FROM ". $table_name ." WHERE id = ". $id, ARRAY_A);

		//check the slider value
		if(!is_array($slider)) return false;

		$slider['data'] = json_decode($slider['data'], true);

		return $slider;
	}

	public static function getOption($name) {
		global $wpdb;

		$table_name = $wpdb->prefix . STORM_DB_OPTION_TABLE;
		$option_name = $name;

		//get transition data
		$option = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". $table_name ." WHERE option_name = '%s'", $option_name), ARRAY_A);

		$option['option_value'] = json_decode($option['option_value'], true);

		return $option;
	}

	public static function addOption($option_name, $data) {
		global $wpdb;

		// Add data to stormslider_option table
		$wpdb->insert($wpdb->prefix . STORM_DB_OPTION_TABLE, array(
			'option_name' => $option_name,
			'option_value' => json_encode($data),
			'date_create' => current_time('mysql'),
			'date_modified' => current_time('mysql')
		), array('%s', '%s', '%s'));

		return $wpdb->insert_id;
	}

	public static function updateOption($option_name, $data) {
		global $wpdb;

		// Update data to stormslider_option table
		$wpdb->update($wpdb->prefix . STORM_DB_OPTION_TABLE, array(
			'option_value' => json_encode($data)
		), array('option_name' => $option_name), array('%s'), array('%s'));
	}

	public static function removeOption($id) {
		global $wpdb;

		// Remove option
		$wpdb->delete($wpdb->prefix. STORM_DB_OPTION_TABLE, array('id' => $id), array('%d'));

		return true;
	}
}
