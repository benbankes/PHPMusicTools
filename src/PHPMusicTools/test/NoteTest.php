<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Note.php';

class NoteTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}

	public function getSample() {
		return array(
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
			'tie' => true,
			'timeModification' => array(
				'actualNotes' => 3,
				'normalNotes' => 2,
			),
			'defaultX' => 3,
			'defaultY' => 1,
			'chord' => true,
			'annotations' => array(
				// ironically in musicXML these are called notations
			),
			'notations' => array(
				array(
					'notationType' => 'tuplet',
					'number' => 1,
					'type' => 'stop',
					'bracket' => true,
					'show' => 'actual' // can be actual, both, or none
				),
				array(
					'notationType' => 'slur',
					'type' => 'start',
					'number' => 1,
				),
				array(
					'notationType' => 'tied',
				),
				array(
					'notationType' => 'slide',
				),
				array(
					'notationType' => 'ornament',
				),
				array(
					'notationType' => 'glissando',
				),
				array(
					'notationType' => 'fermata',
				),
				array(
					'notationType' => 'glissando',
				),
				array(
					'notationType' => 'accidentalmark',
				),
				array(
					'notationType' => 'nonarpeggiate',
				),
				array(
					'notationType' => 'technical',
				),
				array(
					'notationType' => 'arpeggiate',
				),
				array(
					'notationType' => 'dynamics',
				),
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
		);
	}
	
	public function test_constructFromArray(){

		$input = $this->getSample();
		$note = \ianring\Note::constructFromArray($input);

		$this->assertInstanceOf(\ianring\Note::class, $note);
			$this->assertObjectHasAttribute('pitch', $note);
			$this->assertInstanceOf(\ianring\Pitch::class, $note->pitch);
			$this->assertEquals(false, $note->rest);
			$this->assertEquals(4, $note->pitch->octave);

			$this->assertInstanceOf(\ianring\TimeModification::class, $note->timeModification);

			$this->assertInstanceOf(\ianring\Articulation::class, $note->articulations[0]);
			$this->assertInstanceOf(\ianring\Articulation::class, $note->articulations[1]);

			$this->assertInstanceOf(\ianring\NoteBeam::class, $note->beams[0]);
			$this->assertEquals('begin', $note->beams[0]->type);
			$this->assertEquals(1, $note->beams[0]->number);
			$this->assertInstanceOf(\ianring\NoteBeam::class, $note->beams[1]);
			$this->assertEquals(2, $note->beams[1]->number);

			$this->assertEquals(false, $note->rest);


	}

	public function test_toMusicXML(){

		$input = $this->getSample();
		$note = \ianring\Note::constructFromArray($input);
		$this->assertInstanceOf(\ianring\Note::class, $note);

		$xml = $note->toMusicXML();

		$this->assertEquals($xml, '<note default-x="3" default-y="1"><cue/><chord/><pitch><step>C</step><alter>1</alter><octave>4</octave></pitch><duration>4</duration><voice>1</voice><type>eighth</type><tie style="1"><stem default-x="2" default-y="3">up</stem><staff>1</staff></note>');


	}


}

