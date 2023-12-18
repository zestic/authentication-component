<?php

test('register user', function () {
    expect(true)->toBeTrue();
});

test('login user', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('logout user', function () {
    expect(true)->toBeTrue();
})->depends('login user');

test('forgot password', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('reset password', function () {
    expect(true)->toBeTrue();
})->depends('forgot password');

test('change password', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('update identity', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('delete user', function () {
    expect(true)->toBeTrue();
})->depends('register user');
