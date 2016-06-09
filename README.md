# Mergado App Hook Client

A standard implementation of hook client for a Mergado App. You can of course implement your own app's hooks handler, but this is to provide you a default and standard *Mergado* way and to show you how it can be done.

**For more information about hooks**, see http://mergado.github.io/docs/apps/app-hooks.html.

### Installation
1. Download the file `MergadoAppHookClient.php` located at https://github.com/mergado/mergado-app-hook-client/blob/master/lib/MergadoAppHookClient.php
2. Put it somewhere into your app's directory, so you can include the file from the `index.php` file located in your app's system hook directory (which is then invoked by Mergado placing requests to your app's hook endpoint: `https://appcloud.mergado.com/apps/myapp/_mergado/hook/`) 

### Usage
1. Include the file containing the Hook Client.
2. Instantiate the Hook Client:

 ```php
 $c = new \MergadoApp\Hook\Client;
 ```
3. Add handlers to hooks by registering callbacks with their respective hook names. For example:

 ```php
 $c->addHandler('app.disable', function($hookData) { /* Some logic. */ });
 ```
4. Run the client:

 ```php
 $c->run();
 ```
5. ***Profit!***

### Full example:

```php
<?php

require __DIR__ . "/../lib/MergadoAppHookClient.php";

// Uncomment this to enable debug mode - errors will be shown.
// \MergadoApp\Hook\Client::$debug = true;

$c = new \MergadoApp\Hook\Client;

$c->addHandler('app.enable', function($hookData) {

	// "App is being enabled" logic.
	printf("App enabled for %s with ID %s.", $hookData['entity_type'], $hookData['entity_id']);
	
});

$c->addHandler('app.disable', function($hookData) {

	// "App is being disabled" logic.
	printf("App disabled for %s with ID %s.", $hookData['entity_type'], $hookData['entity_id']);
	
});

$c->run();

```

### Registering handlers
Handler for each hook must fit the *`callable`* typehint. This handler is then invoked, with the only argument: **data** parsed from the hook request JSON body. This way you have the information you need about the hook available inside the handler function.
