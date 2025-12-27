<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
	class renderer
	{
		function get_icon($category)
		{
			global  $path_to_root, $SysPrefs;

			if ($SysPrefs->show_menu_category_icons)
				$img = $category == '' ? 'right.gif' : $category.'.png';
			else	
				$img = 'right.gif';
			return "<img src='$path_to_root/themes/".user_theme()."/images/$img' style='vertical-align:middle;' border='0'>&nbsp;&nbsp;";
		}
		
		// Get Font Awesome icon for each FrontAccounting application
		// Using Font Awesome 6 (https://fontawesome.com)
		function get_app_icon($app_id, $icon_class = '')
		{
			$icons = array(
				// Sales - Shopping Cart
				'orders' => '<i class="fas fa-shopping-cart '.$icon_class.'"></i>',
				
				// Purchases - Clipboard List
				'AP' => '<i class="fas fa-clipboard-list '.$icon_class.'"></i>',
				
				// Items and Inventory - Cubes
				'stock' => '<i class="fas fa-cubes '.$icon_class.'"></i>',
				
				// Manufacturing - Tools
				'manuf' => '<i class="fas fa-tools '.$icon_class.'"></i>',
				
				// Fixed Assets - Building
				'assets' => '<i class="fas fa-building '.$icon_class.'"></i>',
				
				// Dimensions - Layer Group
				'proj' => '<i class="fas fa-layer-group '.$icon_class.'"></i>',
				
				// Banking and General Ledger - Money Bill Wave
				'GL' => '<i class="fas fa-money-bill-wave '.$icon_class.'"></i>',
				
				// Setup - Cog
				'system' => '<i class="fas fa-cog '.$icon_class.'"></i>',
				
				// Default - Grid
				'default' => '<i class="fas fa-th '.$icon_class.'"></i>'
			);
			
			return isset($icons[$app_id]) ? $icons[$app_id] : $icons['default'];
		}
		
		// Helper function to check if a menu link matches the current page
		function is_menu_link_active($link, $current_script, $current_params) {
			if (empty($link)) return false;
			
			// Parse the link
			$link_parts = explode('?', $link);
			$link_script = basename($link_parts[0]);
			
			// First check: filename must match
			if ($link_script !== $current_script) {
				return false;
			}
			
			// If link has no query params, just match by filename
			if (!isset($link_parts[1]) || empty($link_parts[1])) {
				return true;
			}
			
			// Parse link query params
			parse_str($link_parts[1], $link_params);
			
			// Check if all link params exist in current params with same values
			foreach ($link_params as $key => $value) {
				if (!isset($current_params[$key]) || $current_params[$key] != $value) {
					return false;
				}
			}
			
			return true;
		}

		// Get shortcuts for the current application
		function get_app_shortcuts($app_id) 
		{
			$shortcuts = array(
				'orders' => array(
					array('link' => 'sales/sales_order_entry.php?NewOrder=Yes', 'label' => _('Sales Order')),
					array('link' => 'sales/sales_order_entry.php?NewInvoice=0', 'label' => _('Direct Invoice')),
					array('link' => 'sales/customer_payments.php?', 'label' => _('Payments')),
					array('link' => 'sales/inquiry/sales_orders_view.php?', 'label' => _('Sales Order Inquiry')),
					array('link' => 'sales/inquiry/customer_inquiry.php?', 'label' => _('Transactions')),
					array('link' => 'sales/manage/customers.php?', 'label' => _('Customers')),
					array('link' => 'sales/manage/customer_branches.php?', 'label' => _('Branch')),
					array('link' => 'reporting/reports_main.php?Class=0', 'label' => _('Reports and Analysis')),
				),
				'AP' => array(
					array('link' => 'purchasing/po_entry_items.php?NewOrder=0', 'label' => _('Purchase Order')),
					array('link' => 'purchasing/inquiry/po_search.php?', 'label' => _('Receive')),
					array('link' => 'purchasing/supplier_invoice.php?New=1', 'label' => _('Supplier Invoice')),
					array('link' => 'purchasing/supplier_payment.php?', 'label' => _('Payments')),
					array('link' => 'purchasing/inquiry/supplier_inquiry.php?', 'label' => _('Transactions')),
					array('link' => 'purchasing/manage/suppliers.php?', 'label' => _('Suppliers')),
					array('link' => 'reporting/reports_main.php?Class=1', 'label' => _('Reports and Analysis')),
				),
				'stock' => array(
					array('link' => 'inventory/adjustments.php?NewAdjustment=1', 'label' => _('Inventory Adjustments')),
					array('link' => 'inventory/inquiry/stock_movements.php?', 'label' => _('Inventory Movements')),
					array('link' => 'inventory/manage/items.php?', 'label' => _('Items')),
					array('link' => 'inventory/prices.php?', 'label' => _('Sales Pricing')),
					array('link' => 'reporting/reports_main.php?Class=2', 'label' => _('Reports and Analysis')),
				),
				'manuf' => array(
					array('link' => 'manufacturing/work_order_entry.php?', 'label' => _('Work Order Entry')),
					array('link' => 'manufacturing/search_work_orders.php?outstanding_only=1', 'label' => _('Outstanding Work Orders')),
					array('link' => 'manufacturing/search_work_orders.php?', 'label' => _('Work Order Inquiry')),
					array('link' => 'manufacturing/manage/bom_edit.php?', 'label' => _('Bills Of Material')),
					array('link' => 'reporting/reports_main.php?Class=3', 'label' => _('Reports and Analysis')),
				),
				'assets' => array(
					array('link' => 'purchasing/po_entry_items.php?NewInvoice=Yes&FixedAsset=1', 'label' => _('Fixed Assets Purchase')),
					array('link' => 'fixed_assets/inquiry/stock_inquiry.php?', 'label' => _('Fixed Assets Inquiry')),
					array('link' => 'inventory/manage/items.php?FixedAsset=1', 'label' => _('Fixed Assets')),
					array('link' => 'fixed_assets/process_depreciation.php?', 'label' => _('Depreciations')),
					array('link' => 'reporting/reports_main.php?Class=7', 'label' => _('Reports and Analysis')),
				),
				'proj' => array(
					array('link' => 'dimensions/dimension_entry.php?', 'label' => _('Dimension Entry')),
					array('link' => 'dimensions/inquiry/search_dimensions.php?', 'label' => _('Dimension Inquiry')),
					array('link' => 'reporting/reports_main.php?Class=4', 'label' => _('Reports and Analysis')),
				),
				'GL' => array(
					array('link' => 'gl/gl_bank.php?NewPayment=Yes', 'label' => _('Payments')),
					array('link' => 'gl/gl_bank.php?NewDeposit=Yes', 'label' => _('Deposits')),
					array('link' => 'gl/gl_journal.php?NewJournal=Yes', 'label' => _('Journal Entry')),
					array('link' => 'gl/inquiry/bank_inquiry.php?', 'label' => _('Bank Account Inquiry')),
					array('link' => 'gl/inquiry/gl_trial_balance.php?', 'label' => _('Trial Balance')),
					array('link' => 'gl/manage/exchange_rates.php?', 'label' => _('Exchange Rates')),
					array('link' => 'gl/manage/gl_accounts.php?', 'label' => _('GL Accounts')),
					array('link' => 'reporting/reports_main.php?Class=6', 'label' => _('Reports and Analysis')),
				),
				'system' => array(
					array('link' => 'admin/company_preferences.php?', 'label' => _('Company Setup')),
					array('link' => 'admin/gl_setup.php?', 'label' => _('General GL')),
					array('link' => 'taxes/tax_types.php?', 'label' => _('Taxes')),
					array('link' => 'taxes/tax_groups.php?', 'label' => _('Tax Groups')),
					array('link' => 'admin/forms_setup.php?', 'label' => _('Forms Setup')),
					array('link' => 'admin/backups.php?', 'label' => _('Backup and Restore')),
				),
			);
			
			return isset($shortcuts[$app_id]) ? $shortcuts[$app_id] : array();
		}

		function wa_header()
		{
			page(_($help_context = "Main Menu"), false, true);
		}

		function wa_footer()
		{
			end_page(false, true);
		}

		function menu_header($title, $no_menu, $is_index)
		{
			global $path_to_root, $SysPrefs, $db_connections;
			
			$theme_path = $path_to_root . "/themes/".user_theme();
			$indicator = "$path_to_root/themes/".user_theme(). "/images/ajax-loader.gif";
			
			// Load AdminLTE 3.2, Bootstrap 4, jQuery, and Font Awesome from local files
			// Note: AdminLTE 3.2 uses Bootstrap 4
			echo "<!-- AdminLTE 3.2 Theme -->\n";
			echo "<!-- Bootstrap 4 CSS -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/bootstrap/bootstrap4.min.css\">\n";
			echo "<!-- Font Awesome 6 -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/fontawesome/all.min.css\">\n";
			echo "<!-- AdminLTE 3.2 CSS -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/adminlte/adminlte.min.css\">\n";
			echo "<!-- Custom CSS -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/default.css\">\n";
			
			// Start AdminLTE layout structure
			// Note: AdminLTE 3.2 doesn't have built-in dark-mode class, we'll use custom CSS
			$darkMode = '';
			if (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == 'true') {
				$darkMode = 'dark-mode';
			} elseif (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == 'false') {
				$darkMode = '';
			}
			echo "<body class=\"hold-transition sidebar-mini layout-fixed $darkMode\">\n";
			echo "<div class=\"wrapper\">\n";
			
			if (!$no_menu)
			{
				// ===== Main Sidebar Start =====
				echo "<!-- Main Sidebar -->\n";
				echo "<aside class=\"main-sidebar sidebar-dark-primary elevation-4\">\n";
				
				// Sidebar Brand/Logo
				echo "<!-- Brand Logo -->\n";
				echo "<a href=\"$path_to_root/index.php\" class=\"brand-link\">\n";
				echo "<img src=\"$theme_path/images/logo/logo-icon.svg\" alt=\"FrontAccounting Logo\" class=\"brand-image img-circle elevation-3\" style=\"opacity: .8\">\n";
				echo "<span class=\"brand-text font-weight-light\">FrontAccounting</span>\n";
				echo "</a>\n";
				
				// Sidebar Menu
				echo "<!-- Sidebar -->\n";
				echo "<div class=\"sidebar\">\n";
				
				// User Panel (optional - can show user info)
				echo "<!-- Sidebar user panel (optional) -->\n";
				echo "<div class=\"user-panel mt-3 pb-3 mb-3 d-flex\">\n";
				$user_initials = strtoupper(substr($_SESSION["wa_current_user"]->name, 0, 1));
				echo "<div class=\"image\">\n";
				echo "<div class=\"img-circle elevation-2 bg-info\" style=\"width: 2.1rem; height: 2.1rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;\">$user_initials</div>\n";
				echo "</div>\n";
				echo "<div class=\"info\">\n";
				echo "<a href=\"#\" class=\"d-block\">".$_SESSION["wa_current_user"]->name."</a>\n";
				echo "</div>\n";
				echo "</div>\n";
				
				// Sidebar Menu
				echo "<!-- Sidebar Menu -->\n";
				echo "<nav class=\"mt-2\">\n";
				echo "<ul class=\"nav nav-pills nav-sidebar flex-column\" data-widget=\"treeview\" role=\"menu\" data-accordion=\"false\">\n";
				
				// Get applications and render menu
				$applications = $_SESSION['App']->applications;
				$sel_app = $_SESSION['sel_app'];
				
				// Get current page for active menu detection
				$current_page = $_SERVER['PHP_SELF'];
				$current_query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
				$current_script = basename($current_page);
				parse_str($current_query, $current_params);
				
				// Find which app contains the current page
				$active_app_key = '';
				foreach($applications as $app) {
					if (isset($app->modules) && is_array($app->modules)) {
						foreach ($app->modules as $module) {
							if (isset($module->lappfunctions)) {
								foreach ($module->lappfunctions as $appfunction) {
									if ($this->is_menu_link_active($appfunction->link, $current_script, $current_params)) {
										$active_app_key = 'App_' . $app->id;
										break 3;
									}
								}
							}
							if (isset($module->rappfunctions)) {
								foreach ($module->rappfunctions as $appfunction) {
									if ($this->is_menu_link_active($appfunction->link, $current_script, $current_params)) {
										$active_app_key = 'App_' . $app->id;
										break 3;
									}
								}
							}
						}
					}
				}
				
				// Render menu items
				foreach($applications as $app)
				{
					if ($_SESSION["wa_current_user"]->check_application_access($app))
					{
						$acc = access_string($app->name);
						$app_key = 'App_' . $app->id;
						$is_active = ($sel_app == $app->id) || ($active_app_key == $app_key);
						
						// Check if app has modules
						$has_modules = false;
						if (isset($app->modules) && is_array($app->modules)) {
							foreach ($app->modules as $module) {
								if ($_SESSION["wa_current_user"]->check_module_access($module)) {
									$has_modules = true;
									break;
								}
							}
						}
						
						// Override display name for GL to shorten it
						$app_name = $app->name;
						if ($app->id == 'GL') {
							$app_name = str_replace('&Banking and General Ledger', '&Banking & General Ledger', $app_name);
							$app_name = str_replace('Banking and General Ledger', 'Banking & General Ledger', $app_name);
						}
						$acc = access_string($app_name);
						// Also replace after access_string() in case HTML tags were added
						if ($app->id == 'GL') {
							$acc[0] = str_replace('Banking and General Ledger', 'Banking & General Ledger', $acc[0]);
							$acc[0] = preg_replace('/(<u>)?B(<\/u>)?anking and General Ledger/', '$1B$2anking & General Ledger', $acc[0]);
						}
						
						// Determine if menu should be expanded
						$menu_open = ($active_app_key == $app_key || $is_active) ? 'menu-open' : '';
						$menu_expanded = ($active_app_key == $app_key || $is_active) ? 'true' : 'false';
						
						if ($has_modules) {
							// Menu item with treeview (has submenus)
							// Use AdminLTE treeview structure with custom split-click behavior
							echo "<li class=\"nav-item has-treeview $menu_open\" data-app-key=\"$app_key\">\n";
							
							// Main link - will handle navigation and show arrow
							$arrow_icon = ($menu_open == 'menu-open') ? 'fa-angle-down' : 'fa-angle-left';
							echo "<a href=\"$path_to_root/index.php?application=".$app->id."\" class=\"nav-link app-menu-link\" data-app-id=\"".$app->id."\">\n";
							echo $this->get_app_icon($app->id, 'nav-icon');
							echo "<p>\n";
							echo "<span class=\"app-menu-text\">".$acc[0]."</span>\n";
							echo "<i class=\"right fas $arrow_icon\"></i>\n";
							echo "</p>\n";
							echo "</a>\n";
							
							// Submenu
							echo "<ul class=\"nav nav-treeview\">\n";
							
							$module_index = 0;
							foreach ($app->modules as $module)
							{
								if ($_SESSION["wa_current_user"]->check_module_access($module))
								{
									// Show module group header
									if (!empty($module->name)) {
										if ($module_index > 0) {
											echo "<li class=\"nav-header\">".$module->name."</li>\n";
										} else {
											echo "<li class=\"nav-header\" style=\"padding-top: 0;\">".$module->name."</li>\n";
										}
									}
									$module_index++;
									
									// Render left app functions
									foreach ($module->lappfunctions as $appfunction)
									{
										if ($appfunction->label == "")
											continue;
										elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access))
										{
											$acc_func = access_string($appfunction->label);
											$is_active_item = $this->is_menu_link_active($appfunction->link, $current_script, $current_params);
											$item_class = $is_active_item ? 'active' : '';
											echo "<li class=\"nav-item\">\n";
											echo "<a href=\"$path_to_root/".$appfunction->link."\" class=\"nav-link $item_class\">\n";
											$img = $this->get_icon($appfunction->category);
											echo "<i class=\"far fa-circle nav-icon\"></i>\n";
											echo "<p>".$acc_func[0]."</p>\n";
											echo "</a>\n";
											echo "</li>\n";
										}
										elseif (!$_SESSION["wa_current_user"]->hide_inaccessible_menu_items())
										{
											echo "<li class=\"nav-item\">\n";
											echo "<span class=\"nav-link disabled\">".access_string($appfunction->label, true)."</span>\n";
											echo "</li>\n";
										}
									}
									
									// Render right app functions
									if (isset($module->rappfunctions) && sizeof($module->rappfunctions) > 0)
									{
										foreach ($module->rappfunctions as $appfunction)
										{
											if ($appfunction->label == "")
												continue;
											elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access))
											{
												$acc_func = access_string($appfunction->label);
												$is_active_item = $this->is_menu_link_active($appfunction->link, $current_script, $current_params);
												$item_class = $is_active_item ? 'active' : '';
												echo "<li class=\"nav-item\">\n";
												echo "<a href=\"$path_to_root/".$appfunction->link."\" class=\"nav-link $item_class\">\n";
												$img = $this->get_icon($appfunction->category);
												echo "<i class=\"far fa-circle nav-icon\"></i>\n";
												echo "<p>".$acc_func[0]."</p>\n";
												echo "</a>\n";
												echo "</li>\n";
											}
											elseif (!$_SESSION["wa_current_user"]->hide_inaccessible_menu_items())
											{
												echo "<li class=\"nav-item\">\n";
												echo "<span class=\"nav-link disabled\">".access_string($appfunction->label, true)."</span>\n";
												echo "</li>\n";
											}
										}
									}
								}
							}
							
							echo "</ul>\n";
							echo "</li>\n";
						} else {
							// Simple menu item (no submenus)
							$item_class = $is_active ? 'active' : '';
							echo "<li class=\"nav-item\">\n";
							echo "<a href=\"$path_to_root/index.php?application=".$app->id."\" class=\"nav-link $item_class\">\n";
							echo $this->get_app_icon($app->id, 'nav-icon');
							echo "<p>".$acc[0]."</p>\n";
							echo "</a>\n";
							echo "</li>\n";
						}
					}
				}
				
				echo "</ul>\n";
				echo "</nav>\n";
				echo "<!-- /.sidebar-menu -->\n";
				echo "</div>\n";
				echo "<!-- /.sidebar -->\n";
				echo "</aside>\n";
				// ===== Main Sidebar End =====
				
				// ===== Main Header Start =====
				echo "<!-- Main Header -->\n";
				echo "<nav class=\"main-header navbar navbar-expand navbar-white navbar-light\">\n";
				// Left navbar links
				echo "<ul class=\"navbar-nav\">\n";
				echo "<li class=\"nav-item\">\n";
				echo "<a class=\"nav-link\" data-widget=\"pushmenu\" href=\"#\" role=\"button\"><i class=\"fas fa-bars\"></i></a>\n";
				echo "</li>\n";
				echo "</ul>\n";
				
				// Right navbar links
				echo "<ul class=\"navbar-nav ml-auto\">\n";
				
				// Dark mode toggle
				echo "<li class=\"nav-item\">\n";
				echo "<a class=\"nav-link\" href=\"#\" id=\"darkModeToggle\" role=\"button\">\n";
				echo "<i class=\"fas fa-moon\" id=\"darkModeIcon\"></i>\n";
				echo "</a>\n";
				echo "</li>\n";
				
				// User dropdown
				echo "<li class=\"nav-item dropdown\">\n";
				echo "<a class=\"nav-link\" data-toggle=\"dropdown\" href=\"#\">\n";
				$user_initials = strtoupper(substr($_SESSION["wa_current_user"]->name, 0, 1));
				echo "<span class=\"badge badge-warning navbar-badge\">$user_initials</span>\n";
				echo "</a>\n";
				echo "<div class=\"dropdown-menu dropdown-menu-lg dropdown-menu-right\">\n";
				echo "<span class=\"dropdown-item dropdown-header\">".$_SESSION["wa_current_user"]->name."</span>\n";
				echo "<div class=\"dropdown-divider\"></div>\n";
				echo "<a href=\"$path_to_root/admin/display_prefs.php?\" class=\"dropdown-item\">\n";
				echo "<i class=\"fas fa-user-cog mr-2\"></i> "._("Preferences")."\n";
				echo "</a>\n";
				echo "<a href=\"$path_to_root/admin/change_current_user_password.php?selected_id=".$_SESSION["wa_current_user"]->username."\" class=\"dropdown-item\">\n";
				echo "<i class=\"fas fa-key mr-2\"></i> "._("Change password")."\n";
				echo "</a>\n";
				if ($SysPrefs->help_base_url != null) {
					echo "<a target=\"_blank\" onclick=\"javascript:openWindow(this.href,this.target); return false;\" href=\"".help_url()."\" class=\"dropdown-item\">\n";
					echo "<i class=\"fas fa-question-circle mr-2\"></i> "._("Help")."\n";
					echo "</a>\n";
				}
				echo "<div class=\"dropdown-divider\"></div>\n";
				echo "<a href=\"$path_to_root/access/logout.php?\" class=\"dropdown-item dropdown-footer\">\n";
				echo "<i class=\"fas fa-sign-out-alt mr-2\"></i> "._("Sign out")."\n";
				echo "</a>\n";
				echo "</div>\n";
				echo "</li>\n";
				
				echo "</ul>\n";
				echo "</nav>\n";
				// ===== Main Header End =====
				
				// ===== Content Wrapper Start =====
				echo "<!-- Content Wrapper -->\n";
				echo "<div class=\"content-wrapper\">\n";
				
				// ===== Content Header =====
				if ($title && !$is_index)
				{
					echo "<!-- Content Header -->\n";
					echo "<div class=\"content-header\">\n";
					echo "<div class=\"container-fluid\">\n";
					echo "<div class=\"row mb-2\">\n";
					echo "<div class=\"col-sm-6\">\n";
					echo "<h1 class=\"m-0\">$title</h1>\n";
					if (user_hints())
						echo "<small id='hints'></small>\n";
					echo "</div>\n";
					echo "</div>\n";
					echo "</div>\n";
					echo "</div>\n";
				}
				
				// ===== Main Content Start =====
				echo "<!-- Main content -->\n";
				echo "<section class=\"content\">\n";
				echo "<div class=\"container-fluid\">\n";
				
				// AJAX Indicator
				echo "<img id='ajaxmark' src='$indicator' align='center' style='visibility:hidden;position:fixed;top:50%;left:50%;z-index:99999;' alt='ajaxmark'>\n";
			}
			else
			{
				// No menu mode (installer, popups)
				echo "<!-- Content Wrapper -->\n";
				echo "<div class=\"content-wrapper\">\n";
				echo "<!-- Main content -->\n";
				echo "<section class=\"content\">\n";
				echo "<div class=\"container-fluid\">\n";
				echo "<center><img id='ajaxmark' src='$indicator' align='center' style='visibility:hidden;' alt='ajaxmark'></center>\n";
			}
		}

		function menu_footer($no_menu, $is_index)
		{
			global $version, $path_to_root, $Pagehelp, $Ajax, $SysPrefs;

			include_once($path_to_root . "/includes/date_functions.inc");

			if ($no_menu == false)
			{
				// Keyboard shortcuts card
				$phelp = implode('; ', $Pagehelp);
				$Ajax->addUpdate(true, 'hotkeyshelp', $phelp);
				
				echo "<div class=\"card mt-3\">\n";
				echo "<div class=\"card-body text-center\">\n";
				echo "<div id='hotkeyshelp' class=\"text-muted small\">".$phelp."</div>\n";
				echo "</div>\n";
				echo "</div>\n";
				
				echo "</div>\n"; // container-fluid
				echo "</section>\n";
				echo "<!-- /.content -->\n";
				echo "</div>\n";
				echo "<!-- /.content-wrapper -->\n";
				
				// Footer
				echo "<!-- Main Footer -->\n";
				echo "<footer class=\"main-footer\">\n";
				echo "<strong>".$SysPrefs->app_title." $version</strong> - "._("Theme:")." ".user_theme()." - ".show_users_online();
				echo "<div class=\"float-right d-none d-sm-inline\">\n";
				echo Today()." | ".Now()."\n";
				echo "</div>\n";
				echo "<br />\n";
				echo "<a target='_blank' href='".$SysPrefs->power_url."' class=\"text-muted\">";
				echo $SysPrefs->power_by;
				echo "</a>\n";
				echo "</footer>\n";
			}
			
			echo "</div>\n"; // wrapper
			echo "<!-- ./wrapper -->\n";
			
			// Load JavaScript libraries
			$theme_path = $path_to_root . "/themes/".user_theme();
			echo "<!-- jQuery -->\n";
			echo "<script src=\"$theme_path/vendor/jquery/jquery3.6.min.js\"></script>\n";
			echo "<!-- Popper.js (required for Bootstrap 4) -->\n";
			echo "<script src=\"$theme_path/vendor/bootstrap/popper.min.js\"></script>\n";
			echo "<!-- Bootstrap 4 JS -->\n";
			echo "<script src=\"$theme_path/vendor/bootstrap/bootstrap4.min.js\"></script>\n";
			echo "<!-- AdminLTE 3.2 JS -->\n";
			echo "<script src=\"$theme_path/vendor/adminlte/adminlte.min.js\"></script>\n";
			
			// Custom JavaScript for theme functionality
			echo "<script>\n";
			echo "(function() {\n";
			echo "  // Check for saved dark mode preference or default to light mode\n";
			echo "  const darkMode = localStorage.getItem('darkMode') === 'true' || document.body.classList.contains('dark-mode');\n";
			echo "  if (darkMode) {\n";
			echo "    document.body.classList.add('dark-mode');\n";
			echo "    var icon = document.getElementById('darkModeIcon');\n";
			echo "    if (icon) {\n";
			echo "      icon.classList.remove('fa-moon');\n";
			echo "      icon.classList.add('fa-sun');\n";
			echo "    }\n";
			echo "  }\n";
			echo "  \n";
			echo "  // Dark mode toggle handler\n";
			echo "  var darkModeToggle = document.getElementById('darkModeToggle');\n";
			echo "  if (darkModeToggle) {\n";
			echo "    darkModeToggle.addEventListener('click', function(e) {\n";
			echo "      e.preventDefault();\n";
			echo "      document.body.classList.toggle('dark-mode');\n";
			echo "      const isDark = document.body.classList.contains('dark-mode');\n";
			echo "      localStorage.setItem('darkMode', isDark);\n";
			echo "      const icon = document.getElementById('darkModeIcon');\n";
			echo "      if (icon) {\n";
			echo "        if (isDark) {\n";
			echo "          icon.classList.remove('fa-moon');\n";
			echo "          icon.classList.add('fa-sun');\n";
			echo "        } else {\n";
			echo "          icon.classList.remove('fa-sun');\n";
			echo "          icon.classList.add('fa-moon');\n";
			echo "        }\n";
			echo "      }\n";
			echo "    });\n";
			echo "  }\n";
			echo "  \n";
			echo "  // Split click behavior for sidebar menu items with treeview\n";
			echo "  // Clicking on text navigates, clicking on arrow toggles\n";
			echo "  $(document).ready(function() {\n";
			echo "    $('.app-menu-link').on('click', function(e) {\n";
			echo "      var link = $(this);\n";
			echo "      var treeviewItem = link.closest('.has-treeview');\n";
			echo "      var clickedElement = $(e.target);\n";
			echo "      \n";
			echo "      // Check if click was on the arrow (right icon) or the icon area\n";
			echo "      if (clickedElement.hasClass('fa-angle-left') || clickedElement.hasClass('fa-angle-down') || clickedElement.closest('.right').length > 0 || clickedElement.closest('.nav-icon').length > 0) {\n";
			echo "        e.preventDefault();\n";
			echo "        // Toggle treeview\n";
			echo "        treeviewItem.toggleClass('menu-open');\n";
			echo "        var rightIcon = link.find('.right');\n";
			echo "        if (treeviewItem.hasClass('menu-open')) {\n";
			echo "          rightIcon.removeClass('fa-angle-left').addClass('fa-angle-down');\n";
			echo "        } else {\n";
			echo "          rightIcon.removeClass('fa-angle-down').addClass('fa-angle-left');\n";
			echo "        }\n";
			echo "      }\n";
			echo "      // If click was on text, allow navigation (default behavior)\n";
			echo "    });\n";
			echo "  });\n";
			echo "})();\n";
			echo "</script>\n";
			
			echo "</body>\n";
		}
		
		function display_applications(&$waapp)
		{
			global $path_to_root;

			$selected_app = $waapp->get_selected_application();
			if (!$_SESSION["wa_current_user"]->check_application_access($selected_app))
				return;

			if (method_exists($selected_app, 'render_index'))
			{
				$selected_app->render_index();
				return;
			}

			// Page title - strip keyboard shortcut marker (&) from name
			$page_title = str_replace('&', '', $selected_app->name);
			// Override display name for GL to shorten it
			if ($selected_app->id == 'GL') {
				$page_title = str_replace('Banking and General Ledger', 'Banking & General Ledger', $page_title);
			}
			echo "<div class=\"mb-3\">\n";
			echo "<h1 class=\"m-0\">".$page_title."</h1>\n";
			echo "<p class=\"text-muted\">"._("Select a function from the menu below or use the quick links")."</p>\n";
			echo "</div>\n";
			
			// Display shortcuts bar
			$shortcuts = $this->get_app_shortcuts($selected_app->id);
			if (!empty($shortcuts)) {
				echo "<div class=\"card mb-3\">\n";
				echo "<div class=\"card-body\">\n";
				foreach ($shortcuts as $shortcut) {
					echo "<a href=\"$path_to_root/".$shortcut['link']."\" class=\"btn btn-sm btn-secondary mr-2 mb-2\">\n";
					echo $shortcut['label'];
					echo "</a>\n";
				}
				// Add Dashboard link
				$sel_app = $_SESSION['sel_app'];
				echo "<a href=\"$path_to_root/admin/dashboard.php?sel_app=$sel_app\" class=\"btn btn-sm btn-primary mr-2 mb-2\">\n";
				echo "<i class=\"fas fa-tachometer-alt mr-1\"></i> "._('Dashboard');
				echo "</a>\n";
				echo "</div>\n";
				echo "</div>\n";
			}

			// Module cards
			echo "<div class=\"row\">\n";
			
			foreach ($selected_app->modules as $module)
			{
        		if (!$_SESSION["wa_current_user"]->check_module_access($module))
        			continue;
				
				echo "<div class=\"col-md-4 mb-3\">\n";
				echo "<div class=\"card\">\n";
				echo "<div class=\"card-header\">\n";
				echo "<h3 class=\"card-title\">".$module->name."</h3>\n";
				echo "</div>\n";
				echo "<div class=\"card-body\">\n";
				
				// Left column items
				foreach ($module->lappfunctions as $appfunction)
				{
					$img = $this->get_icon($appfunction->category);
					if ($appfunction->label == "")
						echo "<div style=\"height: 0.5rem;\"></div>\n";
					elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access)) 
					{
						echo "<div class=\"mb-2\">\n";
						echo $img.menu_link($appfunction->link, $appfunction->label);
						echo "</div>\n";
					}
					elseif (!$_SESSION["wa_current_user"]->hide_inaccessible_menu_items())
					{
						echo "<div class=\"mb-2 text-muted\">\n";
						echo $img.'<span class="text-muted">'.access_string($appfunction->label, true)."</span>\n";
						echo "</div>\n";
					}
				}
				
				// Right column items
				if (sizeof($module->rappfunctions) > 0)
				{
					foreach ($module->rappfunctions as $appfunction)
					{
						$img = $this->get_icon($appfunction->category);
						if ($appfunction->label == "")
							echo "<div style=\"height: 0.5rem;\"></div>\n";
						elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access)) 
						{
							echo "<div class=\"mb-2\">\n";
							echo $img.menu_link($appfunction->link, $appfunction->label);
							echo "</div>\n";
						}
						elseif (!$_SESSION["wa_current_user"]->hide_inaccessible_menu_items())
						{
							echo "<div class=\"mb-2 text-muted\">\n";
							echo $img.'<span class="text-muted">'.access_string($appfunction->label, true)."</span>\n";
							echo "</div>\n";
						}
					}
				}
				
				echo "</div>\n";
				echo "</div>\n";
				echo "</div>\n";
			}
			
			echo "</div>\n";
		}
	}

