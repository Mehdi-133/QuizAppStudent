<?php

class Attempte{


      private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

       public function getResultsByStudentAndQuiz($studentId, $quizId)
    {
        $sql = "SELECT * FROM results 
                WHERE etudiant_id = ? AND quiz_id = ?
                LIMIT 1";

        $stmt = $this->db->query($sql, [$studentId, $quizId]);
        return $stmt->fetch(); 
    }

}





?>