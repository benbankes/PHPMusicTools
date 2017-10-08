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
	 * @todo
	 * @dataProvider provider_primeFormRahn
	 */
	public function test_primeFormRahn() {

	}
	public function provider_primeFormRahn() {
		return array(
			// there are only five sets that differ from forte, and here they are:
			'5-20' => array(
				'input' => array(0,1,3,7,8),
				'expected' => array(0,1,5,6,8)
			),
			'6-Z29' => array(
				'input' => array(0,1,3,6,8,9),
				'expected' => array(0,2,3,6,7,9)
			),
			'6-31' => array(
				'input' => array(0,1,3,5,8,9),
				'expected' => array(0,1,4,5,7,9)
			),
			'7-20' => array(
				'input' => array(0,1,2,4,7,8,9),
				'expected' => array(0,1,2,5,6,7,9)
			),
			'8-26' => array(
				'input' => array(0,1,2,4,5,7,9,10),
				'expected' => array(0,1,3,4,5,7,8,10)
			),
			// and let's test some other ones too OK
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

