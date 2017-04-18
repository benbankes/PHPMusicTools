<?php
namespace ianring;
require_once 'PMTObject.php';
require_once 'BarlineEnding.php';
require_once 'BarlineRepeat.php';

/**
 * Barline is a vertical separator between measures
 */
class Barline extends PMTObject
{

	public static $properties = array(
		'location',
		'barStyle',
		'footnote',
		'ending',
		'repeat',
		'coda'
	);


	public function __construct($location, $barStyle, $footnote, $ending, $repeat, $coda) {
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
		$defaults = array_fill_keys(self::$properties, null);
		$props    = array_merge($defaults, $props);
		extract($props);

		if (isset($props['ending'])) {
            if ($props['ending'] instanceof BarlineEnding) {
                $ending = $ending;
            } else {
				$ending = BarlineEnding::constructFromArray($props['ending']);
			}
		}

		if (isset($props['repeat'])) {
            if ($props['repeat'] instanceof BarlineRepeat) {
                $repeat = $repeat;
            } else {
				$repeat = BarlineRepeat::constructFromArray($props['repeat']);
			}
		}

        if (isset($props['coda'])) {
            if ($props['coda'] instanceof Coda) {
                $coda = $coda;
            } else {
                $coda = Coda::constructFromArray($props['coda']);
            }
        }


		return new Barline($location, $barStyle, $footnote, $ending, $repeat, $coda);

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
