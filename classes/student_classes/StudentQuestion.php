<?php

class StudentQuiz
{


    private $db;

    public function  __construct()
    {

        $this->db = Database::getInstance();
    }

    public function getByCategory($categoryId)
    {

        $sql = "SELECT q.id , q.titre , q.description , q.time_limit  , 
                    COUNT(questions.id ) AS Question_count
                    FROM quiz q  
                    LEFT JOIN questions ON question.quiz_id = q.id
                    WHERE q.categorie_id  = category_id
                    AND q.is_active = 1
                    GROUP BY q.id
                    ORDER BY q.created_at DESC";

        $result = $this->db->query($sql);
        return $result->fetchAll();
    }
}
