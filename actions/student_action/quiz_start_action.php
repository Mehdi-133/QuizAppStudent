<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/student_classes/StudentResult.php';

try {
   
    Security::requireStudent();


    $rawInput = file_get_contents('php://input');
    error_log("Raw input: " . $rawInput);

    $data = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON');
    }

    $requiredFields = ['quizId', 'score', 'totalQuestions'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $studentId = $_SESSION['user_id'];
    $quizId = (int)$data['quizId'];
    $score = (int)$data['score'];
    $totalQuestions = (int)$data['totalQuestions'];
    $timeTaken = $data['timeTaken'] ?? '00:00:00';

    error_log("Saving result: student=$studentId quiz=$quizId score=$score/$totalQuestions");

    $results = new Results();
   $inserted = $results->addResult(
    $studentId,
    $quizId,
    $score,
    $totalQuestions
);
    if (!$inserted) {
        throw new Exception('Database insertion failed');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Result saved successfully'
    ]);

} catch (Exception $e) {
    error_log("quiz_start_action error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
