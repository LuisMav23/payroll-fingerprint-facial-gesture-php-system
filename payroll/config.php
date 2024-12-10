<?php

define('BASE_URL', 'http://localhost/payroll-fingerprint-facial-gesture-php-system/payroll/');
define('REG_URL', 'http://localhost/payroll-fingerprint-facial-gesture-php-system/payroll/registration/');
define('COMPANY_NAME', 'Payroll Management System');

// MySQL Database Details
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'payroll_mdb');
define('DB_PREFIX', 'wy_');

// Email Constant
define("PHPMAILER_SMTPSECURE", "ssl");
define("PHPMAILER_HOST", "smtp.gmail.com");
define("PHPMAILER_PORT", "465");
define("PHPMAILER_USERNAME", "gnnwoodmanufacturing@gmail.com");
define("PHPMAILER_PASSWORD", "cmmn spzj zbry fxwx");
define("PHPMAILER_FROM", "gnnwoodmanufacturing@gmail.com");
define("PHPMAILER_FROMNAME", "gnnwoodmanufacturing");
define("PHPMAILER_WORDWRAP", "50");

ini_set("display_errors", 0);

date_default_timezone_set("Asia/Manila");

$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}
session_start();

// Use Composer's autoload for mPDF
require 'vendor/autoload.php';

// Create an instance of mPDF
$mpdf = new \Mpdf\Mpdf();

include(dirname(__FILE__) . '/functions.php');

if (isset($_SESSION['Admin_ID']) && isset($_SESSION['Login_Type'])) {
  $userData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
} else {
  $userData = array();
}
