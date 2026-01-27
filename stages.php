<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['email']; 

// ✅ ADD THIS — Database connection
$conn = new mysqli("localhost", "root", "", "online_exam");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * ✅ Fetch exam status
 */
$stmt = $conn->prepare("SELECT exam_end_time, exam_completed FROM users WHERE email=?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$exam_end_time   = $result['exam_end_time'] ?? null;
$exam_completed  = $result['exam_completed'] ?? 0;

// Check if timer exists
$remaining_seconds = 0;
if (!empty($exam_end_time)) {
    $remaining_seconds = strtotime($exam_end_time) - time();
}

$exam_in_progress = ($remaining_seconds > 0 && $exam_completed == 0);
$exam_expired_not_completed = ($remaining_seconds <= 0 && $exam_completed == 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Online Exam</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/logo-enviro.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/logo-enviro.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo-enviro.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/logo-enviro.png">
    <meta name="theme-color" content="#ffffff">

    <link href="assets/css/theme.css" rel="stylesheet" />
    <style>
  .exam-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    max-width: 1000px;
    margin: 30px auto;
  }
  .exam-header {
    background-color: #2196f3;
    color: #fff;
    font-weight: 600;
    padding: 8px 20px;
    font-size: 0.95rem;
  }
  .exam-body {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
  }
  .exam-body img {
    max-width: 120px;
    height: auto;
  }
  .exam-info {
    flex: 1;
    margin-left: 20px;
    min-width: 260px;
  }
  .exam-info h4 {
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
  }
  .exam-details {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    margin-top: 10px;
  }
  .exam-details div {
    font-size: 0.95rem;
    color: #555;
  }
  .exam-details strong {
    display: block;
    color: #111;
  }
  .exam-actions {
    text-align: right;
    min-width: 200px;
  }
  .exam-actions .btn {
    background-color: #2196f3;
    color: #fff;
    font-weight: 600;
    border-radius: 5px;
    padding: 8px 25px;
    transition: 0.3s;
  }
  .exam-actions .btn:hover {
    background-color: #1976d2;
  }
  .exam-footer {
    font-size: 0.85rem;
    color: #777;
    padding: 10px 25px;
    border-top: 1px solid #eee;
  }
  @media (max-width: 768px) {
    .exam-body {
      flex-direction: column;
      align-items: flex-start;
      text-align: left;
    }
    .exam-info {
      margin-left: 0;
      margin-top: 15px;
    }
    .exam-actions {
      width: 100%;
      text-align: left;
      margin-top: 15px;
    }
  }
  .center-card {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    transition: all 0.3s ease-in-out;
    height: 100%;
  }

  .center-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .center-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #222;
  }

  .center-address {
    color: #555;
    font-size: 0.95rem;
    margin-bottom: 10px;
  }

  .center-time {
    color: #c58a00;
  }

  .map-thumb {
    width: 60px;
    height: 60px;
    border-radius: 5px;
    object-fit: cover;
  }

  @media (max-width: 767px) {
    .center-card {
      text-align: left;
    }
    .map-thumb {
      width: 50px;
      height: 50px;
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

        <!--  Logout Button -->
        <div class="d-flex ms-auto">
          <a href="logout.php" class="btn btn-danger px-4 py-2 fw-semibold">
            Logout
          </a>
        </div>
      </div>
    </nav>

    <!-- section start -->
    <section class="vh-100 d-flex align-items-center justify-content-center" style="background-color:rgb(246, 238, 226);">
    <div class="container">
        <h2 class="text-center">Welcome to the Scholarship Exam Test</h2>
        <p class="text-center">Logged in as: <strong><?php echo htmlspecialchars($user_email); ?></strong></p>
        
        <!-- ✅ Exam Start Layout -->
          <div class="exam-card">
            <div class="exam-header">
              Online Exam - Get upto 50% scholarship*
            </div>
            <div class="exam-body">
              <div class="d-flex align-items-center">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Exam Icon" class="me-3">
                <div class="exam-info">
                <h4 id="greeting-text"> <span id="greet"></span> <?php echo htmlspecialchars($user_name ?? 'Student'); ?>,</h4>

                  <script>
                  // Function to set dynamic greeting based on time
                  function setGreeting() {
                    const hour = new Date().getHours();
                    let greeting = "";

                    if (hour < 12) {
                      greeting = "Good Morning";
                    } else if (hour < 17) {
                      greeting = "Good Afternoon";
                    } else if (hour < 20) {
                      greeting = "Good Evening";
                    } else {
                      greeting = "Good Night";
                    }

                    // Insert greeting text before user name
                    document.getElementById("greet").textContent = greeting;
                  }

                  // Call the function when page loads
                  setGreeting();
                  </script>

                  <div class="exam-details">
                    <div>
                      <strong>Date</strong>
                      <span id="current-date"></span>
                    </div>
                    <!-- <div>
                      <strong>Timings</strong>
                      09:00 AM - 10:00 PM
                    </div> -->
                    <div>
                    <script>
                    // Get the current date
                    const today = new Date();

                    // Format options (e.g., Mon, 03 November, 2025)
                    const options = { weekday: 'short', day: '2-digit', month: 'long', year: 'numeric' };

                    // Format date using locale (en-GB gives DD Month YYYY)
                    const formattedDate = today.toLocaleDateString('en-GB', options);

                    // Display in the span
                    document.getElementById('current-date').textContent = formattedDate;
                  </script>
                      <strong>Duration</strong>
                      30 Mins
                    </div>
                  </div>
                </div>
              </div>

              <div class="exam-actions text-end">
                <p class="mb-2 small text-muted">Need help? Call us at <strong>+91-0123456789</strong></p>

                <?php
                // CONDITIONS:
                // exam_completed = 1 → show only scorecard
                // exam not completed + timer exists & > 0 → resume
                // exam not completed + no timer → begin test

                $has_timer = !empty($exam_end_time);
                $time_remaining = $remaining_seconds > 0;
                ?>

                <?php if ($exam_completed == 1): ?>

                    <!-- ✅ USER COMPLETED EXAM -->
                    <a href="result.php" class="btn btn-success">View Score Card</a>

                <?php elseif ($has_timer && $time_remaining): ?>

                    <!-- ✅ EXAM STARTED BUT NOT FINISHED -->
                    <a href="form.php" class="btn btn-warning me-2">Resume Exam</a>

                <?php else: ?>

                    <!-- ✅ FIRST TIME USER (NEVER STARTED)  -->
                    <a href="payment_gateway.php" class="btn btn-primary">Begin Test</a>

                <?php endif; ?>
            </div>
            </div>
            <div class="exam-footer">
              *Terms and conditions applied
            </div>
          </div>
    </div>
</section>
<!-- section end -->
<section class="center-locations-section py-5">
  <div class="container">
    <h2 class="text-center mb-4 fw-bold">Branches Near you</h2>
    <div class="row justify-content-center">

      <!-- Card 1 -->
      <div class="col-md-6 col-sm-6 mb-4">
        <div class="center-card">
          <h5 class="center-title">Institute1</h5>
          <p class="center-address">Address</p>
          <p class="center-time text-warning fw-semibold mb-2">Open Hours 9:30AM to 5:30PM <br> Monday to Saturday</p>
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <i class="bi bi-telephone text-info fs-5 me-2"></i>
              <a href="tel:0123456789" class="text-decoration-none fw-semibold text-primary">+91-0123456789</a>
            </div>
            <img src="assets/img/goa_map.png" class="map-thumb" alt="Map">
          </div>
        </div>
      </div>

     <!-- Card 2 -->
      <div class="col-md-6 col-sm-6 mb-4">
        <div class="center-card">
          <h5 class="center-title">Institute2</h5>
          <p class="center-address">Address</p>
          <p class="center-time text-warning fw-semibold mb-2">Open Hours 9:30AM to 5:30PM <br>Monday to Saturday</p>
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <i class="bi bi-telephone text-info fs-5 me-2"></i>
              <a href="tel:0123456789" class="text-decoration-none fw-semibold text-primary">+91-0123456789</a>
            </div>
            <img src="assets/img/lucknow.png" class="map-thumb" alt="Map">
          </div>
        </div>
      </div>
      
      <!-- Card 3 -->
      <div class="col-md-6 col-sm-6 mb-4">
        <div class="center-card">
          <h5 class="center-title">Institute3</h5>
          <p class="center-address">Address</p>
          <p class="center-time text-warning fw-semibold mb-2">Open Hours 9:30AM to 5:30PM <br>Monday to Saturday</p>
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <i class="bi bi-telephone text-info fs-5 me-2"></i>
              <a href="tel:0123456789" class="text-decoration-none fw-semibold text-primary">+91-0123456789</a>
            </div>
            <img src="assets/img/bangalore.png" class="map-thumb" alt="Map">
          </div>
        </div>
      </div>      

      <!-- Card 4 -->
      <div class="col-md-6 col-sm-6 mb-4">
        <div class="center-card">
          <h5 class="center-title">Institute4</h5>
          <p class="center-address">Address</p>
          <p class="center-time text-warning fw-semibold mb-2">Open Hours 9:30AM to 5:30PM <br>Monday to Saturday</p>
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <i class="bi bi-telephone text-info fs-5 me-2"></i>
              <a href="tel:0123456789" class="text-decoration-none fw-semibold text-primary">+91-0123456789</a>
            </div>
            <img src="assets/img/guwahati.png" class="map-thumb" alt="Map">
          </div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col-md-6 col-sm-6 mb-4">
        <div class="center-card">
          <h5 class="center-title">Institute5</h5>
          <p class="center-address">Address</p>
          <p class="center-time text-warning fw-semibold mb-2">Open Hours 9:30AM to 5:30PM <br>Monday to Saturday</p>
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <i class="bi bi-telephone text-info fs-5 me-2"></i>
              <a href="tel:0123456789" class="text-decoration-none fw-semibold text-primary">+91-0123456789</a>
            </div>
            <img src="assets/img/shillong.png" class="map-thumb" alt="Map">
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

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
