<?php
require_once __DIR__ . "/config/database.php";

$page_title = "Hospital Care | Create User";
$active_page = "register";
$notice = "";
$notice_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $full_name = trim($_POST["full_name"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $phone = trim($_POST["phone"] ?? "");
  $password = trim($_POST["password"] ?? "");
  $confirm_password = trim($_POST["confirm_password"] ?? "");

  if ($full_name === "" || $email === "" || $phone === "" || $password === "" || $confirm_password === "") {
    $notice = "Please complete all fields.";
    $notice_type = "error";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $notice = "Please enter a valid email address.";
    $notice_type = "error";
  } elseif ($password !== $confirm_password) {
    $notice = "Password and confirm password do not match.";
    $notice_type = "error";
  } elseif (strlen($password) < 8) {
    $notice = "Password must be at least 8 characters long.";
    $notice_type = "error";
  } else {
    $db_error = null;
    $db = db_connect($db_error);

    if ($db === null) {
      $notice = "Database connection failed. Check DB credentials in config/database.php.";
      $notice_type = "error";
    } else {
      try {
        $check_statement = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $check_statement->execute(["email" => $email]);

        if ($check_statement->fetch()) {
          $notice = "An account with this email already exists.";
          $notice_type = "error";
        } else {
          $password_hash = password_hash($password, PASSWORD_DEFAULT);

          $insert_statement = $db->prepare(
            "INSERT INTO users (full_name, email, phone, password_hash) VALUES (:full_name, :email, :phone, :password_hash)"
          );
          $insert_statement->execute([
            "full_name" => $full_name,
            "email" => $email,
            "phone" => $phone,
            "password_hash" => $password_hash,
          ]);

          $notice = "User profile created successfully. You can now login.";
          $notice_type = "success";
        }
      } catch (Throwable $exception) {
        $notice = "Could not create user. Ensure database/schema.sql has been imported.";
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
      <h1>Create Your Patient Account</h1>
      <p>
        Register once to book appointments faster, receive reminders, and access
        your digital medical information.
      </p>
      <ul>
        <li>Book and reschedule appointments online</li>
        <li>Get updates from your care team</li>
        <li>Access reports anytime, anywhere</li>
      </ul>
    </aside>

    <div class="auth-panel">
      <h2>Create User</h2>
      <p class="auth-subtitle">Register your secure account in a minute.</p>

      <?php if ($notice !== ""): ?>
        <div class="notice <?php echo htmlspecialchars($notice_type); ?>">
          <?php echo htmlspecialchars($notice); ?>
        </div>
      <?php endif; ?>

      <form method="post" class="auth-form">
        <label for="full_name">Full name</label>
        <input id="full_name" name="full_name" type="text" placeholder="Your full name" required />

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" placeholder="you@example.com" required />

        <label for="phone">Phone number</label>
        <input id="phone" name="phone" type="tel" placeholder="+1 (555) 000-0000" required />

        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Create password" required />

        <label for="confirm_password">Confirm password</label>
        <input id="confirm_password" name="confirm_password" type="password" placeholder="Re-enter password" required />

        <button type="submit" class="auth-button">Create Account</button>
      </form>

      <div class="auth-links">
        <a href="login.php">Already have an account? Login</a>
        <a href="forgot-password.php">Forgot password</a>
      </div>
    </div>
  </div>
</section>

<?php include "includes/footer.php"; ?>
