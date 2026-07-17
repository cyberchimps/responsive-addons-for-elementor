# Responsive Addons for Elementor

[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![WordPress Plugin](https://img.shields.io/badge/WordPress%20Plugin-2.2.2-blue.svg)](https://wordpress.org/plugins/responsive-addons-for-elementor/)
[![Elementor Compatible](https://img.shields.io/badge/Elementor-4.0+-green.svg)](https://elementor.com)

A free, powerful WordPress plugin that extends [Elementor](https://elementor.com) with 80+ widgets, 5+ extensions, a theme builder, and 250+ pre-designed sections to help you build modern, responsive websites with ease.

> **Created by [Cyberchimps](https://cyberchimps.com)**

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Getting Started](#getting-started)
- [Project Structure](#project-structure)
- [Build & Development](#build--development)
- [Contributing](#contributing)
- [Support](#support)
- [License](#license)

## Overview

Responsive Addons for Elementor (RAE) is a free plugin that empowers web designers and developers to build professional, responsive websites using Elementor's visual builder. Whether you're using Elementor Free or Pro, RAE seamlessly integrates to provide:

- **80+ Elementor Widgets** - Pre-built components for common website elements
- **5+ Extensions** - Enhance Elementor's capabilities with powerful tools
- **Theme Builder** - Create custom headers, footers, and single post templates
- **250+ Sections** - Ready-to-use page sections for faster development
- **150+ Templates** - Professional website templates to jumpstart your projects
- **WooCommerce Widgets** - E-commerce specific components for online stores

All features are completely free and fully compatible with Elementor (both Free and Pro versions).

## Features

### 🎨 Core Capabilities

- **Lightweight & Performance-Focused** - Load only the widgets and features you need
- **Element Control** - Toggle individual widgets on/off to optimize site performance
- **Cross-Browser Compatible** - Works seamlessly across all modern browsers
- **Responsive Design** - All widgets automatically adapt to mobile, tablet, and desktop
- **Extensive Customization** - Fine-tune every aspect of your website's design

### 📦 Widget Categories

- **Content Widgets** - Advanced Tabs, Audio Player, Author Box, Banner, Breadcrumbs, and more
- **WooCommerce Widgets** - Product display, shopping cart, and e-commerce specific components
- **Navigation Widgets** - Menu builders, breadcrumb navigation, and back-to-top buttons
- **Form Widgets** - Custom forms, contact forms, and input elements
- **Media Widgets** - Image galleries, video players, carousels, and before/after sliders

### 🔧 Extensions

- Theme Builder for custom headers and footers
- Advanced styling and animation controls
- Performance optimization tools
- Content protection and security features

## Requirements

- **WordPress**: 5.0 or later
- **PHP**: 5.6 or later (7.4+ recommended)
- **Elementor**: Version 3.34 or later (Free or Pro)
- **Tested up to**: WordPress 7.0, Elementor 4.0

## Installation

### From WordPress Plugin Directory

1. Navigate to **Plugins > Add New** in your WordPress admin
2. Search for "Responsive Addons for Elementor"
3. Click **Install Now**, then **Activate**

### Manual Installation

1. Download the plugin from [WordPress.org](https://wordpress.org/plugins/responsive-addons-for-elementor/)
2. Extract and upload the `responsive-addons-for-elementor` folder to `/wp-content/plugins/`
3. Activate the plugin from the **Plugins** page in WordPress admin

### From Source (Development)

```bash
# Clone the repository
git clone https://github.com/cyberchimps/responsive-addons-for-elementor.git

# Navigate to the plugin directory
cd responsive-addons-for-elementor

# Install dependencies
npm install

# Build the plugin
npm run build
```

## Getting Started

### Activating Widgets

1. After activation, Enable the widgets you want to use
2. Disable unused widgets to keep your site lightweight

### Using Widgets in Elementor

1. Open any page/post in Elementor editor
2. Look for RAE widget categories in the element sidebar
3. Drag and drop widgets onto your page
4. Customize using the element settings panel

## Project Structure

```
responsive-addons-for-elementor/
├── includes/              # Core plugin classes
├── admin/                 # Admin dashboard and settings
├── assets/                # CSS, JS, and images
│   ├── css/              # Compiled stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Plugin images and icons
├── helper/               # Helper functions and utilities
├── ext/                  # Plugin extensions and add-ons
├── themes/               # Theme builder components
├── traits/               # PHP traits for reusable code
├── languages/            # Translation files
├── Gruntfile.js         # Build configuration
├── package.json         # Node dependencies
├── responsive-addons-for-elementor.php  # Main plugin file
├── README.md            # This file
└── readme.txt           # WordPress plugin readme
```

## Build & Development

### Available Commands

```bash
# Build production assets
npm run build

# Watch for changes (development mode)
npm run watch

# Create plugin ZIP file for distribution
npm run zip
```

### Build Tools

- **[Grunt](https://gruntjs.com/)** - Task runner
- **[Sass](https://sass-lang.com/)** - CSS preprocessing
- **[Uglify](https://github.com/gruntjs/grunt-contrib-uglify)** - JavaScript minification
- **[WP i18n](https://codex.wordpress.org/I18n_for_WordPress_Developers)** - Internationalization

### Development Workflow

1. Make changes to source files in `assets` and widget directories
2. Run `npm run watch` to automatically rebuild on changes
3. Test in Elementor editor
4. Commit changes with descriptive messages
5. Run `npm run build` before deployment
6. Use `npm run zip` to create release package

## Contributing

We welcome contributions! Here's how you can help:

### Getting Started with Development

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature-name`)
3. Make your changes following the code style
4. Test thoroughly in Elementor editor
5. Submit a pull request with a clear description

### Code Guidelines

- Follow WordPress coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Test on multiple Elementor versions
- Ensure backward compatibility

### Reporting Issues

Found a bug? Please create an issue on GitHub with:
- Clear description of the problem
- Steps to reproduce
- Expected vs. actual behavior
- WordPress and Elementor versions
- Any error messages

## Support

### Getting Help

- **Documentation**: [Visit our docs](https://cyberchimps.com/docs/responsive-addons-for-elementor/)
- **Video Tutorials**: [YouTube Channel](https://www.youtube.com/watch?v=pR4ZjH-P9Qs)
- **Contact Form**: [Support Page](https://cyberchimps.com/contact/)

### Useful Resources

- [Widget Demonstrations](https://cyberchimps.com/responsive-addons-for-elementor/widgets/)
- [Elementor Documentation](https://elementor.com/help/)

## License

Responsive Addons for Elementor is licensed under the [GNU General Public License v2.0 or later](https://www.gnu.org/licenses/gpl-2.0.html).

## Changelog

For detailed changelog, visit our [releases page](https://cyberchimps.com/changelog/responsive-elementor-addons/).

## Credits

**Responsive Addons for Elementor** is developed and maintained by [Cyberchimps](https://cyberchimps.com).

### Credits & Attribution

- Built for [Elementor Page Builder](https://elementor.com)
- Compatible with WordPress and all popular themes
- Community-driven development and continuous improvements

---

**Ready to build something amazing?** [Get started with Responsive Addons for Elementor today!](https://cyberchimps.com/responsive-addons-for-elementor/)