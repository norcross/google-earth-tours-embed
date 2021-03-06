<?php
/*
Plugin Name: Google Earth Tours Embed
Plugin URI: http://andrewnorcross.com/plugins/
Description: A tool for embedding a Google Earth Tour with a shortcode.
Author: Andrew Norcross
Version: 0.0.2
Requires at least: 3.8
Author URI: http://andrewnorcross.com
*/
/*  Copyright 2014 Andrew Norcross

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License (GPL v2) only.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if( ! defined( 'RKV_GETE_BASE' ) ) {
    define( 'RKV_GETE_BASE', plugin_basename(__FILE__) );
}

if( ! defined( 'RKV_GETE_VER' ) ) {
    define( 'RKV_GETE_VER', '0.0.2' );
}

class GTour_Embed_Core
{
    /**
     * Static property to hold our singleton instance
     * @var GTour_Embed_Core
     */
    static $instance = false;

    /**
     * This is our constructor
     *
     * @return GTour_Embed_Core
     */
    private function __construct() {
        add_action( 'plugins_loaded',       array(  $this,  'textdomain'            )           );
        add_action( 'plugins_loaded',       array(  $this,  'load_files'            )           );
    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * retuns it.
     *
     * @return $instance
     */
    public static function getInstance() {
        // set the instance if one does not exist
        if ( ! self::$instance ) {
            self::$instance = new self;
        }
        // return the instance
        return self::$instance;
    }

    /**
     * load textdomain
     *
     * @return string
     */
    public function textdomain() {
        load_plugin_textdomain( 'google-earth-tour-embed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * load our secondary files
     * @return null
     */
    public function load_files() {
        require_once( 'lib/admin.php' );
        require_once( 'lib/front.php' );
    }

/// end class
}

// Instantiate our class
$GTour_Embed_Core = GTour_Embed_Core::getInstance();
