<?php
class MahasiswaProfile {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getByUserId(int $userId): ?array {
        $sql = "SELECT
                    u.id AS user_id,
                    u.name,
                    u.email,
                    u.username,
                    m.id AS mahasiswa_id,
                    m.nim,
                    m.phone,
                    m.address,
                    m.gender,
                    m.birth_date,
                    m.university,
                    m.faculty,
                    m.major,
                    m.year,
                    m.semester,
                    m.gpa,
                    m.avatar
                FROM `user` u
                INNER JOIN mahasiswa m
                    ON m.user_id = u.id
                WHERE u.id = :user_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId
        ]);

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        return $profile ?: null;
    }

    
    public function updatePersonal(
        int $userId,
        string $name,
        string $nim,
        string $phone,
        string $address,
        string $gender,
        ?string $birthDate
    ): bool {
        try {
            // Cek apakah NIM sudah digunakan mahasiswa lain
            $sqlCheck = "SELECT id
                        FROM mahasiswa
                        WHERE nim = :nim
                        AND user_id != :user_id
                        LIMIT 1";

            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                'nim' => $nim,
                'user_id' => $userId
            ]);

            if ($stmtCheck->fetch()) {
                throw new DomainException(
                    'NIM sudah digunakan oleh mahasiswa lain.'
                );
            }

            $this->pdo->beginTransaction();

            $sqlUser = "UPDATE `user`
                        SET name = :name
                        WHERE id = :user_id";

            $stmtUser = $this->pdo->prepare($sqlUser);
            $stmtUser->execute([
                'name' => $name,
                'user_id' => $userId
            ]);

            $sqlMahasiswa = "UPDATE mahasiswa
                            SET nim = :nim,
                                phone = :phone,
                                address = :address,
                                gender = :gender,
                                birth_date = :birth_date
                            WHERE user_id = :user_id";

            $stmtMahasiswa = $this->pdo->prepare($sqlMahasiswa);
            $stmtMahasiswa->execute([
                'nim' => $nim,
                'phone' => $phone,
                'address' => $address,
                'gender' => $gender,
                'birth_date' => $birthDate,
                'user_id' => $userId
            ]);

            $this->pdo->commit();
            return true;

        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function updateAcademic(
        int $userId,
        string $university,
        string $faculty,
        string $major,
        int $year,
        int $semester,
        float $gpa
    ): bool {
        $sql = "UPDATE mahasiswa
                SET university = :university,
                    faculty = :faculty,
                    major = :major,
                    year = :year,
                    semester = :semester,
                    gpa = :gpa
                WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'university' => $university,
            'faculty' => $faculty,
            'major' => $major,
            'year' => $year,
            'semester' => $semester,
            'gpa' => $gpa,
            'user_id' => $userId
        ]);
    }
}
