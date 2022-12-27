<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->post('/register', \App\Action\Auth\RegisterAction::class);
            $app->post('/login', \App\Action\Auth\LoginAction::class);
            $app->post('/logout', \App\Action\Auth\LoginAction::class)
                ->add(\App\Middleware\SimpleTokenAuthMiddleware::class);

            $app->get('/users', \App\Action\User\UserListAction::class)
                ->add(\App\Middleware\SimpleTokenAuthMiddleware::class);
            $app->get('/conversations', \App\Action\Conversation\ConversationListAction::class)
                ->add(\App\Middleware\SimpleTokenAuthMiddleware::class);
            $app->post('/conversations', \App\Action\Conversation\ConversationSendMessageAction::class)
                ->add(\App\Middleware\SimpleTokenAuthMiddleware::class);
            $app->get('/conversations/{conversation_id}', \App\Action\Conversation\ConversationShowAction::class)
                ->add(\App\Middleware\SimpleTokenAuthMiddleware::class);

            $app->get('/customers', \App\Action\Customer\CustomerFinderAction::class);
            $app->post('/customers', \App\Action\Customer\CustomerCreatorAction::class);
            $app->get('/customers/{customer_id}', \App\Action\Customer\CustomerReaderAction::class);
            $app->put('/customers/{customer_id}', \App\Action\Customer\CustomerUpdaterAction::class);
            $app->delete('/customers/{customer_id}', \App\Action\Customer\CustomerDeleterAction::class);
        }
    );
};
