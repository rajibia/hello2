<?php

namespace App\Exports;

use Illuminate\Support\Collection;

trait Exportable
{
    protected Collection $data;
    protected string $title;

    public function __construct(Collection $data, string $title = 'Report')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    
    abstract public function headings(): array;
    abstract public function map($row): array;
}