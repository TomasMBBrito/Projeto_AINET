<?php
namespace App\Exports;

use App\Models\Operation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Operation::where('type', 'debit')
            ->where('debit_type', 'order')
            ->select('id', 'card_id', 'value', 'date')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Card ID', 'Value (€)', 'Date'];
    }
}
