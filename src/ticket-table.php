<?php
namespace calisia_ticket_system;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once CALISIA_TICKET_SYSTEM_ROOT . '/src/wp/class-wp-list-table.php';

class Ticket_List extends WP_List_Table {

    /** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'ticket', 'calisia-ticket-system' ), //singular name of the listed records
			'plural'   => __( 'tickets', 'calisia-ticket-system' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	private static function where_clause(){
		$gets = array('kind', 'status', 'user_id', 'order_id');
		$where = ' WHERE deleted=0 AND';
		$params = array();

		foreach($_GET as $key => $value){
			if(in_array($key, $gets)){
				if(is_numeric($value)){
					$where .= " $key=%d AND";
					$params[] = $value;
				}else{
					if($value != 'all'){
						$where .= " $key=%s AND";
						$params[] = $value;
					}	
				}
				
			}
		}

		return array('sql'=>rtrim($where, 'AND'), 'params'=>$params);
	}

	/**
	 * Retrieve tickets data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_tickets( $per_page = 25, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}calisia_ticket_system_ticket";

		$where_clause = self::where_clause();
		$sql .= $where_clause['sql'];
		$params = $where_clause['params'];

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if(in_array($_REQUEST['orderby'],array('title','added','last_support_reply','last_customer_reply'))){
				$sql .= ' ORDER BY ';
				$sql .= esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}else{
				$sql .= ' ORDER BY added DESC';
			}
		}else{
			//default order by
			$sql .= ' ORDER BY added DESC';
		}

		$sql .= " LIMIT %d";
		$params[] = $per_page;
		$sql .= ' OFFSET %d';
		$params[] = ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( 
			$wpdb->prepare(
				$sql,
				$params
			), 
			'ARRAY_A' 
		);

		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_ticket( $id ) {
		//global $wpdb;
		//return $wpdb->update( $wpdb->prefix."calisia_ticket", array( 'deleted' => 1 ), array( 'id' => $id ), array( '%d' ), array( '%d' ));

		$ticket = new ticket($id);
		$ticket->delete();


		/*
		$wpdb->delete(
			"{$wpdb->prefix}calisia_ticket",
			[ 'id' => $id ],
			[ '%d' ]
		);*/
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) as ticket_count FROM {$wpdb->prefix}calisia_ticket_system_ticket";
		$where_clause = self::where_clause();
		if(empty($where_clause['params'])){
			return $wpdb->get_var( $sql . $where_clause['sql'] );
		}else{
			$result = $wpdb->get_results( 
				$wpdb->prepare(
					$sql . $where_clause['sql'],
					$where_clause['params']
				)
			);
			return $result[0]->ticket_count;
		}
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No tickets avaliable.', 'calisia-ticket-system' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'title':
				$link_url = menu_page_url( 'calisia-tickets', false ).'&id='.$item['id'];
				$number_of_unread = data::get_number_of_unread_messages($item['id']);
				$link_name = $item[ $column_name ];
				if($number_of_unread)
					$link_name = '<strong>' . $link_name . ' ('.$number_of_unread.')</strong>';
                return "<a href='$link_url'>$link_name</a>";
                break;
			case 'user_id':
				return  '<a href="' . menu_page_url( 'calisia-tickets', false ) . '&user_id=' . $item[ $column_name ] . '">' .get_user_by( 'ID', $item[ $column_name ] )->user_email . '</a>';
				break;
			case 'status': return translations::ticket_status($item[ $column_name ]); break;
			case 'kind': return translations::ticket_kind($item[ $column_name ]); break;
			case 'added':
			case 'last_support_reply':
			case 'last_customer_reply':
				return $item[ $column_name ];
			default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_ticket' );

		$title = '<strong>' . $item['title'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'title'    => __( 'Title', 'calisia-ticket-system' ),
			'user_id' => __( 'E-mail', 'calisia-ticket-system' ),
			'status' => __( 'Status', 'calisia-ticket-system' ),
			'kind' => __( 'Kind', 'calisia-ticket-system' ),
			'added'    => __( 'Added', 'calisia-ticket-system' ),
			'last_support_reply'    => __( 'Last Support Reply', 'calisia-ticket-system' ),
			'last_customer_reply'    => __( 'Last Customer Reply', 'calisia-ticket-system' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'title' => array( 'title', true ),
			'added' => array( 'added', false ),
			'last_support_reply' => array( 'last_support_reply', false ),
			'last_customer_reply' => array( 'last_customer_reply', false )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		//$this->_column_headers = $this->get_column_info();
        $this->_column_headers = [
            $this->get_columns(),
            [], // hidden columns
            $this->get_sortable_columns(),
            $this->get_primary_column_name(),
        ];

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'tickets_per_page', 25 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_tickets( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_ticket' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_ticket( absint( $_GET['customer'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_ticket( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

	//function display_tablenav($which){}
}