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

        // Only handle POST (form is now in modal on index page)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /categories');
            exit;
        }

        // Verify CSRF
        if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'CSRF Token Mismatch';
            header('Location: /categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? '';

        if (empty($name)) {
            $_SESSION['error'] = 'Category name is required.';
        } elseif (!in_array($type, ['income', 'expense'])) {
            $_SESSION['error'] = 'Category type must be income or expense.';
        } else {
            $categoryModel = new Category();

            if ($categoryModel->create($_SESSION['user_id'], $name, $type)) {
                $_SESSION['message'] = 'Category created successfully!';
            } else {
                $_SESSION['error'] = 'Failed to create category. Please try again.';
            }
        }

        header('Location: /categories');
        exit;
    }

    public function edit($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Only handle POST (form is now in modal on index page)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /categories');
            exit;
        }

        $categoryModel = new Category();
        
        // Check if category belongs to user
        $category = $categoryModel->getByIdAndUser($id, $_SESSION['user_id']);
        if (!$category) {
            $_SESSION['error'] = 'Category not found.';
            header('Location: /categories');
            exit;
        }

        // Verify CSRF
        if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'CSRF Token Mismatch';
            header('Location: /categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? '';

        if (empty($name)) {
            $_SESSION['error'] = 'Category name is required.';
        } elseif (!in_array($type, ['income', 'expense'])) {
            $_SESSION['error'] = 'Category type must be income or expense.';
        } else {
            if ($categoryModel->update($id, $_SESSION['user_id'], $name, $type)) {
                $_SESSION['message'] = 'Category updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update category. Please try again.';
            }
        }

        header('Location: /categories');
        exit;
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
            // Get all other categories for the user to transfer transactions to
            $allCategories = $categoryModel->getAllByUser($_SESSION['user_id']);

            // Check if category has transactions
            $transactionCount = $categoryModel->getTransactionCount($id);

            // Get the category being deleted to check its type
            $categoryToDelete = $categoryModel->getByIdAndUser($id, $_SESSION['user_id']);

            if ($transactionCount > 0) {
                // Category has transactions, show transfer form
                $otherCategories = array_filter($allCategories, function($cat) use ($id, $categoryToDelete) {
                    return $cat['id'] != $id && $cat['type'] == $categoryToDelete['type'];
                });

                // If no other categories of the same type exist, we can't transfer
                if (empty($otherCategories)) {
                    $_SESSION['error'] = 'Cannot delete category because it has transactions and no other categories of the same type to transfer to.';
                    header('Location: /categories');
                    exit;
                }

                $data = [
                    'title' => 'Transfer Transactions and Delete Category',
                    'categoryToDelete' => $categoryToDelete,
                    'otherCategories' => $otherCategories,
                    'transactionCount' => $transactionCount,
                    'error' => null
                ];

                $this->view('categories/transfer_before_delete', $data);
                return;
            } else {
                // No transactions, safe to delete
                if ($categoryModel->delete($id, $_SESSION['user_id'])) {
                    $_SESSION['message'] = 'Category deleted successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to delete category.';
                }
                header('Location: /categories');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Unauthorized: Category does not belong to you.';
            header('Location: /categories');
            exit;
        }
    }

    public function transferAndDelete() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = new Category();

            $categoryId = $_POST['category_id'] ?? null;
            $newCategoryId = $_POST['new_category_id'] ?? null;

            if ($categoryModel->deleteWithTransfer($categoryId, $_SESSION['user_id'], $newCategoryId)) {
                $_SESSION['message'] = 'Category deleted successfully and transactions transferred!';
            } else {
                $_SESSION['error'] = 'Failed to delete category and transfer transactions.';
            }
        }

        header('Location: /categories');
        exit;
    }

    // ========== API METHODS FOR AJAX ==========

    /**
     * API: Create category via AJAX
     */
    public function apiCreate() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Verify CSRF
        if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'CSRF Token Mismatch']);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? '';

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Category name is required.']);
            return;
        }

        if (!in_array($type, ['income', 'expense'])) {
            echo json_encode(['success' => false, 'message' => 'Category type must be income or expense.']);
            return;
        }

        $categoryModel = new Category();

        if ($categoryModel->create($_SESSION['user_id'], $name, $type)) {
            echo json_encode([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => ['name' => $name, 'type' => $type]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create category.']);
        }
    }

    /**
     * API: Get category data for editing
     */
    public function apiGet($id) {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $categoryModel = new Category();
        $category = $categoryModel->getByIdAndUser($id, $_SESSION['user_id']);

        if (!$category) {
            echo json_encode(['success' => false, 'message' => 'Category not found.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * API: Update category via AJAX
     */
    public function apiUpdate($id) {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Verify CSRF
        if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'message' => 'CSRF Token Mismatch']);
            return;
        }

        $categoryModel = new Category();

        // Check if category belongs to user
        $category = $categoryModel->getByIdAndUser($id, $_SESSION['user_id']);
        if (!$category) {
            echo json_encode(['success' => false, 'message' => 'Category not found.']);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? '';

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Category name is required.']);
            return;
        }

        if (!in_array($type, ['income', 'expense'])) {
            echo json_encode(['success' => false, 'message' => 'Category type must be income or expense.']);
            return;
        }

        if ($categoryModel->update($id, $_SESSION['user_id'], $name, $type)) {
            echo json_encode([
                'success' => true,
                'message' => 'Category updated successfully!',
                'category' => ['id' => $id, 'name' => $name, 'type' => $type]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update category.']);
        }
    }
}