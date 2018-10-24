<?php
/**
 * @Author: liutingxie
 * @Date:   2016-08-07 15:21:30
 * @Last Modified by:   liutingxie
 * @Last Modified time: 2017-12-15 09:39:45
 */

$sliders = Storm_Sliders::getSliders();

$count = Storm_Sliders::count();

$per_page = Storm_Sliders::limit();

$presetData = Storm_Sliders::getOption('preset_effect')['option_value'];

$maxPage = ceil($count / $per_page);
$maxPage = $maxPage ? $maxPage : 1;

$search_value = isset($_GET['search']) ? esc_html(stripslashes($_GET['search'])) : '';

$paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
?>

<script type="text/template" id="storm-importexport-template">
	<div class="storm-importexportContainer">
		<div class="storm-remove-importexport">
			<span><?php _e('Import', 'StormSlider'); ?></span>
			<span class="dashicons dashicons-no-alt storm-remove-importexport-btn"></span>
		</div>
		<form action="<?php echo admin_url('admin.php?import=storm-import&importStep=2'); ?>" method="post" enctype="multipart/form-data">
			<table class="storm-improtContainer">
				<tbody>
					<tr>
						<td>
							<input type="hidden" name="storm-import">
							<input type="file" name="storm-import-file">
							<button class="storm-import-btn btn"><?php _e('Import', 'StormSlider'); ?></button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td><span><?php _e('To import sliders select your Export file that you downloaded before then click import button.', 'StormSlider'); ?></span></td>
					</tr>
				</tfoot>
			</table>
		</form>
		<div class="storm-lable-export"><span><?php _e('Export', 'StormSlider'); ?></span></div>
		<form id="storm-export-form" method="post">
			<div class="storm-export-form-container">
				<table class="storm-exportPresetTransitionContainer">
					<h3><?php _e('Export preset transition', 'StormSlider'); ?></h3>
					<thead>
						<tr>
							<th scope="col"><input type="checkbox" class="storm-preset-check-all"></th>
							<th scope="col"><?php _e('Id', 'StormSlider'); ?></th>
							<th scope="col"><?php _e('Name', 'StormSlider'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($presetData['transition'])) : ?>
							<?php foreach ($presetData['transition'] as $key => $value) : ?>
									<tr>
										<td><input type="checkbox" name="storm-transition-arr[]" class="storm-preset-check" value="<?php echo $key; ?>"></td>
										<td data-th="<?php _e('Id', 'StormSlider'); ?>"><?php echo $key + 1; ?></td>
										<td data-th="<?php _e('Name', 'StormSlider'); ?>"><?php echo $value['name']; ?></td>
									</tr>
							<?php endforeach; ?>
						<?php endif; ?>
				</table>
				<table class="storm-exportSliderContainer">
					<h3><?php _e('Export preset transition', 'StormSlider'); ?></h3>
					<thead>
						<tr>
							<th scope="col"><input type="checkbox" class="storm-slider-check-all"></th>
							<th scope="col"><?php _e('Id', 'StormSlider'); ?></th>
							<th scope="col"><?php _e('Name', 'StormSlider'); ?></th>
							<th scope="col"><?php _e('Slides', 'StormSlider'); ?></th>
							<th scope="col"><?php _e('Create', 'StormSlider'); ?></th>
							<th scope="col"><?php _e('Modify', 'StormSlider'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($sliders)) : ?>
							<?php foreach ($sliders as $key => $value) : ?>
								<tr>
									<td><input type="checkbox" name="storm-slider-arr[]" class="storm-slider-check" value="<?php echo $value['id']; ?>"></td>
									<td data-th="<?php _e('Id', 'StormSlider'); ?>"><?php echo $value['id']; ?></td>
									<td data-th="<?php _e('Name', 'StormSlider'); ?>"><?php echo $value['name']; ?></td>
									<td data-th="<?php _e('Slides', 'StormSlider'); ?>"><?php echo isset($value['data']['layers']) ? count($value['data']['layers']) : 0; ?></td>
									<td data-th="<?php _e('Create', 'StormSlider'); ?>"><?php echo $value['date_create']; ?></td>
									<td data-th="<?php _e('Modify', 'StormSlider'); ?>"><?php echo $value['date_modified']; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="storm-export-btn">
				<input type="hidden" name="storm_export">
				<span><?php _e('Downloads an export file that contains youu selected preset transition and sliders', 'StormSlider'); ?></span>
				<button class="storm-export-presetslider-btn btn"><?php _e('Export', 'StormSlider'); ?></button>
			</div>
		</form>
	</div>
	<div class="storm-importexport-overlay"></div>
</script>

<div id="storm-screen-options" class="metabox-prefs">
	<div id="screen-options-wrap" class="hidden">
		<form id="storm-screen-options-form" method="post" novalidate>
			<h5><?php _e('Show on screen', 'StormSlider') ?></h5>

			<?php _e('Pagination', 'StormSlider') ?> <input type="number" name="numberOfSliders" min="8" step="4" value="<?php echo $per_page ?>"> <?php _e('sliders per page', 'StormSlider') ?>
			<button class="button"><?php _e('Apply', 'StormSlider') ?></button>
		</form>
	</div>
	<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
		<button type="button" id="show-settings-link" class="button show-settings" aria-controls="screen-options-wrap" aria-expanded="false"><?php _e('Screen Options', 'StormSlider') ?></button>
	</div>
</div>

<div class="wrap">
	<?php $image_path = plugins_url('image/', __FILE__); ?>
 	<div class="storm-slider">
	  <div class="slider_list">
	    <form method="post" class="admin_form" name="admin_form" id="admin_form" action="admin.php?page=stormslider">
		    <span class="slider_icon"></span>
			<div class="tablenav top" style="width: 97%">
				<div class="storm-search-value">
					<label for="search-title">Name :</label>
		        	<input type="search" name="search-title" id="search-title" value="<?php echo $search_value; ?>">
		        </div>
		        <div class="storm-search-reset">
		          <a class="btn"><?php _e('Search', 'StormSlider'); ?></a>
		        </div>
				<div class="tablenav-pages">
					<span class="displayind-num"><?php echo $count; ?> Items</span>
					<?php
						if( $paged == 1 ) {
							$first_page = "first-page disabled";
							$prev_page = "prev-page disabled";
							$next_page = "next-page";
							$last_page = "last-page";
						}
						else if ( $paged == $maxPage ) {
							$first_page = "first-page";
							$prev_page = "prev-page";
							$next_page = "next-page disabled";
							$last_page = "last-page disabled";
						}
					?>
					<span class="pagination-links">
					<a class="<?php echo $first_page; ?>" title="Go to the first page" href="admin.php?page=stormslider<?php echo $search_value == '' ? '' : '&search=' . $search_value; ?>">&laquo;</a>
					<a class="<?php echo $prev_page; ?>" title="Go to the previous page" href="admin.php?page=stormslider<?php echo $search_value == '' ? '' : '&search=' . $search_value; ?>&amp;paged=<?php echo $paged == 1 ? $paged : $paged - 1; ?>">&lsaquo;</a>
					<span class="paging-input">
						<span class="total-pages"><?php echo $paged; ?></span>
						of
						<span class="total-pages"><?php echo $maxPage; ?></span>
					</span>
					<a class="<?php echo $next_page; ?>" title="Go to the next page" href="admin.php?page=stormslider<?php echo $search_value == '' ? '' : '&search=' . $search_value; ?>&amp;paged=<?php echo $paged == $maxPage ? $maxPage : $paged + 1; ?>">&rsaquo;</a>
					<a class="<?php echo $last_page; ?>" title="Go to the last page" href="admin.php?page=stormslider<?php echo $search_value == '' ? '' : '&search=' . $search_value; ?>&amp;paged=<?php echo $maxPage; ?>">&raquo;</a>
					</span>
				</div>
			</div>

	        <table class="storm-list-table">
	            <thead>
			      <tr>
			        <th scope="col"><?php _e('Id', 'StormSlider'); ?></th>
			        <th scope="col"><?php _e('Name', 'StormSlider'); ?></th>
			        <th scope="col"><?php _e('Slides', 'StormSlider'); ?></th>
			        <th scope="col"><?php _e('Shortcode', 'StormSlider'); ?></th>
			        <th scope="col"><?php _e('Delete', 'StormSlider'); ?></th>
			      </tr>
	            </thead>
		        <tbody>
		        	<?php if(!empty($sliders)) : ?>
			          	<?php foreach ($sliders as $key => $data) : ?>
				          	<tr>
				              	<td data-th="<?php _e('Id', 'StormSlider'); ?>"><?php echo $data['id']; ?></td>
				              	<td data-th="<?php _e('Name', 'StormSlider'); ?>">
				              	<a href="admin.php?page=stormslider&amp;action=edit&amp;id=<?php echo esc_html($data['id']); ?>"><?php echo esc_html(stripslashes($data['data']['slider']['name'])); ?></a>
				              	</td>
				              	<td data-th="<?php _e('Slides', 'StormSlider'); ?>"><?php echo isset($data['data']['layers']) ? count($data['data']['layers']) : 0; ?></td>
				              	<td data-th="<?php _e('Shortcode', 'StormSlider'); ?>">[stormslider id="<?php echo $data['id']; ?>"]</td>
				              	<td data-th="<?php _e('Delete', 'StormSlider'); ?>"><a class="action-delete" href="admin.php?page=stormslider&amp;action=remove&amp;id=<?php  echo esc_html($data['id']); ?>"><?php _e('Delete', 'StormSlider'); ?></a></td>
				          	</tr>
			          	<?php endforeach; ?>
			        <?php else : ?>
			        	<tr>
			        		<td colspan="5" class="storm-empty-slider"><span><?php _e('Sorry, there is no slider here.', 'StormSlider'); ?></span></td>
			        	</tr>
			        <?php endif; ?>

		        </tbody>
	        </table>
 	    </form>
	    <div class="storm-action-btn-list">
	        <div class="storm-action-btn-addslider">
		        <a href="#" onclick="window.location.href='admin.php?page=stormslider&amp;action=add'" class="add_new btn"><?php _e('ADD NEW SLIDER', 'StormSlider'); ?></a>
		    </div>
		    <div class="storm-action-btn-importexport">
		    	<button class="import_export_btn btn"><?php _e('IMPORT & EXPORT', 'StormSlider'); ?></button>
		    </div>
        </div>
	  </div>
	</div>
</div>
