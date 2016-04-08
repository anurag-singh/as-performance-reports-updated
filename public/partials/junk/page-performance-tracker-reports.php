<?php
/*
 *Template Name: Performance Tracker Reports Page
*/
get_header();?>
    
                <h2>Performance Tracker - Equity Trading Calls</h2>

                	<?php
					    // $callType = new Single_Call_Type();

					    // $allTradingCalls = $callType->display_data_in_table('Trading');
                    ?> 

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
						                <th>Date</th>
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


	
                    
                    
        <?php get_footer();?>
    