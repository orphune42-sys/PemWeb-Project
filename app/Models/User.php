<?php
class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, username, password, role FROM `user` WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findEmailOrUsername(string $email, string $username): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT email, username FROM `user` WHERE email = :email OR username = :username'
        );
        $stmt->execute(['email' => $email, 'username' => $username]);
        return $stmt->fetchAll();
    }

    public function createMahasiswa(string $name, string $email, string $username, string $password): void
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO `user` (name, email, username, password, role) VALUES (:name, :email, :username, :password, :role)'
            );
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'mahasiswa'
            ]);
            $userId = $this->pdo->lastInsertId();

            $profile = $this->pdo->prepare('INSERT INTO `mahasiswa` (user_id) VALUES (:user_id)');
            $profile->execute(['user_id' => $userId]);
            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            throw $e;
        }
    }
}
