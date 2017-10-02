<?php

require_once 'PHPMusicToolsTest.php';
require_once __DIR__.'/../classes/Utils/BitmaskUtils.php';

class BitmaskUtilsTest extends PHPMusicToolsTest
{
	
	protected function setUp(){
	}

	/**
	 * @dataProvider provider_bits2Tones
	 */
	public function test_bits2Tones($bits, $tones) {
		$t = \ianring\BitmaskUtils::bits2tones($bits);
		$this->assertEquals($tones, $t);
		$b = \ianring\BitmaskUtils::tones2bits($tones);
		$this->assertEquals($bits, $b);
	}
	public function provider_bits2Tones() {
		return array(
			array('bits' => 0, 'tones' => array()),
			array('bits' => 1, 'tones' => array(0)),
			array('bits' => 2, 'tones' => array(1)),
			array('bits' => 4, 'tones' => array(2)),
			array('bits' => 8, 'tones' => array(3)),
			array('bits' => 16, 'tones' => array(4)),
			array('bits' => 32, 'tones' => array(5)),
			array('bits' => 33, 'tones' => array(0,5)),
			array('bits' => 37, 'tones' => array(0,2,5)),
			array('bits' => 4095, 'tones' => array(0,1,2,3,4,5,6,7,8,9,10,11)),
		);
	}


}
