<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Excel as MaatwebsiteExcel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HasHeaders implements ValidationRule
{
    protected array $requiredHeaders;
    protected string $messageText = 'Headers not found in file';

    public function __construct(array $requiredHeaders)
    {
        $this->requiredHeaders = $requiredHeaders;
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $headers = $this->parseExcelHeaders($value);
        $missing = array_diff(array_keys($this->requiredHeaders), $headers);

        if (!empty($missing)) {
            $this->messageText = 'Missing required headers from file(' . $value->getClientOriginalName() . '): ' . implode(', ', $missing);
            $fail($this->messageText);
        }
    }
    private function parseExcelHeaders($file): array
    {
        $extension = is_string($file)
            ? pathinfo($file, PATHINFO_EXTENSION)
            : strtolower($file->getClientOriginalExtension());

        $format = match ($extension) {
            'csv' => MaatwebsiteExcel::CSV,
            'xls' => MaatwebsiteExcel::XLS,
            default => MaatwebsiteExcel::XLSX,
        };

        // Import headers
        $sheetData = (new HeadingRowImport)->toArray($file, null, $format)[0] ?? [];

        if (empty($sheetData)) {
            return [];
        }

        $headers = [];

        foreach ($sheetData as $row) {
            if (is_array($row)) {
                $headers = array_merge($headers, array_map('trim', $row));
            }
        }
        $headers = array_values(array_unique(array_filter($headers)));

        return $headers;
    }

    public function message()
    {
        return $this->messageText;
    }
}
