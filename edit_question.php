<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login-registration.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $question_id = intval($_POST['question_id']);
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $answer_type = $conn->real_escape_string($_POST['answer_type']);
    $custom_options = $answer_type == "custom" ? $conn->real_escape_string($_POST['custom_options']) : NULL;
    
    $stmt = $conn->prepare("UPDATE questions SET question_text=?, answer_type=?, custom_options=? WHERE id=?");
    $stmt->bind_param("sssi", $question_text, $answer_type, $custom_options, $question_id);
    $stmt->execute();
    $stmt->close();
    
    $success_message = "Question updated successfully!";
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $question_id = intval($_POST['question_id']);
    
    $stmt = $conn->prepare("DELETE FROM questions WHERE id=?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $stmt->close();
    
    $success_message = "Question deleted successfully!";
}

// Fetch all questions
$result = $conn->query("SELECT * FROM questions");
$questions = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--    Document Title-->
    <title>Edit Question</title>

    <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />

  </head>


  <body>
    <!--    Main Content-->
    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light sticky-top" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container">
          <a class="navbar-brand" href="index.php">
            <img src="assets/img/logo.png" height="90" alt="logo" />
          </a>
          <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"> </span>
          </button> -->
          <a class="navbar-brand" href="index.php">
          <img src="assets/img/call.png" height="40" alt="logo" /> +91-0123456789
          </a>
          </div>
        </div>
      </nav>

      <!-- <section> begin -->
      <section class="pt-5 mb-6" id="feature">
      <div class="container mt-5">
        <h2>Edit Questions</h2>
        <?php if (isset($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Stage</th>
                    <th>Question</th>
                    <th>Answer Type</th>
                    <th>Custom Options</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question): ?>
                <tr>
                    <form method="POST">
                        <td><?php echo htmlspecialchars($question['stage']); ?></td>
                        <td><input type="text" name="question_text" class="form-control" value="<?php echo htmlspecialchars($question['question_text']); ?>" required></td>
                        <td>
                            <select name="answer_type" class="form-control" required>
                                <option value="yes_no" <?php if ($question['answer_type'] == 'yes_no') echo 'selected'; ?>>Yes/No</option>
                                <option value="custom" <?php if ($question['answer_type'] == 'custom') echo 'selected'; ?>>Custom Options</option>
                            </select>
                        </td>
                        <td><input type="text" name="custom_options" class="form-control" value="<?php echo htmlspecialchars($question['custom_options']); ?>"></td>
                        <td>
                            <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                            <button type="submit" name="update" class="btn btn-primary btn-sm">Update</button>
                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    </section>
      <!-- <section> close -->

      <!-- <section> begin -->
      <section class="pb-2 pb-lg-5">

        <div class="container">
          <div class="row border-top border-top-secondary pt-7">
            <div class="col-lg-3 col-md-6 mb-4 mb-md-6 mb-lg-0 mb-sm-2 order-1 order-md-1 order-lg-1"><img class="mb-4" src="assets/img/logo-enviro.PNG" width="184" alt="" /></div>
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
<?php $conn->close(); ?>