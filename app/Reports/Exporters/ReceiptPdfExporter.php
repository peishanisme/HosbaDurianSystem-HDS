<?php

namespace App\Reports\Exporters;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;

class ReceiptPdfExporter
{
    public function export(Transaction $transaction)
    {
        $summary = $transaction->summary;

        $companyName = 'Hosba Durian Farm Sdn Bhd';
        $companyAddress = 'Kampung Alor Janggus, 06000 Jitra, Kedah, Malaysia';

        $pdf = Pdf::loadView(
            'components.documents.transaction-receipt',
            compact('transaction', 'summary', 'companyName', 'companyAddress')
        );

        return $pdf->download(
            "receipt-{$transaction->reference_id}.pdf"
        );
    }
}
