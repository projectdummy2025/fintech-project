<?php

require_once __DIR__ . '/../Core/Controller.php';

class HomeController extends Controller {
    public function index() {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        // Show landing page for guests
        $this->view('home/index', [
            'title' => 'Take Control of Your Money'
        ]);
    }
}
