<?php
// Auth helpers — called by login.php, register.php, account.php

function authLogin(mysqli $con, string $email, string $password): array
{
    $stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        return ['success' => false, 'error' => 'No account found with that email.'];
    }

    if (!password_verify($password, $user['user_password'])) {
        return ['success' => false, 'error' => 'Incorrect password.'];
    }

    return ['success' => true, 'user' => $user];
}

function authRegister(mysqli $con, string $name, string $email, string $password, string $confirm): array
{
    if (trim($name) === '') return ['success' => false, 'error' => 'Name is required.'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['success' => false, 'error' => 'Enter a valid email.'];
    if (strlen($password) < 6) return ['success' => false, 'error' => 'Password must be at least 6 characters.'];
    if ($password !== $confirm) return ['success' => false, 'error' => 'Passwords do not match.'];

    // Check duplicate email
    $stmt = $con->prepare("SELECT user_id FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'error' => 'An account with that email already exists.'];
    }
    $stmt->close();

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $con->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hash);
    $stmt->execute();
    $newId = $con->insert_id;
    $stmt->close();

    return ['success' => true, 'user_id' => $newId, 'user_name' => $name];
}

function authChangePassword(mysqli $con, int $userId, string $current, string $newPass, string $confirm): array
{
    if (strlen($newPass) < 6) return ['success' => false, 'error' => 'New password must be at least 6 characters.'];
    if ($newPass !== $confirm) return ['success' => false, 'error' => 'Passwords do not match.'];

    $stmt = $con->prepare("SELECT user_password FROM users WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row || !password_verify($current, $row['user_password'])) {
        return ['success' => false, 'error' => 'Current password is incorrect.'];
    }

    $hash = password_hash($newPass, PASSWORD_BCRYPT);
    $stmt = $con->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $hash, $userId);
    $stmt->execute();
    $stmt->close();

    return ['success' => true];
}
