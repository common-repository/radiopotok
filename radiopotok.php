<?php
/**
 * @package Radiopotok
 */
/**
Plugin Name: Radiopotok
Plugin URI: http://radiopotok.ru/info/wp.html
Description: Этот виджет предоставляет вам возможность прослушивать онлайн радиостанции выбранные на сайте http://radiopotok.ru/radio_on_site
Version: 0.1
Author: Radiopotok.ru
Author URI: http://radiopotok.ru/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
  Copyright 2013  Radiopotok.ru  (email: radiopotok@bk.ru )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('RADIOPOTOK_VERSION', '0.1');

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}

if ( is_admin() )
    require_once dirname( __FILE__ ) . '/admin.php';

class Radiopotok_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'radiopotok_widget',
            __( 'Радиопоток' ),
            array( 'description' => __( 'Онлайн радио от Radiopotok.ru' ) )
        );
    }
    function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'];
            echo esc_html( $instance['title'] );
            echo $args['after_title'];
        }

        echo '<div id="RP_v4_radio" align="center" class="RPv4-well RPv4-well-small"><div class="RPv4-radioplayer-wrapper"><div id="RP_v4_radioplayer"></div></div><div class="RPv4-btn-group" align="left"><a class="RPv4-btn RPv4-dropdown-toggle" data-toggle="dropdown" href="http://radiopotok.ru/">Онлайн радио<span class="RPv4-caret"></span></a><ul class="RPv4-dropdown-menu"></ul></div></div>';

        echo $args['after_widget'];
    }
}

function Radiopotok_JS()
{
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'swfobject' );
	wp_enqueue_script( 'radiopotok_js', 'http://radiopotok.ru/f/script4/' . get_option( 'radiopotok_script_hash' ) . '.js', array('jquery', 'swfobject'), '', TRUE );
}

function Radiopotok_JS_Vars()
{
    $theme = get_option( 'radiopotok_script_theme' );
    if ('' == $theme) $theme = 'light';
	echo '<script type="text/javascript">var RP_v4_theme = "' . $theme . '";</script>'."\n";
}

function Radiopotok_register_widgets() {
    register_widget( 'Radiopotok_Widget' );
}

add_action( 'wp_head', 'Radiopotok_JS_Vars' );
add_action( 'init', 'Radiopotok_JS' );
add_action( 'widgets_init', 'Radiopotok_register_widgets' );
?>