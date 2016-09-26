<?php
namespace ianring;
require_once 'PMTObject.php';

class Pitch extends PMTObject {
	
	public function __construct($step = 'C', $alter = 0, $octave = 4) {
		$this->step = $step;
		$this->alter = $alter;
		$this->octave = $octave;
	}

	/**
	 * accepts the object in the form of an array structure
	 * @param  [winged] $scale [description]
	 * @return [winged]        [description]
	 */
	public static function constructFromArray($props) {
		$step = $props['step'];
		$alter = $props['alter'];
		$octave = $props['octave'];
		return new Pitch($step, $alter, $octave);
	}

	/**
	 * accepts the object in the form of a string
	 * @param  [winged] $scale [description]
	 * @return [winged]        [description]
	 */
	public static function constructFromString($string) {
		$props = self::_resolvePitchString($string);
		return self::constructFromArray($props);
	}

	/**
	 * gives the interval between this pitch and the given one. 
	 * 0 means the pitches are enharmonic.
	 * < 0 means the interval is downward,
	 * > 0 means the interval is upward
	 * @param  [type] $pitch [description]
	 * @return [type]        [description]
	 */
	public function interval($pitch) {
		return self::toNoteNumber($pitch) - self::toNoteNumber($this);
	}

	/**
	 * returns true if $this is lower than the provided pitch
	 * @param  [type]  $pitch [description]
	 * @return boolean        [description]
	 */
	public function isLowerThan($pitch) {
		return $this->interval($pitch) > 0;
	}

	/**
	 * returns true if $this is higher than the provided pitch
	 * @param  [type]  $pitch [description]
	 * @return boolean        [description]
	 */
	public function isHigherThan($pitch) {
		return $this->interval($pitch) < 0;
	}
	/**
	 * returns true if $this is enharmonic with the provided pitch
	 * @param  [type]  $pitch [description]
	 * @return boolean        [description]
	 */
	public function isEnharmonic($pitch) {
		return $this->interval($pitch) == 0;
	}

	/**
	 * note the difference between this and isEnharmonic - in this one, spelling counts
	 */
	public function equals($pitch) {
		return $pitch->step == $this->step && $pitch->alter == $this->alter && $pitch->octave == $this->octave;
	}


	public static $chromas = array(
		0 => 'C',
		1 => array(
			1 => array('step' => 'C', 'alter' => 1),
			-1 => array('step' => 'D', 'alter' => -1)
		),
		2 => 'D',
		3 => array(
			1 => array('step' => 'D', 'alter' => 1),
			-1 => array('step' => 'E', 'alter' => -1),
		),
		4 => 'E',
		5 => 'F',
		6 => array(
			1 => array('step' => 'F', 'alter' => 1),
			-1 => array('step' => 'G', 'alter' => -1),
		),
		7 => 'G',
		8 => array(
			1 => array('step' => 'G', 'alter' => 1),
			-1 => array('step' => 'A', 'alter' => -1),
		),
		9 => 'A',
		10 => array(
			1 => array('step' => 'A', 'alter' => 1),
			-1 => array('step' => 'B', 'alter' => -1),
		),
		11 => 'B'
	);


	function setProperty($name, $value) {
		$this->$name = $value;
	}

	public function isHeightless() {
		return is_null($this->octave);
	}

	public function toXML() {
		if ($this->octave == null) {
			throw new Exception('heightless pitches can not be rendered as XML. Provide an "octave" property. '.print_r($this->properties, true));
		}

		$out = '<pitch>';

		$out .= '<step>' . $this->step . '</step>';
		$out .= '<alter>' . $this->alter . '</alter>';
		$out .= '<octave>' . $this->octave . '</octave>';

		$out .= '</pitch>';

		return $out;
	}

	private static function _resolvePitchString($pitch) {

		if (is_array($pitch)) {
			return $pitch;
		}

		$properties = array();

		preg_match('/([A-Ga-g+#-b]+?)(\d+)/', $pitch, $matches);
		$chroma = $matches[1];

		// there might be no octave part, if we're creating a heightless pitch, like "D#". Default to octave 4.
		$octave = null;
		if (!empty($matches[2])) {
			$octave = $matches[2];
		}

		preg_match('/([A-Ga-g]+?)(.*)/', $chroma, $matches);
		$step = $matches[1];
		switch ($matches[2]) {
			case '##':
			case '++':
				$alter = 2;
				break;
			case '#':
			case '+':
				$alter = 1;
				break;
			case 'b':
			case '-':
				$alter = -1;
				break;
			case 'bb':
			case '--':
				$alter = -2;
				break;
			default:
				$alter = 0;
		}
		$step = $step;
		$alter = $alter;
		$octave = $octave;

		return $properties;
	}


	/**
	 * transposes a Pitch up or down by $interval semitones.
	 * @param  integer  $interval  a signed integer telling how many semitones to transpose up or down
	 * @param  integer  $preferredAlteration  either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
	 * @return  null
	 */
	public function transpose($interval, $preferredAlteration = 1) {
		$isHeightless = $this->isHeightless();
		if (!in_array($preferredAlteration, array(1, -1))) {
			$preferredAlteration = 1; // default to prefer sharps.
		}
		if ($isHeightless) {
			$this->octave = 4; // just choose some arbitrary octave to use for calculations
		}
		$num = self::toNoteNumber($this);
		$num += $interval;
		$this->noteNumberToPitch($num, $preferredAlteration);

		if ($isHeightless) {
			// set it back the way it was
			$this->octave = null;
		}
	}

	public function toString() {
		$str = '';
		$str .= $this->step;
		switch ($this->alter) {
			case 1:
				$str .= '#';
				break;
			case -1:
				$str .= '-';
				break;
			default:
				break;
		}
		$str .= $this->octave;
		return $str;
	}

	/**
	 * translates a pitch properties into a signed integer, abitrarily centered with zero on middle C
	 * @param Pitch $pitch
	 * @return int pitch number, useful for doing calculcations
	 */
	public static function toNoteNumber($pitch) {
		$chromas = array('C' => 0, 'D' => 2, 'E' => 4, 'F' => 5, 'G' => 7, 'A' => 9, 'B' => 11);
		$num = ($pitch->octave - 4) * 12;
		$num += $chromas[$pitch->step];
		$num += $pitch->alter; // adds a sharp or flat, e.g. 1 = sharp, -1 = flat, -2 = double-flat...
		return $num;
	}

	/**
	 * accepts a note number and sets the pitch properties
	 * @param  integer  $noteNumber          signed integer with origin zero as middle C4
	 * @param  integer  $preferredAlteration 1 for sharp or -1 for flats
	 * @return array                       returns a pitch array, containing step and alter elements.
	 */
	public function noteNumberToPitch($noteNumber, $preferredAlteration = 1) {
		$chroma = $this->_truemod($noteNumber, 12); // chroma is the note pitch independent of octave, eg C = 0, Eb/D# = 3, E = 4
		$octave = (($noteNumber - $chroma) / 12) + 4;
		$this->octave = $octave;

		if (is_array(self::$chromas[$chroma])) {
			if ($preferredAlteration === 1) {
				$this->step = self::$chromas[$chroma][1]['step'];
				$this->alter = self::$chromas[$chroma][1]['alter'];
			} else {
				$this->step = self::$chromas[$chroma][-1]['step'];
				$this->alter = self::$chromas[$chroma][-1]['alter'];
			}
		} else {
			$this->step = self::$chromas[$chroma];
			$this->alter = 0;
		}
	}

	/**
	 * required because PHP doesn't do modulo correctly with negative numbers.
	 */
	private function _truemod($num, $mod) {
		return ($mod + ($num % $mod)) % $mod;
	}

	/**
	 * returns a pitch string suitable for using in Lilypond (in absolute mode)
	 * @return [type] [description]
	 */
	public function toLilypond() {
		$out = strtolower($this->step);
		if ($this->alter == 1) {
			$out .= 'is';
		}
		if ($this->alter == -1) {
			$out .= 'es';
		}
		if ($this->octave > 3) {
			$out .= str_repeat("'", $this->octave - 3);
		} else {
			$out .= str_repeat(",", 3 - $this->octave);
		}
		return $out;
	}

	/**
	 * finds the nearest pitch that is higher than (or equal), with a step and alter.
	 * @return [type] [description]
	 */
	public function closestUp($step, $alter, $allowEqual = true) {
		$base = new Pitch($step, $alter, -6);
		for ($i=0; $i<25; $i++) {
			$base->transpose(12, $alter);
			if ($base->isHigherThan($this)) {
				return $base;
			}
			if ($allowEqual && $base->isEnharmonic($this)) {
				return $base;
			}
		}
		// this may happen if the pitch is really high or low
		return null;
	}

	/**
	 * finds the nearest pitch that is lower than or equal, with a step and alter.
	 * @return [type] [description]
	 */
	public function closestDown($step, $alter, $allowEqual = true) {
		$base = new Pitch($step, $alter, 20);
		for ($i=0; $i<25; $i++) {
			$base->transpose(-12, $alter);
			if ($base->isLowerThan($this)) {
				return $base;
			}
			if ($allowEqual && $base->isEnharmonic($this)) {
				return $base;
			}
		}
		// this may happen if the pitch is really high or low
		return null;
	}


}
