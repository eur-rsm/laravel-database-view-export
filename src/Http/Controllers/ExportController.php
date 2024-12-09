<?php

namespace EUR\RSM\DatabaseViewExport\Http\Controllers;

use EUR\RSM\DatabaseViewExport\Services\BackofficeExportService;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class ExportController
{
    public function export(string $exportKey, BackofficeExportService $exportService): Response
    {
        return rescue(
            function () use ($exportKey, $exportService): Response {
                $export = $exportService->findBySlug($exportKey);

                if (!$export->isView()) {
                    throw new RuntimeException("The specified export '{$exportKey}' is not a valid view.", 1733750033);
                }

                return $export->download($export->filename());
            }, fn() => abort(500, 'Unable to run the requested export. An exception was thrown (see logs).'),
        );
    }
}
