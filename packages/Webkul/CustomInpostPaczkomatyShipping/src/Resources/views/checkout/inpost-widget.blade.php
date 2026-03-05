@pushOnce('scripts')

<link rel="stylesheet"
      href="https://sdk.inpost.pl/geowidget/v5/assets/css/geowidget.css"
      crossorigin="anonymous">
<script src="https://sdk.inpost.pl/geowidget/v5/assets/js/geowidget.js"
        defer
        crossorigin="anonymous"></script>

<script type="text/x-template" id="v-inpost-widget-template">

<div v-show="visible" class="mt-6 p-4 border rounded">

    <h3 class="font-bold text-lg mb-3">
        Wybierz paczkomat
    </h3>

    <div id="inpost-map" ref="mapContainer" style="height: 450px;"></div>

    <div v-if="selectedLocker" class="mt-4 p-3 bg-green-600 text-white rounded">
        Wybrano: @{{ selectedLocker }}
    </div>

</div>

</script>

<script type="module">
app.component('v-inpost-widget', {

    template: '#v-inpost-widget-template',

    data() {
        return {
            visible: false,
            widgetInstance: null,
            selectedLocker: null,
        };
    },

    mounted() {
        document.body.addEventListener('change', this.onShippingMethodChange);
    },

    beforeUnmount() {
        document.body.removeEventListener('change', this.onShippingMethodChange);
    },

    methods: {

        onShippingMethodChange(e) {
            if (e.target.name !== "shipping_method") return;

            const method = e.target.value;

            // TYLKO dla paczkomatów
            this.visible = method.includes('custom_inpostpaczkomaty_shipping');

            if (this.visible) {
                this.loadGeowidget();
            } else {
                this.resetWidget();
            }
        },

        loadGeowidget() {
            if (!window.InPostGeowidget) {
                console.error("SDK InPostGeowidget not loaded.");
                return;
            }

            if (this.widgetInstance) return;

            const TOKEN = "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}";
            if (!TOKEN) {
                console.error("Brak geowidget tokena!");
                return;
            }

            this.widgetInstance = new window.InPostGeowidget({
                token: TOKEN,
                language: "pl",
                config: "parcelcollect"
            },
            (station) => {

                this.selectedLocker = station.name + ", " + station.address.line1;

                this.$axios.post("{{ route('inpost.save_locker') }}", {
                    paczkomat_id: station.name,
                    paczkomat_details: this.selectedLocker
                });

            });

            this.widgetInstance.render(this.$refs.mapContainer);
        },

        resetWidget() {
            this.selectedLocker = null;
            this.widgetInstance = null;
            if (this.$refs.mapContainer) {
                this.$refs.mapContainer.innerHTML = "";
            }
        }

    }

});
</script>

@endPushOnce

