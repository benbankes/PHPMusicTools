<?php
require_once(__DIR__.'/../../src/PHPMusicTools/classes/Part.php');


$happy_birthday = \ianring\Part::constructFromArray(
array(
	'measures' => array(
		array(
			'time' => array('', 1, 4),
			'layers' => array(
				array(
					'chords' => array(
						array(
							'notes' => array(
								array(
									'pitch' => array(
										'step' => 'G',
										'alter' => 0,
										'octave' => 4,
									),
									'duration' => 1,
								)
							)
						),
						array(
							'notes' => array(
								array(
									'pitch' => array(
										'step' => 'G',
										'alter' => 0,
										'octave' => 4,
									),
									'duration' => 1,
								)
							)
						)
					)
				)
			)			
		),
		array(
			'time' => array('', 3, 4),
			'layers' => array(
				array(
					'chords' => array(
						array(
							'notes' => array(
								array(
									'pitch' => array(
										'step' => 'A',
										'alter' => 0,
										'octave' => 4,
									),
									'duration' => 2,
								)
							)
						),
						array(
							'notes' => array(
								array(
									'pitch' => array(
										'step' => 'G',
										'alter' => 0,
										'octave' => 4,
									),
									'duration' => 2,
								)
							)
						),
						array(
							'notes' => array(
								array(
									'pitch' => array(
										'step' => 'C',
										'alter' => 0,
										'octave' => 5,
									),
									'duration' => 2,
								)
							)
						)
					)
				)
			)			
		),
		array(
			'time' => array('', 3, 4),
			'layers' => array(
				array(
					'chords' => array(
						array(
							'notes' => array(
								array(
									'pitch' => array(
										'step' => 'B',
										'alter' => 0,
										'octave' => 4,
									),
									'duration' => 6,
								)
							)
						)
					)
				)
			)			
		)
	)
));

