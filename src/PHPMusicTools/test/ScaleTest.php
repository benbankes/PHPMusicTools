<?php

require_once 'PHPMusicToolsTest.php';
require_once '../classes/Scale.php';
require_once '../classes/Pitch.php';

class ScaleTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}
	
	public function testConstructFromArray() {

		$input = array(
			'scale' => 4033,
			'root' => array(
				'step' => 'C',
				'alter' => 1,
				'octave' => 4,
			),
			'direction' => 'ascending'
		);

		$scale = \ianring\Scale::constructFromArray($input);

		$this->assertObjectHasAttribute('scale', $scale);
		$this->assertObjectHasAttribute('root', $scale);
		$this->assertObjectHasAttribute('direction', $scale);

		$this->assertInstanceOf(\ianring\Pitch::class, $scale->root);
		$this->assertEquals('C', $scale->root->step);
		$this->assertEquals(1, $scale->root->alter);
		$this->assertEquals(4, $scale->root->octave);

		$this->assertEquals('ascending', $scale->direction);

	}

	/**
	 * @dataProvider providerResolveScaleFromStructure
	 */
	public function testResolveScaleFromStructure($structure, $expected) {
		$actual = ianring\Scale::resolveScaleFromStructure($structure);
		$this->assertEquals($expected, $actual);
	}
	public function providerResolveScaleFromStructure() {
		return array(
			'major' 					=> array('structure' => '2212221', 'expected' => 2741),
			'whole tone' 					=> array('structure' => '222222', 'expected' => 1365),
			'Aeolian' 						=> array('structure' => '2122122', 'expected' => 1453),
			'Algerian' 						=> array('structure' => '21211131', 'expected' => 2541),
			'Arabian (a)' 					=> array('structure' => '21212121', 'expected' => 2925),
			'Arabian (b)' 					=> array('structure' => '2211222', 'expected' => 1397),
			'Asavari Theta' 				=> array('structure' => '2122122', 'expected' => 1453),
			'Balinese' 						=> array('structure' => '12414', 'expected' => 395),
			'Bilaval Theta' 				=> array('structure' => '2212221', 'expected' => 2741),
			'Bhairav Theta' 				=> array('structure' => '1312131', 'expected' => 2483),
			'Bhairavi Theta' 				=> array('structure' => '1222122', 'expected' => 1451),
			'Byzantine' 					=> array('structure' => '1312131', 'expected' => 2483),
			'Chinese' 						=> array('structure' => '42141', 'expected' => 2257),
			'Chinese Mongolian' 			=> array('structure' => '22323', 'expected' => 661),
			'Diminished' 					=> array('structure' => '21212121', 'expected' => 2925),
			'Dorian'   						=> array('structure' => '2122212', 'expected' => 1709),
			'Egyptian'    					=> array('structure' => '23232', 'expected' => 1189),
			'Ethiopian (A raray)'			=> array('structure' => '2212221', 'expected' => 2741),
			'Ethiopian (Geez & ezel)' 		=> array('structure' => '2122122', 'expected' => 1453),
			'Harmonic Minor' 				=> array('structure' => '2122131', 'expected' => 2477),
			'Hawaiian' 						=> array('structure' => '2122221', 'expected' => 2733),
			'Hindustan' 					=> array('structure' => '2212122', 'expected' => 1461),
			'Hungarian Major' 				=> array('structure' => '3121212', 'expected' => 1753),
			'Hungarian Gypsy' 				=> array('structure' => '2131131', 'expected' => 2509),
			'Hungarian Gypsy Persian' 		=> array('structure' => '1312131', 'expected' => 2483),
			'Hungarian Minor' 				=> array('structure' => '2131131', 'expected' => 2509),
			'Ionian' 						=> array('structure' => '2212221', 'expected' => 2741),
			'Japanese (A)' 					=> array('structure' => '14214', 'expected' => 419),
			'Japenese (B)' 					=> array('structure' => '23214', 'expected' => 421),
			'Japanese (Ichikosucho)' 		=> array('structure' => '22111221', 'expected' => 2805),
			'Japanese (Taishikicho)' 		=> array('structure' => '221112111', 'expected' => 3829),
			'Javanese (Pelog)' 				=> array('structure' => '1222212', 'expected' => 1707),
			'Jewish (Adonai Malakh)' 		=> array('structure' => '11122212', 'expected' => 1711),
			'Jewish (Ahaba Rabba)' 			=> array('structure' => '1312122', 'expected' => 1459),
			'Jewish (Magan Abot)' 			=> array('structure' => '12122211', 'expected' => 3419),
			'Kafi Theta' 					=> array('structure' => '2122212', 'expected' => 1709),
			'Kalyan Theta' 					=> array('structure' => '2221221', 'expected' => 2773),
			'Khamaj Theta' 					=> array('structure' => '2212212', 'expected' => 1717),
			'Locrian' 						=> array('structure' => '1221222', 'expected' => 1387),
			'Lydian' 						=> array('structure' => '2221221', 'expected' => 2773),
			'Major' 						=> array('structure' => '2212221', 'expected' => 2741),
			'Marva Theta' 					=> array('structure' => '1321221', 'expected' => 2771),
			'Mela Bhavapriya (44)' 			=> array('structure' => '1231122', 'expected' => 1483),
			'Mela Chakravakam (16)' 		=> array('structure' => '1312212', 'expected' => 1715),
			'Mela Chalanata (36)' 			=> array('structure' => '3112311', 'expected' => 3257),
			'Mela Charukesi (26)' 			=> array('structure' => '2212122', 'expected' => 1461),
			'Mela Chitrambari (66)' 		=> array('structure' => '2221311', 'expected' => 3285),
			'Mela Dharmavati (59)' 			=> array('structure' => '2131221', 'expected' => 2765),
			'Mela Dhatuvardhani (69)' 		=> array('structure' => '3121131', 'expected' => 2521),
			'Mela Dhavalambari (49)' 		=> array('structure' => '1321113', 'expected' => 979),
			'Mela Dhenuka (9)' 				=> array('structure' => '1222131', 'expected' => 2475),
			'Mela Dhirasankarabharana' 		=> array('structure' => '2212221', 'expected' => 2741),
			'Mela Divyamani (48)' 			=> array('structure' => '1231311', 'expected' => 3275),
			'Mela Gamanasrama (53)' 		=> array('structure' => '1321221', 'expected' => 2771),
			'Mela Ganamurti (3)' 			=> array('structure' => '1132131', 'expected' => 2471),
			'Mela Gangeyabhusani (33)' 		=> array('structure' => '3112131', 'expected' => 2489),
			'Mela Gaurimanohari (23)' 		=> array('structure' => '2122221', 'expected' => 2733),
			'Mela Gavambodhi (43)' 			=> array('structure' => '1231113', 'expected' => 971),
			'Mela Gayakapriya (13)' 		=> array('structure' => '1312113', 'expected' => 947),
			'Mela Hanumattodi (8)' 			=> array('structure' => '1222122', 'expected' => 1451),
			'Mela Harikambhoji (28)' 		=> array('structure' => '2212212', 'expected' => 1717),
			'Mela Hatakambari (18)' 		=> array('structure' => '1312311', 'expected' => 3251),
			'Mela Hemavati (58)' 			=> array('structure' => '2131212', 'expected' => 1741),
			'Mela Jalarnavam (38)' 			=> array('structure' => '1141122', 'expected' => 1479),
			'Mela Jhalavarali (39)' 		=> array('structure' => '1141131', 'expected' => 2503),
			'Mela Jhankaradhvani (19)' 		=> array('structure' => '2122113', 'expected' => 941),
			'Mela Jyotisvarupini (68)' 		=> array('structure' => '3121122', 'expected' => 1497),
			'Mela Kamavarardhani (51)' 		=> array('structure' => '1321131', 'expected' => 2515),
			'Mela Kanakangi (1)' 			=> array('structure' => '1132113', 'expected' => 935),
			'Mela Kantamani (61)' 			=> array('structure' => '2221113', 'expected' => 981),
			'Mela Kharaharapriya (22)' 		=> array('structure' => '2122212', 'expected' => 1709),
			'Mela Kiravani (21)' 			=> array('structure' => '2122131', 'expected' => 2477),
			'Mela Kokilapriya (11)' 		=> array('structure' => '1222221', 'expected' => 2731),
			'Mela Kosalam (71)' 			=> array('structure' => '3121221', 'expected' => 2777),
			'Mela Latangi (63)' 			=> array('structure' => '2221131', 'expected' => 2517),
			'Mela Manavati (5)' 			=> array('structure' => '1132221', 'expected' => 2727),
			'Mela Mararanjani (25)' 		=> array('structure' => '2212113', 'expected' => 949),
			'Mela Mayamalavagaula (15)'  	=> array('structure' => '1312131', 'expected' => 2483),
			'Mela Mechakalyani (65)' 		=> array('structure' => '2221221', 'expected' => 2773),
			'Mela Naganandini (30)' 		=> array('structure' => '2212311', 'expected' => 3253),
			'Mela Namanarayani (50)' 		=> array('structure' => '1321122', 'expected' => 1491),
			'Mela Nasikabhusani (70)' 		=> array('structure' => '3121212', 'expected' => 1753),
			'Mela Natabhairavi (20)' 		=> array('structure' => '2122122', 'expected' => 1453),
			'Mela Natakapriya (10)' 		=> array('structure' => '1222212', 'expected' => 1707),
			'Mela Navanitam (40)' 			=> array('structure' => '1141212', 'expected' => 1735),
			'Mela Nitimati (60)' 			=> array('structure' => '2131311', 'expected' => 3277),
			'Mela Pavani (41)' 				=> array('structure' => '1141221', 'expected' => 2759),
			'Mela Ragavardhani (32)' 		=> array('structure' => '3112122', 'expected' => 1465),
			'Mela Raghupriya (42)    ' 		=> array('structure' => '1141311', 'expected' => 3271),
			'Mela Ramapriya (52)     ' 		=> array('structure' => '1321212', 'expected' => 1747),
			'Mela Rasikapriya (72)   ' 		=> array('structure' => '3121311', 'expected' => 3289),
			'Mela Ratnangi (2)       ' 		=> array('structure' => '1132122', 'expected' => 1447),
			'Mela Risabhapriya (62)  ' 		=> array('structure' => '2221122', 'expected' => 1493),
			'Mela Rupavati (12)      ' 		=> array('structure' => '1222311', 'expected' => 3243),
			'Mela Sadvidhamargini (46)' 	=> array('structure' => '1231212', 'expected' => 1739),
			'Mela Salagam (37)       ' 		=> array('structure' => '1141113', 'expected' => 967),
			'Mela Sanmukhapriya (56) ' 		=> array('structure' => '2131122', 'expected' => 1485),
			'Mela Sarasangi (27)     ' 		=> array('structure' => '2212131', 'expected' => 2485),
			'Mela Senavati (7)       ' 		=> array('structure' => '1222113', 'expected' => 939),
			'Mela Simhendramadhyama (57)' 	=> array('structure' => '2131131', 'expected' => 2509),
			'Mela Subhapantuvarali (45)' 	=> array('structure' => '1231131', 'expected' => 2507),
			'Mela Sucharitra (67)    ' 		=> array('structure' => '3121113', 'expected' => 985),
			'Mela Sulini (35)        ' 		=> array('structure' => '3112221', 'expected' => 2745),
			'Mela Suryakantam (17)   ' 		=> array('structure' => '1312221', 'expected' => 2739),
			'Mela Suvarnangi (47)    ' 		=> array('structure' => '1231221', 'expected' => 2763),
			'Mela Syamalangi (55)    ' 		=> array('structure' => '2131113', 'expected' => 973),
			'Mela Tanarupi (6)       ' 		=> array('structure' => '1132311', 'expected' => 3239),
			'Mela Vaschaspati (64)   ' 		=> array('structure' => '2221212', 'expected' => 1749),
			'Mela Vagadhisvari (34)  ' 		=> array('structure' => '3112212', 'expected' => 1721),
			'Mela Vakulabharanam (14)' 		=> array('structure' => '1312122', 'expected' => 1459),
			'Mela Vanaspati (4)      ' 		=> array('structure' => '1132212', 'expected' => 1703),
			'Mela Varunapriya (24)   ' 		=> array('structure' => '2122311', 'expected' => 3245),
			'Mela Visvambari (54)    ' 		=> array('structure' => '1321311', 'expected' => 3283),
			'Mela Yagapriya (31)     ' 		=> array('structure' => '3112113', 'expected' => 953),
			'Melodic Minor           ' 		=> array('structure' => '2122221', 'expected' => 2733),
			'Mixolydian              ' 		=> array('structure' => '2212212', 'expected' => 1717),
			'Mohammedan              ' 		=> array('structure' => '2122131', 'expected' => 2477),
			'Neopolitan              ' 		=> array('structure' => '1222131', 'expected' => 2475),
			'Oriental (a)            ' 		=> array('structure' => '1311222', 'expected' => 1395),
			'Overtone Dominant       ' 		=> array('structure' => '2221212', 'expected' => 1749),
			'Pentatonic Major        ' 		=> array('structure' => '23223', 'expected' => 677),
			'Pentatonic Minor        ' 		=> array('structure' => '32232', 'expected' => 1193),
			'Persian                 ' 		=> array('structure' => '1311231', 'expected' => 2419),
			'Phrygian                ' 		=> array('structure' => '1222122', 'expected' => 1451),
			'Purvi Theta             ' 		=> array('structure' => '1321131', 'expected' => 2515),
			'Roumanian Minor         ' 		=> array('structure' => '2131212', 'expected' => 1741),
			'Spanish Gypsy           ' 		=> array('structure' => '1312122', 'expected' => 1459),
			'Todi Theta              ' 		=> array('structure' => '1231131', 'expected' => 2507),
			'Whole Tone              ' 		=> array('structure' => '222222', 'expected' => 1365),
			'Augmented               ' 		=> array('structure' => '313131', 'expected' => 2457),
			'Blues                   ' 		=> array('structure' => '321132', 'expected' => 1257),
			'Diatonic                ' 		=> array('structure' => '22323', 'expected' => 661),
			'Double Harmonic         ' 		=> array('structure' => '1312131', 'expected' => 2483),
			'Eight Tone Spanish      ' 		=> array('structure' => '12111222', 'expected' => 1403),
			'Enigmatic               ' 		=> array('structure' => '1322211', 'expected' => 3411),
			'Hirajoshi               ' 		=> array('structure' => '21414', 'expected' => 397),
			'Kumoi                   ' 		=> array('structure' => '21423', 'expected' => 653),
			'Leading Whole Tone      ' 		=> array('structure' => '2222211', 'expected' => 3413),
			'Lydian Augmented        ' 		=> array('structure' => '2222121', 'expected' => 2901),
			'Neoploitan Major        ' 		=> array('structure' => '1222221', 'expected' => 2731),
			'Neopolitan Minor        ' 		=> array('structure' => '1222122', 'expected' => 1451),
			'Oriental (b)            ' 		=> array('structure' => '1311312', 'expected' => 1651),
			'Pelog (Javanese)        ' 		=> array('structure' => '1222212', 'expected' => 1707),
			'Prometheus              ' 		=> array('structure' => '222312', 'expected' => 1621),
			'Prometheus Neopolitan   ' 		=> array('structure' => '132312', 'expected' => 1619),
			'Six Tone Symmetrical    ' 		=> array('structure' => '131313', 'expected' => 819),
			'Super Locrian           ' 		=> array('structure' => '1212222', 'expected' => 1371),
			'Lydian Minor            ' 		=> array('structure' => '2221122', 'expected' => 1493),
			'Lydian Diminished       ' 		=> array('structure' => '2131122', 'expected' => 1485),
			'Nine Tone Scale         ' 		=> array('structure' => '211211121', 'expected' => 3037),
			'Auxiliary Diminished    ' 		=> array('structure' => '21212121', 'expected' => 2925),
			'Auxiliary Augmented     ' 		=> array('structure' => '222222', 'expected' => 1365),
			'Auxiliary Diminished Blues' 	=> array('structure' => '12121212', 'expected' => 1755),
			'Major Locrian           ' 		=> array('structure' => '2211222', 'expected' => 1397),
			'Overtone                ' 		=> array('structure' => '2221212', 'expected' => 1749),
			'Hindu                   ' 		=> array('structure' => '2212122', 'expected' => 1461),
			'Diminished Whole Tone   ' 		=> array('structure' => '1212222', 'expected' => 1371),
			'Pure Minor              ' 		=> array('structure' => '2122122', 'expected' => 1453),
			'Half Diminished (Locrian)' 	=> array('structure' => '1221222', 'expected' => 1387),
			'Half Diminished #2 (Locrian #2)'	=> array('structure' => '2121222', 'expected' => 1389),
			'Dominant 7th            ' 		=> array('structure' => '232212', 'expected' => 1701),
		);
	}



	/**
	 * @dataProvider providerLevenshtein
	 */
	public function testLevenshteinScale($scale1, $scale2, $expected) {
		$actual = ianring\Scale::levenshtein_scale($scale1, $scale2);
		$this->assertEquals($actual, $expected);
	}
	public function providerLevenshtein() {
		return array(
			array('scale1' => 2741, 'scale2' => 2743, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2739, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2745, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2737, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2749, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2733, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2725, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2773, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2709, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2805, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2677, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2869, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2613, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2997, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2485, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 3253, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 2229, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 3765, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 1717, 'expected' => 1),
			array('scale1' => 2741, 'scale2' => 693, 'expected' => 1),
			array('scale1' => 2743, 'scale2' => 2741, 'expected' => 1),
			array('scale1' => 325, 'scale2' => 4095, 'expected' => 8),
			array('scale1' => 273, 'scale2' => 4095, 'expected' => 9),
			array('scale1' => 3549, 'scale2' => 4095, 'expected' => 3),
			array('scale1' => 3549, 'scale2' => 3003, 'expected' => 3),
			array('scale1' => 585, 'scale2' => 273, 'expected' => 3),
			array('scale1' => 273, 'scale2' => 585, 'expected' => 3),
			array('scale1' => 3935, 'scale2' => 4031, 'expected' => 2),
			array('scale1' => 3935, 'scale2' => 3871, 'expected' => 1),

		);
	}

	/**
	 * @dataProvider providerNormalizeScalePitches
	 */
	public function testNormalizeScalePitches($scale, $pitches, $expected) {
		$scale = new \ianring\Scale($scale, $pitches[0]);
		$actual = $scale->_normalizeScalePitches($pitches);
		$this->assertEquals($expected, $actual);
	}
	public function providerNormalizeScalePitches() {
		return array(
			array(
				'scale' => 2741,
				'pitches' => array(
					new \ianring\Pitch('C', 0, 3),
					new \ianring\Pitch('D', 0, 3),
					new \ianring\Pitch('F', -1, 3), // this one should change to an E natural
					new \ianring\Pitch('F', 0, 3),
					new \ianring\Pitch('G', 0, 3),
					new \ianring\Pitch('A', 0, 3),
					new \ianring\Pitch('B', 0, 3),
				),
				'expected' => array(
					new \ianring\Pitch('C', 0, 3),
					new \ianring\Pitch('D', 0, 3),
					new \ianring\Pitch('E', 0, 3),
					new \ianring\Pitch('F', 0, 3),
					new \ianring\Pitch('G', 0, 3),
					new \ianring\Pitch('A', 0, 3),
					new \ianring\Pitch('B', 0, 3),
				)
			),
			array(
				'scale' => 2741,
				'pitches' => array(
					new \ianring\Pitch('C', 1, 3),
					new \ianring\Pitch('D', 1, 3),
					new \ianring\Pitch('F', 0, 3), // this one should change to an E sharp
					new \ianring\Pitch('F', 1, 3),
					new \ianring\Pitch('G', 1, 3),
					new \ianring\Pitch('A', 1, 3),
					new \ianring\Pitch('C', 0, 4), // this should become a B sharp
				),
				'expected' => array(
					new \ianring\Pitch('C', 1, 3),
					new \ianring\Pitch('D', 1, 3),
					new \ianring\Pitch('E', 1, 3),
					new \ianring\Pitch('F', 1, 3),
					new \ianring\Pitch('G', 1, 3),
					new \ianring\Pitch('A', 1, 3),
					new \ianring\Pitch('B', 1, 4),
				)
			)
		);
	}


}
