<?php

namespace EUR\RSM\DatabaseViewExport\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Excel;

/**
 * Represents a database view that can be exported to Excel.
 */
final class ViewExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    /** @var string */
    private $label;

    /**
     * Name of the view (table) to base the export on.
     *
     * @var string
     */
    private $viewName;

    /**
     * @see \Maatwebsite\Excel\Excel::XLSX
     * @see \Maatwebsite\Excel\Excel::CSV
     * @var string
     */
    private $exportType = Excel::XLSX;

    /**
     * @param  string  $label
     * @param  string  $viewName
     */
    public function __construct(string $label, string $viewName)
    {
        $this->label = $label;
        $this->viewName = $viewName;
    }

    /**
     * Key (unique) that identifies the export.
     *
     * @return string
     */
    public function key(): string
    {
        return Str::of($this->label())->lower()->slug();
    }

    /**
     * Human-friendly label for this export, displayed in the dropdown menu.
     *
     * @return string
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * URL to access this export, used in the dropdown menu.
     *
     * @return string
     */
    public function url(): string
    {
        return route('database-view-export.export', $this->key());
    }

    /**
     * Collect the data that need to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return DB::table($this->viewName)->select()->get();
    }

    /**
     * Get the headings (columns) that are inserted as the first row.
     *
     * @return array
     */
    public function headings(): array
    {
        return DB::getSchemaBuilder()->getColumnListing($this->viewName);
    }

    /**
     * Determine the filename for the exported file.
     *
     * @param  string  $extension
     * @return string
     */
    public function filename(): string
    {
        return $this->key() . '_' . Carbon::now()->toDateTimeString() . '.' . strtolower($this->exportType);
    }
}
