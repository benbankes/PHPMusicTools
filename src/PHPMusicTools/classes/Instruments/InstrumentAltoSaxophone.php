<?php
namespace ianring;
require_once 'PMTObject.php';
require_once '../Instrument.php';

class InstrumentAltoSaxophone extends Instrument
{

	public $ranges = array(
		'normal' => array(
			'min' => new Pitch('D', -1, 3),
			'max' => new Pitch('A', 0, 5)
		)
	);

	public $transpose = 8;
	public $family = 'woodwind';
	public $otherNames = array(
		'E flat Alto Saxophone',
		'E flat Alto Sax',
		'Alto Sax',
	);
	public $abbreviation = 'Alto Sax';

}
