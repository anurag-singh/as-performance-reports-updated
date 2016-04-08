<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.facebook.com/anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/includes
 * @author     Anurag Singh <anuragsinghce@outlook.com>
 */
class As_Performance_Reports_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;
        $table = $wpdb->prefix . "performance_report";
        $charset_collate = $wpdb->get_charset_collate();
        $createTable = "CREATE TABLE IF NOT EXISTS $table (
                ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                stockParentCat VARCHAR(15) NOT NULL,
                stockCat VARCHAR(25) NOT NULL,
                stockID VARCHAR(25) NOT NULL,
                stockName VARCHAR(50) NOT NULL,
                action VARCHAR(12) NOT NULL,
                futurePrice VARCHAR(12),
                strikePrice VARCHAR(12), 
                entryDate DATE NOT NULL,
                exitDate DATE NOT NULL,
                entryPrice VARCHAR(12) NOT NULL,
                exitPrice VARCHAR(12),
                targetPrice VARCHAR(12) NOT NULL,
                stopLoss VARCHAR(12) NOT NULL,
                lotSize VARCHAR(12),
                marginPercentage VARCHAR(12),
                lastUpdate TIMESTAMP
            )$charset_collate;";

        // Check if database is created
        if($wpdb->query($createTable) === FALSE)
        {
            echo $wpdb->print_error();
        }


        $newPage = new New_Page();
        $newPage->insert_new_page();

        $newMenu = new New_Menu();
        $newMenu->register_menu();

    }

    private function create_db_table() 
    {
        
    }

}
