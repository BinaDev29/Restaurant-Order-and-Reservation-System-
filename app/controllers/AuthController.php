<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../functions.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        global $pdo;
        $this->userModel = new User($pdo);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize_input($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                flash('login_success', 'Welcome back, ' . $user['full_name']);

                // Redirect everyone to Home Page as requested
                redirect('index.php');
            } else {
                flash('login_error', 'Invalid email or password', 'danger');
                redirect('login.php');
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize_input($_POST['full_name']);
            $email = sanitize_input($_POST['email']);
            $password = $_POST['password'];
            $phone = sanitize_input($_POST['phone'] ?? '');

            if ($this->userModel->register($name, $email, $password, $phone)) {
                flash('register_success', 'Account created! Please login.');
                redirect('login.php');
            } else {
                flash('register_error', 'Registration failed. Email might be in use.', 'danger');
                redirect('register.php');
            }
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('login.php');
    }
}
