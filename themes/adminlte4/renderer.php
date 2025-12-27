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
		
		// Get Bootstrap Icons for each FrontAccounting application
		// Using Bootstrap Icons (https://icons.getbootstrap.com)
		function get_app_icon($app_id, $icon_class = '')
		{
			$icons = array(
				// Sales - Cart
				'orders' => '<i class="bi bi-cart '.$icon_class.'"></i>',
				
				// Purchases - Clipboard
				'AP' => '<i class="bi bi-clipboard '.$icon_class.'"></i>',
				
				// Items and Inventory - Box
				'stock' => '<i class="bi bi-box-seam '.$icon_class.'"></i>',
				
				// Manufacturing - Tools
				'manuf' => '<i class="bi bi-tools '.$icon_class.'"></i>',
				
				// Fixed Assets - Building
				'assets' => '<i class="bi bi-building '.$icon_class.'"></i>',
				
				// Dimensions - Layers
				'proj' => '<i class="bi bi-layers '.$icon_class.'"></i>',
				
				// Banking and General Ledger - Wallet
				'GL' => '<i class="bi bi-wallet2 '.$icon_class.'"></i>',
				
				// Setup - Gear
				'system' => '<i class="bi bi-gear '.$icon_class.'"></i>',
				
				// Default - Grid
				'default' => '<i class="bi bi-grid '.$icon_class.'"></i>'
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
				),
				'AP' => array(
					array('link' => 'purchasing/po_entry_items.php?NewOrder=Yes', 'label' => _('Purchase Order')),
					array('link' => 'purchasing/supplier_invoice.php?NewInvoice=Yes', 'label' => _('Supplier Invoice')),
					array('link' => 'purchasing/supplier_payment.php?', 'label' => _('Payments')),
				),
				'stock' => array(
					array('link' => 'inventory/inventory_adjustment.php?NewAdjustment=Yes', 'label' => _('Adjustment')),
					array('link' => 'inventory/inventory_transfer.php?NewTransfer=Yes', 'label' => _('Transfer')),
					array('link' => 'inventory/inventory_movements.php?', 'label' => _('Movements')),
				),
				'GL' => array(
					array('link' => 'gl/gl_journal.php?NewJournal=Yes', 'label' => _('Journal Entry')),
					array('link' => 'gl/gl_bank.php?NewPayment=Yes', 'label' => _('Bank Payment')),
					array('link' => 'gl/gl_bank.php?NewDeposit=Yes', 'label' => _('Bank Deposit')),
				),
			);
			
			return isset($shortcuts[$app_id]) ? $shortcuts[$app_id] : array();
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

			// Get application name (strip & for keyboard shortcuts)
			$app_name = $selected_app->name;
			$app_name = str_replace('&', '', $app_name);
			
			// Override display name for GL
			if ($selected_app->id == 'GL') {
				$app_name = str_replace('Banking and General Ledger', 'Banking & General Ledger', $app_name);
			}
			
			if (!$current_app) {
				return;
			}
			
			// Get application name (strip & for keyboard shortcuts)
			$app_name = $current_app->name;
			$app_name = str_replace('&', '', $app_name);
			
			// Override display name for GL
			if ($current_app->id == 'GL') {
				$app_name = str_replace('Banking and General Ledger', 'Banking & General Ledger', $app_name);
			}
			
			// Page Title
			echo "<div class=\"mb-4\">\n";
			echo "<h1 class=\"h3 mb-1\">$app_name</h1>\n";
			echo "<p class=\"text-muted\">"._("Select a function from the menu below or use the quick links")."</p>\n";
			if (user_hints())
				echo "<small id='hints' class=\"text-muted\"></small>\n";
			echo "</div>\n";
			
			// Quick Links
			$shortcuts = $this->get_app_shortcuts($selected_app->id);
			if (!empty($shortcuts)) {
				echo "<div class=\"mb-4\">\n";
				echo "<div class=\"btn-toolbar\" role=\"toolbar\">\n";
				foreach($shortcuts as $shortcut) {
					echo "<a href=\"$path_to_root/".$shortcut['link']."\" class=\"btn btn-sm btn-outline-primary me-2 mb-2\">\n";
					echo $shortcut['label']."\n";
					echo "</a>\n";
				}
				echo "</div>\n";
				echo "</div>\n";
			}
			
			// Application Cards
			$module_index = 0;
			foreach ($selected_app->modules as $module)
			{
				if ($_SESSION["wa_current_user"]->check_module_access($module))
				{
					if ($module_index > 0) {
						echo "<hr class=\"my-4\">\n";
					}
					
					// Module Group Header
					if (!empty($module->name)) {
						echo "<h5 class=\"mb-3 text-muted\">".$module->name."</h5>\n";
					}
					
					// Module Functions Grid
					echo "<div class=\"row g-3 mb-4\">\n";
					
					// Left app functions
					foreach ($module->lappfunctions as $appfunction)
					{
						if ($appfunction->label == "")
							continue;
						elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access))
						{
							$acc_func = access_string($appfunction->label);
							echo "<div class=\"col-md-4 col-lg-3\">\n";
							echo "<div class=\"card h-100\">\n";
							echo "<div class=\"card-body\">\n";
							echo "<h6 class=\"card-title\"><a href=\"$path_to_root/".$appfunction->link."\" class=\"text-decoration-none\">".$acc_func[0]."</a></h6>\n";
							echo "</div>\n";
							echo "</div>\n";
							echo "</div>\n";
						}
					}
					
					// Right app functions
					if (isset($module->rappfunctions) && sizeof($module->rappfunctions) > 0)
					{
						foreach ($module->rappfunctions as $appfunction)
						{
							if ($appfunction->label == "")
								continue;
							elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access))
							{
								$acc_func = access_string($appfunction->label);
								echo "<div class=\"col-md-4 col-lg-3\">\n";
								echo "<div class=\"card h-100\">\n";
								echo "<div class=\"card-body\">\n";
								echo "<h6 class=\"card-title\"><a href=\"$path_to_root/".$appfunction->link."\" class=\"text-decoration-none\">".$acc_func[0]."</a></h6>\n";
								echo "</div>\n";
								echo "</div>\n";
								echo "</div>\n";
							}
						}
					}
					
					echo "</div>\n"; // row
					$module_index++;
				}
			}
		}

		function start_form($title, $action, $method="post", $target="_self", $params="")
		{
			global $path_to_root;
			if ($title != "")
				echo "<p class=\"page_title_text\">$title</p>\n";
			echo "<form method=\"$method\" action=\"$action\" target=\"$target\" $params>\n";
		}

		function end_form()
		{
			echo "</form>\n";
		}

		function end_page($no_menu=false, $is_index=false)
		{
			end_page($no_menu, $is_index);
		}

		function menu_header($title, $no_menu, $is_index)
		{
			global $path_to_root, $SysPrefs, $db_connections;
			
			$theme_path = $path_to_root . "/themes/".user_theme();
			$indicator = "$path_to_root/themes/".user_theme(). "/images/ajax-loader.gif";
			
			// Load AdminLTE 4, Bootstrap 5, jQuery, Bootstrap Icons, and OverlayScrollbars from local files
			// Note: CSS links in body work in modern browsers (same pattern as adminlte32 theme)
			echo "<!-- AdminLTE 4 Theme -->\n";
			echo "<!-- Bootstrap 5 CSS -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/bootstrap/bootstrap.min.css\">\n";
			echo "<!-- Bootstrap Icons -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/bootstrap-icons/bootstrap-icons.min.css\">\n";
			echo "<!-- OverlayScrollbars -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/overlayscrollbars/overlayscrollbars.min.css\">\n";
			echo "<!-- AdminLTE 4 CSS -->\n";
			echo "<link rel=\"stylesheet\" href=\"$theme_path/vendor/adminlte/adminlte.min.css\">\n";
			echo "<!-- Custom CSS (default.css is included via FrontAccounting's send_css()) -->\n";
			
			// Start AdminLTE 4 layout structure
			// Note: FrontAccounting already opened <body>, so we add classes to it and start wrapper
			// Same pattern as TailAdmin theme - no body tag, just wrapper div
			echo "<script>
			// Add AdminLTE 4 body classes
			document.body.className = 'layout-fixed sidebar-expand-lg bg-body-tertiary';
			</script>\n";
			echo "<!--begin::App Wrapper-->\n";
			echo "<div class=\"app-wrapper\">\n";
			
			if (!$no_menu)
			{
				// ===== Main Header Start =====
				echo "<!--begin::Header-->\n";
				echo "<nav class=\"app-header navbar navbar-expand bg-body\">\n";
				echo "<!--begin::Container-->\n";
				echo "<div class=\"container-fluid\">\n";
				
				// Left navbar links
				echo "<!--begin::Start Navbar Links-->\n";
				echo "<ul class=\"navbar-nav\">\n";
				echo "<li class=\"nav-item\">\n";
				echo "<a class=\"nav-link\" data-lte-toggle=\"sidebar\" href=\"#\" role=\"button\">\n";
				echo "<i class=\"bi bi-list\"></i>\n";
				echo "</a>\n";
				echo "</li>\n";
				echo "</ul>\n";
				echo "<!--end::Start Navbar Links-->\n";
				
				// Right navbar links
				echo "<!--begin::End Navbar Links-->\n";
				echo "<ul class=\"navbar-nav ms-auto\">\n";
				
				// Dark mode toggle
				echo "<!--begin::Dark Mode Toggle-->\n";
				echo "<li class=\"nav-item\">\n";
				echo "<a class=\"nav-link\" href=\"#\" id=\"darkModeToggle\" role=\"button\">\n";
				echo "<i class=\"bi bi-moon\" id=\"darkModeIcon\"></i>\n";
				echo "</a>\n";
				echo "</li>\n";
				echo "<!--end::Dark Mode Toggle-->\n";
				
				// User dropdown
				echo "<!--begin::User Menu Dropdown-->\n";
				echo "<li class=\"nav-item dropdown user-menu\">\n";
				echo "<a href=\"#\" class=\"nav-link dropdown-toggle\" data-bs-toggle=\"dropdown\">\n";
				$user_initials = strtoupper(substr($_SESSION["wa_current_user"]->name, 0, 1));
				echo "<span class=\"badge bg-primary rounded-circle\" style=\"width: 2rem; height: 2rem; display: inline-flex; align-items: center; justify-content: center;\">$user_initials</span>\n";
				echo "</a>\n";
				echo "<ul class=\"dropdown-menu dropdown-menu-lg dropdown-menu-end\">\n";
				echo "<!--begin::User Image-->\n";
				echo "<li class=\"user-header bg-primary\">\n";
				echo "<p class=\"mb-0\">\n";
				echo $_SESSION["wa_current_user"]->name."\n";
				echo "<small>".$db_connections[user_company()]["name"]."</small>\n";
				echo "</p>\n";
				echo "</li>\n";
				echo "<!--end::User Image-->\n";
				echo "<!--begin::Menu Footer-->\n";
				echo "<li class=\"user-footer\">\n";
				echo "<a href=\"$path_to_root/admin/dashboard.php?sel_app=$sel_app\" class=\"btn btn-default btn-flat\">"._("Dashboard")."</a>\n";
				echo "<a href=\"$path_to_root/admin/display_prefs.php?\" class=\"btn btn-default btn-flat\">"._("Preferences")."</a>\n";
				if ($SysPrefs->help_base_url != null) {
					echo "<a target=\"_blank\" onclick=\"javascript:openWindow(this.href,this.target); return false;\" href=\"".help_url()."\" class=\"btn btn-default btn-flat\">"._("Help")."</a>\n";
				}
				echo "<a href=\"$path_to_root/access/logout.php?\" class=\"btn btn-default btn-flat float-end\">"._("Sign out")."</a>\n";
				echo "</li>\n";
				echo "<!--end::Menu Footer-->\n";
				echo "</ul>\n";
				echo "</li>\n";
				echo "<!--end::User Menu Dropdown-->\n";
				
				echo "</ul>\n";
				echo "<!--end::End Navbar Links-->\n";
				echo "</div>\n";
				echo "<!--end::Container-->\n";
				echo "</nav>\n";
				echo "<!--end::Header-->\n";
				// ===== Main Header End =====
				
				// ===== Main Sidebar Start =====
				echo "<!--begin::Sidebar-->\n";
				echo "<aside class=\"app-sidebar bg-body-secondary shadow\" data-bs-theme=\"dark\">\n";
				
				// Sidebar Brand/Logo
				echo "<!--begin::Sidebar Brand-->\n";
				echo "<div class=\"sidebar-brand\">\n";
				echo "<!--begin::Brand Link-->\n";
				echo "<a href=\"$path_to_root/index.php\" class=\"brand-link\">\n";
				echo "<!--begin::Brand Image-->\n";
				echo "<img src=\"$theme_path/images/logo/logo-icon.svg\" alt=\"FrontAccounting Logo\" class=\"brand-image opacity-75 shadow\">\n";
				echo "<!--end::Brand Image-->\n";
				echo "<!--begin::Brand Text-->\n";
				echo "<span class=\"brand-text fw-light\">FrontAccounting</span>\n";
				echo "<!--end::Brand Text-->\n";
				echo "</a>\n";
				echo "<!--end::Brand Link-->\n";
				echo "</div>\n";
				echo "<!--end::Sidebar Brand-->\n";
				
				// Sidebar Wrapper
				echo "<!--begin::Sidebar Wrapper-->\n";
				echo "<div class=\"sidebar-wrapper\">\n";
				echo "<nav class=\"mt-2\">\n";
				echo "<!--begin::Sidebar Menu-->\n";
				echo "<ul class=\"nav sidebar-menu flex-column\" data-lte-toggle=\"treeview\" role=\"navigation\" aria-label=\"Main navigation\" data-accordion=\"false\" id=\"navigation\">\n";
				
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
				$current_app_id = isset($_GET['application']) ? $_GET['application'] : '';
				foreach($applications as $app) {
					if ($app->id == $current_app_id) {
						$active_app_key = 'App_' . $app->id;
						break;
					}
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
						$is_active = ($sel_app == $app->id) || ($active_app_key == $app_key) || ($current_app_id == $app->id);
						
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
						$menu_open = ($active_app_key == $app_key || $is_active || $current_app_id == $app->id) ? 'menu-open' : '';
						
						if ($has_modules) {
							// Menu item with treeview (has submenus)
							$active_class = $is_active ? 'active' : '';
							echo "<li class=\"nav-item $menu_open\">\n";
							
							// Main link
							echo "<a href=\"$path_to_root/index.php?application=".$app->id."\" class=\"nav-link $active_class\">\n";
							echo $this->get_app_icon($app->id, 'nav-icon');
							echo "<p>\n";
							echo $acc[0]."\n";
							echo "<i class=\"nav-arrow bi bi-chevron-right\"></i>\n";
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
											echo "<i class=\"nav-icon bi bi-circle\"></i>\n";
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
												echo "<i class=\"nav-icon bi bi-circle\"></i>\n";
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
				echo "<!--end::Sidebar Menu-->\n";
				echo "</nav>\n";
				echo "</div>\n";
				echo "<!--end::Sidebar Wrapper-->\n";
				echo "</aside>\n";
				echo "<!--end::Sidebar-->\n";
				// ===== Main Sidebar End =====
				
				// ===== Main Content Start =====
				echo "<!--begin::App Main-->\n";
				echo "<main class=\"app-main\">\n";
				
				// Content Header
				if ($title && !$is_index)
				{
					echo "<!--begin::Content Header-->\n";
					echo "<div class=\"app-content-header\">\n";
					echo "<div class=\"container-fluid\">\n";
					echo "<div class=\"row\">\n";
					echo "<div class=\"col-sm-6\">\n";
					echo "<h1 class=\"m-0\">$title</h1>\n";
					if (user_hints())
						echo "<small id='hints' class=\"text-muted\"></small>\n";
					echo "</div>\n";
					echo "</div>\n";
					echo "</div>\n";
					echo "</div>\n";
					echo "<!--end::Content Header-->\n";
				}
				
				// Content
				echo "<!--begin::App Content-->\n";
				echo "<div class=\"app-content\">\n";
				echo "<div class=\"container-fluid\">\n";
				
				// AJAX Indicator
				echo "<img id='ajaxmark' src='$indicator' align='center' style='visibility:hidden;position:fixed;top:50%;left:50%;z-index:99999;' alt='ajaxmark'>\n";
			}
			else
			{
				// No menu mode (installer, popups)
				echo "<!--begin::App Main-->\n";
				echo "<main class=\"app-main\">\n";
				echo "<!--begin::App Content-->\n";
				echo "<div class=\"app-content\">\n";
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
				echo "</div>\n"; // app-content
				echo "</main>\n";
				echo "<!--end::App Main-->\n";
				
				// Footer
				echo "<!--begin::Footer-->\n";
				echo "<footer class=\"app-footer\">\n";
				echo "<strong>".$SysPrefs->app_title." $version</strong> - "._("Theme:")." ".user_theme()." - ".show_users_online();
				echo "<span class=\"ms-auto\">\n";
				echo Today()." | ".Now()."\n";
				echo "</span>\n";
				echo "<br />\n";
				echo "<a target='_blank' href='".$SysPrefs->power_url."' class=\"text-muted\">";
				echo $SysPrefs->power_by;
				echo "</a>\n";
				echo "</footer>\n";
				echo "<!--end::Footer-->\n";
			}
			else
			{
				echo "</div>\n"; // container-fluid
				echo "</div>\n"; // app-content
				echo "</main>\n";
				echo "<!--end::App Main-->\n";
			}
			
			echo "</div>\n"; // app-wrapper
			echo "<!--end::App Wrapper-->\n";
			
			// Load JavaScript libraries
			$theme_path = $path_to_root . "/themes/".user_theme();
			echo "<!-- jQuery -->\n";
			echo "<script src=\"$theme_path/vendor/jquery/jquery.min.js\"></script>\n";
			echo "<!-- Bootstrap 5 JS Bundle -->\n";
			echo "<script src=\"$theme_path/vendor/bootstrap/bootstrap.bundle.min.js\"></script>\n";
			echo "<!-- OverlayScrollbars -->\n";
			echo "<script src=\"$theme_path/vendor/overlayscrollbars/overlayscrollbars.min.js\"></script>\n";
			echo "<!-- AdminLTE 4 JS -->\n";
			echo "<script src=\"$theme_path/vendor/adminlte/adminlte.min.js\"></script>\n";
			
			// Custom JavaScript for theme functionality
			echo "<script>\n";
			echo "(function() {\n";
			echo "// Dark mode toggle\n";
			echo "const darkModeToggle = document.getElementById('darkModeToggle');\n";
			echo "const darkModeIcon = document.getElementById('darkModeIcon');\n";
			echo "const body = document.body;\n";
			echo "\n";
			echo "// Check for saved dark mode preference or default to light mode\n";
			echo "const darkMode = localStorage.getItem('darkMode') === 'true';\n";
			echo "if (darkMode) {\n";
			echo "body.setAttribute('data-bs-theme', 'dark');\n";
			echo "if (darkModeIcon) darkModeIcon.classList.replace('bi-moon', 'bi-sun');\n";
			echo "}\n";
			echo "\n";
			echo "if (darkModeToggle && darkModeIcon) {\n";
			echo "darkModeToggle.addEventListener('click', function(e) {\n";
			echo "e.preventDefault();\n";
			echo "const isDark = body.getAttribute('data-bs-theme') === 'dark';\n";
			echo "if (isDark) {\n";
			echo "body.removeAttribute('data-bs-theme');\n";
			echo "localStorage.setItem('darkMode', 'false');\n";
			echo "darkModeIcon.classList.replace('bi-sun', 'bi-moon');\n";
			echo "} else {\n";
			echo "body.setAttribute('data-bs-theme', 'dark');\n";
			echo "localStorage.setItem('darkMode', 'true');\n";
			echo "darkModeIcon.classList.replace('bi-moon', 'bi-sun');\n";
			echo "}\n";
			echo "});\n";
			echo "}\n";
			echo "\n";
			echo "// Initialize AdminLTE components\n";
			echo "if (typeof AdminLTEOptions !== 'undefined') {\n";
			echo "// AdminLTE will auto-initialize\n";
			echo "}\n";
			echo "})();\n";
			echo "</script>\n";
			
			// Note: FrontAccounting's page_footer() closes </body></html>, so we don't close it here
		}
	}
?>
