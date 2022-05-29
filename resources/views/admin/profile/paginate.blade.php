@if ($paginator->hasPages())
<div class="col-sm-12 col-md-12 align-self-start">
    Showing {{$paginator->firstItem()}} to {{$paginator->lastItem()}} of {{ $paginator->total() }} entries
</div>
<div class="col-sm-3 col-md-3">
    <ul class="pagination">
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link" aria-hidden="true">Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
                    </li>
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                @endif
    
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @elseif (($page == $paginator->currentPage() + 1 || $page == $paginator->currentPage() + 2) || $page == $paginator->lastPage())
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @elseif ($page == $paginator->lastPage() - 1)
                                <li class="page-item disabled"><span class="page-link"><i class="fa fa-ellipsis-h"></i></span></li>
                                
                            @endif
                        @endforeach
                    @endif
                @endforeach
    
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true">Next</span>
                    </li>
                @endif
            </ul>
        </nav>
    </ul>
</div>
@endif
