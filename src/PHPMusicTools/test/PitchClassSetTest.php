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
		include('allpitchclasses2.php');
		return $sets;
	}


	/**
	 * @dataProvider provider_primeFormForte
	 */
	public function test_primeFormForte($bitmask, $id, $forte, $mirror, $prime, $vector, $name) {
		$pcs = new \ianring\PitchClassSet($bitmask);
		$pf = $pcs->primeFormForte();
		$expected = \ianring\BitmaskUtils::tones2Bits($prime);
		$this->assertEquals($expected, $pf);
	}
	public function provider_primeFormForte() {
		include('allpitchclasses2.php');
		return $sets;
	}


	/**
	 * 
	 */
	public function test_fortePrimes() {
		include('forteprimes.php');
		$allsets = range(0, 4095);
		foreach($allsets as $set) {
			$pcs = new \ianring\PitchClassSet($set);
			$prime = $pcs->primeFormForte();
			// if this one is prime, then it should be in the enummed collection
			if ($set == $prime) {
				$this->assertContains($set, $allforteprimes, 'this prime isnt in the collection');
			} else {
				$this->assertNotContains($set, $allforteprimes, 'this one is not prime so it should not be in the collection');
				$this->assertContains($prime, $allforteprimes, 'the prime of this one is missing from the collection');
			}
		}
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

}

