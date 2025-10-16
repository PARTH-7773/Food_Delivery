// checkout.js (Complete updated version)
document.addEventListener('DOMContentLoaded', () => {
    const orderItemsList = document.getElementById('order-items-list');
    const orderTotalPrice = document.getElementById('order-total-price');
    const checkoutForm = document.getElementById('checkout-form');
    const cart = JSON.parse(localStorage.getItem('foodieCart')) || [];

    const displayOrderSummary = () => {
        orderItemsList.innerHTML = '';
        let total = 0;
        if (cart.length === 0) {
            orderItemsList.innerHTML = '<p>Your cart is empty.</p>';
            orderTotalPrice.textContent = '₹0.00';
            return;
        }
        cart.forEach(item => {
            const itemPrice = parseFloat(item.price) * item.quantity;
            total += itemPrice;
            const itemElement = document.createElement('div');
            itemElement.className = 'order-item';
            itemElement.innerHTML = `<span>${item.name} (x${item.quantity})</span><strong>₹${itemPrice.toFixed(2)}</strong>`;
            orderItemsList.appendChild(itemElement);
        });
        orderTotalPrice.textContent = `₹${total.toFixed(2)}`;
    };

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const customerDetails = {
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                phone: document.getElementById('phone').value,
            };
            const orderData = { customer: customerDetails, cart: cart };
            fetch('place_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order placed successfully!');
                    localStorage.removeItem('foodieCart');
                    window.location.href = 'index.php';
                } else {
                    alert('There was an error placing your order. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was a network error. Please try again.');
            });
        });
    }

    displayOrderSummary();
});