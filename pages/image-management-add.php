<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$Lrisg_errors = array();
$Lrisg_success = '';
$Lrisg_error_found = FALSE;

// Preset the form fields
$form = array(
	'Lrisg_path' => '',
	'Lrisg_link' => '',
	'Lrisg_target' => '',
	'Lrisg_title' => '',
	'Lrisg_order' => '',
	'Lrisg_status' => '',
	'Lrisg_type' => ''
);

// Form submitted, check the data
if (isset($_POST['Lrisg_form_submit']) && $_POST['Lrisg_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('Lrisg_form_add');
	
	$form['Lrisg_path'] = isset($_POST['Lrisg_path']) ? esc_url_raw($_POST['Lrisg_path']) : '';
	if ($form['Lrisg_path'] == '')
	{
		$Lrisg_errors[] = __('Please enter the image path.', 'left-right-image-slideshow-gallery');
		$Lrisg_error_found = TRUE;
	}

	$form['Lrisg_link'] = isset($_POST['Lrisg_link']) ? esc_url_raw($_POST['Lrisg_link']) : '';
	if ($form['Lrisg_link'] == '')
	{
		$Lrisg_errors[] = __('Please enter the target link.', 'left-right-image-slideshow-gallery');
		$Lrisg_error_found = TRUE;
	}
	
	$form['Lrisg_target'] = isset($_POST['Lrisg_target']) ? sanitize_text_field($_POST['Lrisg_target']) : '';
	if($form['Lrisg_target'] != "_blank" && $form['Lrisg_target'] != "_parent" && $form['Lrisg_target'] != "_self" && $form['Lrisg_target'] != "_new")
	{
		$form['Lrisg_target'] = "_blank";
	}
	
	$form['Lrisg_title'] = isset($_POST['Lrisg_title']) ? sanitize_text_field($_POST['Lrisg_title']) : '';
	
	$form['Lrisg_order'] = isset($_POST['Lrisg_order']) ? intval($_POST['Lrisg_order']) : '';
	
	$form['Lrisg_status'] = isset($_POST['Lrisg_status']) ? sanitize_text_field($_POST['Lrisg_status']) : '';
	if($form['Lrisg_status'] != "YES" && $form['Lrisg_status'] != "NO")
	{
		$form['Lrisg_status'] = "YES";
	}
	
	$form['Lrisg_type'] = isset($_POST['Lrisg_type']) ? sanitize_text_field($_POST['Lrisg_type']) : '';

	//	No errors found, we can add this Group to the table
	if ($Lrisg_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_LRISG_TABLE."`
			(`Lrisg_path`, `Lrisg_link`, `Lrisg_target`, `Lrisg_title`, `Lrisg_order`, `Lrisg_status`, `Lrisg_type`)
			VALUES(%s, %s, %s, %s, %d, %s, %s)",
			array($form['Lrisg_path'], $form['Lrisg_link'], $form['Lrisg_target'], $form['Lrisg_title'], $form['Lrisg_order'], $form['Lrisg_status'], $form['Lrisg_type'])
		);
		$wpdb->query($sql);
		
		$Lrisg_success = __('New image details was successfully added.', 'left-right-image-slideshow-gallery');
		
		// Reset the form fields
		$form = array(
			'Lrisg_path' => '',
			'Lrisg_link' => '',
			'Lrisg_target' => '',
			'Lrisg_title' => '',
			'Lrisg_order' => '',
			'Lrisg_status' => '',
			'Lrisg_type' => ''
		);
	}
}

if ($Lrisg_error_found == TRUE && isset($Lrisg_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $Lrisg_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($Lrisg_error_found == FALSE && strlen($Lrisg_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $Lrisg_success; ?> 
		<a href="<?php echo WP_LRISG_ADMIN_URL; ?>"><?php _e('Click here', 'left-right-image-slideshow-gallery'); ?></a> <?php _e('to view the details', 'left-right-image-slideshow-gallery'); ?></strong></p>
	  </div>
	  <?php
	}
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var img_imageurl = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#Lrisg_path').val(img_imageurl);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery'); // jQuery
wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Left right image slideshow gallery', 'left-right-image-slideshow-gallery'); ?></h2>
	<form name="Lrisg_form" method="post" action="#" onsubmit="return Lrisg_submit()"  >
      <h3><?php _e('Add new image details', 'left-right-image-slideshow-gallery'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path (URL)', 'left-right-image-slideshow-gallery'); ?></label>
      <input name="Lrisg_path" type="text" id="Lrisg_path" value="" size="90" />
	  <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
      <p><?php _e('Where is the picture located on the internet', 'left-right-image-slideshow-gallery'); ?> (ex: http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x167/250x167_2.jpg)</p>
      <label for="tag-link"><?php _e('Enter target link', 'left-right-image-slideshow-gallery'); ?></label>
      <input name="Lrisg_link" type="text" id="Lrisg_link" value="#" size="90" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them', 'left-right-image-slideshow-gallery'); ?></p>
      <label for="tag-target"><?php _e('Enter target option', 'left-right-image-slideshow-gallery'); ?></label>
      <select name="Lrisg_target" id="Lrisg_target">
        <option value='_blank'>_blank</option>
        <option value='_parent'>_parent</option>
        <option value='_self'>_self</option>
        <option value='_new'>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'left-right-image-slideshow-gallery'); ?></p>
      <label for="tag-title"><?php _e('Enter image reference', 'left-right-image-slideshow-gallery'); ?></label>
      <input name="Lrisg_title" type="text" id="Lrisg_title" value="" size="90" />
      <p><?php _e('Enter image reference. This is only for reference.', 'left-right-image-slideshow-gallery'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select gallery group', 'left-right-image-slideshow-gallery'); ?></label>
      <select name="Lrisg_type" id="Lrisg_type">
        <option value='GROUP1'>Group1</option>
        <option value='GROUP2'>Group2</option>
        <option value='GROUP3'>Group3</option>
        <option value='GROUP4'>Group4</option>
        <option value='GROUP5'>Group5</option>
        <option value='GROUP6'>Group6</option>
        <option value='GROUP7'>Group7</option>
        <option value='GROUP8'>Group8</option>
        <option value='GROUP9'>Group9</option>
        <option value='GROUP0'>Group0</option>
		<option value='Widget'>Widget</option>
		<option value='Sample'>Sample</option>
      </select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'left-right-image-slideshow-gallery'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'left-right-image-slideshow-gallery'); ?></label>
      <select name="Lrisg_status" id="Lrisg_status">
        <option value='YES'>Yes</option>
        <option value='NO'>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'left-right-image-slideshow-gallery'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'left-right-image-slideshow-gallery'); ?></label>
      <input name="Lrisg_order" type="text" id="Lrisg_order" size="10" value="1" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'left-right-image-slideshow-gallery'); ?></p>
      <input name="Lrisg_id" id="Lrisg_id" type="hidden" value="">
      <input type="hidden" name="Lrisg_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="<?php _e('Submit', 'left-right-image-slideshow-gallery'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="Lrisg_redirect()" value="<?php _e('Cancel', 'left-right-image-slideshow-gallery'); ?>" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="Lrisg_help()" value="<?php _e('Help', 'left-right-image-slideshow-gallery'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('Lrisg_form_add'); ?>
    </form>
</div>
</div>