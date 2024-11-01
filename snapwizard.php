<?php

/*
Plugin Name: SnapWizard
Plugin URI: https://www.giuseppemaccario.com/snapwizard
Description: Embed Your Instagram Feed in your WordPress website. No coding skills required!
Text Domain: snapwizard
Author: Giuseppe Maccario
Author URI: https://www.giuseppemaccario.com
Version: 1.0.9
Requires at least: 6.4.2
Requires PHP: 8.1
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use SnapWizard\Dispatcher;

define("SNAPWIZARD_SLUG", dirname(plugin_basename(__FILE__)));
// define( "SNAPWIZARD_PLUGIN_URL", plugins_url('', __FILE__) );

function snapwizard_init()
{
    (Dispatcher::getInstance())->dispatch();
}

add_action('init', 'snapwizard_init');
