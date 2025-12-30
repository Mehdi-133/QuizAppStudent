<?php

$userName = $userName ?? $_SESSION['user_nom'] ?? 'User';
$initials = strtoupper(substr($userName, 0, 1) . substr(explode(' ', $userName)[1] ?? '', 0, 1));


$basePath = '';
if (strpos($_SERVER['PHP_SELF'], '/students/') !== false) {
    $basePath = '../';
}
?>

<nav class="bg-white shadow-lg fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">

                <div class="flex-shrink-0 flex items-center">
                    <i class="fas fa-graduation-cap text-3xl text-green-600"></i>
                    <span class="ml-2 text-2xl font-bold text-gray-900">Qodex</span>
                    <span class="ml-3 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Étudiant</span>
                </div>

                <div class="hidden md:ml-10 md:flex md:space-x-8">

                    <a href="<?= $basePath ?>students/dashboard.php"
                        class="<?= ($currentPage ?? '') === 'dashboard' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i class="fas fa-home mr-2"></i>Tableau de bord
                    </a>


                    <a href="<?= $basePath ?>students/quizzes.php"
                        class="<?= ($currentPage ?? '') === 'quizzes' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i class="fas fa-clipboard-list mr-2"></i>Quiz Disponibles
                    </a>


                    <a href="<?= $basePath ?>students/quiz_result.php"
                        class="<?= ($currentPage ?? '') === 'resultats' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i class="fas fa-chart-bar mr-2"></i>Mes Résultats
                    </a>


                    <a href="<?= $basePath ?>students/history.php"
                        class="<?= ($currentPage ?? '') === 'history' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i class="fas fa-history mr-2"></i>Historique
                    </a>
                </div>
            </div>


            <div class="flex items-center">
                <div class="flex items-center space-x-4">

                    <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
                        <?= $initials ?>
                    </div>

                    <div class="hidden md:block">
                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($userName) ?></div>
                        <div class="text-xs text-gray-500">Étudiant</div>
                    </div>


                    <a href="<?= $basePath ?>auth/logout.php?token=<?= Security::generateCSRFToken() ?>"
                        class="text-red-600 hover:text-red-700" title="Déconnexion">
                        <i class="fas fa-sign-out-alt text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="md:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="<?= $basePath ?>students/dashboard.php"
                class="<?= ($currentPage ?? '') === 'dashboard' ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
            <a href="<?= $basePath ?>students/quizzes.php"
                class="<?= ($currentPage ?? '') === 'quizzes' ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                <i class="fas fa-clipboard-list mr-2"></i>Quiz Disponibles
            </a>
            <a href="<?= $basePath ?>students/quiz_result.php"
                class="<?= ($currentPage ?? '') === 'resultats' ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                <i class="fas fa-chart-bar mr-2"></i>Mes Résultats
            </a>
            <a href="<?= $basePath ?>students/history.php"
                class="<?= ($currentPage ?? '') === 'history' ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                <i class="fas fa-history mr-2"></i>Historique
            </a>
        </div>
    </div>
</nav>