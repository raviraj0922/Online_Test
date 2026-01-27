<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Exam</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/logo-enviro.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/logo-enviro.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo-enviro.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/logo-enviro.png">
    <meta name="theme-color" content="#ffffff">

    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <style>
  .section-title {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 1.5rem;
      color: #2b1b0e;
    }

    .info-box {
      background-color: #f8fbff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .info-box:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .info-item img {
      width: 45px;
      height: 45px;
    }

    .info-item h5 {
      font-weight: 600;
      font-size: 1.1rem;
      margin-bottom: 0.25rem;
    }

    .info-item p {
      font-size: 0.95rem;
      color: #6c757d;
      margin: 0;
    }

    hr {
      margin: 1.2rem 0;
      border-top: 1px solid #e0e0e0;
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
      .section-title {
        text-align: center;
        font-size: 1.8rem;
      }

      .info-box {
        padding: 1.5rem;
      }

      .info-item {
        flex-direction: row;
      }
    }

    @media (max-width: 767px) {
      .section-title {
        font-size: 1.6rem;
      }

      .info-item img {
        width: 38px;
        height: 38px;
      }

      .info-item h5 {
        font-size: 1rem;
      }

      .info-item p {
        font-size: 0.9rem;
      }
    }
    /* Green Banner */
    .scholarship-banner {
      background-color: #372770;
      color: #fff;
      font-weight: 600;
      font-size: 1.1rem;
      padding: 18px 25px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 40px;
    }

    .scholarship-banner img {
      width: 70px;
      height: auto;
    }

    /* Registration Section */
    .register-section {
      border-radius: 18px;
      padding: 40px;
    }

    .register-content {
      background-color: #ffffff;
      border-radius: 12px;
      padding: 30px 35px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .register-title {
      font-weight: 700;
      font-size: 2rem;
      color: #2b1b0e;
    }

    .register-sub {
      font-size: 1rem;
      color: #555;
      margin-bottom: 1.5rem;
    }

    .step {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.2rem;
    }

    .step-number {
      background-color: #0d2a45;
      color: #fff;
      font-weight: 600;
      border-radius: 50%;
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 0.9rem;
      flex-shrink: 0;
    }

    .step p {
      margin: 0;
      font-weight: 600;
      color: #222;
    }

    .illustration {
      text-align: center;
    }

    .illustration img {
      max-width: 100%;
      height: auto;
    }

    /* Responsive */
    @media (max-width: 991px) {
      .register-section {
        text-align: center;
        padding: 30px 20px;
      }

      .register-content {
        margin-top: 25px;
      }

      .scholarship-banner {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
    }

    @media (max-width: 576px) {
      .register-title {
        font-size: 1.6rem;
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
        <section class="vh-70 d-flex align-items-center justify-content-center" style="background-color:rgb(246, 238, 226);">
          <div class="container">
              <div class="row align-items-center g-5">
                  <!-- Left Column -->
                  <div class="col-lg-6 text-white">
                    <img src="assets/img/hero_image.png" alt="Get Started" class="img-fluid">
                      
                  </div>
      
                  <!-- Right Column -->
                  <div class="col-lg-6">
                    <div class="card shadow-lg p-4 rounded-4">
                        <div class="card-body">
                            <h2 class="fw-bold mb-4">Start your journey</h2>
                            <form id="otp-form">
                                <div class="mb-3">
                                    <label for="email" class="form-label visually-hidden">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" required>
                                </div>
                                <button type="button" class="btn btn-warning w-100 fw-bold" id="get-otp-btn" onclick="sendOTP(event)">Submit to Get OTP</button>
                                <div class="mt-3" id="otp-section" style="display: none;">
                                    <label for="otp" class="form-label visually-hidden">OTP</label>
                                    <input type="text" class="form-control mb-3" id="otp" placeholder="Enter OTP" required>
                                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="verifyOTP(event)">Verify OTP</button>
                                </div>
                            </form>
                            <p class="text-center mt-4 mb-0">Already completed the journey? <a href="login-registration.php" class="text-decoration-none fw-bold">LOGIN</a></p>
                        </div>
                    </div>
                </div>
                
              </div>
          </div>
      </section>
      <script>
        function sendOTP(event) {
    event.preventDefault();
    let email = document.getElementById("email").value;

    fetch("otp_handler.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=send_otp&email=${email}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.getElementById("otp-section").style.display = "block";
        }
    })
    .catch(error => console.error("Error:", error));
}

function verifyOTP(event) {
  event.preventDefault();
  const email = encodeURIComponent(document.getElementById("email").value.trim());
  const otp = encodeURIComponent(document.getElementById("otp").value.trim());
  if (!email || !otp) { alert("Enter email and OTP"); return; }

  fetch("otp_handler.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `action=verify_otp&email=${email}&otp=${otp}`
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === "success") {
      alert(data.message);
      window.location.href = data.redirect;
    } else {
      alert(data.message);
    }
  })
  .catch(err => console.error("Error:", err));
}
      </script>
      <!-- <section> close -->

      <!-- <section> begin -->
      <section>
    <div class="container">
      <div class="row g-5 align-items-start">
        <!-- Left Column -->
        <div class="col-lg-6 col-md-12">
          <h2 class="section-title">Test details</h2>
          <div class="info-box">
            <div class="info-item d-flex align-items-start mb-3">
              <img src="https://cdn-icons-png.flaticon.com/512/3063/3063829.png" class="me-3" alt="Online mode">
              <div>
                <h5>Online mode</h5>
                <p>Join from desktop for better performance</p><br>
              </div>
            </div>
            <hr>
            <div class="info-item d-flex align-items-start mb-3">
              <img src="https://cdn-icons-png.flaticon.com/512/2088/2088617.png" class="me-3" alt="Time">
              <div>
                <h5>24x7</h5>
                <p>Available on all days (Monday to Sunday)</p>
              </div>
            </div>
            <hr>
            <div class="info-item d-flex align-items-start">
              <img src="https://cdn-icons-png.flaticon.com/512/2891/2891491.png" class="me-3" alt="Duration">
              <div>
                <h5>30 mins. duration</h5>
                <p>To submit your test</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6 col-md-12">
          <h2 class="section-title">Eligibility Criteria</h2>
          <div class="info-box">
            <div class="info-item d-flex align-items-start mb-3">
              <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png" class="me-3" alt="6th to 12th">
              <div>
                <h5>All students from any state</h5>
                <p>All State boards, CBSE and ICSE/ISC boards students are eligible for Online test</p>
              </div>
            </div>
            <hr>
            <div class="info-item d-flex align-items-start mb-3">
              <img src="https://cdn-icons-png.flaticon.com/512/201/201818.png" class="me-3" alt="5th Passing">
              <div>
                <h5>Graduate students</h5>
                <p>Students who are below the age 27 years are also eligible</p>
              </div>
            </div>
            <hr>
            <div class="info-item d-flex align-items-start">
              <img src="https://cdn-icons-png.flaticon.com/512/2921/2921822.png" class="me-3" alt="12th Passed">
              <div>
                <h5>Class 12th passed students</h5>
                <p>Students who have passed class 12th are also eligible</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- <section> end -->
    <div class="container my-5">
    <!-- Scholarship Banner -->
    <div class="scholarship-banner">
      <span>Within <strong>30 days</strong> contact to your nearest center.</span>
      <img src="assets/img/calendar.png" alt="Calendar" width="50" height="auto">
    </div>
  </div>

  <section class="vh-70 d-flex align-items-center justify-content-center" style="background-color:rgb(246, 238, 226);">
          <div class="container">
              <div class="row align-items-center g-5">

  <!-- Registration Section -->
  <div class="register-section">
      <div class="row align-items-center">
        <div class="col-lg-6 illustration mb-4 mb-lg-0">
          <img src="assets/img/register.png" alt="Register Illustration">
        </div>
        <div class="col-lg-6">
          <div class="register-content">
            <h2 class="register-title">How to register?</h2>
            <p class="register-sub">In just 3-step process</p>

            <div class="step">
              <div class="step-number">1</div>
              <p>Verify your email id</p>
            </div>

            <div class="step">
              <div class="step-number">2</div>
              <p>Submit your details</p>
            </div>

            <div class="step">
              <div class="step-number">3</div>
              <p>Select preferred exam</p>
            </div>

            <!-- <div class="step">
              <div class="step-number">4</div>
              <p>Make payment</p>
            </div> -->
          </div>
        </div>
      </div>
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