<?php
/**
 * Helpful Simple Config File
 *
 * @author  Devhats
 * @version 1.0
 */

// Set global variable
global $helpful;
		
// Current tab
$helpful['tab'] = ( $_GET[ 'tab' ] ? $_GET[ 'tab' ] : 'general' );


// Defaults

$helpful['strings'] = array(
	'heading' 		=> __('War dieser Beitrag hilfreich?','helpful'),
	'content' 		=> __('Teilen Sie uns mit, wenn Ihnen der Beitrag gefallen hat. Nur so können wir uns verbessern.','helpful'),
	'exists'		=> __('Sie haben bereits für diesen Beitrag abgestimmt.','helpful'),
	'success'		=> __('Vielen Dank dafür, dass Sie für unseren Beitrag abgestimmt haben.','helpful'),
	'error'			=> __('Leider ist ein Fehler aufgetreten.','helpful'),
	'button-pro'	=> __('Ja','helpful'),
	'button-contra' => __('Nein','helpful'),
	'column-pro'	=> __('Pro','helpful'),
	'column-contra'	=> __('Kontra','helpful'),
	'credits'		=> '<div class="helpful-credits">Powered by <a href="https://devhats.de" target="_blank" rel="nofollow">Devhats</a></div>'
);