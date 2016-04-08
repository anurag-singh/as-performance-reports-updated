<?php
	class New_Menu {

		public function register_menu() {
			register_nav_menu(
					'performance_report' , __('Performance Report Filter')
				);
		}


	}
?>