@props([
    'name'     => '',
    'value'    => 1,
    'minValue' => 1,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex items-center border border-navyBlue']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div>
            <span 
                class="icon-minus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            >
            </span>

            <input
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="5"
                class="me-qty-input max-sm:text-sm"
                aria-label="Quantity"
                :value="quantity"
                @input="sanitize"
                @change="commit"
                @keydown.enter.prevent="$event.target.blur()"
            />

            <span 
                class="icon-plus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            >
            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value', 'minValue'],

            /**
             * Declared so a parent's @change listens to this event only, not the
             * native change event bubbling up from the quantity input.
             */
            emits: ['change'],

            data() {
                return  {
                    quantity: Number(this.value),
                }
            },

            watch: {
                value() {
                    this.quantity = Number(this.value);
                },
            },

            methods: {
                sanitize(event) {
                    event.target.value = event.target.value.replace(/\D/g, '');
                },

                /**
                 * Apply a typed quantity: empty or invalid input restores the
                 * current quantity, anything below the minimum becomes the minimum.
                 */
                commit(event) {
                    let quantity = parseInt(event.target.value, 10);

                    if (isNaN(quantity)) {
                        quantity = this.quantity;
                    }

                    quantity = Math.max(quantity, Number(this.minValue));

                    event.target.value = quantity;

                    if (quantity !== this.quantity) {
                        this.quantity = quantity;

                        this.$emit('change', this.quantity);
                    }
                },

                increase() {
                    this.$emit('change', ++this.quantity);
                },

                decrease() {
                    if (this.quantity > this.minValue) {
                        this.quantity -= 1;

                        this.$emit('change', this.quantity);
                    }
                },
            }
        });
    </script>
@endpushOnce
