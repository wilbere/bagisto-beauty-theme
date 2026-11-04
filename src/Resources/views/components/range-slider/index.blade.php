<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-range-slider-template"
    >
        <div class="w-full">
            <div class="flex items-center justify-between py-1 text-xs">
                <span class="text-[#71717a]">
                    @lang('shop::app.components.range-slider.range')
                </span>

                <span class="font-semibold text-[#18181b]">
                    @{{ rangeText }}
                </span>
            </div>

            <div class="relative mx-auto flex h-8 w-full items-center justify-center px-1">
                <div class="relative h-1 w-full rounded-full bg-[#ede8dc]">
                    <div
                        ref="progress"
                        class="absolute left-0 right-0 h-full rounded-full bg-[#18181b]"
                    >
                    </div>

                    <span>
                        <input
                            :step="allowedMaxRange - Math.floor(allowedMaxRange) > 0 ? 0.01 : 1"
                            ref="minRange"
                            type="range"
                            :value="minRange"
                            class="pointer-events-none absolute left-0 top-0 h-1 w-full cursor-pointer appearance-none bg-transparent outline-hidden [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-[#18181b] [&::-moz-range-thumb]:shadow-xs hover:[&::-moz-range-thumb]:scale-110 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[#18181b] [&::-webkit-slider-thumb]:shadow-xs hover:[&::-webkit-slider-thumb]:scale-110"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            aria-label="@lang('shop::app.components.range-slider.min-range')"
                            @input="handle('min')"
                            @change="change"
                        >
                    </span>

                    <span>
                        <input
                            :step="allowedMaxRange - Math.floor(allowedMaxRange) > 0 ? 0.01 : 1"
                            ref="maxRange"
                            type="range"
                            :value="maxRange"
                            class="pointer-events-none absolute left-0 top-0 h-1 w-full cursor-pointer appearance-none bg-transparent outline-hidden [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-[#18181b] [&::-moz-range-thumb]:shadow-xs hover:[&::-moz-range-thumb]:scale-110 [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[#18181b] [&::-webkit-slider-thumb]:shadow-xs hover:[&::-webkit-slider-thumb]:scale-110"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            aria-label="@lang('shop::app.components.range-slider.max-range')"
                            @input="handle('max')"
                            @change="change"
                        >
                    </span>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-range-slider', {
            template: '#v-range-slider-template',

            props: [
                'defaultType',
                'defaultAllowedMinRange',
                'defaultAllowedMaxRange',
                'defaultMinRange',
                'defaultMaxRange',
            ],

            data() {
                return {
                    gap: (this.defaultAllowedMaxRange || 100) * 0.05,

                    supportedTypes: ['integer', 'float', 'price'],

                    allowedMinRange: parseFloat(this.defaultAllowedMinRange ?? 0),

                    allowedMaxRange: parseFloat(this.defaultAllowedMaxRange ?? 100),

                    minRange: parseFloat(this.defaultMinRange ?? 0),

                    maxRange: parseFloat(this.defaultMaxRange ?? 100),
                };
            },

            computed: {
                rangeText() {
                    let { formattedMinRange, formattedMaxRange } = this.getFormattedData();

                    return `${formattedMinRange} - ${formattedMaxRange}`;
                },
            },

            mounted() {
                this.handleProgressBar();
            },

            methods: {
                getData() {
                    return {
                        allowedMinRange: this.allowedMinRange,
                        allowedMaxRange: this.allowedMaxRange,
                        minRange: this.minRange,
                        maxRange: this.maxRange,
                    };
                },

                getFormattedData() {
                    if (this.isTypeSupported()) {
                        switch (this.defaultType) {
                            case 'price':
                                return {
                                    formattedAllowedMinRange: this.$shop.formatPrice(this.allowedMinRange),
                                    formattedAllowedMaxRange: this.$shop.formatPrice(this.allowedMaxRange),
                                    formattedMinRange: this.$shop.formatPrice(this.minRange),
                                    formattedMaxRange: this.$shop.formatPrice(this.maxRange),
                                };

                            case 'float':
                                return {
                                    formattedAllowedMinRange: parseFloat(this.allowedMinRange).toFixed(2),
                                    formattedAllowedMaxRange: parseFloat(this.allowedMaxRange).toFixed(2),
                                    formattedMinRange: parseFloat(this.minRange).toFixed(2),
                                    formattedMaxRange: parseFloat(this.maxRange).toFixed(2),
                                };

                            default:
                                return {
                                    formattedAllowedMinRange: this.allowedMinRange,
                                    formattedAllowedMaxRange: this.allowedMaxRange,
                                    formattedMinRange: this.minRange,
                                    formattedMaxRange: this.maxRange,
                                };
                        }
                    }

                    return {
                        formattedAllowedMinRange: this.allowedMinRange,
                        formattedAllowedMaxRange: this.allowedMaxRange,
                        minRange: this.minRange,
                        maxRange: this.maxRange,
                    };
                },

                isTypeSupported() {
                    return this.supportedTypes.includes(this.defaultType);
                },

                change() {
                    this.$emit('change-range', {
                        minRange: this.minRange,
                        maxRange: this.maxRange,
                    });
                },

                handle(type) {
                    if (this.maxRange - this.minRange < this.gap) {
                        if (type === 'min') {
                            this.minRange = this.maxRange - this.gap;
                        } else {
                            this.maxRange = this.minRange + this.gap;
                        }
                    } else {
                        if (type === 'min') {
                            this.minRange = parseFloat(this.$refs.minRange.value);
                        } else {
                            this.maxRange = parseFloat(this.$refs.maxRange.value);
                        }
                    }

                    this.handleProgressBar();
                },

                handleProgressBar() {
                    const totalRange = this.allowedMaxRange - this.allowedMinRange;
                    if (totalRange <= 0) return;

                    const leftPercent = ((this.minRange - this.allowedMinRange) / totalRange) * 100;
                    const rightPercent = 100 - (((this.maxRange - this.allowedMinRange) / totalRange) * 100);

                    if (this.$refs.progress) {
                        this.$refs.progress.style.left = `${Math.max(0, leftPercent)}%`;
                        this.$refs.progress.style.right = `${Math.max(0, rightPercent)}%`;
                    }
                },
            },
        });
    </script>
@endPushOnce
