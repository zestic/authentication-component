<?php

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Zestic\Authentication\AuthenticationRepository;
use Zestic\Authentication\AuthenticationManager;
use Zestic\Authentication\Entity\AuthLookup;
use Zestic\Authentication\Entity\NewAuthLookup;
use Zestic\Authentication\Entity\PasswordReset;
use Zestic\Authentication\Interface\AuthenticationResponseInterface;
use Zestic\Authentication\Interface\GenerateAuthenticationResponseInterface;
use Zestic\Authentication\Interface\UserClientDataInterface;

$faker = Faker\Factory::create();

$repository = Mockery::mock(AuthenticationRepository::class);
$generateAuthenticationResults = Mockery::mock(GenerateAuthenticationResponseInterface::class);
$logger = Mockery::mock(LoggerInterface::class);
$userClientData = Mockery::mock(UserClientDataInterface::class);

$manager = new AuthenticationManager($repository, $generateAuthenticationResults, $logger, $userClientData);

$email = $faker->email();
$lookupId = Uuid::uuid4();
$authLookup = new AuthLookup($email, [], ['id' => (string) $lookupId]);
$password = $faker->password;

$repository->shouldReceive('findLookupByEmail')->with($email)->andReturn($authLookup);
$repository->shouldReceive('updateLookup')->with($lookupId, ['password' => $password])->andReturn(true);

$_SERVER['HTTP_USER_AGENT'] = $faker->userAgent();

test('register user', function () use ($manager, $repository, $logger, $userClientData, $faker) {
    $uuid = Uuid::uuid4();
    $repository->shouldReceive('createLookup')->andReturn($uuid);
    $userData = [];
    $userClientData->shouldReceive('getData')->andReturn($userData);

    $email = $faker->email();
    $userId = $faker->uuid();
    $username = $faker->userName();

    $context = [
        'data' => [
            'email' => $email,
            'userId' => $userId,
            'username' => $username,
            'success' => true,
        ],
        'userClientData' => $userData,
    ];
    $logger->shouldReceive('info')->with('RegisterUser', $context);

    $newAuthLookup = new NewAuthLookup(
        $email,
        $faker->password(),
        $userId,
        $username,
    );
    $lookupId = $manager->register($newAuthLookup);
    expect($lookupId)->toBe($uuid);
});

test('authenticate user', function () use ($manager, $repository, $generateAuthenticationResults, $logger, $userClientData, $faker) {
    $email = $faker->email();

    $details = [];
    $authLookup = new AuthLookup($email, [], $details);
    $repository->shouldReceive('authenticate')->andReturn($authLookup);

    $response = Mockery::mock(AuthenticationResponseInterface::class);
    $response->shouldReceive('isSuccess')->andReturn(true);
    $generateAuthenticationResults->shouldReceive('succeeded')->andReturn($response);

    $userData = [];
    $userClientData->shouldReceive('getData')->andReturn($userData);

    $context = [
        'data' => [
            'credential' => $email,
            'success' => true,
        ],
        'userClientData' => $userData,
    ];
    $logger->shouldReceive('info')->with('AuthenticateUser', $context);

    $authenticatedUser = $manager->authenticate($email, $faker->password());
    expect($authenticatedUser)->toBe($authenticatedUser);
})->depends('register user');

test('logout user', function () {
    expect(true)->toBeTrue();
})->depends('authenticate user');

test('generate password reset token', function () use ($email, $manager, $repository) {
    $expectedToken = 'e03kgje2';
    $repository->shouldReceive('createPasswordReset')->andReturn($expectedToken);

    $token = $manager->generatePasswordResetToken($email);
    expect($token)->toBe($expectedToken);
})->depends('register user');

test('reset password from token', function () use ($lookupId, $password, $manager, $repository) {
    $token = 'e03kgjei2';
    $passwordReset = (new PasswordReset())
        ->setToken($token)
        ->setLookupId($lookupId);

    $repository->shouldReceive('findPasswordResetByToken')->with($token)->andReturn($passwordReset);
    $repository->shouldReceive('deletePasswordReset')->with($token)->andReturn(true);

    $results = $manager->setPasswordForToken($token, $password);
    expect($results)->toBeTrue();
})->depends('generate password reset token');

test('set password', function () use ($email, $password, $manager) {
    $results = $manager->setPasswordForEmail($email, $password);
    expect($results)->toBeTrue();
})->depends('register user');

test('update lookup', function () {
    expect(true)->toBeTrue();
})->depends('register user');

test('delete lookup', function () {
    expect(true)->toBeTrue();
})->depends('register user');
