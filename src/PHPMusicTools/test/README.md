Naturally, the goal is to have a phpunit test in here for every method of every class.

Laziness sometimes prevails, and code gets written without tests. If you're feeling heroic, find something that isn't tested, and smother it.


vendor/bin/phpunit --testdox-html public/reports/phpunit.html --whitelist src/PHPMusicTools/classes --coverage-html public/reports/coverage.html src/PHPMusicTools/test/