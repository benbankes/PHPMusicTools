<?php
namespace ianring;
require_once 'PMTObject.php';
require_once '../Instrument.php';

class InstrumentPiano extends Instrument
{

	public $ranges = array(
		'normal' => array(
			'min' => new Pitch('A', 0, 1),
			'max' => new Pitch('C', 0, 8)
		)
	);

	public $transpose = 0;
	public $family = 'percussion';
	public $otherNames = array('Pianoforte');
	public $abbreviation = 'Piano';


}
