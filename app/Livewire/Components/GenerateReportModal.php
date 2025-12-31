<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class GenerateReportModal extends Component
{
    public string $modalID = 'generateReportModalLivewire', $modalTitle = 'Generate Report';
    public ?string $format = '';
    public ?string $from = '';
    public ?string $to = '';
    public ?string $model = '' ;

    public function generateReport()
    {        
        $this->validate(
            [
            'format' => 'required|in:pdf,xlsx',
            'from'   => 'required|date',
            'to'     => 'required|date',
            ],
            [
            'format.required' => 'Please select a report format.',
            'format.in' => 'The format must be either PDF or Excel.',
            'from.required' => 'Please select a start date.',
            'from.date' => 'The start date must be a valid date.',
            'to.required' => 'Please select an end date.',
            'to.date' => 'The end date must be a valid date.',
            ]
        );

        return redirect()->route('report.export', [
            'model' => $this->model,
            'format' => $this->format,
            'from' => $this->from,
            'to' => $this->to,
        ]);
        
    }

    #[On('reset-generator')]
    public function resetInput()
    {
        $this->reset(['format', 'from', 'to']);
    }

    public function render()
    {
        return view('livewire.components.generate-report-modal');
    }
}
