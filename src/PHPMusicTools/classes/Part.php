<?php
namespace ianring;

require_once 'PMTObject.php';

class Part extends PMTObject {

	var $measures = array();

	function __construct($name = null, $measures = array()) {
		$this->name = $name;
		$this->measures = $measures;
	}

	public static function constructFromArray($props) {
		$name = $props['name'];
		$measures = array();
		if (isset($props['measures'])) {
			foreach ($props['measures'] as $measure) {
				if ($measure instanceof Measure) {
					$measures[] = $measure;
				} else {
					$measures[] = Measure::constructFromArray($measure);
				}
			}
		}

		return new Part($name, $measures);
	}

	function toXML($num = 1) {
		$out = '<part id="P' . $num . '">';

		if (!empty($this->measures)) {
			foreach ($this->measures as $key => $measure) {
				$out .= $measure->toXML($key);
			}
		}
		$out .= '</part>';

		return $out;
	}

	function addMeasure($measure) {
		$newmeasure = clone $measure;
		$this->measures[] = $newmeasure;
	}

}
