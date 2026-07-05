@if ($paginator->hasPages())
    <nav class="join">
        @if ($paginator->onFirstPage())
            <button class="join-item btn btn-disabled btn-sm">«</button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn btn-sm">«</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <button class="join-item btn btn-disabled btn-sm">{{ $element }}</button>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="join-item btn btn-warning btn-sm">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="join-item btn btn-sm">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn btn-sm">»</a>
        @else
            <button class="join-item btn btn-disabled btn-sm">»</button>
        @endif
    </nav>
@endif
