<?php

namespace App\Livewire\Components;

use App\Models\HarvestEvent;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class GenerateReportModal extends Component
{
    public string $modalID = 'generateReportModalLivewire', $modalTitle = 'Generate Report';
    public ?string $format = '';
    public ?string $from = '';
    public ?string $to = '';
    public ?string $model = '';
    public ?string $reportType = '';
    public ?HarvestEvent $harvestEvent = null;

    public function mount()
    {
        $this->from = now()->format('Y-m-d');
        $this->to   = now()->format('Y-m-d');
    }

    public function initDatePicker()
    {
        $this->dispatch('init-report-daterangepicker');
    }

    public function rules(): array
    {
        $rules = [
            'format'     => 'required|in:pdf,xlsx',
        ];

        if ($this->model === \App\Models\HarvestEvent::class) {
            $rules['reportType'] = 'required|in:species,record,tree';
        } else {
            $rules['from'] = 'required|date';
            $rules['to']   = 'required|date';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'reportType.required' => 'Please select a report type.',
            'reportType.in'       => 'Invalid report type selected.',
            'from.required'       => 'Please select a date range.',
            'to.required'         => 'Please select a date range.',
        ];
    }

    public function generateReport()
    {
        $this->validate();

        return redirect()->route('report.export', [
            'model' => $this->model,
            'format' => $this->format,
            'from' => $this->from,
            'to' => $this->to,
            'reportType' => $this->reportType,
            'harvest_uuid' => $this->harvestEvent?->uuid,
        ]);
    }

    #[On('reset-generator')]
    public function resetInput()
    {
        $this->reset(['format', 'from', 'to', 'reportType']);
    }

    public function render()
    {
        return view('livewire.components.generate-report-modal');
    }
}
