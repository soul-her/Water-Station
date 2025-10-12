document.addEventListener('DOMContentLoaded', function () {
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartBackdrop = document.getElementById('cart-backdrop');
    const checkoutBtn = document.getElementById('checkout-btn');

    // === Sidebar Controls ===
    window.openCartSidebar = function () {
        cartSidebar.classList.add('open');
        cartBackdrop.classList.remove('hidden');
        loadCart();
    };

    window.closeCartSidebar = function () {
        cartSidebar.classList.remove('open');
        cartBackdrop.classList.add('hidden');
    };

    // === Add to Cart ===
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const id = this.getAttribute('data-id');

            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ id })
            });

            const data = await response.json();
            if (data.cart) {
                updateCartCount(data.cart);
                openCartSidebar(); // Auto-open sidebar
            }
        });
    });

    // === Checkout Redirect ===
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            window.location.href = '/cart/checkout';
        });
    }
});

// === Load Cart ===
async function loadCart() {
    const response = await fetch('/cart/view');
    const data = await response.json();

    const cartItemsDiv = document.getElementById('cart-items');
    const totalSpan = document.getElementById('cart-total');
    cartItemsDiv.innerHTML = '';
    let total = 0;

    for (const id in data.cart) {
        const item = data.cart[id];
        total += item.price * item.quantity;

        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'justify-between', 'border-b', 'pb-2');

        div.innerHTML = `
            <div class="flex items-center space-x-3">
                <img src="/uploads/products/${item.image}" alt="${item.name}" class="w-12 h-12 rounded-lg object-cover">
                <div>
                    <p class="font-semibold">${item.name}</p>
                    <p class="text-gray-500 text-sm">₱${item.price} × ${item.quantity}</p>
                </div>
            </div>
            <button onclick="removeItem(${item.id})" class="text-red-500 hover:text-red-700 font-bold text-xl">&times;</button>
        `;
        cartItemsDiv.appendChild(div);
    }

    totalSpan.textContent = total.toFixed(2);
    updateCartCount(data.cart);
}

// === Update Cart Count ===
function updateCartCount(cart) {
    const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) cartCountEl.textContent = count;
}

// === Remove & Clear Cart ===
async function removeItem(id) {
    await fetch('/cart/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id })
    });
    loadCart();
}

async function clearCart() {
    await fetch('/cart/clear', { method: 'POST' });
    loadCart();
}
