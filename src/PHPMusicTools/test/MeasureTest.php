<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Measure.php';

class MeasureTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function testConstructFromArray(){

		$input = array(
			'width' => 143.18,
			'directions' => array(
				0 => array(
					'directionType' => 'metronome',
					'parentheses' => false,
					'beatUnit' => 'quarter',
					'perMinute' => 125,
					'placement' => 'above',
					'offset' => 3,
					'staff' => 1,
					'sound' => array(
						'tempo' => 125
					)
				),
				1 => array(
					'directionType' => 'dynamics',
					'placement' => 'below',
						'text' => 'p',
					'staff' => 1,
					'sound' => array(
						'dynamics' => 54.44
					)
				)
			),
			'layers' => array(
				0 => array(
					'name' => 'Voice 1',
					'chords' => array(
						0 => array(
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
									'accidental' => array(
										'type' => 'double-sharp' // sharp, flat, natural, double-sharp, sharp-sharp, flat-flat, natural-sharp, natural-flat, quarter-flat, quarter-sharp, three- quarters-flat, and three-quarters-sharp
									),
									'rest' => false,
									'duration' => 4,
									'voice' => 1,
									'type' => 'eighth',
									'staff' => 1,
								)							)
						),
						1 => array(
							'notes' => array(
								0 => array(
									'accidental' => array(
										'courtesy' => true,
										'editorial' => null,
										'bracket' => false,
										'parentheses' => true,
										'size' => false,
										'type' => 'natural' // sharp, flat, natural, double-sharp, sharp-sharp, flat-flat, natural-sharp, natural-flat, quarter-flat, quarter-sharp, three- quarters-flat, and three-quarters-sharp
									)
								)
							)
						),
					)
				),
				1 => array(
					'name' => 'Voice 2',
					'chords' => array(
						0 => array(
							'notes' => array(

							)
						),
						1 => array(
							'notes' => array(
							)
						),
					)
				)
			),
			'time' => array(
				'symbol' => 'C',
				'beats' => 4,
				'beatType' => 4
			),
			'clef' => array(
				'sign' => 'G',
				'line' => 2
			),
			'key' => array(
				'fifths' => 3,
				'mode' => 'D'
			),
			'divisions' => 24,
			'barline' => array(
				'location' => 'right',
				'barStyle' => 'light-heavy',
				'footnote' => 'wtf',
				'ending' => array(
					'number' => 12,
					'type' => 'final'
				),
				'repeat' => array(
					'direction' => 'backward',
					'winged' => true
				)
			),
			'implicit' => false,
			'nonControlling' => false
		);

		$measure = \ianring\Measure::constructFromArray($input);

		$this->assertObjectHasAttribute('layers', $measure);
		$this->assertObjectHasAttribute('directions', $measure);
		$this->assertObjectHasAttribute('time', $measure);
		$this->assertObjectHasAttribute('clef', $measure);
		$this->assertObjectHasAttribute('key', $measure);
		$this->assertObjectHasAttribute('divisions', $measure);
		$this->assertObjectHasAttribute('barline', $measure);
		$this->assertObjectHasAttribute('implicit', $measure);
		$this->assertObjectHasAttribute('nonControlling', $measure);

		$this->assertEquals(143.18, $measure->width);


		$this->assertInstanceOf(\ianring\Layer::class, $measure->layers[0]);
			$this->assertEquals('Voice 1', $measure->layers[0]->name);
			$this->assertObjectHasAttribute('chords', $measure->layers[0]);
			$this->assertObjectHasAttribute('notes', $measure->layers[0]->chords[0]);
			$this->assertObjectHasAttribute('pitch', $measure->layers[0]->chords[0]->notes[0]);
			$this->assertInstanceOf(\ianring\Pitch::class, $measure->layers[0]->chords[0]->notes[0]->pitch);
			$this->assertEquals(false, $measure->layers[0]->chords[0]->notes[0]->rest);
			$this->assertEquals(4, $measure->layers[0]->chords[0]->notes[0]->pitch->octave);

			$this->assertInstanceOf(\ianring\TimeModification::class, $measure->layers[0]->chords[0]->notes[0]->timeModification);

			$this->assertInstanceOf(\ianring\Articulation::class, $measure->layers[0]->chords[0]->notes[0]->articulations[0]);
			$this->assertInstanceOf(\ianring\Articulation::class, $measure->layers[0]->chords[0]->notes[0]->articulations[1]);

			$this->assertInstanceOf(\ianring\NoteBeam::class, $measure->layers[0]->chords[0]->notes[0]->beams[0]);
			$this->assertEquals('begin', $measure->layers[0]->chords[0]->notes[0]->beams[0]->type);
			$this->assertEquals(1, $measure->layers[0]->chords[0]->notes[0]->beams[0]->number);
			$this->assertInstanceOf(\ianring\NoteBeam::class, $measure->layers[0]->chords[0]->notes[0]->beams[1]);
			$this->assertEquals(2, $measure->layers[0]->chords[0]->notes[0]->beams[1]->number);

			$this->assertEquals(false, $measure->layers[0]->chords[0]->notes[0]->rest);


			$this->assertInstanceOf(\ianring\Accidental::class, $measure->layers[0]->chords[1]->notes[0]->accidental);
			$this->assertEquals('natural', $measure->layers[0]->chords[1]->notes[0]->accidental->type);


		$this->assertInstanceOf(\ianring\DirectionMetronome::class, $measure->directions[0]);
			$this->assertEquals('above', $measure->directions[0]->placement);
			$this->assertEquals(1, $measure->directions[0]->staff);

			$this->assertEquals(false, $measure->directions[0]->parentheses);
			$this->assertEquals('quarter', $measure->directions[0]->beatUnit);
			$this->assertEquals(125, $measure->directions[0]->perMinute);


		$this->assertInstanceOf(\ianring\DirectionDynamics::class, $measure->directions[1]);
			$this->assertEquals('below', $measure->directions[1]->placement);
			$this->assertEquals(1, $measure->directions[1]->staff);



		$this->assertInstanceOf(\ianring\Layer::class, $measure->layers[1]);

		$this->assertInstanceOf(\ianring\Time::class, $measure->time);
			$this->assertEquals('C', $measure->time->symbol);
			$this->assertEquals(4, $measure->time->beats);
			$this->assertEquals(4, $measure->time->beatType);
		$this->assertInstanceOf(\ianring\Clef::class, $measure->clef);
			$this->assertEquals('G', $measure->clef->sign);
			$this->assertEquals(2, $measure->clef->line);
		$this->assertInstanceOf(\ianring\Key::class, $measure->key);
			$this->assertEquals(3, $measure->key->fifths);
			$this->assertEquals('D', $measure->key->mode);
		$this->assertInstanceOf(\ianring\Barline::class, $measure->barline);
			$this->assertEquals('right', $measure->barline->location);
			$this->assertEquals('light-heavy', $measure->barline->barStyle);
			$this->assertEquals('wtf', $measure->barline->footnote);
		$this->assertInstanceOf(\ianring\BarlineEnding::class, $measure->barline->ending);
			$this->assertEquals(12, $measure->barline->ending->number);
			$this->assertEquals('final', $measure->barline->ending->type);
		$this->assertInstanceOf(\ianring\BarlineRepeat::class, $measure->barline->repeat);
			$this->assertEquals('backward', $measure->barline->repeat->direction);
			$this->assertEquals(true, $measure->barline->repeat->winged);

			$this->assertEquals(false, $measure->implicit);
			$this->assertEquals(false, $measure->nonControlling);


	}



}
