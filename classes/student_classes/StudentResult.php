<?php

class Results
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

   public function addResult($studentId, $quizId, $score, $totalQuestions)
{
    $sql = "INSERT INTO results 
            (quiz_id, etudiant_id, score, total_questions, completed_at, created_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())";

    try {
        $stmt = $this->db->query($sql, [
            $quizId,
            $studentId,
            $score,
            $totalQuestions
        ]);

        return $stmt->rowCount() === 1;

    } catch (PDOException $e) {
        error_log("SQL ERROR: " . $e->getMessage());
        return false;
    }
}


    public function getResultsByStudent($studentId)
    {
        $sql = "SELECT r.*, q.titre, q.categorie_id, c.nom AS categorie_nom
                FROM results r
                JOIN quiz q ON r.quiz_id = q.id
                join categories c on q.categorie_id = c.id
                WHERE r.etudiant_id = ?
                ORDER BY r.created_at DESC";

        $result = $this->db->query($sql, [$studentId]);
        return $result->fetchAll();
    }

  
   public function countAttemptsByStudent($studentId)
{
    $sql = "SELECT COUNT(*) AS total 
            FROM results 
            WHERE etudiant_id = ?";

    $stmt = $this->db->query($sql, [$studentId]);
    $row = $stmt->fetch();

    return $row['total'] ?? 0;
}

}

