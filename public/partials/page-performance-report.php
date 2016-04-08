<?php
/*
 *Template Name: Performance Tracker
*/
get_header();?>
    <div class="cntn-sec">
        <div class="wper">
            <div class="pg-lft-clm">
                <div class="pg-bnr">
                    <span class="pg-brn-img"><img src="<?php echo get_template_directory_uri();?>/assets/images/page-banner/re-search.png" align="right"> </span>
                    <div class="pg-bnr-tilel">
                        <div>
                            <h2>Research</h2
                        ></div>
                    </div>
                </div>
                <div class="pg-cntnt">
                <?php get_template_part('partials/breadcrumbs/breadcrumbs');?>
				<?php echo do_shortcode('[display_performance_report]'); ?>
               </div>
            </div>
            <div class="pg-rit-clm">
        		<?php get_sidebar('research-report-filter');?>            
        	</div>
       	
       		<style type="text/css">
       			.getCategory{cursor: pointer;
       		</style>	
<?php get_footer();?>

</div>
</div>
