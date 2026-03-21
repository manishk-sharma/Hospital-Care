<?php
require_once __DIR__ . "/config/database.php";

$page_title = "Hospital Care | Forgot Password";
$active_page = "forgot";
$notice = "";
$notice_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");

  if ($email === "") {
    $notice = "Please enter your registered email address.";
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
        $user_statement = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $user_statement->execute(["email" => $email]);

        if ($user_statement->fetch()) {
          $token = bin2hex(random_bytes(32));
          $token_hash = password_hash($token, PASSWORD_DEFAULT);
          $expires_at = (new DateTime("+1 hour"))->format("Y-m-d H:i:s");

          $delete_statement = $db->prepare("DELETE FROM password_resets WHERE email = :email");
          $delete_statement->execute(["email" => $email]);

          $insert_statement = $db->prepare(
            "INSERT INTO password_resets (email, token_hash, expires_at) VALUES (:email, :token_hash, :expires_at)"
          );
          $insert_statement->execute([
            "email" => $email,
            "token_hash" => $token_hash,
            "expires_at" => $expires_at,
          ]);

          $notice = "Reset request saved. Demo token: " . $token;
          $notice_type = "success";
        } else {
          $notice = "If this email is registered, a reset link will be sent.";
          $notice_type = "success";
        }
      } catch (Throwable $exception) {
        $notice = "Could not start password reset. Ensure database/schema.sql has been imported.";
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
      <h1>Recover Your Password</h1>
      <p>
        Enter your registered email and we will send a secure reset link to help
        you access your patient portal again.
      </p>
      <ul>
        <li>Fast account recovery workflow</li>
        <li>Secure reset token process</li>
        <li>Support available 24/7</li>
      </ul>
    </aside>

    <div class="auth-panel">
      <h2>Forgot Password</h2>
      <p class="auth-subtitle">Reset your account password securely.</p>

      <?php if ($notice !== ""): ?>
        <div class="notice <?php echo htmlspecialchars($notice_type); ?>">
          <?php echo htmlspecialchars($notice); ?>
        </div>
      <?php endif; ?>

      <form method="post" class="auth-form">
        <label for="email">Registered email</label>
        <input id="email" name="email" type="email" placeholder="you@example.com" required />

        <button type="submit" class="auth-button">Send Reset Link</button>
      </form>

      <div class="auth-links">
        <a href="login.php">Back to login</a>
        <a href="register.php">Create user account</a>
      </div>
    </div>
  </div>
</section>

<?php include "includes/footer.php"; ?>
