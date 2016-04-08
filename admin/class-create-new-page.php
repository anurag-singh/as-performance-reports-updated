<?php

class New_Page {
	var $pageTitle;
	var $postType;
	

	function __construct() 
	{
		$this->pageTitle = "Performance Report";
		$this->postType = "page";
	}

	public function insert_new_page() 
	{
		$newpage = array(
						'post_title'	=> $this->pageTitle,
						'post_content'	=> "Plz dont delete this page. Its a pluging auto generated page.",
						'post_type'		=> $this->postType,
						'post_status'	=> "publish",
						'post_author'	=> 1
					);

		wp_insert_post($newpage);

	}

	
	public function delete_page()
	{
		$newpage = get_page_by_title( $this->pageTitle, 'OBJECT', $this->postType );

		if($newpage->ID)
		{
			wp_delete_post($newpage->ID, TRUE);
		}		  
	}
}

?>