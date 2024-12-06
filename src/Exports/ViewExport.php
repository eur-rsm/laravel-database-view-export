<?php

namespace EUR\RSM\DatabaseViewExport\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

    /** @var string */
    private $slug;

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
    public function __construct(string $slug, string $label, string $viewName)
    {
        $this->label = $label;
        $this->slug = $slug;
        $this->viewName = $viewName;

        if (!$this->isView($viewName)) {
            throw new \RuntimeException("The specified view name '{$viewName}' is not a valid view.");
        }
    }

    /**
     * Key (unique) that identifies the export.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->slug();
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
     * Slug for this export, used in the URL.
     *
     * @return string
     */
    public function slug(): string
    {
        return $this->slug;
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

    /**
     * Check if the given name is a view.
     *
     * @param  string  $viewName
     * @return bool
     */
    private function isView(string $viewName): bool
    {
        return DB::select("SELECT TABLE_TYPE FROM information_schema.tables WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?", [
                $viewName,
                config('database.connections.' . config('database.default') . '.database'),
            ])[0]->TABLE_TYPE === 'VIEW';
    }
}
