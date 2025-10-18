document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;

            this.disabled = true;

            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id, name, price })
                });

                // if backend returns JSON, handle it silently
                if (response.ok) {
                    const data = await response.json();
                    updateCartCount(data.cart);
                    showToast(`✅ ${name} added to cart!`);
                }
            } catch (error) {
                // no alerts or logs shown to user
                console.error('Add to cart failed:', error);
            } finally {
                this.disabled = false;
            }
        });
    });
});

function updateCartCount(cart) {
    const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    const cartCount = document.getElementById('cart-count');
    if (cartCount) cartCount.textContent = count;
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}
