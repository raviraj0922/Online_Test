<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['email'];

// Check if user already paid
$check = $conn->prepare("SELECT payment_status FROM users WHERE email=?");
$check->bind_param("s", $user_email);
$check->execute();
$check->store_result();
$check->bind_result($payment_status);
$check->fetch();
$check->close();

// If already paid → redirect to exam instructions
if ($payment_status === "Paid") {
    header("Location: test-instructions.php");
    exit();
}

$conn->close();

// Razorpay amount (1.00 INR)
$amount = 100; // paise
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Online Exam</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
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

            <!-- 🔹 Logout Button -->
            <div class="d-flex ms-auto">
            <a href="logout.php" class="btn btn-danger px-4 py-2 fw-semibold">
                Logout
            </a>
            </div>
        </div>
        </nav>
    <section class="vh-100 d-flex align-items-center justify-content-center" style="background-color:rgb(246, 238, 226);">
    <div class="container py-5">
    <div class="text-center mb-4">
      <h2>Exam Fee Payment</h2>
      <p class="lead">Pay ₹499 to start your examination.</p>
    </div>

    <div class="card mx-auto shadow-lg" style="max-width: 500px;">
      <div class="card-body text-center">
        <h5 class="card-title">Exam Fee: ₹499</h5>
        <p class="card-text">Your email: <strong><?php echo htmlspecialchars($user_email); ?></strong></p>
        <button id="payButton" class="btn btn-primary px-5 py-2 mt-3">Pay Now</button>
      </div>
    </div>
  </div>

  <script>
  document.getElementById('payButton').onclick = function(e) {
    var options = {
      "key": "rzp_live_RpRaD7HO0Hjwk2", // ⚠️ Replace with your Razorpay key ID
      "amount": "<?php echo $amount; ?>",
      "currency": "INR",
      "name": "Online Examination",
      "description": "Exam Fee Payment",
      "image": "assets/img/logo-enviro.png", // optional
      "handler": function (response){
          // After successful payment, redirect
          window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id;
      },
      "prefill": {
          "email": "<?php echo $user_email; ?>",
      },
      "theme": {
          "color": "#0d6efd"
      }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
  }
  </script>
</section>

      <!-- <section> begin ============================-->
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

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap" rel="stylesheet">
  </body>
</html>
