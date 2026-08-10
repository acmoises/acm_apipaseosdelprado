<?php

namespace App\Imports;

use App\Models\Resident;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Si tu archivo Excel tiene encabezados
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ResidentsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        return new Resident([
            'name' => $row['nombre'],
            'paternal_surname' => $row['appaterno'],
            'maternal_surname' => $row['apmaterno'],
            'phone_number' => $row['telefono'],
            'address' => $row['direccion'],
            'card_id' => $row['cardid']
        ]);
    }

    // Configuración de batch insert para mejorar el rendimiento
    public function batchSize(): int
    {
        return 1000;
    }

    // Configuración de chunk reading para mejorar el rendimiento
    public function chunkSize(): int
    {
        return 1000;
    }
}
