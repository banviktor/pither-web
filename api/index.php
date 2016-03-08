<?php
/**
 * @file
 * Controllers for PiTher Web.
 */

// Load Silex.
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

// Controllers.
$app->get('/logout', function() use($app) {
  // TODO: logout.

});
$app->post('/login', function() use($app) {
  // TODO: log in.

});
$app->post('/users', function() use($app) {
  // TODO: create user.

});
$app->get('/users/{name}', function($name) use($app) {
  // TODO: get user data.

});
$app->delete('/users/{name}', function($name) use($app) {
  // TODO: delete user.

});
$app->patch('/users/{name}', function($name) use($app) {
  // TODO: modify user.

});
$app->get('/settings', function() use($app) {
  // TODO: get all settings.

});
$app->get('/settings/{key}', function($key) use($app) {
  // TODO: get setting by key.

});
$app->patch('/settings/{key}', function($key) use($app) {
  // TODO: set setting.

});
$app->get('/rules', function() use($app) {
  // TODO: get all rules.

});
$app->get('/rules/{id}', function($id) use($app) {
  // TODO: get rule by id.

});
$app->delete('/rules/{id}', function($id) use($app) {
  // TODO: delete rule.

});
$app->patch('/rules/{id}', function($id) use($app) {
  // TODO: modify rule.

});
$app->get('/overrides', function() use($app) {
  // TODO: get all overrides.

});
$app->get('/overrides/{id}', function($id) use($app) {
  // TODO: get override by id.

});
$app->delete('/overrides/{id}', function($id) use($app) {
  // TODO: delete override.

});
$app->patch('/overrides/{id}', function($id) use($app) {
  // TODO: modify override.

});
$app->get('/sensor', function() use($app) {
  // TODO: get current sensor data.

});
$app->get('/sensor/log/{start}/{end}', function($start, $end) use($app) {
  // TODO: get sensor data from {start} to {end}.

});
$app->get('/heating/log/{start}/{end}', function($start, $end) use($app) {
  // TODO: get heating data from {start} to {end}.

});

$app->run();
