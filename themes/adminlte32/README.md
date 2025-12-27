# AdminLTE 3.2 Theme for FrontAccounting

A modern, responsive theme for [FrontAccounting](https://frontaccounting.com/) based on [AdminLTE 3.2](https://adminlte.io/) framework.

![License](https://img.shields.io/badge/license-GPL--3.0-blue.svg)
![FrontAccounting](https://img.shields.io/badge/FrontAccounting-2.4.x-green.svg)

## ✨ Features

- 🎨 **Modern Design** - Clean, professional UI based on AdminLTE 3.2
- 🌙 **Dark Mode** - Full dark mode support with smooth transitions
- 📱 **Responsive** - Mobile-friendly sidebar and layout using Bootstrap 5
- 🎯 **Font Awesome 6** - Beautiful icons throughout
- ⚡ **jQuery & Bootstrap** - Proven JavaScript libraries
- 🔔 **Styled Alerts** - Beautiful error, warning, and info messages
- 📅 **Modern Date Picker** - Styled calendar with icon inside input
- 🔘 **Consistent Buttons** - Color-coded action buttons with icons
- 📋 **Module Grouping** - Sidebar menus organized by Transactions, Inquiries, Maintenance
- 🎯 **Quick Links** - Shortcuts bar on module main pages
- 🖱️ **Smart Navigation** - Click menu text to navigate, arrow to expand/collapse
- 🎴 **Card-based Layout** - Consistent card styling throughout
- 🔑 **Keyboard Shortcuts** - Styled keyboard shortcuts display

## 📋 Requirements

- FrontAccounting 2.4.x or higher
- Modern web browser (Chrome, Firefox, Safari, Edge)
- AdminLTE 3.2, Bootstrap 4, jQuery 3.x, Font Awesome 6 (local files)

## 🚀 Installation

### Method 1: Download ZIP

1. Download the latest release from the repository
2. Extract the `adminlte32` folder to your FrontAccounting `themes/` directory
3. Log in to FrontAccounting as administrator
4. Go to **Setup** → **User Preferences** or **Company Setup**
5. Select **adminlte32** from the Theme dropdown
6. Save changes

### Method 2: Git Clone

```bash
cd /path/to/frontaccounting/themes/
git clone <repository-url> adminlte32
```

## 🔐 Login Page Configuration

By default, FrontAccounting's login page uses the "default" theme. To apply the AdminLTE 4 theme to the login page, you need to modify the FrontAccounting core configuration.

### Step 1: Edit `config.php`

Open your FrontAccounting `config.php` file (located in the root directory) and add or modify the following line:

```php
$dflt_theme = 'adminlte32';
```

This sets the default theme for the login page and password reset page. The line should be placed in the "User configurable variables" section, typically around line 134.

**Example:**
```php
/* Default theme for login page and new users */
$dflt_theme = 'adminlte32';
```

### Step 2: Verify Core Files

The theme requires that two core files use the `$SysPrefs->dflt_theme` variable:

1. **`access/login.php`** (around line 53-54):
   ```php
   global $SysPrefs;
   $def_theme = isset($SysPrefs->dflt_theme) ? $SysPrefs->dflt_theme : "default";
   ```

2. **`access/password_reset.php`** (around line 27-28):
   ```php
   global $SysPrefs;
   $def_theme = isset($SysPrefs->dflt_theme) ? $SysPrefs->dflt_theme : "default";
   ```

> **Note:** These modifications are required because FrontAccounting's login page doesn't use the theme hook system. The changes are minimal and only affect the theme selection logic. If your FrontAccounting installation already has these modifications (e.g., from another theme), no additional changes are needed.

### Why Core File Modifications Are Needed

FrontAccounting's login page (`access/login.php`) and password reset page (`access/password_reset.php`) are loaded before the theme system is initialized. They hardcode the theme name instead of using the hook system. Therefore, to apply a custom theme to these pages, we need to:

1. Define the default theme in `config.php` as a configurable variable
2. Modify the login and password reset pages to read this variable from `$SysPrefs->dflt_theme`

This is a one-time change that benefits all custom themes. Once these modifications are in place, you can switch between themes by changing the `$dflt_theme` value in `config.php`.

### Alternative: Manual Theme Selection

If you prefer not to modify core files, users can still select the AdminLTE 4 theme from their user preferences after logging in. The login page will continue to use the default theme until core files are modified.

## 📁 Directory Structure

```
adminlte32/
├── index.php           # Theme index (redirect)
├── renderer.php        # Main theme renderer
├── default.css         # Theme styles and overrides
├── images/
│   └── logo/          # Logo files (light/dark/icon)
├── vendor/            # Third-party libraries (local files)
│   ├── adminlte/      # AdminLTE 3.2 files
│   ├── bootstrap/     # Bootstrap 4 files
│   ├── jquery/        # jQuery files
│   ├── fontawesome/   # Font Awesome icons
│   └── webfonts/      # Font Awesome webfonts
└── README.md
```

## 🎨 Customization

### Colors

The theme uses AdminLTE's default color palette. Main button colors can be customized in `default.css`:

| Color | AdminLTE Class | Default | Usage |
|-------|---------------|---------|-------|
| Primary | `btn-primary` | Blue (#007bff) | Search, Select buttons |
| Success | `btn-success` | Green (#28a745) | Add, Save, Update, Place buttons |
| Danger | `btn-danger` | Red (#dc3545) | Delete, Remove buttons |
| Secondary | `btn-secondary` | Gray (#6c757d) | Cancel buttons |
| Warning | `alert-warning` | Amber | Warning alerts |
| Info | `alert-info` | Blue | Info alerts |

### Logo

Replace the logo files in `images/logo/`:
- `logo-icon.svg` - Sidebar icon/logo
- Additional logo files can be added for header branding

### Dark Mode

Dark mode can be toggled via the header button. The preference is saved to localStorage and persists across sessions. The theme uses AdminLTE's dark mode styling.

### Using Local Files

The theme uses local files for all libraries. All files are located in the `vendor/` directory:

1. Download the libraries to the `vendor/` directory
2. Update the `<link>` and `<script>` tags in `renderer.php` to point to local files instead of CDN URLs

**CDN Links Currently Used:**
- Bootstrap 5: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css`
- Font Awesome 6: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css`
- AdminLTE 3.2: Local files in `vendor/adminlte/`
- Bootstrap 4: Local files in `vendor/bootstrap/`
- jQuery 3.6: Local file in `vendor/jquery/`
- Font Awesome 6: Local files in `vendor/fontawesome/`
- jQuery 3.7.1: `https://code.jquery.com/jquery-3.7.1.min.js`

## 🎯 Sidebar Navigation

The sidebar features intelligent navigation:

- **Module Grouping**: Menu items are organized under headers (Transactions, Inquiries and Reports, Maintenance)
- **Smart Click Behavior**: 
  - Click on menu text/icon → Navigate to module main page
  - Click on arrow → Expand/collapse submenu
- **Active State Detection**: Automatically highlights active menu items and expands parent menus
- **Responsive**: Sidebar collapses on mobile devices, shows as overlay

## 🔘 Button Styling

Buttons are automatically styled based on their text content:

- **Success (Green)**: Add, Update, Save, Process, Submit, Confirm, Enter, Create, Post, Place
- **Danger (Red)**: Delete, Remove
- **Secondary (Gray)**: Cancel, Cancel Order
- **Primary (Blue)**: Search, Select

Font Awesome icons are automatically added to buttons via CSS.

## 📝 Form Elements

All form inputs, textareas, and select dropdowns are styled with AdminLTE form classes for consistent appearance. Date pickers include a calendar icon inside the input field.

## 🔔 Alert Messages

FrontAccounting alert messages (error, warning, notice, success) are styled as AdminLTE alert components with Font Awesome icons:

- **Error** (`err_msg`): Red alert with exclamation-circle icon
- **Warning** (`warn_msg`): Amber alert with exclamation-triangle icon
- **Notice** (`note_msg`): Blue alert with info-circle icon
- **Success** (`success_msg`): Green alert with check-circle icon

## 📱 Responsive Design

The theme is fully responsive using Bootstrap 5's grid system:

- **Mobile**: Sidebar collapses, menu accessible via hamburger button
- **Tablet**: Sidebar can be toggled, optimized layout
- **Desktop**: Full sidebar visible, maximum content width

## 🛠️ Development

### File Structure

- `renderer.php`: Main theme renderer class with all rendering logic
- `default.css`: Custom CSS overrides and FrontAccounting-specific styling
- `index.php`: Simple redirect file

### Key Functions

- `get_app_icon()`: Returns Font Awesome icons for each application
- `is_menu_link_active()`: Determines if a menu item matches the current page
- `get_app_shortcuts()`: Returns quick links for each module
- `menu_header()`: Renders sidebar and header
- `menu_footer()`: Renders footer and loads JavaScript
- `display_applications()`: Renders module main pages

## 📄 License

This theme is released under the GNU General Public License (GPL) v3.0, same as FrontAccounting.

## 🙏 Credits

- [FrontAccounting](https://frontaccounting.com/) - Open source accounting software
- [AdminLTE](https://adminlte.io/) - Admin dashboard template
- [Bootstrap](https://getbootstrap.com/) - CSS framework
- [Font Awesome](https://fontawesome.com/) - Icon library
- [jQuery](https://jquery.com/) - JavaScript library

## 📞 Support

For issues, questions, or contributions, please use the repository's issue tracker.

## 🔄 Version History

### 1.0.0 (Initial Release)

- Initial release based on AdminLTE 3.2
- Full responsive design
- Dark mode support
- Styled buttons, forms, and alerts
- Module grouping in sidebar
- Quick links on module pages
- Login page integration (based on AdminLTE 3.2 login-v2 design)

