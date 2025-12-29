# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-29

### Added

- **CLI Commands**
  - `php-ui init` - Initialize project configuration with Tailwind detection
  - `php-ui add <component>` - Add components to your project
  - `php-ui list-components` - List all available components

- **30+ UI Components**
  - Base & Form: `button`, `input`, `toggle`, `range-slider`, `file-upload`, `date-picker`
  - Navigation: `breadcrumbs`, `tabs`, `modal`, `drawer`, `accordion`
  - Feedback: `alert`, `badge`, `toast`, `tooltip`, `progress-bar`, `progress-steps`, `skeleton`
  - Data: `data-table`, `stat-card`, `timeline`, `rating`
  - Utilities: `avatar`, `avatar-group`, `command-palette`, `kbd`, `code-snippet`, `empty-state`, `popover`, `carousel`, `stepper`, `sortable-list`, `dropdown`

- **Smart Detection**
  - Automatic Tailwind v3/v4 detection
  - Root namespace detection from `composer.json`
  - Package manager detection (npm, yarn, pnpm, bun)

- **Theme Support**
  - Base color selection (slate, zinc, gray, neutral, stone)
  - Accent color selection (blue, indigo, violet, rose, orange, emerald)
  - CSS variable injection for Tailwind v4

- **Dependency Management**
  - Automatic Composer package installation
  - Automatic npm package installation

### Dependencies

- PHP 8.2+
- Laravel 10/11/12
- `laravel/prompts` for interactive CLI
- `illuminate/filesystem` for file operations
