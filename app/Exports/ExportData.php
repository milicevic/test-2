<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportData implements FromCollection, WithHeadings, ShouldAutoSize, ShouldQueue
{
    protected $query;
    protected $fields;

    public function __construct($query, $fields)
    {
        $this->query = $query;
        $this->fields = $fields;
    }

    public function collection()
    {
        return $this->query
            ->select(array_keys($this->fields))
            ->get();
    }

    public function headings(): array
    {
        return collect($this->fields)
            ->pluck('label')
            ->toArray();
    }
}
