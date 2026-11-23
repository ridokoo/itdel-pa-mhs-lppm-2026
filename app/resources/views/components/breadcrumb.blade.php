<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-info-subtle px-3 py-2 rounded">
        @foreach ($breadcrumb as $key => $value)
            @if ($value)
                <li class="breadcrumb-item">
                    <a href="{{ $value }}" class="text-info d-flex align-items-center">
                        @if ($key != 'Home')
                            {{ $key }}
                        @else
                            <i class="ti ti-home fs-4 mt-1"></i>
                        @endif
                    </a>
                </li>
            @else
                <li class="breadcrumb-item text-info active" aria-current="page">
                    {{ $key }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>



