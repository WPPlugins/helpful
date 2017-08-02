<?php
/** 
 * Tab: General Options
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

global $helpful;

if( $helpful['tab'] == 'general' ) : 

// wp_editor settings
$settings = array( 'teeny' => true, 'media_buttons' => false, 'textarea_rows' => 5 );

?>

<h3><?php _e('Allgemein', 'helpful') ?></h3>

<p><?php _e('Hier kannst du allgemeine Einstellungen vornehmen. Dazu zählen unter anderem das Ausblenden der Credits, die verschiedenen Texte und andere Dinge.<br />Mit <code>{pro}</code> kannst du die positiven und mit <code>{contra}</code> die negatigen Stimmen, in einem Editor hier auf der Seite, ausgeben.', 'helpful'); ?></p>

<hr />

<form method="post" action="options.php">

	<?php settings_fields( 'helpful-general-settings-group' ); ?>
	<?php do_settings_sections( 'helpful-general-settings-group' ); ?>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="helpful_heading"><?php _e('Überschrift', 'helpful'); ?></label></th>
			<td>
				<input type="text" id="helpful_heading" name="helpful_heading" class="regular-text" value="<?php echo esc_attr( get_option('helpful_heading') ); ?>"/>
				<p class="description"><?php _e('Gebe hier deine eigene Überschrift ein.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_content"><?php _e('Inhaltstext', 'helpful'); ?></label></th>
			<td>
				<?php wp_editor( get_option('helpful_content'), 'helpful_content', $settings ); ?>
				<p class="description"><?php _e('Gebe hier deinen eigenen Inhaltstext ein.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_exists"><?php _e('Bereits abgestimmt', 'helpful'); ?></label></th>
			<td>
				<?php wp_editor( get_option('helpful_exists'), 'helpful_exists', $settings ); ?>
				<p class="description"><?php _e('Dieser Text erschreint immer dann, wenn der jeweilige Benutzer bereits für den Beitrag abgestimmt hat.','helpful'); ?></p>
			</td>
		</tr>
	</table>
	
	<hr />

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="helpful_pro"><?php _e('Button (Pro)', 'helpful'); ?></label></th>
			<td>
				<input type="text" id="helpful_pro" name="helpful_pro" class="regular-text" value="<?php echo esc_attr( get_option('helpful_pro') ); ?>"/>
				<p class="description"><?php _e('Gebe hier deinen eigenen Text für den Pro-Button ein.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_contra"><?php _e('Button (Kontra)', 'helpful'); ?></label></th>
			<td>
				<input type="text" id="helpful_contra" name="helpful_contra" class="regular-text" value="<?php echo esc_attr( get_option('helpful_contra') ); ?>"/>
				<p class="description"><?php _e('Gebe hier deinen eigenen Text für den Kontra-Button ein.','helpful'); ?></p>
			</td>
		</tr>
	</table>
	
	<hr />

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="helpful_column_pro"><?php _e('Spalte (Pro)', 'helpful'); ?></label></th>
			<td>
				<input type="text" id="helpful_column_pro" name="helpful_column_pro" class="regular-text" value="<?php echo esc_attr( get_option('helpful_column_pro') ); ?>"/>
				<p class="description"><?php _e('Gebe hier deinen eigenen Text für die Pro-Spalte in Beitragslisten ein.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_column_contra"><?php _e('Spalte (Kontra)', 'helpful'); ?></label></th>
			<td>
				<input type="text" id="helpful_column_contra" name="helpful_column_contra" class="regular-text" value="<?php echo esc_attr( get_option('helpful_column_contra') ); ?>"/>
				<p class="description"><?php _e('Gebe hier deinen eigenen Text für die Kontra-Spalte in Beitragslisten ein.','helpful'); ?></p>
			</td>
		</tr>
	</table>
	
	<hr />
	
	<?php do_action( 'helpful_general_settings' ); ?>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="helpful_post_types"><?php _e('Beitragstypen', 'helpful'); ?></label></th>
			<td class="helpful-checkbox">
				
				<?php $post_types = get_post_types( array( 'public' => true ) ); ?>
				
				<?php if( $post_types ) : foreach( $post_types as $post_type ) : ?>
				
				<?php if( get_option('helpful_post_types') ) : ?>
				
					<?php if( in_array($post_type, get_option('helpful_post_types')) ) : ?>

						<label>
							<input type="checkbox" name="helpful_post_types[]" id="helpful_post_types[]" value="<?php echo $post_type; ?>" checked="checked"/> 
							<?php echo $post_type; ?>
						</label>

					<?php else : ?>

						<label>
							<input type="checkbox" name="helpful_post_types[]" id="helpful_post_types[]" value="<?php echo $post_type; ?>"/> 
							<?php echo $post_type; ?>
						</label>	

					<?php endif; ?>		
				
				<?php else : ?>

					<label>
						<input type="checkbox" name="helpful_post_types[]" id="helpful_post_types[]" value="<?php echo $post_type; ?>"/> 
						<?php echo $post_type; ?>
					</label>	
				
				<?php endif; ?>
				
				<?php endforeach; endif; ?>
				
				<p class="description"><?php _e('Wähle hier die Beitragstypen, unter denen Helpful erscheinen soll.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_exists_hide"><?php _e('Bereits abgestimmt', 'helpful'); ?></label></th>
			<td>
				<?php $checked = ( get_option('helpful_exists_hide') ? 'checked="checked"' : '' ); ?>				
				<label><input id="helpful_exists_hide" type="checkbox" name="helpful_exists_hide" <?php echo $checked; ?> /> <?php _e('ausblenden','helpful'); ?></label>
				<p class="description"><?php _e('Blende Helpful aus, wenn der Benutzer bereits abgestimmt hat.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_count_hide"><?php _e('Abstimmungen', 'helpful'); ?></label></th>
			<td>
				<?php $checked = ( get_option('helpful_count_hide') ? 'checked="checked"' : '' ); ?>				
				<label><input id="helpful_count_hide" type="checkbox" name="helpful_count_hide" <?php echo $checked; ?> /> <?php _e('ausblenden','helpful'); ?></label>
				<p class="description"><?php _e('Blende die Abstimmung aus.','helpful'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="helpful_credits"><?php _e('Credits', 'helpful'); ?></label></th>
			<td>
				<?php $checked = ( get_option('helpful_credits') ? 'checked="checked"' : '' ); ?>				
				<label><input id="helpful_credits" type="checkbox" name="helpful_credits" <?php echo $checked; ?> /> <?php _e('anzeigen','helpful'); ?></label>
				<p class="description"><?php _e('Unterstütze uns, in dem du deinen Besuchern zeigst, dass dieses Plugin von uns ist.','helpful'); ?></p>
			</td>
		</tr>
	</table>
	
	<hr />

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="helpful_widget"><?php _e('Dashboard Widget', 'helpful'); ?></label></th>
			<td>
				<?php $checked = ( get_option('helpful_widget') ? 'checked="checked"' : '' ); ?>				
				<label><input id="helpful_widget" type="checkbox" name="helpful_widget" <?php echo $checked; ?> /> <?php _e('ausblenden','helpful'); ?></label>
				<p class="description"><?php _e('Blendet das Dashboard Widget mit den beliebtesten/unbeliebtesten Beiträgen aus.','helpful'); ?></p>
			</td>
		</tr>
	</table>
	
	<hr />

	<?php submit_button(); ?>

</form>

<?php endif; ?>
