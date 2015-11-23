<?php

/*
Plugin Name: WP Sync to Salesforce
Plugin URI: https://github.com/codenameEli/wp-sync-to-salesforce
Description: WordPress plugin that syncs website meta to Salesforce
Author: Tim "Eli" Dalbey
Version: 0.1
*/

/*  Copyright 2014  Tim "Eli" Dalbey

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace WPSyncSalesforce;

class WPSyncSalesforce {

    private static $instance = null;

    private function __construct() {

        $this->setup_autoload();

        $this->setup_includes();
    }

    public static function instance() {

        if( self::$instance === null ){

            self::$instance = new WPSyncSalesforce();
        }

        return self::$instance;
    }

    public static function get_plugin_dir() {

        return plugin_dir_path( __FILE__ );
    }

    public static function get_plugin_url() {

        return plugins_url( 'wp-sync-to-salesforce' );
    }

    public static function get_js_url() {

        return self::get_plugin_url() . '/js/';
    }

    public static function get_css_url() {

        return self::get_plugin_url() . '/css/';
    }

    public static function get_images_url() {

        return self::get_plugin_url() . '/images/';
    }

    public static function get_include_dir() {

        return self::get_plugin_dir() . 'includes/';
    }

    public static function get_vendor_dir() {

        return self::get_plugin_dir() . 'vendor/';
    }

    private function setup_includes() {

        $path = self::get_include_dir() . '*.php';

        foreach ( glob( $path ) as $file ) {

            include( $file );
        }
    }

    private function setup_autoload() {

        $autoloader = self::get_vendor_dir() . 'autoload.php';

        if( file_exists( $autoloader ) ){

            require_once( $autoloader );
        }
    }

    private function setup_actions() {

        add_action( 'init', array( $this, 'on_init' ) );
    }
}

WPSyncSalesforce::instance();