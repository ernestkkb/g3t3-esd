<?php

# THIS FILE IS REFERENCE FROM WAD. WILL NEED TO MAKE ADJUSTMENTS BEFORE USE

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

// session_start();

?>