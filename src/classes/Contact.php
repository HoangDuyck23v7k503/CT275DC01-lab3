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

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $contacts = [];
        $stmt = $this->db->prepare('SELECT * FROM contacts ORDER BY id DESC');
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $contact = new Contact($this->db);
            $contact->fillFromDbRow($row);
            $contacts[] = $contact;
        }
        return $contacts;
    }

    public function count(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM contacts');
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $offset, int $limit): array
    {
        $contacts = [];
        $stmt = $this->db->prepare('SELECT * FROM contacts LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $contact = new Contact($this->db);
            $contact->fillFromDbRow($row);
            $contacts[] = $contact;
        }
        return $contacts;
    }

    public function find(int $id): ?Contact
    {
        $stmt = $this->db->prepare('SELECT * FROM contacts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch()) {
            $this->fillFromDbRow($row);
            return $this;
        }
        return null;
    }

    public function save(): bool
    {
        if ($this->id) {
            $stmt = $this->db->prepare(
                'UPDATE contacts SET name=:name, phone=:phone, notes=:notes, updated_at=NOW() WHERE id=:id'
            );
            return $stmt->execute([
                'name' => $this->name,
                'phone' => $this->phone,
                'notes' => $this->notes,
                'id' => $this->id
            ]);
        } else {
            $stmt = $this->db->prepare(
                'INSERT INTO contacts (name, phone, notes, created_at, updated_at) VALUES (:name, :phone, :notes, NOW(), NOW())'
            );
            $result = $stmt->execute([
                'name' => $this->name,
                'phone' => $this->phone,
                'notes' => $this->notes
            ]);
            if ($result) {
                $this->id = (int)$this->db->lastInsertId();
            }
            return $result;
        }
    }

    public function delete(): bool
    {
        $stmt = $this->db->prepare('DELETE FROM contacts WHERE id=:id');
        return $stmt->execute(['id' => $this->id]);
    }

    protected function fillFromDbRow(array $row): Contact
    {
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->phone = $row['phone'];
        $this->notes = $row['notes'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        return $this;
    }

    public function fill(array $data): Contact
    {
        $this->name = $data['name'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->notes = $data['notes'] ?? '';
        return $this;
    }
}
