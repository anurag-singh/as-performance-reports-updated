<?php

/**
 * holds all function which are responsible to generate last month reports
 *
 * @link       https://www.facebook.com/anuragsingleCallgh.me
 * @singleCallce      1.0.0
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

// 	public function get_list_of_all_call_types() {
// 		return $this->callCatArray;
// 	}


// 	public function display_all_call_type(){
// echo '<pre>';
// print_r($this->callCatArray);
// echo '</pre>';
// 	}
		

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
									'action'				=> 	$action,
									'entryPrice'			=> 	$entryPrice,
									'exitPrice'				=> 	$exitPrice,
		    						'totalCalls' 			=>	$totalCallsgivenTillYet,
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

    	$singleCallTypes	= 	array(
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
    			'summarizedAllCalltypes' 		=> 	$summarizedAllCalltypes, 
    			'singleCallTypes' 	=> 	$singleCallTypes
    			);

    	return $data;

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';


    }

    public function display_data_in_tabular_format($reportFrontEnd) {
    	
    	$callCatArray = $this->callCatArray;
		
		foreach ($callCatArray as $single ) {
			$singleCallType[] = end($reportFrontEnd->detail_about_calls($single));
		}

		$this->singleCallType = $singleCallType;

		return $singleCallType;

		// echo '<pre>';
		// print_r($singleCallType);
		// echo '</pre>';	
    }

    public function display_overall_call_type_dataaaaaaa() {
    	foreach ($this->singleCallType as $singleCall ) {
			
			$OverallAllProducts['callsGiven'] += $singleCall['callsGiven'];
			$OverallAllProducts['totalHits']	+=	$singleCall['totalHits'];
			$OverallAllProducts['totalMisses'] += $singleCall['totalMisses'];
			$OverallAllProducts['totalPendings']	+=	$singleCall['totalPendings'];
			$OverallAllProducts['success'] += $singleCall['success'];
			$OverallAllProducts['roiOnInvestment']	+=	$singleCall['roiOnInvestment'];
			$OverallAllProducts['annualisedROI']	+=	$singleCall['annualisedROI'];	

		}
			

		// Get the total no of calls from the array
		$totalCallCats = count($this->callCatArray);

		// Get the all the success value and divied them to get average of success
		$OverallAllProducts['totalSuccessPercent'] = $OverallAllProducts['success']/$totalCallCats  . ' %';;

		// Get the all the roiOnInvestment value and divied them to get the average of roiOnInvestment
		$OverallAllProducts['totalRoiOnInvestmentPercent'] = $OverallAllProducts['roiOnInvestment']/$totalCallCats . ' %';

		// Get the all the annualisedROI value and divied them to get the average of annualisedROI
		$OverallAllProducts['totalAnnualisedROIPercent'] = $OverallAllProducts['annualisedROI']/$totalCallCats  . ' %';;

		return $OverallAllProducts;
    }

    






}


