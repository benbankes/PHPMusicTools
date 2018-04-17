<?php

require_once('Visualization.php');


/**
 * renders a piano keyboard, with notes indicated
 * @param  [type] $notes [description]
 * @return [type]        [description]
 */
class SaxophoneFingering extends Visualization {

	static public $types = array(
		'soprano' => 1,
		'alto' => 3,
		'tenor' => 6,
		'baritone' => 9,
	);

	public function __construct($pitch, $transposition = 'concert') {
		$this->pitch = $pitch;
		$this->transposition = 'concert';
	}

	public function getFingering() {
		$f = array(
			-2 => array('lh1','lh2','lh3', 'rh1','rh2','rh3', 'rhpinky2','lhpinky4'), 
			-1 => array('lh1','lh2','lh3', 'rh1','rh2','rh3', 'rhpinky2','lhpinky2'), 
			0 => array('lh1','lh2','lh3', 'rh1','rh2','rh3', 'rhpinky2'), // C
			1 => array('lh1','lh2','lh3', 'rh1','rh2','rh3', 'rhpinky2', 'lhpinky3'),
			2 => array('lh1','lh2','lh3', 'rh1','rh2','rh3'), // D
			3 => array('lh1','lh2','lh3', 'rh1','rh2','rh3', 'rhpinky1'), 
			4 => array('lh1','lh2','lh3', 'rh1','rh2'), 
			5 => array('lh1','lh2','lh3', 'rh1'), 
			6 => array('lh1','lh2','lh3', 'rh2'), 
			7 => array('lh1','lh2','lh3'), 
			8 => array('lh1','lh2','lh3','lhpinky1'), 
			9 => array('lh1','lh2'), 
			10 => array('lh1', 'rh1'),
			11 => array('lh1'),
			12 => array('lh2'),
			13 => array(),
			14 => array('octave','lh1','lh2','lh3','rh1','rh2','rh3'),
			15 => array('octave','lh1','lh2','lh3','rh1','rh2','rh3','rhpinky1'),
			16 => array('octave','lh1','lh2','lh3','rh1','rh2'),
			17 => array('octave','lh1','lh2','lh3','rh1'),
			18 => array('octave','lh1','lh2','lh3','rh2'),
			19 => array('octave','lh1','lh2','lh3'),
			20 => array('octave','lh1','lh2','lh3','lhpinky1'),
			21 => array('octave','lh1','lh2'),
			22 => array('octave','lh1','rh1'),
			23 => array('octave','lh1'),
			24 => array('octave','lh2'),
			25 => array('octave'),
			26 => array('octave','lpalm2'),
			27 => array('octave','lpalm2','lpalm1'),
			28 => array('octave','lpalm2','lpalm1','rhpalm1'),
			29 => array('octave','lpalm3', 'lpalm2','lpalm1','rhpalm1'),
		);
		if (array_key_exists($this->pitch, $f)) {
			return $f[$this->pitch];
		}
		return array();
	}

	function render() {

    $stroke = 'black';
    $fill = 'white';
    $strokeWidth = '4pt';

		$output  = '
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="80" height="160" viewBox="0 0 473 1437" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">
    <g id="Layer1">';

    $keys = array(
    	'octave' => '<ellipse cx="73" cy="99" rx="41" ry="97" style />',

	    'lh1' => '<circle cx="225" cy="180" r="76" style />',
	    'lh2' => '<circle cx="225" cy="387" r="76" style />',
	    'lh3' => '<circle cx="225" cy="558" r="76" style />',
	    'lh4' => '<circle cx="290" cy="290" r="40" style />',

    	'lpalm1' => '<ellipse cx="399" cy="155" rx="29" ry="75" style />',
    	'lpalm2' => '<ellipse cx="452" cy="284" rx="29" ry="75" style />',
    	'lpalm3' => '<ellipse cx="368" cy="322" rx="29" ry="75" style />',

	    'lhpinky1' => '<path 
        	d="
        		M310,642
        		C310,642
        		333,573
        		380,572
        		C428,571
        		460,641
        		460,641
        		L310,642
        		Z
        	" style />',

        'lhpinky2' => '<path 
        	d="
        		M365,655
        		L308,655
        		C308,655	301,667		301,690
        		C302,714	310,727		310,727
        		L368,725
        		L365,658
        		Z
        	" style />',
        'lhpinky3' => '<path 
        	d="
        		M384,655
        		L463,655
        		C463,655	470,667		470,690
        		C470,713	460,725		460,725
        		L384,725
        		L384,656
        		Z
        	" style />',
        'lhpinky4' => '<path 
        	d="
        		M317,740
        		L454,740
        		C454,740	437,797		382,796
        		C327,796	317,740		317,740
        		Z
        	" style />',

	    'rh1' => '<circle cx="225" cy="898" r="75" style />',
	    'rh2' => '<circle cx="225" cy="1086" r="75" style />',
	    'rh3' => '<circle cx="225" cy="1274" r="75" style />',
	    'rh4' => '<circle cx="140" cy="1170" r="40" style />',

	    'rhpalm1' => '<path 
        	d="
        		M79,707
        		C79,699 		73,693 		65,693
        		L37,693
        		C29,693 		23,699 		23,707
        		L23,795
        		C23,803 		29,809 		37,809
        		L65,809		
        		C73,809 		79,803 		79,795
        		L79,707
        		Z
        	" style />',
	    'rhpalm2' => '<path 
        	d="
        		M78,837
        		C78,830 	72,824		64,824
        		L37,824
        		C30,824 	24,830 		24,837
        		L24,925
        		C24,932 	30,938 		37,938
        		L64,938
        		C72,938 	78,932 		78,925
        		L78,837
        		Z
        	" style />',
        'rhpalm3' => '<path 
        	d="
        		M79,968
        		C79,961 		73,955 		66,955
        		L39,955
        		C31,955 		25,961 		25,968
        		L25,1056
        		C25,1064 		31,1070 	39,1070
        		L66,1070
        		C73,1070 		79,1064 	79,1056
        		L79,968
        		Z
        	" style />',

        'rhpinky1' => '<path 
        	d="
        		M4,1351
        		C4,1351			9,1284 		74,1283
        		C139,1283 		141,1351 	141,1351
        		L4,1351
        		Z
        	" style />',
        'rhpinky2' => '<path 
        	d="
        		M4,1371        		
        		C4,1371			11,1435 	70,1435
        		C129,1434 		141,1371 	141,1371
        		L4,1371        		
        		Z
        	" style />',

	);

	$f = $this->getFingering();

	foreach($keys as $key => $svg) {

		$fill = in_array($key, $f) ? 'red' : 'white';
	    $style = 'style="fill:'.$fill.';stroke:'.$stroke.';stroke-width:'.$strokeWidth.';"';

		$svg = str_replace('style', $style, $svg);
		$output .= $svg;
	}

 $output .= '       
    </g>
</svg>';

		return $output;


	}
}