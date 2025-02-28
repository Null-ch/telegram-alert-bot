<?php

namespace App\Repositories;

use App\Models\Report;

class ReportRepository
{
    public Report $report;

    public function __construct(
    ) {
        $this->report = new Report;
    }

    public function create(array $data): string
    {
        $report = $this->report::create($data);
        return $report->id;
    }
}
