<?php

namespace App\Exports;

use App\Lead;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeadExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;
    protected $query;
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }
    public function map($lead): array
    {
        return [
            $lead->honorific,
            $lead->first_name,
            $lead->last_name,
            $lead->email,
            $lead->phone_number,
            $lead->mobile_number,
            $lead->company,
            $lead->office_address,
        ];
    }
    public function headings(): array
    {
        return [
            'Danh xưng',
            'Họ',
            'Tên',
            'Email',
            'SĐT',
            'Số di động',
            'Công ty',
            'Địa chỉ văn phòng',
            'Chủ sở hữu'
        ];
    }
}
