/**
 * Luxury Beauty & Nails Theme - Frontend Entrypoint
 * Fully Compatible with Bagisto 2.5 (Vue 3.5 + Tailwind CSS v4)
 */

/**
 * Track all images and fonts for Vite manifest and publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

import { createApp } from "vue/dist/vue.esm-bundler";
import mitt from "mitt";

// Global Event Emitter for Beauty Theme Micro-Interactions
window.beautyEmitter = window.beautyEmitter || mitt();

/**
 * Main root application registry for Bagisto storefront.
 */
window.app = createApp({
    data() {
        return {};
    },

    mounted() {
        this.lazyImages();
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit({ values, errors, results }) {
            setTimeout(() => {
                const errorKeys = Object.entries(errors)
                    .map(([key, value]) => ({ key, value }))
                    .filter(error => error["value"].length);

                if (errorKeys.length > 0) {
                    const errorKey = errorKeys[0]["key"];
                    let scrollTarget = null;
                    let firstErrorElement = document.querySelector('[name="' + errorKey + '"]');

                    if (!firstErrorElement && !errorKey.endsWith('[]')) {
                        firstErrorElement = document.querySelector('[name="' + errorKey + '[]"]');
                    }

                    if (!firstErrorElement) {
                        firstErrorElement = document.querySelector('[name^="' + errorKey + '"]');
                    }

                    if (firstErrorElement) {
                        scrollTarget = firstErrorElement;
                        if (firstErrorElement.tagName === 'TEXTAREA' && firstErrorElement.style.display === 'none') {
                            const editorId = firstErrorElement.id;
                            const tinyMCEContainer = document.querySelector('#' + editorId + '_parent');
                            if (tinyMCEContainer) scrollTarget = tinyMCEContainer;
                        }
                    } else {
                        const errorMessageElement = document.querySelector('[name="' + errorKey + '"] p, [name="' + errorKey + '[]"] p');
                        if (errorMessageElement) {
                            scrollTarget = errorMessageElement.closest('.border') || errorMessageElement.closest('div[class*="bg-white"]') || errorMessageElement;
                        }
                    }

                    if (scrollTarget) {
                        scrollTarget.scrollIntoView({ behavior: "smooth", block: "center" });
                        if (firstErrorElement) {
                            if (firstErrorElement.tagName === 'TEXTAREA' && firstErrorElement.style.display === 'none') {
                                const editorId = firstErrorElement.id;
                                if (window.tinymce && tinymce.get(editorId)) {
                                    tinymce.get(editorId).focus();
                                }
                            } else if (firstErrorElement.focus) {
                                firstErrorElement.focus();
                            }
                        }
                    } else {
                        const allErrors = errorKeys
                            .map(error => (Array.isArray(error.value) ? error.value.join(', ') : error.value))
                            .filter(msg => msg).join(' ');

                        this.$emitter.emit('add-flash', {
                            type: 'error',
                            message: allErrors,
                        });
                    }
                }
            }, 100);
        },

        lazyImages() {
            const lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

            const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.classList.remove('lazy');
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach((lazyImage) => {
                lazyImageObserver.observe(lazyImage);
            });
        },
    },
});

/**
 * Global plugins registration.
 */
import Axios from "./plugins/axios";
import Emitter from "./plugins/emitter";
import Shop from "./plugins/shop";
import VeeValidate from "./plugins/vee-validate";
import Flatpickr from "./plugins/flatpickr";

[
    Axios,
    Emitter,
    Shop,
    VeeValidate,
    Flatpickr,
].forEach((plugin) => window.app.use(plugin));

/**
 * Global directives.
 */
import Debounce from "./directives/debounce";
window.app.directive("debounce", Debounce);

/**
 * Initialize Beauty Micro-Interactions once DOM is ready
 */
document.addEventListener("DOMContentLoaded", () => {
    initStickyBuyBar();
    initSwatchInteractions();
    initMiniCartProgress();
});

/**
 * Mobile Sticky Buy Bar Observer
 */
export function initStickyBuyBar() {
    const buyButton = document.getElementById("pdp-primary-add-to-cart");
    const stickyBar = document.getElementById("pdp-sticky-mobile-bar");

    if (!buyButton || !stickyBar) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
                    stickyBar.classList.remove("translate-y-full", "opacity-0", "pointer-events-none");
                    stickyBar.classList.add("translate-y-0", "opacity-100", "pointer-events-auto");
                } else {
                    stickyBar.classList.add("translate-y-full", "opacity-0", "pointer-events-none");
                    stickyBar.classList.remove("translate-y-0", "opacity-100", "pointer-events-auto");
                }
            });
        },
        { threshold: 0.1 }
    );

    observer.observe(buyButton);
}

/**
 * Interactive Polish / Gel Swatch Selector
 */
export function initSwatchInteractions() {
    document.addEventListener("click", (e) => {
        const swatch = e.target.closest(".swatch-item");
        if (!swatch) return;

        const container = swatch.closest(".swatches-group");
        if (container) {
            container.querySelectorAll(".swatch-item").forEach((s) => s.classList.remove("active"));
            swatch.classList.add("active");

            const shadeNameTarget = document.getElementById("selected-shade-name");
            const shade = swatch.getAttribute("data-shade-name");
            if (shadeNameTarget && shade) {
                shadeNameTarget.textContent = shade;
            }

            window.beautyEmitter.emit("shade:selected", {
                shade,
                code: swatch.getAttribute("data-shade-code"),
            });
        }
    });
}

/**
 * Dynamic Free Shipping Progress in Drawer Cart
 */
export function initMiniCartProgress() {
    window.beautyEmitter.on("cart:updated", (cart) => {
        const threshold = 50.0;
        const subtotal = parseFloat(cart?.sub_total || cart?.subtotal || 0);
        const progressBar = document.getElementById("free-shipping-progress");
        const remainingLabel = document.getElementById("free-shipping-remaining");

        if (!progressBar || !remainingLabel) return;

        const percentage = Math.min((subtotal / threshold) * 100, 100);
        progressBar.style.width = `${percentage}%`;

        const successText = remainingLabel.getAttribute("data-success-text");
        const templateText = remainingLabel.getAttribute("data-remaining-template");

        if (percentage >= 100 && successText) {
            remainingLabel.innerHTML = successText;
        } else if (templateText) {
            const diff = (threshold - subtotal).toFixed(2);
            remainingLabel.innerHTML = templateText.replace(":amount", `$${diff}`);
        }
    });
}

export default window.app;
