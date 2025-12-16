// Simple Cart System
let cart = JSON.parse(localStorage.getItem('restaurant_cart')) || [];

document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();

    // Add to Cart Listeners
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('.add-to-cart-btn');
        if (btn) {
            const id = btn.dataset.id;
            const card = btn.closest('.card');
            const name = card.querySelector('.card-title').innerText;
            const priceStr = card.querySelector('.item-price').innerText.replace(/[^0-9.]/g, '');
            const price = parseFloat(priceStr);

            addToCart(id, name, price);
        }
    });

    // Checkout Button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', submitOrder);
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
    // Simple toast
    alert(`${name} added to cart!`);
}

function saveCart() {
    localStorage.setItem('restaurant_cart', JSON.stringify(cart));
}

function updateCartUI() {
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cart-count');
    if (badge) badge.innerText = count;

    // Update Float Button or Modal if exists
}

function submitOrder() {
    if (cart.length === 0) {
        alert('Cart is empty!');
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
                alert('Order placed successfully!');
                cart = [];
                saveCart();
                updateCartUI();
                window.location.href = 'dashboard/orders.php';
            } else {
                alert('Order failed: ' + data.message);
                if (data.message.includes('Login')) window.location.href = 'login.php';
            }
        })
        .catch(err => console.error(err));
}
