<ul class="pagination pagination-primary justify-content-end mt-2">
    <!-- First Page -->
    @if ($data->onFirstPage())
        <li class="page-item disabled"><span class="page-link" aria-hidden="true">&laquo;</span></li>
    @else
        <li class="page-item prev">
            <a class="page-link" href="{{ $data->url(1) }}" aria-label="First Page">&laquo;&laquo;</a>
        </li>
    @endif

    <!-- Previous Page -->
    @if ($data->onFirstPage())
        <li class="page-item disabled"><span class="page-link" aria-hidden="true">&lsaquo;</span></li>
    @else
        <li class="page-item prev">
            <a class="page-link" href="{{ $data->previousPageUrl() }}" aria-label="Previous Page">&lsaquo;</a>
        </li>
    @endif

    <!-- Page Links -->
    @for ($page = max(1, $data->currentPage() - 2); $page <= min($data->lastPage(), $data->currentPage() + 2); $page++)
        <li class="page-item @if ($page == $data->currentPage()) active disabled @endif">
            <a class="page-link" href="{{ $data->url($page) }}">{{ $page }}</a>
        </li>
    @endfor

    <!-- Next Page -->
    @if ($data->hasMorePages())
        <li class="page-item next">
            <a class="page-link" href="{{ $data->nextPageUrl() }}" aria-label="Next Page">&rsaquo;</a>
        </li>
    @else
        <li class="page-item disabled"><span class="page-link" aria-hidden="true">&rsaquo;</span></li>
    @endif

    <!-- Last Page -->
    @if ($data->hasMorePages())
        <li class="page-item next">
            <a class="page-link" href="{{ $data->url($data->lastPage()) }}" aria-label="Last Page">&raquo;</a>
        </li>
    @else
        <li class="page-item disabled"><span class="page-link" aria-hidden="true">&raquo;</span></li>
    @endif
</ul>