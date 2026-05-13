<section class="header">
    @include('partials.auth')
    @php
        $hasTitleEs = !empty($title_es);
        $hasTitleIcon = !empty($image_url);
        $hasEnhancedTitle = $hasTitleEs || $hasTitleIcon;
    @endphp
    <div class="row row-2 {{ $hasEnhancedTitle ? 'row-2-bilingual' : '' }} {{ $hasTitleIcon && !$hasTitleEs ? 'row-2-title-icon-only' : '' }}"
        style="display: flex; align-items: center; justify-content: center; min-height: 60px; height: auto;">
        <div style="flex: 1; text-align: center;" class="{{ $hasEnhancedTitle ? 'header-title-row' : '' }}">
            @if (!empty($title))
                <h3 class="header-title {{ $hasEnhancedTitle ? 'header-title-bilingual' : '' }}">
                    @if ($hasEnhancedTitle)
                        <span class="header-title-copy">
                            <span class="header-title-en">{{ $title }}</span>
                            @if ($hasTitleEs)
                                <span class="header-title-divider"></span>
                                <span class="header-title-es">{{ $title_es }}</span>
                            @endif
                            @if ($hasTitleIcon)
                                <span class="header-title-icon-wrap">
                                    <img src="{{ \App\Support\VersionedAsset::url($image_url) }}"
                                        class="header-title-icon {{ $image_class ?? '' }}" alt="{{ env('APP_TITLE') }}">
                                </span>
                            @endif
                        </span>
                    @else
                        {{ $title }}
                    @endif
                </h3>
            @endif
        </div>
        @if (!empty($show_how_to_scan))
            <div style="flex-shrink: 0; padding-right: 20px;">
                <button type="button" data-toggle="modal" data-target="#howToScanModal"
                    style="background: #6a3aab; color: #fff; border: none; border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 6px; line-height: 1.2;">
                    <img src="{{ asset('/images/iconimages/humanicon48.png') }}" alt=""
                        style="width: 22px; filter: brightness(0) invert(1);">
                    <span>HOW<br>TO<br>SCAN</span>
                </button>
            </div>
        @endif
    </div>
    @if (!empty($menu) && $menu == 'bioconnect')
        @include('partials.bioconnect.menu')
    @elseif (!empty($menu) && $menu == 'data_cache')
        @include('partials.data_cache.menu')
    @else
        @include('partials.menu')
    @endif
</section>
@include('partials.flash_messages')
