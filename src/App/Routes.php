<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/api/v1', function(RouteCollectorProxy $group) {
    $group->get('/users', 'App\Controllers\UsersController:getAll');

    $group->get('/sequences', 'App\Controllers\SequencesController:getAll');
    $group->get('/sequences/lastcode', 'App\Controllers\SequencesController:getLastSequence');
});
