// --- NEW: AUTO-FILL USER DETAILS ---
function loadUserDetails() {
  fetch("get_user_info.php")
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        const user = result.data;
        // Fill the input fields if data exists
        if (document.getElementById("name"))
          document.getElementById("name").value = user.name || "";
        if (document.getElementById("phone"))
          document.getElementById("phone").value = user.phone || "";
        if (document.getElementById("address"))
          document.getElementById("address").value = user.address || "";
      }
    })
    .catch((error) => console.log("Could not load user details"));
}

loadUserDetails(); // Run immediately

document.addEventListener("DOMContentLoaded", () => {
  const orderItemsList = document.getElementById("order-items-list");
  const orderTotalPrice = document.getElementById("order-total-price");
  const checkoutForm = document.getElementById("checkout-form");

  const cart = JSON.parse(localStorage.getItem("foodieCart")) || [];

  // --- DISPLAY ORDER SUMMARY ---
  const displayOrderSummary = () => {
    orderItemsList.innerHTML = "";
    let totalAmount = 0;

    if (cart.length === 0) {
      orderItemsList.innerHTML = "<p>Your cart is empty.</p>";
      orderTotalPrice.textContent = "₹0.00";
      return;
    }

    cart.forEach((item) => {
      const itemPrice = parseFloat(item.price) * item.quantity;
      totalAmount += itemPrice;

      const itemElement = document.createElement("div");
      itemElement.className = "order-item";
      itemElement.innerHTML = `
                <span>${item.name} (x${item.quantity})</span>
                <strong>₹${itemPrice.toFixed(2)}</strong>
            `;
      orderItemsList.appendChild(itemElement);
    });

    orderTotalPrice.textContent = `₹${totalAmount.toFixed(2)}`;
  };

  // --- HANDLE PAYMENT & ORDER ---
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (e) {
      e.preventDefault(); // Stop page refresh

      const name = document.getElementById("name").value;
      const phone = document.getElementById("phone").value;
      const address = document.getElementById("address").value;

      const payButton = document.querySelector(".btn");

      // 1. Simulate "Processing Payment"
      payButton.innerText = "Verifying Card Details...";
      payButton.style.background = "#ccc";
      payButton.disabled = true;

      // 2. Wait 2 Seconds (Fake Delay)
      setTimeout(() => {
        payButton.innerText = "Processing Payment...";

        setTimeout(() => {
          // 3. Create a Fake Transaction ID
          const fakePaymentId =
            "pay_" + Math.random().toString(36).substr(2, 9);

          // 4. Save Order to Database
          saveOrderToDatabase(name, address, phone, fakePaymentId);
        }, 1500);
      }, 1500);
    });
  }

  // --- SAVE TO DATABASE ---
  function saveOrderToDatabase(name, address, phone, paymentId) {
    const customerDetails = { name, address, phone, payment_id: paymentId };
    const orderData = { customer: customerDetails, cart: cart };

    fetch("place_order.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(orderData),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          alert("Payment Successful! Transaction ID: " + paymentId);
          localStorage.removeItem("foodieCart"); // Clear cart
          window.location.href = "index.php"; // Go to homepage
        } else {
          alert("System Error: Could not save order.");
          document.querySelector(".btn").innerText = "Pay & Place Order";
          document.querySelector(".btn").disabled = false;
          document.querySelector(".btn").style.background =
            "var(--gold-finger)";
        }
      })
      .catch((error) => {
        console.error(error);
        alert("Network Error.");
      });
  }

  displayOrderSummary();
});
