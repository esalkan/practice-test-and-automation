<?php
// File: /Users/esalkan/Desktop/codesofmine.com/practice-test-and-automation/file-operations/upload.php

header('Content-Type: application/json');

$uploadDir = 'uploaded_files/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$response = array('success' => true, 'message' => '');

if (!empty($_FILES['files'])) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['files']['name'][$key];
        $file_size = $_FILES['files']['size'][$key];
        $file_tmp = $_FILES['files']['tmp_name'][$key];
        $file_type = $_FILES['files']['type'][$key];

        // Dosya boyutu kontrolü
        if ($file_size > 5 * 1024 * 1024) {
            $response['success'] = false;
            $response['message'] = 'File size exceeds 5MB limit: ' . $file_name;
            break;
        }

        // Dosya türü kontrolü
        $allowed_types = array('image/jpeg', 'image/png', 'application/pdf');
        if (!in_array($file_type, $allowed_types)) {
            $response['success'] = false;
            $response['message'] = 'Invalid file type: ' . $file_name;
            break;
        }

        $targetPath = $uploadDir . $file_name;
        
        if (move_uploaded_file($file_tmp, $targetPath)) {
            // Dosya başarıyla yüklendi
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to move uploaded file: ' . $file_name;
            break;
        }
    }
} else if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $tempName = $_FILES['file']['tmp_name'];
    $fileName = basename($_FILES['file']['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($tempName, $targetPath)) {
        $response['success'] = true;
        $response['message'] = 'File uploaded successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to move uploaded file';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'File upload error: ' . $_FILES['file']['error'];
}

echo json_encode($response);