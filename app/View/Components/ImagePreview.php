<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImagePreview extends Component
{
    /**
     * Create a new component instance.
     */
    public string $src;
    public string $alt;
    public string $modalId;
    public int $thumbSize;
    public int $maxSize;

    public function __construct(
        string $src,
        string $alt = 'Image Preview',
        string $modalId = 'imagePreviewModal',
        int $thumbSize = 160,
        int $maxSize = 600
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->modalId = $modalId;
        $this->thumbSize = $thumbSize;
        $this->maxSize = $maxSize;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.image-preview');
    }
}
