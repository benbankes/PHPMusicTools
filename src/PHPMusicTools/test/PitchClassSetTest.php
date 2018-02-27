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
	 * @dataProvider provider_forteNumber
	 */
	public function test_forteNumber($tones, $expected) {
		$pcs = \ianring\PitchClassSet::constructFromTones($tones);
		$actual = $pcs->forteNumber();
		$this->assertEquals($actual, $expected);
	}
	public function provider_forteNumber() {
		return array(
			// all the primes should return the right forte number
			array('tones' => array(),		'expected' => '0-1'),

			array('tones' => array(0),		'expected' => '1-1'),

			array('tones' => array(0, 1),		'expected' => '2-1'),
			array('tones' => array(0, 2),		'expected' => '2-2'),
			array('tones' => array(0, 3),		'expected' => '2-3'),
			array('tones' => array(0, 4),		'expected' => '2-4'),
			array('tones' => array(0, 5),		'expected' => '2-5'),
			array('tones' => array(0, 6),		'expected' => '2-6'),

			array('tones' => array(0, 1, 2),		'expected' => '3-1'),
			array('tones' => array(0, 1, 3),		'expected' => '3-2'),
			array('tones' => array(0, 2, 3),		'expected' => '3-2'),
			array('tones' => array(0, 1, 4),		'expected' => '3-3'),
			array('tones' => array(0, 3, 4),		'expected' => '3-3'),
			array('tones' => array(0, 1, 5),		'expected' => '3-4'),
			array('tones' => array(0, 4, 5),		'expected' => '3-4'),
			array('tones' => array(0, 1, 6),		'expected' => '3-5'),
			array('tones' => array(0, 5, 6),		'expected' => '3-5'),
			array('tones' => array(0, 2, 4),		'expected' => '3-6'),
			array('tones' => array(0, 2, 5),		'expected' => '3-7'),
			array('tones' => array(0, 3, 5),		'expected' => '3-7'),
			array('tones' => array(0, 2, 6),		'expected' => '3-8'),
			array('tones' => array(0, 4, 6),		'expected' => '3-8'),
			array('tones' => array(0, 2, 7),		'expected' => '3-9'),
			array('tones' => array(0, 3, 6),		'expected' => '3-10'),
			array('tones' => array(0, 3, 7),		'expected' => '3-11'),
			array('tones' => array(0, 4, 7),		'expected' => '3-11'),
			array('tones' => array(0, 4, 8),		'expected' => '3-12'),

			array('tones' => array(0, 1, 2, 3),		'expected' => '4-1'),
			array('tones' => array(0, 1, 2, 4),		'expected' => '4-2'),
			array('tones' => array(0, 2, 3, 4),		'expected' => '4-2'),
			array('tones' => array(0, 1, 3, 4),		'expected' => '4-3'),
			array('tones' => array(0, 1, 2, 5),		'expected' => '4-4'),
			array('tones' => array(0, 3, 4, 5),		'expected' => '4-4'),
			array('tones' => array(0, 1, 2, 6),		'expected' => '4-5'),
			array('tones' => array(0, 4, 5, 6),		'expected' => '4-5'),
			array('tones' => array(0, 1, 2, 7),		'expected' => '4-6'),
			array('tones' => array(0, 1, 4, 5),		'expected' => '4-7'),
			array('tones' => array(0, 1, 5, 6),		'expected' => '4-8'),
			array('tones' => array(0, 1, 6, 7),		'expected' => '4-9'),
			array('tones' => array(0, 2, 3, 5),		'expected' => '4-10'),
			array('tones' => array(0, 1, 3, 5),		'expected' => '4-11'),
			array('tones' => array(0, 2, 4, 5),		'expected' => '4-11'),
			array('tones' => array(0, 2, 3, 6),		'expected' => '4-12'),
			array('tones' => array(0, 3, 4, 6),		'expected' => '4-12'),
			array('tones' => array(0, 1, 3, 6),		'expected' => '4-13'),
			array('tones' => array(0, 3, 5, 6),		'expected' => '4-13'),
			array('tones' => array(0, 2, 3, 7),		'expected' => '4-14'),
			array('tones' => array(0, 4, 5, 7),		'expected' => '4-14'),
			array('tones' => array(0, 1, 4, 6),		'expected' => '4-Z15'),
			array('tones' => array(0, 2, 5, 6),		'expected' => '4-Z15'),
			array('tones' => array(0, 1, 5, 7),		'expected' => '4-16'),
			array('tones' => array(0, 2, 6, 7),		'expected' => '4-16'),
			array('tones' => array(0, 3, 4, 7),		'expected' => '4-17'),
			array('tones' => array(0, 1, 4, 7),		'expected' => '4-18'),
			array('tones' => array(0, 3, 6, 7),		'expected' => '4-18'),
			array('tones' => array(0, 1, 4, 8),		'expected' => '4-19'),
			array('tones' => array(0, 3, 4, 8),		'expected' => '4-19'),
			array('tones' => array(0, 1, 5, 8),		'expected' => '4-20'),
			array('tones' => array(0, 2, 4, 6),		'expected' => '4-21'),
			array('tones' => array(0, 2, 4, 7),		'expected' => '4-22'),
			array('tones' => array(0, 3, 5, 7),		'expected' => '4-22'),
			array('tones' => array(0, 2, 5, 7),		'expected' => '4-23'),
			array('tones' => array(0, 2, 4, 8),		'expected' => '4-24'),
			array('tones' => array(0, 2, 6, 8),		'expected' => '4-25'),
			array('tones' => array(0, 3, 5, 8),		'expected' => '4-26'),
			array('tones' => array(0, 2, 5, 8),		'expected' => '4-27'),
			array('tones' => array(0, 3, 6, 8),		'expected' => '4-27'),
			array('tones' => array(0, 3, 6, 9),		'expected' => '4-28'),
			array('tones' => array(0, 1, 3, 7),		'expected' => '4-Z29'),
			array('tones' => array(0, 4, 6, 7),		'expected' => '4-Z29'),

			array('tones' => array(0, 1, 2, 3, 4),		'expected' => '5-1'),
			array('tones' => array(0, 1, 2, 3, 5),		'expected' => '5-2'),
			array('tones' => array(0, 2, 3, 4, 5),		'expected' => '5-2'),
			array('tones' => array(0, 1, 2, 4, 5),		'expected' => '5-3'),
			array('tones' => array(0, 1, 3, 4, 5),		'expected' => '5-3'),
			array('tones' => array(0, 1, 2, 3, 6),		'expected' => '5-4'),
			array('tones' => array(0, 3, 4, 5, 6),		'expected' => '5-4'),
			array('tones' => array(0, 1, 2, 3, 7),		'expected' => '5-5'),
			array('tones' => array(0, 4, 5, 6, 7),		'expected' => '5-5'),
			array('tones' => array(0, 1, 2, 5, 6),		'expected' => '5-6'),
			array('tones' => array(0, 1, 4, 5, 6),		'expected' => '5-6'),
			array('tones' => array(0, 1, 2, 6, 7),		'expected' => '5-7'),
			array('tones' => array(0, 1, 5, 6, 7),		'expected' => '5-7'),
			array('tones' => array(0, 2, 3, 4, 6),		'expected' => '5-8'),
			array('tones' => array(0, 1, 2, 4, 6),		'expected' => '5-9'),
			array('tones' => array(0, 2, 4, 5, 6),		'expected' => '5-9'),
			array('tones' => array(0, 1, 3, 4, 6),		'expected' => '5-10'),
			array('tones' => array(0, 2, 3, 5, 6),		'expected' => '5-10'),
			array('tones' => array(0, 2, 3, 4, 7),		'expected' => '5-11'),
			array('tones' => array(0, 3, 4, 5, 7),		'expected' => '5-11'),
			array('tones' => array(0, 1, 3, 5, 6),		'expected' => '5-Z12'),
			array('tones' => array(0, 1, 2, 4, 8),		'expected' => '5-13'),
			array('tones' => array(0, 2, 3, 4, 8),		'expected' => '5-13'),
			array('tones' => array(0, 1, 2, 5, 7),		'expected' => '5-14'),
			array('tones' => array(0, 2, 5, 6, 7),		'expected' => '5-14'),
			array('tones' => array(0, 1, 2, 6, 8),		'expected' => '5-15'),
			array('tones' => array(0, 1, 3, 4, 7),		'expected' => '5-16'),
			array('tones' => array(0, 3, 4, 6, 7),		'expected' => '5-16'),
			array('tones' => array(0, 1, 3, 4, 8),		'expected' => '5-Z17'),
			array('tones' => array(0, 1, 4, 5, 7),		'expected' => '5-Z18'),
			array('tones' => array(0, 2, 3, 6, 7),		'expected' => '5-Z18'),
			array('tones' => array(0, 1, 3, 6, 7),		'expected' => '5-19'),
			array('tones' => array(0, 1, 4, 6, 7),		'expected' => '5-19'),
			array('tones' => array(0, 1, 3, 7, 8),		'expected' => '5-20'),
			array('tones' => array(0, 1, 5, 7, 8),		'expected' => '5-20'),
			array('tones' => array(0, 1, 4, 5, 8),		'expected' => '5-21'),
			array('tones' => array(0, 3, 4, 7, 8),		'expected' => '5-21'),
			array('tones' => array(0, 1, 4, 7, 8),		'expected' => '5-22'),
			array('tones' => array(0, 2, 3, 5, 7),		'expected' => '5-23'),
			array('tones' => array(0, 2, 4, 5, 7),		'expected' => '5-23'),
			array('tones' => array(0, 1, 3, 5, 7),		'expected' => '5-24'),
			array('tones' => array(0, 2, 4, 6, 7),		'expected' => '5-24'),
			array('tones' => array(0, 2, 3, 5, 8),		'expected' => '5-25'),
			array('tones' => array(0, 3, 5, 6, 8),		'expected' => '5-25'),
			array('tones' => array(0, 2, 4, 5, 8),		'expected' => '5-26'),
			array('tones' => array(0, 3, 4, 6, 8),		'expected' => '5-26'),
			array('tones' => array(0, 1, 3, 5, 8),		'expected' => '5-27'),
			array('tones' => array(0, 3, 5, 7, 8),		'expected' => '5-27'),
			array('tones' => array(0, 2, 3, 6, 8),		'expected' => '5-28'),
			array('tones' => array(0, 2, 5, 6, 8),		'expected' => '5-28'),
			array('tones' => array(0, 1, 3, 6, 8),		'expected' => '5-29'),
			array('tones' => array(0, 2, 5, 7, 8),		'expected' => '5-29'),
			array('tones' => array(0, 1, 4, 6, 8),		'expected' => '5-30'),
			array('tones' => array(0, 2, 4, 7, 8),		'expected' => '5-30'),
			array('tones' => array(0, 1, 3, 6, 9),		'expected' => '5-31'),
			array('tones' => array(0, 2, 3, 6, 9),		'expected' => '5-31'),
			array('tones' => array(0, 1, 4, 6, 9),		'expected' => '5-32'),
			array('tones' => array(0, 1, 4, 7, 9),		'expected' => '5-32'),
			array('tones' => array(0, 2, 4, 6, 8),		'expected' => '5-33'),
			array('tones' => array(0, 2, 4, 6, 9),		'expected' => '5-34'),
			array('tones' => array(0, 2, 4, 7, 9),		'expected' => '5-35'),
			array('tones' => array(0, 1, 2, 4, 7),		'expected' => '5-Z36'),
			array('tones' => array(0, 3, 5, 6, 7),		'expected' => '5-Z36'),
			array('tones' => array(0, 3, 4, 5, 8),		'expected' => '5-Z37'),
			array('tones' => array(0, 1, 2, 5, 8),		'expected' => '5-Z38'),
			array('tones' => array(0, 3, 6, 7, 8),		'expected' => '5-Z38'),

			array('tones' => array(0, 1, 2, 3, 4, 5),		'expected' => '6-1'),
			array('tones' => array(0, 1, 2, 3, 4, 6),		'expected' => '6-2'),
			array('tones' => array(0, 2, 3, 4, 5, 6),		'expected' => '6-2'),
			array('tones' => array(0, 1, 2, 3, 5, 6),		'expected' => '6-Z3'),
			array('tones' => array(0, 1, 3, 4, 5, 6),		'expected' => '6-Z3'),
			array('tones' => array(0, 1, 2, 4, 5, 6),		'expected' => '6-Z4'),
			array('tones' => array(0, 1, 2, 3, 6, 7),		'expected' => '6-5'),
			array('tones' => array(0, 1, 4, 5, 6, 7),		'expected' => '6-5'),
			array('tones' => array(0, 1, 2, 5, 6, 7),		'expected' => '6-Z6'),
			array('tones' => array(0, 1, 2, 6, 7, 8),		'expected' => '6-7'),
			array('tones' => array(0, 2, 3, 4, 5, 7),		'expected' => '6-8'),
			array('tones' => array(0, 1, 2, 3, 5, 7),		'expected' => '6-9'),
			array('tones' => array(0, 2, 4, 5, 6, 7),		'expected' => '6-9'),
			array('tones' => array(0, 1, 3, 4, 5, 7),		'expected' => '6-Z10'), // this one is prime
			array('tones' => array(0, 2, 3, 4, 6, 7),		'expected' => '6-Z10'), // this one is not prime
			array('tones' => array(0, 1, 2, 4, 5, 7),		'expected' => '6-Z11'),
			array('tones' => array(0, 2, 3, 5, 6, 7),		'expected' => '6-Z11'),
			array('tones' => array(0, 1, 2, 4, 6, 7),		'expected' => '6-Z12'),
			array('tones' => array(0, 1, 3, 5, 6, 7),		'expected' => '6-Z12'),
			array('tones' => array(0, 1, 3, 4, 6, 7),		'expected' => '6-Z13'),
			array('tones' => array(0, 1, 3, 4, 5, 8),		'expected' => '6-14'),
			array('tones' => array(0, 3, 4, 5, 7, 8),		'expected' => '6-14'),
			array('tones' => array(0, 1, 2, 4, 5, 8),		'expected' => '6-15'),
			array('tones' => array(0, 3, 4, 6, 7, 8),		'expected' => '6-15'),
			array('tones' => array(0, 1, 4, 5, 6, 8),		'expected' => '6-16'),
			array('tones' => array(0, 2, 3, 4, 7, 8),		'expected' => '6-16'),
			array('tones' => array(0, 1, 2, 4, 7, 8),		'expected' => '6-Z17'),
			array('tones' => array(0, 1, 4, 6, 7, 8),		'expected' => '6-Z17'),
			array('tones' => array(0, 1, 2, 5, 7, 8),		'expected' => '6-18'),
			array('tones' => array(0, 1, 3, 6, 7, 8),		'expected' => '6-18'),
			array('tones' => array(0, 1, 3, 4, 7, 8),		'expected' => '6-Z19'),
			array('tones' => array(0, 1, 4, 5, 7, 8),		'expected' => '6-Z19'),
			array('tones' => array(0, 1, 4, 5, 8, 9),		'expected' => '6-20'),
			array('tones' => array(0, 2, 3, 4, 6, 8),		'expected' => '6-21'),
			array('tones' => array(0, 2, 4, 5, 6, 8),		'expected' => '6-21'),
			array('tones' => array(0, 1, 2, 4, 6, 8),		'expected' => '6-22'),
			array('tones' => array(0, 2, 4, 6, 7, 8),		'expected' => '6-22'),
			array('tones' => array(0, 2, 3, 5, 6, 8),		'expected' => '6-Z23'),
			array('tones' => array(0, 1, 3, 4, 6, 8),		'expected' => '6-Z24'),
			array('tones' => array(0, 2, 4, 5, 7, 8),		'expected' => '6-Z24'),
			array('tones' => array(0, 1, 3, 5, 6, 8),		'expected' => '6-Z25'),
			array('tones' => array(0, 2, 3, 5, 7, 8),		'expected' => '6-Z25'),
			array('tones' => array(0, 1, 3, 5, 7, 8),		'expected' => '6-Z26'),
			array('tones' => array(0, 1, 3, 4, 6, 9),		'expected' => '6-27'),
			array('tones' => array(0, 2, 3, 5, 6, 9),		'expected' => '6-27'),
			array('tones' => array(0, 1, 3, 5, 6, 9),		'expected' => '6-Z28'),
			array('tones' => array(0, 1, 3, 6, 8, 9),		'expected' => '6-Z29'),
			array('tones' => array(0, 1, 3, 6, 7, 9),		'expected' => '6-30'),
			array('tones' => array(0, 2, 3, 6, 8, 9),		'expected' => '6-30'),
			array('tones' => array(0, 1, 3, 5, 8, 9),		'expected' => '6-31'),
			array('tones' => array(0, 1, 4, 6, 8, 9),		'expected' => '6-31'),
			array('tones' => array(0, 2, 4, 5, 7, 9),		'expected' => '6-32'),
			array('tones' => array(0, 2, 3, 5, 7, 9),		'expected' => '6-33'),
			array('tones' => array(0, 2, 4, 6, 7, 9),		'expected' => '6-33'),
			array('tones' => array(0, 1, 3, 5, 7, 9),		'expected' => '6-34'),
			array('tones' => array(0, 2, 4, 6, 8, 9),		'expected' => '6-34'),
			array('tones' => array(0, 2, 4, 6, 8, 10),		'expected' => '6-35'),
			array('tones' => array(0, 1, 2, 3, 4, 7),		'expected' => '6-Z36'),
			array('tones' => array(0, 3, 4, 5, 6, 7),		'expected' => '6-Z36'),
			array('tones' => array(0, 1, 2, 3, 4, 8),		'expected' => '6-Z37'),
			array('tones' => array(0, 1, 2, 3, 7, 8),		'expected' => '6-Z38'),
			array('tones' => array(0, 2, 3, 4, 5, 8),		'expected' => '6-Z39'),
			array('tones' => array(0, 3, 4, 5, 6, 8),		'expected' => '6-Z39'),
			array('tones' => array(0, 1, 2, 3, 5, 8),		'expected' => '6-Z40'),
			array('tones' => array(0, 3, 5, 6, 7, 8),		'expected' => '6-Z40'),
			array('tones' => array(0, 1, 2, 3, 6, 8),		'expected' => '6-Z41'),
			array('tones' => array(0, 2, 5, 6, 7, 8),		'expected' => '6-Z41'),
			array('tones' => array(0, 1, 2, 3, 6, 9),		'expected' => '6-Z42'),
			array('tones' => array(0, 1, 2, 5, 6, 8),		'expected' => '6-Z43'),
			array('tones' => array(0, 2, 3, 6, 7, 8),		'expected' => '6-Z43'),
			array('tones' => array(0, 1, 2, 5, 6, 9),		'expected' => '6-Z44'),
			array('tones' => array(0, 1, 2, 5, 8, 9),		'expected' => '6-Z44'),
			array('tones' => array(0, 2, 3, 4, 6, 9),		'expected' => '6-Z45'),
			array('tones' => array(0, 1, 2, 4, 6, 9),		'expected' => '6-Z46'),
			array('tones' => array(0, 2, 4, 5, 6, 9),		'expected' => '6-Z46'),
			array('tones' => array(0, 1, 2, 4, 7, 9),		'expected' => '6-Z47'),
			array('tones' => array(0, 2, 3, 4, 7, 9),		'expected' => '6-Z47'),
			array('tones' => array(0, 1, 2, 5, 7, 9),		'expected' => '6-Z48'),
			array('tones' => array(0, 1, 3, 4, 7, 9),		'expected' => '6-Z49'),
			array('tones' => array(0, 1, 4, 6, 7, 9),		'expected' => '6-Z50'),

			array('tones' => array(0, 1, 2, 3, 4, 5, 6),		'expected' => '7-1'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7),		'expected' => '7-2'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 7),		'expected' => '7-2'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 8),		'expected' => '7-3'),
			array('tones' => array(0, 3, 4, 5, 6, 7, 8),		'expected' => '7-3'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7),		'expected' => '7-4'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 7),		'expected' => '7-4'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 7),		'expected' => '7-5'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 7),		'expected' => '7-5'),
			array('tones' => array(0, 1, 2, 3, 4, 7, 8),		'expected' => '7-6'),
			array('tones' => array(0, 1, 4, 5, 6, 7, 8),		'expected' => '7-6'),
			array('tones' => array(0, 1, 2, 3, 6, 7, 8),		'expected' => '7-7'),
			array('tones' => array(0, 1, 2, 5, 6, 7, 8),		'expected' => '7-7'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 8),		'expected' => '7-8'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 8),		'expected' => '7-9'),
			array('tones' => array(0, 2, 4, 5, 6, 7, 8),		'expected' => '7-9'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 9),		'expected' => '7-10'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 9),		'expected' => '7-10'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 8),		'expected' => '7-11'),
			array('tones' => array(0, 2, 3, 4, 5, 7, 8),		'expected' => '7-11'),
			array('tones' => array(0, 1, 2, 3, 4, 7, 9),		'expected' => '7-Z12'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 8),		'expected' => '7-13'),
			array('tones' => array(0, 2, 3, 4, 6, 7, 8),		'expected' => '7-13'),
			array('tones' => array(0, 1, 2, 3, 5, 7, 8),		'expected' => '7-14'),
			array('tones' => array(0, 1, 3, 5, 6, 7, 8),		'expected' => '7-14'),
			array('tones' => array(0, 1, 2, 4, 6, 7, 8),		'expected' => '7-15'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 9),		'expected' => '7-16'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 9),		'expected' => '7-16'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 9),		'expected' => '7-Z17'),
			array('tones' => array(0, 1, 2, 3, 5, 8, 9),		'expected' => '7-Z18'),
			array('tones' => array(0, 1, 4, 6, 7, 8, 9),		'expected' => '7-Z18'),
			array('tones' => array(0, 1, 2, 3, 6, 7, 9),		'expected' => '7-19'),
			array('tones' => array(0, 1, 2, 3, 6, 8, 9),		'expected' => '7-19'),
			array('tones' => array(0, 1, 2, 4, 7, 8, 9),		'expected' => '7-20'),
			array('tones' => array(0, 1, 2, 5, 7, 8, 9),		'expected' => '7-20'),
			array('tones' => array(0, 1, 2, 4, 5, 8, 9),		'expected' => '7-21'),
			array('tones' => array(0, 1, 3, 4, 5, 8, 9),		'expected' => '7-21'),
			array('tones' => array(0, 1, 2, 5, 6, 8, 9),		'expected' => '7-22'),
			array('tones' => array(0, 2, 3, 4, 5, 7, 9),		'expected' => '7-23'),
			array('tones' => array(0, 2, 4, 5, 6, 7, 9),		'expected' => '7-23'),
			array('tones' => array(0, 1, 2, 3, 5, 7, 9),		'expected' => '7-24'),
			array('tones' => array(0, 2, 4, 6, 7, 8, 9),		'expected' => '7-24'),
			array('tones' => array(0, 2, 3, 4, 6, 7, 9),		'expected' => '7-25'),
			array('tones' => array(0, 2, 3, 5, 6, 7, 9),		'expected' => '7-25'),
			array('tones' => array(0, 1, 3, 4, 5, 7, 9),		'expected' => '7-26'),
			array('tones' => array(0, 2, 4, 5, 6, 8, 9),		'expected' => '7-26'),
			array('tones' => array(0, 1, 2, 4, 5, 7, 9),		'expected' => '7-27'),
			array('tones' => array(0, 2, 4, 5, 7, 8, 9),		'expected' => '7-27'),
			array('tones' => array(0, 1, 3, 5, 6, 7, 9),		'expected' => '7-28'),
			array('tones' => array(0, 2, 3, 4, 6, 8, 9),		'expected' => '7-28'),
			array('tones' => array(0, 1, 2, 4, 6, 7, 9),		'expected' => '7-29'),
			array('tones' => array(0, 2, 3, 5, 7, 8, 9),		'expected' => '7-29'),
			array('tones' => array(0, 1, 2, 4, 6, 8, 9),		'expected' => '7-30'),
			array('tones' => array(0, 1, 3, 5, 7, 8, 9),		'expected' => '7-30'),
			array('tones' => array(0, 1, 3, 4, 6, 7, 9),		'expected' => '7-31'),
			array('tones' => array(0, 2, 3, 5, 6, 8, 9),		'expected' => '7-31'),
			array('tones' => array(0, 1, 3, 4, 6, 8, 9),		'expected' => '7-32'),
			array('tones' => array(0, 1, 3, 5, 6, 8, 9),		'expected' => '7-32'),
			array('tones' => array(0, 1, 2, 4, 6, 8, 10),		'expected' => '7-33'),
			array('tones' => array(0, 1, 3, 4, 6, 8, 10),		'expected' => '7-34'),
			array('tones' => array(0, 1, 3, 5, 6, 8, 10),		'expected' => '7-35'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 8),		'expected' => '7-Z36'),
			array('tones' => array(0, 2, 3, 5, 6, 7, 8),		'expected' => '7-Z36'),
			array('tones' => array(0, 1, 3, 4, 5, 7, 8),		'expected' => '7-Z37'),
			array('tones' => array(0, 1, 2, 4, 5, 7, 8),		'expected' => '7-Z38'),
			array('tones' => array(0, 1, 3, 4, 6, 7, 8),		'expected' => '7-Z38'),

			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7),		'expected' => '8-1'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 8),		'expected' => '8-2'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 7, 8),		'expected' => '8-2'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 9),		'expected' => '8-3'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7, 8),		'expected' => '8-4'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 7, 8),		'expected' => '8-4'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7, 8),		'expected' => '8-5'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 7, 8),		'expected' => '8-5'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 7, 8),		'expected' => '8-6'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 8, 9),		'expected' => '8-7'),
			array('tones' => array(0, 1, 2, 3, 4, 7, 8, 9),		'expected' => '8-8'),
			array('tones' => array(0, 1, 2, 3, 6, 7, 8, 9),		'expected' => '8-9'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 7, 9),		'expected' => '8-10'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7, 9),		'expected' => '8-11'),
			array('tones' => array(0, 2, 4, 5, 6, 7, 8, 9),		'expected' => '8-11'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 7, 9),		'expected' => '8-12'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 8, 9),		'expected' => '8-12'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7, 9),		'expected' => '8-13'),
			array('tones' => array(0, 2, 3, 5, 6, 7, 8, 9),		'expected' => '8-13'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 7, 9),		'expected' => '8-14'),
			array('tones' => array(0, 2, 3, 4, 5, 7, 8, 9),		'expected' => '8-14'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 8, 9),		'expected' => '8-Z15'),
			array('tones' => array(0, 1, 3, 5, 6, 7, 8, 9),		'expected' => '8-Z15'),
			array('tones' => array(0, 1, 2, 3, 5, 7, 8, 9),		'expected' => '8-16'),
			array('tones' => array(0, 1, 2, 4, 6, 7, 8, 9),		'expected' => '8-16'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 8, 9),		'expected' => '8-17'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 8, 9),		'expected' => '8-18'),
			array('tones' => array(0, 1, 3, 4, 6, 7, 8, 9),		'expected' => '8-18'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 8, 9),		'expected' => '8-19'),
			array('tones' => array(0, 1, 3, 4, 5, 7, 8, 9),		'expected' => '8-19'),
			array('tones' => array(0, 1, 2, 4, 5, 7, 8, 9),		'expected' => '8-20'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 8, 10),		'expected' => '8-21'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 8, 10),		'expected' => '8-22'),
			array('tones' => array(0, 1, 2, 3, 5, 7, 9, 10),		'expected' => '8-22'),
			array('tones' => array(0, 1, 2, 3, 5, 7, 8, 10),		'expected' => '8-23'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 8, 10),		'expected' => '8-24'),
			array('tones' => array(0, 1, 2, 4, 6, 7, 8, 10),		'expected' => '8-25'),
			array('tones' => array(0, 1, 2, 4, 5, 7, 9, 10),		'expected' => '8-26'),
			array('tones' => array(0, 1, 2, 4, 5, 7, 8, 10),		'expected' => '8-27'),
			array('tones' => array(0, 1, 2, 4, 6, 7, 9, 10),		'expected' => '8-27'),
			array('tones' => array(0, 1, 3, 4, 6, 7, 9, 10),		'expected' => '8-28'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 7, 9),			'expected' => '8-Z29'),
			array('tones' => array(0, 2, 3, 4, 6, 7, 8, 9),			'expected' => '8-Z29'),

			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 8),		'expected' => '9-1'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 9),		'expected' => '9-2'),
			array('tones' => array(0, 2, 3, 4, 5, 6, 7, 8, 9),		'expected' => '9-2'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 8, 9),		'expected' => '9-3'),
			array('tones' => array(0, 1, 3, 4, 5, 6, 7, 8, 9),		'expected' => '9-3'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7, 8, 9),		'expected' => '9-4'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 7, 8, 9),		'expected' => '9-4'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7, 8, 9),		'expected' => '9-5'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 7, 8, 9),		'expected' => '9-5'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 8, 10),		'expected' => '9-6'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7, 8, 10),		'expected' => '9-7'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7, 9, 10),		'expected' => '9-7'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7, 8, 10),		'expected' => '9-8'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 8, 9, 10),		'expected' => '9-8'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 7, 8, 10),		'expected' => '9-9'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7, 9, 10),		'expected' => '9-10'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 7, 9, 10),		'expected' => '9-11'),
			array('tones' => array(0, 1, 2, 3, 5, 6, 8, 9, 10),		'expected' => '9-11'),
			array('tones' => array(0, 1, 2, 4, 5, 6, 8, 9, 10),		'expected' => '9-12'),

			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),		'expected' => '10-1'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 10),		'expected' => '10-2'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 9, 10),		'expected' => '10-3'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 8, 9, 10),		'expected' => '10-4'),
			array('tones' => array(0, 1, 2, 3, 4, 5, 7, 8, 9, 10),		'expected' => '10-5'),
			array('tones' => array(0, 1, 2, 3, 4, 6, 7, 8, 9, 10),		'expected' => '10-6'),

			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),		'expected' => '11-1'),

			array('tones' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11),		'expected' => '12-1'),

			// we must also test some non-primes...
			array('tones' => array(0, 3, 4, 5, 7, 9),		'expected' => '6-Z46'), 	// P = (0,1,2,4,6,9)
			array('tones' => array(0, 3, 4, 5, 6, 7, 9),	'expected' => '7-10'), 		// P = (0,1,2,3,4,6,9)
			array('tones' => array(0, 4, 5, 6, 7, 9),		'expected' => '6-Z40'), 	// P = (0,1,2,3,5,8)
			array('tones' => array(0, 4, 5, 7, 9),			'expected' => '5-27'), 		// P = (0,1,3,5,8)

			array('tones' => array(3, 5, 6, 8),				'expected' => '4-10'), 		// P = (0,2,3,5)
			array('tones' => array(8, 9, 10),				'expected' => '3-1'), 		// P = (0,1,2)
			array('tones' => array(1, 3, 5, 7, 9, 11),		'expected' => '6-35'), 		// P = (0,2,4,6,8,10)
			array('tones' => array(11),						'expected' => '1-1'), 		// P = (0)
			array('tones' => array(10),						'expected' => '1-1'), 		// P = (0)
			array('tones' => array(9),						'expected' => '1-1'), 		// P = (0)
			array('tones' => array(5),						'expected' => '1-1'), 		// P = (0)
			array('tones' => array(3,5,7,9),				'expected' => '4-21'), 		// P = (0,2,4,6)

		);
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
	 * @dataProvider provider_isCoherent
	 */
	public function test_isCoherent($input, $expected){
		$pcs = new \ianring\PitchClassSet($input);
		$actual = $pcs->isCoherent();
		$this->assertEquals($expected, $actual);
	}
	public function provider_isCoherent() {
		return array(
			array('input' => 661, 'expected' => true),
			array('input' => 1193, 'expected' => true),
			array('input' => 1387, 'expected' => false),
			array('input' => 1451, 'expected' => false),
			array('input' => 1453, 'expected' => false),
			array('input' => 1709, 'expected' => false),
			array('input' => 1717, 'expected' => false),
			array('input' => 2741, 'expected' => false),
			array('input' => 2773, 'expected' => false),

			array('input' => 273, 'expected' => true),
			array('input' => 585, 'expected' => true),
			array('input' => 859, 'expected' => false),
			array('input' => 1257, 'expected' => false),
			array('input' => 1365, 'expected' => true),
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
			array('input' => 1755, 'expected' => true),
			array('input' => 2257, 'expected' => false),
			array('input' => 2275, 'expected' => false),
			array('input' => 2457, 'expected' => true),
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
			array('input' => 2925, 'expected' => true),
			array('input' => 2989, 'expected' => false),
			array('input' => 2997, 'expected' => false),
			array('input' => 3055, 'expected' => false),
			array('input' => 3411, 'expected' => false),
			array('input' => 3445, 'expected' => false),
			array('input' => 3549, 'expected' => false),
			array('input' => 3669, 'expected' => false),
			array('input' => 3765, 'expected' => false),
			array('input' => 4095, 'expected' => true),
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


	/**
	 * @dataProvider provider_scalarTranspose
	 */
	public function test_scalarTranspose($set, $subset, $interval, $expected) {
		$actual = \ianring\PitchClassSet::scalarTranspose($set, $subset, $interval);
		$this->assertEquals($expected, $actual);
	}
	public function provider_scalarTranspose() {
		return array(
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 0,
				'expected' => array(0,2,4)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 1,
				'expected' => array(2,4,5)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 2,
				'expected' => array(4,5,7)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 3,
				'expected' => array(5,7,9)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 4,
				'expected' => array(7,9,11)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 5,
				'expected' => array(9,11,0) // here's where the order starts to matter
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 6,
				'expected' => array(11,0,2)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => 7,
				'expected' => array(0,2,4)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => -1,
				'expected' => array(11,0,2)
			),
			array(
				'set' => 2741, 
				'subset' => 21,
				'interval' => -2,
				'expected' => array(9,11,0)
			),
			array(
				'set' => 2741, 
				'subset' => 13,
				'interval' => 0,
				'expected' => false
			),
			array(
				'set' => 1365, // whole-tone
				'subset' => 273,
				'interval' => 1,
				'expected' => array(2,6,10)
			),
			array(
				'set' => 1365, // whole-tone
				'subset' => 273,
				'interval' => -1,
				'expected' => array(10,2,6)
			),
			array(
				'set' => 1365, // whole-tone
				'subset' => 1092,
				'interval' => 1,
				'expected' => array(4,8,0)
			),
			array(
				'set' => 1365, // whole-tone
				'subset' => 1092,
				'interval' => -1,
				'expected' => array(0,4,8)
			),
			array(
				'set' => 2047,
				'subset' => 1023,
				'interval' => 2,
				'expected' => array(2,3,4,5,6,7,8,9,10,0)
			),
		);
	}


	/**
	 * @dataProvider provider_getScalarTranspositions
	 */
	public function test_getScalarTranspositions($set, $subset, $expected) {
		$actual = \ianring\PitchClassSet::getScalarTranspositions($set, $subset);
		$this->assertEquals($expected, $actual);
	}
	public function provider_getScalarTranspositions() {
		return array(
			array(
				'set' => 2741, // major
				'subset' => 21,
				'expected' => array(
					array(0,2,4),
					array(2,4,5),
					array(4,5,7),
					array(5,7,9),
					array(7,9,11),
					array(9,11,0),
					array(11,0,2)
				),
			),
			array(
				'set' => 1365, // whole-tone
				'subset' => 273,
				'expected' => array(
					array(0,4,8),
					array(2,6,10),
					array(4,8,0),
					array(6,10,2),
					array(8,0,4),
					array(10,2,6),
				)
			),
		);
	}


	/**
	 * @dataProvider provider_getIntervalsBetweenTones
	 */
	public function test_getIntervalsBetweenTones($tones, $expected) {
		$actual = \ianring\PitchClassSet::getIntervalsBetweenTones($tones);
		$this->assertEquals($expected, $actual);
	}
	public function provider_getIntervalsBetweenTones() {
		return array(
			'major triad' => array(
				'tones' => array(0,4,7), // major triad
				'expected' => array(4,3,5) // major third, and minor third, perfect 4th
			),
			'minor triad' => array(
				'tones' => array(0,3,7), // minor triad
				'expected' => array(3,4,5) // minor third, and major third, perfect 4th
			),
			array(
				'tones' => array(5,9,2),
				'expected' => array(4,5,3)
			),
			array(
				'tones' => array(10,3,4),
				'expected' => array(5,1,6)
			),
		);
	}


	/**
	 * @dataProvider provider_getVariety
	 */
	public function test_getVariety($set, $subset, $expected) {
		$actual = \ianring\PitchClassSet::getVariety($set, $subset);
		$this->assertEquals($expected, $actual);
	}
	public function provider_getVariety() {
		return array(
			'major triad' => array(
				'set' => 2741, // major scale
				'subset' => 145, // major triad
				'expected' => 3
			),
			array(
				'set' => 2741, // major scale
				'subset' => 37,
				'expected' => 3
			),
			array(
				'set' => 2741, // major scale
				'subset' => 549,
				'expected' => 4
			),
			array(
				'set' => 2741, // major scale
				'subset' => 2228,
				'expected' => 5
			),
		);
	}


}

