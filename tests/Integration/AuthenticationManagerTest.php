<?php

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Zestic\Authentication\AuthLookupRepository;
use Zestic\Authentication\AuthenticationManager;
use Zestic\Authentication\Entity\NewAuthLookup;
use Zestic\Authentication\Interface\FindUserByIdInterface;
use Zestic\Authentication\Interface\UserClientDataInterface;

$faker = Faker\Factory::create();

$repository = Mockery::mock(AuthLookupRepository::class);
$findUserById = Mockery::mock(FindUserByIdInterface::class);
$logger = Mockery::mock(LoggerInterface::class);
$userClientData = Mockery::mock(UserClientDataInterface::class);

$manager = new AuthenticationManager($repository, $findUserById, $logger, $userClientData);

$_SERVER['HTTP_USER_AGENT'] = $faker->userAgent();

test('register user', function () use ($manager, $repository, $logger, $userClientData, $faker) {
    $uuid = Uuid::uuid4();
    $repository->shouldReceive('createLookup')->andReturn($uuid);
    $userData = [];
    $userClientData->shouldReceive('getData')->andReturn($userData);

    $email = $faker->email();
    $password = $faker->password();
    $userId = $faker->uuid();
    $username = $faker->userName();

    $context = [
        'data' => [
            'email' => $email,
            'userId' => $userId,
            'username' => $username,
        ],
        'user' => $userData,
    ];
    $logger->shouldReceive('info')->with('RegisterUser', $context);

    $newAuthLookup = new NewAuthLookup(
        $email,
        $password,
        $userId,
        $username,
    );
    $lookupId = $manager->register($newAuthLookup);
    expect($lookupId)->toBe($uuid);
});

test('authenticate user', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('logout user', function () {
    expect(true)->toBeTrue();
})->depends('authenticate user');

test('generate password reset token', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('reset password from token', function () {
    expect(true)->toBeTrue();
})->depends('forgot password');

test('set password', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('update lookup', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('delete lookup', function () {
    expect(true)->toBeTrue();
})->depends('register user');
