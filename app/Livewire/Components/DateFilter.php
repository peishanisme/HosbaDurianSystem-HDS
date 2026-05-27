<?php

namespace App\Livewire\Components;

use Livewire\Component;

class DateFilter extends Component
{
    public ?string $fromDate = null;
    public ?string $toDate = null;
    public ?string $dateFilter = null;
    public string $pickerId = 'date_range_picker';

    public function updatedDateFilter($value)
    {
        [$fromDate, $toDate] = array_pad(
            explode(' to ', $value),
            2,
            null
        );

        $this->fromDate = $fromDate;
        $this->toDate = $toDate;

        $this->dispatch(
            'date-range-updated',
            fromDate: $this->fromDate,
            toDate: $this->toDate
        );
    }

    public function clearDateFilter()
    {
        $this->fromDate = null;
        $this->toDate = null;
        $this->dateFilter = null;

        $this->dispatch('clear-date-picker');

        $this->dispatch(
            'date-range-updated',
            fromDate: null,
            toDate: null
        );
    }

    public function getShowClearButtonProperty()
    {
        return $this->fromDate || $this->toDate;
    }

    public function render()
    {
        return view('livewire.components.date-filter');
    }
}
