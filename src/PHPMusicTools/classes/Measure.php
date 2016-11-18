<?php
namespace ianring;

require_once 'PMTObject.php';
require_once 'Layer.php';
require_once 'Clef.php';
require_once 'Key.php';
require_once 'Time.php';
require_once 'Barline.php';
require_once 'Direction.php';

require_once 'DirectionMetronome.php';
require_once 'DirectionDynamics.php';

/**
 * Measure is a collection of layers, having a rhythmic duration
 */
class Measure extends PMTObject {

	private static $defaults = array(
		'layers' => array(),
		'directions' => array(),
		'time' => null,
		'clef' => null,
		'key' => null,
		'divisions' => null,
		'barline' => null,
		'implicit' => null,
		'nonControlling' => null,
		'width' => null
	);

	var $properties = array();
	var $layers = array();

	function __construct(
		$layers = array(),
		$directions = array(),
		$time = null,
		$clef = null,
		$key = null,
		$divisions = 24,
		$barline = null,
		$implicit = null,
		$nonControlling = null,
		$width
	) {
		if (is_null($clef)) {
			$clef = new Clef('treble');
		}
		if (is_null($key)) {
			$clef = new Key('C major');
		}
		if (is_null($time)) {
			$time = new Time('common');
		}
		if (is_null($barline)) {
			$barline = new Barline();
		}

		$this->layers = $layers;
		$this->directions = $directions;
		$this->time = $time;
		$this->clef = $clef;
		$this->key = $key;
		$this->divisions = $divisions;
		$this->barline = $barline;
		$this->implicit = $implicit;
		$this->nonControlling = $nonControlling;
		$this->width = $width;

		// this line allows us to chain commands!
		return $this;
	}

	public static function constructFromArray($props) {

		$props = array_merge(self::$defaults, $props);

		foreach ($props['layers'] as &$layer) {
			if ($layer instanceof Layer) {
				$layers[] = $layer;
			} else {
				$layer = Layer::constructFromArray($layer);
			}
		}

		foreach ($props['directions'] as &$direction) {
			switch ($direction['directionType']) {
				case 'metronome':
					$direction = DirectionMetronome::constructFromArray($direction);
					break;
				case 'dynamics':
					$direction = DirectionDynamics::constructFromArray($direction);
					break;
				default:

			}
		}

		if ($props['time'] instanceof Time) {
//			$time = $props['time'];
		} else {
			$props['time'] = Time::constructFromArray($props['time']);
		}

		if ($props['clef'] instanceof Clef) {
//			$clef = $props['clef'];
		} else {
			$props['clef'] = Clef::constructFromArray($props['clef']);
		}

		if ($props['key'] instanceof Key) {
//				$key = $props['key'];
		} else {
			$props['key'] = Key::constructFromArray($props['key']);
		}			

		if ($props['barline'] instanceof Barline) {
//			$barline = $props['barline'];
		} else {
			$props['barline'] = Barline::constructFromArray($props['barline']);
		}

		return new Measure(
			$props['layers'],
			$props['directions'],
			$props['time'],
			$props['clef'],
			$props['key'],
			$props['divisions'],
			$props['barline'],
			$props['implicit'],
			$props['nonControlling'],
			$props['width']
		);
	}

	/**
	 * renders this object as MusicXML
	 * @return string MusicXML representation of the object
	 */
	function toMusicXML($number) {
		$out = '';

		$out .= '<measure ';
		if (!is_null($this->implicit)) {
			$out .= ' implicit="' . ($this->implicit ? 'yes' : 'no') . '"';
		}
		if (!is_null($this->nonontrolling)) {
			$out .= ' non-controlling="' . ($this->nonontrolling ? 'yes' : 'no') . '"';
		}
		$out .= ' number="' . $number . '"';
		$out .= '>';

		$out .= '<attributes>';
		$out .= $this->_renderproperties();
		$out .= '</attributes>';

		$ticks = $this->divisions * $this->time->beats;

		$i = 0;
		foreach ($this->layers as $layer) {
			$out .= $layer->toMusicXML();
			$i++;

			if ($i < count($this->layers)) {
				$out .= '<backup>';
		    	$out .= '<duration>'.$ticks.'</duration>';
		  		$out .= '</backup>';
				$out .= '<forward>';
		    	$out .= '<duration>0</duration>';
		  		$out .= '</forward>';
			}
		}

		if (!empty($this->barline)) {
			foreach ($this->barline as $barline) {
				if (!$barline instanceof Barline) {
					$barline = new Barline($barline);
				}
				$out .= $barline->toMusicXML();
			}
		}

		$out .= '</measure>';
		return $out;
	}

	/**
	 * renders the object's properties as XML
	 * @return  string  the XML
	 */
	private function _renderproperties() {
		$out = '';

		$out .= '<divisions>'.$this->properties['divisions'].'</divisions>';
		$staves = 1;

		if (isset($this->key)) {
			$out .= $this->key->toMusicXML();
		}

		if (isset($this->time)) {
			$time = $this->time;
			if (!$time instanceof Time) {
				$time = new Time($time);
			}
			$out .= $this->time->toMusicXML();
		}

		// we count the number of clefs, and that is our number of staves
		$clefs = '';
		if (isset($this->clef)) {
			if (!is_array($this->clef)) {
				$this->clef = array($this->clef);
			}
			$num = 0;
			foreach ($this->clef as $clef) {
				$num++;
				$clefs .= $clef->toMusicXML($num);
			}
			$staves = $num;
		}

		if (isset($this->staves)) {
			$staves = $this->staves;
		}

		// output staves first, and then clefs.
		$out .= '<staves>'.$staves.'</staves>';
		$out .= $clefs;

		return $out;
	}

	function addLayer($layer) {
		$this->layers[] = $layer;
	}

	/**
	 * adds a note to a measure. Assumes that it should be added to the first layer, and if there are no layers in
	 * the measure one should be created. The note is enclosed in its own Chord object.
	 * @param Note $note the note to add
	 */
	function addNote($note) {
		if (!count($this->layers)) {
			$layer = new Layer();
			$this->addLayer($layer);
		} else {
			$layer = $this->layers[0];
		}
		$layer->addNote($note);
	}

	/**
	 * adds a  bunch of notes all at once.
	 * @param array $array an array of Notes
	 */
	function addNotes($array) {
		foreach ($array as $note) {
			$this->addNote($note);
		}
	}

	function backup($duration) {

	}

	function forward($duration) {

	}

	/**
	 * transposes all the notes in this measure by $interval
	 * @param  integer  $interval  a signed integer telling how many semitones to transpose up or down
	 * @param  integer  $preferredAlteration  either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
	 * @return  null
	 */
	public function transpose($interval, $preferredAlteration = 1) {
		foreach ($this->layers as &$layer) {
			$layer->transpose($interval);
		}
	}

	/**
	 * using the measure's own Key, will quantize all the notes to be part of a given scale.
	 * If scale is omitted, will use the scale implied by the Key's "mode" property.
	 * @param   $scale  a Scale object
	 * @return null
	 */
	public function autoTune($scale = null) {
		// todo: figure out the key and scale, based on the measure's Key property
		foreach ($this->layers as &$layer) {
			$layer->autoTune($scale);
		}
	}


	/**
	 * analyze the current measure, and return an array of all the Scales that its notes fit into.
	 * @param  Pitch  $root  if the root is known and we only want to learn about matching modes, provide a Pitch for the root.
	 * @return [type] [description]
	 */
	public function getScales($root = null) {
		$scales = Scale::getScales($this);
	}

	/**
	 * returns an array of Pitch objects, for every pitch of every note in the measure.
	 * @param  boolean  $heightless  if true, will return heightless pitches all mudul to the same octave. Useful for
	 *                              analysis, determining mode etc.
	 * @return array  an array of Pitch objects
	 */
	public function getAllPitches($heightless = false) {
		$pitches = array();
		foreach ($this->layers as $layer) {
			$layerPitches = $layer->getAllPitches($heightless);
			$pitches = array_merge_recursive($pitches, $layerPitches);
		}
		return $pitches;
	}

}

