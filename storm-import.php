<?php

/**
 * @Author: liutingxie
 * @Date:   2017-11-05 10:31:13
 * @Last Modified by:   liutingxie
 * @Last Modified time: 2017-11-06 15:41:59
 */
class Storm_exportImport {

	function __construct() {
		add_action('admin_menu', array($this, 'admin_init'));
	}

	function admin_init() {
		register_importer('storm-import', __('Storm Import', 'StormSlider'), __('Import you export slider or preset transition.', 'StormSlider'), array($this, 'storm_register_import'));
	}

	function storm_register_import() {

		$this->import_header();

		$this->import_body();

		$this->import_footer();

	}



	//Display import page title
	function import_header() {
		echo '<div class="storm-wrap">';
		echo '<h3>' . __('Storm Import Page', 'StormSlider') .'</h3>';
	}

	//Display import page body
	function import_body() {
		if(!isset($_POST['storm-import'])) {
			$size = size_format(wp_max_upload_size());
?>

		<form action="<?php echo admin_url('admin.php?import=storm-import&importStep=2'); ?>" method="post" enctype="multipart/form-data">
			<div class="storm-wrap-container">
				<input type="hidden" name="storm-import" value="1">
				<input type="file" name="storm-import-file">
				<span class="storm-wrap-import-label"><?php  printf(__('Maximum size: %s', 'StormSlider'), $size); ?></span>
			</div>
			<br>
			<div class="storm-wrap-import-btn">
				<button class="button"><?php _e('Import', 'StormSlider'); ?></button>
			</div>
		</form>
<?php 	}

		if(isset($_POST['storm-import'])) {
			if(current_user_can('delete_plugins')) {
				if($_REQUEST['importStep'] == 2) {
					if($_FILES['storm-import-file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['storm-import-file']['tmp_name'])) {
						$data = file_get_contents($_FILES['storm-import-file']['tmp_name']);
						$this->storm_import_data(json_decode($data, true));
					}
				}
			}
			else {
				_e( 'You do not have enough permission to import sliders', 'StormSlider' );
			}
		}

	}

	//Display import page footer
	function import_footer() {
		echo '</div>';
	}


	function storm_import_data($data) {

		if(isset($data['stormSliders'])) {
			foreach ($data['stormSliders'] as $key => $value) {
				$sliderId = Storm_Sliders::importSlider($value['name'], $value['data']);

				if($sliderId) {
					echo sprintf('Slider "%s" created successfully.', $sliderId) . "<br /></br />";
				}
			}
		}

		if(isset($data['stormSliderTransitions'])) {
			$transitionData['transition'] = $data['stormSliderTransitions'];
			$transitionId = Storm_Sliders::addOption('preset_effect', $transitionData);

			if($transitionId) {
				echo 'Preset transition import successfully.';
			}
		}

		echo 'All data successfully import.<br /><br />';

		printf('<a href="%s" class="button">%s</a>', admin_url( 'admin.php?page=stormslider' ), __('Back to slider page', 'StormSlider'));

		return true;
	}
}

global $storm_import;
if(is_null($storm_import)) $storm_import = new Storm_exportImport();