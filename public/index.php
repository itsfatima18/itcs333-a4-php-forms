
/**
 * ITCS333 A4 starter — contact form.
 * Requirements (see README rubric):
 *   - GET: render the form
 *   - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *   - invalid POST: re-render with inline errors + repopulated fields (200)
 *   - valid POST: redirect (302/303) to a success page (PRG)
 *   - escape all output with htmlspecialchars
 */


<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/validate.php';

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$values = ['name' => '', 'email' => '', 'message' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim((string)($_POST['name'] ?? ''));
    $email   = trim((string)($_POST['email'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    $values = ['name' => $name, 'email' => $email, 'message' => $message];

    if (!validate_required($name)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($name, 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    if (!validate_required($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (!validate_required($message)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($message, 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    if (!$errors) {
        header('Location: success.php', true, 303);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
  <form method="post" action="index.php" novalidate>
    <div>
      <label>Name
        <input type="text" name="name" value="<?= e($values['name']) ?>">
      </label>
      <?php if (isset($errors['name'])): ?>
        <span class="error" style="color:red"><?= e($errors['name']) ?></span>
      <?php endif; ?>
    </div>

    <div>
      <label>Email
        <input type="email" name="email" value="<?= e($values['email']) ?>">
      </label>
      <?php if (isset($errors['email'])): ?>
        <span class="error" style="color:red"><?= e($errors['email']) ?></span>
      <?php endif; ?>
    </div>

    <div>
      <label>Message
        <textarea name="message"><?= e($values['message']) ?></textarea>
      </label>
      <?php if (isset($errors['message'])): ?>
        <span class="error" style="color:red"><?= e($errors['message']) ?></span>
      <?php endif; ?>
    </div>

    <button type="submit">Send</button>
  </form>
</body>
</html>