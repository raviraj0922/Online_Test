<?php
session_start();

// Check if the user is authenticated (OTP verified)
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login page if not authenticated
    exit();
}

$user_email = $_SESSION['email']; // Get logged-in user email

// Verify payment before allowing form access
$conn = new mysqli("localhost", "root", "", "online_exam");
$check = $conn->prepare("SELECT payment_status FROM users WHERE email=?");
$check->bind_param("s", $_SESSION['email']);
$check->execute();
$check->bind_result($payment_status);
$check->fetch();
$check->close();

if ($payment_status !== 'Paid') {
    header("Location: payment_gateway.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!--    Document Title-->
    <title>Online Exam</title>

    <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/logo-enviro.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/logo-enviro.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo-enviro.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/logo-enviro.png">
    <meta name="theme-color" content="#ffffff">
    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    .instructions-wrapper {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .instructions-wrapper h3 {
    font-weight: 700;
    }
    .top-info {
    display: flex;
    justify-content: flex-end;
    gap: 40px;
    margin-bottom: 20px;
    }
    .top-info div {
    text-align: center;
    font-size: 14px;
    }
    .top-info div span {
    display: block;
    font-size: 20px;
    font-weight: bold;
    }
    #startBtn {
    width: 250px;
    display: block;
    margin: 40px auto 0;
    padding: 15px;
    font-size: 18px;
    border-radius: 6px;
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
    <section class="pt-5" id="feature">
<div class="container mt-5">
<div class="top-info">
<div>
Duration <span>30 mins</span>
</div>
<div>
Questions <span>25</span>
</div>
<div>
Marks <span>50</span>
</div>
</div>


<div class="instructions-wrapper">
<h3>General Instructions:</h3>
<p class="text-danger">Please read the instructions carefully</p>


<ol>
<li>Total duration of ESAITE exam is 30 min.</li>
<li>The Test consists of 25 questions. The maximum marks are 50.</li>
<li>The question paper consists of 3 parts (Quantitative Aptitude, Logical Ability, General Knowledge, English Language).</li>
<li>Each correct answer carries 1 marks. There is no negative marking.</li>
<li>The clock will be set at the server. The countdown timer will appear on the screen.</li>
<li>The Questions Palette on the right will show the status of each question.</li>
</ol>


<ul>
<li>Answered</li>
<li>Not Answered</li>
<li>Marked for Review</li>
<li>Not Visited</li>
<li>Answered and Marked for Review</li>
</ul>


<ol start="7">
<li>Ensure your device battery is charged for at least 1 hour.</li>
<li>Begin the test with a plan. Attempt your strongest section first.</li>
<li>Go through the entire paper and attempt known questions first.</li>
<li>Save 5–10 minutes at the end to revisit answers.</li>
<li>Don't change device date/time during the test.</li>
<li>Don't submit the test early; use the full duration.</li>
</ol>
</div>


<button id="startBtn" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#startModal">Start Test</button>
</div>
<!-- Modal -->
<div class="modal fade" id="startModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Start Test</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
Are you sure you want to begin the test? Once started, the timer will begin.
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<a href="form.php" class="btn btn-primary">Yes, Start Now</a>
</div>
</div>
</div>
</div>
</section>
      <!-- <section> close -->

      <!-- <section> begin -->
      <section class="text-center py-0">

        <div class="container">
          <div class="container border-top py-3">
            <div class="row justify-content-between">
              <div class="col-12 col-md-auto mb-1 mb-md-0">
                <p class="mb-0"><span>Copyright</span>&copy; 2025 Online Exam Application <span>All Rights Reserved</span></p>
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

  </body>

</html>