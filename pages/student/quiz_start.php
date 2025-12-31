<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/teacher_classes/Quiz.php';
require_once '../../classes/teacher_classes/Question.php';

// // Vérifier que l'utilisateur est connecté
// Security::requireLogin();

// // Variables pour la navigation
// $currentPage = 'quizzes';
// $pageTitle = 'Passer un Quiz';

// // Récupérer l'ID du quiz
// $quizId = intval($_GET['id'] ?? 0);
// $userId = $_SESSION['user_id'];
// $userName = $_SESSION['user_nom'];

// // Récupérer le quiz et ses questions
// $quizObj = new Quiz();
// $quiz = $quizObj->getById($quizId);

// if (!$quiz || !$quiz['is_active']) {
//     $_SESSION['quiz_error'] = 'Quiz non disponible';
//     header('Location: dashboard.php');
//     exit();
// }

// $questionObj = new Question();
// $questions = $questionObj->getAllByQuiz($quizId);

// if (empty($questions)) {
//     $_SESSION['quiz_error'] = 'Ce quiz ne contient aucune question';
//     header('Location: dashboard.php');
//     exit();
// }

// // Durée du quiz en secondes (30 minutes par défaut)
// $time_limit = 1800; // 30 minutes
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_student.php'; ?>

<!-- Quiz Taking Interface -->
<div class="pt-16">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($quiz['titre']) ?></h1>
                    <p class="text-green-100">
                        <span class="font-semibold"><?= count($questions) ?> questions</span> •
                        <span><?= htmlspecialchars($quiz['categorie_nom'] ?? 'N/A') ?></span>
                    </p>
                    <p class="text-green-100 mt-2">
                        Question <span id="currentQuestion">1</span> sur <span id="totalQuestions"><?= count($questions) ?></span>
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-green-100 mb-1">Temps restant</div>
                    <div class="text-3xl font-bold" id="timer">30:00</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <form id="quizForm" action="../../actions/quiz_submit.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
            <input type="hidden" name="quiz_id" value="<?= $quizId ?>">
            <input type="hidden" name="time_taken" id="timeTaken" value="0">

            <div id="questionsContainer">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-slide <?= $index === 0 ? '' : 'hidden' ?>" data-question-index="<?= $index ?>">
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                                <?= htmlspecialchars($question['question']) ?>
                            </h3>

                            <div class="space-y-4">
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <label class="answer-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition block">
                                        <div class="flex items-center">
                                            <input type="radio"
                                                name="answers[<?= $question['id'] ?>]"
                                                value="<?= $i ?>"
                                                class="hidden answer-radio"
                                                required>
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 option-radio">
                                                <div class="w-4 h-4 rounded-full bg-green-600 hidden option-selected"></div>
                                            </div>
                                            <span class="text-lg"><?= htmlspecialchars($question['option' . $i]) ?></span>
                                        </div>
                                    </label>
                                <?php endfor; ?>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button"
                                    onclick="previousQuestion()"
                                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition <?= $index === 0 ? 'invisible' : '' ?>">
                                    <i class="fas fa-arrow-left mr-2"></i>Précédent
                                </button>

                                <?php if ($index === count($questions) - 1): ?>
                                    <button type="button"
                                        onclick="confirmSubmit()"
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                        <i class="fas fa-check mr-2"></i>Soumettre le Quiz
                                    </button>
                                <?php else: ?>
                                    <button type="button"
                                        onclick="nextQuestion()"
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        Suivant<i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>

        <!-- Progress Bar -->
        <div class="mt-6 bg-white rounded-lg p-4 shadow">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">Progression</span>
                <span class="text-sm text-gray-600" id="progressText">1 / <?= count($questions) ?></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progressBar" class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: <?= (1 / count($questions)) * 100 ?>%"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Configuration
    const totalQuestions = <?= count($questions) ?>;
    let currentQuestionIndex = 0;
    let timeLeft = <?= $time_limit ?>;
    let startTime = Date.now();
    let timerInterval;

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        startTimer();
        setupAnswerSelection();
    });

    // Timer
    function startTimer() {
        timerInterval = setInterval(function() {
            timeLeft--;
            updateTimerDisplay();

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert('Temps écoulé! Le quiz va être soumis automatiquement.');
                submitQuiz();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timer').textContent =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // Changer la couleur si moins de 5 minutes
        if (timeLeft < 300) {
            document.getElementById('timer').classList.add('text-red-300');
        }
    }

    // Gestion des réponses
    function setupAnswerSelection() {
        document.querySelectorAll('.answer-option').forEach(function(option) {
            option.addEventListener('click', function() {
                const radio = this.querySelector('.answer-radio');
                const slide = this.closest('.question-slide');

                // Décocher toutes les options de cette question
                slide.querySelectorAll('.answer-option').forEach(function(opt) {
                    opt.classList.remove('border-green-500', 'bg-green-50');
                    opt.querySelector('.option-selected').classList.add('hidden');
                });

                // Cocher l'option sélectionnée
                this.classList.add('border-green-500', 'bg-green-50');
                this.querySelector('.option-selected').classList.remove('hidden');
                radio.checked = true;
            });
        });
    }

    // Navigation
    function showQuestion(index) {
        document.querySelectorAll('.question-slide').forEach(function(slide, i) {
            if (i === index) {
                slide.classList.remove('hidden');
            } else {
                slide.classList.add('hidden');
            }
        });

        currentQuestionIndex = index;
        updateProgress();
    }

    function nextQuestion() {
        const currentSlide = document.querySelector(`.question-slide[data-question-index="${currentQuestionIndex}"]`);
        const radio = currentSlide.querySelector('.answer-radio:checked');

        if (!radio) {
            alert('Veuillez sélectionner une réponse avant de continuer.');
            return;
        }

        if (currentQuestionIndex < totalQuestions - 1) {
            showQuestion(currentQuestionIndex + 1);
        }
    }

    function previousQuestion() {
        if (currentQuestionIndex > 0) {
            showQuestion(currentQuestionIndex - 1);
        }
    }

    function updateProgress() {
        const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
        document.getElementById('progressText').textContent = `${currentQuestionIndex + 1} / ${totalQuestions}`;
        document.getElementById('currentQuestion').textContent = currentQuestionIndex + 1;
    }

    // Soumission
    function confirmSubmit() {
        // Vérifier que toutes les questions ont une réponse
        const allAnswered = checkAllAnswered();

        if (!allAnswered) {
            if (confirm('Vous n\'avez pas répondu à toutes les questions. Voulez-vous quand même soumettre le quiz?')) {
                submitQuiz();
            }
        } else {
            if (confirm('Êtes-vous sûr de vouloir soumettre ce quiz? Vous ne pourrez plus modifier vos réponses.')) {
                submitQuiz();
            }
        }
    }

    function checkAllAnswered() {
        let allAnswered = true;
        document.querySelectorAll('.question-slide').forEach(function(slide) {
            const radio = slide.querySelector('.answer-radio:checked');
            if (!radio) {
                allAnswered = false;
            }
        });
        return allAnswered;
    }

    function submitQuiz() {
        clearInterval(timerInterval);

        // Calculer le temps pris
        const timeTaken = Math.floor((Date.now() - startTime) / 1000);
        document.getElementById('timeTaken').value = timeTaken;

        // Soumettre le formulaire
        document.getElementById('quizForm').submit();
    }

    // Empêcher de quitter la page par accident
    window.addEventListener('beforeunload', function(e) {
        if (timeLeft > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>

<?php include '../partials/footer.php'; ?>