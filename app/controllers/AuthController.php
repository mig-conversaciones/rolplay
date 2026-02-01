<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'title' => 'Iniciar sesion',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'title' => 'Crear cuenta',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function register(): void
    {
        $data = [
            'name' => $this->sanitize($_POST['name'] ?? ''),
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'password' => (string) ($_POST['password'] ?? ''),
            'role' => $this->sanitize($_POST['role'] ?? 'aprendiz'),
            'ficha' => $this->sanitize($_POST['ficha'] ?? ''),
        ];

        $errors = $this->validate($data, [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:150',
            'password' => 'required|min:6|max:255',
            'role' => 'required',
        ]);

        if (!empty($errors)) {
            $this->view('auth/register', [
                'title' => 'Crear cuenta',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $userModel = new User();
        $existing = $userModel->findByEmail($data['email']);
        if ($existing) {
            $errors['email'][] = 'Este correo ya esta registrado';
            $this->view('auth/register', [
                'title' => 'Crear cuenta',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $userId = $userModel->create($data);

        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'ficha' => $data['ficha'],
        ];

        $this->redirect('/');
    }

    public function login(): void
    {
        $data = [
            'email' => $this->sanitize($_POST['email'] ?? ''),
            'password' => (string) ($_POST['password'] ?? ''),
        ];

        $errors = $this->validate($data, [
            'email' => 'required|email|max:150',
            'password' => 'required|min:6|max:255',
        ]);

        if (!empty($errors)) {
            $this->view('auth/login', [
                'title' => 'Iniciar sesion',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($data['email']);

        if (!$user || !$user['active'] || !password_verify($data['password'], $user['password'])) {
            $errors['email'][] = 'Credenciales invalidas o usuario inactivo';
            $this->view('auth/login', [
                'title' => 'Iniciar sesion',
                'errors' => $errors,
                'old' => ['email' => $data['email']],
            ]);
            return;
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => (string) $user['name'],
            'email' => (string) $user['email'],
            'role' => (string) $user['role'],
            'ficha' => (string) ($user['ficha'] ?? ''),
        ];

        // Redirigir según el rol del usuario
        $role = (string) $user['role'];
        if ($role === 'admin') {
            $this->redirect('/admin');
        } elseif ($role === 'instructor') {
            $this->redirect('/instructor');
        } else {
            // aprendiz u otros roles
            $this->redirect('/scenarios');
        }
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
        $this->redirect('/login');
    }
}
