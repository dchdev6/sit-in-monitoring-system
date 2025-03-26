<?php
include '../../includes/functions.php';

// Set header to return JSON
header('Content-Type: application/json');

// Get updated counts
$data = [
    'studentsCount' => retrieve_students_dashboard(),
    'currentSitInCount' => retrieve_current_sit_in_dashboard(),
    'totalSitInCount' => retrieve_total_sit_in_dashboard(),
    
    // Programming language counts
    'cSharp' => retrieve_c_sharp_programming(),
    'c' => retrieve_c_programming(),
    'java' => retrieve_java_programming(),
    'asp' => retrieve_asp_programming(),
    'php' => retrieve_php_programming(),
    
    // Year level counts
    'freshmen' => retrieve_first(),
    'sophomore' => retrieve_second(),
    'junior' => retrieve_third(),
    'senior' => retrieve_fourth(),
];

// Return JSON response
echo json_encode($data);