@php
    $geowidgetToken = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment    = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl  = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';

    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp

@once
    <link rel="stylesheet" href="{{ $widgetBaseUrl }}/inpost-geowidget.css">
    <script src="{{ $widgetBaseUrl }}/inpost-geowidget.js" defer></script>
@endonce

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

<div id="inpost-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;width:100%;max-width:900px;height:80vh;border-radius:8px;display:flex;flex-direction:column;">
        <div style="display:flex;justify-content:space-between;padding:12px;border-bottom:1px solid #eee;">
            <span>📦 Paczkomaty InPost</span>
            <button type="button" onclick="inpostCloseWidget()">X</button>
        </div>
        <div id="inpost-map" style="flex:1;overflow:hidden;"></div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var METHOD_CODE  = 'inpost_inpost';
    var TOKEN        = '{{ $geowidgetToken }}';
    var SAVE_URL     = '{{ route('inpost.save-point') }}';
    var CSRF_TOKEN   = '{{ csrf_token() }}';

    /* ── helpers ── */
    function showWidget()  { document.getElementById('inpost-widget-wrapper').style.display = 'block'; }
    function hideWidget()  { document.getElementById('inpost-widget-wrapper').style.display = 'none';  }

    window.inpostOpenWidget = function () {
        var modal = document.getElementById('inpost-modal');
        modal.style.display = 'flex';

        var container = document.getElementById('inpost-map');
        if (!container.hasChildNodes()) {
            var widget = document.createElement('inpost-geowidget');
            widget.setAttribute('token', TOKEN);
            widget.setAttribute('language', 'pl');
            widget.setAttribute('config', 'parcelcollect');
            widget.setAttribute('onpoint', 'window.onInpostSelect');
            widget.style.width  = '100%';
            widget.style.height = '100%';
            widget.style.display = 'block';
            container.appendChild(widget);
        }
    };

    window.inpostCloseWidget = function () {
        document.getElementById('inpost-modal').style.display = 'none';
    };

    /* ── callback z GeoWidgetu ── */
    window.onInpostSelect = function (point) {
        inpostCloseWidget();

        var pointId = point.name;
        var addr    = point.address_details || {};
        var pointAddress = addr.street
            ? (addr.street + ' ' + (addr.building_number || '') + ', ' + (addr.post_code || '') + ' ' + (addr.city || '')).trim()
            : ((point.address && point.address.line1) || '');

        /* aktualizuj UI */
        document.getElementById('inpost-point-name').textContent    = pointId;
        document.getElementById('inpost-point-address').textContent = pointAddress;
        document.getElementById('inpost-selected').classList.remove('hidden');
        document.getElementById('inpost-open-btn').classList.add('hidden');

        /* zapisz do sesji i CartShippingRate */
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        fetch(SAVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : CSRF_TOKEN,
            },
            body: JSON.stringify({
                point_id:      pointId,
                point_name:    pointId,
                point_address: pointAddress,
            }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) {
                console.error('InPost: błąd zapisu paczkomatu', data);
            }
        })
        .catch(function (err) {
            console.error('InPost: błąd sieci', err);
        });
    };

    /* ── reaguj na zmianę metody wysyłki ── */
    document.addEventListener('change', function (e) {
        if (!e.target || e.target.name !== 'shipping_method') return;
        if (e.target.value === METHOD_CODE) {
            showWidget();
        } else {
            hideWidget();
        }
    });

    /* ── sprawdź stan początkowy (np. po odświeżeniu) ── */
    function checkInitial() {
        var checked = document.querySelector('input[name="shipping_method"]:checked');
        if (checked && checked.value === METHOD_CODE) {
            showWidget();
        }
    }

    /* Vue renderuje inputy asynchronicznie — obserwuj DOM */
    var obs = new MutationObserver(function () { checkInitial(); });
    obs.observe(document.body, { childList: true, subtree: true });
    setTimeout(function () { obs.disconnect(); }, 15000);
    checkInitial();

})();
</script>
@endpush