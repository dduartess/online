<?php
include_once("legacy_compat.php");
// Compat layer for legacy e-Cidade code running on modern PHP versions.
if (function_exists('session_status')) {
  if (session_status() === PHP_SESSION_NONE) {
    @session_start();
  }
} elseif (!session_id()) {
  @session_start();
}

if (!function_exists('session_register')) {
  function session_register($name) {
    if (is_array($name)) {
      foreach ($name as $varName) {
        session_register($varName);
      }
      return true;
    }

    if (!is_string($name) || $name === '') {
      return false;
    }

    $_SESSION[$name] = array_key_exists($name, $GLOBALS) ? $GLOBALS[$name] : null;
    return true;
  }
}

if (!function_exists('session_is_registered')) {
  function session_is_registered($name) {
    return isset($_SESSION[$name]);
  }
}

if (!function_exists('session_unregister')) {
  function session_unregister($name) {
    if (isset($_SESSION[$name])) {
      unset($_SESSION[$name]);
    }
    return true;
  }
}

// Legacy superglobal aliases expected by older code.
$HTTP_SESSION_VARS =& $_SESSION;
$HTTP_SERVER_VARS  =& $_SERVER;
$HTTP_POST_VARS    =& $_POST;
$HTTP_GET_VARS     =& $_GET;
$HTTP_COOKIE_VARS  =& $_COOKIE;
$HTTP_ENV_VARS     =& $_ENV;
$HTTP_FILES_VARS   =& $_FILES;
