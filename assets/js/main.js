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
        // Bump animation
        badge.classList.add('animate__rubberBand'); // Requires animate.css or manually add keyframes
        // fallback manual scale
        badge.style.transform = 'scale(1.2)';
        setTimeout(() => badge.style.transform = 'scale(1)', 200);
    }
}

function submitOrder() {
    if (cart.length === 0) {
        showToast('Your cart is empty!', 'warning');
        return;
    }

    if (!confirm(`Place order for ${cart.length} items? Total: ETB ${calculateTotal()}`)) {
        return;
    }

    // Send to API
    fetch('app/api/orders.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ items: cart })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Order placed successfully! Kitchen notified.', 'success');
                cart = [];
                saveCart();
                updateCartUI();
                setTimeout(() => {
                    window.location.href = 'dashboard/orders.php';
                }, 1500);
            } else {
                showToast(data.message, 'danger');
                if (data.message && data.message.includes('Login')) {
                    setTimeout(() => window.location.href = 'login.php', 1500);
                }
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Connection error. Please try again.', 'danger');
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
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Table Reserved Successfully!', 'success');
                form.reset();
            } else {
                showToast(data.message || 'Reservation failed.', 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Something went wrong.', 'danger');
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
