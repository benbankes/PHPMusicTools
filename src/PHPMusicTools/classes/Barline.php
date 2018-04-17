<?php
/**
 * Barline Class
 *
 * Barline is a separator between measures
 *
 * @package      PHPMusicTools
 * @author       Ian Ring <httpwebwitch@email.com>
 */

namespace ianring;
require_once 'PMTObject.php';
require_once 'BarlineEnding.php';
require_once 'BarlineRepeat.php';

class Barline extends PMTObject
{

	public static $properties = array(
		'location',
		'barStyle',
		'footnote',
		'ending',
		'repeat',
		'segno',
		'coda',
		'fermata'
	);


	public function __construct($location, $barStyle, $footnote, $ending, $repeat, $segno, $coda, $fermata) {
		foreach (self::$properties as $var) {
			$this->$var = $$var;
		}
	}


	/**
	 * accepts the object in the form of an array structure
	 *
	 * @param  [type] $scale [description]
	 * @return [type]        [description]
	 */
	public static function constructFromArray($props) {
		if (!is_array($props)) {
			$props = array($props);
		}
		$defaults = array_fill_keys(self::$properties, null);
		$props = array_merge($defaults, $props);
		extract($props);

		if (isset($props['ending'])) {
            if ($props['ending'] instanceof BarlineEnding) {
                $ending = $props['ending'];
            } else {
				$ending = BarlineEnding::constructFromArray($props['ending']);
			}
		}

		if (isset($props['repeat'])) {
            if ($props['repeat'] instanceof BarlineRepeat) {
                $repeat = $props['repeat'];
            } else {
				$repeat = BarlineRepeat::constructFromArray($props['repeat']);
			}
		}

		// a barline coda is not a complex coda type, it's just a simple attribute

		return new Barline($location, $barStyle, $footnote, $ending, $repeat, $segno, $coda, $fermata);

	}


	/**
	 * renders this object as MusicXML
	 *
	 * @return string MusicXML representation of the object
	 */
	function toMusicXML() {
		$out  = '';
		$out .= '<barline';
		if (isset($this->location)) {
			$out .= ' location="'.$this->location.'"';
		}

		$out .= '>';
		if (isset($this->barStyle)) {
			$out .= '<bar-style>'.$this->barStyle.'</bar-style>';
		}

		if (!empty($this->segno)) {
			$out .= '<segno/>';
		}
		if (!empty($this->coda)) {
			$out .= '<coda/>';
		}
		if (!empty($this->fermata)) {
			$out .= '<fermata/>';
		}

		if (isset($this->footnote)) {
			$out .= '<footnote>'.$this->footnote.'</footnote>';
		}

		if (isset($this->ending)) {
			$out .= $this->ending->toMusicXML();
		}

		if (isset($this->repeat)) {
			$out .= $this->repeat->toMusicXML();
		}

		$out .= '</barline>';
		return $out;
	}



}
