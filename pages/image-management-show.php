<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_Lrisg_display']) && $_POST['frm_Lrisg_display'] == 'yes')
{
	$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$Lrisg_success = '';
	$Lrisg_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_LRISG_TABLE."
		WHERE `Lrisg_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'left-right-image-slideshow-gallery'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('Lrisg_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_LRISG_TABLE."`
					WHERE `Lrisg_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$Lrisg_success_msg = TRUE;
			$Lrisg_success = __('Selected record was successfully deleted.', 'left-right-image-slideshow-gallery');
		}
	}
	
	if ($Lrisg_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $Lrisg_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Left right image slideshow gallery', 'left-right-image-slideshow-gallery'); ?>
	<a class="add-new-h2" href="<?php echo WP_LRISG_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'left-right-image-slideshow-gallery'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_LRISG_TABLE."` order by Lrisg_type, Lrisg_order";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_Lrisg_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Reference/Title', 'left-right-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('Group', 'left-right-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Image', 'left-right-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('URL', 'left-right-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Order', 'left-right-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Display', 'left-right-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('Target', 'left-right-image-slideshow-gallery'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Reference/Title', 'left-right-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('Group', 'left-right-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Image', 'left-right-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('URL', 'left-right-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Order', 'left-right-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Display', 'left-right-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('Target', 'left-right-image-slideshow-gallery'); ?></th>
          </tr>
        </tfoot>
		<tbody>
		<?php 
		$i = 0;
		if(count($myData) > 0 )
		{
			foreach ($myData as $data)
			{
				?>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td>
					<strong><?php echo esc_html(stripslashes($data['Lrisg_title'])); ?></strong>
					<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_LRISG_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['Lrisg_id']; ?>"><?php _e('Edit', 'left-right-image-slideshow-gallery'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:Lrisg_delete('<?php echo $data['Lrisg_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'left-right-image-slideshow-gallery'); ?></a></span> 
					</div>
					</td>
					<td><?php echo esc_html(stripslashes($data['Lrisg_type'])); ?></td>
					<td><a href="<?php echo esc_html($data['Lrisg_path']); ?>" target="_blank"><img src="<?php echo WP_LRISG_PLUGIN_URL; ?>/inc/image-icon.png"  /></a></td>
					<td><a href="<?php echo esc_html($data['Lrisg_link']); ?>" target="_blank"><img src="<?php echo WP_LRISG_PLUGIN_URL; ?>/inc/link-icon.gif"  /></a></td>
					<td><?php echo esc_html(stripslashes($data['Lrisg_order'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['Lrisg_status'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['Lrisg_target'])); ?></td>
				</tr>
				<?php 
				$i = $i+1; 
			}
		}
		else
		{
			?><tr><td colspan="7" align="center"><?php _e('No records available', 'left-right-image-slideshow-gallery'); ?></td></tr><?php 
		}
		?>
		</tbody>
        </table>
		<?php wp_nonce_field('Lrisg_form_show'); ?>
		<input type="hidden" name="frm_Lrisg_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
		  <a href="<?php echo WP_LRISG_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'left-right-image-slideshow-gallery'); ?>" /></a>
		  <a href="<?php echo WP_LRISG_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Widget setting', 'left-right-image-slideshow-gallery'); ?>" /></a>
		  <a target="_blank" href="<?php echo WP_LRISG_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'left-right-image-slideshow-gallery'); ?>" /></a>
		  <a target="_blank" href="<?php echo WP_LRISG_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'left-right-image-slideshow-gallery'); ?>" /></a>
	  </div>
	</div>
</div>