#!/bin/bash

vendor/bin/phpdoc -d src/PHPMusicTools/classes -t public/docs/api

vendor/bin/phpunit --testdox-html public/phpunit/results.html src/PHPMusicTools/test/

vendor/bin/phpcs src/PHPMusicTools/classes/ --report=checkstyle --report-file=public/phpcs/report.xml

vendor/bin/phpcbf src/PHPMusicTools/classes/

