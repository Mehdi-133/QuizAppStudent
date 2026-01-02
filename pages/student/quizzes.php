<?php


require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/student_classes/StudentQuiz.php';



Security::requireStudent();

// echo $category_id;

// Variables pour la navigation
$currentPage = 'quizzes';
$pageTitle = 'quizzes';

// Récupérer les données
$studentId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'];

$quizzes = [];
$StudentQuiz = new StudentQuiz();

if (isset($_GET['id'])) {
    $categoryId =  $_GET['id'];
    $quizzes = $StudentQuiz->getByCategory($categoryId);
} else {
    $quizzes = $StudentQuiz->getAllQuiz();
}



include '../partials/header.php';
include '../partials/nav_student.php';
?>
<div id="studentQuizzes" class="student-section pt-16">

    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <h1 class="text-4xl font-bold mb-2">Quiz Disponibles</h1>
            <p class="text-indigo-100 text-lg">
                Choisissez un quiz et testez vos connaissances 🚀
            </p>
        </div>
    </div>



    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <?php if (empty($quizzes)): ?>
            <p class="text-gray-500">Aucun quiz trouvé pour cette catégorie.</p>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            foreach ($quizzes as $quiz):
            ?>
                <!-- Quiz Cards -->


                <!-- Quiz Card -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition">
                    <div class="p-6">


                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?= $quiz["titre"]; ?>
                        </h3>

                        <p class="text-gray-600 text-sm mb-4">
                            <?= $quiz["description"]; ?>
                        </p>

                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>
                                <i class="fas fa-question-circle mr-1"></i> <?= $quiz["Question_count"]; ?>
                            </span>
                        </div>

                        <a href="quiz_start.php?id=<?=$quiz['id']?>"
                            class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold">
                            Commencer le Quiz
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php include '../partials/footer.php'; ?>