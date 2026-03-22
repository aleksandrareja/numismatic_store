@php
    $geowidgetToken    = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment       = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl     = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';
    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp


{{-- Przycisk i informacja o wybranym paczkomacie --}}
<div id="inpost-widget-wrapper" style="display:none;" class="mt-4 rounded-xl border border-zinc-200 bg-gray-100 p-5 shadow-sm">
    <h3 class="font-semibold mb-2 text-zinc-800">Wybierz paczkomat InPost</h3>

    <div id="inpost-selected" class="{{ $savedPointId ? '' : 'hidden' }}">
        <p><b id="inpost-point-name">{{ $savedPointId }}</b></p>
        <p id="inpost-point-address">{{ $savedPointAddress }}</p>
        <button type="button" onclick="inpostOpenWidget()" class="mt-2 rounded-lg bg-yellow-400 px-4 py-2.5 font-semibold text-black hover:bg-yellow-500">
            Zmień paczkomat
        </button>
    </div>

    <button
        id="inpost-open-btn"
        type="button"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }} mt-3 w-full rounded-lg bg-yellow-400 px-4 py-2.5 font-semibold text-black hover:bg-yellow-500"
    >
        Otwórz mapę paczkomatów InPost
    </button>
</div>

{{-- Modal z GeoWidgetem --}}
<div
    id="inpost-modal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:99999; align-items:center; justify-content:center;"
>
    <div style="background:#fff; width:90%; max-width:900px; height:85vh; border-radius:12px; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;">

        {{-- Nagłówek modala --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding:14px 20px; border-bottom:1px solid #e5e7eb; flex-shrink:0;">
            <span style="font-weight:600; font-size:16px;">📦 Wybierz paczkomat InPost</span>
            <button
                type="button"
                onclick="inpostCloseWidget()"
                style="background:none; border:none; font-size:22px; cursor:pointer; color:#6b7280; line-height:1; padding:4px 8px;"
            >✕</button>
        </div>

        {{-- Kontener na widget --}}
        <div id="inpost-map" style="flex:1; overflow:hidden; min-height:0;"></div>

    </div>
</div>