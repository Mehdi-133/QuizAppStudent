<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';

include '../partials/header.php';
include '../partials/nav_student.php';

?>

<!-- HISTORY SECTION -->
<div id="studentHistory" class="student-section">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <button onclick="showStudentSection('studentDashboard')" class="text-white hover:text-green-100 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
            </button>
            <h1 class="text-4xl font-bold mb-2">Mon Historique de Quiz</h1>
            <p class="text-xl text-green-100">Consultez tous vos quiz passés</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Stats or summary cards (optional) -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Quiz Complétés</p>
                        <p class="text-3xl font-bold text-gray-900" id="historyTotalCompleted">0</p>
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
                        <p class="text-3xl font-bold text-gray-900" id="historyAverage">0/0</p>
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
                        <p class="text-3xl font-bold text-gray-900" id="historySuccessRate">0%</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Example static rows (replace via JS/PHP) -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Les Bases de HTML5</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">HTML/CSS</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">18/20</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04 Déc 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Réussi
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">JavaScript Fondamentaux</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">JavaScript</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">8/20</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">02 Déc 2024</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Échoué
                                </span>
                            </td>
                        </tr>
                        <!-- END examples -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include '../partials/footer.php'; ?>