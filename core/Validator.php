<?php
/**
 * Form Validator Class
 */

class Validator {
    private $errors = [];
    
    // Required field
    public function required($field, $value, $message = null) {
        if (empty($value)) {
            $this->errors[$field] = $message ?: ucfirst($field) . " is required";
        }
        return $this;
    }
    
    // Email validation
    public function email($field, $value, $message = null) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?: "Invalid email format";
        }
        return $this;
    }
    
    // Min length
    public function minLength($field, $value, $min, $message = null) {
        if (strlen($value) < $min) {
            $this->errors[$field] = $message ?: ucfirst($field) . " must be at least {$min} characters";
        }
        return $this;
    }
    
    // Max length
    public function maxLength($field, $value, $max, $message = null) {
        if (strlen($value) > $max) {
            $this->errors[$field] = $message ?: ucfirst($field) . " must not exceed {$max} characters";
        }
        return $this;
    }
    
    // Numeric
    public function numeric($field, $value, $message = null) {
        if (!is_numeric($value)) {
            $this->errors[$field] = $message ?: ucfirst($field) . " must be a number";
        }
        return $this;
    }
    
    // Mobile number (10 digits)
    public function mobile($field, $value, $message = null) {
        if (!preg_match('/^[6-9]\d{9}$/', $value)) {
            $this->errors[$field] = $message ?: "Invalid mobile number";
        }
        return $this;
    }
    
    // Aadhar number (12 digits)
    public function aadhar($field, $value, $message = null) {
        if (!preg_match('/^\d{12}$/', str_replace(' ', '', $value))) {
            $this->errors[$field] = $message ?: "Invalid Aadhar number";
        }
        return $this;
    }
    
    // PAN number
    public function pan($field, $value, $message = null) {
        if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', strtoupper($value))) {
            $this->errors[$field] = $message ?: "Invalid PAN number";
        }
        return $this;
    }
    
    // Check if valid
    public function isValid() {
        return empty($this->errors);
    }
    
    // Get errors
    public function getErrors() {
        return $this->errors;
    }
    
    // Get first error
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}
?>
