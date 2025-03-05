<?php
/**
 * Plugin Name: Ilija's Plugin
 * Description: Test plug-in.
 * Author: Ilija Toskovic
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load files

require_once 'includes/proizvodi-admin.php';
require_once 'includes/register-proizvodi-type.php';
require_once 'includes/prikazi_proizvode.php';
require_once 'includes/proizvodi-rest-api.php';

function test_plugin() {
	
}

test_plugin();