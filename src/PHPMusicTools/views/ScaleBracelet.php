<?php

require_once('Visualization.php');
require_once(__DIR__ . '/../classes/Scale.php');

class ScaleBracelet extends Visualization {


	public function __construct($scale, $args=array()) {
		if (is_integer($scale)) {
			$scale = new \ianring\Scale($scale);
		}
		$this->scale = $scale;

		$this->args = array_merge(
			$args,
			array(
				'size' => 200,
				'text' => null,
				'showImperfections' => false,
				'showSymmetries' => false
			)
		);
	}


	public function render() {
		$scale = $this->scale;
		$size = $this->args['size'];
		$text = $this->args['text'];
		$showImperfections = $this->args['showImperfections'];
		$showSymmetries = $this->args['showSymmetries'];

		if ($showImperfections) {
			$imperfections = $scale->imperfections();
		}
		if ($showSymmetries) {
			$symmetries = $scale->symmetries();
		}

		$s = '';
		if ($size > 100) {
			$stroke = 3;
		} elseif ($size > 70) {
			$stroke = 2;
		} else {
			$stroke = 1;
		}
		$smallrad = floor(($size / 12));
		$centerx = $size / 2;
		$centery = $size / 2;
		$radius = floor(($size - ($smallrad*2) - ($stroke*4)) / 2);
		$s .= '<svg xmlns="http://www.w3.org/2000/svg" height="'. ($size + 3).'" width="'.($size + 3) .'">';
		$s .= '<circle r="'.$radius.'" cx="'.$centerx.'" cy="'.$centery.'" stroke-width="'.$stroke.'" fill="white" stroke="black"/>';
		$symmetryshape = array();
		for ($i=0; $i<12; $i++) {
			$deg = $i * 30 - 90;
			$x1 = floor($centerx + ($radius * cos(deg2rad($deg))));
			$y1 = floor($centery + ($radius * sin(deg2rad($deg))));

			$innerx1 = floor($centerx + (($radius - $smallrad) * cos(deg2rad($deg))));
			$innery1 = floor($centery + (($radius - $smallrad) * sin(deg2rad($deg))));

			if ($i == 0) {
				$symmetryshape[] = array($innerx1, $innery1);
			}

			$s .= '<circle r="'.$smallrad.'" cx="'.$x1.'" cy="'.$y1.'" stroke="black" stroke-width="'.$stroke.'"';
			if ($scale->scale & (1 << $i)) {
				$s .= ' fill="black"';
			} else {
				$s .= ' fill="white"';
			}
			$s .= '/>';

			if ($showImperfections) {
				if (in_array($i, $imperfections)) {
					$s .= '<text style="font-family: Times New Roman;font-weight:bold;font-style:italic;font-size:30px;" text-anchor="middle" x="'.$x1.'" y="'. ($y1 + 9) .'" fill="white">i</text>';
				}
			}
			if ($showSymmetries) {
				if (in_array($i, $symmetries)) {
					$symmetryshape[] = array($innerx1, $innery1);
				}
			}
		}
		if (count($symmetryshape) > 1) {
			for ($i = 0; $i < count($symmetryshape) - 1; $i++) {
				$s .= '<line x1="'.$symmetryshape[$i][0].'" y1="'.$symmetryshape[$i][1].'" x2="'.$symmetryshape[$i+1][0].'" y2="'.$symmetryshape[$i+1][1].'" style="stroke:#000;stroke-width:'.$stroke.'" />';
			}
			$s .= '<line x1="'.$symmetryshape[count($symmetryshape)-1][0].'" y1="'.$symmetryshape[count($symmetryshape)-1][1].'" x2="'.$symmetryshape[0][0].'" y2="'.$symmetryshape[0][1].'" style="stroke:#000;stroke-width:'.$stroke.'" />';
		}
		if (!empty($text)) {
			$s .= '<text style="font-weight: bold;" text-anchor="middle" x="'.$centerx.'" y="'. ($centery + 5) .'" fill="black">'.$text.'</text>';
		}
		$s .= '</svg>';
		return $s;
	}


}