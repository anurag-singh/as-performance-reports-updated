<?php
/*
 *Template Name: Performance Tracker - Equity Trading Calls 
*/
get_header();?>
    <div class="cntn-sec">
        <div class="wper">
            <div class="pg-lft-clm">
                <div class="pg-bnr">
                    <span class="pg-brn-img"><img src="<?php echo get_template_directory_uri();?>/assets/images/page-banner/re-search.png" align="right"> </span>
                    <div class="pg-bnr-tilel">
                        <div>
                            <h2>Performance Tracker</h2
                        ></div>
                    </div>
                </div>
                <div class="pg-cntnt">
                <?php get_template_part('partials/breadcrumbs/breadcrumbs');?>

                	<div class="header"><h1>DataTable demo (Server side) in Php,Mysql and Ajax </h1></div>
					<div class="tbl-ovr-flo">
						<table id="employee-grid"  width="100%" cellspacing="0" cellpadding="7" bordercolor="#fff" border="1" bgcolor="#f1f2f2">
								<thead>
									<tr>
										<th>Stocks</th>
						                <th>Date</th>
						                <th>Buy/Sell</th>
						                <th>Entry Price</th>
						                <th>Target Price</th>
						                <th>Stop Loss</th>
						                <th>Exit Price</th>
						                <!-- <th>P/L Per Unit</th>
						                <th>P/L Per Lac</th>
						                <th>Gross ROI%</th>
						                <th>Final Result</th> -->
									</tr>
								</thead>
						</table>
					</div>



                    <h2>Performance Tracker - Equity Trading Calls</h2>
                    <?php //get_template_part('partials/performance-report/hit-&-miss-report');?>

                    <?php
					    $callType = new Single_Call_Type();

					    $allTradingCalls = $callType->display_data_in_table('Trading');
					?>

                    <?php
                        if(have_posts()) : while(have_posts()) : the_post();
                            the_content();
                        endwhile;
                        endif;
                    ?>
               </div>
            </div>
            
        <?php get_sidebar();?>            
        <?php get_footer();?>
    </div>
</div>
