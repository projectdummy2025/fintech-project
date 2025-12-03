<?php

require_once __DIR__ . '/../Core/Controller.php';

class DashboardController extends Controller {
    
    public function index() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $data = [
            'title' => 'Dashboard',
            'username' => $_SESSION['username']
        ];

        $this->view('dashboard/index', $data);
    }
}
