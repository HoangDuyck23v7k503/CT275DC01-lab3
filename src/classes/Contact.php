<?php
namespace CT275\Labs;

use PDO;

class Contact
{
    private PDO $db;
    public ?int $id = null;
    public string $name = '';
    public string $phone = '';
    public string $notes = '';
    public string $created_at = '';
    public string $updated_at = '';
    public ?string $avatar = null; // thêm thuộc tính avatar

    public function __construct(PDO $db) { $this->db = $db; }

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM contacts ORDER BY id DESC");
        $contacts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contacts[] = $this->fillFromDbRow($row);
        }
        return $contacts;
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    }

    public function paginate(int $offset, int $limit): array {
        $stmt = $this->db->prepare("SELECT * FROM contacts ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $contacts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contacts[] = $this->fillFromDbRow($row);
        }
        return $contacts;
    }

    public function find(int $id): ?Contact {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->fillFromDbRow($row) : null;
    }

    public function save(): bool {
        if ($this->id) {
            $stmt = $this->db->prepare(
                "UPDATE contacts SET name=:name, phone=:phone, notes=:notes, avatar=:avatar, updated_at=NOW() WHERE id=:id"
            );
            return $stmt->execute([
                ':name' => $this->name,
                ':phone' => $this->phone,
                ':notes' => $this->notes,
                ':avatar' => $this->avatar,
                ':id' => $this->id
            ]);
        } else {
            $stmt = $this->db->prepare(
                "INSERT INTO contacts (name, phone, notes, avatar, created_at, updated_at) 
                 VALUES (:name, :phone, :notes, :avatar, NOW(), NOW())"
            );
            $ok = $stmt->execute([
                ':name' => $this->name,
                ':phone' => $this->phone,
                ':notes' => $this->notes,
                ':avatar' => $this->avatar
            ]);
            if ($ok) $this->id = (int)$this->db->lastInsertId();
            return $ok;
        }
    }

    public function delete(): bool {
        if (!$this->id) return false;
        $stmt = $this->db->prepare("DELETE FROM contacts WHERE id=:id");
        return $stmt->execute([':id' => $this->id]);
    }

    protected function fillFromDbRow(array $row): Contact {
        $this->id = (int)$row['id'];
        $this->name = $row['name'];
        $this->phone = $row['phone'];
        $this->notes = $row['notes'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        $this->avatar = $row['avatar'] ?? null;
        return $this;
    }

    public function fill(array $data): Contact {
        $this->id = $data['id'] ?? $this->id;
        $this->name = $data['name'] ?? $this->name;
        $this->phone = $data['phone'] ?? $this->phone;
        $this->notes = $data['notes'] ?? $this->notes;
        $this->avatar = $data['avatar'] ?? $this->avatar;
        return $this;
    }
}
