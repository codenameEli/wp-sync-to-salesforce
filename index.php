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

use WPSyncSalesforce\ScheduledEvent;

class WPSyncSalesforce {

    private static $instance = null;

    private $scheduled_event;

    private function __construct() {


        $this->setup_autoload();

        $this->setup_actions();
        $this->create_connection();

        $this->scheduled_event = new ScheduledEvent( time(), 'hourly', 'wp_sync_to_salesforce' );

        $this->scheduled_event->do_event( function() {

            $this->create_connection();
        });
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

    public static function get_salesforce_toolkit_dir() {

        return plugin_dir_path( __FILE__ ) . 'vendor/developerforce/force.com-toolkit-for-php';
    }

    public static function get_vendor_dir() {

        return self::get_plugin_dir() . 'vendor/';
    }

    public function on_activate() {

        $this->scheduled_event->register();
    }

    public function create_connection() {

        require_once( self::get_plugin_dir() . 'misc/globalconstants.php' );
        require_once( self::get_plugin_dir() . 'soapclient/SforceEnterpriseClient.php');

        $sf_connection = new \SforceEnterpriseClient();

        $sf_connection->createConnection( self::get_plugin_dir() . 'soapclient/enterprise.wsdl.xml' );
        $sf_connection->login( $USERNAME, $PASSWORD.$TOKEN );

        // $query = "SELECT Id, FirstName, LastName, Phone from Contact";
        // $response = $sf_connection->query($query);

        // echo "Results of query '$query'<br/><br/>\n";
        // foreach ($response->records as $record) {
        //     echo $record->Id . ": " . $record->FirstName . " "
        //         . $record->LastName . " " . $record->Phone . "<br/>\n";
        // }
    }

    private function setup_autoload() {

        $autoloader = self::get_vendor_dir() . 'autoload.php';

        if( file_exists( $autoloader ) ){

            require_once( $autoloader );
        }
    }

    private function setup_actions() {

        // add_action( 'init', array( $this, 'on_init' ) );
        register_activation_hook( __FILE__, array( $this, 'on_activate' ) );
    }
}

WPSyncSalesforce::instance();