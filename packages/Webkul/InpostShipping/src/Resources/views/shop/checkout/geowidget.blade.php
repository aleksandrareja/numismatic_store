{{--
    InPost GeoWidget — wstrzykiwany przez View Render Event po liście metod wysyłki.

    Checkout Bagisto jest Vue.js + AJAX, więc nie możemy polegać na stanie PHP/Blade
    ($cart->shipping_method) — metoda jest zapisywana dopiero po kliknięciu w Vue.
    Widget jest zawsze renderowany w DOM, a jego widoczność kontroluje JavaScript
    nasłuchując na zmianę radio inputu shipping_method.
--}}

@php
    $geowidgetToken = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment    = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl  = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';

    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp

{{-- GeoWidget CSS + JS — ładowane raz na stronie --}}
@once
    <link rel="stylesheet" href="{{ $widgetBaseUrl }}/inpost-geowidget.css">
    <script defer src="{{ $widgetBaseUrl }}/inpost-geowidget.js"></script>
@endonce

{{--
    Wrapper — domyślnie ukryty (display:none).
    JavaScript pokaże go gdy klient wybierze metodę inpost_inpost.
--}}
<div
    id="inpost-widget-wrapper"
    style="display:none"
    class="mt-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm"
>
    <p class="mb-3 font-semibold text-gray-800">
        {{ __('inpost::app.geowidget.select-locker') }}
    </p>

    {{-- Wybrany paczkomat --}}
    <div
        id="inpost-selected-info"
        class="{{ $savedPointId ? '' : 'hidden' }} mb-3 flex items-start gap-3 rounded-md border border-green-300 bg-green-50 p-3"
    >
        <span class="mt-0.5 text-2xl">📦</span>
        <div class="flex-1">
            <p class="font-semibold text-green-800" id="inpost-point-name">{{ $savedPointId }}</p>
            <p class="text-sm text-green-700" id="inpost-point-address">{{ $savedPointAddress }}</p>
        </div>
        <button
            type="button"
            onclick="inpostOpenWidget()"
            class="shrink-0 text-sm text-blue-600 underline hover:text-blue-800"
        >{{ __('inpost::app.geowidget.change') }}</button>
    </div>

    {{-- Przycisk wyboru paczkomatu --}}
    <button
        type="button"
        id="inpost-open-btn"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }} inline-flex items-center gap-2 rounded-md bg-yellow-400 px-5 py-2.5 font-semibold text-black hover:bg-yellow-500"
    >
        📦 {{ __('inpost::app.geowidget.choose-locker') }}
    </button>

    {{-- Komunikat walidacji --}}
    <p id="inpost-validation-msg" class="mt-2 hidden text-sm text-red-600">
        {{ __('inpost::app.geowidget.validation-required') }}
    </p>
</div>

{{-- Modal z mapą GeoWidgetu --}}
<div
    id="inpost-modal"
    role="dialog"
    aria-modal="true"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 p-4"
>
    <div class="flex h-[90vh] w-full max-w-5xl flex-col rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-5 py-3">
            <span class="text-lg font-bold text-gray-900">
                📦 {{ __('inpost::app.geowidget.modal-title') }}
            </span>
            <button
                type="button"
                onclick="inpostCloseWidget()"
                class="text-3xl leading-none text-gray-500 hover:text-gray-800"
            >&times;</button>
        </div>
        <div class="flex-1 overflow-hidden" id="inpost-geowidget-container">
        </div>
    </div>
</div>

<script>
(function () {
    // ── Helpers ──────────────────────────────────────────────────────────────

    function inpostShowWidget() {
        document.getElementById('inpost-widget-wrapper').style.display = 'block';
    }

    function inpostHideWidget() {
        document.getElementById('inpost-widget-wrapper').style.display = 'none';
    }

    window.inpostOpenWidget = function () {
        var modal = document.getElementById('inpost-modal');
        modal.style.display = 'flex';

        // Wstrzyknij widget przez JS przy pierwszym otwarciu
        var container = document.getElementById('inpost-geowidget-container');
        if (!container.hasChildNodes()) {
            var widget = document.createElement('inpost-geowidget');
            widget.setAttribute('token', '{{ $geowidgetToken }}');
            widget.setAttribute('language', 'pl');
            widget.setAttribute('config', 'parcelcollect');
            widget.setAttribute('onpoint', 'window.onInpostPointSelected');
            widget.style.cssText = 'width:100%;height:100%;display:block;';
            container.appendChild(widget);
        }
    };

    window.inpostCloseWidget = function () {
        document.getElementById('inpost-modal').style.display = 'none';
    };

    // ── Callback wywoływany przez SDK GeoWidgetu InPost ───────────────────────

    window.onInpostPointSelected = function (point) {
        inpostCloseWidget();

        const pointId      = point.name;
        const addr         = point.address_details ?? {};
        const pointAddress = addr.street
            ? (addr.street + ' ' + (addr.building_number ?? '') + ', ' + (addr.post_code ?? '') + ' ' + (addr.city ?? '')).trim()
            : (point.address?.line1 ?? '');

        // Aktualizuj UI
        document.getElementById('inpost-point-name').textContent    = pointId;
        document.getElementById('inpost-point-address').textContent = pointAddress;
        document.getElementById('inpost-selected-info').classList.remove('hidden');
        document.getElementById('inpost-open-btn').classList.add('hidden');
        document.getElementById('inpost-validation-msg').classList.add('hidden');

        // Zapisz do backendu (sesja + CartShippingRate)
        fetch('{{ route('inpost.save-point') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                point_id:      pointId,
                point_name:    pointId,
                point_address: pointAddress,
            }),
        })
        .then(r => r.json())
        .catch(err => console.error('InPost save-point error:', err));
    };

    // ── Nasłuchuj na zmianę metody wysyłki (Vue zapisuje przez AJAX,
    //    ale input radio zmienia się natychmiast w DOM) ──────────────────────

    function handleShippingChange(e) {
        if (e.target && e.target.name === 'shipping_method') {
            if (e.target.value === 'inpost_inpost') {
                inpostShowWidget();
            } else {
                inpostHideWidget();
            }
        }
    }

    // Delegacja na document — działa z dynamicznie renderowanymi elementami Vue
    document.addEventListener('change', handleShippingChange);

    // ── Przywróć stan jeśli InPost był już wybrany (np. odświeżenie strony) ──

    function checkInitialState() {
        var checked = document.querySelector('input[name="shipping_method"]:checked');
        if (checked && checked.value === 'inpost_inpost') {
            inpostShowWidget();
        }
    }

    // Vue może jeszcze nie wyrenderować inputów przy DOMContentLoaded,
    // więc sprawdzamy po krótkim opóźnieniu i też przy MutationObserver
    document.addEventListener('DOMContentLoaded', function () {
        checkInitialState();

        var observer = new MutationObserver(function () {
            checkInitialState();
        });
        observer.observe(document.body, { childList: true, subtree: true });

        // Zatrzymaj observer po 10s (Vue powinien już skończyć renderowanie)
        setTimeout(function () { observer.disconnect(); }, 10000);
    });

})();
</script>