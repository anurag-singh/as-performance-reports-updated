// Display diffrent meta boxes based on taxonomy's term selection

jQuery(function($)
    {
        function my_check_categories()
        {
            $('#piklist_meta_download_kyc_meta_boxes').hide();
            $('#piklist_meta_download_margin_meta_boxes').hide();
            $('#piklist_meta_download_software_meta_boxes').hide();
            $('#piklist_meta_download_guide_meta_boxes').hide();
            $('#piklist_meta_download_product_demo_video_meta_boxes').hide();
            
 
            $('#taxonomy-download_cat input[type="checkbox"]').each(function(i,e)
            {
                var id = $(this).attr('id').match(/-([0-9]*)$/i);
 
                id = (id && id[1]) ? parseInt(id[1]) : null ;
 
                if ($.inArray(id, [263]) > -1 && $(this).is(':checked'))
                {
                    $('#piklist_meta_download_kyc_meta_boxes').show();
                }

                if ($.inArray(id, [264]) > -1 && $(this).is(':checked'))
                {
                    $('#piklist_meta_download_guide_meta_boxes').show();
                }

                if ($.inArray(id, [265]) > -1 && $(this).is(':checked'))
                {
                    $('#piklist_meta_download_software_meta_boxes').show();
                }

                if ($.inArray(id, [266]) > -1 && $(this).is(':checked'))
                {
                    $('#piklist_meta_download_product_demo_video_meta_boxes').show();
                }

                if ($.inArray(id, [267]) > -1 && $(this).is(':checked'))
                {
                    $('#piklist_meta_download_margin_meta_boxes').show();
                }
            });
        }
 
        $('#taxonomy-download_cat input[type="checkbox"]').live('click', my_check_categories);
 
        my_check_categories();
    });