<?php

if (!function_exists('redirect')) {

  function redirect($page) {

      if (!headers_sent()) {
          header('location: ' . URLROOT . '/' . $page);
          exit();
      } else {
          echo '<script type="text/javascript">';
          echo 'window.location.href="' . URLROOT . '/' . $page . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . URLROOT . '/' . $page . '" />';
          echo '</noscript>';
          exit();
          
      }
  }
}

if (!function_exists('url')) {

  function url($path = '') {
      return URLROOT . '/' . $path;
  }
}