<?php

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Exceptions\CouldNotLogActivity;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\Test\Models\Article;
use Spatie\Activitylog\Test\Models\User;

it('can resolve current logged in user', function (): void {
    Auth::login($user = User::first());

    $causer = CauserResolver::resolve();

    expect($causer)->toBeInstanceOf(User::class);
    expect($causer->id)->toEqual($user->id);
});

it('will throw an exception if it cannot resolve user by id', function (): void {
    $this->expectException(CouldNotLogActivity::class);

    CauserResolver::resolve(9999);
});

it('can resloved user from passed id', function (): void {
    $causer = CauserResolver::resolve(1);

    expect($causer)->toBeInstanceOf(User::class);
    expect($causer->id)->toEqual(1);
});

it('will resolve the provided override callback', function (): void {
    CauserResolver::resolveUsing(fn () => Article::first());

    $causer = CauserResolver::resolve();

    expect($causer)->toBeInstanceOf(Article::class);
    expect($causer->id)->toEqual(1);
});

it('will resolve any model', function (): void {
    $causer = CauserResolver::resolve($article = Article::first());

    expect($causer)->toBeInstanceOf(Article::class);
    expect($causer->id)->toEqual($article->id);
});
