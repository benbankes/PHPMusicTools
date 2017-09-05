<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/Chord.php';

class ChordTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function test_constructFromArray(){

		$input = array(
			'notes' => array(
				0 => array(
					'pitch' => array(
						'step' => 'C',
						'alter' => 1,
						'octave' => 4,
					),
					'rest' => false,
					'duration' => 4,
					'voice' => 1,
					'type' => 'eighth',
					'accidental' => 'sharp',
					'dot' => false,
					'tie' => true,
					'timeModification' => array(
						'actualNotes' => 3,
						'normalNotes' => 2,
					),
					'defaultX' => 3,
					'defaultY' => 1,
					'chord' => true,
					'notations' => array(
						0 => array(
							'notationType' => 'tuplet',
							'number' => 1,
							'type' => 'stop',
						),
						1 => array(
							'notationType' => 'slur',
							'type' => 'start',
							'number' => 1,
						)
					),
					'articulations' => array(
						0 => array('articulationType' => 'staccato'),
						1 => array(
							'articulationType' => 'accent',
							'defaultX' => -1,
							'defaultY' => -71,
							'placement' => 'below',
						),
					),
					'staff' => 1,
					'beams' => array(
						0 => array(
							'number' => 1,
							'type' => 'begin',
						),
						1 => array(
							'number' => 2,
							'type' => 'begin',
						)
					),
					'stem' => array(
						'defaultX' => 2,
						'defaultY' => 3,
						'direction' => 'up',
					)
				),
				1 => array(
					'pitch' => array(
						'step' => 'C',
						'alter' => 1,
						'octave' => 4,
					),
					'rest' => false,
					'duration' => 4,
					'voice' => 1,
					'type' => 'eighth',
					'staff' => 1,
				)			
			)
		);

		$chord = \ianring\Chord::constructFromArray($input);

        $this->assertObjectHasAttribute('notes', $chord);
        $this->assertObjectHasAttribute('pitch', $chord->notes[0]);
		$this->assertInstanceOf(\ianring\Pitch::class, $chord->notes[0]->pitch);
		$this->assertEquals(false, $chord->notes[0]->rest);
		$this->assertEquals(4, $chord->notes[0]->pitch->octave);

		$this->assertInstanceOf(\ianring\TimeModification::class, $chord->notes[0]->timeModification);

		$this->assertInstanceOf(\ianring\Articulation::class, $chord->notes[0]->articulations[0]);
		$this->assertInstanceOf(\ianring\Articulation::class, $chord->notes[0]->articulations[1]);

		$this->assertInstanceOf(\ianring\NoteBeam::class, $chord->notes[0]->beams[0]);
		$this->assertEquals('begin', $chord->notes[0]->beams[0]->type);
		$this->assertEquals(1, $chord->notes[0]->beams[0]->number);
		$this->assertInstanceOf(\ianring\NoteBeam::class, $chord->notes[0]->beams[1]);
		$this->assertEquals(2, $chord->notes[0]->beams[1]->number);

		$this->assertEquals(false, $chord->notes[0]->rest);


	}

	/**
	 * @dataProvider provider_toMusicXML
	 */
	public function test_toMusicXML($input, $expected){
		$chord = \ianring\Chord::constructFromArray($input);
		$xml = $chord->toMusicXML();
		$this->assertEquals($xml, $expected);
	}
	public function provider_toMusicXML() {
		return array(
			array(
				'input' => array(
					'notes' => array(
						0 => array(
							'pitch' => array(
								'step' => 'C',
								'alter' => 1,
								'octave' => 4,
							),
							'rest' => false,
							'duration' => 4,
							'cue' => true,
							'voice' => 1,
							'type' => 'eighth',
							'accidental' => 'sharp',
							'dot' => false,
							'tie' => 'start',
							'timeModification' => array(
								'actualNotes' => 3,
								'normalNotes' => 2,
							),
							'defaultX' => 3,
							'defaultY' => 1,
							'annotations' => array(
								// ironically in musicXML these are called notations
							),
							'notations' => array(
								array(
									'notationType' => 'tuplet',
									'number' => 1,
									'type' => 'stop',
									'bracket' => true,
									'placement' => 'above',
									'showNumber' => 'actual', // can be actual, both, or none
									'showType' => 'actual', // can be actual, both, or none
									'lineShape' => 'curved', // can be straight or curved
								),
								array(
									'notationType' => 'slur',
									'type' => 'start',
									'number' => 1,
								),
							),
							'articulations' => array(
								array(
									'articulationType' => 'staccato',
									'placement' => 'below'
								),
								array(
									'articulationType' => 'accent',
									'defaultX' => -1,
									'defaultY' => -71,
									'placement' => 'below',
								),
							),
							'staff' => 1,
							'beams' => array(
								array(
									'number' => 1,
									'type' => 'begin',
								),
								array(
									'number' => 2,
									'type' => 'continue',
								)
							),
							'stem' => array(
								'defaultX' => 2,
								'defaultY' => 3,
								'direction' => 'up',
							)
						),
						1 => array(
							'pitch' => array(
								'step' => 'C',
								'alter' => 1,
								'octave' => 4,
							),
							'rest' => false,
							'duration' => 4,
							'voice' => 1,
							'type' => 'eighth',
							'staff' => 1,
						),
						2 => array(
							'pitch' => array(
								'step' => 'E',
								'alter' => 0,
								'octave' => 4,
							),
							'rest' => false,
							'duration' => 4,
							'voice' => 1,
							'type' => 'eighth',
							'staff' => 1,
						),
					)

				),
				'expected' => '<note default-x="3" default-y="1"><cue/><pitch><step>C</step><alter>1</alter><octave>4</octave></pitch><duration>4</duration><voice>1</voice><type>eighth</type><tie type="start"/><stem default-x="2" default-y="3">up</stem><staff>1</staff><time-modification><actual-notes>3</actual-notes><normal-notes>2</normal-notes></time-modification><beam number="1">begin</beam><beam number="2">continue</beam><stem default-x="2" default-y="3">up</stem><notations><tuplet bracket="yes" line-shape="curved" number="1" placement="above" show-number="actual" show-type="actual" type="stop"/><slur number="1" type="start"/><articulations><staccato placement="below"/><accent placement="below" default-x="-1" default-y="-71"/></articulations></notations></note><note><chord/><pitch><step>C</step><alter>1</alter><octave>4</octave></pitch><duration>4</duration><voice>1</voice><type>eighth</type><staff>1</staff></note><note><chord/><pitch><step>E</step><alter>0</alter><octave>4</octave></pitch><duration>4</duration><voice>1</voice><type>eighth</type><staff>1</staff></note>'
			)
		);
	}


	/**
	 * @dataProvider provider_toVexFlow
	 */
	public function test_toVexFlow($chord, $expected) {
		$result = $chord->toVexFlow();
		$this->assertEquals($expected, $result);
	}
	function provider_toVexFlow() {
		return array(
			array(
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'A', 'alter' => -1, 'octave' => 3)),
						array('pitch' => array('step' => 'C', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'E', 'alter' => -1, 'octave' => 4))
					)
				)),			
				'expected' => 'new Vex.Flow.StaveNote({keys:["ab\/3","cn\/4","eb\/4"], duration: "w"})' . "\n"
					. '    .addAccidental(0, new Vex.Flow.Accidental("b"))' . "\n"
					. '    .addAccidental(2, new Vex.Flow.Accidental("b"))' . "\n"
			),

			array(
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'C', 'alter' => 0, 'octave' => 4)),
					)
				)),			
				'expected' => 'new Vex.Flow.StaveNote({keys:["cn\/4"], duration: "w"})' . "\n"
			),

		);
	}


	/**
	 * @dataProvider provider_analyzeTriad
	 */
	public function test_analyzeTriad($chord, $expected) {
		$result = $chord->analyzeTriad();
		$this->assertEquals($expected, $result);
	}
	function provider_analyzeTriad() {
		return array(
			'maj' => array(
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'A', 'alter' => -1, 'octave' => 3)),
						array('pitch' => array('step' => 'C', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'E', 'alter' => -1, 'octave' => 4))
					)
				)),			
				'expected' => 'maj'
			),
			'min' => array(
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'A', 'alter' => -1, 'octave' => 3)),
						array('pitch' => array('step' => 'C', 'alter' => -1, 'octave' => 4)),
						array('pitch' => array('step' => 'E', 'alter' => -1, 'octave' => 4))
					)
				)),			
				'expected' => 'min'
			),

			'aug' => array(
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'C', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'E', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'G', 'alter' => 1, 'octave' => 4))
					)
				)),			
				'expected' => 'aug'
			),

			'dim' => array(
				'chord' => \ianring\Chord::constructFromArray(array(
					'notes' => array(
						array('pitch' => array('step' => 'B', 'alter' => 0, 'octave' => 3)),
						array('pitch' => array('step' => 'D', 'alter' => 0, 'octave' => 4)),
						array('pitch' => array('step' => 'F', 'alter' => 0, 'octave' => 4))
					)
				)),			
				'expected' => 'dim'
			),

		);
	}



	/**
	 * @dataProvider provider_lowestMember
	 */
	public function test_lowestMember($chord, $expected) {
		$result = $chord->lowestMember();
		$this->assertEquals($expected, $result);
	}
	function provider_lowestMember() {
		return array(
		);
	}

}
