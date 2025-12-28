# FrontAccounting Setup Guide

This guide will help you set up FrontAccounting in a new environment after cloning this repository.

## Prerequisites

- PHP >= 5.0 (version 5.6 or 7.x recommended)
- MySQL >= 4.1 with InnoDB tables enabled, or MariaDB
- Web server (Apache with mod_php or IIS)
- Git (for cloning the repository)

## Quick Setup

### 1. Clone the Repository

```bash
git clone <repository-url> frontaccounting
cd frontaccounting
```

### 2. Configure Database Connection

Copy the example database configuration file and edit it with your database credentials:

```bash
cp config_db.php.example config_db.php
```

Edit `config_db.php` and update the following values:

```php
$db_connections = array (
  0 => 
  array (
    'name' => 'Your Company Name',
    'host' => 'localhost',           // Your database host
    'port' => '',                     // Database port (empty for default 3306)
    'dbname' => 'frontaccounting',    // Your database name
    'collation' => 'utf8_xx',
    'tbpref' => '0_',
    'dbuser' => 'your_db_user',       // Your database username
    'dbpassword' => 'your_db_password', // Your database password
  ),
);
```

**Important:** Never commit `config_db.php` to the repository as it contains sensitive credentials.

### 3. Configure Application Settings (Optional)

Copy the example configuration file if you want to customize application settings:

```bash
cp config.php.example config.php
```

Edit `config.php` to customize:
- Debug settings (set `$debug = 0` for production)
- Default theme (`$dflt_theme`)
- Timezone settings
- Other application preferences

**Note:** If you don't create `config.php`, FrontAccounting will use `config.default.php` which contains safe defaults.

### 4. Set File Permissions

Ensure the web server has write permissions to required directories:

```bash
# Create necessary directories if they don't exist
mkdir -p company tmp

# Set appropriate permissions (adjust user/group as needed)
chown -R www-data:www-data company tmp
chmod -R 755 company tmp
```

### 5. Run Installation Wizard

1. Open your browser and navigate to your FrontAccounting installation URL:
   ```
   http://your-domain/frontaccounting
   ```

2. Follow the installation wizard to:
   - Create the database schema
   - Set up the initial company
   - Configure language and encoding
   - Create the admin user account

3. After installation, **remove or rename the `_install/` directory** for security:
   ```bash
   mv _install _install_backup
   # or
   rm -rf _install
   ```

### 6. Verify Installation

1. Log in with the admin credentials you created during installation
2. Navigate to **Setup** → **User Preferences** to change your theme if desired
3. Configure your company settings, fiscal years, currencies, etc.

## Configuration Files

### Files to Configure

| File | Required | Description |
|------|----------|-------------|
| `config_db.php` | **Yes** | Database connection credentials |
| `config.php` | No | Application settings (uses `config.default.php` if not present) |

### Files Ignored by Git

The following files are excluded from version control (see `.gitignore`):

- `config.php` - Application configuration
- `config_db.php` - Database credentials
- `_install/` - Installation directory (should be removed after setup)
- `company/*/` - Company data and backups
- `tmp/` - Temporary files and logs
- `*.log` - Log files

## Troubleshooting

### Database Connection Issues

- Verify MySQL/MariaDB is running
- Check database credentials in `config_db.php`
- Ensure the database user has proper permissions
- Verify InnoDB is enabled: `SHOW ENGINES;`

### Permission Issues

- Ensure web server user has write access to `company/` and `tmp/` directories
- Check PHP error logs for specific permission errors

### Blank Page / Errors

- Check PHP error logs
- Verify `config_db.php` exists and has correct credentials
- Ensure all required PHP extensions are installed
- Check web server error logs

### Installation Wizard Not Appearing

- Ensure `_install/` directory exists
- Check file permissions
- Verify PHP version meets requirements
- Check web server configuration

## Security Best Practices

1. **Never commit sensitive files:**
   - `config_db.php` (contains database passwords)
   - `config.php` (may contain sensitive settings)

2. **Remove installation directory:**
   - Delete or rename `_install/` after installation

3. **Set proper file permissions:**
   - Restrict access to configuration files
   - Use appropriate ownership for web server files

4. **Use HTTPS:**
   - FrontAccounting should NOT be used via unsecured HTTP
   - Configure SSL/TLS for production environments

5. **Regular backups:**
   - Backup your `company/` directory regularly
   - Backup your database regularly

## Additional Resources

- [FrontAccounting Wiki](http://frontaccounting.com/fawiki/)
- [FrontAccounting Forum](http://frontaccounting.com/punbb/index.php)
- [Main README](./README.md)

## Getting Help

If you encounter issues:

1. Check the [FrontAccounting Wiki](http://frontaccounting.com/fawiki/)
2. Search the [FrontAccounting Forum](http://frontaccounting.com/punbb/index.php)
3. Report bugs to the [Mantis Bugtracker](http://mantis.frontaccounting.com)

