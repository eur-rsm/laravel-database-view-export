<?php

namespace EUR\RSM\DatabaseViewExport\Http\Controllers;

use EUR\RSM\DatabaseViewExport\Services\BackofficeExportService;
use Symfony\Component\HttpFoundation\Response;

final class ExportController
{
    public function export(string $exportKey, BackofficeExportService $exportService): Response
    {
        $export = $exportService->findBySlug($exportKey);

        return rescue(
            fn(): Response => $export->download($export->filename()),
            fn() => abort(500, 'Unable to run the requested export. An exception was thrown (see logs).'),
        );
    }
}
