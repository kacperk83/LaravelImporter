<?php

Route::group(
    [
        'as' => 'v1.',
        'prefix' => 'v1',
        'namespace' => 'V1'
    ],
    function () {

        // Users
        Route::resource(
            'users',
            'Users\UsersController',
            [
                'only' => ['index', 'show'],
                'names' => [
                    'index' => 'users.index',
                    'show' => 'users.show',
                ]
            ]
        );
    }
);
