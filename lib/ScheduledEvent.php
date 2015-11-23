<?php

namespace WPSyncSalesforce;

class ScheduledEvent {

	private $timestamp;
	private $recurrance;
	private $event_name;
	private $event_callback;

	public function __construct( $timestamp, $recurrance, $event_name ) {

		$this->set_timestamp( $timestamp );
		$this->set_recurrance( $recurrance );
		$this->set_event_name( $event_name );

		$this->setup_actions();
	}

	public function get_timestamp() {

		return $this->timestamp;
	}

	public function set_timestamp( $timestamp ) {

		$this->timestamp = $timestamp;
	}

	public function get_recurrance() {

		return $this->recurrance;
	}

	public function set_recurrance( $recurrance ) {

		$this->recurrance = $recurrance;
	}

	public function get_event_name() {

		return $this->event_name;
	}

	public function set_event_name( $event_name ) {

		$this->event_name = $event_name;
	}

	public function get_event_callback() {

		return $this->event_callback;
	}

	public function set_event_callback( $event_callback ) {

		$this->event_callback = $event_callback;
	}

	public function do_event( $event_callback ) {

		if ( is_callable( $event_callback ) ) {

			$this->set_event_callback( $event_callback );
		}
	}

	public function on_do_event() {

		$event_callback = $this->get_event_callback();

		if ( $event_callback ) {

			call_user_func_array( $event_callback, array() );
		}
	}

	public function register() {

		$timestamp = $this->get_timestamp();
		$recurrance = $this->get_recurrance();
		$event_name = $this->get_event_name();

		wp_schedule_event( $timestamp, $recurrance, $event_name );
	}

	private function setup_actions() {

		$event_name = $this->get_event_name();

		add_action( $event_name, array( $this, 'on_do_event' ) );
	}
}