<?php

require_once('Visualization.php');


/**
 * renders a piano keyboard, with notes indicated
 * @param  [type] $notes [description]
 * @return [type]        [description]
 */
class PianoKeyboard extends Visualization {

	public function __construct($pitches) {
		$this->pitches = $pitches;
	}

	function render() {

		$notes = $this->pitchesToNotes($this->pitches);

		// define an array with all the notes from 0 to 88 and their x y
		$keys = array(
			"ffffff" => array(
				0 => array("x" => 3),
				2 => array("x" => 13),
				3 => array("x" => 23),
				5 => array("x" => 33),
				7 => array("x" => 43),
				8 => array("x" => 53),
				10 => array("x" => 63),
				12 => array("x" => 73),
				14 => array("x" => 83),
				15 => array("x" => 93),
				17 => array("x" => 103),
				19 => array("x" => 113),
				20 => array("x" => 123),
				22 => array("x" => 133),
				24 => array("x" => 143),
				26 => array("x" => 153),
				27 => array("x" => 163),
				29 => array("x" => 173),
				31 => array("x" => 183),
				32 => array("x" => 193),
				34 => array("x" => 203),
				36 => array("x" => 213),
				38 => array("x" => 223),
				39 => array("x" => 233),
				41 => array("x" => 243),
				43 => array("x" => 253),
				44 => array("x" => 263),
				46 => array("x" => 273),
				48 => array("x" => 283),
				50 => array("x" => 293),
				51 => array("x" => 303),
				53 => array("x" => 313),
				55 => array("x" => 323),
				56 => array("x" => 333),
				58 => array("x" => 343),
				60 => array("x" => 353),
				62 => array("x" => 363),
				63 => array("x" => 373),
				65 => array("x" => 383),
				67 => array("x" => 393),
				68 => array("x" => 403),
				70 => array("x" => 413),
				72 => array("x" => 423),
				74 => array("x" => 433),
				75 => array("x" => 443),
				77 => array("x" => 453),
				79 => array("x" => 463),
				80 => array("x" => 473),
				82 => array("x" => 483),
				84 => array("x" => 493),
				86 => array("x" => 503),
				87 => array("x" => 513),
			),
			"000000" => array(
				1 => array("x" => 10.5),
				4 => array("x" => 30.5),
				6 => array("x" => 40.5),
				9 => array("x" => 60.5),
				11 => array("x" => 70.5),
				13 => array("x" => 80.5),
				16 => array("x" => 100.5),
				18 => array("x" => 110.5),		
				21 => array("x" => 130.5),
				23 => array("x" => 140.5),
				25 => array("x" => 150.5),
				28 => array("x" => 170.5),
				30 => array("x" => 180.5),
				33 => array("x" => 200.5),
				35 => array("x" => 210.5),
				37 => array("x" => 220.5),
				40 => array("x" => 240.5),
				42 => array("x" => 250.5),
				45 => array("x" => 270.5),
				47 => array("x" => 280.5),
				49 => array("x" => 290.5),
				52 => array("x" => 310.5),
				54 => array("x" => 320.5),
				57 => array("x" => 340.5),
				59 => array("x" => 350.5),
				61 => array("x" => 360.5),
				64 => array("x" => 380.5),
				66 => array("x" => 390.5),
				69 => array("x" => 410.5),
				71 => array("x" => 420.5),
				73 => array("x" => 430.5),
				76 => array("x" => 450.5),
				78 => array("x" => 460.5),
				81 => array("x" => 480.5),
				83 => array("x" => 490.5),
				85 => array("x" => 500.5),
			)
		);

		$output = '<svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="520px" height="100" viewBox="0 0 530 100" >';
		foreach(array('ffffff', '000000') as $colour) {
			foreach($keys[$colour] as $index => $key) {
				$height = ($colour == 'ffffff') ? 50 : 25;
				$width = ($colour == 'ffffff') ? 10 : 5;
				$stroke = ($colour == 'ffffff') ? '888888' : '000000';
				$fill = in_array($index, $notes) ? 'ff0000' : $colour;
				$output .= '<rect x="'.$key['x'].'" y="20" height="'.$height.'" width="'.$width.'" style="stroke:#'.$stroke.'; fill: #'.$fill.'"></rect>';
			}

		}
		$output .= '</svg>';

		return $output;
	}

	private function pitchesToNotes() {
		$output = array();
		foreach($this->pitches as $pitch) {
			$output[] = $pitch->toNoteNumber() + 39;
		}
		return $output;
	}

}

// for($i=0;$i<88;$i++) {
// 	echo $i;
// 	render(array($i));
// 	echo '<br/>';
// }


