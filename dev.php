<?php
if (!str_starts_with(PHP_VERSION, '7.4')) {
    echo '<div style="color: #000; padding: 10px; text-align: center;">
            ⚠️ Warning: This site is running PHP ' . PHP_VERSION . '. PHP 7.4 is required for development.
          </div>';
      die;
}
