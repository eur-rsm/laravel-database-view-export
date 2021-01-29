<?php $exportService = app(\EUR\RSM\DatabaseViewExport\Services\BackofficeExportService::class); ?>

<dropdown>
    <dropdown-trigger class="h-9 flex items-center">
        <span class="text-90">Export</span>
    </dropdown-trigger>

    <dropdown-menu dusk="exports-list" slot="menu" width="200" direction="rtl">
        <ul class="list-reset">
            @if($exportService->all()->count() === 0)
                <li>
                    <a href="javascript:void(0)" class="block no-underline text-70 p-3 cursor-default">
                        No exports available
                    </a>
                </li>
            @else
                @foreach($exportService->all() as $export)
                    <li>
                        <a href="{{ $export->url() }}" class="block no-underline text-90 hover:bg-30 p-3">
                            {{ $export->label() }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </dropdown-menu>
</dropdown>
