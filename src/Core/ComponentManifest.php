<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core;

use Illuminate\Support\Collection;

class ComponentManifest
{
    /**
     * Get the configuration for a specific component.
     */
    public static function get(string $component): ?array
    {
        return self::all()[$component] ?? null;
    }

    /**
     * Get all available components.
     */
    public static function all(): Collection
    {
        return collect([
            'button' => [
                'description' => 'A versatile button component with variants, sizes, and icon support.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'input' => [
                'description' => 'Form input field with label, error handling, password toggle, and icons.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'modal' => [
                'description' => 'Accessible modal dialog with backdrop, transition animations, and size options.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'accordion' => [
                'description' => 'Expandable panels for organizing content.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['accordion.js'],
            ],
            'date-picker' => [
                'description' => 'A pure Alpine.js date picker with month navigation.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['date-picker.js'],
            ],
            'toast' => [
                'description' => 'Notification system with support for multiple types (success, error, etc.) and positioning.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'dropdown' => [
                'description' => 'Customizable dropdown/select menu with search capabilities.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'alert' => [
                'description' => 'Contextual feedback messages for user actions.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'badge' => [
                'description' => 'Small status indicators for items.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'breadcrumbs' => [
                'description' => 'Navigation trail for nested pages.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'avatar' => [
                'description' => 'User profile image with fallback initials and status.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'avatar-group' => [
                'description' => 'Stacked group of avatars.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
                'files' => [
                    'avatar-group.blade.php.stub' => 'avatar-group.blade.php',
                    'avatar.blade.php.stub' => 'avatar.blade.php',
                ],
            ],
            'toggle' => [
                'description' => 'Switch toggle input for boolean values.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'tooltip' => [
                'description' => 'Popup information on hover/focus.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'tabs' => [
                'description' => 'Tabbed interface for switching content panels.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
                'files' => [
                    'tabs.blade.php.stub' => 'tabs.blade.php',
                    'tabs/list.blade.php.stub' => 'tabs/list.blade.php',
                    'tabs/trigger.blade.php.stub' => 'tabs/trigger.blade.php',
                    'tabs/content.blade.php.stub' => 'tabs/content.blade.php',
                ],
            ],
            'progress-bar' => [
                'description' => 'Visual indicator of progress.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'file-upload' => [
                'description' => 'File upload zone with drag-and-drop and progress bar.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
                'files' => [
                    'file-upload.blade.php.stub' => 'file-upload.blade.php',
                    'progress-bar.blade.php.stub' => 'progress-bar.blade.php',
                ],
            ],
            'carousel' => [
                'description' => 'Image carousel with autoplay and navigation.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['carousel.js'],
            ],
            'drawer' => [
                'description' => 'Side panel that slides in from the screen edges.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'skeleton' => [
                'description' => 'Placeholder loading states for content.',
                'dependencies' => [
                    'composer' => [],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'rating' => [
                'description' => 'Star rating component with read-only and half-star support.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['rating.js'],
            ],
            'popover' => [
                'description' => 'Floating panel for detailed information or actions.',
                'dependencies' => [
                    'composer' => [],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'kbd' => [
                'description' => 'Keyboard key indicators.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'stepper' => [
                'description' => 'Progress indicator for multi-step workflows.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['stepper.js'],
            ],
            'timeline' => [
                'description' => 'Chronological display of events or activities.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'progress-steps' => [
                'description' => 'Horizontal progress tracker with icons and labels.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'stat-card' => [
                'description' => 'Card displaying statistics with trends and icons.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'data-table' => [
                'description' => 'Powerful data table with searching, sorting, and pagination.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'command-palette' => [
                'description' => 'Global search and command interface.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['command-palette.js'],
            ],
            'sortable-list' => [
                'description' => 'Drag and drop sortable list.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => ['sortablejs'],
                ],
                'css_vars' => [],
                'js_stubs' => ['sortable-list.js'],
            ],
            'range-slider' => [
                'description' => 'Single or double handle numeric slider.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['range-slider.js'],
            ],
            'empty-state' => [
                'description' => 'Placeholder for empty data collections.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => [],
            ],
            'code-snippet' => [
                'description' => 'Code display with line numbers and copy functionality.',
                'dependencies' => [
                    'composer' => ['mallardduck/blade-lucide-icons'],
                    'npm' => [],
                ],
                'css_vars' => [],
                'js_stubs' => ['code-snippet.js'],
            ],
        ]);
    }
}
