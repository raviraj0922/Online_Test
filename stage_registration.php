<?php
session_start();
if (!isset($_SESSION['email']) && !isset($_GET['user_email'])) {
    header("Location: index.php"); // Redirect if email is missing
    exit();
}

$email = isset($_GET['email']) ? $_GET['email'] : $_SESSION['email'];

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "online_exam";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Registration Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error_message = "Email already exists. Try logging in.";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $password);
        
        if ($stmt->execute()) {
            $_SESSION['email'] = $email; // Store session
            header("Location: stages.php"); // Redirect to stage.php
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <!--    Favicons-->
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">

    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <style>
      .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .feature-card {
            background-color: #0056b3;
            color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .get-started {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            background-color: #0056b3;
            color: white;
            border-radius: 8px;
            padding: 40px;
            gap: 20px;
        }

        .get-started .content {
            flex: 1;
            min-width: 300px;
        }

        .get-started .form-container {
            flex: 1;
            min-width: 300px;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: black;
        }

        .form {
            display: none;
        }

        .form.active {
            display: block;
        }

        .form input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form button:hover {
            background: #0056b3;
        }

        .toggle-link {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .get-started {
                flex-direction: column;
                text-align: center;
            }
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
      <section class="pt-5 mb-6 d-flex align-items-center justify-content-center" id="feature" style="background-color:rgb(246, 238, 226);">
    <div class="container mt-5">
        <h2 id="form-title" class="text-center">Registration</h2>
        <p class="text-center">Welcome! Please register to submit your application.</p>

        <?php if (isset($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" id="register-form">
                    <div class="mb-3">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="phone" name="phone" class="form-control" placeholder="Mobile Number" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Continue</button>
                </form>
                <p class="text-center mt-4 mb-0">Already completed the journey? <a href="login-registration.php" class="text-decoration-none fw-bold">LOGIN</a></p>
            </div>
        </div>
    </div>
</section>

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
    <script>
          // Get all navbar links
      const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

      // Highlight the active navbar link based on the current page URL
      function setActiveNavLink() {
        const currentPage = window.location.pathname.split('/').pop(); // Get the current file name
        navLinks.forEach(link => {
          if (link.getAttribute('href') === currentPage) {
            link.classList.add('active'); // Add the 'active' class to the matching link
          } else {
            link.classList.remove('active'); // Remove the 'active' class from non-matching links
          }
        });
      }

      // Run the function on page load
      document.addEventListener('DOMContentLoaded', setActiveNavLink);

    </script>
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/fontawesome/all.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">
  </body>

</html>
