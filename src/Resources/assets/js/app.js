/**
 * Luxury Beauty & Nails Theme - Frontend Entrypoint
 * Compatible with Bagisto 2.5 (Vue 3.5 + Tailwind CSS v4)
 */
import mitt from "mitt";

// Global Event Emitter for Beauty Theme Micro-Interactions
window.beautyEmitter = window.beautyEmitter || mitt();

/**
 * Initialize DOM interactions once ready
 */
document.addEventListener("DOMContentLoaded", () => {
    initStickyBuyBar();
    initSwatchInteractions();
    initMiniCartProgress();
});

/**
 * Mobile Sticky Buy Bar Observer
 * Automatically reveals when the user scrolls past the main CTA on product page
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
        const threshold = 50.0; // Customizable free shipping threshold
        const subtotal = parseFloat(cart?.subtotal || 0);
        const progressBar = document.getElementById("free-shipping-progress");
        const remainingLabel = document.getElementById("free-shipping-remaining");

        if (!progressBar || !remainingLabel) return;

        const percentage = Math.min((subtotal / threshold) * 100, 100);
        progressBar.style.width = `${percentage}%`;

        if (percentage >= 100) {
            remainingLabel.innerHTML = "<strong>¡Felicidades!</strong> Tienes envío gratuito";
        } else {
            const diff = (threshold - subtotal).toFixed(2);
            remainingLabel.innerHTML = `Te faltan <strong>$${diff}</strong> para envío gratis`;
        }
    });
}
