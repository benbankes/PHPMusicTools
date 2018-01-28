<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/PitchClassSet.php';
require_once __DIR__.'/../classes/Utils/BitmaskUtils.php';

class PitchClassSetTest extends PHPMusicToolsTest
{
	
    public static function setUpBeforeClass()
    {
    }

	protected function setUp(){
	}
	protected function tearDown(){
	}


	/**
	 * @dataProvider provider_spectrum
	 */
	public function test_spectrum($pcs, $spectrum) {
		$pcs = new \ianring\PitchClassSet($pcs);
		$actual = $pcs->spectrum();
		$this->assertEquals($actual, $spectrum);
	}
	public function provider_spectrum() {
		return array(
			'diatonic scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,4,5,7,9,11)),
				'spectrum' => array(
					1 => array(1,2),
					2 => array(3,4),
					3 => array(5,6),
					4 => array(6,7),
					5 => array(8,9),
					6 => array(10,11),
				)
			),
			'natural minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,8,10)),
				'spectrum' => array(
					1 => array(1,2),
					2 => array(3,4),
					3 => array(5,6),
					4 => array(6,7),
					5 => array(8,9),
					6 => array(10,11),
				)
			),
			'ascending melodic minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,9,11)),
				'spectrum' => array(
					1 => array(1,2),
					2 => array(3,4),
					3 => array(4,5,6),
					4 => array(6,7,8),
					5 => array(8,9),
					6 => array(10,11),
				)
			),
			'harmonic minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,8,11)),
				'spectrum' => array(
					1 => array(1,2,3),
					2 => array(3,4),
					3 => array(4,5,6),
					4 => array(6,7,8),
					5 => array(8,9),
					6 => array(9,10,11),
				)
			),
			'whole tone plus one' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,4,6,8,10,11)),
				'spectrum' => array(
					1 => array(1,2),
					2 => array(2,3,4),
					3 => array(4,5,6),
					4 => array(6,7,8),
					5 => array(8,9,10),
					6 => array(10,11),
				)
			)
		);
	}

	/**
	 * @dataProvider provider_spectrumWidth
	 */
	public function test_spectrumWidth($pcs, $spectrum) {
		$pcs = new \ianring\PitchClassSet($pcs);
		$actual = $pcs->spectrumWidth();
		$this->assertEquals($actual, $spectrum);
	}
	public function provider_spectrumWidth() {
		return array(
			'diatonic scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,4,5,7,9,11)),
				'spectrum' => array(
					1 => 1,
					2 => 1,
					3 => 1,
					4 => 1,
					5 => 1,
					6 => 1,
				)
			),
			'natural minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,8,10)),
				'spectrum' => array(
					1 => 1,
					2 => 1,
					3 => 1,
					4 => 1,
					5 => 1,
					6 => 1,
				)
			),
			'ascending melodic minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,9,11)),
				'spectrum' => array(
					1 => 1,
					2 => 1,
					3 => 2,
					4 => 2,
					5 => 1,
					6 => 1,
				)
			),
			'harmonic minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,8,11)),
				'spectrum' => array(
					1 => 2,
					2 => 1,
					3 => 2,
					4 => 2,
					5 => 1,
					6 => 2,
				)
			),
			'whole tone plus one' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,4,6,8,10,11)),
				'spectrum' => array(
					1 => 1,
					2 => 2,
					3 => 2,
					4 => 2,
					5 => 2,
					6 => 1,
				)
			)
		);
	}

	/**
	 * @dataProvider provider_isDeepScale
	 */
	public function test_isDeepScale($input, $expected){
		$pcs = new \ianring\PitchClassSet($input);
		$pf = $pcs->isDeepScale();
		$this->assertEquals($expected, $pf);
	}
	public function provider_isDeepScale() {
		return array(
			array('input' => 1387, 'expected' => true),
			array('input' => 1451, 'expected' => true),
			array('input' => 1453, 'expected' => true),
			array('input' => 1709, 'expected' => true),
			array('input' => 1717, 'expected' => true),
			array('input' => 2741, 'expected' => true),
			array('input' => 2773, 'expected' => true),

			array('input' => 273, 'expected' => false),
			array('input' => 585, 'expected' => false),
			array('input' => 661, 'expected' => false),
			array('input' => 859, 'expected' => false),
			array('input' => 1193, 'expected' => false),
			array('input' => 1257, 'expected' => false),
			array('input' => 1365, 'expected' => false),
			array('input' => 1371, 'expected' => false),
			array('input' => 1389, 'expected' => false),
			array('input' => 1397, 'expected' => false),
			array('input' => 1459, 'expected' => false),
			array('input' => 1485, 'expected' => false),
			array('input' => 1493, 'expected' => false),
			array('input' => 1499, 'expected' => false),
			array('input' => 1621, 'expected' => false),
			array('input' => 1643, 'expected' => false),
			array('input' => 1725, 'expected' => false),
			array('input' => 1741, 'expected' => false),
			array('input' => 1749, 'expected' => false),
			array('input' => 1753, 'expected' => false),
			array('input' => 1755, 'expected' => false),
			array('input' => 2257, 'expected' => false),
			array('input' => 2275, 'expected' => false),
			array('input' => 2457, 'expected' => false),
			array('input' => 2475, 'expected' => false),
			array('input' => 2477, 'expected' => false),
			array('input' => 2483, 'expected' => false),
			array('input' => 2509, 'expected' => false),
			array('input' => 2535, 'expected' => false),
			array('input' => 2731, 'expected' => false),
			array('input' => 2733, 'expected' => false),
			array('input' => 2777, 'expected' => false),
			array('input' => 2869, 'expected' => false),
			array('input' => 2901, 'expected' => false),
			array('input' => 2925, 'expected' => false),
			array('input' => 2925, 'expected' => false),
			array('input' => 2989, 'expected' => false),
			array('input' => 2997, 'expected' => false),
			array('input' => 3055, 'expected' => false),
			array('input' => 3411, 'expected' => false),
			array('input' => 3445, 'expected' => false),
			array('input' => 3549, 'expected' => false),
			array('input' => 3669, 'expected' => false),
			array('input' => 3765, 'expected' => false),
			array('input' => 4095, 'expected' => false),
		);
	}


	/**
	 * @dataProvider provider_hasMyhillProperty
	 */
	public function test_hasMyhillProperty($input, $expected){
		$pcs = new \ianring\PitchClassSet($input);

		$s = $pcs->spectrum();
		// print_r($s);
		// echo $expected?'MYHILL':'NOPE';
		// echo "\n\n\n\n";

		$pf = $pcs->hasMyhillProperty();
		$this->assertEquals($expected, $pf);
	}
	public function provider_hasMyhillProperty() {
		return array(
			array('input' => 661, 'expected' => true),
			array('input' => 1193, 'expected' => true),
			array('input' => 1387, 'expected' => true),
			array('input' => 1451, 'expected' => true),
			array('input' => 1453, 'expected' => true),
			array('input' => 1709, 'expected' => true),
			array('input' => 1717, 'expected' => true),
			array('input' => 2741, 'expected' => true),
			array('input' => 2773, 'expected' => true),

			array('input' => 273, 'expected' => false),
			array('input' => 585, 'expected' => false),
			array('input' => 859, 'expected' => false),
			array('input' => 1257, 'expected' => false),
			array('input' => 1365, 'expected' => false),
			array('input' => 1371, 'expected' => false),
			array('input' => 1389, 'expected' => false),
			array('input' => 1397, 'expected' => false),
			array('input' => 1459, 'expected' => false),
			array('input' => 1485, 'expected' => false),
			array('input' => 1493, 'expected' => false),
			array('input' => 1499, 'expected' => false),
			array('input' => 1621, 'expected' => false),
			array('input' => 1643, 'expected' => false),
			array('input' => 1725, 'expected' => false),
			array('input' => 1741, 'expected' => false),
			array('input' => 1749, 'expected' => false),
			array('input' => 1753, 'expected' => false),
			array('input' => 1755, 'expected' => false),
			array('input' => 2257, 'expected' => false),
			array('input' => 2275, 'expected' => false),
			array('input' => 2457, 'expected' => false),
			array('input' => 2475, 'expected' => false),
			array('input' => 2477, 'expected' => false),
			array('input' => 2483, 'expected' => false),
			array('input' => 2509, 'expected' => false),
			array('input' => 2535, 'expected' => false),
			array('input' => 2731, 'expected' => false),
			array('input' => 2733, 'expected' => false),
			array('input' => 2777, 'expected' => false),
			array('input' => 2869, 'expected' => false),
			array('input' => 2901, 'expected' => false),
			array('input' => 2925, 'expected' => false),
			array('input' => 2925, 'expected' => false),
			array('input' => 2989, 'expected' => false),
			array('input' => 2997, 'expected' => false),
			array('input' => 3055, 'expected' => false),
			array('input' => 3411, 'expected' => false),
			array('input' => 3445, 'expected' => false),
			array('input' => 3549, 'expected' => false),
			array('input' => 3669, 'expected' => false),
			array('input' => 3765, 'expected' => false),
			array('input' => 4095, 'expected' => false),
		);
	}


	/**
	 * @dataProvider provider_spectraVariation
	 */
	public function test_spectraVariation($pcs, $expected) {
		$pcs = new \ianring\PitchClassSet($pcs);
		$actual = $pcs->spectraVariation();
		$this->assertEquals($actual, $expected);
	}
	public function provider_spectraVariation() {
		return array(
			'diatonic scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,4,5,7,9,11)),
				'expected' => (6/7)
			),
			'natural minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,8,10)),
				'expected' => (6/7)
			),
			'ascending melodic minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,9,11)),
				'spectrum' => (8/7)
			),
			'harmonic minor scale' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,5,7,8,11)),
				'spectrum' => (10/7)
			),
			'whole tone plus one' => array(
				'pcs' => \ianring\BitmaskUtils::tones2Bits(array(0,2,4,6,8,10,11)),
				'spectrum' => (10/7)
			)
		);
	}


	/**
	 * @dataProvider provider_constructFromTones
	 */
	public function test_constructFromTones($bitmask, $id, $forte, $mirror, $prime, $vector, $name){
		$pcs = \ianring\PitchClassSet::constructFromTones($prime);
		$this->assertInstanceOf(\ianring\PitchClassSet::class, $pcs);
		$this->assertEquals($pcs->bits, $bitmask);
	}
	public function provider_constructFromTones() {
		include('allpitchclasses.php');
		return $sets;
	}


	/**
	 * @dataProvider provider_datasetForte
	 */
	public function test_datasetForte($bitmask, $fortename, $primeform, $vector, $invarianceTn, $invarianceTnI, $zmate) {
		$pcs = \ianring\PitchClassSet::constructFromTones($primeform);
		$pf = $pcs->primeFormForte();
		$this->assertEquals($bitmask, $pf);
	}
	public function provider_datasetForte() {
		include('allforteprimes.php');
		return $allforteprimes;
	}



	/**
	 * @todo
	 * @dataProvider provider_primeFormRahn
	 */
	public function test_primeFormRahn($input, $expected) {
		$pcs = new \ianring\PitchClassSet($input);
		$actual = $pcs->primeFormRahn();
		$this->assertEquals($expected, $actual);
	}
	public function provider_primeFormRahn() {
		return array(
			// there should be six sets that differ between forte and rahn
			'5-20' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,1,3,7,8)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,5,6,8))
			),
			'6-Z29' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,1,3,6,8,9)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,6,7,9))
			),
			'6-31' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,1,3,5,8,9)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,4,5,7,9))
			),
			'7-20' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,1,2,4,7,8,9)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,2,5,6,7,9))
			),
			'8-26' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,1,2,4,5,7,9,10)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,3,4,5,7,8,10))
			),
			'b' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,3,4,7,8,11)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,4,5,8,9))
			),
			'a' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,3,6,9,10,11)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,2,3,6,9))
			),
			// and let's test some other ones too OK
			'c' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(0,3,4,6,9,11)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,2,3,6,7,9))
			),
			'd' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(1,2,3,4,6,7,11)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,1,3,4,5,6,8))
			),
			'e' => array(
				'input' => \ianring\BitmaskUtils::tones2Bits(array(3,7,11)),
				'expected' => \ianring\BitmaskUtils::tones2Bits(array(0,4,8))
			),
		);


	}


	/**
	 * @dataProvider provider_getAllTransformations
	 */
	public function test_getAllTransformations($set, $expected) {
		$pcs = new \ianring\PitchClassSet($set);
		$t = $pcs->getAllTransformations();
		$this->assertEquals($t, $expected);
	}
	public function provider_getAllTransformations() {
		return array(
			'major locrian' => array(
				'set' => bindec('010101110101'),
				'expected' => array(
					'T0' => bindec('010101110101'),
					'T1' => bindec('101010111010'),
					'T2' => bindec('010101011101'),
					'T3' => bindec('101010101110'),
					'T4' => bindec('010101010111'),
					'T5' => bindec('101010101011'),
					'T6' => bindec('110101010101'),
					'T7' => bindec('111010101010'),
					'T8' => bindec('011101010101'),
					'T9' => bindec('101110101010'),
					'T10' => bindec('010111010101'),
					'T11' => bindec('101011101010'),
					'T0I' => bindec('101011101010'),
					'T1I' => bindec('010101110101'),
					'T2I' => bindec('101010111010'),
					'T3I' => bindec('010101011101'),
					'T4I' => bindec('101010101110'),
					'T5I' => bindec('010101010111'),
					'T6I' => bindec('101010101011'),
					'T7I' => bindec('110101010101'),
					'T8I' => bindec('111010101010'),
					'T9I' => bindec('011101010101'),
					'T10I' => bindec('101110101010'),
					'T11I' => bindec('010111010101'),
				)
			),
			'major' => array(
				'set' => bindec('101010110101'),
				'expected' => array(
					'T0' => bindec('101010110101'),
					'T1' => bindec('110101011010'),
					'T2' => bindec('011010101101'),
					'T3' => bindec('101101010110'),
					'T4' => bindec('010110101011'),
					'T5' => bindec('101011010101'),
					'T6' => bindec('110101101010'),
					'T7' => bindec('011010110101'),
					'T8' => bindec('101101011010'),
					'T9' => bindec('010110101101'),
					'T10' => bindec('101011010110'),
					'T11' => bindec('010101101011'),
					'T0I' => bindec('101011010101'),
					'T1I' => bindec('110101101010'),
					'T2I' => bindec('011010110101'),
					'T3I' => bindec('101101011010'),
					'T4I' => bindec('010110101101'),
					'T5I' => bindec('101011010110'),
					'T6I' => bindec('010101101011'),
					'T7I' => bindec('101010110101'),
					'T8I' => bindec('110101011010'),
					'T9I' => bindec('011010101101'),
					'T10I' => bindec('101101010110'),
					'T11I' => bindec('010110101011'),
				)
			)
		);
	}


	/**
	 * @dataProvider provider_getTransformation
	 */
	public function test_getTransformation($set1, $set2, $expected) {
		$t = \ianring\PitchClassSet::getTransformation($set1, $set2);
		$this->assertEquals($t, $expected);
	}
	public function provider_getTransformation() {
		return array(
			array(
				'set1' => bindec('010101110101'),
				'set2' => bindec('010101110101'),
				'expected' => 'T0',
			),
			array(
				'set1' => bindec('010101110101'),
				'set2' => bindec('101010111010'),
				'expected' => 'T1',
			),
			array(
				'set1' => bindec('010101110101'),
				'set2' => bindec('110101010101'),
				'expected' => 'T6',
			),
			array(
				'set1' => bindec('010101110101'),
				'set2' => bindec('111111111111'),
				'expected' => null,
			),
		);
	}




	/**
	 * @dataProvider provider_fortePrimes
	 */
	public function test_fortePrimes($set) {
		include('allforteprimes.php');
		$pcs = new \ianring\PitchClassSet($set);
		$prime = $pcs->primeFormForte();
		// if this one is prime, then it should be in the enummed collection
		if ($set == $prime) {
			$this->assertArrayHasKey($set, $allforteprimes, 'this prime isnt in the collection');
		} else {
			$this->assertArrayNotHasKey($set, $allforteprimes, 'this one is not prime so it should not be in the collection');
			$this->assertArrayHasKey($prime, $allforteprimes, 'the prime of this one is missing from the collection');
		}
	}
	public function provider_fortePrimes() {
		$o = array();
		$allsets = range(0, 4095);
		foreach ($allsets as $bits) {
			$o[] = array('bits' => $bits);
		}
		return $o;
	}


	/**
	 * @dataProvider provider_primeFormForte2
	 */
	public function test_primeFormForte2($input, $expected){
		$pcs = new \ianring\PitchClassSet($input);
		$pf = $pcs->primeFormForte();
		$this->assertEquals($expected, $pf);
	}
	public function provider_primeFormForte2() {
		return array(
			array(
				'input' => 0,
				'expected' => 0
			),
			array(
				'input' => 1,
				'expected' => 1
			),
			array(
				'input' => 12,
				'expected' => 3
			),
			array(
				'input' => 1365,
				'expected' => 1365
			),
			array(
				'input' => 2741,
				'expected' => 1387
			),
			array(
				'input' => 4095,
				'expected' => 4095
			),
		);
	}

	/**
	 * @dataProvider provider_primeFormForte2
	 */
	/*
	public function test_zRelations($input, $expected){
		$pcs = new \ianring\PitchClassSet($input);
		$z = $pcs->zRelations();
		$this->assertEquals($expected, $z);
	}
	public function provider_primeFormForte2() {
		return array(
			array(
				'input' => 0146,
				'expected' => array()
			),
			array(
				'input' => 0256,
				'expected' => array()
			),
			array(
				'input' => 0137,
				'expected' => array()
			),
			array(
				'input' => 0467,
				'expected' => array()
			),
			array(
				'input' => 01356,
				'expected' => array()
			),
			array(
				'input' => 01348,
				'expected' => array()
			),
			array(
				'input' => 01457,
				'expected' => array()
			),
			array(
				'input' => 02367,
				'expected' => array()
			),
			array(
				'input' => 01247,
				'expected' => array()
			),
			array(
				'input' => 03567,
				'expected' => array()
			),
			array(
				'input' => 03458,
				'expected' => array()
			),
			array(
				'input' => 01258,
				'expected' => array()
			),
			array(
				'input' => 03678,
				'expected' => array()
			),
			array(
				'input' => 012356,
				'expected' => array()
			),
			array(
				'input' => 013456,
				'expected' => array()
			),
			array(
				'input' => 012456,
				'expected' => array()
			),
			array(
				'input' => 012567,
				'expected' => array()
			),
			array(
				'input' => 013457,
				'expected' => array()
			),
			array(
				'input' => 023467,
				'expected' => array()
			),
			array(
				'input' => 012457,
				'expected' => array()
			),
			array(
				'input' => 023567,
				'expected' => array()
			),
			array(
				'input' => 012467,
				'expected' => array()
			),
			array(
				'input' => 013567,
				'expected' => array()
			),
			array(
				'input' => 013467,
				'expected' => array()
			),
			array(
				'input' => 012478,
				'expected' => array()
			),
			array(
				'input' => 014678,
				'expected' => array()
			),
			array(
				'input' => 014578,
				'expected' => array()
			),
			array(
				'input' => 023568,
				'expected' => array()
			),
			array(
				'input' => 013468,
				'expected' => array()
			),
			array(
				'input' => 024578,
				'expected' => array()
			),
			array(
				'input' => 013568,
				'expected' => array()
			),
			array(
				'input' => 023578,
				'expected' => array()
			),
			array(
				'input' => 013578,
				'expected' => array()
			),
			array(
				'input' => 013569,
				'expected' => array()
			),
			array(
				'input' => 013689,
				'expected' => array()
			),
			array(
				'input' => 012347,
				'expected' => array()
			),
			array(
				'input' => 034567,
				'expected' => array()
			),
			array(
				'input' => 012348,
				'expected' => array()
			),
			array(
				'input' => 012378,
				'expected' => array()
			),
			array(
				'input' => 023458,
				'expected' => array()
			),
			array(
				'input' => 034568,
				'expected' => array()
			),
			array(
				'input' => 	012358,
				'expected' => array()
			),
			array(
				'input' => 035678,
				'expected' => array()
			),
			array(
				'input' => 012368,
				'expected' => array()
			),
			array(
				'input' => 025678,
				'expected' => array()
			),
			array(
				'input' => 012369,
				'expected' => array()
			),
			array(
				'input' => 012568,
				'expected' => array()
			),
			array(
				'input' => 023678,
				'expected' => array()
			),
			array(
				'input' => 012569,
				'expected' => array()
			),
			array(
				'input' => 012589,
				'expected' => array()
			),
			array(
				'input' => 023469,
				'expected' => array()
			),
			array(
				'input' => 012469,
				'expected' => array()
			),
			array(
				'input' => 024569,
				'expected' => array()
			),
			array(
				'input' => 012479,
				'expected' => array()
			),
			array(
				'input' => 023479,
				'expected' => array()
			),
			array(
				'input' => 012579,
				'expected' => array()
			),
			array(
				'input' => 013479,
				'expected' => array()
			),
			array(
				'input' => 014679,
				'expected' => array()
			),
			array(
				'input' => 0123479,
				'expected' => array()
			),
			array(
				'input' => 0124569,
				'expected' => array()
			),
			array(
				'input' => 0123589,
				'expected' => array()
			),
			array(
				'input' => 0146789,
				'expected' => array()
			),
			array(
				'input' => 0123568,
				'expected' => array()
			),
			array(
				'input' => 0235678,
				'expected' => array()
			),
			array(
				'input' => 0134578,
				'expected' => array()
			),
			array(
				'input' => 0124578,
				'expected' => array()
			),
			array(
				'input' => 0134678,
				'expected' => array()
			),
			array(
				'input' => 01234689,
				'expected' => array()
			),
			array(
				'input' => 01356789,
				'expected' => array()
			),
			array(
				'input' => 01235679,
				'expected' => array()
			),
			array(
				'input' => 02346789,
				'expected' => array()
			),
		);
	}
	*/

}

