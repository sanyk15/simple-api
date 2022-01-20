<?php

namespace Src\TableGateway;

use PDO;
use PDOException;

class AdGateway
{
    private ?PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function find($id): array
    {
        $statement = "
            SELECT id, text, banner
            FROM ads
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input): string
    {
        $statement = "
            INSERT INTO `ads` (`text`, `price`, `limit`, `banner`)
            VALUES (:text, :price, :limit, :banner);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'text' => $input['text'],
                'price' => $input['price'],
                'limit' => $input['limit'] ?? null,
                'banner' => $input['banner'] ?? null,
            ));

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, array $input): int
    {
        $statement = "
            UPDATE `ads`
            SET 
                `text` = :text,
                `price`  = :price,
                `limit` = :limit,
                `banner` = :banner
            WHERE `id` = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int)$id,
                'text' => $input['text'],
                'price' => $input['price'],
                'limit' => $input['limit'] ?? 0,
                'banner' => $input['banner'] ?? null,
            ));

            return $id;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id): int
    {
        $statement = "
            DELETE FROM ads
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));

            return $id;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function relevant()
    {
        $statement = "
            SELECT *
            FROM `ads`
            WHERE `limit` > 0
            ORDER BY `price` DESC 
            LIMIT 1
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}