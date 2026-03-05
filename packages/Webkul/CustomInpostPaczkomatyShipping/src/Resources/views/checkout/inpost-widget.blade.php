@pushOnce('scripts')

<link rel="stylesheet" href="https://geowidget.inpost.pl/v1/assets/css/geowidget.css">
<script src="https://geowidget.inpost.pl/v1/assets/js/geowidget.js"></script>

<script type="text/x-template" id="v-inpost-widget-template">

<div v-if="visible" class="mt-6 p-4 border rounded-xl bg-white">

    <h3 class="mb-4 font-bold text-lg">
        Wybierz paczkomat
    </h3>

    <div ref="map" style="width:100%; height:450px;"></div>

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
            selectedLocker: null
        }
    },

    mounted() {

        document.addEventListener('change', (e) => {

            if (e.target.name !== 'shipping_method') return;

            const method = e.target.value;

            this.visible = method === 'custom_inpostpaczkomaty_shipping_custom_inpostpaczkomaty_shipping';

            if (this.visible) {
                this.$nextTick(() => {
                    setTimeout(() => this.initWidget(), 200);
                });
            }

        });

    },

    methods: {

        initWidget() {

            if (this.widgetInstance) return;

            const config = {

                token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",

                language: "pl",

                config: "parcelcollect"

            };

            this.widgetInstance = new InPostGeowidget(config, (station) => {

                this.selectedLocker = station.name + ", " + station.address.line1;

                this.$axios.post("{{ route('inpost.save_paczkomat') }}", {

                    paczkomat_id: station.name,

                    paczkomat_details: this.selectedLocker

                });

            });

            this.widgetInstance.render(this.$refs.map);

        }

    }

});

</script>

@endPushOnce


<v-inpost-widget></v-inpost-widget>