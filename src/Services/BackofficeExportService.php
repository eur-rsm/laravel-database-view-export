<?php

namespace EUR\RSM\DatabaseViewExport\Services;

use EUR\RSM\DatabaseViewExport\Exports\ViewExport;
use EUR\RSM\DatabaseViewExport\Models\Export as ExportModel;
use Illuminate\Support\Collection;

class BackofficeExportService
{
    /** @var \Illuminate\Support\Collection|null */
    protected $exports;

    /**
     * Get all available exports.
     * The data is cached so only 1 query is required to do all tasks.
     *
     * @return \Illuminate\Support\Collection|\EUR\RSM\DatabaseViewExport\Exports\ViewExport[]
     */
    public function all(): Collection
    {
        return $this->exports = $this->exports ?? ExportModel::all()
                ->map(function (ExportModel $export): ViewExport {
                    return new ViewExport($export->name, $export->view_name);
                });
    }

    /**
     * Run an export and download the resulting file.
     *
     * @param  \EUR\RSM\DatabaseViewExport\Exports\ViewExport  $export
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function runExport(ViewExport $export)
    {
        return $export->download($export->filename());
    }

    /**
     * Find an export class for the given key.
     *
     * @param  string  $key
     * @return \EUR\RSM\DatabaseViewExport\Exports\ViewExport
     */
    public function findByKey(string $key): ViewExport
    {
        $export = $this->all()->first(
            function (ViewExport $exportClass) use ($key): bool {
                return $exportClass->key() === $key;
            }
        );

        if (is_null($export)) {
            abort(404, 'Cannot find requested export.');
        }

        return $export;
    }
}
