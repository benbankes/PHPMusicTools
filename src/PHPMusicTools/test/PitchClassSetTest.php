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

