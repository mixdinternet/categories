<?php

/* DEMO
    Route::get('downloads/categories/trash', ['uses' => '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController@index', 'as' => '.categories.trash']);
    Route::post('downloads/categories/restore/{id}', ['uses' => '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController@restore', 'as' => '.categories.restore']);
    Route::resource('downloads/categories', '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController', [
        'names' => [
            'index' => '.categories.index',
            'create' => '.categories.create',
            'store' => '.categories.store',
            'edit' => '.categories.edit',
            'update' => '.categories.update',
            'show' => '.categories.show',
        ], 'except' => ['destroy']]);
    Route::delete('downloads/categories/destroy', ['uses' => '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController@destroy', 'as' => '.categories.destroy']);
*/