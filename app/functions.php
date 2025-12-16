<?php

function sanitize_input($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize_input($value);
        }
        return $data;
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header("Location: " . APP_URL . "/" . $path);
    exit;
}

function current_user()
{
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function require_admin()
{
    require_login();
    $user = current_user();
    if ($user['role'] !== 'admin') {
        redirect('dashboard/index.php'); // Or 403 page
    }
}

function format_price($amount)
{
    return '$' . number_format($amount, 2);
}

function flash($name, $message = '', $class = 'success')
{
    if (!empty($message)) {
        $_SESSION['flash'][$name] = [
            'message' => $message,
            'class' => $class
        ];
    } elseif (isset($_SESSION['flash'][$name])) {
        $flash = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $flash;
    }
}

function get_image_url($image_path)
{
    if (empty($image_path)) {
        return APP_URL . '/assets/img/placeholder.jpg';
    }
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    return APP_URL . '/uploads/' . $image_path;
}
