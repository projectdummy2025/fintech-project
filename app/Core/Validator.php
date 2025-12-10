<?php

class Validator {
    private $errors = [];
    
    /**
     * Validate required field
     */
    public function required($field, $value, $fieldName = null) {
        if (empty($value) && $value !== '0') {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' is required.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate minimum length
     */
    public function minLength($field, $value, $min, $fieldName = null) {
        if (strlen($value) < $min) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be at least ' . $min . ' characters.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate maximum length
     */
    public function maxLength($field, $value, $max, $fieldName = null) {
        if (strlen($value) > $max) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must not exceed ' . $max . ' characters.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate numeric
     */
    public function numeric($field, $value, $fieldName = null) {
        if (!is_numeric($value)) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be a number.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate positive number
     */
    public function positive($field, $value, $fieldName = null) {
        if (!is_numeric($value) || $value <= 0) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be a positive number.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate email
     */
    public function email($field, $value, $fieldName = null) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be a valid email address.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate in array (enum)
     */
    public function in($field, $value, $allowed, $fieldName = null) {
        if (!in_array($value, $allowed)) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be one of: ' . implode(', ', $allowed) . '.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate date
     */
    public function date($field, $value, $fieldName = null) {
        if (!strtotime($value)) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be a valid date.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate URL
     */
    public function url($field, $value, $fieldName = null) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be a valid URL.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate integer
     */
    public function integer($field, $value, $fieldName = null) {
        if (!filter_var($value, FILTER_VALIDATE_INT) && $value !== 0) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be an integer.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate minimum value
     */
    public function min($field, $value, $min, $fieldName = null) {
        if ($value < $min) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must be at least ' . $min . '.';
            return false;
        }
        return true;
    }
    
    /**
     * Validate maximum value
     */
    public function max($field, $value, $max, $fieldName = null) {
        if ($value > $max) {
            $this->errors[$field] = ($fieldName ?? ucfirst($field)) . ' must not exceed ' . $max . '.';
            return false;
        }
        return true;
    }
    
    /**
     * Custom validation rule
     */
    public function custom($field, $value, $callback, $errorMessage) {
        if (!$callback($value)) {
            $this->errors[$field] = $errorMessage;
            return false;
        }
        return true;
    }
    
    /**
     * Check if there are any errors
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Get all errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get first error
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Get error for specific field
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }
    
    /**
     * Clear errors
     */
    public function clearErrors() {
        $this->errors = [];
    }
}
