@if ($product->getTypeInstance()->isCustomizable())
    @php
        $options = $product->customizable_options()->with([
            'product',
            'customizable_option_prices',
        ])->get();

        $options = $options->map(function ($option) {
            $option->customizable_option_prices = $option->customizable_option_prices->map(function ($priceOption) {
                $priceOption->price = core()->convertPrice($priceOption->price);
                
                return $priceOption;
            });

            return $option;
        });
    @endphp

    @if ($options->isNotEmpty())
        {!! view_render_event('bagisto.shop.products.view.customizable-options.before', ['product' => $product]) !!}

        <v-product-customizable-options
            :initial-price="{{ core()->convertPrice($product->getTypeInstance()->getMinimalPrice()) }}"
        >
        </v-product-customizable-options>

        {!! view_render_event('bagisto.shop.products.view.customizable-options.after', ['product' => $product]) !!}

        @pushOnce('scripts')
            <script
                type="text/x-template"
                id="v-product-customizable-options-template"
            >
                <div class="mt-6 space-y-5">
                    <template v-for="(option, index) in options">
                        <v-product-customizable-option-item
                            :option="option"
                            :key="index"
                            @priceUpdated="priceUpdated"
                        >
                        </v-product-customizable-option-item>
                    </template>

                    <!-- Total Amount Display when options add extra price -->
                    <div
                        v-if="hasPriceAdditions"
                        class="flex items-center justify-between border-t border-[#ede8dc] pt-4"
                    >
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.products.view.type.simple.customizable-options.total-amount')
                        </p>

                        <p class="font-serif text-xl font-bold text-[#18181b]">
                            @{{ formattedTotalPrice }}
                        </p>
                    </div>
                </div>
            </script>

            <script
                type="text/x-template"
                id="v-product-customizable-option-item-template"
            >
                <div class="border-b border-[#ede8dc]/80 pb-5 last:border-b-0 last:pb-0">
                    <!-- Option Header: Label + Selected value readout -->
                    <div class="mb-3 flex items-center justify-between text-xs">
                        <span
                            class="font-semibold uppercase tracking-wider text-[#18181b]"
                            :class="{ 'after:content-[\"*\"] after:ml-0.5 after:text-rose-500': Boolean(option.is_required) }"
                        >
                            @{{ option.label }}
                        </span>

                        <span class="text-[#71717a]" v-if="selectedOptionLabel">
                            @lang('beauty::app.products.selected'): <strong class="text-[#18181b]">@{{ selectedOptionLabel }}</strong>
                        </span>
                    </div>

                    <!-- Radio Options (Luxury Pill Buttons) -->
                    <template v-if="option.type == 'radio'">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- None option if not required -->
                            <label
                                v-if="! Boolean(option.is_required)"
                                class="group relative inline-flex cursor-pointer items-center justify-center rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-wider transition-all select-none active:scale-95"
                                :class="!selectedItems || selectedItems === '0' || selectedItems === 0 ? 'border-[#18181b] bg-[#18181b] text-white shadow-xs' : 'border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7]'"
                            >
                                <input
                                    type="radio"
                                    :name="'customizable_options[' + option.id + '][]'"
                                    value="0"
                                    v-model="selectedItems"
                                    class="sr-only"
                                />
                                <span>@lang('shop::app.products.view.type.simple.customizable-options.none')</span>
                            </label>

                            <!-- Options -->
                            <label
                                v-for="(item, index) in optionItems"
                                :key="item.id"
                                class="group relative inline-flex cursor-pointer items-center justify-center gap-2 rounded-full border px-4.5 py-2.5 text-xs font-semibold tracking-wide transition-all select-none shadow-xs active:scale-95"
                                :class="selectedItems == item.id ? 'border-[#18181b] bg-[#18181b] text-white shadow-md' : 'border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7]'"
                            >
                                <v-field
                                    type="radio"
                                    :name="'customizable_options[' + option.id + '][]'"
                                    :value="item.id"
                                    v-model="selectedItems"
                                    :rules="Boolean(option.is_required) ? 'required' : ''"
                                    :label="option.label"
                                    v-slot="{ field }"
                                >
                                    <input
                                        type="radio"
                                        :name="'customizable_options[' + option.id + '][]'"
                                        :value="item.id"
                                        v-bind="field"
                                        class="sr-only"
                                    />
                                </v-field>

                                <span>@{{ item.label }}</span>

                                <span
                                    v-if="parseFloat(item.price) > 0"
                                    class="text-[11px] font-normal"
                                    :class="selectedItems == item.id ? 'text-[#c5a880]' : 'text-[#71717a]'"
                                >
                                    (+ @{{ $shop.formatPrice(item.price) }})
                                </span>
                            </label>
                        </div>
                    </template>

                    <!-- Checkbox Options (Multi-select Pills) -->
                    <template v-else-if="option.type == 'checkbox'">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <label
                                v-for="(item, index) in optionItems"
                                :key="item.id"
                                class="group relative inline-flex cursor-pointer items-center justify-center gap-2 rounded-full border px-4.5 py-2.5 text-xs font-semibold tracking-wide transition-all select-none shadow-xs active:scale-95"
                                :class="selectedItems && selectedItems.includes(item.id) ? 'border-[#18181b] bg-[#18181b] text-white shadow-md' : 'border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7]'"
                            >
                                <v-field
                                    type="checkbox"
                                    :name="'customizable_options[' + option.id + '][]'"
                                    :value="item.id"
                                    v-model="selectedItems"
                                    :rules="Boolean(option.is_required) ? 'required' : ''"
                                    :label="option.label"
                                    v-slot="{ field }"
                                >
                                    <input
                                        type="checkbox"
                                        :name="'customizable_options[' + option.id + '][]'"
                                        :value="item.id"
                                        v-bind="field"
                                        class="sr-only"
                                    />
                                </v-field>

                                <span>@{{ item.label }}</span>

                                <span
                                    v-if="parseFloat(item.price) > 0"
                                    class="text-[11px] font-normal"
                                    :class="selectedItems && selectedItems.includes(item.id) ? 'text-[#c5a880]' : 'text-[#71717a]'"
                                >
                                    (+ @{{ $shop.formatPrice(item.price) }})
                                </span>
                            </label>
                        </div>
                    </template>

                    <!-- Select Dropdown -->
                    <template v-else-if="option.type == 'select'">
                        <v-field
                            as="select"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="Boolean(option.is_required) ? 'required' : ''"
                            :label="option.label"
                            class="w-full rounded-full border border-[#ede8dc] bg-white px-5 py-3 text-sm text-[#18181b] transition-colors focus:border-[#9e8464] focus:outline-hidden shadow-xs cursor-pointer"
                        >
                            <option value="">
                                @lang('shop::app.products.view.type.simple.customizable-options.none')
                            </option>
                            <option
                                v-for="item in optionItems"
                                :key="item.id"
                                :value="item.id"
                            >
                                @{{ item.label }} <template v-if="parseFloat(item.price) > 0">(+ @{{ $shop.formatPrice(item.price) }})</template>
                            </option>
                        </v-field>
                    </template>

                    <!-- Multiselect -->
                    <template v-else-if="option.type == 'multiselect'">
                        <v-field
                            as="select"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="Boolean(option.is_required) ? 'required' : ''"
                            :label="option.label"
                            multiple
                            class="w-full rounded-2xl border border-[#ede8dc] bg-white px-5 py-3 text-sm text-[#18181b] transition-colors focus:border-[#9e8464] focus:outline-hidden shadow-xs"
                        >
                            <option
                                v-for="item in optionItems"
                                :key="item.id"
                                :value="item.id"
                            >
                                @{{ item.label }} <template v-if="parseFloat(item.price) > 0">(+ @{{ $shop.formatPrice(item.price) }})</template>
                            </option>
                        </v-field>
                    </template>

                    <!-- Text Field -->
                    <template v-else-if="option.type == 'text'">
                        <v-field
                            type="text"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="{ 'required': Boolean(option.is_required), 'max': option.max_characters }"
                            :label="option.label"
                            class="w-full rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-5 py-3 text-sm text-[#18181b] focus:border-[#9e8464] focus:bg-white focus:outline-hidden shadow-xs"
                            placeholder="Introduce el texto personalizado"
                        />
                    </template>

                    <!-- Textarea Field -->
                    <template v-else-if="option.type == 'textarea'">
                        <v-field
                            as="textarea"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="{ 'required': Boolean(option.is_required), 'max': option.max_characters }"
                            :label="option.label"
                            rows="3"
                            class="w-full rounded-2xl border border-[#ede8dc] bg-[#fdfbf7] p-4 text-sm text-[#18181b] focus:border-[#9e8464] focus:bg-white focus:outline-hidden shadow-xs"
                            placeholder="Introduce el texto personalizado"
                        />
                    </template>

                    <!-- Date Field -->
                    <template v-else-if="option.type == 'date'">
                        <v-field
                            type="date"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="Boolean(option.is_required) ? 'required' : ''"
                            :label="option.label"
                            class="w-full rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-5 py-3 text-sm text-[#18181b] focus:border-[#9e8464] focus:bg-white focus:outline-hidden shadow-xs"
                        />
                    </template>

                    <!-- Datetime Field -->
                    <template v-else-if="option.type == 'datetime'">
                        <v-field
                            type="datetime-local"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="Boolean(option.is_required) ? 'required' : ''"
                            :label="option.label"
                            class="w-full rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-5 py-3 text-sm text-[#18181b] focus:border-[#9e8464] focus:bg-white focus:outline-hidden shadow-xs"
                        />
                    </template>

                    <!-- Time Field -->
                    <template v-else-if="option.type == 'time'">
                        <v-field
                            type="time"
                            :name="'customizable_options[' + option.id + '][]'"
                            v-model="selectedItems"
                            :rules="Boolean(option.is_required) ? 'required' : ''"
                            :label="option.label"
                            class="w-full rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-5 py-3 text-sm text-[#18181b] focus:border-[#9e8464] focus:bg-white focus:outline-hidden shadow-xs"
                        />
                    </template>

                    <!-- File Field -->
                    <template v-else-if="option.type == 'file'">
                        <input
                            type="file"
                            :name="'customizable_options[' + option.id + '][]'"
                            @change="handleFileChange"
                            class="block w-full text-xs text-[#71717a] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#18181b] file:text-white hover:file:bg-[#9e8464] file:cursor-pointer"
                        />
                    </template>

                    <!-- Error message -->
                    <v-error-message
                        :name="'customizable_options[' + option.id + '][]'"
                        v-slot="{ message }"
                    >
                        <p class="mt-2 text-xs font-medium text-rose-500" v-text="message"></p>
                    </v-error-message>
                </div>
            </script>

            <script type="module">
                app.component('v-product-customizable-options', {
                    template: '#v-product-customizable-options-template',

                    props: {
                        initialPrice: {
                            type: Number,
                            required: true,
                        },
                    },

                    data: function() {
                        return {
                            options: @json($options),
                            prices: [],
                        };
                    },

                    mounted() {
                        this.options = this.options.map((option) => {
                            if (! this.canHaveMultiplePriceOptions(option.type)) {
                                return {
                                    id: option.id,
                                    label: option.label,
                                    type: option.type,
                                    is_required: option.is_required,
                                    max_characters: option.max_characters,
                                    supported_file_extensions: option.supported_file_extensions,
                                    price_id: option.customizable_option_prices[0]?.id,
                                    price: option.customizable_option_prices[0]?.price ?? 0,
                                };
                            }

                            return {
                                id: option.id,
                                label: option.label,
                                type: option.type,
                                is_required: option.is_required,
                                max_characters: option.max_characters,
                                supported_file_extensions: option.supported_file_extensions,
                                price: 0,
                            };
                        });

                        this.prices = this.options.map((option) => {
                            return {
                                option_id: option.id,
                                price: 0,
                            };
                        });
                    },

                    computed: {
                        formattedTotalPrice: function() {
                            let totalPrice = this.initialPrice;

                            for (let price of this.prices) {
                                totalPrice += parseFloat(price.price);
                            }

                            return this.$shop.formatPrice(totalPrice);
                        },

                        hasPriceAdditions: function() {
                            return this.prices.some(p => parseFloat(p.price) > 0);
                        },
                    },

                    methods: {
                        priceUpdated({ option, totalPrice }) {
                            let price = this.prices.find(price => price.option_id === option.id);

                            if (price) {
                                price.price = totalPrice;
                            }
                        },

                        canHaveMultiplePriceOptions(type) {
                            return ['checkbox', 'radio', 'select', 'multiselect'].includes(type);
                        },
                    },
                });

                app.component('v-product-customizable-option-item', {
                    template: '#v-product-customizable-option-item-template',

                    emits: ['priceUpdated'],

                    props: ['option'],

                    data: function() {
                        return {
                            optionItems: [],
                            selectedItems: this.canHaveMultiplePrices() ? [] : null,
                        };
                    },

                    mounted() {
                        if (! this.option.customizable_option_prices) {
                            return;
                        }

                        this.optionItems = this.option.customizable_option_prices.map(optionItem => {
                            return {
                                id: optionItem.id,
                                label: optionItem.label,
                                price: optionItem.price,
                            };
                        });
                    },

                    computed: {
                        selectedOptionLabel() {
                            if (! this.selectedItems) return '';

                            if (this.canHaveMultiplePrices()) {
                                if (! Array.isArray(this.selectedItems) || ! this.selectedItems.length) return '';
                                let labels = this.optionItems.filter(i => this.selectedItems.some(v => v == i.id)).map(i => i.label);
                                return labels.join(', ');
                            }

                            let item = this.optionItems.find(i => i.id == this.selectedItems);
                            return item ? item.label : '';
                        },
                    },

                    watch: {
                        selectedItems: function (value) {
                            let selectedItemValues = Array.isArray(value) ? value : [value];

                            let totalPrice = 0;

                            for (let item of this.optionItems) {
                                switch (this.option.type) {
                                    case 'text':
                                    case 'textarea':
                                    case 'date':
                                    case 'datetime':
                                    case 'time':
                                        if (selectedItemValues[0] && selectedItemValues[0].length > 0) {
                                            totalPrice += parseFloat(item.price);
                                        }
                                        break;

                                    case 'checkbox':
                                    case 'radio':
                                    case 'select':
                                    case 'multiselect':
                                        if (selectedItemValues.some(val => val == item.id)) {
                                            totalPrice += parseFloat(item.price);
                                        }
                                        break;

                                    case 'file':
                                        if (selectedItemValues[0] instanceof File) {
                                            totalPrice += parseFloat(item.price);
                                        }
                                        break;
                                }
                            }

                            this.$emit('priceUpdated', {
                                option: this.option,
                                totalPrice,
                            });
                        },
                    },

                    methods: {
                        canHaveMultiplePrices() {
                            return ['checkbox', 'multiselect'].includes(this.option.type);
                        },

                        handleFileChange(event) {
                            const selectedFiles = event.target.files;

                            this.selectedItems = selectedFiles[0];
                        },
                    },
                });
            </script>
        @endPushOnce
    @endif
@endif
