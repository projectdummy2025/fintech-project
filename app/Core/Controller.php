<?php

class Controller {
    private $currentView;
    private $sections = [];
    private $currentSection = null;
    private $viewLayout = null;

    public function view($view, $data = []) {
        // Store the current view to identify it later
        $this->currentView = $view;
        // Extract data to variables
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Start output buffering to capture the view content
            ob_start();
            require_once $viewFile;
            $viewContent = ob_get_clean();

            // If layout was set in the view, process with layout
            if ($this->viewLayout) {
                $layoutFile = __DIR__ . '/../Views/' . $this->viewLayout . '.php';

                if (file_exists($layoutFile)) {
                    // Extract data again for layout
                    extract($data);

                    // Start output buffering for layout
                    ob_start();
                    require_once $layoutFile;
                    $layoutContent = ob_get_clean();

                    // Replace section placeholders in layout with actual content
                    $layoutContent = $this->processLayoutSections($layoutContent);

                    echo $layoutContent;
                } else {
                    echo "Layout does not exist: " . $this->viewLayout;
                }
            } else {
                echo $viewContent;
            }
        } else {
            die("View does not exist: " . $view);
        }
    }

    public function extend($layout) {
        $this->viewLayout = $layout;
    }

    public function section($sectionName) {
        $this->currentSection = $sectionName;
        ob_start();
    }

    public function endSection() {
        if ($this->currentSection !== null) {
            $sectionContent = ob_get_clean();
            $this->sections[$this->currentSection] = $sectionContent;
            $this->currentSection = null;
        }
    }

    public function renderSection($sectionName) {
        return isset($this->sections[$sectionName]) ? $this->sections[$sectionName] : '';
    }

    private function processLayoutSections($layoutContent) {
        // Replace content sections in the layout
        foreach ($this->sections as $sectionName => $sectionContent) {
            // Handle different syntax variations
            $layoutContent = str_replace('<?= $this->renderSection(\'' . $sectionName . '\') ?>', $sectionContent, $layoutContent);
            $layoutContent = str_replace('<?php echo $this->renderSection(\'' . $sectionName . '\') ?>', $sectionContent, $layoutContent);
            $layoutContent = str_replace('<?= $this->renderSection("' . $sectionName . '") ?>', $sectionContent, $layoutContent);
            $layoutContent = str_replace('<?php echo $this->renderSection("' . $sectionName . '") ?>', $sectionContent, $layoutContent);
        }
        return $layoutContent;
    }
}

// Function for compatibility with CodeIgniter syntax
if (!function_exists('service')) {
    function service($service) {
        if ($service === 'uri') {
            // Create a simple URI service object
            $uri = new class() {
                public function getSegment($index) {
                    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $segments = explode('/', trim($path, '/'));
                    // Index adjustment to match standard routing where index 1 is the first segment after domain
                    if (isset($segments[$index - 1])) {
                        return $segments[$index - 1];
                    }
                    return null;
                }
            };
            return $uri;
        }
        return null;
    }
}
