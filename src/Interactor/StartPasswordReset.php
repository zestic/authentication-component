<?php
declare(strict_types=1);

namespace Zestic\Authentication\Interactor;

use Zestic\Authentication\Interface\UserInterface;
use App\Entity\User;

final class StartPasswordReset
{
    /** @var \PDO */
    private $pdo;
    /** @var string */
    private $table;

    public function __construct(\PDO $pdo, array $options = [])
    {
        $this->pdo = $pdo;
        $this->table = $options['table'] ?? 'users';
    }

    public function reset(string $email): array
    {
        $email = trim($email);
        if (!$user = $this->fetchUserByEmail($email)) {
            return [
                'message' => "No user found with the email address \"$email\".",
                'success' => false,
            ];
        }

        $token = $this->generateToken();
        $this->addResetToDatabase($user, $token);
        $this->notify->send();

        return [
            'success' => true,
        ];
    }

    private function addResetToDatabase($user, string $token)
    {
        $ipAddress = ip2long(
            $_SERVER['REMOTE_ADDR']
        );
    }

    private function fetchUserByEmail(string $email): ?UserInterface
    {
        $sql = <<<SQL
SELECT *  
FROM {$this->table}
WHERE email = '$email';
SQL;
        $user = $this->pdo->query($sql);

        return new User($user['id'], [], $user);
    }

    private function generateToken(): string
    {
        do {
            $token = bin2hex(random_bytes(10));
            $unique = $this->isTokenUnique($token);
        } while (false === $unique);

        return $token;
    }

    private function isTokenUnique(string $token): bool
    {
        $sql = <<<SQL
SELECT id
FROM password_reset
WHERE token = '$token';
SQL;
        return ! (bool)$this->pdo->query($sql);
    }
}
