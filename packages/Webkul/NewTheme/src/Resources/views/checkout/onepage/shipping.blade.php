<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
></v-shipping-methods>

<v-inpost-widget :method="selectedMethod" />

@pushOnce('scripts')
    <script type="text/x-template" id="v-shipping-methods-template">
        <div class="mb-7">
            <template v-if="! methods">
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <div class="p-4 border rounded-lg bg-white">
                    <h2 class="text-xl font-bold mb-4">Wybierz sposób dostawy</h2>
                    
                    <div class="grid gap-4">
                        <template v-for="method in methods">
                            <div v-for="rate in method.rates" class="relative">
                                <input 
                                    type="radio"
                                    name="shipping_method"
                                    :id="rate.method"
                                    :value="rate.method"
                                    class="peer hidden"
                                    @change="store(rate.method)"
                                    :checked="rate.method == selectedMethod"
                                >
                                <label :for="rate.method" class="block p-4 border rounded-xl cursor-pointer peer-checked:border-navyBlue peer-checked:bg-blue-50">
                                    <span class="font-bold">@{{ rate.method_title }}</span> - @{{ rate.base_formatted_price }}
                                </label>
                            </div>
                        </template>
                    </div>

                    <v-inpost-widget :method="selectedMethod"></v-inpost-widget>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',
            props: ['methods'],
            data() {
                return {
                    // Pobieramy aktualną metodę z koszyka na start
                    selectedMethod: "{{ cart()->getCart() ? cart()->getCart()->shipping_method : '' }}",
                }
            },
            methods: {
                store(method) {
                    console.log('Wybrano metodę:', method);
                    this.selectedMethod = method;
                    
                    this.$emit('processing', 'payment');
                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                        shipping_method: method
                    }).then(res => this.$emit('processed', 'payment'));
                }
            }
        });
    </script>
@endPushOnce