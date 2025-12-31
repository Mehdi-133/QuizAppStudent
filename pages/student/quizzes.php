<?php


require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';



Security::requireStudent();

// Variables pour la navigation
$currentPage = 'quizzes';
$pageTitle = 'quizzes';

// Récupérer les données
$studentId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'];


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

        <!-- Filters -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div class="flex gap-3">
                <select class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option>Toutes les catégories</option>
                    <option>HTML / CSS</option>
                    <option>JavaScript</option>
                    <option>PHP</option>
                </select>

                <select class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option>Tous les niveaux</option>
                    <option>Débutant</option>
                    <option>Intermédiaire</option>
                    <option>Avancé</option>
                </select>
            </div>

            <input
                type="text"
                placeholder="Rechercher un quiz..."
                class="border rounded-lg px-4 py-2 w-full md:w-64 focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Quiz Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Quiz Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition">
                <div class="p-6">
                    <span class="inline-block mb-3 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        HTML / CSS
                    </span>

                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Les Bases de HTML5
                    </h3>

                    <p class="text-gray-600 text-sm mb-4">
                        Testez vos connaissances sur les fondamentaux du HTML5.
                    </p>

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>
                            <i class="fas fa-question-circle mr-1"></i> 20 Questions
                        </span>
                        <span>
                            <i class="fas fa-clock mr-1"></i> 15 min
                        </span>
                    </div>

                    <a href="#"
                        class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold">
                        Commencer le Quiz
                    </a>
                </div>
            </div>

            <?php include '../partials/footer.php'; ?>