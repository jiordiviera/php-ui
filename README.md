# PHP-UI

**A shadcn-like CLI for Laravel Livewire components.**

PHP-UI allows you to scaffold beautifully designed, accessible, and customizable components for your Laravel Livewire applications. It detects your Tailwind setup (v3 or v4) and handles dependencies automatically.

## Installation

You can install the package via composer globally to use it in any project:

```bash
composer global require jiordiviera/php-ui
```

Make sure your global composer bin directory is in your system's PATH.

Alternatively, you can install it as a dev dependency in a specific project:

```bash
composer require --dev jiordiviera/php-ui
```

## Usage

### 1. Initialize

Run the init command to set up the configuration for your project. This will detect your project structure and Tailwind version.

```bash
php-ui init
```

This creates a `php-ui.json` file in your project root.

### 2. Add Components

Use the `add` command to scaffold a new component.

```bash
php-ui add button
```

This will:
1.  Check and install necessary dependencies (e.g., icons, libraries).
2.  Generate the PHP Class (e.g., `app/Livewire/UI/Button.php`).
3.  Generate the Blade View (e.g., `resources/views/livewire/ui/button.blade.php`).
4.  Inject necessary CSS variables into your `app.css`.

## Supported Components

*   Button
*   *(More coming soon...)*

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
