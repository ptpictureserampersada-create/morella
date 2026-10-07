<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    protected static ?array $icons = null;

    public string $svg = '';

    public function __construct(
        public string $name,
        public ?string $class = null,
    ) {
        $body = self::icons()[$name] ?? '';

        $this->svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"'
            .' stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"'
            .($class !== null ? ' class="'.$class.'"' : '')
            .'>'.$body.'</svg>';
    }

    protected static function icons(): array
    {
        if (self::$icons === null) {
            self::$icons = json_decode((string) file_get_contents(storage_path('app/lucide-icons.json')), true) ?? [];
        }

        return self::$icons;
    }

    public function render()
    {
        return 'components.icon';
    }
}
