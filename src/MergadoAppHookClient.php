<?php

namespace MergadoApp\Hook;

final class Client {

	const MERGADO_HOOK_AUTH_HEADER = 'HTTP_MERGADO_APPS_HOOK_AUTH';

	public static $debug = false;

	/** @var string Raw data from php://input. Can be passed in the constructor for tests. **/
	private $rawRequest;

	/** @var array Contents of $_SERVER superglobal. Can be passed in the constructor for tests. **/
	private $server;

	/** @var array Registered hook handlers. **/
	private $handlers;

	/** @var bool Was hook client initialized during this request? **/
	private static $staticInit;

	public function __construct(string $rawRequest = null, array $server = null) {

		self::staticInit();

		// Allow injecting arbitrary data (eg. for tests) but use global data by default.
		$this->rawRequest = $rawRequest ?: file_get_contents('php://input');
		$this->server = $server ?: $_SERVER;

	}

	private static function staticInit() {

		if (self::$staticInit) return;

		if (!self::$debug) {
			// Make sure real errors will always result in a proper HTTP error code.
			error_reporting(0);
			ini_set('display_errors', false);
		}

		self::$staticInit = true;

	}

	public function run() {

		// This handler should be used only when we're sure all requests are in fact hooks.
		// If this particular request is not a hook, something is wrong.
		if (!isset($this->server[self::MERGADO_HOOK_AUTH_HEADER])) {
			throw new \RuntimeException(sprintf(
				"%s is to be used only for handling hooks sent from Mergado.
				Mergado-Apps-Hook-Auth is missing.",
				__CLASS__
			));
		}

		if (!$decoded = json_decode($this->rawRequest, true)) {
			throw new \RuntimeException(sprintf(
				"%s cannot handle request, because the data to be handled is empty.",
				__CLASS__
			));
		} elseif (!isset($decoded['action'])) {
			throw new \RuntimeException(sprintf(
				"%s cannot handle the hook, because the hook action is undefined.",
				__CLASS__
			));
		};

		$this->handle($decoded['action'], $decoded);

	}

	public function addHandler(string $hook, callable $handler) {
		$this->handlers[$hook] = $handler;
	}

	private function handle(string $hook, array $data = array()) {

		if (isset($this->handlers[$hook])) {
			$this->handlers[$hook]($data);
		} else {
			// Having hooks without registered handlers are quite allowed.
		}

	}

}

