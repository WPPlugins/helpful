<?php
/** 
 * Tab: System Options
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

global $helpful;

if( $helpful['tab'] == 'system' ) : 

?>

<h3><?php _e('System', 'helpful') ?></h3>

<p><?php _e('Hier lässt sich das Plugin vollständig zurücksetzen. Das ist der Inhalt der Datenbank-Tabelle, die einzelnen Meta-Felder der Beiträge und alle vorgenommenen Einstellungen, mit Ausnahme der Design-Einstellungen (PRO). <b class="danger">Dieser Vorgang lässt sich nicht rückgängig machen! Gelöscht ist gelöscht!</b>', 'helpful'); ?></p>

<hr />

<form method="post" action="options.php">

	<?php settings_fields( 'helpful-system-settings-group' ); ?>
	<?php do_settings_sections( 'helpful-system-settings-group' ); ?>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Plugin zurücksetzen', 'helpful'); ?></th>
			<td>
				<?php $checked = ( get_option('helpful_uninstall') ? 'checked="checked"' : '' ); ?>				
				<label><input type="checkbox" name="helpful_uninstall" <?php echo $checked; ?> /></label>
			</td>
		</tr>
	</table>
	
	<hr />
		
	<?php do_action( 'helpful_system_settings' ); ?>

	<?php submit_button(); ?>

</form>

<?php endif; ?>
