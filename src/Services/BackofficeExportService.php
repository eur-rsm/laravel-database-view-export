<?php

namespace EUR\RSM\DatabaseViewExport\Services;

use EUR\RSM\DatabaseViewExport\Exports\ViewExport;
use EUR\RSM\DatabaseViewExport\Models\Export as ExportModel;
use Illuminate\Support\Collection;

class BackofficeExportService
{
    protected ?Collection $exports = null;

    public function all(): Collection
    {
        return $this->exports = $this->exports ?? ExportModel::query()
            ->orderBy('name')
            ->chunkMap(fn(ExportModel $export): ViewExport => new ViewExport($export));
    }

    public function findBySlug(string $slug): ViewExport
    {
        $export = ExportModel::query()->where('slug', $slug)->first();

        if (is_null($export)) {
            abort(404, 'Cannot find requested export.');
        }

        return new ViewExport($export);
    }
}
