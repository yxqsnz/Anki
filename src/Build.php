<?php

$phar = new Phar('Anki.phar');
$phar->buildFromDirectory('build/');
