<?php
/**
 * Secure File Upload Handler
 */

class FileUploader {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;
    private $errors = [];
    
    public function __construct($uploadDir, $allowedTypes, $maxSize) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
        
        // Create directory if not exists
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function upload($file) {
        // Check if file was uploaded
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'File upload error'];
        }
        
        // Check file size
        if ($file['size'] > $this->maxSize) {
            return ['success' => false, 'error' => 'File too large (max ' . ($this->maxSize / 1024 / 1024) . 'MB)'];
        }
        
        // Get file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validate extension
        if (!in_array($ext, $this->allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $this->allowedTypes)];
        }
        
        // Validate MIME type (security)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf'
        ];
        
        if (isset($allowedMimes[$ext]) && $mimeType !== $allowedMimes[$ext]) {
            return ['success' => false, 'error' => 'File content does not match extension'];
        }
        
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $filepath = $this->uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Set proper permissions
            chmod($filepath, 0644);
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $filepath,
                'size' => $file['size']
            ];
        }
        
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
    
    public function delete($filepath) {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
?>
