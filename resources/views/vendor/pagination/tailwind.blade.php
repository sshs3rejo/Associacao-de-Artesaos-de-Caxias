@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-center gap-2 flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-lg">
                    <x-icon name="chevron-left" class="w-4 h-4 mr-1" /> Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-accent bg-brand border border-brand leading-5 rounded-lg hover:bg-brand-dark transition ease-in-out duration-150">
                    <x-icon name="chevron-left" class="w-4 h-4 mr-1" /> Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-accent bg-brand border border-brand leading-5 rounded-lg hover:bg-brand-dark transition ease-in-out duration-150">
                    Próximo
                    <x-icon name="chevron-right" class="w-4 h-4 ml-1" />
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-lg">
                    Próximo
                    <x-icon name="chevron-right" class="w-4 h-4 ml-1" />
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium text-brand">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium text-brand">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium text-brand">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse rounded-lg overflow-hidden border border-gray-200">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white cursor-default leading-5" aria-hidden="true">
                                <x-icon name="chevron-left" class="w-4 h-4" />
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-brand bg-white leading-5 hover:text-brand-light hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-brand/30 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                            <x-icon name="chevron-left" class="w-4 h-4" />
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border-l border-gray-200 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-accent bg-brand leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-brand bg-white border-l border-gray-200 leading-5 hover:text-brand-light hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-brand/30 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-brand bg-white leading-5 hover:text-brand-light hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-brand/30 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                            <x-icon name="chevron-right" class="w-4 h-4" />
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-300 bg-white border-l border-gray-200 cursor-default leading-5" aria-hidden="true">
                                <x-icon name="chevron-right" class="w-4 h-4" />
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
