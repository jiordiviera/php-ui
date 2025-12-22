# Contributing to PHP-UI

Thank you for your interest in contributing to PHP-UI! We welcome contributions from everyone.

## Development Workflow

1. **Clone the repository**
2. **Install dependencies**: `composer install`
3. **Run tests**: `composer test`
4. **Code style**: We use Laravel Pint. Run `composer lint` before submitting a PR.

## Adding New Components

To add a new component to the library:

1. Create the Blade stub in `stubs/<name>.blade.php.stub`.
2. If the component needs logic, create `stubs/<name>.js.stub`.
3. Add the component definition to `src/Core/ComponentManifest.php`.
4. Ensure all text in the component is in **English**.
5. Run tests to ensure everything is working correctly.

## Pull Requests

- Create a descriptive branch name.
- Write clear commit messages.
- Ensure the CI (GitHub Actions) passes.
- Keep PRs focused on a single change.

## Security

If you discover a security vulnerability, please send an e-mail to jiordikengne@gmail.com.

## License

By contributing, you agree that your contributions will be licensed under its MIT License.