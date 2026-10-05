<?php
session_start(); // Start the session

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'th'; // Default to English

$file = "languages.json";
if (file_exists($file)) {
    $jsonData = file_get_contents($file);
    $languages = json_decode($jsonData, true);

    if (isset($languages[$lang])) {
        echo json_encode($languages[$lang]); // Return selected language
    } else {
        echo json_encode(["error" => "Language not found"]);
    }
} else {
    echo json_encode(["error" => "Language file not found"]);
}
?>