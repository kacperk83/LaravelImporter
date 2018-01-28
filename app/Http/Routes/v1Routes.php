<?php

Route::group(
    [
        'as' => 'v1.',
        'prefix' => 'v1',
        'namespace' => 'V1'
    ],
    function () {

        // Galleries
        Route::resource(
            'users',
            'Users\UsersController',
            [
                'only' => ['index', 'show'],
                'names' => [
                    'index' => 'galleries.index',
                    'show' => 'galleries.show',
                ]
            ]
        );
    }
);
