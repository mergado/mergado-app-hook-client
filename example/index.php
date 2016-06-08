<?php

require __DIR__ . "/../lib/MergadoAppHookClient.php";

// Uncomment this to enable debug mode - errors will be shown.
// \MergadoApp\Hook\Client::$debug = true;

$c = new \MergadoApp\Hook\Client;

$c->addHandler('enable', function($hookData) {

	// "App is being enabled" logic.
	printf("App enabled for %s with ID %s.", $hookData['entity_type'], $hookData['entity_id']);

});

$c->addHandler('disable', function($hookData) {

	// "App is being disabled" logic.
	printf("App disabled for %s with ID %s.", $hookData['entity_type'], $hookData['entity_id']);

});

$c->run();
