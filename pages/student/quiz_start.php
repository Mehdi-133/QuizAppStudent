<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/student_classes/StudentQuestion.php';

Security::requireStudent();

$quizId = isset($_GET['id']) ? $_GET['id'] : 0;
if ($quizId <= 0) die('Quiz invalide');


$questionObj = new StudentQuestions();
$questions = $questionObj->getAllQuestion($quizId);
$totalQuestions = count($questions);


?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_student.php'; ?>

<div class="pt-16">
    <div class="max-w-3xl mx-auto px-4 py-8">

        <!-- En-tête -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Quiz – Question <span id="currentIndex">1</span> / <?= $totalQuestions ?>
            </h2>
        </div>

        <!-- Formulaire du quiz -->
        <form id="quizForm" method="POST" action="../../actions/quiz_submit.php">

            <input type="hidden" name="quiz_id" value="<?= $quizId ?>">

            <!-- Questions -->
            <?php foreach ($questions as $index => $question): ?>
                <div class="question-slide <?= $index === 0 ? '' : 'hidden' ?>" data-index="<?= $index ?>">

                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-lg font-semibold mb-4">
                            <?= htmlspecialchars($question['question']) ?>
                        </p>

                        <div class="space-y-3">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <label class="block p-3 border rounded cursor-pointer hover:bg-gray-50">
                                    <input type="radio"
                                           name="answers[<?= $question['id'] ?>]"
                                           value="<?= $i ?>"
                                           class="mr-2">
                                    <?= htmlspecialchars($question['option' . $i]) ?>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>

            <!-- Navigation -->
            <div class="flex justify-between mt-6">

                <button type="submit"
                        id="prevBtn"
                        onclick="previousQuestion()"
                        class="px-5 py-2 bg-gray-300 rounded hidden">
                    Précédent
                </button>

                <button type="submit"
                        id="nextBtn"
                        onclick="nextQuestion()"
                        class="px-5 py-2 bg-green-600 text-white rounded">
                    Suivant
                </button>

                <button type="submit"
                        id="submitBtn"
                        class="px-5 py-2 bg-blue-600 text-white rounded hidden">
                    Terminer le quiz
                </button>

            </div>

        </form>
    </div>
</div>

<script>
    let currentQuestion = 0;
    const totalQuestions = <?= $totalQuestions ?>;

    function showQuestion(index) {
        document.querySelectorAll('.question-slide').forEach((slide, i) => {
            slide.classList.toggle('hidden', i !== index);
        });

        document.getElementById('currentIndex').textContent = index + 1;

        document.getElementById('prevBtn').classList.toggle('hidden', index === 0);
        document.getElementById('nextBtn').classList.toggle('hidden', index === totalQuestions - 1);
        document.getElementById('submitBtn').classList.toggle('hidden', index !== totalQuestions - 1);
    }

    function nextQuestion() {
        const currentSlide = document.querySelector(`.question-slide[data-index="${currentQuestion}"]`);
        const checked = currentSlide.querySelector('input[type="radio"]:checked');

        if (!checked) {
            alert('Veuillez sélectionner une réponse');
            return;
        }

        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
        }
    }

    function previousQuestion() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    }
</script>

<?php include '../partials/footer.php'; ?>
