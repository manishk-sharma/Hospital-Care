<?php
require_once __DIR__ . "/config/database.php";

$page_title = "Hospital Care | Login";
$active_page = "login";
$notice = "";
$notice_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $password = trim($_POST["password"] ?? "");

  if ($email === "" || $password === "") {
    $notice = "Please enter both email and password.";
    $notice_type = "error";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $notice = "Please enter a valid email address.";
    $notice_type = "error";
  } else {
    $db_error = null;
    $db = db_connect($db_error);

    if ($db === null) {
      $notice = "Database connection failed. Check DB credentials in config/database.php.";
      $notice_type = "error";
    } else {
      try {
        $statement = $db->prepare(
          "SELECT id, full_name, email, password_hash FROM users WHERE email = :email LIMIT 1"
        );
        $statement->execute(["email" => $email]);
        $user = $statement->fetch();

        if ($user && password_verify($password, $user["password_hash"])) {
          if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
          }

          $_SESSION["user_id"] = $user["id"];
          $_SESSION["user_name"] = $user["full_name"];
          $_SESSION["user_email"] = $user["email"];

          $notice = "Login successful. Welcome, " . $user["full_name"] . "!";
          $notice_type = "success";
        } else {
          $notice = "Invalid email or password.";
          $notice_type = "error";
        }
      } catch (Throwable $exception) {
        $notice = "Could not process login. Ensure database/schema.sql has been imported.";
        $notice_type = "error";
      }
    }
  }
}

include "includes/header.php";
?>

<section class="auth-shell">
  <div class="auth-grid">
    <aside class="auth-side">
      <h1>Welcome Back</h1>
      <p>
        Access appointments, lab reports, and health records through your
        secure patient portal.
      </p>
      <ul>
        <li>View upcoming consultations</li>
        <li>Track prescriptions and reports</li>
        <li>Manage profile and notifications</li>
      </ul>
    </aside>

    <div class="auth-panel">
      <h2>Patient Login</h2>
      <p class="auth-subtitle">Sign in to continue to your dashboard.</p>

      <?php if ($notice !== ""): ?>
        <div class="notice <?php echo htmlspecialchars($notice_type); ?>">
          <?php echo htmlspecialchars($notice); ?>
        </div>
      <?php endif; ?>

      <form method="post" class="auth-form">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" placeholder="you@example.com" required />

        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter your password" required />

        <button type="submit" class="auth-button">Login</button>
      </form>

      <div class="auth-links">
        <a href="forgot-password.php">Forgot password?</a>
        <a href="register.php">Create user account</a>
      </div>
    </div>
  </div>
</section>

<?php include "includes/footer.php"; ?>
