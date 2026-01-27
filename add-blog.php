<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login-registration.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--    Document Title-->
    <title>Add Blogs</title>

    <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#ffffff">
    <!--    Stylesheets-->
    <link href="assets/css/theme.css" rel="stylesheet" />
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<style>
    .full-height {
      min-height:100vh;
    }

    .form-group label {
      font-weight: 600;
    }

    .ck-editor__editable {
      min-height: 200px;
    }

    .btn-preview, .btn-submit {
      width: 160px;
    }
    
    .btn-back {
      background-color: #0e97ae;
      color: white;
      min-width: 180px;
    }

    .text-center button {
      margin: 10px 5px;
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
          <img src="assets/img/call.png" height="40" alt="logo" /> +91-1234567890
          </a>
          </div>
        </div>
      </nav>

      <!-- <section> begin -->
      <section class="d-flex align-items-center justify-content-center" style="background-color:rgb(246, 238, 226);">
        <div class="container">

        <form action="submit_blog.php" method="POST" enctype="multipart/form-data">
            <div class="row mt-4 mb-3">
                <div class="col-12 text-center">
                    <h2 class="mb-4 text-center">Add New Blog</h2>
                </div>
            </div>

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" required>
            </div>

            <div class="form-group mt-3">
                <label for="category">Category:</label>
                <input type="text" class="form-control" name="category" required>
            </div>

            <div class="form-group mt-3">
                <label for="image">Featured Image (Upload):</label>
                <input type="file" class="form-control-file" name="image">
            </div>

            <div class="form-group">
                <label for="image_url">Or use an image URL:</label>
                <input type="url" class="form-control" name="image_url" placeholder="https://example.com/image.jpg">
            </div>

            <div class="form-group mt-3">
                <label for="description"><strong>Blog Description</strong></label>
                <textarea name="description" id="description"></textarea>
                <script>CKEDITOR.replace('description');</script>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-primary btn-preview" onclick="previewBlog()">Preview Blog</button>
                <button type="submit" class="btn btn-primary btn-submit">Submit Blog</button>
            </div>

            <div id="successMessage" class="text-success font-weight-bold text-center mt-3" style="display: none;">
                Blog submitted successfully!
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-back">Back to Dashboard</a>
        </div>
    </div>

        <!-- Preview Script -->
        <script>
        function previewBlog() {
            const title = document.querySelector('[name="title"]').value;
            const category = document.querySelector('[name="category"]').value;
            const description = CKEDITOR.instances.description.getData();
            const imageURL = document.querySelector('[name="image_url"]').value;

            const previewWindow = window.open('', '_blank');
            previewWindow.document.write(`
            <html>
            <head><title>Blog Preview</title></head>
            <body style="font-family: Arial; padding: 20px;">
                <h2>${title}</h2>
                <p><strong>Category:</strong> ${category}</p>
                ${imageURL ? `<img src="${imageURL}" style="max-width: 100%; margin: 15px 0;">` : ""}
                <hr>
                ${description}
            </body>
            </html>
            `);
            previewWindow.document.close();
        }

        if (window.location.search.includes("success=1")) {
            document.getElementById("successMessage").style.display = "block";
        }
        </script>
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