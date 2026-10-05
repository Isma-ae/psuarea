<?php
session_start();

if (isset($_POST['lang'])) {
    $_SESSION['lang'] = $_POST['lang']; // Store the language in a session
}
?>