<?php
/**
 * URL Shortener
 */

use UrlShortener\Request;
use UrlShortener\Response\InternalServerErrorResponse;
use UrlShortener\Response\NotFoundResponse;
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
  if (isset($route['args'])) {
    return !empty(array_filter($route['args']));
  }

  return $route['uri'] === $requestUri && in_array($requestMethod, $route['method']);
});

if (empty($matchedRoute)) {
  new NotFoundResponse()();
}

$matchedRoute = reset($matchedRoute);
$callbackResponse = call_user_func_array($matchedRoute['callback'], $matchedRoute['params'] ?? []);

//echo '<pre>';
//print_r($callbackResponse);
//echo '</pre>';
//exit;
/** @var ResponseInterface $callbackResponse */
$response = is_string($callbackResponse)
  ? $callbackResponse
  : $callbackResponse->getResponse();

if (!$response) {
  new InternalServerErrorResponse()();
}

echo $response;
exit;