<!-- Cart Drawer Partial -->
<div class="cart-overlay" id="cart-overlay"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 1055; display: none;">
</div>
<div class="cart-drawer" id="cart-drawer"
    style="position: fixed; top: 0; right: -400px; width: 400px; height: 100vh; background: #0a0a0a; border-left: 1px solid rgba(255,255,255,0.08); z-index: 1060; transition: right 0.4s cubic-bezier(0.77, 0, 0.175, 1); padding: 2rem; display: flex; flex-direction: column; box-shadow: -20px 0 50px rgba(0,0,0,0.8); color: white;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Order Details</h3>
        <button class="btn text-white fs-4 p-0" id="close-cart"><i class="fas fa-times"></i></button>
    </div>

    <div id="cart-items-container" class="flex-grow-1 overflow-auto pe-2">
        <!-- Items injected via JS -->
        <div class="text-center py-5 opacity-25">
            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
            <p>Your basket is empty</p>
        </div>
    </div>

    <div class="cart-footer" style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 2rem;">
        <div class="d-flex justify-content-between mb-3 fs-5">
            <span class="text-white-50">Subtotal</span>
            <span class="fw-bold text-primary-gold" id="cart-total" style="color: #FCDD09;">ETB 0.00</span>
        </div>
        <button class="btn btn-primary-gold w-100 py-3 fw-bold rounded-4 mb-3" id="checkout-btn-drawer"
            style="background: #FCDD09; color: #000; border: none;">
            Confirm & Place Order
        </button>
        <p class="small text-center text-white-50">Free delivery for orders above ETB 1000.</p>
    </div>
</div>

<script>
    // Universal Drawer Logic
    (function () {
        const drawer = document.getElementById('cart-drawer');
        const overlay = document.getElementById('cart-overlay');
        const openBtn = document.getElementById('open-cart') || document.getElementById('checkout-btn');
        const closeBtn = document.getElementById('close-cart');

        if (openBtn) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                drawer.classList.add('open');
                overlay.style.display = 'block';
                if (typeof updateCartDrawerUI === 'function') updateCartDrawerUI();
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                drawer.classList.remove('open');
                overlay.style.display = 'none';
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                drawer.classList.remove('open');
                overlay.style.display = 'none';
            });
        }

        const checkoutBtnDrawer = document.getElementById('checkout-btn-drawer');
        if (checkoutBtnDrawer) {
            checkoutBtnDrawer.addEventListener('click', () => {
                if (typeof submitOrder === 'function') submitOrder();
            });
        }
    })();
</script>

<style>
    .cart-drawer.open {
        right: 0 !important;
    }

    .cart-item-ui {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 16px;
        padding: 15px;
        display: flex;
        gap: 15px;
        margin-bottom: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.1);
    }
</style>