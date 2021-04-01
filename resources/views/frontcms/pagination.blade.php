@if($paginator->hasPages())
    <nav class="pagination-outer clearfix" aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item">
                <a href="{{ (!$paginator->onFirstPage()) ? $paginator->previousPageUrl() : ''}}" class="page-link"
                   aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <li class="page-item">
                <a href="{{($paginator->hasMorePages()) ? $paginator->nextPageUrl() : ''}}" class="page-link"
                   aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
{{-- @if($paginator->hasPages())
<nav aria-label="Page navigation">
    <ul class="pagination">
      <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
      <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
    </ul>
  </nav>
  @endif --}}