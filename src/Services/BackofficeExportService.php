<?php

namespace EUR\RSM\DatabaseViewExport\Services;

use EUR\RSM\DatabaseViewExport\Exports\ViewExport;
use EUR\RSM\DatabaseViewExport\Models\Export as ExportModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BackofficeExportService
{
    protected ?Collection $exports = null;

    public function all(): Collection
    {
        return $this->exports = $this->exports ?? ExportModel::all()
            ->map(function (ExportModel $export): ViewExport {
                return new ViewExport($export->name, $export->view_name);
            });
    }

    public function runExport(ViewExport $export)
    {
        return $export->download($export->filename());
    }

    public function findByKey(string $key): ViewExport
    {
        $key = Str::lower($key);
        $export = $this->all()->first(fn(ViewExport $exportClass): bool => $exportClass->key() === $key);

        if (is_null($export)) {
            abort(404, 'Cannot find requested export.');
        }

        return $export;
    }
}
