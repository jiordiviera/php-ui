# PHP-UI

[![Tests](https://github.com/jiordiviera/php-ui/actions/workflows/tests.yml/badge.svg)](https://github.com/jiordiviera/php-ui/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/jiordiviera/php-ui.svg?style=flat-square)](https://packagist.org/packages/jiordiviera/php-ui)
[![Total Downloads](https://img.shields.io/packagist/dt/jiordiviera/php-ui.svg?style=flat-square)](https://packagist.org/packages/jiordiviera/php-ui)
[![License](https://img.shields.io/packagist/l/jiordiviera/php-ui.svg?style=flat-square)](https://packagist.org/packages/jiordiviera/php-ui)

**A shadcn-like CLI for Laravel Livewire components.**

PHP-UI allows you to scaffold beautifully designed, accessible, and customizable components for your Laravel Livewire applications. It detects your Tailwind setup (v3 or v4) and handles dependencies automatically.

## Installation

You can install the package via composer globally to use it in any project:

```bash
composer global require jiordiviera/php-ui
```

Alternatively, you can install it as a dev dependency in a specific project:

```bash
composer require --dev jiordiviera/php-ui
```

## Usage

### 1. Initialize

Run the init command to set up the configuration for your project.

```bash
php-ui init
```

### 2. List Available Components

See all components you can add to your project.

```bash
php-ui list
```

### 3. Add Components

Use the `add` command to scaffold a new component.

```bash
php-ui add <component-name>
```

**Options:**

- `--force` or `-f`: Overwrite existing files without asking.

## Available Components (25+)

### Base & Form

- `button`: Versatile button with variants and sizes.
- `input`: Form field with label, icons, and error handling.
- `toggle`: Accessible switch for boolean values.
- `range-slider`: Single or double handle numeric slider.
- `file-upload`: Zone with drag-and-drop and progress bar.

### Navigation & Layout

- `breadcrumbs`: Navigation trail for nested pages.
- `tabs`: Tabbed interface for content switching.
- `modal`: Accessible dialog with transitions.
- `drawer`: Side panel sliding from edges.
- `accordion`: Expandable content panels.

### Feedback & Status

- `alert`: Contextual messages for user actions.
- `badge`: Small status indicators.
- `toast`: Global notification system.
- `tooltip`: Hover information popups.
- `progress-bar`: Visual progress indicator.
- `progress-steps`: Horizontal process tracker.
- `skeleton`: Loading state placeholders.

### Data & Visualization

- `data-table`: Advanced table with search, sort, and pagination.
- `stat-card`: Metrics display with trends and icons.
- `timeline`: Chronological event display.
- `rating`: Star rating with half-star support.

### Utilities

- `avatar`: Profile image with fallback initials and status.
- `avatar-group`: Stacked user avatars.
- `command-palette`: Global Spotlight-style search interface.
- `kbd`: Keyboard key indicators.
- `code-snippet`: Code display with copy functionality.
- `empty-state`: Placeholder for empty collections.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
