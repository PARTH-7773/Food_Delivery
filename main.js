document.addEventListener("DOMContentLoaded", () => {
  // --- 1. SELECT ELEMENTS ---
  // Layout Elements
  const hamburger = document.querySelector(".hamburger");
  const mobileMenu = document.querySelector(".mobile-menu");
  const closeMenuBtn = document.querySelector(".close-menu-btn");

  // Cart Elements
  const cartIcon = document.querySelector(".cart-icon");
  const cartTab = document.querySelector(".cart-tab");
  const closeBtn = document.querySelector(".close-btn"); // Close Cart
  const cardList = document.querySelector(".card-list");
  const cartList = document.querySelector(".cart-list");
  const cartTotal = document.querySelector(".cart-total");
  const cartValue = document.querySelector(".cart-value");

  // Search & Filter Elements
  const searchInput = document.getElementById("search-input");
  const categoryBtns = document.querySelectorAll(".cat-btn");

  // Login Popup Elements
  const loginPopup = document.getElementById("login-popup");
  const closePopupBtn = document.getElementById("close-popup-btn");

  // --- 2. STATE MANAGEMENT ---
  let productList = [];
  let cart = [];

  // --- 3. SWIPER SLIDER ---
  if (document.querySelector(".mySwiper")) {
    new Swiper(".mySwiper", {
      loop: true,
      navigation: {
        nextEl: "#next",
        prevEl: "#prev",
      },
    });
  }

  // --- 4. EVENT LISTENERS (Layout) ---

  // Mobile Menu
  if (hamburger) {
    hamburger.addEventListener("click", () => {
      mobileMenu.classList.add("mobile-menu-active");
    });
  }

  // Close Menu (Using the X button class)
  if (closeMenuBtn) {
    closeMenuBtn.addEventListener("click", () => {
      mobileMenu.classList.remove("mobile-menu-active");
    });
  }

  // Cart Open/Close
  if (cartIcon)
    cartIcon.addEventListener("click", () =>
      cartTab.classList.add("cart-tab-active")
    );
  if (closeBtn)
    closeBtn.addEventListener("click", () =>
      cartTab.classList.remove("cart-tab-active")
    );

  // Login Popup Close
  if (closePopupBtn) {
    closePopupBtn.addEventListener("click", () => {
      loginPopup.classList.remove("active");
    });
  }
  // Close popup if clicking outside content
  if (loginPopup) {
    loginPopup.addEventListener("click", (e) => {
      if (e.target === loginPopup) loginPopup.classList.remove("active");
    });
  }

  // --- 5. SEARCH & FILTER LOGIC ---

  // A. Search Bar
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      const value = e.target.value.toLowerCase();
      const filtered = productList.filter((item) =>
        item.name.toLowerCase().includes(value)
      );
      displayProducts(filtered);
    });
  }

  // B. Category Buttons
  if (categoryBtns) {
    categoryBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        categoryBtns.forEach((b) => b.classList.remove("active-cat"));
        btn.classList.add("active-cat");

        const category = btn.dataset.id;
        if (category === "all") {
          displayProducts(productList);
        } else {
          const filtered = productList.filter(
            (item) => item.category === category
          );
          displayProducts(filtered);
        }
      });
    });
  }

  // --- 6. PRODUCT CARD ACTIONS (Add/Buy) ---
  if (cardList) {
    cardList.addEventListener("click", (event) => {
      let productCard = event.target.closest(".order-card");
      if (!productCard) return;
      let productId = parseInt(productCard.dataset.id);

      // Handle "Add to Cart"
      if (event.target.classList.contains("card-btn")) {
        event.preventDefault();
        addToCart(productId);
      }

      // Handle "Buy Now"
      if (event.target.classList.contains("buy-btn")) {
        event.preventDefault();

        // CHECK IF LOGGED IN (Using variable from index.php)
        if (typeof isLoggedIn !== "undefined" && !isLoggedIn) {
          // Show the Login Popup
          if (loginPopup) loginPopup.classList.add("active");
          else alert("Please login first.");
          return;
        }

        addToCart(productId);
        window.location.href = "new_checkout.php";
      }
    });
  }

  // --- 7. CART ACTIONS (Quantity) ---
  if (cartList) {
    cartList.addEventListener("click", (event) => {
      event.preventDefault();
      let button = event.target.closest(".quantity-btn");
      if (button) {
        let itemElement = button.closest(".item");
        let product_id = parseInt(itemElement.dataset.id);
        let type = button.classList.contains("plus") ? "plus" : "minus";
        changeQuantity(product_id, type);
      }
    });
  }

  // --- 8. CORE FUNCTIONS ---

  const displayProducts = (items = productList) => {
    cardList.innerHTML = "";

    if (items.length === 0) {
      cardList.innerHTML =
        '<h3 style="grid-column: 1/-1; color: gray;">No items found :(</h3>';
      return;
    }

    items.forEach((product) => {
      const orderCard = document.createElement("div");
      orderCard.classList.add("order-card");
      orderCard.dataset.id = product.id;
      orderCard.innerHTML = `
                <div class="card-image"><img src="${product.image}"></div>
                <h4>${product.name}</h4>
                <h4 class="price">₹${parseFloat(product.price).toFixed(2)}</h4>
                <div class="button-group">
                    <a href="#" class="btn card-btn">Add to Cart</a>
                    <a href="#" class="btn buy-btn">Buy Now</a>
                </div>
            `;
      cardList.appendChild(orderCard);
    });
  };

  const addToCart = (productId) => {
    const existingProduct = cart.find((item) => item.id == productId);
    if (existingProduct) {
      existingProduct.quantity++;
    } else {
      const productToAdd = productList.find((item) => item.id == productId);
      if (productToAdd) {
        cart.push({ ...productToAdd, quantity: 1 });
      }
    }
    updateCart();
  };

  const updateCart = () => {
    cartList.innerHTML = "";
    let total = 0,
      totalItems = 0;
    cart.forEach((item) => {
      let itemPrice = parseFloat(item.price) * item.quantity;
      total += itemPrice;
      totalItems += item.quantity;
      const cartItem = document.createElement("div");
      cartItem.classList.add("item");
      cartItem.dataset.id = item.id;
      cartItem.innerHTML = `
                <div class="item-image"><img src="${item.image}"></div>
                <div class="detail"><h4>${
                  item.name
                }</h4><h4 class="item-total">₹${itemPrice.toFixed(2)}</h4></div>
                <div class="flex">
                    <a href="#" class="quantity-btn minus"><i class="fa-solid fa-minus"></i></a>
                    <h4 class="quantity-value">${item.quantity}</h4>
                    <a href="#" class="quantity-btn plus"><i class="fa-solid fa-plus"></i></a>
                </div>`;
      cartList.appendChild(cartItem);
    });
    cartTotal.textContent = `₹${total.toFixed(2)}`;
    cartValue.textContent = totalItems;

    // Save Cart to Storage
    localStorage.setItem("foodieCart", JSON.stringify(cart));
  };

  const changeQuantity = (productId, type) => {
    let item = cart.find((i) => i.id == productId);
    if (item) {
      if (type === "plus") item.quantity++;
      else {
        item.quantity--;
        if (item.quantity <= 0) cart = cart.filter((i) => i.id != productId);
      }
    }
    updateCart();
  };

  // --- 9. INITIALIZATION ---
  const initApp = () => {
    fetch("get_products.php")
      .then((res) => res.json())
      .then((data) => {
        productList = data;
        displayProducts(productList); // Display all initially

        // Load cart from storage
        if (localStorage.getItem("foodieCart")) {
          cart = JSON.parse(localStorage.getItem("foodieCart"));
          updateCart();
        }
      })
      .catch((error) => console.error("Error fetching products:", error));
  };

  initApp();
});
