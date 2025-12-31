// Enhanced Cart & Interaction Logic
let cart = JSON.parse(localStorage.getItem('restaurant_cart')) || [];

document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();

    // Add to Cart Listeners
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('.add-to-cart-btn');
        if (btn) {
            e.preventDefault(); // Prevent jump
            const id = btn.dataset.id;
            const card = btn.closest('.card');
            const name = card.querySelector('.card-title').innerText;
            // Extract raw number price
            const priceText = card.querySelector('.item-price').innerText;
            // Handles "ETB 150.00" or "$150.00"
            const priceStr = priceText.replace(/[^0-9.]/g, '');
            const price = parseFloat(priceStr);

            addToCart(id, name, price);

            // Animation Feedback
            const icon = btn.querySelector('i');
            icon.classList.remove('fa-cart-plus');
            icon.classList.add('fa-check');
            setTimeout(() => {
                icon.classList.add('fa-cart-plus');
                icon.classList.remove('fa-check');
            }, 1000);
        }
    });

    // Checkout Button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', submitOrder);
    }

    // AJAX Reservation Form
    const resForm = document.querySelector('form[action="app/api/reservations.php"]');
    if (resForm) {
        resForm.addEventListener('submit', handleReservationSubmit);
    }
});

function addToCart(id, name, price) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1 });
    }
    saveCart();
    updateCartUI();
    showToast(`${name} added to your order`, 'success');
}

function saveCart() {
    localStorage.setItem('restaurant_cart', JSON.stringify(cart));
}

function updateCartUI() {
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cart-count');
    if (badge) {
        badge.innerText = count;
        badge.style.transform = 'scale(1.2)';
        setTimeout(() => badge.style.transform = 'scale(1)', 200);
    }
    updateCartDrawerUI();
}

function updateCartDrawerUI() {
    const container = document.getElementById('cart-items-container');
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5 opacity-25">
                <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                <p>Your basket is empty</p>
            </div>`;
        document.getElementById('cart-total').innerText = 'ETB 0.00';
        return;
    }

    container.innerHTML = cart.map(item => `
        <div class="cart-item-ui">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="mb-0 fw-bold">${item.name}</h6>
                    <button class="btn btn-sm text-danger p-0" onclick="removeFromCart('${item.id}')">
                        <i class="fas fa-trash-alt small"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="qty-control d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-glass p-0 px-2" onclick="updateQty('${item.id}', -1)">-</button>
                        <span class="small">${item.qty}</span>
                        <button class="btn btn-sm btn-glass p-0 px-2" onclick="updateQty('${item.id}', 1)">+</button>
                    </div>
                    <span class="text-primary-gold small fw-bold">ETB ${(item.price * item.qty).toFixed(2)}</span>
                </div>
            </div>
        </div>
    `).join('');

    document.getElementById('cart-total').innerText = 'ETB ' + calculateTotal();
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) {
            removeFromCart(id);
        } else {
            saveCart();
            updateCartUI();
        }
    }
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
    updateCartUI();
}

function submitOrder() {
    if (cart.length === 0) {
        showToast('Your cart is empty!', 'warning');
        return;
    }

    const btn = document.getElementById('checkout-btn-drawer');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Placing Order...';
    btn.disabled = true;

    fetch('app/api/orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ items: cart })
    })
        .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Server returned non-JSON response:', text);
                throw new Error('Server returned invalid data format.');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                showToast('Order confirmed! Moving to tracker...', 'success');
                cart = [];
                saveCart();
                updateCartUI();
                setTimeout(() => {
                    window.location.href = 'dashboard/orders.php';
                }, 1500);
            } else {
                showToast(data.message || 'Order failed.', 'danger');
                if (data.message && data.message.includes('Login')) {
                    setTimeout(() => window.location.href = 'login.php', 1500);
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Order Error:', err);
            showToast(err.message || 'Connection error. Please try again.', 'danger');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
}

function calculateTotal() {
    return cart.reduce((sum, item) => sum + (item.price * item.qty), 0).toFixed(2);
}

function handleReservationSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitBtn.disabled = true;

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
        .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Server returned non-JSON response:', text);
                throw new Error('Server returned invalid data format.');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                showToast('Table Reserved Successfully!', 'success');
                form.reset();
            } else {
                showToast(data.message || 'Reservation failed.', 'danger');
            }
        })
        .catch(err => {
            console.error('Reservation Error:', err);
            showToast(err.message || 'Something went wrong.', 'danger');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
}

// Custom Toast Notification System
function showToast(message, type = 'success') {
    // Create container if not exists
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.minWidth = '250px';
    toast.style.marginBottom = '10px';

    // Bootstrap Toast HTML Structure
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    container.appendChild(toast);

    // Auto remove after 3s
    setTimeout(() => {
        toast.remove();
    }, 3000);

    // Close button logic
    const closeBtn = toast.querySelector('.btn-close');
    closeBtn.addEventListener('click', () => toast.remove());
}
