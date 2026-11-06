<v-gallery-zoomer {{ $attributes }}></v-gallery-zoomer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-gallery-zoomer-template"
    >
        <teleport to="body">
            <transition
                enter-active-class="transition-opacity ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isOpen"
                    class="fixed inset-0 z-[99999] flex flex-col bg-[#0c0a09]/95 text-white backdrop-blur-md select-none"
                    role="dialog"
                    aria-modal="true"
                    aria-label="@lang('shop::app.components.image-zoomer.close')"
                >
                    <!-- Top Control Bar (Always on Top) -->
                    <div class="relative z-20 flex h-16 w-full items-center justify-between border-b border-white/10 px-4 sm:px-6">
                        <!-- Left: Counter & Hint -->
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold tracking-wider text-white">
                                @{{ currentIndex }} / @{{ attachments.length }}
                            </span>

                            <span class="hidden text-xs text-white/50 sm:inline-block">
                                Doble clic o pellizco para ampliar
                            </span>
                        </div>

                        <!-- Center: Zoom Controls -->
                        <div class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2 py-1">
                            <!-- Zoom Out -->
                            <button
                                type="button"
                                class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-base font-bold text-white/80 transition-colors hover:bg-white/10 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
                                :disabled="zoomScale <= 1"
                                @click.stop="zoomOut"
                                aria-label="Reducir zoom"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>

                            <!-- Current Scale Display / Reset -->
                            <button
                                type="button"
                                class="px-2 text-xs font-semibold tracking-wider text-white/90 hover:text-white cursor-pointer"
                                @click.stop="resetZoom"
                                title="Restablecer tamaño original"
                            >
                                @{{ Math.round(zoomScale * 100) }}%
                            </button>

                            <!-- Zoom In -->
                            <button
                                type="button"
                                class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-base font-bold text-white/80 transition-colors hover:bg-white/10 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
                                :disabled="zoomScale >= 3.5"
                                @click.stop="zoomIn"
                                aria-label="Aumentar zoom"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>

                        <!-- Right: Close Button -->
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white/10 text-white transition-all hover:bg-white/20 active:scale-95"
                                @click="close"
                                aria-label="@lang('shop::app.components.image-zoomer.close')"
                                title="Cerrar (Esc)"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Main Viewport Area -->
                    <div
                        ref="viewportContainer"
                        class="relative flex-1 overflow-hidden cursor-default flex items-center justify-center"
                        @wheel.prevent="handleWheel"
                        @mousedown="handleMouseDown"
                        @mousemove="handleMouseMove"
                        @mouseup="handleMouseUp"
                        @mouseleave="handleMouseUp"
                        @touchstart="handleTouchStart"
                        @touchmove="handleTouchMove"
                        @touchend="handleTouchEnd"
                    >
                        <!-- Navigation Chevron Left -->
                        <button
                            v-if="attachments.length > 1"
                            type="button"
                            class="absolute left-4 top-1/2 -translate-y-1/2 z-30 flex h-12 w-12 cursor-pointer items-center justify-center rounded-full bg-black/40 text-white shadow-lg backdrop-blur-md transition-all hover:bg-black/70 hover:scale-105 active:scale-95"
                            @click.stop="prevSlide"
                            aria-label="Anterior"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Navigation Chevron Right -->
                        <button
                            v-if="attachments.length > 1"
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 z-30 flex h-12 w-12 cursor-pointer items-center justify-center rounded-full bg-black/40 text-white shadow-lg backdrop-blur-md transition-all hover:bg-black/70 hover:scale-105 active:scale-95"
                            @click.stop="nextSlide"
                            aria-label="Siguiente"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Active Slide Stage -->
                        <div
                            class="relative flex h-full w-full items-center justify-center p-4 sm:p-8"
                            :class="{
                                'cursor-zoom-in': zoomScale === 1,
                                'cursor-grab': zoomScale > 1 && !isDragging,
                                'cursor-grabbing': isDragging
                            }"
                        >
                            <template v-for="(item, index) in attachments" :key="index">
                                <div
                                    v-show="currentIndex === index + 1"
                                    class="flex h-full w-full items-center justify-center"
                                >
                                    <!-- Video Element -->
                                    <video
                                        v-if="item.type === 'video'"
                                        controls
                                        class="max-h-full max-w-full rounded-2xl shadow-2xl"
                                        :src="item.url"
                                    ></video>

                                    <!-- Image Element with Transform Scale & Translate -->
                                    <img
                                        v-else
                                        :src="item.url"
                                        :alt="item.alt || ''"
                                        class="max-h-full max-w-full object-contain pointer-events-auto transition-transform"
                                        :style="currentImageStyle"
                                        @click="handleImageClick"
                                        draggable="false"
                                    />
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Bottom Thumbnail Strip -->
                    <div
                        v-if="attachments.length > 1"
                        class="relative z-20 flex h-24 w-full shrink-0 items-center justify-center border-t border-white/10 bg-[#0c0a09]/80 px-4 py-2"
                    >
                        <div class="flex max-w-full gap-2.5 overflow-x-auto no-scrollbar py-1">
                            <button
                                v-for="(item, index) in attachments"
                                :key="'thumb-' + index"
                                type="button"
                                class="relative h-16 w-16 shrink-0 cursor-pointer overflow-hidden rounded-xl border-2 transition-all p-1 bg-white/5"
                                :class="currentIndex === index + 1 ? 'border-[#9e8464] ring-2 ring-[#9e8464]/40 scale-105' : 'border-white/10 hover:border-white/30 opacity-70 hover:opacity-100'"
                                @click="goToSlide(index + 1)"
                                :aria-label="`Ver imagen ${index + 1}`"
                            >
                                <img
                                    v-if="item.type !== 'video'"
                                    :src="item.url"
                                    :alt="item.alt || ''"
                                    class="h-full w-full object-contain rounded-lg"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center bg-black/40 rounded-lg text-xs">
                                    ▶
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </teleport>
    </script>

    <script type="module">
        app.component('v-gallery-zoomer', {
            template: '#v-gallery-zoomer-template',

            props: {
                attachments: {
                    type: Array,
                    required: true,
                    default: () => [],
                },

                isImageZooming: {
                    type: Boolean,
                    default: false,
                },

                initialIndex: {
                    type: [String, Number],
                    default: 0,
                },
            },

            emits: ['close'],

            data() {
                return {
                    isOpen: false,
                    currentIndex: 1,
                    zoomScale: 1,
                    translateX: 0,
                    translateY: 0,
                    isDragging: false,
                    dragStartX: 0,
                    dragStartY: 0,
                    // Mobile touch tracking
                    touchStartX: 0,
                    touchStartY: 0,
                    lastTouchEnd: 0,
                    initialPinchDistance: 0,
                    initialScaleOnPinch: 1,
                };
            },

            computed: {
                currentImageStyle() {
                    const duration = this.isDragging ? '0ms' : '200ms';
                    return {
                        transform: `translate(${this.translateX}px, ${this.translateY}px) scale(${this.zoomScale})`,
                        transitionDuration: duration,
                    };
                },
            },

            watch: {
                isImageZooming(newVal) {
                    if (newVal) {
                        const parsed = typeof this.initialIndex === 'string'
                            ? parseInt(this.initialIndex.split('_').pop(), 10)
                            : (parseInt(this.initialIndex, 10) || 0);

                        this.currentIndex = (isNaN(parsed) ? 0 : parsed) + 1;
                        this.open();
                    } else {
                        this.close();
                    }
                },
            },

            beforeUnmount() {
                if (this.isOpen) {
                    document.body.style.overflow = '';
                    window.removeEventListener('keydown', this.handleKeyDown);
                }
            },

            methods: {
                open() {
                    this.isOpen = true;
                    this.resetZoom();
                    document.body.style.overflow = 'hidden';
                    window.addEventListener('keydown', this.handleKeyDown);
                },

                close() {
                    if (! this.isOpen) return;
                    this.isOpen = false;
                    this.resetZoom();
                    document.body.style.overflow = '';
                    window.removeEventListener('keydown', this.handleKeyDown);
                    this.$emit('close');
                },

                goToSlide(index) {
                    if (index < 1) index = this.attachments.length;
                    if (index > this.attachments.length) index = 1;
                    this.currentIndex = index;
                    this.resetZoom();
                },

                prevSlide() {
                    this.goToSlide(this.currentIndex - 1);
                },

                nextSlide() {
                    this.goToSlide(this.currentIndex + 1);
                },

                zoomIn() {
                    this.zoomScale = Math.min(+(this.zoomScale + 0.5).toFixed(1), 3.5);
                },

                zoomOut() {
                    this.zoomScale = Math.max(+(this.zoomScale - 0.5).toFixed(1), 1);
                    if (this.zoomScale <= 1) {
                        this.resetZoom();
                    }
                },

                resetZoom() {
                    this.zoomScale = 1;
                    this.translateX = 0;
                    this.translateY = 0;
                    this.isDragging = false;
                },

                toggleZoom(event) {
                    if (this.zoomScale > 1) {
                        this.resetZoom();
                    } else {
                        this.zoomScale = 2;
                        if (event && event.currentTarget) {
                            const rect = event.currentTarget.getBoundingClientRect();
                            const clickX = (event.clientX ?? (rect.left + rect.width / 2)) - rect.left;
                            const clickY = (event.clientY ?? (rect.top + rect.height / 2)) - rect.top;
                            const centerX = rect.width / 2;
                            const centerY = rect.height / 2;
                            this.translateX = (centerX - clickX) * 0.7;
                            this.translateY = (centerY - clickY) * 0.7;
                        }
                    }
                },

                handleImageClick(event) {
                    // Only toggle if we didn't perform a significant drag
                    if (! this.isDragging) {
                        this.toggleZoom(event);
                    }
                },

                handleWheel(event) {
                    if (event.deltaY < 0) {
                        this.zoomIn();
                    } else if (event.deltaY > 0) {
                        this.zoomOut();
                    }
                },

                handleMouseDown(event) {
                    if (this.zoomScale <= 1) return;
                    this.isDragging = true;
                    this.dragStartX = event.clientX - this.translateX;
                    this.dragStartY = event.clientY - this.translateY;
                },

                handleMouseMove(event) {
                    if (! this.isDragging) return;
                    event.preventDefault();
                    this.translateX = event.clientX - this.dragStartX;
                    this.translateY = event.clientY - this.dragStartY;
                },

                handleMouseUp() {
                    this.isDragging = false;
                },

                handleTouchStart(event) {
                    if (event.touches.length === 1) {
                        this.touchStartX = event.touches[0].clientX;
                        this.touchStartY = event.touches[0].clientY;

                        if (this.zoomScale > 1) {
                            this.isDragging = true;
                            this.dragStartX = event.touches[0].clientX - this.translateX;
                            this.dragStartY = event.touches[0].clientY - this.translateY;
                        }
                    } else if (event.touches.length === 2) {
                        // Pinch to zoom start
                        this.initialPinchDistance = Math.hypot(
                            event.touches[0].clientX - event.touches[1].clientX,
                            event.touches[0].clientY - event.touches[1].clientY
                        );
                        this.initialScaleOnPinch = this.zoomScale;
                    }
                },

                handleTouchMove(event) {
                    if (event.touches.length === 1 && this.isDragging && this.zoomScale > 1) {
                        event.preventDefault();
                        this.translateX = event.touches[0].clientX - this.dragStartX;
                        this.translateY = event.touches[0].clientY - this.dragStartY;
                    } else if (event.touches.length === 2 && this.initialPinchDistance > 0) {
                        event.preventDefault();
                        const currentDistance = Math.hypot(
                            event.touches[0].clientX - event.touches[1].clientX,
                            event.touches[0].clientY - event.touches[1].clientY
                        );
                        const scaleFactor = currentDistance / this.initialPinchDistance;
                        const newScale = +(this.initialScaleOnPinch * scaleFactor).toFixed(2);
                        this.zoomScale = Math.min(Math.max(newScale, 1), 3.5);
                        if (this.zoomScale <= 1) {
                            this.translateX = 0;
                            this.translateY = 0;
                        }
                    }
                },

                handleTouchEnd(event) {
                    this.isDragging = false;
                    this.initialPinchDistance = 0;

                    if (event.changedTouches.length === 1) {
                        const touchEndX = event.changedTouches[0].clientX;
                        const diffX = this.touchStartX - touchEndX;

                        // Double tap detection on mobile
                        const now = Date.now();
                        if (now - this.lastTouchEnd < 300) {
                            this.toggleZoom(event.changedTouches[0]);
                            this.lastTouchEnd = 0;
                            return;
                        }
                        this.lastTouchEnd = now;

                        // Swipe navigation if not zoomed in
                        if (this.zoomScale <= 1 && Math.abs(diffX) > 40) {
                            if (diffX > 0) {
                                this.nextSlide();
                            } else {
                                this.prevSlide();
                            }
                        }
                    }
                },

                handleKeyDown(event) {
                    if (event.key === 'Escape') {
                        this.close();
                    } else if (event.key === 'ArrowLeft') {
                        this.prevSlide();
                    } else if (event.key === 'ArrowRight') {
                        this.nextSlide();
                    }
                },
            },
        });
    </script>
@endPushOnce
