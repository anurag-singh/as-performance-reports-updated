<?php

/**
 * holds all function which are responsible to generate reports based on single research call
 *
 * @link       https://www.facebook.com/anuragsingleCallgh.me
 * @singleCallce      1.0.0
 *
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/public
 */

class Single_Call_Type extends Report_Formulas {
	var $allCalls;
	
	/*	get all calls of perticular 'call type'
	* 	from database 
	*/
    private function get_all_calls_from_specific_category($callType) {
    	global $wpdb;
       
        $queryAllCalls = "SELECT *
                    FROM    wp_performance_report
                    WHERE   stockCat = '".$callType."'
                    ORDER BY lastUpdate ASC
                    ";

        return $wpdb->get_results($queryAllCalls, ARRAY_A);
    }


    public function display_data_in_table($callType) {
    	$allCalls = $this->get_all_calls_from_specific_category($callType);

    	return $this->prepare_data_to_display($allCalls);		
    }

    private function singleCalls($singleCall) {

		$singleCalls = 	array(
								'stockName'			=>	$singleCall['stockName'],
								'entryDate'			=>	$singleCall['entryDate'],
								'action'			=>	$singleCall['action'],
								'entryPrice'		=>	$singleCall['entryPrice'],
								'targetPrice'		=>	$singleCall['targetPrice'],
								'stopLoss'			=>	$singleCall['stopLoss'],
								'exitPrice'			=>	$singleCall['exitPrice'],
								'plPerunit'			=>	$singleCall['plPerunit'],
								'plPerLac'			=>	$singleCall['plPerLac'],
								'grossROI'			=>	$singleCall['grossROI'],
								'finalResultIcon'	=>	$singleCall['finalResultIcon'],
							);

			return $singleCalls;					

			//$this->display_all_calls_in_tabular_format($singleCall);
			//$this->summarisedSnapshot($summarisedSnapshot);
	}
    

	private function prepare_data_to_display($allCalls) {	
		$totalCallsgivenTillYet = 0;

		foreach ($allCalls as $singleCall) {
			$totalCallsgivenTillYet ++;


			$totalCallsgivenTillYet;
			$action 	= $singleCall['action'];
			$entryPrice = $singleCall['entryPrice'];
			$exitPrice 	= $singleCall['exitPrice'];
			$entryDate		= $singleCall['entryDate'];
			$exitDate		= $singleCall['exitDate'];
			$timePeriod 	= $this->timePeriod($entryDate, $exitDate);
			$noOfUnits 		= $this->noOfUnits($entryPrice);
			$plPerUnit 		= $this->plPerUnit($action, $entryPrice, $exitPrice);
			$plPerLac 		= $this->plPerLac($plPerUnit, $noOfUnits);
			$grossROI 		= $this->grossROI($plPerLac, $noOfUnits, $entryPrice);
			$finalResult 	= $this->finalResult($grossROI);
			$finalResultIcon= $this->finalResultIcon($grossROI);
			$totalInvestmentTillYet = $this->totalInvestmentTillYet($entryPrice, $noOfUnits);
			$netProfitLossTillYet = $this->netProfitLossTillYet($plPerLac);
			// Sum all time periods
			$totalTimePeriod += $timePeriod;
			
			// Prepare a array to store the additional columns
			$extraColumns = array(
						'totalCallsgivenTillYet' => $totalCallsgivenTillYet,
						'timePeriod'	=> $timePeriod,
						'totalTimePeriod'=> $totalTimePeriod,
						'plPerunit' 	=>	$plPerUnit,
						'plPerLac'		=> 	$plPerLac,
						'grossROI'		=>	$grossROI,
						'finalResult'	=>	$finalResult,	
						'finalResultIcon'	=>	$finalResultIcon,	
						'totalInvestmentTillYet'=> $totalInvestmentTillYet,
						'netProfitLossTillYet'	=> $netProfitLossTillYet,
					);

			// Mergre arrays and make a single one
			$singleCall = array_merge($singleCall, $extraColumns);

				if($finalResult == 'HIT') {
					 $totalHits++;
				}
				elseif ($finalResult == 'MISS') {
					$totalMisses++;
				}
				else{
					if($totalPendings<=0){
						return $totalPendings = 0;
					}
					$totalPendings++;
				}

			$singleCalls[] = $this->singleCalls($singleCall);


			$perCallInvestment = $this->perCallInvestment($entryPrice, $noOfUnits);	// each call investment
			$totalPerCallInvestment += $perCallInvestment;	// Sum investment of each call and return the total
		} 


		// Calculate the success and convert it into Percentage
		$successPercentage = $this->successPercentage($totalCallsgivenTillYet, $totalHits, $totalPendings);
		// Calculate ROI on investment with total netprofitloss and totalInvestment
		$roiOnInvestment = $this->roiOnInvestment($netProfitLossTillYet, $totalInvestmentTillYet);


		$totalAverageTimePeriod =  $this->totalAverageTimePeriod($totalTimePeriod, $totalCallsgivenTillYet);


		$annualisedROI = $this->annualisedROI($netProfitLossTillYet, $totalInvestmentTillYet, $totalAverageTimePeriod);
		

		$summarisedSnapshot =	array(
							'callsGiven'		=>	$totalCallsgivenTillYet,
							'totalHits' 		=>	$totalHits,
							'totalMisses' 		=>	$totalMisses,
							'totalPendings' 	=>	$totalPendings,
							'successPercentage' =>	$successPercentage,
							'roiOnInvestment' 	=>	$roiOnInvestment,
							'annualisedROI' 	=>	$annualisedROI
						);	

		
		

		$singleCallTypeData = 	array(
									'singleCalls' 			=>		$singleCalls,
									'summarisedSnapshot' 	=>		$summarisedSnapshot
								); 

		$singleCalls = $singleCallTypeData['singleCalls'];
		$summarisedSnapshot = $singleCallTypeData['summarisedSnapshot'];

		$this->display_summarisedSnapshot($summarisedSnapshot);	
		//$this->display_all_calls_in_tabular_format($singleCalls);		
	}

	private function display_all_calls_in_tabular_format($singleCalls) {
		?>
		<h2>Hit & Miss Report</h2>
		<div class="tbl-ovr-flo">
		    <table width="100%" cellspacing="0" cellpadding="7" bordercolor="#fff" border="1" bgcolor="#f1f2f2">
		        <thead>
		            <tr>
		                <td>Stocks</td>
		                <td>Date</td>
		                <td>Buy/Sell</td>
		                <td>Entry Price</td>
		                <td>Target Price</td>
		                <td>Stop Loss</td>
		                <td>Exit Price</td>
		                <td>P/L Per Unit</td>
		                <td>P/L Per Lac</td>
		                <td>Gross ROI%</td>
		                <td>Final Result</td>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php 
		        	   
		        	for($i=0; $i<=count($singleCalls); $i++){
						echo '<tr>';
					    echo '<td>' .$singleCalls[$i]['stockName']. '</td>';
					    echo '<td>' .$singleCalls[$i]['entryDate']. '</td>';
					    echo '<td>' .$singleCalls[$i]['action']. '</td>';
					    echo '<td>' .$singleCalls[$i]['entryPrice']. '</td>';
					    echo '<td>' .$singleCalls[$i]['targetPrice']. '</td>';
					    echo '<td>' .$singleCalls[$i]['stopLoss']. '</td>';
					    echo '<td>' .$singleCalls[$i]['exitPrice']. '</td>';
					    echo '<td>' .$singleCalls[$i]['plPerunit']. '</td>';
					    echo '<td>' .$singleCalls[$i]['plPerLac']. '</td>';
					    echo '<td>' .$singleCalls[$i]['grossROI']. '</td>';
					    echo '<td>' .$singleCalls[$i]['finalResultIcon']. '</td>';
					    echo '</tr>';
					}
		        	?>
		            <tr>
		                <td>
		                    <font color="#ee4326" class="font-icn">&#xf087;</font> Hit </td>
		                <td>
		                    <font color="#ee4326" class="font-icn">&#xf088;</font> Miss </td>
		                <td colspan="2">
		                    <font color="#ee4326" class="font-icn">&#xf256;</font> Pending status </td>
		                <td colspan="7"> </td>
		            </tr>
		        </tbody>
		    </table>
		</div>
	<?php }

	private function display_summarisedSnapshot($summarisedSnapshot) {
		?>
		
		<h2>Summarised Snapshot</h2>
		<div class="tbl-ovr-flo">
			<table bgcolor="#f1f2f2" border="1" bordercolor="#fff" cellpadding="7" cellspacing="0" width="100%">

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
					<?php 
					echo '<tr style="background-color: rgb(252, 252, 252);">';
					echo '<td>' .$summarisedSnapshot['callsGiven']. '</td>';
					echo '<td>' .$summarisedSnapshot['totalHits']. '</td>';
					echo '<td>' .$summarisedSnapshot['totalMisses']. '</td>';
					echo '<td>' .$summarisedSnapshot['totalPendings']. '</td>';
					echo '<td>' .$summarisedSnapshot['successPercentage']. '</td>';
					echo '<td>' .$summarisedSnapshot['roiOnInvestment']. ' </td>';
					echo '<td>' .$summarisedSnapshot['annualisedROI']. ' </td>';
					echo '<tr>';
					?>
					<tr> <td colspan="7"></td> </tr>
				</tbody>
			</table>
		</div>
	<?php
	}


	private function finalResultIcon($grossROI) {
		if($grossROI<=0){
			return '<button class="icn-btn"><span class="font-icn">&#xf088;</span></button>';
		}
		elseif ($grossROI>0){
			return '<button class="icn-btn"><span class="font-icn">&#xf087;</span></button>';
		}
		else {
			return '<button class="icn-btn"><span class="font-icn">&#xf256;</span></button>';
		}
	}

	public function summarised_snapshot($callType) {
		$allCalls = $this->get_all_calls_from_specific_category($callType);

		$summarisedSnapshot['callGiven'] = count($allCalls);
		//var_dump($summarisedSnapshot);

		return $summarisedSnapshot;
	}	
}