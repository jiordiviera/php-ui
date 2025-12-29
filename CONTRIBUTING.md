# Contributing to PHP-UI

Thank you for your interest in contributing to PHP-UI! We welcome contributions from everyone.

## Ways to Contribute

There are multiple ways to contribute to PHP-UI:

1. **Direct contributions** - Submit PRs to this repository
2. **Create your own registry** - Host custom components that others can install via `--registry`
3. **Share components** - Publish individual components that can be installed via `--url`
4. **Report issues** - Help us improve by reporting bugs or suggesting features

## Development Workflow

1. **Clone the repository**
2. **Install dependencies**: `composer install`
3. **Run tests**: `composer test`
4. **Code style**: We use Laravel Pint. Run `composer lint` before submitting a PR.

## Adding New Components

To add a new component to the library:

1. Create the Blade stub in `stubs/<name>.blade.php.stub`.
2. If the component needs JavaScript, create `stubs/<name>.js.stub`.
3. Add the component definition to `registry.json`.
4. Ensure all text in the component is in **English**.
5. Run tests to ensure everything is working correctly.

## Creating a Custom Registry

You can create and host your own component registry:

1. Create a `registry.json` file following this structure:

```json
{
  "name": "My Custom Components",
  "version": "1.0.0",
  "baseUrl": "https://raw.githubusercontent.com/username/my-components/main",
  "components": {
    "my-component": {
      "description": "Description of your component",
      "dependencies": {
        "composer": [],
        "npm": []
      },
      "css_vars": {},
      "js_stubs": []
    }
  }
}
```

1. Create a `stubs/` folder with your component files
2. Host it on GitHub or any static hosting
3. Users can install via: `php-ui add my-component --registry https://your-url/registry.json`

## Sharing Individual Components

You can also share single components without a full registry:

1. Host your `.blade.php.stub` file publicly
2. Users can install via: `php-ui add --url https://your-url/component.blade.php.stub`

## Pull Requests

- Create a descriptive branch name.
- Write clear commit messages.
- Ensure the CI (GitHub Actions) passes.
- Keep PRs focused on a single change.

## Security

If you discover a security vulnerability, please send an e-mail to <jiordikengne@gmail.com>.

## License

By contributing, you agree that your contributions will be licensed under its MIT License.
