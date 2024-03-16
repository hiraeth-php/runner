<?php

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter as Emitter;

ignore_user_abort(true);

//
// Track backwards until we discover our composer.json.
//

for (
	$root_path  = dirname(__DIR__);
	$root_path != '/' && !is_file($root_path . DIRECTORY_SEPARATOR . 'composer.json');
	$root_path  = realpath($root_path . DIRECTORY_SEPARATOR . '..')
);

if ($root_path) {
	$running  = TRUE;
	$loader   = require $root_path . '/vendor/autoload.php';
	$hiraeth  = new Hiraeth\Application($root_path);
	$handler  = function() use ($hiraeth) {
		$hiraeth->run(function(Handler $handler, Request $request, Emitter $emitter) {
			$emitter->emit($handler->handle($request));
		});
	};

	$hiraeth->exec();

	for ($requests = 0; $requests < ($_SERVER['MAX_REQUESTS'] ?? 100) && $running; ++$requests) {
		$running = \frankenphp_handle_request($handler);

		gc_collect_cycles();
	}
}
