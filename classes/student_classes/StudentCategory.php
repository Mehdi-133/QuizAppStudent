<?php

class StudentCategory
{

    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

   
    public function getAll()
    {
        $sql = "SELECT c.*,
                       COUNT(q.id) as quiz_count
                FROM categories c
                LEFT JOIN quiz q ON c.id = q.categorie_id
                WHERE is_active = 1
                GROUP BY c.id
                ORDER BY c.nom ASC";

        $result = $this->db->query($sql);
        return $result->fetchAll();
    }
}









