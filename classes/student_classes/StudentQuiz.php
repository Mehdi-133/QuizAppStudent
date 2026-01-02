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

        $sql = "SELECT 
    quiz.id,
    quiz.titre,
    quiz.description,
    quiz.created_at,
    COUNT(questions.id) AS Question_count
    FROM quiz
    LEFT JOIN questions ON quiz.id = questions.quiz_id
    WHERE quiz.is_active = 1
    AND quiz.categorie_id = ?
    GROUP BY quiz.id
    ORDER BY quiz.created_at DESC";

        $result = $this->db->query($sql, [$categoryId]);
        return $result->fetchAll();
    }

    public function getAllQuiz(){
        $sql = "SELECT 
    quiz.id,
    quiz.titre,
    quiz.description,
    quiz.created_at,
    COUNT(questions.id) AS Question_count
    FROM quiz
    LEFT JOIN questions ON quiz.id = questions.quiz_id
    WHERE quiz.is_active = 1
    GROUP BY quiz.id
    ORDER BY quiz.created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetchAll();
    }
}


