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

<div
    id="inpost-widget-wrapper"
    style="display:none"
    class="mt-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm"
>
    <p class="mb-3 font-semibold text-gray-800">
        Wybierz paczkomat InPost
    </p>

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
        >Zmień paczkomat</button>
    </div>

    <button
        type="button"
        id="inpost-open-btn"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }} inline-flex items-center gap-2 rounded-md bg-yellow-400 px-5 py-2.5 font-semibold text-black hover:bg-yellow-500"
    >
        📦 Wybierz paczkomat
    </button>

    <p id="inpost-validation-msg" class="mt-2 hidden text-sm text-red-600">
        Proszę wybrać paczkomat przed kontynuowaniem
    </p>
</div>

<div
    id="inpost-modal"
    style="display:none"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4"
>
    <div class="flex h-[90vh] w-full max-w-5xl flex-col rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-5 py-3">
            <span class="text-lg font-bold text-gray-900">
                📦 Wybierz paczkomat InPost
            </span>
            <button
                type="button"
                onclick="inpostCloseWidget()"
                class="text-3xl leading-none text-gray-500 hover:text-gray-800"
            >X</button>
        </div>
        <div class="flex-1 overflow-hidden" id="inpost-geowidget-container"></div>
    </div>
</div>

    <script>
        (function () {
            var INPOST_TOKEN = '{{ $geowidgetToken }}';
            var INPOST_METHOD = 'inpost_inpost';

            function inpostShowWidget() {
                var el = document.getElementById('inpost-widget-wrapper');
                if (el) el.style.display = 'block';
            }

            function inpostHideWidget() {
                var el = document.getElementById('inpost-widget-wrapper');
                if (el) el.style.display = 'none';
            }

            window.inpostOpenWidget = function () {
                if (!window.customElements.get('inpost-geowidget')) {
                    alert('Widget InPost nie załadował się');
                    return;
                }

                var modal = document.getElementById('inpost-modal');
                if (modal) modal.style.display = 'flex';

                var container = document.getElementById('inpost-geowidget-container');
                if (container && !container.hasChildNodes()) {
                    var widget = document.createElement('inpost-geowidget');
                    widget.setAttribute('token', INPOST_TOKEN);
                    widget.setAttribute('language', 'pl');
                    widget.setAttribute('config', 'parcelcollect');
                    widget.setAttribute('onpoint', 'window.onInpostPointSelected');
                    widget.style.cssText = 'width:100%;height:100%;display:block;';
                    container.appendChild(widget);
                }
            };

            window.inpostCloseWidget = function () {
                var modal = document.getElementById('inpost-modal');
                if (modal) modal.style.display = 'none';
            };

            window.onInpostPointSelected = function (point) {
                inpostCloseWidget();

                var pointId = point.name;
                var addr = point.address_details || {};
                var pointAddress = addr.street
                    ? (addr.street + ' ' + (addr.building_number || '') + ', ' + (addr.post_code || '') + ' ' + (addr.city || '')).trim()
                    : ((point.address && point.address.line1) || '');

                var nameEl = document.getElementById('inpost-point-name');
                var addrEl = document.getElementById('inpost-point-address');
                var infoEl = document.getElementById('inpost-selected-info');
                var btnEl  = document.getElementById('inpost-open-btn');
                var msgEl  = document.getElementById('inpost-validation-msg');

                if (nameEl) nameEl.textContent = pointId;
                if (addrEl) addrEl.textContent = pointAddress;
                if (infoEl) infoEl.classList.remove('hidden');
                if (btnEl)  btnEl.classList.add('hidden');
                if (msgEl)  msgEl.classList.add('hidden');

                fetch('{{ route('inpost.save-point') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        point_id:      pointId,
                        point_name:    pointId,
                        point_address: pointAddress,
                    }),
                })
                .then(function(r) { return r.json(); })
                .catch(function(err) { console.error('InPost save-point error:', err); });
            };

            document.addEventListener('change', function (e) {
                if (!e.target || e.target.name !== 'shipping_method') return;
                if (e.target.value === INPOST_METHOD) {
                    inpostShowWidget();
                } else {
                    inpostHideWidget();
                }
            });

            function checkInitialState() {
                var checked = document.querySelector('input[name="shipping_method"]:checked');
                if (checked && checked.value === INPOST_METHOD) {
                    inpostShowWidget();
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', checkInitialState);
            } else {
                checkInitialState();
            }

            var observer = new MutationObserver(function () {
                checkInitialState();
            });
            observer.observe(document.body, { childList: true, subtree: true });
            setTimeout(function () { observer.disconnect(); }, 15000);

        })();
    </script>
