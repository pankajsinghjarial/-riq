<?php
$myfile = fopen("trashedwidget/widget_token.txt", "r");
$widget_token = fread($myfile,filesize("trashedwidget/widget_token.txt"));
if(!empty($widget_token)){
	$widgettoken = explode(',',$widget_token);
 
	if (in_array($_REQUEST['widget'], $widgettoken)){
		die('disabled');
	} 
}


header("Access-Control-Allow-Origin: *");
session_start();
error_reporting(0);
include("database.php");
$samesession = 0;


 if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ipaddress  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ipaddress  = $_SERVER['REMOTE_ADDR'];
	}
 
 
 $widgets=mysql_query("INSERT INTO `IP_traffic` (`id`, `ip`, `widget`, `created`) VALUES ('', '".$ipaddress."', '".$_REQUEST['widget']."', '".date("Y-m-d H:i:s")."');");
 
/* if(!isset($_SESSION['browsersession'])){
	$_SESSION['browsersession'] = rand().'-'.time();		
}else{
	if(isset($_SESSION['browsersession'])){
		$browsersession_arr = explode('-',$_SESSION['browsersession']);
		if(isset($browsersession_arr[1])){
			$minute =  round(abs(time() - $browsersession_arr[1]) / 60);
			if($minute > 30){
				$_SESSION['browsersession'] = rand().'-'.time();
				$samesession = 0;
			}else{
				$samesession = 1;
			}
		}else{
			$samesession = 1;
		}
	}else{
		$samesession = 1;
	}
} */
if(!isset($_SESSION['browsersession'])){
	$_SESSION['browsersession'] = rand();
	session_write_close();
}else{
	$samesession = 1;
}
$versions = 0;
if(stripos($_SERVER['HTTP_USER_AGENT'],"MSIE")!==false){
	$domain = stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE');
	$split =explode(' ',$domain);
	$version = number_format($split[1], 0, '.', '');
}else if(stripos($_SERVER['HTTP_USER_AGENT'],"Edge")!==false){
	$domain = stristr($_SERVER['HTTP_USER_AGENT'], 'Edge');
	$split =explode('/',$domain);
	$version = number_format($split[1], 0, '.', '');
}else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false) {
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:')) {
		$content_nav = 'Trident/7.0; rv:';
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7')) {
		$content_nav = 'Trident/7';
	}
	$pattern = '#' . $content_nav . '\/*([0-9\.]*)#';
	$matches = array();
	if(preg_match($pattern, $_SERVER['HTTP_USER_AGENT'], $matches)) {
		$version = number_format($matches[1], 0, '.', '');
	}
}
if($version > 0){
	if($version < 11 || $version < '11'){
		$versions = 1;
	}
}
if($versions==0){
	//echo 'session data'.$_SESSION['browsersession'];
	function getLocationInfoByIp(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip  = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip  = $_SERVER['REMOTE_ADDR'];
		}
		$ip_data = json_decode(file_get_contents("https://ipapi.co/".$ip."/json?key=fcf57187ce9ecf319f3f391379678bd165a478d1"));
		if($ip_data && $ip_data->country != null){
			$result = $ip_data->country;
		}
		return $ip_data;
	}
	function send_mail($to,$toname,$subject,$message){
		$url = 'https://api.mailgun.net/v3/responseiq.com/messages';
		$fromname = 'Response IQ';
		$from = 'no-reply@responseiq.com';
		$ch = curl_init();
		$message = $message;
		
		$bcc_1 = 'sales@responseiq.com'; 	 
		$bcc_2 = 'simer169@gmail.com';  
		
		
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:key-111a5aa24bc8cad5a2bbd3b33c72176f');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
					array('from' => ''.$fromname.' '.$from.'',
						  'to' => ''.$toname.' <'.$to.'>',
						  'bcc' => ''.$bcc_1.','.$bcc_2.'',
						  'subject' => ''.$subject.'',
						  'html' => ''.$message.'',				  
						  ));
		$result = curl_exec($ch);
		curl_close($ch);
	}
	function extract_emails_from($string){
		preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
		return $matches[0];
	}
	function sanitize_output($buffer){
		$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s'       // shorten multiple whitespace sequences
		);
		$replace = array(
			'>',
			'<',
			'\\1'
		);
		$buffer = preg_replace($search, $replace, $buffer);
		return $buffer;
	}
	$configs = mysql_query("SELECT * FROM configs");
	if(mysql_num_rows($configs) > 0){
		$configinfo = mysql_fetch_array($configs);
		$siteurl = $configinfo['siteurl'];
	}
	if ((stripos($_SERVER['HTTP_USER_AGENT'], 'Google') === false) && (stripos($_SERVER['HTTP_USER_AGENT'], 'google') === false) && (stripos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') === false)) {
	}else{
		echo "200";
		die();
	}
	$delay_icon_second = 1;
	$delay_icon_out_second = 1;
	$call_icon_fade = 0;
	$enable_sounds = 0;
	$attempts_to_exit = 0;
	$agents_agents_true = 0;
	$onlineagents = 0;
	$htmlpages = '';
	$widget_schedule_html = '';
	$widget_thanks_html = '';
	$widget_schedule_callback_html = '';
	$buttonimage = '';
	$btn_color = '';
	$btn_class = '';
	$day_agent = '';
	$time_agent = '';
	$allow_widgets_url = '';
	$disallow_widgets_url = '';
	$template_capture_number = '';
	$currentday = date("d");
	// $total_connected_calls = ($currentday % 6);
	// if($total_connected_calls==0){
		// $total_connected_calls = 2;
	// }
	// $total_scheduled_calls = ($currentday % 6);
	// if($total_scheduled_calls==0){
		// $total_scheduled_calls = 2;
	// }
	$welcometext_animate_1 = 0;
	$welcometextschedule_animate_2 = 0;
	$tooltip_close = 0;
	$tooltip_close_click = 0;
	$time_on_website = 0;
	$time_check = 0;
	$saveattempts_to_exit = 0;
	$tooltip_animation = 0;
	$show_tooltip = 0;
	$btn_border_color = '';
	$agentslist = '';
	$notification_msg = '';
	$notification_msg_2 = '';
	$selector = '';
	$location = '';
	$phonecode = '';
	$is_type = 0;
	$speachbox_type = 0;
	$outhours_show_tooltip = 0;
	$outhours_time_check = 0;
	$outhours_attempts_to_exit = 0;
	$outhours_time_on_website = 0;
	$company_id = 0;
	$hide_widgets_background = 0;
	$hide_after_closing_popup = 0;
	$hide_widgets_background_out = 0;
	$out_mobile_popup = 0;
	$live_mobile_popup = 0;
	$out_delay_before_minimising_popup = 0;
	$live_delay_before_minimising_popup = 0;
	$template_id = 0;
	$currenttimezone = '';
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ipaddress  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ipaddress  = $_SERVER['REMOTE_ADDR'];
	}
	$hide_after_closing_popup_reload = 0;
	$hide_after_closing_popup_out_reload = 0;
	$call_schedule_button_enable = 0;
	$select_visitor_time_enabled = 0;
	if(isset($_REQUEST['widget'])){
		if($_REQUEST['widget'] !=''){
			$widgets=mysql_query("select * from widgets where widget_token = '".$_REQUEST['widget']."' and status = 1");
			if(mysql_num_rows($widgets) > 0){
				$widgets_info=mysql_fetch_assoc($widgets);
				$company=mysql_query("select * from companies where id = '".$widgets_info['company_id']."' and is_trashed= 0 and status= 1 and company_active_status= 1");
				if(mysql_num_rows($company) > 0){
					// if($widgets_info['install_status']==1){
						//commented, because we want to show widget everytime on site.
					if(1==1){
						$company_details=mysql_query("select * from companies where id = '".$widgets_info['company_id']."'");
						$company_arr=mysql_fetch_assoc($company_details);
						if($company_arr['master_company_id'] > 0){
							$company_credits=mysql_query("select * from company_settings where company_id = '".$company_arr['master_company_id']."'");
							$company_id = $company_arr['master_company_id'];
						}else{
							$company_credits=mysql_query("select * from company_settings where company_id = '".$widgets_info['company_id']."'");
							$company_id = $widgets_info['company_id'];
						}
						$company_credits_info=mysql_fetch_assoc($company_credits);
						date_default_timezone_set($company_credits_info['time_zone']);
						if(($company_credits_info['calling_limit'] > 0) && ($company_credits_info['next_renual_date'] >= date("Y-m-d"))){
							$visitors_arr=mysql_query("SELECT * FROM visitors WHERE ipaddress = '".$ipaddress."' and company_id = '".$widgets_info['company_id']."' and DATE(created) = '".date("Y-m-d")."' order by id desc limit 1");
							if(mysql_num_rows($visitors_arr) > 0){
								$visitors_info=mysql_fetch_assoc($visitors_arr);
								$location = $visitors_info['countrycode'];
							}else{
								$ip_data = getLocationInfoByIp();
								
								// echo '<pre>';
								// print_r($visitors_info);
								// print_r($ip_data);
								// echo '<pre>';
								
								
								
								$location = $ip_data->country;
							}
							if($location !=''){
								if($location=='CA'){
									$phonecode = '1';
								}else{
									$country_arr=mysql_query("select * from countries where iso = '".$location."'");
									if(mysql_num_rows($country_arr) > 0){
										$country_info=mysql_fetch_assoc($country_arr);
										$phonecode = $country_info['phonecode'];
									}
								}
							}else{
								$phonecode = $company_arr['country_code'];
								$country_arr=mysql_query("select * from countries where phonecode = '".$company_arr['country_code']."'");
								if(mysql_num_rows($country_arr) > 0){
									$country_info=mysql_fetch_assoc($country_arr);
									$location = $country_info['iso'];
								}
							}
							/* if(($company_arr['country_code']==1) || ($company_arr['country_code']=='1')){
								$location = 'US';
								$phonecode = '1';
							} */
							$is_type = $company_arr['is_type'];
							$selector = $company_arr['selector'];
							$notication_animate_1 = $widgets_info['welcometext_animate_1'];
							$welcometextschedule_animate_2 = $widgets_info['welcometextschedule_animate_2'];
							$widgets_allow_list=mysql_query("select * from widget_url_settings where widget_id = ".$widgets_info['id']."");
							$allowurl_arr = array();
							$disallowurl_arr = array();
							if(mysql_num_rows($widgets_allow_list) > 0){
								while($widgets_allows=mysql_fetch_assoc($widgets_allow_list)){
									if($widgets_allows['allowpage']==1){
										$allowurl_arr[] = array(
											'type'=>$widgets_allows['type'],
											'pattern'=>strtolower($widgets_allows['pattern']),
										);
									}else if($widgets_allows['allowpage']==0){
										$disallowurl_arr[] = array(
											'type'=>$widgets_allows['type'],
											'pattern'=>strtolower($widgets_allows['pattern']),
										);
									}
								}
							}
							if($widgets_info['logo'] !=''){
								$logoexists = 1;
							}
							$allow_widgets_url = $allowurl_arr;
							$disallow_widgets_url = $disallowurl_arr;
							$ipaddresses=mysql_query("SELECT * FROM ipaddresses WHERE ipaddress = '".$ipaddress."' AND company_id = ".$widgets_info['company_id']."");
							$location_enabled = 1;
							if(mysql_num_rows($ipaddresses) > 0){
								$location_enabled = 0;
							}
							$countryblock=mysql_query("SELECT * FROM ipaddresses WHERE ipaddress = '".$location."' AND company_id = ".$widgets_info['company_id']."");
							if(mysql_num_rows($countryblock) > 0){
								$location_enabled = 0;
							}
						 
						 	$allowedCountries=mysql_query("SELECT * FROM allow_only_countries where company_id = ".$widgets_info['company_id']." and allowcountry=1");
							if(mysql_num_rows($allowedCountries) > 0){
								$location_enabled = 0;
								while($widgets_allow_country=mysql_fetch_assoc($allowedCountries)){
									 
									if($widgets_allow_country['code']==$location){


										$location_enabled = 1;
										
									}
								}
							}
							
							
							$allowedCountries=mysql_query("SELECT * FROM allow_only_countries where company_id = ".$widgets_info['company_id']."  and allowcountry=0");
							if(mysql_num_rows($allowedCountries) > 0){
							
								while($widgets_allow_country=mysql_fetch_assoc($allowedCountries)){
									 
									if($widgets_allow_country['code']==$location){
										$location_enabled = 0;
										
									}
								}
							}
						 
							// if($company_id==214){
								// if($location!="GB"){
									// $location_enabled = 0;	
								// }
							// }
							
							
							// if($company_id==491){
								// if($location!="US"){
									// $location_enabled = 0;	
								// }
							// }	
							
							//robard.com
							// if($company_id==573){
								// if($location!="US" && $location !="CA" ){
									// $location_enabled = 0;	
								// }
							// }
							
							
							//north shore company
							// if($company_id==282){
								// if($location !="US" && $location !="CA" && $location !="GB"){
									// $location_enabled = 0;	
								// }
							// }
							// redflagalert company , capify.co.uk , bonline , Begbies ,flightguru, Telecoms World PLC
							// if($company_id==258 || $company_id==378 || $company_id==379 || $company_id==214 || $company_id==558|| $company_id==578){
								// if($location !="GB"){
									// $location_enabled = 0;	
								// }
							// }
							// if($company_id==327){
								// if($location =="IN"){
									// $location_enabled = 0;	
								// }
							// }
							
							//illumine
							// if($company_id==541){
								// if($location =="IN" || $location =="PH" || $location =="NG" || $location =="PK" || $location =="AF" || $location =="AF" || $location =="ET"){
									// $location_enabled = 0;	
								// }
							// }
							
							
							//Insurance Pro Shop 
							// if($company_id==244 || $company_id==246){
								// if($location !="US" && $location !="CA"){
									// $location_enabled = 0;	
								// }
							// }
							
							
							// if($company_id==292){
								// if($location =="NG"){
									// $location_enabled = 0;	
								// }
							// }
							if($location_enabled ==1){
								$widget_themes=mysql_query("select * from widget_themes where widget_id = ".$widgets_info['id']." and company_id = ".$widgets_info['company_id']."");
								if(mysql_num_rows($widget_themes) > 0){
									$widget_themes_info=mysql_fetch_assoc($widget_themes);
									$company_settings=mysql_query("select * from company_settings where company_id = '".$widget_themes_info['company_id']."'");
									if(mysql_num_rows($company_settings) > 0){
										$company_info=mysql_fetch_assoc($company_settings);
										date_default_timezone_set($company_info['time_zone']);
										$timezones=mysql_query("select * from timezones where time_zone = '".$company_info['time_zone']."'");
										if(mysql_num_rows($timezones) > 0){
											$timezones_info=mysql_fetch_assoc($timezones);
											$currenttimezone ="Current ".$timezones_info['time_zone_name']." Time: <b>".date('h:i a')."</b>";
										}
									}else{
										date_default_timezone_set('Europe/London');
										$timezones=mysql_query("select * from timezones where time_zone = 'Europe/London'");
										if(mysql_num_rows($timezones) > 0){
											$timezones_info=mysql_fetch_assoc($timezones);
											$currenttimezone ="Current ".$timezones_info['time_zone_name']." Time: <b>".date('h:i a')."</b>";
										}
									}
									$ieversion = 0;
									if(stripos($_SERVER['HTTP_USER_AGENT'],"MSIE")!==false){
										$domain = stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE');
										$split =explode(' ',$domain);
										$ieversion = number_format($split[1], 0, '.', '');
									}else if(stripos($_SERVER['HTTP_USER_AGENT'],"Edge")!==false){
										$domain = stristr($_SERVER['HTTP_USER_AGENT'], 'Edge');
										$split =explode('/',$domain);
										$ieversion = number_format($split[1], 0, '.', '');
									}else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false) {
										if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:')) {
											$content_nav = 'Trident/7.0; rv:';
										} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7')) {
											$content_nav = 'Trident/7';
										}
										$pattern = '#' . $content_nav . '\/*([0-9\.]*)#';
										$matches = array();
										if(preg_match($pattern, $_SERVER['HTTP_USER_AGENT'], $matches)) {
											$ieversion = number_format($matches[1], 0, '.', '');
										}
									}
									if($ieversion > 0){
										$visitors=mysql_query("SELECT * FROM visitors WHERE ipaddress = '".$ipaddress."' AND company_id = ".$widgets_info['company_id']." and DATE(created) = '".date("Y-m-d")."' order by id desc limit 1");
									}else if ((stripos($_SERVER['HTTP_USER_AGENT'],"iPhone") !== false) || (stripos($_SERVER['HTTP_USER_AGENT'],"iPad") !== false) || (stripos($_SERVER['HTTP_USER_AGENT'],"Safari") !== false)) {
										$visitors=mysql_query("SELECT * FROM visitors WHERE ipaddress = '".$ipaddress."' AND company_id = ".$widgets_info['company_id']." and DATE(created) = '".date("Y-m-d")."' order by id desc limit 1");
									}else{
										$visitors=mysql_query("SELECT * FROM visitors WHERE browsersession = '".$_SESSION['browsersession']."' AND company_id = ".$widgets_info['company_id']." and DATE(created) = '".date("Y-m-d")."' order by id desc limit 1");
									}
									$seconds = 0;
									if(mysql_num_rows($visitors) > 0){
										$visitors_info=mysql_fetch_assoc($visitors);
										$seconds = (strtotime(date("Y-m-d H:i:s")) - strtotime($visitors_info['created']));
										$tooltip_close_click = $visitors_info['tooltip_close_click'];
										$tooltip_close = $visitors_info['tooltip_close'];
										$tooltip_animation = $visitors_info['tooltip_animation'];
										$saveattempts_to_exit = $visitors_info['saveattempts_to_exit'];
										if($tooltip_close == 0){
											$tooltip_close=1;
											mysql_query("UPDATE visitors SET tooltip_close=1 WHERE id = ".$visitors_info['id']."");
										}
									}
									//check visitor ip based widget opened in once a day
									$autopop=mysql_query("SELECT * FROM visitors WHERE ipaddress = '".$ipaddress."' AND company_id = ".$widgets_info['company_id']." and DATE(created) = '".date("Y-m-d")."' and saveattempts_to_exit = 1 order by id desc limit 1");
									//if record found than disable
									if(mysql_num_rows($autopop) > 0){
										$saveattempts_to_exit = 1;
									}
									// $calllogs=mysql_query("SELECT * FROM calllogs WHERE widget_id = ".$widgets_info['id']." AND dialcallstatus = 'Connected' AND company_id = ".$widgets_info['company_id']." AND DATE(created) = '".date("Y-m-d")."'");
									// if(mysql_num_rows($calllogs) > 0){
										// $total_connected_calls = $total_connected_calls + mysql_num_rows($calllogs);
									// }
									// $scheduled_calls=mysql_query("SELECT * FROM scheduled_calls WHERE widget_id = ".$widgets_info['id']." AND company_id = ".$widgets_info['company_id']." AND DATE(created) = '".date("Y-m-d")."'");
									// if(mysql_num_rows($scheduled_calls) > 0){
										// $total_scheduled_calls = $total_scheduled_calls + mysql_num_rows($scheduled_calls);
									// }
									$current_day = date('l');	
									if($current_day == 'Monday'){
										$day_value = 1;
									}else if($current_day == 'Tuesday'){
										$day_value = 2;
									}else if($current_day == 'Wednesday'){
										$day_value = 3;
									}else if($current_day == 'Thursday'){
										$day_value = 4;
									}else if($current_day == 'Friday'){
										$day_value = 5;
									}else if($current_day == 'Saturday'){
										$day_value = 6;
									}else if($current_day == 'Sunday'){
										$day_value = 7;
									}
									$current_time = date('H:i:s');
									$agent_ids = array();
									if($widgets_info['select_agent_or_team']==1){
										$result_team_widgets=mysql_query("select * from team_widgets where widgets_id = '".$widgets_info['id']."'");
										if(mysql_num_rows($result_team_widgets) > 0){
											while($team_widgets_info=mysql_fetch_array($result_team_widgets)){
												if(($team_widgets_info['team_id'] > 0) && ($team_widgets_info['agent_id'] == 0)){
													$sql_agents=mysql_query("select team_agents.agent_id as agent_id ,agents.first_name,agents.last_name,agents.email ,agents.phone,agents.status from team_agents,agents where team_agents.agent_id=agents.id and team_agents.team_id =".$team_widgets_info['team_id']." and agents.status=1");
													if(mysql_num_rows($sql_agents) > 0){
														while($agents=mysql_fetch_array($sql_agents)){
															if($agents['status']==1){
																$agent_ids[]=$agents['agent_id'];
															}
														}
													}
												}
											}
										}
									}else if($widgets_info['select_agent_or_team']==0){
										$result_team_widgets=mysql_query("select * from team_widgets where widgets_id = '".$widgets_info['id']."'");
										if(mysql_num_rows($result_team_widgets) > 0){
											while($team_widgets_info=mysql_fetch_array($result_team_widgets)){
												if($team_widgets_info['agent_id'] > 0){
													$sql_agents=mysql_query("select * from agents where id =".$team_widgets_info['agent_id']." and status=1");
													if(mysql_num_rows($sql_agents) > 0){
														$agents = mysql_fetch_assoc($sql_agents);
														$agent_ids[]=$agents['id'];
													}
												}
											}
										}
									}
									if(!empty($agent_ids)){
										$widget_settings=mysql_query("select * from widget_settings where widget_id =".$widgets_info['id']."");
										$show_opt_count = 0;
										$hide_widgets_working_hours = 0;
										if(mysql_num_rows($widget_settings) > 0){
											$widget_settings_info = mysql_fetch_assoc($widget_settings);
											$show_opt_count =$widget_settings_info['show_opt_count'];
											$show_tooltip =$widget_settings_info['show_tooltip'];
											$enable_sounds =$widget_settings_info['enable_sound'];
											$attempts_to_exit =$widget_settings_info['attempts_to_exit'];
											$delay_icon_second =$widget_settings_info['delay_icon'] * 1000;
											$delay_icon_out_second =$widget_settings_info['delay_icon_out'] * 1000;
											$time_check =$widget_settings_info['time_check'];
											$outhours_time_check =$widget_settings_info['outhours_time_check'];
											$outhours_attempts_to_exit =$widget_settings_info['outhours_attempts_to_exit'];
											$outhours_show_tooltip =$widget_settings_info['outhours_show_tooltip'];
											$hide_widgets_background =$widget_settings_info['hide_widgets_background'];
											$hide_widgets_background_out =$widget_settings_info['hide_widgets_background_out'];
											$out_delay_before_minimising_popup =$widget_settings_info['out_delay_before_minimising_popup'] * 1000;
											$live_delay_before_minimising_popup =$widget_settings_info['live_delay_before_minimising_popup'] * 1000;
											$intervals =$widget_settings_info['intervals'];
											$hide_after_closing_popup =$widget_settings_info['hide_after_closing_popup'];
											$live_mobile_popup =$widget_settings_info['live_mobile_popup'];
											$out_mobile_popup =$widget_settings_info['out_mobile_popup'];
											$call_schedule_button_enable =$widget_settings_info['call_schedule_button_enable'];
											$select_visitor_time_enabled =$widget_settings_info['schedule_call_timezone_enable'];
											if($widget_settings_info['reppear_load'] > 0){
												$hide_after_closing_popup_reload =$widget_settings_info['reppear_load'] * 1000;
											}
											if($widget_settings_info['out_of_hor_reppear_load'] > 0){
												$hide_after_closing_popup_out_reload =$widget_settings_info['out_of_hor_reppear_load'] * 1000;
											}
											if($seconds > 0){
												$time_on_website =($widget_settings_info['delay_icon'] +($widget_settings_info['time_on_website'] - $seconds)) * 1000;
												$outhours_time_on_website =($widget_settings_info['delay_icon_out'] + ($widget_settings_info['outhours_time_on_website'] - $seconds)) * 1000;
											}else{
												$time_on_website =($widget_settings_info['delay_icon'] + $widget_settings_info['time_on_website']) * 1000;
												$outhours_time_on_website =($widget_settings_info['delay_icon_out'] + $widget_settings_info['outhours_time_on_website']) * 1000;
											}
											if($widget_settings_info['call_scenario']==2){
												$capture_number_sql=mysql_query("select * from capture_number_templates where widget_id =".$widgets_info['id']." and company_id =".$widgets_info['company_id']." and dynamic_number_enable = 1");
												if(mysql_num_rows($capture_number_sql) > 0){
													$capture_number=mysql_fetch_array($capture_number_sql);
													$template_id = $capture_number['template_id'];
												}
											}
											if(!empty($agent_ids)){
												$exits_scheduletime = array();
												$schedulecalls=mysql_query("SELECT * from scheduled_calls where status=0 and widget_id =".$widgets_info['id']." and company_id =".$widgets_info['company_id']." and schdeuled_date ='".date('Y-m-d')."'");
												if(mysql_num_rows($schedulecalls) > 0){
													while($schedulecall=mysql_fetch_array($schedulecalls)){
														$exits_scheduletime[] = $schedulecall['scheduled_time'];
													}
												}
												$agents_schedule=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day_value." and start_time <='".$current_time."' and end_time >='".$current_time."' and agent_id in (".implode(',',$agent_ids).")");
												if(mysql_num_rows($agents_schedule) > 0){
													$onlineagents = mysql_num_rows($agents_schedule);
													if($company_arr['is_type'] == 1){
														$agents_query=mysql_query("SELECT * from agents where id in (".implode(',',$agent_ids).")");
														if(mysql_num_rows($agents_query) > 0){
															$agents = "<option value='0'>Select ".$company_arr['selector']."</option>";
															while($agents_arr=mysql_fetch_array($agents_query)){
																$schedule_agent=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day_value." and start_time <='".$current_time."' and end_time >='".$current_time."' and agent_id ='".$agents_arr['id']."'");
																if(mysql_num_rows($schedule_agent) > 0){
																	$agents .= "<option value='".$agents_arr['id']."'>".ucfirst($agents_arr['first_name'])."</option>";
																}
															}
															$agentslist = sanitize_output("<div class='nmbcntry slclct'><select id='riq_agentslist' onchange='return riq_change_agent(this.value)'>".$agents."</select></div>");
														}
													}
													$agents_schedule_notactive=mysql_query("SELECT * from agent_schedules where agent_id in (".implode(',',$agent_ids).")");
													if(mysql_num_rows($agents_schedule_notactive) > 0){
														$agents_lastendtime_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day_value." and agent_id in (".implode(',',$agent_ids).") order by end_time asc limit 1");
														$todayenable = 0;
														if(mysql_num_rows($agents_lastendtime_details) > 0){
															$lastendtime_agents=mysql_fetch_array($agents_lastendtime_details);
															if(strtotime(date("H:i:00",strtotime("+30 minutes"))) > strtotime($lastendtime_agents['end_time'])){
																$todayenable = 1;
															}
														}
														if($todayenable==0){
															$day_agent = "<option value='".date("Y-m-d")."'>".date("j F")."</option>";
														}
														$days = array();
														while($online_agents=mysql_fetch_array($agents_schedule_notactive)){
															if($online_agents['status']==1 ){
																$days[$online_agents['day']] = $online_agents['day'];
															}
														}
														$alldays = array("1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7);
														$notavaliabledays = array_diff($alldays,$days);
														$k = 3;
														$m = 0;
														for($i=1;$i<=$k;$i++){
															$tomorrow = strtotime(date("Y-m-d") . "+".($i)." days");
															$current_day = date('l',$tomorrow);	
															if($current_day == 'Monday'){
																$day_value = 1;
															}else if($current_day == 'Tuesday'){
																$day_value = 2;
															}else if($current_day == 'Wednesday'){
																$day_value = 3;
															}else if($current_day == 'Thursday'){
																$day_value = 4;
															}else if($current_day == 'Friday'){
																$day_value = 5;
															}else if($current_day == 'Saturday'){
																$day_value = 6;
															}else if($current_day == 'Sunday'){
																$day_value = 7;
															}
															if(in_array($day_value,$notavaliabledays)){
															}else{
																if($m <= 1){
																	$day_agent .= "<option value='".date("Y-m-d",$tomorrow)."'>".date("j F",$tomorrow)."</option>";
																}
																$m++;
															}
														}
													}else{
														$agents_lastendtime_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day_value." and agent_id in (".implode(',',$agent_ids).") order by end_time asc limit 1");
														$todayenable = 0;
														if(mysql_num_rows($agents_lastendtime_details) > 0){
															$lastendtime_agents=mysql_fetch_array($agents_lastendtime_details);
															if(strtotime(date("H:i:00",strtotime("+30 minutes"))) > strtotime($lastendtime_agents['end_time'])){
																$todayenable = 1;
															}
														}
														if($todayenable==0){
															$day_agent = "<option value='".date("Y-m-d")."'>".date("j F")."</option>";
														}
														for($i=1;$i<=1;$i++){
															$tomorrow = strtotime(date("Y-m-d") . "+".$i." days");
															$day_agent .= "<option value='".date("Y-m-d",$tomorrow)."'>".date("j F",$tomorrow)."</option>";
														}
														$day_agent = sanitize_output(str_replace(array('"',"'"),"'",$day_agent));
													}
													//1 yes
													$agents_agents_true = 1;
													$current_day = date('l');	
													if($current_day == 'Monday'){
														$day = 1;
													}else if($current_day == 'Tuesday'){
														$day = 2;
													}else if($current_day == 'Wednesday'){
														$day = 3;
													}else if($current_day == 'Thursday'){
														$day = 4;
													}else if($current_day == 'Friday'){
														$day = 5;
													}else if($current_day == 'Saturday'){
														$day = 6;
													}else if($current_day == 'Sunday'){
														$day = 7;
													}
													$agents_start_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day." and agent_id in (".implode(',',$agent_ids).") order by start_time asc limit 1");
													if(mysql_num_rows($agents_start_details) > 0){
														$agent_schedules_start=mysql_fetch_assoc($agents_start_details);
														$minute = date("i");
														if($minute < 15){
															$minute = 15;
															$start = date("H:".$minute.":00");
														}else{
															$modulus = ($minute%15);
															$minute = ($minute-$modulus) + 15;
															if($minute > 59){
																$time=strtotime("+1 hour"); 
																$start=date("H:00:00",$time);  
															}else{
																$start = date("H:".$minute.":00");
															}
														}
													}else{
														$minute = 15;
														$start = date("H:".$minute.":00");
													}
													$agents_end_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day." and agent_id in (".implode(',',$agent_ids).") order by end_time asc limit 1");
													if(mysql_num_rows($agents_end_details) > 0){
														$agent_schedules_endtime=mysql_fetch_assoc($agents_end_details);
														if($intervals==5){
															$intervals_time = $intervals * 3;
															$end = date("H:i:00", strtotime("-".$intervals_time." minutes", strtotime($agent_schedules_endtime['end_time'])));
														}else{
															$end = date("H:i:00", strtotime("-".$intervals." minutes", strtotime($agent_schedules_endtime['end_time'])));
														}
													}else{
														$end = "23:45:00";	
													}
													$tStart = strtotime($start);
													$tEnd = strtotime($end);
													  $tNow = $tStart;
													 
													$select_time = '';
													while($tNow <= $tEnd){
														//'.date('T').' ('.str_replace('_',' ',date_default_timezone_get()).')'
														$time = date("H:i:00",$tNow);
														$select_time .= '<option value="'.$time.'">'.date("g:i A",$tNow).'</option>';
														$tNow = strtotime('+'.$intervals.' minutes',$tNow);
													}
													$time_agent = sanitize_output(str_replace(array('"',"'"),"'",$select_time));
												}else{
													$agents_schedule=mysql_query("SELECT * from agent_schedules where status=1 and agent_id in (".implode(',',$agent_ids).")");
													if(mysql_num_rows($agents_schedule) > 0){
														if($company_arr['is_type'] == 1){
															$selectorcurrentday = date('l');
															if($selectorcurrentday == 'Monday'){
																$selectorday_val = 1;
															}else if($selectorcurrentday == 'Tuesday'){
																$selectorday_val = 2;
															}else if($selectorcurrentday == 'Wednesday'){
																$selectorday_val = 3;
															}else if($selectorcurrentday == 'Thursday'){
																$selectorday_val = 4;
															}else if($selectorcurrentday == 'Friday'){
																$selectorday_val = 5;
															}else if($selectorcurrentday == 'Saturday'){
																$selectorday_val = 6;
															}else if($selectorcurrentday == 'Sunday'){
																$selectorday_val = 7;
															}
															$agents_query=mysql_query("SELECT * from agents where status = 1 and id in (".implode(',',$agent_ids).")");
															if(mysql_num_rows($agents_query) > 0){
																$agents = "<option value='0'>Select ".$company_arr['selector']."</option>";
																while($agents_arr=mysql_fetch_array($agents_query)){
																	$agents_arr_all[] = $agents_arr;
																}
																//to check for next 3 days
																for($i=0;$i<=2;$i++){				
																	//echo $selectorday_val.'<br>';
																	foreach($agents_arr_all as $agents_arr){
																		$starttime_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$selectorday_val." and agent_id = ".$agents_arr['id']."");
																		//	echo "SELECT * from agent_schedules where status=1 and day=".$selectorday_val." and agent_id = ".$agents_arr['id']."";
																		//echo '<br>';
																		if(mysql_num_rows($starttime_details) > 0){
																			if(!isset($finalAgentArr[$agents_arr['first_name']]) ){
																				$agents .= "<option value='".$agents_arr['id']."'>".ucfirst($agents_arr['first_name'])."</option>";
																				$finalAgentArr[$agents_arr['first_name']]= 1;
																			}
																		}
																	}
																	//increase day number to next day of week	
																	if($selectorday_val < 7){
																		$selectorday_val++;
																	}else{
																		$selectorday_val=1;
																	}
																}
																$agentslist = sanitize_output("<div class='nmbcntry slclct'><select id='riq_agentslist' onchange='return riq_change_agent(this.value)'>".$agents."</select></div>");
															}
														}
														$agents_agents_true = 0;
														$onlineagents = 0;
														$ko=1;
														$todaydata = 0;
														$agents_schedule_notactive=mysql_query("SELECT * from agent_schedules where agent_id in (".implode(',',$agent_ids).")");
														$notavaliabledays = array();
														if(mysql_num_rows($agents_schedule_notactive) > 0){
															$days = array();
															while($online_agents=mysql_fetch_array($agents_schedule_notactive)){
																if($online_agents['status']==1 ){
																	$days[$online_agents['day']] = $online_agents['day'];
																}
															}
															$alldays = array("1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7);
															$notavaliabledays = array_diff($alldays,$days);
														}
														for($r=1;$r<=$ko;$r++){
															$m = 2;
															$noactiveday = 0;
															$hide_widgets_working_hours =$widget_settings_info['hide_widgets'];
															if($ko==1){
																$day_agent = "<option value='".date("Y-m-d")."'>".date("j F")."</option>";
															}else{
																$todaydata = $ko-1;
																$day_agent = "<option value='".date('Y-m-d',strtotime(date("Y-m-d") . "+".$todaydata." days"))."'>".date('j F',strtotime(date("Y-m-d") . "+".$todaydata." days"))."</option>";
															}
															for($i=0;$i<=$m;$i++){
																$currentday = date('l',strtotime(date("Y-m-d") . "+".($ko+$i)." days"));
																if($currentday == 'Monday'){
																	$day_val = 1;
																}else if($currentday == 'Tuesday'){
																	$day_val = 2;
																}else if($currentday == 'Wednesday'){
																	$day_val = 3;
																}else if($currentday == 'Thursday'){
																	$day_val = 4;
																}else if($currentday == 'Friday'){
																	$day_val = 5;
																}else if($currentday == 'Saturday'){
																	$day_val = 6;
																}else if($currentday == 'Sunday'){
																	$day_val = 7;
																}
																if(in_array($day_val,$notavaliabledays)){
																 
																}else{
																	$tomorrow = strtotime(date("Y-m-d") . "+".($ko+$i)." days");
																	$day_agent .= "<option value='".date("Y-m-d",$tomorrow)."'>".date("j F",$tomorrow)."</option>";
																	$m--;
																}
															}
															$day_agent = sanitize_output(str_replace(array('"',"'"),"'",$day_agent));
															if($ko==1){
																$current_day = date('l');
															}else{
																$current_day = date('l',strtotime(date("Y-m-d") . "+".$todaydata." days"));	
															}
															if($current_day == 'Monday'){
																$day = 1;
															}else if($current_day == 'Tuesday'){
																$day = 2;
															}else if($current_day == 'Wednesday'){
																$day = 3;
															}else if($current_day == 'Thursday'){
																$day = 4;
															}else if($current_day == 'Friday'){
																$day = 5;
															}else if($current_day == 'Saturday'){
																$day = 6;
															}else if($current_day == 'Sunday'){
																$day = 7;
															}
															$agents_start_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day." and agent_id in (".implode(',',$agent_ids).") order by start_time asc limit 1");
															if(mysql_num_rows($agents_start_details) > 0){
																$agent_schedules_start=mysql_fetch_assoc($agents_start_details);
																$minute = date("i");
																$start_time= date('H',strtotime($agent_schedules_start['start_time']));
																if($minute < 15){
																	$minute = 15;
																	$start = date($start_time.":".$minute.":00");
																}else{
																	$modulus = ($minute%15);
																	$minute = ($minute-$modulus) + 15;
																	if($minute > 59){
																		$time=strtotime("+1 hour"); 
																		$start=date($start_time.":00:00",$time);  
																	}else{
																		//$start = date("H:".$minute.":00");
																		$start = $agent_schedules_start['start_time'];
																	}
																}
															}else{
																$minute = 15;
																$start = date("H:".$minute.":00");
																$noactiveday = 1;
															}
															$agents_end_details=mysql_query("SELECT * from agent_schedules where status=1 and day=".$day." and agent_id in (".implode(',',$agent_ids).") order by end_time asc limit 1");
															if(mysql_num_rows($agents_end_details) > 0){
																$agent_schedules_endtime=mysql_fetch_assoc($agents_end_details);
																if($intervals==5){
																	$intervals_time = $intervals * 3;
																	$end = date("H:i:00", strtotime("-".$intervals_time." minutes", strtotime($agent_schedules_endtime['end_time'])));
																}else{
																	$end = date("H:i:00", strtotime("-".$intervals." minutes", strtotime($agent_schedules_endtime['end_time'])));
																}
															}else{
																$end = "23:45:00";	
															}
															$select_time = '';
															if($todaydata == 0){
																$startfrom=date("H:i:s");
															}else{
																$startfrom = $start;
															}
															if($startfrom > $end || $noactiveday==1){
																$start = '';
																$end = '';
																$ko = $ko + 1;
															}else{
																$tStart = strtotime($start);
																$tEnd = strtotime($end);
																$tNow = $tStart;
																$select_time = '';
																while($tNow <= $tEnd){
																	$time = date("H:i:00",$tNow);
																	$select_time .= '<option value="'.$time.'">'.date("g:i A",$tNow).'</option>';
																	$tNow = strtotime('+'.$intervals.' minutes',$tNow);
																}
																$time_agent = sanitize_output(str_replace(array('"',"'"),"'",$select_time)); 
															}
														}
													}else{
														$day_agents = '<option value="">No Day Available</option>';
														$day_agent = sanitize_output(str_replace(array('"',"'"),"'",$day_agents));
														$select_time = '<option value="">No Time Available</option>';
														$time_agent = sanitize_output(str_replace(array('"',"'"),"'",$select_time));
													}
												}
												if($hide_widgets_working_hours==0){
													$widget_call=mysql_query("select * from widget_themes where widget_id = ".$widgets_info['id']." and company_id = ".$widgets_info['company_id']."");
													if(mysql_num_rows($widget_call) > 0){
														while($widget_call_info=mysql_fetch_assoc($widget_call)){
															if($widget_call_info['template_id']==1){
																$response = extract_emails_from($widget_call_info['description']);
																if(isset($response[0])){
																	foreach($response as $respon){
																		$tag = "<a href='mailto:".$respon."'>".$respon."</a>";
																		$widget_call_info['description'] = str_replace($respon,$tag,$widget_call_info['description']);
																	}
																	$description = $widget_call_info['description'];
																}else{
																	$description = $widget_call_info['description'];
																}
																$htmlpages = sanitize_output(str_replace(array('"',"'"),"'",$description));
																$buttonimage = $widget_call_info['imageicon'];
																$btn_class = $widget_call_info['btn_class'];
																$btn_color = $widget_call_info['btn_color'];
																$btn_border_color = $widget_call_info['btn_border_color'];
																$notification_msg = $widget_call_info['notification_msg'];
																$notification_msg_2 = $widget_call_info['notification_msg_2'];
																$speachbox_type = $widget_call_info['speachbox_type'];
															}else if($widget_call_info['template_id']==2){
																$response = extract_emails_from($widget_call_info['description']);
																if(isset($response[0])){
																	foreach($response as $respon){
																		$tag = "<a href='mailto:".$respon."'>".$respon."</a>";
																		$widget_call_info['description'] = str_replace($respon,$tag,$widget_call_info['description']);
																	}
																	$description = $widget_call_info['description'];
																}else{
																	$description = $widget_call_info['description'];
																}
																$widget_thanks_html = sanitize_output(str_replace(array('"',"'"),"'",$description));
															}else if($widget_call_info['template_id']==3){
																$response = extract_emails_from($widget_call_info['description']);
																if(isset($response[0])){
																	foreach($response as $respon){
																		$tag = "<a href='mailto:".$respon."'>".$respon."</a>";
																		$widget_call_info['description'] = str_replace($respon,$tag,$widget_call_info['description']);
																	}
																	$description = $widget_call_info['description'];
																}else{
																	$description = $widget_call_info['description'];
																}
																$widget_schedule_html = sanitize_output(str_replace(array('"',"'"),"'",$description));
															}else if($widget_call_info['template_id']==4){
																$response = extract_emails_from($widget_call_info['description']);
																if(isset($response[0])){
																	foreach($response as $respon){
																		$tag = "<a href='mailto:".$respon."'>".$respon."</a>";
																		$widget_call_info['description'] = str_replace($respon,$tag,$widget_call_info['description']);
																	}
																	$description = $widget_call_info['description'];
																}else{
																	$description = $widget_call_info['description'];
																}
																$widget_schedule_callback_html = sanitize_output(str_replace(array('"',"'"),"'",$description));
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					
					 if($widgets_info['install_status']==0){
						$companies=mysql_query("select * from companies where id = '".$widgets_info['company_id']."' and is_trashed= 0 and status= 1 and company_active_status= 1 and manual_install= 0");
						if(mysql_num_rows($companies) > 0){
							$content =  file_get_contents($widgets_info['widget_url']);
							
							
							
							
							$URL = $widgets_info['widget_url'];
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL,$URL);
						curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
						curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
						$result=curl_exec ($ch); 
						 
						curl_close ($ch);
													
							
							
							if (strpos($result,$_REQUEST['widget']) !== false || strpos($content,$_REQUEST['widget']) !== false){
								if($widgets_info['first_time_installation']==0){
									$companysettings=mysql_query("select * from company_settings where company_id = '".$widgets_info['company_id']."'");
									if(mysql_num_rows($companysettings) > 0){
										$results = mysql_fetch_assoc($companysettings);
										mysql_query("UPDATE company_settings SET next_renual_date='".date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+7, date("Y")))."' WHERE id = ".$results['id']."");
									}
								}
								
							
								
						
								mysql_query("UPDATE widgets SET first_time_installation=1,install_status=1,installed_mail_sent=1,installed_date='".date('Y-m-d H:i:s')."' WHERE id = ".$widgets_info['id']."");
								$company=mysql_query("select * from companies where id = '".$widgets_info['company_id']."' and is_trashed= 0 and status= 1 and company_active_status= 1");
								$num_rows = mysql_num_rows($company);
								if($num_rows > 0){
									$response = mysql_fetch_assoc($company);
									$widgetUrl = str_replace('http://','',$widgets_info['widget_url']);
									$widgetUrls = str_replace('https://','',$widgetUrl);
									$email_subject = $widgetUrls.' Congrats your responseiQ widget is installed';
									$email_message = "<p>Hello,</p>";
									$email_message .="<p>Congrats your responseiQ widget is installed and ready to generate calls on - ".$widgets_info['widget_url']."</p>";
									$email_message .="<br />";
									$email_message .="All the Best,<br />";
									$email_message .='The Response IQ Team';
								 	send_mail($response['email'],$response['name'],$email_subject,$email_message);
								}
							}else{
								if($widgets_info['company_id'] != 340 && $widgets_info['company_id'] !=  333){
								 	mysql_query("UPDATE widgets SET install_status=0 WHERE id = ".$widgets_info['id']."");
								}
							}
						}
					}
				}
			}
		}
	}
	if($htmlpages !=''){
		$show_tooltips = '';
		$speachbox_msg = '';
		if(($notification_msg !='') || ($notification_msg_2 !='')){
			if($onlineagents > 0){
				$speachbox_msg = $notification_msg;
			}else{
				$speachbox_msg = $notification_msg_2;
			}
		}
		if($speachbox_type==1){
			$speachbox_bgcolor = "riq_dlrtxtr riq_dlrtxtrwht";
		}else{  
			$speachbox_bgcolor = "riq_dlrtxtr";
		}
		if(($show_tooltip==1) && ($tooltip_close_click==0)){
			$show_tooltips  = "<div class='riq_box1' style='display:none;'><div class='".$speachbox_bgcolor."' id='riq_dlrtxtr'><button type='button' id='closeButton' class='close' data-dismiss='modal' aria-label='Close' onclick='return savetooltip_close_click()'>&times;</button><p id='riq_click_tooltip'>".$speachbox_msg."</p></div></div>";
		}
		if($company_id==282){
			//NORTHSSHORE
			if($btn_class=='lftbtm'){
				if($agents_agents_true==0){
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='opacity: 0; height: 100%; display: none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks' class='lftbtmicon'>".$widget_schedule_callback_html."</div>".$show_tooltips."<div class='dilricn2nw lftbtmicon' id='riq_dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/northshore.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}else{
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='opacity: 0; height: 100%; display: none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks' class='lftbtmicon'>".$htmlpages."</div>".$show_tooltips."<div class='dilricn2nw lftbtmicon' id='riq_dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/northshore.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}
			}else if($btn_class=='rgtbtm'){
				if($agents_agents_true==0){
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='opacity: 0; height: 100%; display: none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks'>".$widget_schedule_callback_html."</div>".$show_tooltips."<div class='dilricn2nw' id='riq_dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/northshore.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}else{
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='opacity: 0; height: 100%; display: none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks'>".$htmlpages."</div>".$show_tooltips."<div id='riq_dilricn2nw' class='dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/northshore.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}
			}
		}else{
			if($btn_class=='lftbtm'){
				if($agents_agents_true==0){
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='display:none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks' class='lftbtmicon'>".$widget_schedule_callback_html."</div>".$show_tooltips."<div class='dilricn2nw lftbtmicon' id='riq_dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/dilicnclnw.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}else{
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='display:none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks' class='lftbtmicon'>".$htmlpages."</div>".$show_tooltips."<div class='dilricn2nw lftbtmicon' id='riq_dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/dilicnclnw.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}
			}else if($btn_class=='rgtbtm'){
				if($agents_agents_true==0){
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='display:none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks'>".$widget_schedule_callback_html."</div>".$show_tooltips."<div class='dilricn2nw' id='riq_dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/dilicnclnw.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}else{
					$phone_icon = "<div id='ovt' class='vbox-overlay' style='display:none;'></div><div class='widpupnw ".$btn_class."'><div id='riq_showthanks'>".$htmlpages."</div>".$show_tooltips."<div id='riq_dilricn2nw' class='dilricn2nw' style='background:".$btn_color."'><img class='dilicn' src='".$siteurl."images/dilicnclnw.png' alt=''><img class='crssicn' src='".$siteurl."images/cross.png' alt=''></div></div>";
				}
			}
		}
		echo 'var btn_class = "'.$btn_class.'";';
		echo 'var siteurl = "'.$siteurl.'";';
		echo "var allow_widgets_url = '".json_encode($allow_widgets_url)."';";
		echo "var disallow_widgets_url = '".json_encode($disallow_widgets_url)."';";
		echo 'var widget = "'.$_REQUEST['widget'].'";';
		echo 'var pagetitle = "";';
		echo 'var htmlpage = "'.$htmlpages.'";';
		echo 'var widget_schedule_html = "'.$widget_schedule_html.'";';
		echo 'var widget_thanks_html = "'.$widget_thanks_html.'";';
		echo 'var agentslist = "'.$agentslist.'";';
		echo 'var widget_schedule_callback_html = "'.$widget_schedule_callback_html.'";';
		echo 'var phonecode = "+'.$phonecode.'";';
		
			//IP api fixes
	
			echo 'var location_ip = "'.$ip_data->country.'";';
		
		echo 'var city = "'.$ip_data->city.'";';
		echo 'var region = "'.$ip_data->region.'";';
		echo 'var timezone = "'.$ip_data->timezone.'";';
		echo 'var country_name = "'.$ip_data->country_name.'";';
		echo 'var country = "'.$ip_data->country.'";';
	 
		
	
	 
		
		
		echo 'var location_ip = "'.$location.'";';
		echo 'var phone_icon = "'.$phone_icon.'";';
		echo 'var agents_agents_true = "'.$agents_agents_true.'";';
		echo 'var day_agent = "'.$day_agent.'";';
		echo 'var time_agent = "'.$time_agent.'";';
		if($onlineagents ==0){
			echo 'var delay_icon_second = '.$delay_icon_out_second.';';
		}else{
			echo 'var delay_icon_second = '.$delay_icon_second.';';
		}
		echo 'var enable_sounds = '.$enable_sounds.';';
		// echo 'var total_connected_calls = "'.$total_connected_calls.'";';
		echo 'var onlineagents = "'.$onlineagents.'";';
		echo 'var show_opt_count = "'.$show_opt_count.'";';
		echo 'var welcometext_animate_1 = "'.$welcometext_animate_1.'";';
		echo 'var welcometextschedule_animate_2 = "'.$welcometextschedule_animate_2.'";';
		// echo 'var total_scheduled_calls = "'.$total_scheduled_calls.'";';
		echo 'var btn_border_color = "'.$btn_border_color.'";';
		echo 'var btn_color = "'.$btn_color.'";';
		echo 'var ipaddress = "'.$ipaddress.'";';
		echo 'var tooltip_close = "'.$tooltip_close.'";';
		echo 'var tooltip_close_click = "'.$tooltip_close_click.'";';
		if($onlineagents ==0){
			if($out_mobile_popup==0){
				$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
				$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
				$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
				if($iPhone){
					echo 'var time_check = "0";';
				}else if($iPad){
					echo 'var time_check = "0";';
				}else if($Android){	
					echo 'var time_check = "0";';
				}else{
					echo 'var time_check = "'.$outhours_time_check.'";';
				}
			}else{
				echo 'var time_check = "'.$outhours_time_check.'";';
			}
			echo 'var time_on_website = "'.$outhours_time_on_website.'";';
			echo 'var show_tooltip = "'.$outhours_show_tooltip.'";';
			echo 'var attempts_to_exit = "'.$outhours_attempts_to_exit.'";';
			echo 'var delay_before_minimising_popup = "'.$out_delay_before_minimising_popup.'";';
		}else{
			if($live_mobile_popup==0){
				$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
				$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
				$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
				if($iPhone){
					echo 'var time_check = "0";';
				}else if($iPad){
					echo 'var time_check = "0";';
				}else if($Android){	
					echo 'var time_check = "0";';
				}else{
					echo 'var time_check = "'.$time_check.'";';
				}
			}else{
				echo 'var time_check = "'.$time_check.'";';
			}
			echo 'var time_on_website = "'.$time_on_website.'";';
			echo 'var show_tooltip = "'.$show_tooltip.'";';
			echo 'var attempts_to_exit = "'.$attempts_to_exit.'";';
			echo 'var delay_before_minimising_popup = "'.$live_delay_before_minimising_popup.'";';
		}
		echo 'var saveattempts_to_exit = "'.$saveattempts_to_exit.'";';
		echo 'var tooltip_animation = "'.$tooltip_animation.'";';
		echo 'var samesession = "'.$samesession.'";';
		echo 'var browsersession = "'.$_SESSION['browsersession'].'";';
		echo 'var is_type = "'.$is_type.'";';
		echo 'var company_id = "'.$company_id.'";';
		echo 'var selector = "'.$selector.'";';
		echo 'var hide_after_closing_popup = "'.$hide_after_closing_popup.'";';
		echo 'var currenttimezone = "'.$currenttimezone.'";';
		echo 'var template_id = "'.$template_id.'";';
		echo 'var template_capture_number = "'.$template_capture_number.'";';
		echo 'var call_schedule_button_enable = "'.$call_schedule_button_enable.'";';
		echo 'var select_visitor_time_enabled = "'.$select_visitor_time_enabled.'";';
		//356
		/* 	if($widgets_info['company_id'] == 328 || $widgets_info['company_id'] == 356 || $widgets_info['company_id'] == 357 || $widgets_info['company_id'] == 295 || $widgets_info['company_id'] == 333|| $widgets_info['company_id'] == 328 ){
			echo 'var noFlag = 1;';
			//wiki
			if($widgets_info['company_id'] == 333 || $widgets_info['company_id'] == 328){
				echo 'var showCountryTextbox = 1;';
			}else{
				echo 'var showCountryTextbox = 0;';
			}
		}else{
			echo 'var noFlag = 0;';
		} */
		echo 'var showCountryTextbox = 1;';
		echo 'var noFlag = 1;';
		if($onlineagents ==0){
			echo 'var hide_widgets_background = "'.$hide_widgets_background_out.'";';
		}else{
			echo 'var hide_widgets_background = "'.$hide_widgets_background.'";';
		}
		if($onlineagents ==0){
			echo 'var tooltip_reload_page = "'.$hide_after_closing_popup_out_reload.'";';
		}else{
			echo 'var tooltip_reload_page = "'.$hide_after_closing_popup_reload.'";';
		}
		/* if($company_id == 295){
			include("widgetsjs/spiritdentalwidgets.js");
		}else if($company_id == 282){
			include("widgetsjs/widgetswithoutflags.js");
		}else{
			include("widgetsjs/widgets.js");
		} */
		include("widgetsjs/widgets.js");
	}
}
