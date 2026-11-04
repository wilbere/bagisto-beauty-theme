@props([
    'name'      => '',
    'value'     => 1,
    'minValue'  => 1,
    'removable' => false,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-3 py-1.5 shadow-2xs']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
    is-removable="{{ $removable ? '1' : '0' }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div class="inline-flex items-center gap-3 select-none">
            <!-- Trash / Remove button when at minimum and removable -->
            <button
                v-if="isAtMinValue"
                type="button"
                class="flex h-7 w-7 items-center justify-center rounded-full text-sm text-[#71717a] transition-colors hover:bg-[#ede8dc] hover:text-red-600 focus:outline-hidden"
                aria-label="@lang('shop::app.components.quantity-changer.remove-item')"
                @click="remove"
            >
                <span class="icon-bin"></span>
            </button>

            <!-- Minus Button -->
            <button
                v-else
                type="button"
                class="flex h-7 w-7 items-center justify-center rounded-full text-sm text-[#18181b] transition-all hover:bg-[#ede8dc] focus:outline-hidden"
                :class="atMinValue ? 'cursor-not-allowed opacity-30' : 'cursor-pointer'"
                :aria-disabled="atMinValue"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            >
                <span class="icon-minus"></span>
            </button>

            <!-- Numeric display -->
            <span class="w-6 text-center text-xs font-semibold text-[#18181b] font-sans">
                @{{ quantity }}
            </span>

            <!-- Plus Button -->
            <button
                type="button"
                class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-full text-sm text-[#18181b] transition-all hover:bg-[#ede8dc] focus:outline-hidden"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            >
                <span class="icon-plus"></span>
            </button>

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

            props: ['name', 'value', 'minValue', 'isRemovable'],

            data() {
                return {
                    quantity: this.value,
                };
            },

            computed: {
                atMinValue() {
                    return Number(this.quantity) <= Number(this.minValue);
                },

                isAtMinValue() {
                    return this.isRemovable == '1' && this.atMinValue;
                },
            },

            watch: {
                value(newVal) {
                    this.quantity = newVal;
                },
            },

            methods: {
                increase() {
                    this.$emit('change', ++this.quantity);
                },

                decrease() {
                    if (this.quantity > this.minValue) {
                        this.quantity -= 1;
                        this.$emit('change', this.quantity);
                    }
                },

                remove() {
                    this.$emit('remove');
                },
            },
        });
    </script>
@endpushOnce
