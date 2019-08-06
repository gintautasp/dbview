<?php
	class db_4_www extends db_mysql_user1 {

		public function get_active_languages () {
			
			$qwGetActiveLanguages = "SELECT * FROM `www_languages` WHERE `active`";
						
			return $this->perform_query ( $qwGetActiveLanguages,  3 );
		}

		public function get_menu_by_parent ( $lang, $parent = 0, $order_dir = 'ASC' ) {
		
			$qwGetMenuByParent = "
				SELECT 	  `www_menu`.`txt_" . $lang . "_name` AS `menu_name` 
						, IF(LENGTH(`www_seo_links`.`seo_link`)>0
							, `www_seo_links`.`seo_link`, `www_links`.`link`
							) AS `menu_link` 
						, `www_links`.`link` as `system_link`
				FROM `www_menu` 
				LEFT JOIN `www_links` ON `www_menu`.`id_link` = `www_links`.`id`
				LEFT JOIN `www_seo_links` ON `www_menu`.`id_link` = `www_seo_links`.`id_link`
				WHERE `www_menu`.`id_parent`= $parent AND `www_seo_links`.`language` = '$lang'
				ORDER BY `www_menu`.`order` $order_dir
			";			
			return $this->perform_query ( $qwGetMenuByParent, 3 ); 
		}

		public function get_page_link_by_id ( $lang, $pageId = 0 ) {
		
			$qwGetPageLinkById = "
				SELECT 	IF(LENGTH(`www_seo_links`.`seo_link`)>0
							, `www_seo_links`.`seo_link`, `www_links`.`link`
							) AS `page_link` 
				FROM `www_html_pages`
				RIGHT JOIN `www_links` ON `www_html_pages`.`id_link` = `www_links`.`id`
				LEFT JOIN `www_seo_links` ON `www_links`.`id` = `www_seo_links`.`id_link`
				WHERE `www_links`.`id`= $pageId AND `www_seo_links`.`language` = '$lang'
			";
			return $this->get_1_selected_value ( 'page_link', $qwGetPageLinkById, 3 );
		}

		public function link_by_seo ( $seo_link ) {
		
			$qwGetLinkBySeo = "
							SELECT `language`, `www_links`.`link`
									, IFNULL(`www_links`.`addon_dir`,'') AS `addon`
									, IFNULL(`www_links`.`id_possibilities`, 0) AS `possibilityId`
							FROM `www_seo_links`
							LEFT JOIN `www_links` ON `www_seo_links`.`id_link` = `www_links`.`id`
							WHERE `www_seo_links`.`seo_link` = '" . $seo_link . "'"
			;
			return $this->get_1_selected_row ( $qwGetLinkBySeo, 3 );
		}

		public function get_page_in_langs ( $page ) {

			$rsGetPagesInLangs = false;

			if ( !empty ( $page ) ) {

				$qwGetPagesInLangs  = "
								SELECT `seo_links_all`.`language`, `seo_links_all`.`seo_link`
								FROM `www_seo_links`
								LEFT JOIN `www_seo_links` AS `seo_links_all` 
								ON `www_seo_links`.`id_link` = `seo_links_all`.`id_link`
								WHERE `www_seo_links`.`seo_link` = '" . $page . "'";

				$rsGetPagesInLangs = $this->perform_query ( $qwGetPagesInLangs, 3 );

			} else $this->take_exec_error ( 'page is empty', $qwGetPagesInLangs, 3 );

			return $rsGetPagesInLangs;
		}

		public function get_visits_by_countries () {

			$qwGetVisitsByCountries = "
				SELECT COUNT(*)  AS `visits_num`, `www_visits`.`country_code`, `geo_countries`.`country_name` 
				FROM `www_visits` LEFT JOIN `geo_countries` ON ( `geo_countries`.`country_code`=`www_visits`.`country_code` ) 
				GROUP BY `www_visits`.`country_code` ORDER BY `visits_num`
			";
			return $this->get_all_objects_array ( $qwGetVisitsByCountries, 3 );
		}

		public function get_visits_summary ( $days = 0, $tillDate='NOW()', $year = 0, $month = 0, $day = 0 ) 	{

			$whereByDate1 = ""; $whereByDate2 ="";

			if ( $days != 0 ) {

				$whereByDate2 = "WHERE (`action_time` > DATE_SUB($tillDate, INTERVAL $days DAY)) AND ( `action_time` < $tillDate)";
			}
			elseif ( $year != 0 ) {
			
				$whereByDate = "$year";
				
				if ( $month != 0 ) {
				
					$whereByDate .= "-$month";
					
					if ($day != 0) $whereByDate .= "-$day";
				}
				$date_length = strlen ( $whereByDate );

				$whereByDate2 = "WHERE SUBSTRING(`action_time`, 1, $date_length) = '$whereByDate'";				
			}

			$visits = new stdClass; $visits->visits = '?'; $visits->pages = '?';

			$qWcreatEtmp_visits_on_period = 
					"
				CREATE TEMPORARY TABLE IF NOT EXISTS `tmp_visits_on_period` (
					`id_visits` int(10) unsigned NOT NULL
					,`clicks` int(10) unsigned NOT NULL
					, PRIMARY KEY (`id_visits`)) 
				ENGINE=MyISAM DEFAULT CHARSET=latin1
					";
			$this->perform_query ( $qWcreatEtmp_visits_on_period, 3 );

			$qWpreparEtmp_visits_on_period =
					"
				INSERT INTO `tmp_visits_on_period` ( `id_visits`, `clicks` ) 
				SELECT `id_visits`, COUNT(*) AS `clicks`
				FROM `www_log_visits`
				$whereByDate2
				GROUP BY `id_visits`
					";
			$this->perform_query ( $qWpreparEtmp_visits_on_period, 3 ); 
			
			$qwGetVisitsSummary 	=  "
							SELECT SUM(`clicks`) AS `pages`, COUNT(*) AS `visits`
							FROM `tmp_visits_on_period`
							";
			$reTval = mysql_fetch_object ( $this->perform_query ( $qwGetVisitsSummary, 3 ) );
			
			$qWtruncatEtmp_visits_on_period = "TRUNCATE TABLE  `tmp_visits_on_period`";
			$this->perform_query ( $qWtruncatEtmp_visits_on_period, 3 );
			
			return $reTval;
		}

		public function get_crawlers_as_options() {
		
			$options_list = '';

			$qwGetCrawlers 	= 	"
									SELECT `sys_name` FROM `www_visitors`
									WHERE `sel_type` = 4
									GROUP BY `sys_name`
									LIMIT 0 , 30
									";
			if ( $rsGetCrawlers = $this->perform_query ( $qwGetCrawlers, 3 ) ) {
			
				while ( $row = mysql_fetch_array ( $rsGetCrawlers  ) ) 
					$options_list 	.= '<option value="' . $row [ 'sys_name' ] . '">' . $row [ 'sys_name' ] . '</option>' . "\n";
				
			} else $this->take_mysql_errors ( 'get_crawlers_as_options', $qwGetCrawlers, 2 );
			
			return $options_list;
		}

		public function get_seo_link_by_link ( $lang, $pageLink ) {
		
			$qwGetSeoLinkByLink = "
								SELECT `seo_link`, `www_links`.`txt_" . $lang . "_name` AS `link_name`
								FROM `www_seo_links` 
								LEFT JOIN `www_links` ON `www_links`.`id` = `www_seo_links`.`id_link`
								WHERE `www_links`.`link`= '$pageLink' AND `www_seo_links`.`language` = '$lang'
								";
			return $this->get_1_selected_row ( $qwGetSeoLinkByLink, 3 );
		}
		
		public function get_country_code_by_ip_num ( $ip_num ) {
		
			$qwGetCountryCodeByIPnum = "
				SELECT `geo_countries`.`country_code`, `geo_countries`.`main_lang_code` 
				FROM `geo_geoip` 
				LEFT JOIN `geo_countries` ON ( `geo_geoip`.`country_code`=`geo_countries`.`country_code`)
				WHERE `start_ip_num` <= $ip_num AND `finish_ip_num` >= $ip_num
				";
			return $this->get_1_selected_row ( $qwGetCountryCodeByIPnum, 3 );
		}
		
		public function get_selectors_as_options ( $lang, $group, $selected, $empty_first = false ) {
		
			$html_options = $empty_first ? '<option value="0">' . $empty_first . '</option>' : '';
			
			if ( $selectors  = $this->get_selectors_array ( $lang, $group ) ) {
						
				foreach ( $selectors as $selector )  {
				
					$html_selected = '';
					
					if ( $selected == $selector->num_on_group ) $html_selected = ' selected="selected" ';
				
					$html_options .= '<option value="' . $selector->num_on_group . '"' . $html_selected . '>' 
								. $selector->name . '</option>';
				}
			}
			return $html_options;
		}
		
		public function get_filters_as_options ( $group, $selected ) {
		
			$html_options = '';
			
			if ( $selectors  = $this->get_filters ( $group ) ) {
						
				foreach ( $selectors as $selector )  {
				
					$html_selected = '';
					
					if ( $selected == $selector->val ) $html_selected = ' selected="selected" ';
				
					$html_options .= '<option value="' . $selector->val . '"' . $html_selected . '>' 
								. $selector->name . '</option>';
				}
			}
			return $html_options;
		}	

		public function get_filters ( $group, $val = '' ) {
		
			$qw_get_selectors = 
					"
				SELECT
					`val`
					, `name`
					, `fltr_val`
				FROM
					`dat_fltr_selectors`
				WHERE
					`grupe`='" . $group . "'
					" . ( $val ? " AND `val`='"  . $val .  "'" : '' ) . "
					";
				$iret = '';
				
			if ( $val ) {
			
				$iret = $this->get_1_selected_value ( 'fltr_val', $qw_get_selectors );
				
			} else {
			
				$iret = $this->get_all_objects_array ( $qw_get_selectors );
			}
			return $iret;
		}
		
		public function get_selectors_array ( $lang, $group ) {
		
			$qwGetSelectors = "
				SELECT `www_selectors`.`num_on_group`, `www_selectors`.`name_" . $lang . "` as `name`
				FROM `www_selectors` 
				LEFT JOIN `sys_groups` ON ( `www_selectors`.`id_group` = `sys_groups`.`id` )
				WHERE `sys_groups`.`name` = '" . $group . "'
				GROUP BY `www_selectors`.`num_on_group`
			";
			return $this->get_all_objects_array ( $qwGetSelectors, 3 );
		}
	}
?>