<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\AgrochemicalRecord;

class GenerateReportModal extends Component
{
    public string $modalID = 'generateReportModalLivewire', $modalTitle = 'Generate Report';
    public ?string $format = '';
    public ?string $from = '';
    public ?string $to = '';

    public function generateReport()
    {        
        $this->validate([
            'format' => 'required|in:pdf,csv',
            'from'   => 'required|date',
            'to'     => 'required|date',
        ]);

        return redirect()->route('report.export', [
            'model' => AgrochemicalRecord::class,
            'format' => $this->format,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }

    #[On('reset-generator')]
    public function resetGenerator()
    {
        $this->reset(['format', 'from', 'to']);
    }

    public function render()
    {
        return view('livewire.components.generate-report-modal');
    }
}
