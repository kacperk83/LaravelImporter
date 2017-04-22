<?php
/**
 *
 * @author Kacper Kowalski kacperk83@gmail.com
 *
 */

Route::group(
    [
        'middleware' => 'web',
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Admin',
    ],
    function () {
        // Users
        Route::group(
            ['namespace' => 'Users'],
            function () {

                Route::post('/users/import', 'UsersController@import')->name('users.import');

            }
        );
    }
);
