<?php

namespace EUR\RSM\DatabaseViewExport\Http\Controllers;

use EUR\RSM\DatabaseViewExport\Services\BackofficeExportService;
use Symfony\Component\HttpFoundation\Response;

final class ExportController
{
    /**
     * @param  string  $exportKey
     * @param  \EUR\RSM\DatabaseViewExport\Services\BackofficeExportService  $exportService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function export(string $exportKey, BackofficeExportService $exportService): Response
    {
        $export = $exportService->findByKey($exportKey);

        return rescue(
            function () use ($export, $exportService): Response {
                return $exportService->runExport($export);
            },
            function (): void {
                abort(500, 'Unable to run the requested export. An exception was thrown (see logs).');
            }
        );
    }
}
