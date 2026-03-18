@php
    $geowidgetToken = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment    = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';

    $widgetBaseUrl  = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';

    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp


<div id="inpost-widget-wrapper" style="display:none" class="mt-4 p-4 border rounded bg-white">
    <h3 class="font-semibold mb-2">📦 Wybierz paczkomat</h3>

    <div id="inpost-selected" class="{{ $savedPointId ? '' : 'hidden' }}">
        <p><b id="inpost-point-name">{{ $savedPointId }}</b></p>
        <p id="inpost-point-address">{{ $savedPointAddress }}</p>

        <button type="button" onclick="inpostOpenWidget()" class="mt-2 text-blue-600 underline">
            Zmień paczkomat
        </button>
    </div>

    <button
        id="inpost-open-btn"
        type="button"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }} mt-2 px-4 py-2 bg-yellow-400 rounded"
    >
        Wybierz paczkomat
    </button>
</div>

<div id="inpost-modal" style="display:none" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-4xl h-[80vh] rounded shadow">
        <div class="flex justify-between p-3 border-b">
            <span>📦 Paczkomaty InPost</span>
            <button onclick="inpostCloseWidget()">X</button>
        </div>

        <div id="inpost-map" style="height:100%"></div>
    </div>
</div>




<div id="inpost-widget-wrapper" style="display:none" class="mt-4 p-4 border rounded bg-white">
    <h3 class="font-semibold mb-2">📦 Wybierz paczkomat</h3>

    <div id="inpost-selected" class="{{ $savedPointId ? '' : 'hidden' }}">
        <p><b id="inpost-point-name">{{ $savedPointId }}</b></p>
        <p id="inpost-point-address">{{ $savedPointAddress }}</p>

        <button type="button" onclick="inpostOpenWidget()" class="mt-2 text-blue-600 underline">
            Zmień paczkomat
        </button>
    </div>

    <button
        id="inpost-open-btn"
        type="button"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }} mt-2 px-4 py-2 bg-yellow-400 rounded"
    >
        Wybierz paczkomat
    </button>
</div>

<div id="inpost-modal" style="display:none" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-4xl h-[80vh] rounded shadow">
        <div class="flex justify-between p-3 border-b">
            <span>📦 Paczkomaty InPost</span>
            <button onclick="inpostCloseWidget()">X</button>
        </div>

        <div id="inpost-map" style="height:100%"></div>
    </div>
</div>

