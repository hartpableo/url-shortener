<?php
/**
 * URL Shortener
 */

use UrlShortener\Request;
use UrlShortener\Response\ResponseInterface;

require_once __DIR__ . '/autoloader.php';

$request = new Request();
$requestUri = $request->uri;
$requestMethod = $request->method;

function request(): Request {
  static $instance = NULL;

  if ($instance === NULL) {
    $instance = new Request();
  }

  return $instance;
}

// Routing
$routes = require __DIR__ . '/routes.php';
$matchedRoute = array_filter($routes, function ($route) use ($requestMethod, $requestUri) {
  return $route['uri'] === $requestUri && in_array($requestMethod, $route['method']);
});

if (empty($matchedRoute)) {
  http_response_code(404);
  echo "Not found";
  exit;
}

$matchedRoute = reset($matchedRoute);
$callbackResponse = call_user_func_array($matchedRoute['callback'], $matchedRoute['params'] ?? []);

/** @var ResponseInterface $callbackResponse */
$response = $callbackResponse->getResponse();
if (!$response) {
  http_response_code(500);
  echo "Internal server error";
  exit;
}

echo $response;
exit;