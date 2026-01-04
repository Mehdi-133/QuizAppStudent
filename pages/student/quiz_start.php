<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/student_classes/StudentQuestion.php';
require_once '../../classes/student_classes/StudentResult.php'; 

Security::requireStudent();

$quizId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($quizId <= 0) {
    die('Quiz invalide');
}

$studentId = $_SESSION['user_id'];

$resultsObj = new Results();
$existingResult = $resultsObj->getResultsByStudentAndQuiz($studentId, $quizId);

if ($existingResult) {
    die('Vous avez déjà passé ce quiz. Vous ne pouvez pas le refaire.');
}

$questionObj = new StudentQuestions();
$questions = $questionObj->getAllQuestion($quizId);
$totalQuestions = count($questions);

$correctAnswers = [];
foreach ($questions as $q) {
    $correctAnswers[$q['id']] = $q['correct_option'];
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_student.php'; ?>

<div class="pt-16">
    <div id="quizContainer" class="max-w-3xl mx-auto px-4 py-8">

        <h2 class="text-2xl font-bold mb-6">
            Quiz – Question <span id="currentIndex">1</span> / <?= $totalQuestions ?>
        </h2>

        <?php foreach ($questions as $index => $question): ?>
            <div class="question-slide <?= $index === 0 ? '' : 'hidden' ?>" data-index="<?= $index ?>">
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-lg font-semibold mb-4">
                        <?= htmlspecialchars($question['question']) ?>
                    </p>

                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <label class="block p-3 border rounded cursor-pointer">
                            <input type="radio"
                                name="answers[<?= $question['id'] ?>]"
                                value="<?= $i ?>" class="mr-2">
                            <?= htmlspecialchars($question['option' . $i]) ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="flex justify-between mt-6">
            <button id="prevBtn" onclick="previousQuestion()" class="hidden px-4 py-2 bg-gray-300 rounded">
                Précédent
            </button>

            <button id="nextBtn" onclick="nextQuestion()" class="px-4 py-2 bg-green-600 text-white rounded">
                Suivant
            </button>

            <button id="submitBtn" onclick="showResult()" class="hidden px-4 py-2 bg-blue-600 text-white rounded">
                Terminer
            </button>
        </div>
    </div>

    <div id="resultContainer" class="hidden max-w-3xl mx-auto px-4 py-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Quiz terminé</h2>
        <div class="text-5xl font-bold text-green-600">
            <span id="scoreDisplay">0</span> / <?= $totalQuestions ?>
        </div>
        <a href="dashboard.php" class="inline-block mt-6 px-6 py-3 bg-indigo-600 text-white rounded">
            Retour au tableau de bord
        </a>
    </div>
</div>

<script>
let currentQuestion = 0;
const totalQuestions = <?= $totalQuestions ?>;
const correctAnswers = <?= json_encode($correctAnswers) ?>;
const quizId = <?= $quizId ?>;
const studentId = <?= $studentId ?>;

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
    currentQuestion++;
    showQuestion(currentQuestion);
}

function previousQuestion() {
    currentQuestion--;
    showQuestion(currentQuestion);
}

function showResult() {
    let score = 0;

    for (const [qid, correct] of Object.entries(correctAnswers)) {
        const checked = document.querySelector(`input[name="answers[${qid}]"]:checked`);
        if (checked && parseInt(checked.value) === parseInt(correct)) {
            score++;
        }
    }

    document.getElementById('quizContainer').classList.add('hidden');
    document.getElementById('resultContainer').classList.remove('hidden');
    document.getElementById('scoreDisplay').textContent = score;

    fetch('../../actions/student_action/quiz_start_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            studentId: studentId,
            quizId: quizId,
            score: score,
            totalQuestions: totalQuestions,
            timeTaken: '00:00:00'
        })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(err => {
        alert('Erreur serveur');
        console.error(err);
    });
}
</script>

<?php include '../partials/footer.php'; ?>
