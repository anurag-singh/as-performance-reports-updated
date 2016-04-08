<?php
	
	class Report_Formula {
		var $sumOfPlPerLac;
		var $totalInvestmentTillYet;
		var $netProfitLossTillYet;

		// (Exit date - Enrty Date)
		protected function timePeriod($entryDate, $exitDate){
	    		$entryDate = strtotime($entryDate);
				$exitDate = strtotime($exitDate);

				$datediff = $exitDate - $entryDate;

	     		return (floor($datediff/(60*60*24)));
	    }

	    protected function noOfUnits($entryPrice) {
			return floor(100000/$entryPrice);
		}

		protected function plPerUnit($action, $entryPrice, $exitPrice) {
			if($action == 'BUY') {
				$plPerUnit = $exitPrice - $entryPrice;
			}
			else {
				$plPerUnit =  $entryPrice - $exitPrice;
			}

			$plPerUnit = number_format((float)$plPerUnit, 1, '.', '');

			return $plPerUnit;
		}

		protected function plPerLac($plPerUnit, $noOfUnits) {
			return $plPerUnit*$noOfUnits;
		}

		protected function grossROI($plPerLac, $noOfUnits, $entryPrice) {
			$number = $noOfUnits * $entryPrice;
			if($number>0)
			{
				$grossROI = ($plPerLac/($noOfUnits * $entryPrice))*100;
				return number_format((float)$grossROI, 2, '.', '') . ' %';
			}
			else
			{
				return 0;
			}
		}

		// protected function number_format_drop_zero_decimals($n, $n_decimals)
		 //    {
		 //        return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
		 //    }

		protected function finalResult($grossROI) {
			if($grossROI<=0){
				return 'MISS';
			}
			elseif ($grossROI>0){
				return 'HIT';
			}
			else {
				return 'Pending';
			}
		}

		protected function perCallInvestment($entryPrice, $noOfUnits) {
			return $entryPrice * $noOfUnits;
		}

		protected function perCallProfitLoss($plPerLac) {
			return $plPerLac;
		}

		protected function perCallROIonInvestment($plPerLac, $perCallInvestment) {
			$roi = $plPerLac / $perCallInvestment;
			return number_format((float)$roi, 4, '.', '');
		}

		protected function totalInvestment($perCallInvestment) {
			return round($this->totalInvestment += $perCallInvestment);
		}

		protected function netProfitLoss($plPerLac) {
			return $this->netProfitLoss += $plPerLac;
		}


		protected function totalAverageTimePeriod($totalTimePeriod, $totalCallsgiven) {
			//$totalTimePeriod = $this->totalAverageTimePeriod += $totalTimePeriod;
			if($totalCallsgiven>0) {
				$averageTimePeriod = $totalTimePeriod / $totalCallsgiven;
				return number_format($averageTimePeriod, 2, '.', ' %');
			}
		}

		protected function annualisedROI($netProfitLossTillYet, $totalInvestmentTillYet, $totalAverageTimePeriod) {

			$secondNo =   ($totalInvestmentTillYet * $totalAverageTimePeriod) / 365;
			if($secondNo > 0 ) {
				$annualisedROI = ($netProfitLossTillYet / $secondNo) * 100;
				return number_format((float)$annualisedROI, 2, '.', ''). ' %';
			}
		}

		protected function successPercentage($totalCallsgiven, $totalHits, $totalPendings) {
			if($totalHits>0){
				$successRate = ($totalHits/($totalCallsgiven - $totalPendings)) * 100;
				return number_format($successRate, 1, '.', ''). ' %';
			}
		}

		protected function sumOfPlPerLac($plPerLac) {
			 return $this->sumOfPlPerLac += $plPerLac;
		}

		protected function totalInvestmentTillYet($entryPrice, $noOfUnits){
			$this->totalInvestmentTillYet += $entryPrice*$noOfUnits;
			return $this->totalInvestmentTillYet;
		}

		protected function netProfitLossTillYet($plPerLac){
			return $this->netProfitLossTillYet += $plPerLac;
		}

		protected function roiOnInvestment($netProfitLossTillYet, $totalInvestmentTillYet) {
			if($totalInvestmentTillYet > 0 ) {
				$this->roiOnInvestment = ($netProfitLossTillYet / $totalInvestmentTillYet)*100;
				return number_format($this->roiOnInvestment, 2 , '.', ''). ' %';
			}
		}

	
	}

?>