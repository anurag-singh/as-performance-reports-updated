<?php
/** Error reporting */
global $wpdb;
$wpdb->show_errors();
// For WordPress Multisite, you must define the DIEONDBERROR constant for database errors to display
define( 'DIEONDBERROR', true );
/** Error reporting */

date_default_timezone_set('Asia/Dili');

class Excel2Mysql extends Custom_Filter_For_Excel
{
    //public $conn;
    private $excelSheetDataArray;
    private $dbColumns = array('stockCat', 'stockID', 'stockName', 'action', 'futurePrice', 'strikePrice' ,'entryDate', 'entryPrice', 'targetPrice', 'stopLoss', 'exitDate', 'exitPrice', 'lotSize', 'marginPercentage');
    //private $postType = 'performance_report';
    private $table;

    function __construct() {

    }

    private function setParentCatInDb($stockCat){
    	if($stockCat == 'Equity Intraday' || $stockCat == "Equity Delivery" || $stockCat == "Equity Futures" || $stockCat == 'Equity Options' ) {
    		return 'Equity';
    	}
    	elseif($stockCat == 'Agri Commodities' || $stockCat == 'Non-Agri Commodities') {
    		return 'Commodities';
    	}
    	elseif ($stockCat == 'Currency') {
    		return 'Currency';
    	}
    	elseif ($stockCat == 'Contract') {
    		return 'Contract';
    	}
    	elseif ($stockCat == 'Options') {
    		return 'Options';
    	}
    	else {
    		die("Invalid Sub Category.");
    	}
    }

    public function fetch_records_from_excel($sheetname, $inputFileName)
    {
        $inputFileType = 'Excel2007';
        $dbColumns = $this->dbColumns;  // Get value in $dbColumns variable


        /**  Create an Instance of our Read Filter, passing in the cell range  **/
        $filterSubset = new Custom_Filter_For_Excel(2,250,range('A','N'));

        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setLoadSheetsOnly($sheetname);
        $objReader->setReadFilter($filterSubset);


        // if excel file is added then
        if (!empty($inputFileName))
        {
            $objPHPExcel = $objReader->load($inputFileName);
            $excelSheetDataArray = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //echo '<pre>';
            //print_r($excelSheetDataArray);
            //echo '</pre>';

             $i = 0;
            foreach ($excelSheetDataArray as $excelRow) {
                //print_r($excelRow);
               
                // echo '<pre>';
                // echo $excelSheetValues[0];
                // echo '</pre>';

                if(!empty ($excelRow['A'])) {
                	$excelSheetValues = array_values($excelRow);

					// echo '<pre>--- dbColumns ---';
					// print_r ($dbColumns);
					// echo '</pre>';

					// echo '<pre>--- excelSheetValues ---';
					// print_r ($excelSheetValues);
					// echo '</pre>';



					// Set parent category based on stockcat field
					$stockParentCat = $this->setParentCatInDb($excelSheetValues[0]);

					// Convert 'parent category' into assoc array
					$stockParentCat_assoc_A = array('stockParentCat' => $stockParentCat);

					$excelSheetValues = array_combine($dbColumns, $excelSheetValues);     

					// Add parent category value in the start
					$excelSheetValues = array_merge($stockParentCat_assoc_A, $excelSheetValues);
                 
					// echo '<pre>--- excelSheetValues with parent category ---';
					// print_r ($excelSheetValues);
					// echo '</pre>';

                    $excelRowSanitizedArray[$i] = $excelSheetValues;

                    $i++;
                }
            }
			// echo '<pre>--- excelRowSanitizedArray ---';
			// print_r ($excelRowSanitizedArray);
			// echo '</pre>';
            
            return $excelRowSanitizedArray;
        }
    }


    /*  Get all records form table  */
    public function fetch_records_from_db()
    {
        global $wpdb;
        $table = $wpdb->prefix . "performance_report";
        $dbColumns = $this->dbColumns;
        $columns = implode(', ', $dbColumns);
        $selectDbRecords = "SELECT $columns
                                FROM $table";

        $getAllRecordsFromDB = $wpdb->get_results($selectDbRecords);

        if ($getAllRecordsFromDB === FALSE)
        {
            echo $wpdb->print_error();
        }
        else
        {
            if(!empty($getAllRecordsFromDB)) {
                return $getAllRecordsFromDB;
            }
            // else
            // {
            //     echo "<br>Database is empty.";
            // }

        }

    }


    public function get_duplicate_records_from_db($sheetData)
    {
        global $wpdb;
        $newRecordsCouter = 0;
        $table = $wpdb->prefix . "performance_report";
        $dbColumns = $this->dbColumns;

        $colNames = implode(', ', $dbColumns);

        if(isset($sheetData)) {


            foreach ($sheetData as $excelrow) {


                //echo $excelrow['stockId'];
//                echo '<hr>Excel Records - <pre>';
//                print_r($excelrow);
//                echo '</pre>';


                $selectDuplicateRecordsFromDB = "SELECT $colNames FROM $table WHERE stockID = '".$excelrow['stockID']."'";

                $duplicateRowsFromDB = $wpdb->get_row($selectDuplicateRecordsFromDB, ARRAY_A);

                // If query not run successfully
                if($duplicateRowsFromDB === FALSE)
                {
                    echo $wpdb->print_error();
                }
                else
                {

                    if(!empty($duplicateRowsFromDB)) {



                        $dataToUpdateInDB = array_diff_assoc($excelrow, $duplicateRowsFromDB);

                       if(!empty ($dataToUpdateInDB)) {
                            echo '<hr><hr>Excel - <pre>';
                            print_r($excelrow);
                            echo '<pre>';

                            echo 'Duplicate DB Records - <pre>';
                            print_r($duplicateRowsFromDB);
                            echo '<pre>';

                            echo '<hr>diff_assoc array - <pre>';
                            print_r($dataToUpdateInDB);
                            echo '<pre>';

                           $column = $excelrow['stockID'];

                           $where = array('stockID' => $duplicateRowsFromDB['stockID']);

                           $update = $wpdb->update( $table, $dataToUpdateInDB, $where, $format = null, $where_format = null );
                       }
                    }
                    else
                    {

                        //$colVals = implode(' ,' , array_values($excelrow));



 $insertNewRecord = $wpdb->insert($table, $excelrow, array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )  );


                            if (isset($insertNewRecord))
                            {
                                $newRecordsCouter++;
                            }
                            else {
                                echo "No new records found.";
                                echo $wpdb->print_error();
                                //echo '<br><b style =color:#f00;>Error - </b>' . $conn->error;
                            }
                    }

                }

        }
        if($newRecordsCouter>0){
            echo '<b>'.$newRecordsCouter. ' new records inserted.</b>';
        }

        }
    }

}


?>
