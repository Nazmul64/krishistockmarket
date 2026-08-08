<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
    <ul class="pagination" style="justify-content: center;margin-top: 10px;">



        @if($paginator->onFirstPage())
            <li class="paginate_button page-item previous disabled" id="example1_previous">
                <a href="{{ $paginator->previousPageUrl() }}" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
            </li>
        @else
            <li class="paginate_button page-item previous" id="example1_previous">
                <a href="{{ $paginator->previousPageUrl() }}" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
            </li>
        @endif




        @foreach($elements as $element)
        @if(is_array($element))
            @foreach($element as $page => $url)
                @if($page == $paginator->currentPage())
                <li class="paginate_button page-item active">
                        <a class="page-link">{{ $page }}</a>
                    </li>
                @else
                <li class="paginate_button page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach
        @endif
        @endforeach




        @if($paginator->hasMorePages())
            <li class="paginate_button page-item next" id="example1_next">
                <a href="{{ $paginator->nextPageUrl() }}" aria-controls="example1" data-dt-idx="3" tabindex="0" class="page-link">Next</a>
            </li>
        @else
            <li class="paginate_button page-item next disabled" id="example1_next">
                <a href="{{ $paginator->nextPageUrl() }}" aria-controls="example1" data-dt-idx="3" tabindex="0" class="page-link">Next</a>
            </li>
        @endif




    </ul>
</div>




{{-- {{ $gallery_images->links('pagination.index') }} --}}
