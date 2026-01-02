<?php


class StudentQuestions
{


    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    public function getAllQuestion($quizId)
    {

        $sql = "SELECT 
        id,
        quiz_id,
        question,
        option1,
        option2,
        option3,
        option4
        FROM questions
        WHERE quiz_id = ?
        ORDER BY id ASC;";

        $result = $this->db->query($sql , [$quizId]);
        return $result->fetchAll();
    }
}
