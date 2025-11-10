<style>
    .pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        margin: 2rem 0;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-item {
        margin: 0;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        padding: 0;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: #ffffff;
        color: #495057;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
        min-width: 40px;
    }

    .page-link:hover {
        background-color: #FF8E4B;
        border-color: #FF8E4B;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(255, 142, 75, 0.3);
    }

    .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 142, 75, 0.25);
        outline: none;
    }

    .page-item.active .page-link {
        background-color: #FF8E4B;
        border-color: #FF8E4B;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(255, 142, 75, 0.3);
    }

    .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .page-item.disabled .page-link:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        transform: none;
        box-shadow: none;
    }

    .page-link.prev,
    .page-link.next {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .page-link.prev i,
    .page-link.next i {
        font-size: 0.875rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-link {
            width: 36px;
            height: 36px;
            min-width: 36px;
            font-size: 0.8rem;
        }

        .pagination {
            gap: 0.25rem;
        }

        .page-link {
            margin: 0 1px;
        }
    }

    @media (max-width: 480px) {
        .page-link {
            width: 32px;
            height: 32px;
            min-width: 32px;
            font-size: 0.75rem;
        }
    }
</style>

@if ($paginator->hasPages())
    <div class="pagination-container">
        {{-- Results Info --}}
        <div class="pagination-info">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </div>

        {{-- Pagination Navigation --}}
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link prev" aria-hidden="true">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link prev" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link next" aria-hidden="true">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
