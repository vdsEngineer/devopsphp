<?php

test('globals')
    ->expect(['dd', 'var_dump', 'dump', 'ray'])
    ->not
    ->toBeUsed();

test('controllers')
    ->expect('App\Http\Controllers')
    ->toOnlyUse([
        'App\Http\Requests',
        'App\Services',
        'Illuminate\Http',
        'Illuminate\Routing',
    ]);

test('models')
    ->expect('App\Models')
    ->toBeClasses();
