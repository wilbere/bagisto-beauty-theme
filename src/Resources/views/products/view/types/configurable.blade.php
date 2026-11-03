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
                    class="mt-6 border-t border-[#eae5db]/60 pt-6 first:mt-0 first:border-0 first:pt-0"
                    v-for='(attribute, index) in childAttributes'
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
                            class="w-full rounded-full border-[#eae5db] bg-white px-5 py-3 text-sm text-[#18181b] transition-colors focus:border-[#18181b] focus:ring-1 focus:ring-[#18181b]"
                            :class="[errors['super_attribute[' + attribute.id + ']'] ? 'border-red-500' : '']"
                            :id="'attribute_' + attribute.id"
                            v-model="attribute.selectedValue"
                            rules="required"
                            :label="attribute.label"
                            :disabled="attribute.disabled"
                            @change="configure(attribute, $event.target.value)"
                        >
                            <option value="">@lang('beauty::app.products.select-option', ['attribute' => '']) @{{ attribute.label }}</option>
                            <option
                                v-for='(option, index) in attribute.options'
                                :value="option.id"
                            >
                                @{{ option.label }}
                            </option>
                        </v-field>
                    </template>

                    <!-- Swatches: Colors & Textures (Ideal for Nail Polish & Cosmetics) -->
                    <template v-else>
                        <div class="flex flex-wrap items-center gap-3">
                            <template v-for="(option, index) in attribute.options">
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
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                class="sr-only"
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
                                        class="group relative flex h-14 w-14 cursor-pointer items-center justify-center overflow-hidden rounded-xl border border-[#eae5db] bg-white p-1 transition-all"
                                        :class="option.id == attribute.selectedValue ? 'border-[#18181b] ring-2 ring-[#18181b] ring-offset-1' : 'hover:border-[#d4a373]'"
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
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                class="sr-only"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <img
                                            :src="option.swatch_value"
                                            :alt="option.label"
                                            class="h-full w-full rounded-lg object-cover"
                                        />
                                    </label>

                                    <!-- Text / Size Swatch (e.g. 10ml, 15ml, Kit) -->
                                    <label
                                        class="group relative flex cursor-pointer items-center justify-center rounded-full border border-[#eae5db] px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#18181b] transition-all"
                                        :class="option.id == attribute.selectedValue ? 'border-[#18181b] bg-[#18181b] text-white shadow-xs' : 'bg-white hover:border-[#18181b]'"
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
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                class="sr-only"
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
                        <p
                            class="mt-1.5 text-xs text-red-500"
                            v-text="message"
                        ></p>
                    </v-error-message>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-product-configurable-options', {
                template: '#v-product-configurable-options-template',

                props: ['errors'],

                data() {
                    return {
                        config: @json(app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product)),
                        childAttributes: [],
                        selectedOptions: {},
                        simpleProducts: {},
                        galleryImages: [],
                    };
                },

                computed: {
                    selectedOptionVariant() {
                        let selectedOptionKeys = Object.keys(this.selectedOptions);

                        if (this.childAttributes.length != selectedOptionKeys.length) {
                            return;
                        }

                        let simpleProductIndex = this.getSimpleProductId();

                        return this.simpleProducts[simpleProductIndex];
                    },
                },

                mounted() {
                    this.initChildAttributes();
                },

                methods: {
                    initChildAttributes() {
                        let config = this.config;

                        let childAttributes = this.childAttributes;

                        let index = 0;

                        for (let attributeId in config.attributes) {
                            let attribute = config.attributes[attributeId];

                            childAttributes.push({
                                id: attribute.id,
                                code: attribute.code,
                                label: attribute.label,
                                swatch_type: attribute.swatch_type,
                                options: attribute.options,
                                selectedValue: '',
                                disabled: index ? true : false,
                            });

                            index++;
                        }

                        this.simpleProducts = config.index;
                        this.galleryImages = config.variant_images;
                    },

                    configure(attribute, value) {
                        this.selectedOptions[attribute.id] = value;

                        let currentAttributeIndex = this.childAttributes.findIndex(
                            (childAttribute) => childAttribute.id == attribute.id
                        );

                        this.resetSubsequentAttributes(currentAttributeIndex);

                        if (this.selectedOptionVariant) {
                            this.$emitter.emit('configurable-variant-selected', this.selectedOptionVariant);
                            this.changeImages(this.selectedOptionVariant);
                        }
                    },

                    resetSubsequentAttributes(currentIndex) {
                        for (let i = currentIndex + 1; i < this.childAttributes.length; i++) {
                            this.childAttributes[i].selectedValue = '';
                            this.childAttributes[i].disabled = true;
                            delete this.selectedOptions[this.childAttributes[i].id];
                        }

                        if (this.childAttributes[currentIndex + 1]) {
                            this.childAttributes[currentIndex + 1].disabled = false;
                        }
                    },

                    getSimpleProductId() {
                        let options = [];

                        for (let key in this.selectedOptions) {
                            options.push(this.selectedOptions[key]);
                        }

                        return options.sort().join('_');
                    },

                    changeImages(variantId) {
                        if (this.galleryImages && this.galleryImages[variantId]) {
                            this.$emitter.emit('change-product-images', this.galleryImages[variantId]);
                        }
                    },

                    getSelectedOptionLabel(attribute) {
                        let selectedOption = attribute.options.find(option => option.id == attribute.selectedValue);
                        return selectedOption ? selectedOption.label : '';
                    },
                },
            });
        </script>
    @endpush
@endif
