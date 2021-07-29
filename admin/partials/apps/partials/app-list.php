<?php
require_once('defaultapps.php');
function applist_page() {
	// tutorial();
	?>
	<style>
		.tableborder {
			border-collapse: collapse;
			width: 100%;
			border-color:#eee;
		}

		.tableborder th, .tableborder td {
			text-align: left;
			padding: 8px;
			border-color:#eee;
		}

		.tableborder tr:nth-child(even){background-color: #f2f2f2}
	</style>
	<div id="mo_oauth_app_list" class="mo_table_layout">
	<?php

		if(isset($_GET['action']) && $_GET['action']=='delete'){
			if(isset($_GET['app']))
				delete_app($_GET['app']);
		} else if(isset($_GET['action']) && $_GET['action']=='instructions'){
			if(isset($_GET['appId'])){
				Mo_OAuth_Client_Admin_Guides::instructions($_GET['appId']);
			}
		}

		if(isset($_GET['action']) && $_GET['action']=='add'){
			Mo_OAuth_Client_Admin_Apps::add_app();
		}
		else if(isset($_GET['action']) && $_GET['action']=='update'){
			if(isset($_GET['app']))
				Mo_OAuth_Client_Admin_Apps::update_app($_GET['app']);
		}
		else if(get_option('mo_oauth_apps_list'))
		{
			$appslist = get_option('mo_oauth_apps_list');
			/*if(sizeof($appslist)>0)
				echo "<br><a href='#'><button disabled style='float:right'>Add Application</button></a>";
			else
				echo "<br><a href='admin.php?page=mo_oauth_settings&action=add'><button style='float:right'>Add Application</button></a>";*/
			echo "<h3>Applications List</h3>";

			echo "<table class='tableborder'>";
			echo "<tr><th><b>Name</b></th><th>Action</th></tr>";
			foreach($appslist as $key => $app){
				$currentapp=$app;
				echo "<tr><td>".$key, " (", $currentapp['apptype'], ") "."</td><td><a href='admin.php?page=mo_oauth_settings&tab=config&action=update&app=".$key."'>Edit Application</a> | <a onclick='return confirm(\"Are you sure you want to delete this item?\")' href='admin.php?page=mo_oauth_settings&tab=config&action=delete&app=".$key."'>Delete</a>";
				/*if(isset($_GET['action'])) {
					if($_GET['action'] == 'instructions') {
					echo "<a href='admin.php?page=mo_oauth_settings&tab=config'>Hide Instructions</a></td></tr>";
					}
				} else {
					echo "<a href='admin.php?page=mo_oauth_settings&tab=config&action=instructions&appId=".((isset($app['appId']) ? $app['appId'] : ''))."'>How to Configure?</a></td></tr>";
				}*/

			}
			echo "</table>";
			echo "<br><br>";

		} else {
			Mo_OAuth_Client_Admin_Apps::add_app();
		 } ?>
		</div>
	<?php
	if(get_option('mo_oauth_eveonline_enable'))
		show_config_old();
}



	function delete_app($appname){
		$appslist = get_option('mo_oauth_apps_list');
		foreach($appslist as $key => $app){
			if($appname == $key){		
				if( $appslist[$appname]['appId'] == 'wso2' )
					delete_option( 'mo_oauth_client_custom_token_endpoint_no_csecret' );
				unset($appslist[$key]);
				delete_option( 'mo_oauth_attr_name_list' );
				if($appname=="eveonline")
					update_option( 'mo_oauth_eveonline_enable', 0);
				delete_option('mo_oauth_apps_list');
			}
		}
		update_option('mo_oauth_apps_list', $appslist);

		$appslist=get_option('mo_openidconnect_apps_list');

		if($appslist){
			foreach ($appslist as $key => $value) {
				if($appname==$key)
				{
					unset($appslist[$key]);
					break;
				}
			}
		update_option('mo_openidconnect_apps_list',$appslist);
	}
	}
