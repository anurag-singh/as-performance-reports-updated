<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

    /**
     * Create a new table class that will extend the WP_List_Table
     * Ref - http://www.paulund.co.uk/wordpress-tables-using-wp_list_table
     */
    class Report_List_Table extends WP_List_Table
    {
        private $dbColumns = array('stockID', 'stockName', 'action', 'futurePrice', 'strikePrice', 'entryDate', 'entryPrice', 'targetPrice', 'stopLoss', 'exitDate', 'exitPrice', 'lotSize', 'marginPercentage');


        /**
         * Prepare the items for the table to process
         *
         * @return Void
         */
        public function prepare_items()
        {
            $columns = $this->get_columns();
            $hidden = $this->get_hidden_columns();
            $sortable = $this->get_sortable_columns();

            $data = $this->table_data();
            usort( $data, array( &$this, 'sort_data' ) );

            $perPage = 20;
            $currentPage = $this->get_pagenum();
            $totalItems = count($data);

            $this->set_pagination_args( array(
                'total_items' => $totalItems,
                'per_page'    => $perPage
            ) );

            $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $data;
        }

        /**
         * Override the parent columns method. Defines the columns to use in your listing table
         *
         * @return Array
         */
        public function get_columns()
        {
            $columns = array(
                'stockID'       => 'Stock ID',
                'stockName'     => 'Stock Name',
                'action'        => 'Action',
                'entryDate'     => 'Entry Date',
                'entryPrice'    => 'Entry Price',
                'targetPrice'   => 'Target Price',
                'stopLoss'      => 'Stop Loss',
                'exitDate'      => 'Exit Date',
                'exitPrice'     => 'Exit Price'
            );

            return $columns;
        }

        /**
         * Define which columns are hidden
         *
         * @return Array
         */
        public function get_hidden_columns()
        {
            return array();
        }

        /**
         * Define the sortable columns
         *
         * @return Array
         */
        public function get_sortable_columns()
        {
            return array('stockName' => array('stockName', false));
        }

        /**
         * Get the table data
         *
         * @return Array
         */
        private function table_data()
        {
            global $wpdb;
            $table = $wpdb->prefix . "performance_report";
            $dbColumns = $this->dbColumns;
            $columns = implode(', ', $dbColumns);
            $selectDbRecords = "SELECT $columns
                                    FROM $table";

            $data = $wpdb->get_results($selectDbRecords, ARRAY_A);

            return $data;
        }


        // Used to display the value of the id column
        public function column_id($item)
        {
            return $item['stockID'];
        }


        /**
         * Define what data to show on each column of the table
         *
         * @param  Array $item        Data
         * @param  String $column_name - Current column name
         *
         * @return Mixed
         */
        public function column_default( $item, $column_name )
        {
            switch( $column_name ) {
                case 'stockID':
                case 'stockName':
                case 'action':
                case 'entryDate':
                case 'entryPrice':
                case 'targetPrice':
                case 'stopLoss':
                case 'exitDate':
                case 'exitPrice':
                    return $item[ $column_name ];

                default:
                    return print_r( $item, true ) ;
            }
        }


        /**
         * Allows you to sort the data by the variables set in the $_GET
         *
         * @return Mixed
         */
        private function sort_data( $a, $b )
        {
            // Set defaults
            $orderby = 'stockName';
            $order = 'asc';

            // If orderby is set, use this as the sort column
            if(!empty($_GET['orderby']))
            {
                $orderby = $_GET['orderby'];
            }

            // If order is set use this as the order
            if(!empty($_GET['order']))
            {
                $order = $_GET['order'];
            }


            $result = strnatcmp( $a[$orderby], $b[$orderby] );

            if($order === 'asc')
            {
                return $result;
            }

            return -$result;
        }
    }
?>
