<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivityLogsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $logs;

    /**
     * Constructor
     *
     * @param \Illuminate\Database\Eloquent\Collection $logs
     */
    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->logs;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Usuario',
            'Acción',
            'Descripción',
            'Modelo',
            'ID Modelo',
            'Dirección IP',
            'Fecha',
            'Hora',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->user ? $row->user->name : 'Sistema',
            $row->action,
            $row->description,
            $row->model_type ? class_basename($row->model_type) : '',
            $row->model_id,
            $row->ip_address,
            $row->created_at->format('Y-m-d'),
            $row->created_at->format('H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     *
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA'],
                ],
            ],
        ];
    }
}
