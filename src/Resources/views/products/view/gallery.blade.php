<v-product-gallery ref="gallery">
    <x-shop::shimmer.products.gallery />
</v-product-gallery>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-gallery-template"
    >
        <div class="w-full">
            <!-- Desktop Luxury Gallery (Large Screens: lg and above) -->
            <div class="hidden lg:flex gap-6 items-start sticky top-28">
                <!-- Vertical Thumbnails Column -->
                <div class="flex flex-col items-center gap-3 shrink-0" v-if="allMedia.length > 1">
                    <!-- Scroll Up Button -->
                    <button
                        type="button"
                        class="flex h-7 w-7 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-xs text-[#71717a] transition-colors hover:border-[#9e8464] hover:text-[#18181b] disabled:opacity-30 disabled:cursor-not-allowed"
                        :disabled="! canScrollUp"
                        @click="scrollThumbnails('up')"
                        aria-label="@lang('shop::app.components.products.carousel.previous')"
                    >
                        <span class="icon-arrow-up"></span>
                    </button>

                    <!-- Thumbnails Scroll Container -->
                    <div
                        ref="thumbnailContainer"
                        class="flex flex-col gap-2.5 max-h-[500px] overflow-y-auto no-scrollbar scroll-smooth py-1"
                        @scroll="onThumbnailScroll"
                    >
                        <template v-for="(item, index) in allMedia" :key="index">
                            <!-- Video Thumbnail -->
                            <div
                                v-if="item.type === 'videos'"
                                class="relative h-20 w-20 shrink-0 cursor-pointer overflow-hidden rounded-xl border-2 transition-all p-1 bg-[#fdfbf7]"
                                :class="activeIndex === index ? 'border-[#9e8464] ring-2 ring-[#9e8464]/20' : 'border-[#ede8dc] hover:border-[#b59f7f]'"
                                @click="changeMedia(item, index)"
                            >
                                <video class="h-full w-full object-cover rounded-lg" :src="item.video_url"></video>
                                <span class="absolute inset-0 flex items-center justify-center bg-black/20 text-white text-sm">▶</span>
                            </div>

                            <!-- Image Thumbnail -->
                            <div
                                v-else
                                class="h-20 w-20 shrink-0 cursor-pointer overflow-hidden rounded-xl border-2 transition-all p-1 bg-[#fdfbf7]"
                                :class="activeIndex === index ? 'border-[#9e8464] ring-2 ring-[#9e8464]/20' : 'border-[#ede8dc] hover:border-[#b59f7f]'"
                                @click="changeMedia(item, index)"
                            >
                                <img
                                    :src="item.small_image_url"
                                    :alt="item.alt || '{{ $product->name }}'"
                                    class="h-full w-full object-contain rounded-lg"
                                    loading="lazy"
                                />
                            </div>
                        </template>
                    </div>

                    <!-- Scroll Down Button -->
                    <button
                        type="button"
                        class="flex h-7 w-7 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-xs text-[#71717a] transition-colors hover:border-[#9e8464] hover:text-[#18181b] disabled:opacity-30 disabled:cursor-not-allowed"
                        :disabled="! canScrollDown"
                        @click="scrollThumbnails('down')"
                        aria-label="@lang('shop::app.components.products.carousel.next')"
                    >
                        <span class="icon-arrow-down"></span>
                    </button>
                </div>

                <!-- Main Active Media Stage (Desktop) -->
                <div class="relative flex-1 overflow-hidden rounded-2xl border border-[#ede8dc] bg-[#fdfbf7] shadow-xs">
                    <!-- Shimmer Loader -->
                    <div
                        v-show="isMediaLoading"
                        class="aspect-[4/5] w-full flex items-center justify-center bg-zinc-100 animate-pulse"
                    >
                        <span class="shimmer aspect-square w-3/4 rounded-xl"></span>
                    </div>

                    <!-- Main Media Viewer -->
                    <div
                        v-show="! isMediaLoading"
                        class="group relative aspect-[4/5] w-full flex items-center justify-center p-6 cursor-zoom-in"
                        @click="openZoomer"
                    >
                        <!-- Active Image -->
                        <img
                            v-if="baseFile.type === 'image'"
                            :src="baseFile.path"
                            :alt="baseFile.alt || '{{ $product->name }}'"
                            class="max-h-full max-w-full object-contain select-none transition-transform duration-300 group-hover:scale-105"
                            @load="onMediaLoad"
                            v-on:error="onMediaError($event)"
                            fetchpriority="high"
                        />

                        <!-- Active Video -->
                        <div v-else-if="baseFile.type === 'video'" class="h-full w-full flex items-center justify-center">
                            <video
                                controls
                                class="max-h-full max-w-full rounded-xl"
                                :key="baseFile.path"
                                @loadeddata="onMediaLoad"
                            >
                                <source :src="baseFile.path" type="video/mp4" />
                            </video>
                        </div>

                        <!-- Zoom Hint Overlay Badge -->
                        <div class="pointer-events-none absolute bottom-4 right-4 flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-medium text-[#18181b] shadow-xs backdrop-blur-xs opacity-0 transition-opacity group-hover:opacity-100">
                            <span class="icon-search text-xs text-[#9e8464]"></span>
                            <span>Ampliar imagen</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile & Tablet Luxury Gallery (Screens under lg: touch carousel) -->
            <div class="lg:hidden w-full">
                <div class="relative overflow-hidden rounded-2xl border border-[#ede8dc] bg-[#fdfbf7]">
                    <!-- Mobile Slide Track -->
                    <div
                        ref="mobileSlider"
                        class="flex transition-transform duration-300 ease-out"
                        :style="{ transform: `translateX(-${activeIndex * 100}%)` }"
                        @touchstart="handleTouchStart"
                        @touchmove="handleTouchMove"
                        @touchend="handleTouchEnd"
                        @click="openZoomer"
                    >
                        <div
                            v-for="(item, index) in allMedia"
                            :key="index"
                            class="w-full shrink-0 aspect-[4/5] flex items-center justify-center p-6"
                        >
                            <video
                                v-if="item.type === 'videos'"
                                controls
                                class="max-h-full max-w-full rounded-xl"
                                :src="item.video_url"
                            ></video>

                            <img
                                v-else
                                :src="item.large_image_url"
                                :alt="item.alt || '{{ $product->name }}'"
                                class="max-h-full max-w-full object-contain select-none"
                                @load="onMediaLoad"
                                v-on:error="onMediaError($event)"
                            />
                        </div>
                    </div>

                    <!-- Mobile Dots Pagination -->
                    <div
                        v-if="allMedia.length > 1"
                        class="absolute bottom-3 inset-x-0 flex items-center justify-center gap-1.5"
                    >
                        <button
                            v-for="(item, index) in allMedia"
                            :key="index"
                            type="button"
                            class="h-1.5 rounded-full transition-all"
                            :class="activeIndex === index ? 'w-6 bg-[#18181b]' : 'w-1.5 bg-[#ede8dc] hover:bg-[#a1a1aa]'"
                            @click.stop="activeIndex = index"
                            :aria-label="`Ir a imagen ${index + 1}`"
                        ></button>
                    </div>

                    <!-- Mobile Zoom Badge -->
                    <button
                        type="button"
                        class="absolute top-3 right-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-sm text-[#18181b] shadow-xs backdrop-blur-xs"
                        @click.stop="openZoomer"
                        aria-label="Ampliar imagen"
                    >
                        <span class="icon-search text-xs"></span>
                    </button>
                </div>
            </div>

            <!-- Full-Screen Lightbox Zoomer -->
            <x-shop::image-zoomer
                ::attachments="attachments"
                ::is-image-zooming="isImageZooming"
                ::initial-index="`media_${activeIndex}`"
                @close="onZoomerClose"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-product-gallery', {
            template: '#v-product-gallery-template',

            data() {
                return {
                    isImageZooming: false,
                    isMediaLoading: true,
                    placeholderUrl: @js(product_image()->getPlaceholderUrl('large')),
                    media: {
                        images: @json(product_image()->getGalleryImages($product)),
                        videos: @json(product_video()->getVideos($product)),
                    },
                    baseFile: {
                        type: 'image',
                        path: '',
                        alt: @js($product->name),
                    },
                    activeIndex: 0,
                    canScrollUp: false,
                    canScrollDown: false,
                    // Mobile touch handlers
                    touchStartX: 0,
                    touchEndX: 0,
                };
            },

            computed: {
                allMedia() {
                    return [
                        ...(Array.isArray(this.media.images) ? this.media.images : []),
                        ...(Array.isArray(this.media.videos) ? this.media.videos : [])
                    ];
                },

                attachments() {
                    return this.allMedia.map((item) => ({
                        url: item.type === 'videos' ? item.video_url : (item.original_image_url || item.large_image_url),
                        type: item.type === 'videos' ? 'video' : 'image',
                        alt: item.alt || @js($product->name),
                    }));
                },
            },

            watch: {
                'media.images': {
                    deep: true,
                    handler(newImages) {
                        if (Array.isArray(newImages) && newImages.length > 0) {
                            this.activeIndex = 0;
                            this.setBaseMedia(newImages[0]);
                        }
                    },
                },

                activeIndex(newIndex) {
                    if (this.allMedia[newIndex]) {
                        this.setBaseMedia(this.allMedia[newIndex]);
                    }
                },
            },

            mounted() {
                this.initFirstMedia();
                this.$nextTick(this.checkThumbnailScroll);

                // Listen to Bagisto variant events
                this.$emitter?.on?.('configurable-variant-update-images-event', (images) => {
                    if (Array.isArray(images) && images.length) {
                        this.media.images = [...images];
                    }
                });

                this.$emitter?.on?.('change-product-images', (images) => {
                    if (Array.isArray(images) && images.length) {
                        this.media.images = [...images];
                    }
                });
            },

            methods: {
                initFirstMedia() {
                    if (this.allMedia.length > 0) {
                        this.setBaseMedia(this.allMedia[0]);
                    } else {
                        this.baseFile = {
                            type: 'image',
                            path: this.placeholderUrl,
                            alt: @js($product->name),
                        };
                        this.isMediaLoading = false;
                    }
                },

                setBaseMedia(item) {
                    this.isMediaLoading = true;
                    if (item.type === 'videos') {
                        this.baseFile = {
                            type: 'video',
                            path: item.video_url,
                            alt: item.alt || @js($product->name),
                        };
                    } else {
                        this.baseFile = {
                            type: 'image',
                            path: item.large_image_url || item.original_image_url || this.placeholderUrl,
                            alt: item.alt || @js($product->name),
                        };
                    }
                },

                changeMedia(item, index) {
                    this.activeIndex = index;
                    this.setBaseMedia(item);
                },

                openZoomer() {
                    this.isImageZooming = true;
                },

                onZoomerClose() {
                    this.isImageZooming = false;
                },

                onMediaLoad() {
                    this.isMediaLoading = false;
                },

                onMediaError(event) {
                    this.isMediaLoading = false;
                    if (event?.target && event.target.src !== this.placeholderUrl) {
                        event.target.src = this.placeholderUrl;
                    }
                },

                scrollThumbnails(direction) {
                    const container = this.$refs.thumbnailContainer;
                    if (! container) return;
                    const offset = direction === 'up' ? -120 : 120;
                    container.scrollBy({ top: offset, behavior: 'smooth' });
                    setTimeout(this.checkThumbnailScroll, 300);
                },

                onThumbnailScroll() {
                    this.checkThumbnailScroll();
                },

                checkThumbnailScroll() {
                    const container = this.$refs.thumbnailContainer;
                    if (! container) return;
                    this.canScrollUp = container.scrollTop > 5;
                    this.canScrollDown = container.scrollTop + container.clientHeight < container.scrollHeight - 5;
                },

                // Mobile Touch Handlers
                handleTouchStart(event) {
                    this.touchStartX = event.changedTouches[0].screenX;
                },

                handleTouchMove(event) {
                    this.touchEndX = event.changedTouches[0].screenX;
                },

                handleTouchEnd() {
                    const diff = this.touchStartX - this.touchEndX;
                    if (Math.abs(diff) > 50) {
                        if (diff > 0 && this.activeIndex < this.allMedia.length - 1) {
                            this.activeIndex++;
                        } else if (diff < 0 && this.activeIndex > 0) {
                            this.activeIndex--;
                        }
                    }
                    this.touchStartX = 0;
                    this.touchEndX = 0;
                },
            },
        });
    </script>
@endpushOnce
