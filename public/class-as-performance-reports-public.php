<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.facebook.com/anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/public
 * @author     Anurag Singh <anuragsinghce@outlook.com>
 */
class As_Performance_Reports_Public {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
	//    public function __construct( $plugin_name, $version ) {
	//
	//        $this->plugin_name = $plugin_name;
	//        $this->version = $version;
	//
	//    }
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in As_Performance_Reports_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The As_Performance_Reports_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( 'dataTables', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.css', array(), $this->version, 'all' );
    }
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in As_Performance_Reports_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The As_Performance_Reports_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script( 'dataTables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'as-performance-reports-public', plugin_dir_url( __FILE__ ) . 'js/as-performance-reports-public.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'as-scrollto', plugin_dir_url( __FILE__ ) . 'js/jquery-scrollto.js', array( 'jquery' ), $this->version, false );
        //wp_enqueue_script( 'ajax-script', plugins_url( '/js/my_query.js', __FILE__ ), array('jquery') );
		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		// wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
	
    }
    // Same handler function...
	function my_action_callback() {
		global $wpdb;
		$queryAllCalls = 	"SELECT *
		                    FROM    wp_performance_report
		                    WHERE   stockCat = 'Trading'
		                    ORDER BY lastUpdate ASC
		                    ";
		$queryAllCalls = $wpdb->get_results($queryAllCalls, ARRAY_A);
		// $whatever = intval( $_POST['whatever'] );
		// $whatever += 10;
	        //echo $whatever[0][0];
	         print_r ($queryAllCalls);
		wp_die();
	}
    
    /**
     * Override wordpress default template
     *
     * @since    1.0.0
     */
    function override_templates_for_cpt_gallery( $template ){
        // Check if its a plugin created page
        if ( is_page('performance-report') ) {
            $template = plugin_dir_path( __FILE__ ) .'/partials/page-performance-report.php';
        }
        if( is_page('equity-trading-calls')) {
            $template = plugin_dir_path(__FILE__) . '/partials/page-performance-tracker-reports.php';
        }
        return $template;
    }	  

    public function display_table_data() {
    		$reportFrontEnd = new Last_Month_Report();
			
			$allCallTypes = $reportFrontEnd->display_data_in_tabular_format($reportFrontEnd);
			// $reportFrontEnd = new Last_Month_Report();
			// //print_r($$allcalltype);
			// $allCallTypes = $reportFrontEnd->display_data_in_tabular_format($reportFrontEnd);
			// echo '<pre>';
			// print_r($allCallTypes);
			// echo '</pre>';
			// Get the sum of each column seperatly
			//$overallAllCallTypes = $reportFrontEnd->get_list_of_all_call_types();
			// echo $single.'<pre>';
			// print_r($overallAllCallTypes);
			// echo '</pre>';
			?>

			<!-- ********************************* -->
			<script type="text/javascript">
				$(document).ready(function(){
					//$('#_test').next('span').remove();
				})
			</script>
			<!-- <div>
					<p id="_test">demo</p>
					<span>test</span>
			
				</div> -->
			<div class="last-month-report">
				<h2>Our Last 1 month Performance Score Card</h2>
			    <div class="tbl-ovr-flo">
			        <table bgcolor="#f1f2f2" border="1" bordercolor="#fff" cellpadding="7" cellspacing="0" width="100%">
			            <thead>
			                <tr>
			                    <td> </td>
			                    <td>Calls Given</td>
			                    <td>Hits</td>
			                    <td>Misses</td>
			                    <td>Pending Status</td>
			                    <td>Success %</td>
			                    <td>ROI % on Investment Period</td>
			                    <td>Annualised ROI %</td>
			                </tr>
			                <tr>
			                </tr>
			            </thead>
			            <tbody>
						<?php
						
							foreach ($allCallTypes as $singleCallTypeArray) {
								// echo '<pre>';
								// print_r($singleCallTypeArray);
								// echo '</pre>';
								$singleCallType = $singleCallTypeArray['summarizedDataOfSingleCallTypes'];
							?>
							<tr style="background-color: rgb(252, 252, 252);">
								<td><?php echo $singleCallType['category']; ?></td>
						        <td><?php echo $singleCallType['callsGiven']; ?></td>
						        <td><?php echo $singleCallType['totalHits']; ?></td>
						        <td><?php echo $singleCallType['totalMisses']; ?></td>
						        <td><?php echo $singleCallType['totalPendings']; ?></td>
						        <td><?php echo $singleCallType['success']; ?></td>
						        <td><?php echo $singleCallType['roiOnInvestment']; ?></td>
						        <td><?php echo $singleCallType['annualisedROI']; ?></td>
						    </tr>
						<?php 
						
						}
						?>
						</tbody>
					</table>
					<hr><hr><hr><hr>
				</div>
			</div>
			<!-- ********************************* -->

			<!-- ********************************* -->   
			<div class="full-report" style="display:none">
				<h2>Performance Tracker - Equity Trading Calls</h2>                 
			    <div class="tbl-ovr-flo">
					<table width="100%" cellspacing="0" cellpadding="7" bordercolor="#fff" border="1" bgcolor="#f1f2f2">
						<thead>
							<tr>
							  <td>Calls Given</td>
							  <td>Hits</td>
							  <td>Misses</td>
							  <td>Pending Status</td>
							  <td>Success %</td>
							  <td>ROI % on Investment Period</td>
							  <td>Annualised ROI %</td>
							</tr>
						</thead>
						<tbody>				
							<tr style="background-color: rgb(252, 252, 252);">
								<td class="callsGiven"></td>
								<td class="totalHits"></td>
								<td class="totalMisses"></td>
								<td class="totalPendings"></td>
								<td class="successPercentage"></td>
								<td class="roiOnInvestment"></td>
								<td class="annualisedROI"></td>
							</tr>
							<tr></tr>
							<tr>
								<td colspan="7"></td> 
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="tbl-ovr-flo _moveHere">
					<table id="all-single-type-calls-grid"  width="100%" cellspacing="0" cellpadding="7" bordercolor="#fff" border="1" bgcolor="#f1f2f2">
							<thead>
								<tr>
									<th>Stocks</th>
					                <th id="options-pre">Date</th>
					                
					                <th>Buy/Sell</th>
					                <th>Entry Price</th>
					                <th>Target Price</th>
					                <th>Stop Loss</th>
					                <th>Exit Price</th>
					                <th>P/L Per Unit</th>
					                <th>P/L Per Lac</th>
					                <th>Gross ROI%</th>
					                <th>Final Result</th>
								</tr>
							</thead>
					</table>
				</div>
			</div>
			<!-- ********************************* -->

	<?php	
	
    }      
		
}


/**
 * holds all function which are responsible to generate last month reports
 *
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/public
 */
class Last_Month_Report extends Report_Formula {
	var $category;
	var $totatROI;
	var $netProfitLoss;
	var $totalInvestment;
	var $totalAverageTimePeriod;
	var $totalHits;
	var $totalMisses;
	var $totalPendings;
	var $singleCallType;
	var $callCatArray;		// Define all research calls in array
	var $totalTimePeriod;
	var $netProfitLossTillYet;
	var $perCallInvestment;
	var $perCallProfitLoss;
	var $perCallROIonInvestment;
	var $timePeriod;
	var $noOfUnits;
	var $plPerUnit;
	var $plPerLac;
	var $grossROI;
	var $finalResult;

	function __construct() {
		$this->get_unique_call_type();	
	}
	
    
	private function get_unique_call_type() {
		global $wpdb;
		$selectAllCategories = "SELECT DISTINCT stockCat
								FROM	wp_performance_report
								";
		$allUniqueCats = $wpdb->get_results($selectAllCategories, OBJECT);
		foreach ($allUniqueCats as $singleCat) {
			$allCats[] = $singleCat->stockCat;
		}
		$this->callCatArray = $allCats;
	}
		
    public function get_all_calls() {
    	global $wpdb;
        $today = date('Y-m-d');
        // $startDate = date('Y-m-d',strtotime($today.'-1 day'));
        $startDate = date('Y-m-d',strtotime($today));
        $endDate = date('Y-m-d',strtotime($startDate.'-30 day'));
        $queryAllCalls = "SELECT *
                    FROM    wp_performance_report
                    WHERE   DATE(`lastUpdate`) BETWEEN '".$endDate."' AND '".$startDate."'
                    ";
        return $wpdb->get_results($queryAllCalls, ARRAY_A);
    } 
    public function detail_about_calls($category) {
    	$totalCallsgivenTillYet = 0;
    	$allCalls = $this->get_all_calls();
    	// echo '<pre>';
    	// print_r($allCalls);
    	// echo '</pre>';
    	foreach ($allCalls as $singleCall) {
    		$stockCat 	= $singleCall['stockCat'];
    		$entryPrice = $singleCall['entryPrice'];
    		$exitPrice 	= $singleCall['exitPrice'];
    		$entryDate 	= $singleCall['entryDate'];
    		$exitDate 	= $singleCall['exitDate'];
    		$action 	= $singleCall['action'];
    		// For total calls of category given
    		if($stockCat == $category) {
    			$stockCat = $this->category;
    			$totalCallsgivenTillYet ++;
				//  For time period of each call
				$timePeriod = $this->timePeriod($entryDate, $exitDate);
				// No of Units
				$noOfUnits = $this->noOfUnits($entryPrice);
				// profit or loss Per Unit
				$plPerUnit = $this->plPerUnit($action, $entryPrice, $exitPrice);
				// profit or loss Per Lac
				$plPerLac = $this->plPerLac($plPerUnit, $noOfUnits);
				// Gross ROI
				$grossROI = $this->grossROI($plPerLac, $noOfUnits, $entryPrice);
				// Final result
				$finalResult = $this->finalResult($grossROI);
				// Sum all time periods
				$this->totalTimePeriod += $timePeriod;
				// Calculate ROI on investment with total netprofitloss and totalInvestment
				$roiOnInvestment = $this->roiOnInvestment($this->netProfitLossTillYet, $this->totalInvestmentTillYet);
				$totalAverageTimePeriod =  $this->totalAverageTimePeriod($this->totalTimePeriod, $totalCallsgivenTillYet);
				$totalInvestmentTillYet = $this->totalInvestmentTillYet($entryPrice, $noOfUnits);
				
				$this->netProfitLossTillYet = $this->netProfitLossTillYet($plPerLac);
				$annualisedROI = $this->annualisedROI($this->netProfitLossTillYet, $totalInvestmentTillYet, $totalAverageTimePeriod);
				
				if($finalResult == 'HIT') {
					 $this->totalHits++;
				}
				elseif ($finalResult == 'MISS') {
					$this->totalMisses++;
				}
				else{
					if($this->totalPendings <= 0){
						return 0;
					}
					$this->totalPendings++;
				}
				$success = $this->successPercentage($totalCallsgivenTillYet, $this->totalHits, $this->totalPendings);

    		}
			$summarizedAllCalltypes = 	array(
			                        'totalCalls' 			=>	$totalCallsgivenTillYet,
									'action'				=> 	$action,
									'entryPrice'			=> 	$entryPrice,
									'exitPrice'				=> 	$exitPrice,
		    						'timePeriod' 			=>	$this->timePeriod,
		    						'noOfUnits' 			=>	$this->noOfUnits,
		    						'plPerUnit' 			=>	$this->plPerUnit,
		    						'plPerLac' 				=>	$this->plPerLac,
		    						'grossROI' 				=>	$this->grossROI . ' %',
		    						'finalResult' 			=>	$this->finalResult . ' %',
		    						'perCallInvestment' 	=>	$this->perCallInvestment,
		    						'perCallProfitLoss' 	=>	$this->perCallProfitLoss,
		    						'perCallROIonInvestment'=>	$this->perCallROIonInvestment,
		    						// 'totalInvestment' 		=>	$totalInvestment,
		    						// 'netProfitLoss' 		=>	$netProfitLoss,
		    						// 'roiOnInvestment'		=>	$roiOnInvestment,
		    						// 'totalAverageTimePeriod'=>	$totalAverageTimePeriod,
		    						// 'annualisedROI'			=> $annualisedROI,
		    						// 'success'				=> 	$success,
		    						// 'totalHits'				=>	$totalHits,
		    						// 'totalMisses'			=>	$totalMisses,
		    						// 'totalPendings'			=> 	$totalPendings
		    					);
    	}
    	$summarizedDataOfSingleCallTypes	= 	array(
    								'category'				=> 	$category,
    								'callsGiven' 			=>	$totalCallsgivenTillYet,
    								'totalHits'				=>	$this->totalHits,
		    						'totalMisses'			=>	$this->totalMisses,
		    						'totalPendings'			=> 	$this->totalPendings,
    								'totalInvestment' 		=>	$this->totalInvestment,
		    						'netProfitLoss' 		=>	$this->netProfitLoss,
		    						'roiOnInvestment'		=>	$roiOnInvestment,
		    						'totalAverageTimePeriod'=>	$totalAverageTimePeriod,
		    						'annualisedROI'			=>  $annualisedROI,
		    						'success'				=> 	$success,
		    						
    							);
    	
    	$data = array(
    			'summarizedAllCalltypes' 		=> 	end($summarizedAllCalltypes), 
    			'summarizedDataOfSingleCallTypes' 	=> 	$summarizedDataOfSingleCallTypes
    			);
    	return $data;
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
    }
    public function display_data_in_tabular_format($reportFrontEnd) {
    	
    	$callCatArray = $this->callCatArray;
		
		foreach ($callCatArray as $single ) {
			$singleCallType[] = $reportFrontEnd->detail_about_calls($single);
		}
		$this->singleCallType = $singleCallType;
		return $singleCallType;
		// echo '<pre>';
		// print_r($singleCallType);
		// echo '</pre>';	
    }
    
}
