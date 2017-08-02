<?php
/**
 * Frontent Template
 *
 * @author  Devhats
 * @version 1.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// Get helpful helpers
$helpful = apply_filters( 'helpful_helpers', null );

?>
	
<?php if( $helpful['exists'] ) : if( !$helpful['exists-hide'] ) : ?>

<div class="<?php echo $helpful['class']; ?>">
	
	<div class="helpful-exists"><?php echo $helpful['exists-text']; ?></div>
	
	<?php echo $helpful['credits']; ?>
	
</div>
	
<?php endif; else : ?>

<div class="<?php echo $helpful['class']; ?>">
		
	<div class="helpful-heading"><?php echo $helpful['heading']; ?></div>
	
	<div class="helpful-content"><?php echo $helpful['content']; ?></div>
	
	<div class="helpful-controls">
		<?php echo $helpful['button-pro']; ?>
		<?php echo $helpful['button-contra']; ?>
	</div>
	
	<?php echo $helpful['credits']; ?>
	
</div>
	
<?php endif; ?>