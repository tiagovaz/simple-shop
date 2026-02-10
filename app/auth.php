<?php
// app/auth.php

function login() {
  $error = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $db = db();
    $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
      session_regenerate_id(true);
      $_SESSION['user'] = $user['email'];
      
      // Redirect to home page
      header('Location: index.php');
      exit;
    }

    $error = 'Invalid email or password.';
  }

  require __DIR__ . '/views/login.php';
}

function register() {
  $error = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || strlen($password) < 6) {
      $error = 'Email is required and password must be at least 6 characters.';
    } else {
      $db = db();
      $hash = password_hash($password, PASSWORD_DEFAULT);

      try {
        $stmt = $db->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
        $stmt->execute([$email, $hash]);

        // Redirect to login page
        header('Location: index.php?action=login');
        exit;

      } catch (PDOException $e) {
        $error = 'That email is already registered.';
      }
    }
  }

  require __DIR__ . '/views/register.php';
}

function logout() {
  // Clear session data
  $_SESSION = [];
  
  // Destroy the session cookie
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
  }
  
  // Destroy session
  session_destroy();
  
  // Start fresh session
  session_start();
  
  // Redirect to home page
  header('Location: index.php');
  exit;
}
