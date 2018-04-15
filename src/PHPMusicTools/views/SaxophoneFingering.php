<?php

require_once('Visualization.php');


/**
 * renders a piano keyboard, with notes indicated
 * @param  [type] $notes [description]
 * @return [type]        [description]
 */
class SaxophoneFingering extends Visualization {

	public function __construct($pitch, $transposition) {
		$this->pitch = $pitch;
		$this->transposition = 'Eflat';
	}

	function render() {
		
	}
}