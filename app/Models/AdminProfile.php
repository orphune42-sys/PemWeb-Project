<?php
class AdminProfile
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProfile(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.username, u.email, u.name, u.role, 
                a.admin_id, a.position, a.phone, a.address, a.birth_date, a.gender, a.avatar 
            FROM `user` u 
            LEFT JOIN `admin` a ON u.id = a.user_id 
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $userId]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function updateProfile(int $userId, array $data, string $newAvatarPath): bool
    {
        try {
            $this->pdo->beginTransaction();

            $stmtUser = $this->pdo->prepare("UPDATE `user` SET name = :name, email = :email WHERE id = :id");
            $stmtUser->execute([
                'name'  => trim($data['name'] ?? ''),
                'email' => trim($data['email'] ?? ''),
                'id'    => $userId
            ]);

            $stmtAdmin = $this->pdo->prepare("INSERT INTO `admin` (user_id, phone, address, birth_date, gender, admin_id, position, avatar)
                VALUES (:user_id, :phone, :address, :birth_date, :gender, :admin_id, :position, :avatar)
                ON DUPLICATE KEY UPDATE phone = VALUES(phone), address = VALUES(address), birth_date = VALUES(birth_date),
                    gender = VALUES(gender), admin_id = VALUES(admin_id), position = VALUES(position), avatar = VALUES(avatar)
            ");
            $stmtAdmin->execute([
                'user_id'    => $userId,
                'phone'      => trim($data['phone'] ?? ''),
                'address'    => trim($data['address'] ?? ''),
                'birth_date' => trim($data['birth_date'] ?? ''),
                'gender'     => trim($data['gender'] ?? ''),
                'admin_id'   => trim($data['admin_id'] ?? ''),
                'position'   => trim($data['position'] ?? ''),
                'avatar'     => $newAvatarPath
            ]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Update Admin Profile Error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteAvatar(int $userId, string $oldAvatarPath): bool
    {
        if (!empty($oldAvatarPath)) {
            $fullPath = dirname(__DIR__, 2) . '/' . $oldAvatarPath;
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
        $stmt = $this->pdo->prepare("UPDATE `admin` SET avatar = '' WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);
    }

    public function deleteAccount(int $userId, string $oldAvatarPath): bool
    {
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