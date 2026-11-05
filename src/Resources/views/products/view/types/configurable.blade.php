@if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))
    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    <v-product-configurable-options :errors="errors"></v-product-configurable-options>

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}

    @push('scripts')
        <script
            type="text/x-template"
            id="v-product-configurable-options-template"
        >
            <div class="w-full">
                <input
                    type="hidden"
                    name="selected_configurable_option"
                    id="selected_configurable_option"
                    :value="selectedOptionVariant"
                    ref="selected_configurable_option"
                >

                <div
                    class="mt-6 border-t border-[#ede8dc]/80 pt-6 first:mt-0 first:border-0 first:pt-0"
                    v-for='(attribute, index) in childAttributes'
                    :key="attribute.id"
                >
                    <!-- Header of Attribute (Label + Selected Value Display) -->
                    <div class="mb-3 flex items-center justify-between text-xs">
                        <span class="font-semibold uppercase tracking-wider text-[#18181b]">
                            @{{ attribute.label }}
                        </span>

                        <span class="text-[#71717a]" v-if="getSelectedOptionLabel(attribute)">
                            @lang('beauty::app.products.selected'): <strong class="text-[#18181b]">@{{ getSelectedOptionLabel(attribute) }}</strong>
                        </span>
                    </div>

                    <!-- Dropdown Options (Standard fallback) -->
                    <template v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'">
                        <v-field
                            as="select"
                            :name="'super_attribute[' + attribute.id + ']'"
                            class="w-full rounded-full border border-[#ede8dc] bg-white px-5 py-3 text-sm text-[#18181b] transition-colors focus:border-[#9e8464] focus:outline-hidden shadow-xs cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="[errors && errors['super_attribute[' + attribute.id + ']'] ? 'border-rose-500!' : '']"
                            :id="'attribute_' + attribute.id"
                            v-model="attribute.selectedValue"
                            rules="required"
                            :label="attribute.label"
                            :aria-label="attribute.label"
                            :disabled="attribute.disabled"
                            @change="configure(attribute, $event.target.value)"
                        >
                            <option
                                v-for='option in attribute.options'
                                :key="option.id"
                                :value="option.id"
                            >
                                @{{ option.label }}
                            </option>
                        </v-field>
                    </template>

                    <!-- Swatches: Colors, Textures & Pills -->
                    <template v-else>
                        <!-- Instruction if waiting for previous attribute -->
                        <span
                            class="text-xs text-[#71717a] italic block py-2"
                            v-if="! attribute.options.length"
                        >
                            @lang('shop::app.products.view.type.configurable.select-above-options')
                        </span>

                        <div v-else class="flex flex-wrap items-center gap-2.5">
                            <template v-for="(option, optIndex) in attribute.options" :key="option.id">
                                <template v-if="option.id">
                                    <!-- Color Swatch Option -->
                                    <label
                                        class="group relative flex cursor-pointer items-center justify-center rounded-full p-1 transition-all"
                                        :class="option.id == attribute.selectedValue ? 'ring-2 ring-[#18181b] ring-offset-2 scale-110' : 'hover:scale-105'"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'color'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id + '_' + option.id"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span
                                            class="h-8 w-8 rounded-full border border-black/10 shadow-xs transition-shadow"
                                            :style="{ 'background-color': option.swatch_value }"
                                        ></span>
                                    </label>

                                    <!-- Image / Texture Swatch Option -->
                                    <label
                                        class="group relative flex h-14 w-14 cursor-pointer items-center justify-center overflow-hidden rounded-xl border border-[#ede8dc] bg-white p-1 transition-all"
                                        :class="option.id == attribute.selectedValue ? 'border-[#18181b] ring-2 ring-[#18181b] ring-offset-1 shadow-sm' : 'hover:border-[#9e8464]'"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'image'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            v-model="attribute.selectedValue"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id + '_' + option.id"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <img
                                            :src="option.swatch_value"
                                            :alt="option.label"
                                            class="h-full w-full rounded-lg object-cover"
                                        />
                                    </label>

                                    <!-- Text / Pill Swatch (e.g. 10ml, 15ml, Kit) -->
                                    <label
                                        class="group relative inline-flex cursor-pointer items-center justify-center rounded-full border px-4.5 py-2.5 text-xs font-semibold tracking-wide transition-all select-none shadow-xs active:scale-95"
                                        :class="option.id == attribute.selectedValue ? 'border-[#18181b] bg-[#18181b] text-white shadow-md' : 'border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7]'"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'text'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            v-model="attribute.selectedValue"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id + '_' + option.id"
                                                class="peer sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span>@{{ option.label }}</span>
                                    </label>
                                </template>
                            </template>
                        </div>
                    </template>

                    <v-error-message
                        :name="'super_attribute[' + attribute.id + ']'"
                        v-slot="{ message }"
                    >
                        <p class="mt-2 text-xs font-medium text-rose-500" v-text="message"></p>
                    </v-error-message>
                </div>
            </div>
        </script>

        <script type="module">
            let galleryImages = @json(product_image()->getGalleryImages($product));

            app.component('v-product-configurable-options', {
                template: '#v-product-configurable-options-template',

                props: ['errors'],

                data() {
                    return {
                        config: @json(app(\Webkul\Product\Helpers\ConfigurableOption::class)->getConfigurationConfig($product)),

                        childAttributes: [],

                        possibleOptionVariant: null,

                        selectedOptionVariant: '',

                        galleryImages: [],
                    };
                },

                mounted() {
                    let attributes = JSON.parse(JSON.stringify(this.config)).attributes.slice();

                    let index = attributes.length;

                    while (index--) {
                        let attribute = attributes[index];

                        attribute.options = [];

                        if (index) {
                            attribute.disabled = true;
                        } else {
                            this.fillAttributeOptions(attribute);
                        }

                        attribute = Object.assign(attribute, {
                            childAttributes: this.childAttributes.slice(),
                            prevAttribute: attributes[index - 1],
                            nextAttribute: attributes[index + 1]
                        });

                        this.childAttributes.unshift(attribute);
                    }
                },

                methods: {
                    configure(attribute, optionId) {
                        this.possibleOptionVariant = this.getPossibleOptionVariant(attribute, optionId);

                        if (optionId) {
                            attribute.selectedValue = optionId;

                            if (attribute.nextAttribute) {
                                attribute.nextAttribute.disabled = false;

                                this.clearAttributeSelection(attribute.nextAttribute);

                                this.fillAttributeOptions(attribute.nextAttribute);

                                this.resetChildAttributes(attribute.nextAttribute);
                            } else {
                                this.selectedOptionVariant = this.possibleOptionVariant;
                            }
                        } else {
                            this.clearAttributeSelection(attribute);

                            this.clearAttributeSelection(attribute.nextAttribute);

                            this.resetChildAttributes(attribute);
                        }

                        this.reloadPrice();

                        this.reloadImages();

                        // Emit both core Bagisto event and theme event
                        if (this.selectedOptionVariant) {
                            this.$emitter.emit('configurable-variant-selected-event', this.selectedOptionVariant);
                            this.$emitter.emit('configurable-variant-selected', this.selectedOptionVariant);
                        } else {
                            this.$emitter.emit('configurable-variant-selected-event', 0);
                        }
                    },

                    getPossibleOptionVariant(attribute, optionId) {
                        let matchedOptions = attribute.options.filter(option => option.id == optionId);

                        if (matchedOptions[0]?.allowedProducts) {
                            return matchedOptions[0].allowedProducts[0];
                        }

                        return undefined;
                    },

                    fillAttributeOptions(attribute) {
                        let options = this.config.attributes.find(tempAttribute => tempAttribute.id === attribute.id)?.options;

                        attribute.options = [{
                            'id': '',
                            'label': "@lang('shop::app.products.view.type.configurable.select-options')",
                            'products': []
                        }];

                        if (! options) {
                            return;
                        }

                        let prevAttributeSelectedOption = attribute.prevAttribute?.options.find(option => option.id == attribute.prevAttribute.selectedValue);

                        let index = 1;

                        for (let i = 0; i < options.length; i++) {
                            let allowedProducts = [];

                            if (prevAttributeSelectedOption) {
                                for (let j = 0; j < options[i].products.length; j++) {
                                    if (prevAttributeSelectedOption.allowedProducts && prevAttributeSelectedOption.allowedProducts.includes(options[i].products[j])) {
                                        allowedProducts.push(options[i].products[j]);
                                    }
                                }
                            } else {
                                allowedProducts = options[i].products.slice(0);
                            }

                            if (allowedProducts.length > 0) {
                                options[i].allowedProducts = allowedProducts;

                                attribute.options[index++] = options[i];
                            }
                        }
                    },

                    resetChildAttributes(attribute) {
                        if (! attribute.childAttributes) {
                            return;
                        }

                        attribute.childAttributes.forEach(function (set) {
                            set.selectedValue = null;

                            set.disabled = true;
                        });
                    },

                    clearAttributeSelection(attribute) {
                        if (! attribute) {
                            return;
                        }

                        attribute.selectedValue = null;

                        this.selectedOptionVariant = null;
                    },

                    reloadPrice() {
                        let selectedOptionCount = this.childAttributes.filter(attribute => attribute.selectedValue).length;

                        let finalPrice = document.querySelector('.final-price');

                        let regularPrice = document.querySelector('.regular-price');

                        let priceLabel = document.querySelector('.price-label');

                        let configVariant = this.config.variant_prices?.[this.possibleOptionVariant];

                        if (this.childAttributes.length == selectedOptionCount && configVariant) {
                            if (priceLabel) {
                                priceLabel.style.display = 'none';
                            }

                            if (parseFloat(configVariant.regular.price) > parseFloat(configVariant.final.price)) {
                                if (regularPrice) {
                                    regularPrice.style.display = 'block';
                                    regularPrice.innerHTML = configVariant.regular.formatted_price;
                                }

                                if (finalPrice) {
                                    finalPrice.innerHTML = configVariant.final.formatted_price;
                                }
                            } else {
                                if (finalPrice) {
                                    finalPrice.innerHTML = configVariant.regular.formatted_price;
                                }

                                if (regularPrice) {
                                    regularPrice.style.display = 'none';
                                    regularPrice.innerHTML = '';
                                }
                            }
                        } else {
                            if (priceLabel) {
                                priceLabel.style.display = 'inline-block';
                            }

                            const baseRegular = parseFloat(this.config.regular?.price ?? 0);

                            const baseFinal = parseFloat(this.config.final?.price ?? baseRegular);

                            if (baseFinal < baseRegular) {
                                if (regularPrice) {
                                    regularPrice.style.display = 'block';
                                    regularPrice.innerHTML = this.config.regular.formatted_price;
                                }

                                if (finalPrice) {
                                    finalPrice.innerHTML = this.config.final.formatted_price;
                                }
                            } else {
                                if (regularPrice) {
                                    regularPrice.style.display = 'none';
                                    regularPrice.innerHTML = '';
                                }

                                if (finalPrice && this.config.regular) {
                                    finalPrice.innerHTML = this.config.regular.formatted_price;
                                }
                            }
                        }
                    },

                    reloadImages() {
                        galleryImages.splice(0, galleryImages.length);

                        if (this.possibleOptionVariant && this.config.variant_images?.[this.possibleOptionVariant]) {
                            this.config.variant_images[this.possibleOptionVariant].forEach(function(image) {
                                galleryImages.push(image);
                            });

                            if (this.config.variant_videos?.[this.possibleOptionVariant]) {
                                this.config.variant_videos[this.possibleOptionVariant].forEach(function(video) {
                                    galleryImages.push(video);
                                });
                            }
                        }

                        this.galleryImages.forEach(function(image) {
                            galleryImages.push(image);
                        });

                        if (galleryImages.length && this.$parent?.$parent?.$refs?.gallery?.media) {
                            this.$parent.$parent.$refs.gallery.media.images = [...galleryImages];
                        }

                        this.$emitter.emit('configurable-variant-update-images-event', galleryImages);
                        this.$emitter.emit('change-product-images', galleryImages);
                    },

                    getSelectedOptionLabel(attribute) {
                        let selectedOption = attribute.options?.find(option => option.id == attribute.selectedValue);
                        return selectedOption && selectedOption.id ? selectedOption.label : '';
                    },
                },
            });
        </script>
    @endpush
@endif
