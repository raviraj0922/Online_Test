<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$user_email = $_SESSION['email'];

// Database connection
$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize 30-min timer when test starts
/**
 * Start 30-min timer if not started; store in DB.
 * IMPORTANT: We will ALWAYS compute remaining seconds on the server to avoid client clock issues.
 */
if (!isset($_SESSION['exam_started'])) {
  $check = $conn->prepare("SELECT exam_end_time FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $row = $check->get_result()->fetch_assoc();
  $check->close();

  $exam_end_time = $row['exam_end_time'] ?? null;

  if (!$exam_end_time) {
      // 30 minutes from now (server time)
      $exam_end_time = date("Y-m-d H:i:s", time() + 1800);
      $upd = $conn->prepare("UPDATE users SET exam_end_time=? WHERE email=?");
      $upd->bind_param("ss", $exam_end_time, $email);
      $upd->execute();
      $upd->close();
  }

  $_SESSION['exam_started']  = true;
  $_SESSION['exam_end_time'] = $exam_end_time;
} else {
  $exam_end_time = $_SESSION['exam_end_time'];
}

/**
* Compute remaining seconds on the server (authoritative).
*/
$remaining_seconds = max(0, strtotime($exam_end_time) - time());

if ($remaining_seconds <= 0) {

  // ✅ Mark exam as completed
  $done = $conn->prepare("UPDATE users SET exam_completed = 1 WHERE email=?");
  $done->bind_param("s", $email);
  $done->execute();
  $done->close();

  unset($_SESSION['exam_started']);
  unset($_SESSION['exam_end_time']);
  unset($_SESSION['stage']);

  header("Location: result.php");
  exit();
}

// ✅ Fetch exam status
$status = $conn->prepare("SELECT exam_completed FROM users WHERE email=?");
$status->bind_param("s", $email);
$status->execute();
$completed = $status->get_result()->fetch_assoc()['exam_completed'] ?? 0;
$status->close();

// ✅ If exam completed → redirect to score page
if ($completed == 1) {
    header("Location: result.php");
    exit();
}

/**
 * Stage definitions
 */
$stages = ["Stage 1", "Stage 2", "Stage 3", "Stage 4", "Stage 5"];
$stage_mapping = [
    "Stage 1" => "Quantitative Aptitude & Numerical Ability (6 Questions)",
    "Stage 2" => "Logical Ability & Reasoning (6 Questions)",
    "Stage 3" => "General Knowledge (7 Questions)",
    "Stage 4" => "English Language (6 Questions)",
    "Stage 5" => "Final Submission"
];

// Initialize stage in session
if (!isset($_SESSION['stage']) || !in_array($_SESSION['stage'], $stages)) {
    $_SESSION['stage'] = "Stage 1";
}
$current_stage = $_SESSION['stage'];
$db_stage = $stage_mapping[$current_stage];

/**
 * POST handler
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($current_stage !== "Stage 5") {
        // Get question IDs for current stage
        $question_ids = [];
        $qid_stmt = $conn->prepare("SELECT id FROM questions WHERE stage = ?");
        $qid_stmt->bind_param("s", $db_stage);
        $qid_stmt->execute();
        $qid_res = $qid_stmt->get_result();
        while ($r = $qid_res->fetch_assoc()) {
            $question_ids[] = (int)$r['id'];
        }
        $qid_stmt->close();

        // Fetch existing answers
        $existing_answers = [];
        $ea_stmt = $conn->prepare("SELECT question_id, answer FROM user_answers WHERE user_email = ?");
        $ea_stmt->bind_param("s", $email);
        $ea_stmt->execute();
        $ea_res = $ea_stmt->get_result();
        while ($r = $ea_res->fetch_assoc()) {
            $existing_answers[(int)$r['question_id']] = $r['answer'];
        }
        $ea_stmt->close();

        // Save posted answers
        if (isset($_POST['answers']) && is_array($_POST['answers'])) {
            foreach ($_POST['answers'] as $qid => $ans) {
                $qid = (int)$qid;
                if (!in_array($qid, $question_ids, true)) continue;
                $ans = trim($ans);
                if ($ans === "") continue;

                if (!isset($existing_answers[$qid]) || $existing_answers[$qid] !== $ans) {
                    $up = $conn->prepare("
                        INSERT INTO user_answers (user_email, question_id, answer)
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE answer = VALUES(answer)
                    ");
                    $up->bind_param("sis", $email, $qid, $ans);
                    $up->execute();
                    $up->close();
                }
            }
        }
    }

    // Navigation
    $current_index = array_search($_SESSION['stage'], $stages);
    if (isset($_POST['previous'])) {
        if ($current_index > 0) $_SESSION['stage'] = $stages[$current_index - 1];
    } else {
      if ($current_stage === "Stage 5" && isset($_POST['confirm_final'])) {

        // ✅ Mark exam completed in database
        $complete = $conn->prepare("UPDATE users SET exam_completed = 1 WHERE email = ?");
        $complete->bind_param("s", $email);
        $complete->execute();
        $complete->close();
    
        // ✅ Remove timer session so it doesn’t start again
        unset($_SESSION['exam_started']);
        unset($_SESSION['exam_end_time']);
        unset($_SESSION['stage']); // optional: clear stage
    
        header("Location: result.php"); // ✅ redirect to score page
        exit();
      }
        if ($current_index < count($stages) - 1) {
            $_SESSION['stage'] = $stages[$current_index + 1];
        }
    }

    header("Location: form.php");
    exit();
}

/**
 * ✅ Always fetch questions and user answers (prevents undefined variable warning)
 */
$questions = [];
$user_answers = [];

if ($current_stage !== "Stage 5") {
    $mapped_stage = $stage_mapping[$current_stage];
    $stmt = $conn->prepare("SELECT id, question_text, answer_type, custom_options FROM questions WHERE stage = ?");
    $stmt->bind_param("s", $mapped_stage);
    $stmt->execute();
    $res = $stmt->get_result();
    $questions = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $a_stmt = $conn->prepare("SELECT question_id, answer FROM user_answers WHERE user_email = ?");
    $a_stmt->bind_param("s", $email);
    $a_stmt->execute();
    $a_res = $a_stmt->get_result();
    while ($row = $a_res->fetch_assoc()) {
        $user_answers[(int)$row['question_id']] = $row['answer'];
    }
    $a_stmt->close();
}

/**
 * ✅ Prepare Final Summary for Stage 5
 */
$stage_db_mapping = [
  "Stage 1" => "Quantitative Aptitude & Numerical Ability (6 Questions)",
  "Stage 2" => "Logical Ability & Reasoning (6 Questions)",
  "Stage 3" => "General Knowledge (7 Questions)",
  "Stage 4" => "English Language (6 Questions)"
];

$stages_for_summary = [
  "All" => array_keys($stage_db_mapping),
  "Quantitative Aptitude" => ["Stage 1"],
  "Logical Ability" => ["Stage 2"],
  "General Knowledge" => ["Stage 3"],
  "English Language" => ["Stage 4"]
];

$stage_summary = [];

foreach ($stages_for_summary as $label => $stage_list) {
    $answered = 0;
    $not_answered = 0;
    $total = 0;

    foreach ($stage_list as $stage_key) {
        $db_stage_name = $stage_db_mapping[$stage_key];

        // Total
        $q_total_query = $conn->prepare("SELECT COUNT(*) AS total FROM questions WHERE stage = ?");
        $q_total_query->bind_param("s", $db_stage_name);
        $q_total_query->execute();
        $q_total_result = $q_total_query->get_result()->fetch_assoc();
        $q_total = $q_total_result['total'] ?? 0;
        $q_total_query->close();

        // Answered
        $q_answered_query = $conn->prepare("
            SELECT COUNT(*) AS answered 
            FROM user_answers 
            WHERE user_email = ? 
            AND answer <> '' 
            AND question_id IN (SELECT id FROM questions WHERE stage = ?)
        ");
        $q_answered_query->bind_param("ss", $user_email, $db_stage_name);
        $q_answered_query->execute();
        $q_answered_result = $q_answered_query->get_result()->fetch_assoc();
        $q_answered = $q_answered_result['answered'] ?? 0;
        $q_answered_query->close();

        $answered += $q_answered;
        $total += $q_total;
    }

    $not_answered = $total - $answered;

    $stage_summary[$label] = [
        "All Questions" => $total,
        "Answered" => $answered,
        "Not Answered" => $not_answered
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--    Document Title-->
    <title>Exam <?php echo $current_stage; ?></title>

    <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/logo-enviro.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/logo-enviro.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo-enviro.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/logo-enviro.png">
    <meta name="theme-color" content="#ffffff">
    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .tab-item {
      padding: 10px 20px;
      border-radius: 25px;
      background: #f8f9fa;
      margin: 5px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: 500;
    }
    .tab-item.active {
      background: #007bff;
      color: #fff;
    }
    /* Sticky timer card */
  .timer-card {
    position: sticky;
    top: 0;
    z-index: 1050; /* above content */
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    background: #ffffff;
  }
  .progress {
    height: 10px;
  }
    </style>

  </head>


  <body>
    <!--    Main Content-->
    <main class="main" id="top">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container">
          <a class="navbar-brand" href="#">
            <img src="assets/img/logo.png" height="90" alt="logo" />
          </a>
          <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"> </span>
          </button> -->
          <a class="navbar-brand" href="#">
          <img src="assets/img/call.png" height="40" alt="logo" /> +91-0123456789
          </a>
          </div>
        </div>
      </nav>

      <!-- <section> begin -->
      <section class="pt-5 mb-6" id="feature">
  <div class="container mt-5">
  <div class="container mt-3">
  <div class="timer-card p-3 border">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="m-0">Time Remaining</h5>
      <h4 class="text-danger m-0" id="timerText">--:--</h4>
    </div>
    <div class="progress mt-3">
      <div id="timerBar" class="progress-bar" role="progressbar" style="width: 100%"></div>
    </div>
    <small class="text-muted d-block mt-2">The test will auto-submit when time is over.</small>
  </div>
</div>

<script>
  // Total test duration in seconds (30 * 60)
  const TOTAL_SECONDS = 1800;

  // Use server-provided remaining seconds (authoritative).
  let remaining = <?php echo (int)$remaining_seconds; ?>;

  // One-time warning flags
  let warned5 = false;
  let warned1 = false;

  // Update UI immediately
  updateTimerUI();

  const tick = setInterval(() => {
    remaining--;
    if (remaining <= 0) {
      remaining = 0;
      updateTimerUI();
      clearInterval(tick);
      autoSubmit(); // client-side auto submit
      return;
    }
    updateTimerUI();
    handleWarnings();
  }, 1000);

  function updateTimerUI() {
    const m = Math.floor(remaining / 60);
    const s = remaining % 60;
    document.getElementById('timerText').textContent =
      (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;

    // Progress bar (remaining / total)
    const pct = Math.max(0, Math.min(100, (remaining / TOTAL_SECONDS) * 100));
    document.getElementById('timerBar').style.width = pct + '%';
    document.getElementById('timerBar').setAttribute('aria-valuenow', pct.toFixed(0));
  }

  function handleWarnings() {
    if (!warned5 && remaining === 5 * 60) {
      warned5 = true;
      showWarning('Only 5 minutes left. Please review and submit your answers.');
    }
    if (!warned1 && remaining === 60) {
      warned1 = true;
      showWarning('Final 1 minute remaining. Your test will auto-submit.');
    }
  }

  function showWarning(msg) {
    // Simple, dependency-free alert. Replace with Bootstrap toast if you prefer.
    alert(msg);
  }

  function autoSubmit() {
    // Create a form and POST confirm_final to trigger your server-side finalization path
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = ''; // current page

    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'confirm_final';
    hidden.value = '1';
    form.appendChild(hidden);

    document.body.appendChild(form);
    form.submit();
  }
</script>

    <?php if ($current_stage !== "Stage 5"): ?>
      <h2 class="mb-4 text-center"><?php echo htmlspecialchars($stage_mapping[$current_stage]); ?></h2>

      <form method="post">
        <?php if (empty($questions)): ?>
          <div class="alert alert-warning text-center">No questions found for <?php echo htmlspecialchars($stage_mapping[$current_stage]); ?>.</div>
        <?php endif; ?>

        <?php foreach ($questions as $index => $question): 
          $qid = (int)$question['id'];
          $saved = $user_answers[$qid] ?? '';
        ?>
          <div class="mb-5 p-3 border rounded shadow-sm bg-light">
            <p class="fw-bold fs-4 mb-3"><?php echo ($index + 1) . '. ' . nl2br(htmlspecialchars($question['question_text'])); ?></p>
            <div class="ms-3">
              <?php if ($question['answer_type'] === 'yes_no'): ?>
                <div class="form-check mb-2">
                  <input type="radio" class="form-check-input toggleable-radio" name="answers[<?php echo $qid; ?>]" value="Yes" id="q<?php echo $qid; ?>_yes" <?php echo ($saved === 'Yes') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="q<?php echo $qid; ?>_yes">Yes</label>
                </div>
                <div class="form-check mb-2">
                  <input type="radio" class="form-check-input toggleable-radio" name="answers[<?php echo $qid; ?>]" value="No" id="q<?php echo $qid; ?>_no" <?php echo ($saved === 'No') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="q<?php echo $qid; ?>_no">No</label>
                </div>
              <?php elseif ($question['answer_type'] === 'custom'): 
                $opts = array_map('trim', explode(',', $question['custom_options']));
                foreach ($opts as $oi => $opt):
              ?>
                <div class="form-check mb-2">
                  <input type="radio" class="form-check-input toggleable-radio" name="answers[<?php echo $qid; ?>]" value="<?php echo htmlspecialchars($opt); ?>" id="q<?php echo $qid; ?>_<?php echo $oi; ?>" <?php echo ($saved === $opt) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="q<?php echo $qid; ?>_<?php echo $oi; ?>"><?php echo htmlspecialchars($opt); ?></label>
                </div>
              <?php endforeach; endif; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-between mt-4">
          <button type="submit" name="previous" class="btn btn-secondary px-4" <?php echo ($current_stage === "Stage 1") ? 'disabled' : ''; ?>>Save-Previous</button>
          <button type="submit" class="btn btn-success px-4">Save-Next</button>
        </div>

        <!-- <div class="mt-4 text-center">
          <a href="stages.php" class="btn btn-danger">Back to Stages</a>
        </div> -->
      </form>

    <?php else: ?>
      <!-- ✅ Final Submission Review Page -->
      <div class="final-review container py-5">
        <h2 class="text-center fw-bold mb-5 text-primary">Exam Summary & Final Submission</h2>

        <div class="stage-tabs d-flex justify-content-around mb-4 flex-wrap">
          <div class="tab-item active" data-stage="All">All</div>
          <div class="tab-item" data-stage="Quantitative Aptitude">Quantitative Aptitude</div>
          <div class="tab-item" data-stage="Logical Ability">Logical Ability</div>
          <div class="tab-item" data-stage="General Knowledge">General Knowledge</div>
          <div class="tab-item" data-stage="English Language">English Language</div>
        </div>

        <div class="summary-table mx-auto mb-5">
          <table class="table table-borderless text-center align-middle" id="summaryTable">
            <tbody></tbody>
          </table>
        </div>

        <div class="text-center mt-5">
          <h4 class="text-muted mb-3">Are you sure you want to submit?</h4>
          <form method="post">
            <button type="submit" name="previous" class="btn btn-secondary px-4">Go Back</button>
            <button type="submit" name="confirm_final" class="btn btn-success px-4">Submit Final</button>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
  const stageData = <?php echo json_encode($stage_summary); ?>;
  function renderTable(stageName) {
    const tbody = document.querySelector("#summaryTable tbody");
    tbody.innerHTML = "";
    const data = stageData[stageName];
    for (const [label, value] of Object.entries(data)) {
      const tr = document.createElement("tr");
      tr.innerHTML = `<td><strong>${label}</strong></td><td class="fw-bold">${value}</td>`;
      tbody.appendChild(tr);
    }
  }
  renderTable("All");
  document.querySelectorAll(".tab-item").forEach(tab => {
    tab.addEventListener("click", function() {
      document.querySelectorAll(".tab-item").forEach(t => t.classList.remove("active"));
      this.classList.add("active");
      renderTable(this.dataset.stage);
    });
  });
  </script>

  <script>
  document.querySelectorAll('.toggleable-radio').forEach(radio => {
    radio.addEventListener('click', function () {
      if (this.previousChecked) this.checked = false;
      this.previousChecked = this.checked;
      document.querySelectorAll(`input[name='${this.name}']`).forEach(r => {
        if (r !== this) r.previousChecked = false;
      });
    });
  });
  </script>
</section>

      <!-- <section> begin -->
      <section class="text-center py-0" style="background-color:rgb(246, 238, 226);">

        <div class="container">
          <div class="container border-top py-3">
            <div class="row justify-content-between">
              <div class="col-12 col-md-auto mb-1 mb-md-0">
                <p class="mb-0">&copy; 2025 Online Exam Application</p>
              </div>
              <div class="col-12 col-md-auto">
                <p class="mb-0">
                  Made with<span class="fas fa-heart mx-1 text-danger"> </span>by<a class="text-decoration-none ms-1" href="#" target="_blank">In House IT</a></p>
              </div>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close -->


    </main>
    <!--    End of Main Content-->
    <!--    JavaScripts-->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/fontawesome/all.min.js"></script>
    <script src="assets/js/theme.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">

    <script src="script.js"></script>
  </body>

</html>