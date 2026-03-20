<?php
// Shared header layout for all pages.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title ?? "Hospital Care"); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="page-<?php echo htmlspecialchars($active_page ?? "home"); ?>">
  <header class="site-header">
    <div class="container header-grid">
      <div class="brand">
        <span class="brand-mark">HC</span>
        <div>
          <div class="brand-name">Hospital Care</div>
          <div class="brand-tagline">Compassion. Care. Commitment.</div>
        </div>
      </div>
      <div class="header-contact">
        <div>24/7 Emergency: <strong>+1 (555) 120-2400</strong></div>
        <div>info@hospitalcare.example</div>
      </div>
    </div>
  </header>

  <nav class="site-nav">
    <div class="container nav-grid">
      <a class="nav-logo" href="index.php">Hospital Care</a>
      <div class="nav-links">
        <?php $is_auth_page = in_array(($active_page ?? ""), ["login", "register", "forgot"], true); ?>
        <a href="index.php" class="<?php echo ($active_page ?? "home") === "home" ? "active" : ""; ?>">Home</a>
        <a href="about.php" class="<?php echo ($active_page ?? "") === "about" ? "active" : ""; ?>">About</a>
        <a href="service.php" class="<?php echo ($active_page ?? "") === "service" ? "active" : ""; ?>">Service</a>
        <a href="blog.php" class="<?php echo ($active_page ?? "") === "blog" ? "active" : ""; ?>">Blog</a>
        <a href="contact.php" class="<?php echo ($active_page ?? "") === "contact" ? "active" : ""; ?>">Contact</a>
        <a href="login.php" class="<?php echo $is_auth_page ? "active" : ""; ?>">Patient Login</a>
      </div>
      <a class="cta" href="contact.php">Book Appointment</a>
    </div>
  </nav>

  <button class="nav-reveal" type="button" aria-label="Show navigation">=</button>

  <main class="site-main">
