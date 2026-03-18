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
    <script src="{{ $widgetBaseUrl }}/inpost-geowidget.js"></script>
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

<div id="inpost-modal" style="display:none" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-4xl h-[80vh] rounded shadow">
        <div class="flex justify-between p-3 border-b">
            <span>📦 Paczkomaty InPost</span>
            <button onclick="inpostCloseWidget()">X</button>
        </div>

        <div id="inpost-map" style="height:100%"></div>
    </div>
</div>

<script>
(function () {

    const METHOD_CODE = 'inpost_inpost'; // 🔥 TU MUSI BYĆ TWOJE

    function getSelectedShipping() {
        const el = document.querySelector('input[name="shipping_method"]:checked');
        return el ? el.value : null;
    }

    function toggleWidget() {
        const wrapper = document.getElementById('inpost-widget-wrapper');

        if (!wrapper) return;

        if (getSelectedShipping() === METHOD_CODE) {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    }

    // 🔥 KLUCZ: działa z Vue (delegacja eventów)
    document.addEventListener('click', function (e) {
        if (e.target.name === 'shipping_method') {
            setTimeout(toggleWidget, 200);
        }
    });

    window.inpostOpenWidget = function () {

        if (!window.customElements.get('inpost-geowidget')) {
            alert('Widget InPost się nie załadował');
            return;
        }

        document.getElementById('inpost-modal').style.display = 'flex';

        const map = document.getElementById('inpost-map');

        if (!map.hasChildNodes()) {
            const widget = document.createElement('inpost-geowidget');

            widget.setAttribute('token', '{{ $geowidgetToken }}');
            widget.setAttribute('language', 'pl');
            widget.setAttribute('config', 'parcelcollect');
            widget.setAttribute('onpoint', 'onInpostSelect');

            widget.style.width = '100%';
            widget.style.height = '100%';

            map.appendChild(widget);
        }
    };

    window.inpostCloseWidget = function () {
        document.getElementById('inpost-modal').style.display = 'none';
    };

    window.onInpostSelect = function (point) {

        inpostCloseWidget();

        const id = point.name;

        const address = point.address?.line1 || '';

        document.getElementById('inpost-point-name').innerText = id;
        document.getElementById('inpost-point-address').innerText = address;

        document.getElementById('inpost-selected').classList.remove('hidden');
        document.getElementById('inpost-open-btn').classList.add('hidden');

        fetch('{{ route('inpost.save-point') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                point_id: id,
                point_address: address
            })
        });
    };

    // 🔥 start po załadowaniu + po Vue
    setTimeout(toggleWidget, 500);
    setTimeout(toggleWidget, 1500);

})();
</script>