# AdminLTE 4 Theme for FrontAccounting

A modern, responsive AdminLTE 4 theme for FrontAccounting ERP system. This theme provides a professional admin dashboard interface based on AdminLTE 4.0.0-rc6, Bootstrap 5.3.3, and Bootstrap Icons.

## Features

- **AdminLTE 4 Design**: Based on the latest AdminLTE 4.0.0-rc6 with Bootstrap 5.3.3
- **Bootstrap Icons**: Modern icon set integrated throughout the theme
- **Dark Mode Support**: Built-in dark mode toggle with persistent preferences
- **Responsive Design**: Fully responsive layout that works on all devices
- **Sidebar Navigation**: Collapsible sidebar with module grouping and active state detection
- **Custom Styling**: Styled buttons, forms, tables, and alerts to match AdminLTE 4 design
- **Themed Login Page**: Custom login page matching AdminLTE 4 design
- **Keyboard Shortcuts**: Styled keyboard shortcuts card in the footer

## Requirements

- FrontAccounting (any recent version)
- PHP 7.4 or higher
- Modern web browser (Chrome, Firefox, Safari, Edge)

## Installation

1. **Copy the theme directory** to your FrontAccounting themes folder:
   ```bash
   cp -r adminlte4 /path/to/frontaccounting/themes/
   ```

2. **Set the theme** in FrontAccounting:
   - Log in as administrator
   - Go to **Setup** → **Display Setup** → **User Display Setup**
   - Select **adminlte4** from the Theme dropdown
   - Click **Update**

   OR

   - Edit `config.php` and set:
     ```php
     $dflt_theme = 'adminlte4';
     ```

3. **Clear browser cache** and refresh the page

## Theme Structure

```
adminlte4/
├── renderer.php          # Main theme renderer class
├── default.css           # Custom CSS overrides and styling
├── index.php             # Theme redirect file
├── README.md             # This file
├── images/               # Theme images
│   ├── logo/            # Logo files
│   └── *.gif            # Icons and images
└── vendor/              # Third-party libraries
    ├── adminlte/        # AdminLTE 4 CSS and JS
    ├── bootstrap/       # Bootstrap 5 CSS and JS
    ├── jquery/          # jQuery library
    ├── bootstrap-icons/ # Bootstrap Icons CSS and fonts
    └── overlayscrollbars/ # OverlayScrollbars CSS and JS
```

## Login Page Configuration

The login page is themed to match AdminLTE 4 design. To apply the theme to the login page:

### Option A: Modify Core Files (Recommended)

1. **Edit `config.php`** (in FrontAccounting root directory):
   ```php
   $dflt_theme = 'adminlte4';
   ```

2. **Verify `access/login.php`** uses the default theme:
   The file should already use `$SysPrefs->dflt_theme` to determine the theme. If not, it needs to be updated to:
   ```php
   $theme_path = $path_to_root . "/themes/" . $SysPrefs->dflt_theme;
   ```

3. **Verify `access/password_reset.php`** uses the default theme (same as above)

**Note**: This is a minimal core file modification. The theme will be applied to all new users and the login page.

### Option B: Manual Theme Selection

Users can manually select the theme from their user preferences:
- Go to **Setup** → **Display Setup** → **User Display Setup**
- Select **adminlte4** from the Theme dropdown
- Click **Update**

However, this method does NOT apply the theme to the login page, as the login page hardcodes the "default" theme by default.

## Customization

### Changing Colors

Edit `default.css` to customize colors. AdminLTE 4 uses Bootstrap 5 color variables and classes.

### Modifying Icons

Icons are handled via Bootstrap Icons. The `get_app_icon()` function in `renderer.php` maps FrontAccounting applications to Bootstrap Icon classes.

### Styling Components

All FrontAccounting components are styled in `default.css`. Key sections:
- **Buttons**: Semantic button styling based on button text/value
- **Forms**: Input fields, selects, textareas
- **Tables**: Data tables with hover effects
- **Alerts**: Error, warning, note, and success messages
- **Sidebar**: Navigation menu styling
- **Header**: Top navigation bar

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Dependencies

This theme includes the following libraries (all included locally in `vendor/` directory):

- **AdminLTE 4.0.0-rc6**: Admin dashboard template
- **Bootstrap 5.3.3**: CSS framework
- **jQuery 3.7.1**: JavaScript library
- **Bootstrap Icons 1.13.1**: Icon set
- **OverlayScrollbars 2.11.0**: Custom scrollbar styling

## Troubleshooting

### Theme Not Loading

1. Check file permissions on the theme directory
2. Verify `config.php` has the correct theme name
3. Clear browser cache and cookies
4. Check browser console for JavaScript errors

### Dark Mode Not Working

1. Clear browser localStorage
2. Check browser console for JavaScript errors
3. Verify AdminLTE 4 JavaScript is loading correctly

### Icons Not Displaying

1. Check that Bootstrap Icons fonts are in `vendor/bootstrap-icons/fonts/`
2. Verify Bootstrap Icons CSS path is correct
3. Clear browser cache

### Login Page Not Themed

1. Verify `$dflt_theme` is set in `config.php`
2. Check that `access/login.php` uses `$SysPrefs->dflt_theme`
3. Clear browser cache

## Development

### File Changes

- **renderer.php**: Main theme logic and HTML structure
- **default.css**: All styling and overrides
- **vendor/**: Third-party libraries (do not modify)

### Testing

1. Test in multiple browsers
2. Test responsive design on different screen sizes
3. Test dark mode toggle
4. Test login page
5. Test all major FrontAccounting modules

## Credits

- **AdminLTE 4**: [https://adminlte.io](https://adminlte.io)
- **Bootstrap 5**: [https://getbootstrap.com](https://getbootstrap.com)
- **Bootstrap Icons**: [https://icons.getbootstrap.com](https://icons.getbootstrap.com)
- **FrontAccounting**: [https://frontaccounting.com](https://frontaccounting.com)

## License

This theme follows the same license as FrontAccounting (GPL v3 or later).

## Version

- **Theme Version**: 1.0.0
- **AdminLTE Version**: 4.0.0-rc6
- **Bootstrap Version**: 5.3.3
- **Bootstrap Icons Version**: 1.13.1

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review FrontAccounting documentation
3. Check AdminLTE 4 documentation: [https://adminlte.io/themes/v4/docs](https://adminlte.io/themes/v4/docs)
