<?php

namespace App\Reports\Exporters;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;

class ReceiptPdfExporter
{
    public function export(Transaction $transaction)
    {
        $summary = $transaction->summary;
        dd($summary);

        $companyName = 'Hosba Durian Farm Sdn Bhd';
        $companyAddress = 'Lot 29, Kawasan Perindustrian Sg.Laka, 06050 Bukit Kayu Hitam, Kedah Darulaman.';

        $pdf = Pdf::loadView(
            'components.documents.transaction-receipt',
            compact('transaction', 'summary', 'companyName', 'companyAddress')
        );

        return $pdf->download(
            "receipt-{$transaction->reference_id}.pdf"
        );
    }
}
