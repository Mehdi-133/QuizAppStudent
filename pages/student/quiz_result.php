<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/student_classes/StudentResult.php';

Security::requireStudent();


$currentPage = 'quiz_result';
$pageTitle = 'quiz_result';

$studentId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'];

$resultsObj = new Results();
$results = $resultsObj->getResultsByStudent($studentId);

$totalQuizzes = count($results);
$totalScore = 0;
$totalQuestions = 0;
$passedCount = 0;

foreach($results as $res){
    $totalScore += $res['score'];
    $totalQuestions += $res['total_questions'];
    if($res['score'] >= ($res['total_questions']/2)) {
        $passedCount++;
    }
}


$averageScore = $totalQuestions > 0 ? round(($totalScore / $totalQuestions) * 20, 1) : 0;

$successRate = $totalQuizzes > 0 ? round(($passedCount / $totalQuizzes) * 100) : 0;

include '../partials/header.php';
include '../partials/nav_student.php';

?>

<!-- Student Results -->
<div id="studentResults" class="student-section pt-16">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <button onclick="showStudentSection('studentDashboard')" class="text-white hover:text-green-100 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
            </button>
            <h1 class="text-4xl font-bold mb-2">Mes Résultats</h1>
            <p class="text-xl text-green-100">Suivez votre progression et vos performances</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Quiz Complétés</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $totalQuizzes ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Moyenne</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $averageScore ?>/20</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-star text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Taux Réussite</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $successRate ?>%</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

    
    </div>
</div>

<!-- Results Table -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Temps</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(!empty($results)): ?>
                    <?php foreach($results as $res):
                        $statusText = $res['score'] >= ($res['total_questions']/2) ? 'Réussi' : 'Échoué';
                        $statusColor = $res['score'] >= ($res['total_questions']/2) ? 'green' : 'red';
                        $completedAt = date('d M Y', strtotime($res['completed_at']));
                        $timeTaken = $res['time_taken'] ?? '00:00';
                        $categoryName = $res['categorie_id'] ?? 'Général';
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($res['titre']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($categoryName) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-<?= $statusColor ?>-600">
                                <?= $res['score'] ?>/<?= $res['total_questions'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $completedAt ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $timeTaken ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                <i class="fas <?= $statusColor === 'green' ? 'fa-check' : 'fa-times' ?> mr-1"></i>
                                <?= $statusText ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun résultat pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
