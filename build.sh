#!/bin/bash

vendor/bin/phpdoc -d ./src/PHPMusicTools/classes -t ./public/docs/api

phpunit --testdox-html ../../../public/phpunit/results.html

vendor/bin/phpcs src/PHPMusicTools/classes/

vendor/bin/phpcbf src/PHPMusicTools/classes/

