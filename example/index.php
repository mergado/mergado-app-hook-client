<?php

require __DIR__ . "/../src/MergadoAppHookClient.php";

\MergadoApp\Hook\Client::$debug = true;
$c = new \MergadoApp\Hook\Client;

$c->addHandler('enable', function($d) {

	// "App is being enabled" logic
	echo "enable";

});

$c->addHandler('disable', function($d) {

	// "App is being enabled" logic
	echo "disable";

});

$c->run();
