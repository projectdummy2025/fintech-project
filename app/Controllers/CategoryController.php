<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Category.php';

class CategoryController extends Controller {

    public function index() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Categories',
            'categories' => $categories
        ];

        $this->view('categories/index', $data);
    }

    public function create() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $data = [
            'title' => 'Create Category',
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $name = trim($_POST['name'] ?? '');
            $type = $_POST['type'] ?? '';

            if (empty($name)) {
                $data['error'] = 'Category name is required.';
            } elseif (!in_array($type, ['income', 'expense'])) {
                $data['error'] = 'Category type must be income or expense.';
            } else {
                $categoryModel = new Category();

                if ($categoryModel->create($_SESSION['user_id'], $name, $type)) {
                    $data['success'] = 'Category created successfully!';
                    header('Location: /categories');
                    exit;
                } else {
                    $data['error'] = 'Failed to create category. Please try again.';
                }
            }
        }

        $data['csrf_field'] = Csrf::field();

        $this->view('categories/create', $data);
    }

    public function edit($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $categoryModel = new Category();
        
        // Check if category belongs to user
        $category = $categoryModel->getByIdAndUser($id, $_SESSION['user_id']);
        if (!$category) {
            header('Location: /categories');
            exit;
        }

        $data = [
            'title' => 'Edit Category',
            'category' => $category,
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $name = trim($_POST['name'] ?? '');
            $type = $_POST['type'] ?? '';

            if (empty($name)) {
                $data['error'] = 'Category name is required.';
            } elseif (!in_array($type, ['income', 'expense'])) {
                $data['error'] = 'Category type must be income or expense.';
            } else {
                if ($categoryModel->update($id, $_SESSION['user_id'], $name, $type)) {
                    $data['success'] = 'Category updated successfully!';
                    $data['category'] = $categoryModel->getByIdAndUser($id, $_SESSION['user_id']); // Refresh category data
                } else {
                    $data['error'] = 'Failed to update category. Please try again.';
                }
            }
        }

        $data['csrf_field'] = Csrf::field();

        $this->view('categories/edit', $data);
    }

    public function delete($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $categoryModel = new Category();
        
        // Check if category belongs to user
        if ($categoryModel->belongsToUser($id, $_SESSION['user_id'])) {
            // Note: Deletion might fail due to foreign key constraints if transactions exist
            if ($categoryModel->delete($id, $_SESSION['user_id'])) {
                $_SESSION['message'] = 'Category deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete category. It may have associated transactions.';
            }
        }

        header('Location: /categories');
        exit;
    }
}