<?php
class AdminProfile {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getProfile(int $userId): ? array
    {
        $stmt = $this->pdo->prepare("SELECT 
            u.id, u.name, u.email, u.username, u.role, 
            a.admin_id, a.position, a.phone, a.address, a.birth_date, a.gender,  a.avatar
            FROM `user` u
            LEFT JOIN `admin` a ON u.id = a.user_id
            WHERE u.id = :id
            Limit 1");
        $stmt->execute(['id' => $userId]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function updateProfile(int $userId, array $data, string $newAvatarPath): bool {
        try {
            $this->pdo->beginTransaction();
            $stmtUser = $this->pdo->prepare("
                UPDATE `user` 
                SET name = :name, 
                email = :email 
                WHERE id = :id");
            $stmtUser->execute([
                'name'  => trim($data['name'] ?? ''),
                'email' => trim($data['email'] ?? ''),
                'id'    => $userId
            ]);

            $stmtCheck = $this->pdo->prepare("
                SELECT id FROM `admin` 
                WHERE user_id = :user_id");
            $stmtCheck->execute(['user_id' => $userId]);
            $adminExist = $stmtCheck->fetch();

            if ($adminExist) {
                $stmtAdmin = $this->pdo->prepare("
                    UPDATE `admin` SET 
                        admin_id   = :admin_id,
                        position   = :position,
                        phone      = :phone, 
                        address    = :address, 
                        birth_date = :birth_date, 
                        gender     = :gender, 
                        avatar     = :avatar 
                    WHERE user_id = :user_id
                ");
            } else {
                $stmtAdmin = $this->pdo->prepare("
                    INSERT INTO `admin` (user_id, admin_id, position, phone, address, birth_date, gender, avatar) VALUES (
                        :user_id, :phone, 
                        :address, :birth_date, 
                        :gender, :avatar)");
            }
                $stmtAdmin->execute([
                    'user_id'    => $userId,
                    'admin_id'   => trim($data['admin_id'] ?? ''),
                    'position'   => trim($data['position'] ?? ''),
                    'phone'      => trim($data['phone'] ?? ''),
                    'address'    => trim($data['address'] ?? ''),
                    'birth_date' => trim($data['birth_date'] ?? ''),
                    'gender'     => trim($data['gender'] ?? ''),
                    'avatar'     => $newAvatarPath
                ]);

                $this->pdo->commit();
                return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
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
        $smtmt = $this->pdo->prepare("
            UPDATE `admin` SET 
                avatar = NULL 
                WHERE user_id = :user_id");
        return$smtmt->execute(['user_id' => $userId]);
    }

    public function deleteAccount(int $userId, string $oldAvatarPath): bool {
        if (!empty($oldAvatarPath)) {
            $fullPath = dirname(__DIR__, 2) . '/' . $oldAvatarPath;
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
        $this->pdo->prepare("
            DELETE FROM `admin` 
            WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        return true;
    }
}