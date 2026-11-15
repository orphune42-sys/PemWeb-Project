
<?php

class Opportunity
{
    private PDO $pdo;

    private array $tables = [
        'goals' => 'student_goals',
        'projects' => 'student_projects',
        'achievements' => 'student_achievements',
        'career' => 'career_plans'
    ];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function table(string $type): string
    {
        if (!isset($this->tables[$type])) {
            throw new InvalidArgumentException('Jenis data tidak valid.');
        }

        return $this->tables[$type];
    }

    public function getAll(string $type, int $userId): array
    {
        $table = $this->table($type);

        $stmt = $this->pdo->prepare(
            "SELECT * FROM `$table`
             WHERE user_id = :user_id
             ORDER BY id DESC"
        );

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(
        string $type,
        int $id,
        int $userId
    ): ?array {
        $table = $this->table($type);

        $stmt = $this->pdo->prepare(
            "SELECT * FROM `$table`
             WHERE id = :id AND user_id = :user_id"
        );

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function create(string $type, array $data): bool
    {
        $table = $this->table($type);

        $columns = array_keys($data);
        $fields = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $sql = "INSERT INTO `$table` ($fields)
                VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update(
        string $type,
        int $id,
        int $userId,
        array $data
    ): bool {
        $table = $this->table($type);

        $sets = [];

        foreach (array_keys($data) as $column) {
            $sets[] = "`$column` = :$column";
        }

        $setClause = implode(', ', $sets);

        $data['id'] = $id;
        $data['user_id'] = $userId;

        $sql = "UPDATE `$table`
                SET $setClause
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($data);

        return $stmt->rowCount() > 0;
    }

    public function delete(
        string $type,
        int $id,
        int $userId
    ): bool {
        $table = $this->table($type);

        $stmt = $this->pdo->prepare(
            "DELETE FROM `$table`
             WHERE id = :id AND user_id = :user_id"
        );

        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        return $stmt->rowCount() > 0;
    }
}
