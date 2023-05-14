<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class that outputs the "visits" HTML table from the affiliate account.
 * 
 */
class SliceWP_List_Table_Affiliate_Account_Visits extends SliceWP_List_Table {

    /**
     * A string identifying the table.
     * 
     * @access protected
	 * @var    string
     * 
     */
    protected $id = 'affiliate_account_visits';


    /**
     * Constructor.
     * 
     */
    public function __construct( $args = array() ) {

        parent::__construct( $args );

        $this->table_columns = array(
            'id'           => __( 'ID', 'slicewp' ),
            'date'         => __( 'Date', 'slicewp' ),
            'landing_url'  => __( 'Landing URL', 'slicewp' ),
            'referrer_url' => __( 'Referrer URL', 'slicewp' ),
        );

        $this->no_items = ( empty( $_GET['list-table-filter-date-start'] ) ? __( 'You have no visits.', 'slicewp' ) : '' );

        $this->set_table_items_data();

    }


    /**
     * Sets the visits data.
     * 
     */
    protected function set_table_items_data() {

        $affiliate_id = slicewp_get_current_affiliate_id();

        // Prepare the visits args.
        $visit_args = array(
            'number'		=> $this->items_per_page,
            'offset'		=> ( $this->current_page - 1 ) * $this->items_per_page,
            'affiliate_id'	=> $affiliate_id,
            'date_min'      => ( ! empty( $_GET['list-table-filter-date-start'] ) ? get_gmt_from_date( $_GET['list-table-filter-date-start'] ) : '' ),
            'date_max'      => ( ! empty( $_GET['list-table-filter-date-end'] ) ? get_gmt_from_date( $_GET['list-table-filter-date-end'] ) : '' )
        );

        $this->items_total = slicewp_get_visits( $visit_args, true );
        $this->items 	   = slicewp_get_visits( $visit_args );

    }


    /**
     * Column "date".
     * 
     * @param array $item
     * 
     * @return string
     * 
     */
    public function column_date( $item ) {

        return slicewp_date_i18n( $item['date_created'] );

    }

}