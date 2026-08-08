<nav class="ow-pagination">
    <ul class="pagination justify-content-center">

        @if($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                    <i class="fa fa-angle-double-left"></i>
                    Prev
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                    <i class="fa fa-angle-double-left"></i>
                    Prev
                </a>
            </li>
        @endif


        @foreach($elements as $element)
        @if(is_array($element))
            @foreach($element as $page => $url)
                @if($page == $paginator->currentPage())
                    <li class="page-item active">
                        <a class="page-link">{{ $page }}</a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach
        @endif
    @endforeach



        @if($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                    Next
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link disabled" href="{{ $paginator->nextPageUrl() }}">
                    Next
                    <i class="fa fa-angle-double-right"></i>
                </a>
        </li>
        @endif

    </ul>
</nav>
