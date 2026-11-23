@if ($paginator->hasPages())
<nav aria-label="Pagination Navigation" class="position-relative">

    {{-- GLOBAL LOADING --}}
    <div
        wire:loading.flex
        wire:target="gotoPage,previousPage,nextPage"
        class="position-absolute top-50 start-50 translate-middle bg-white px-3 py-2 rounded shadow-sm"
        style="z-index: 10;"
    >
        <span class="spinner-border spinner-border-sm text-primary"></span>
        <span class="ms-2 small">Memuat...</span>
    </div>

    <ul class="pagination justify-content-center flex-wrap"
        wire:loading.class="opacity-50"
        wire:target="gotoPage,previousPage,nextPage"
    >

        {{-- First --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <button
                type="button"
                class="page-link"
                wire:click.prevent="gotoPage(1)"
                wire:loading.attr="disabled"
            >
                <i class="ti ti-chevrons-left"></i>
            </button>
        </li>

        {{-- Prev --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <button
                type="button"
                class="page-link"
                wire:click.prevent="previousPage"
                wire:loading.attr="disabled"
            >
                <i class="ti ti-chevron-left"></i>
            </button>
        </li>

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <button
                            type="button"
                            class="page-link"
                            wire:click.prevent="gotoPage({{ $page }})"
                            wire:loading.attr="disabled"
                        >
                            {{ $page }}
                        </button>
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
            <button
                type="button"
                class="page-link"
                wire:click.prevent="nextPage"
                wire:loading.attr="disabled"
            >
                <i class="ti ti-chevron-right"></i>
            </button>
        </li>

        {{-- Last --}}
        <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
            <button
                type="button"
                class="page-link"
                wire:click.prevent="gotoPage({{ $paginator->lastPage() }})"
                wire:loading.attr="disabled"
            >
                <i class="ti ti-chevrons-right"></i>
            </button>
        </li>
    </ul>

    {{-- Info --}}
    <div class="text-center text-muted small mt-2">
        Halaman {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
    </div>

</nav>
@endif



