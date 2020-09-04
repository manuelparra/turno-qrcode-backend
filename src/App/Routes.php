<?php

use Slim\Routing\RouteCollectorProxy;

$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.2'];

$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$app->group('/api/v1', function(RouteCollectorProxy $group) {
    $group->get('/users', 'App\Controllers\UsersController:getAll');

    $group->get('/sequences', 'App\Controllers\SequencesController:getSecuences');
    $group->get('/sequences/lastcode', 'App\Controllers\SequencesController:getLastSequence');
    $group->get('/sequences/secuence/{sec}', 'App\Controllers\SequencesController:getSecuence');
    $group->get('/sequences/nextsequence', 'App\Controllers\SequencesController:getNextSecuence');
});
