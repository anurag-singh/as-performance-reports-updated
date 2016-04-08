jQuery(document).ready(function($) {
var dataTable = '';
         dataTable = $('#all-single-type-calls-grid').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"../wp-content/plugins/as-performance-reports-updated/public/class-all-calls-grid-data.php", // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".all-single-type-calls-grid-error").html("");
                    $("#all-single-type-calls-grid").append('<tbody class="all-single-type-calls-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#all-single-type-calls-grid_processing").css("display","none");
 
                }
            }
        } ).on('xhr.dt', function ( e, settings, data, xhr ) {
        //alert(data.summarisedSnapshot['callsGiven']);
        $('.callsGiven').html(data.summarisedSnapshot['callsGiven']);
        $('.totalHits').html(data.summarisedSnapshot['totalHits']);
        $('.totalMisses').html(data.summarisedSnapshot['totalMisses']);
        $('.totalPendings').html(data.summarisedSnapshot['totalPendings']);
        $('.successPercentage').html(data.summarisedSnapshot['successPercentage']);
        $('.roiOnInvestment').html(data.summarisedSnapshot['roiOnInvestment']);
        $('.annualisedROI').html(data.summarisedSnapshot['annualisedROI']);
    } );
        function func_performanceTracker(_category,dFrom='',dTo='')
        {
        	$('._moveHere').ScrollTo({
		      duration: 1000,
		      durationMode: 'all'
		    });
			    dataTable.destroy();
			if(_category!='')
			{
				var a = $('#options-pre').next('th').hasClass('options');
			    alert(a);
			   	if(a)
			   	{
			   		$('#options-pre').next('th').remove();
			   	}
				var dataParam = {cat:_category,dateFrom:dFrom,dateTo:dTo};
				dataTable = $('#all-single-type-calls-grid').DataTable( {
	            "processing": true,
	            "serverSide": true,
	            "ajax":{
	                url :"../wp-content/plugins/as-performance-reports-updated/public/class-all-calls-grid-data.php", // json datasource
	                type: "post",  // method  , by default get
	                data : dataParam,
	                error: function(){  // error handling
	                    $(".all-single-type-calls-grid-error").html("");
	                    $("#all-single-type-calls-grid").append('<tbody class="all-single-type-calls-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
	                    $("#all-single-type-calls-grid_processing").css("display","none");
	 
	                	}
	           		 }
	       		 } ).on('xhr.dt', function ( e, settings, data, xhr ) {
				        //alert(data.summarisedSnapshot['callsGiven']);
				        $('.callsGiven').html(data.summarisedSnapshot['callsGiven']);
				        $('.totalHits').html(data.summarisedSnapshot['totalHits']);
				        $('.totalMisses').html(data.summarisedSnapshot['totalMisses']);
				        $('.totalPendings').html(data.summarisedSnapshot['totalPendings']);
				        $('.successPercentage').html(data.summarisedSnapshot['successPercentage']);
				        $('.roiOnInvestment').html(data.summarisedSnapshot['roiOnInvestment']);
				        $('.annualisedROI').html(data.summarisedSnapshot['annualisedROI']);
				    } );
			}
			return false;
        }
		/* Add scroll functionality */	
		/*$('#options-pre').next('th').remove();*/
        $('.getCategory').on('click',function(e){
        	
        	$('.last-month-report').hide();
        	$('.full-report').show();

        	
			/* Add scroll functionality */			

			var category = $(this).find('a').attr('rel');
			
			if(category == "Equity Options")
			{
				$('#options-pre').after('<th class="options">Future Price</th>');
			}

		
			$('.getCategory,._perfReport').removeClass('current-menu-item');
			$('._perfReport').css('border-bottom','medium none');
			$(this).addClass('current-menu-item');
			func_performanceTracker(category);
		});

		$('#btnSearch').on('click',function(e){
        	
		/* Add scroll functionality */			
		    
			var _dateFrom 	=	$('#txtFrom').val();
			var _dateTo	 	=	$('#txtTo').val();
			var category 	=	$('#categoryType').val();
			$('.getCategory,._perfReport').removeClass('current-menu-item');
			$('._perfReport').css('border-bottom','medium none');
			/*$(this).addClass('current-menu-item');*/
			
			if($('.getCategory').find('a').attr('rel')==category)
			{
				//alert(0);
				/*$('a').attr('rel',category).addClass('current-menu-item');*/
				/*$('a').attr('rel',category).prev().find('li .getCategory').addClass('current-menu-item');*/
				/*$('a').attr('rel',category).closest('li').addClass('current-menu-item');*/
				/*alert($curr);
				$curr = $curr.prev();
				$curr.addClass('current-menu-item');*/
			}
			func_performanceTracker(category,_dateFrom,_dateTo);
		});
    } );
