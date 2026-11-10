<?php
class AdminProfile {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getProfile(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `user` WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function updateProfile(int $userId, array $data, string $newAvatarPath): bool {
        try {
            $stmt = $this->pdo->prepare("UPDATE `user` SET name = :name, email = :email WHERE id = :id");
            return $stmt->execute([
                'name'  => trim($data['name'] ?? ''),
                'email' => trim($data['email'] ?? ''),
                'id'    => $userId
            ]);
        } catch (PDOException $e) {
            error_log('Update Admin Profile Error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteAvatar(int $userId, string $oldAvatarPath): bool {
        if (!empty($oldAvatarPath)) {
            $fullPath = dirname(__DIR__, 2) . '/' . $oldAvatarPath;
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
        return true;
    }

    public function deleteAccount(int $userId, string $oldAvatarPath): bool {
        if (!empty($oldAvatarPath)) {
            $fullPath = dirname(__DIR__, 2) . '/' . $oldAvatarPath;
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
        $stmt = $this->pdo->prepare("DELETE FROM `user` WHERE id = :id");
        return $stmt->execute(['id' => $userId]);
    }
}