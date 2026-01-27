<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/** 🔹 Fetch all user answers */
$user_answers = [];
$userQuery = $conn->prepare("SELECT question_id, answer FROM user_answers WHERE user_email = ?");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$result = $userQuery->get_result();
while ($row = $result->fetch_assoc()) {
    $user_answers[$row['question_id']] = trim($row['answer']);
}
$userQuery->close();

/** 🔹 Fetch all correct answers from new table */
$correct_answers = [];
$correctQuery = $conn->query("SELECT question_id, correct_answer FROM correct_answers");
while ($row = $correctQuery->fetch_assoc()) {
    $correct_answers[$row['question_id']] = trim($row['correct_answer']);
}

/** 🔹 Calculate score */
$total_questions = count($correct_answers);
$correct_count = 0;

foreach ($user_answers as $qid => $ans) {
    if (isset($correct_answers[$qid]) && strcasecmp($ans, $correct_answers[$qid]) === 0) {
        $correct_count++;
    }
}

$score_percent = ($total_questions > 0) ? round(($correct_count / $total_questions) * 100) : 0;
$conn->close();
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--    Document Title-->
    <title>Final Review</title>

    <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .result-container {
      text-align: center;
      padding: 100px 20px;
    }
    .medal-icon {
      width: 100px;
      height: 100px;
      margin-bottom: 20px;
    }
    .result-text {
      font-size: 1.2rem;
      color: #333;
      margin-bottom: 30px;
    }
    .score {
      font-weight: bold;
      font-size: 1.3rem;
    }
    .btn-custom {
      background-color: #3a2277;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.2s ease;
    }
    .btn-custom:hover {
      background-color: #29165a;
    }
    .btn-container {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }
  </style>

  </head>


  <body>
    <!--    Main Content-->
    <main class="main" id="top">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" data-navbar-on-scroll="data-navbar-on-scroll">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="assets/img/logo.png" height="90" alt="logo" />
        </a>

        <!-- 🔹 Logout Button -->
        <div class="d-flex ms-auto">
          <a href="logout.php" class="btn btn-danger px-4 py-2 fw-semibold">
            Logout
          </a>
        </div>
      </div>
    </nav>

    <!-- <section> begin -->
    <section class="pt-5 mb-6" id="feature">
      <div class="container mt-5 result-container">
        <img src="assets/img/result-medal.png" alt="Medal" class="medal-icon">
        <p class="result-text">
          Thanks for evaluating yourself in the Online Test<br>
          You have scored <span class="score"><?php echo $score_percent; ?>%</span> in this online test.
        </p>

        <div class="btn-container mt-4">
          <a href="download_score.php" class="btn btn-custom">Download Score Card</a>
          <a href="stages.php" class="btn btn-custom">Go to Dashboard</a>
        </div>
      </div>
    </section>
      <!-- <section> close -->

      <!-- <section> begin -->
      <section class="pb-2 pb-lg-5">

        <div class="container">
          <div class="row border-top border-top-secondary pt-7">
            <div class="col-lg-3 col-md-6 mb-4 mb-md-6 mb-lg-0 mb-sm-2 order-1 order-md-1 order-lg-1"><img class="mb-4" src="assets/img/logo-enviro.png" width="184" alt="" /></div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 order-3 order-md-3 order-lg-2">
              <p class="fs-2 mb-lg-4">Quick Links</p>
              <ul class="list-unstyled mb-0">
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">About us</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Blog</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Contact</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">FAQ</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 order-4 order-md-4 order-lg-3">
              <p class="fs-2 mb-lg-4">Legal stuff</p>
              <ul class="list-unstyled mb-0">
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Disclaimer</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Financing</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Privacy Policy</a></li>
                <li class="mb-1"><a class="link-900 text-secondary text-decoration-none" href="#!">Terms of Service</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-4 mb-lg-0 order-2 order-md-2 order-lg-4">
              <p class="fs-2 mb-lg-4">
                knowing you're always on the best energy deal.</p>
              <form class="mb-3">
                <input class="form-control" type="email" placeholder="Enter your phone Number" aria-label="phone" />
              </form>
              <button class="btn btn-warning fw-medium py-1">Sign up Now</button>
            </div>
          </div>
        </div><!-- end of .container-->

      </section>
      <!-- <section> close -->

      <!-- <section> begin -->
      <section class="text-center py-0">

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