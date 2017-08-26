<?php

echo 'example 1';

$part = Part::constructFromArray(
	'name' => 'Viola',
	'measures' => array(
		
	)
);

$lily = 'a4 b8 c8 d4 e4';
$score = Score::constructFromLily($lily);
$score->transpose(1);
echo $score->toSVG();
