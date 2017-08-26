<?php

echo 'example 1';

$lily = 'a4 b8 c8 d4 e4';
$score = Score::constructFromLily($lily);
$score->transpose(1);
echo $score->toSVG();
