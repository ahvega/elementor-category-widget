# Custom Category Archive Widget for Elementor

![License](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg)
![Elementor](https://img.shields.io/badge/elementor-%3E%3D%203.0.0-green.svg)
![WordPress](https://img.shields.io/badge/wordpress-%3E%3D%205.0-blue.svg)

A powerful WordPress plugin that adds an advanced category archive widget to Elementor page builder, allowing for dynamic and customizable category displays.

## Description

The Custom Category Archive Widget for Elementor extends your Elementor page builder with a sophisticated widget for displaying category archives. It provides dynamic filtering capabilities and supports multiple post types and their associated taxonomies.

### Features

- Dynamic post type selection
- Automatic taxonomy loading based on selected post type
- AJAX-powered category updates
- Custom styling options through Elementor interface
- Support for hierarchical taxonomies
- Clean and optimized code structure
- Frontend and backend integration

## Requirements

- WordPress 5.0 or later
- Elementor 3.0.0 or later
- PHP 7.0 or later

## Installation

1. Download the plugin zip file
2. Go to WordPress admin → Plugins → Add New
3. Click on "Upload Plugin" and select the zip file
4. Activate the plugin through the 'Plugins' menu in WordPress
5. Elementor will automatically add the new widget to your widget panel

## Usage

1. Edit any page with Elementor
2. Look for "Custom Category" in the widget categories
3. Drag and drop the widget into your page
4. Configure the widget settings:
   - Select post type
   - Choose categories
   - Customize appearance
   - Set up layout options

## Configuration

The widget provides several configuration options through the Elementor interface:

- Post Type Selection
- Category Display Options
- Layout Settings
- Style Customization
- Dynamic Filtering

## Development

### File Structure

```bash
elementor-category-widget/
├── assets/
│   ├── css/
│   │   └── widget-styles.css
│   └── js/
│       └── widget-scripts.js
├── includes/
│   └── widgets/
│       └── elementor-category-widget.php
├── elementor-category-widget.php
└── README.md
```

### Technical Details

The plugin follows WordPress and Elementor coding standards and best practices:

- OOP-based architecture
- WordPress coding standards
- Elementor widget development guidelines
- PSR-4 autoloading
- Proper hooks and filters implementation

## Author

### **Adalberto H. Vega**

- Website: [https://patpal.me/inteldevign](https://patpal.me/inteldevign)
- GitHub: [@inteldevign](https://github.com/inteldevign)

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Changelog

### 1.0.0

- Initial release
- Basic category widget functionality
- Elementor integration
- Custom styling options
- Dynamic post type support

## Contributing

Contributions are welcome and appreciated! Here's how you can contribute:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Support

For support, please create an issue in the GitHub repository or contact the author directly through the website.

## Acknowledgments

- Thanks to the Elementor team for their excellent documentation
- WordPress community for continuous support
- All contributors who help improve this plugin
