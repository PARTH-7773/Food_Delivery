document.addEventListener("DOMContentLoaded", () => {
  // --- SELECT ELEMENTS ---
  const hamburger = document.querySelector(".hamburger");
  const mobileMenu = document.querySelector(".mobile-menu");
  const cartIcon = document.querySelector(".cart-icon");
  const cartTab = document.querySelector(".cart-tab");
  const closeBtn = document.querySelector(".close-btn");
  const cardList = document.querySelector(".card-list");
  const cartList = document.querySelector(".cart-list");
  const cartTotal = document.querySelector(".cart-total");
  const cartValue = document.querySelector(".cart-value");

  // --- STATE MANAGEMENT ---
  let productList = [];
  let cart = [];

  // --- SWIPER INITIALIZATION ---
  // Note: Ensure your HTML has a container with the class "mySwiper" for this to work.
  if (document.querySelector(".mySwiper")) {
    new Swiper(".mySwiper", {
      loop: true,
      navigation: {
        nextEl: "#next",
        prevEl: "#prev",
      },
    });
  }

  // --- EVENT LISTENERS ---
  if (hamburger) {
    hamburger.addEventListener("click", () => {
      mobileMenu.classList.toggle("mobile-menu-active");
    });
  }
  if (cartIcon) {
    cartIcon.addEventListener("click", () => {
      cartTab.classList.add("cart-tab-active");
    });
  }
  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      cartTab.classList.remove("cart-tab-active");
    });
  }
  if (cardList) {
    cardList.addEventListener("click", (event) => {
      let productCard = event.target.closest(".order-card");
      if (!productCard) return;
      let productId = parseInt(productCard.dataset.id);
      if (event.target.classList.contains("card-btn")) {
        event.preventDefault();
        addToCart(productId);
      }
      if (event.target.classList.contains("buy-btn")) {
        event.preventDefault();
        addToCart(productId);
        window.location.href = "checkout.html";
      }
    });
  }

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

  // --- FUNCTIONS ---
  const displayProducts = () => {
    cardList.innerHTML = "";
    if (productList.length > 0) {
      productList.forEach((product) => {
        const orderCard = document.createElement("div");
        orderCard.classList.add("order-card");
        orderCard.dataset.id = product.id;
        orderCard.innerHTML = `
                    <div class="card-image"><img src="${product.image}"></div>
                    <h4>${product.name}</h4>
                    <h4 class="price">₹${parseFloat(product.price).toFixed(
                      2
                    )}</h4>
                    <div class="button-group">
                        <a href="#" class="btn card-btn">Add to Cart</a>
                        <a href="#" class="btn buy-btn">Buy Now</a>
                    </div>
                `;
        cardList.appendChild(orderCard);
      });
    }
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
      cartItem.className = "item";
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

    // Saves the cart to the browser's storage for the checkout page
    localStorage.setItem("foodieCart", JSON.stringify(cart));
  };

  const changeQuantity = (productId, type) => {
    let item = cart.find((i) => i.id == productId);
    if (item) {
      if (type === "plus") {
        item.quantity++;
      } else {
        item.quantity--;
        if (item.quantity <= 0) {
          cart = cart.filter((i) => i.id != productId);
        }
      }
    }
    updateCart();
  };

  const initApp = () => {
    fetch("get_products.php")
      .then((res) => res.json())
      .then((data) => {
        productList = data;
        displayProducts();
      })
      .catch((error) => console.error("Error fetching products:", error));
  };

  // main.js (Complete updated version)
  document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.querySelector(".hamburger"),
      mobileMenu = document.querySelector(".mobile-menu"),
      cartIcon = document.querySelector(".cart-icon"),
      cartTab = document.querySelector(".cart-tab"),
      closeBtn = document.querySelector(".close-btn"),
      cardList = document.querySelector(".card-list"),
      cartList = document.querySelector(".cart-list"),
      cartTotal = document.querySelector(".cart-total"),
      cartValue = document.querySelector(".cart-value");
    let productList = [],
      cart = [];
    if (document.querySelector(".mySwiper")) {
      new Swiper(".mySwiper", {
        loop: true,
        navigation: { nextEl: "#next", prevEl: "#prev" },
      });
    }
    if (hamburger) {
      hamburger.addEventListener("click", () => {
        mobileMenu.classList.toggle("mobile-menu-active");
      });
    }
    if (cartIcon) {
      cartIcon.addEventListener("click", () => {
        cartTab.classList.add("cart-tab-active");
      });
    }
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        cartTab.classList.remove("cart-tab-active");
      });
    }
    if (cardList) {
      cardList.addEventListener("click", (e) => {
        let t = e.target.closest(".order-card");
        if (t) {
          let a = parseInt(t.dataset.id);
          e.target.classList.contains("card-btn") &&
            (e.preventDefault(), addToCart(a)),
            e.target.classList.contains("buy-btn") &&
              (e.preventDefault(),
              addToCart(a),
              (window.location.href = "checkout.html"));
        }
      });
    }
    if (cartList) {
      cartList.addEventListener("click", (e) => {
        e.preventDefault();
        let t = e.target.closest(".quantity-btn");
        if (t) {
          let a = t.closest(".item"),
            d = parseInt(a.dataset.id),
            c = t.classList.contains("plus") ? "plus" : "minus";
          changeQuantity(d, c);
        }
      });
    }
    const displayProducts = () => {
      (cardList.innerHTML = ""),
        productList.forEach((e) => {
          let t = document.createElement("div");
          (t.className = "order-card"),
            (t.dataset.id = e.id),
            (t.innerHTML = `<div class="card-image"><img src="${
              e.image
            }"></div><h4>${e.name}</h4><h4 class="price">₹${parseFloat(
              e.price
            ).toFixed(
              2
            )}</h4><div class="button-group"><a href="#" class="btn card-btn">Add to Cart</a><a href="#" class="btn buy-btn">Buy Now</a></div>`),
            cardList.appendChild(t);
        });
    };
    const addToCart = (e) => {
      let t = cart.find((t) => t.id == e);
      if (t) t.quantity++;
      else {
        let a = productList.find((t) => t.id == e);
        a && cart.push({ ...a, quantity: 1 });
      }
      updateCart();
    };
    const updateCart = () => {
      cartList.innerHTML = "";
      let e = 0,
        t = 0;
      cart.forEach((a) => {
        let d = parseFloat(a.price) * a.quantity;
        (e += d), (t += a.quantity);
        let c = document.createElement("div");
        (c.className = "item"),
          (c.dataset.id = a.id),
          (c.innerHTML = `<div class="item-image"><img src="${
            a.image
          }"></div><div class="detail"><h4>${
            a.name
          }</h4><h4 class="item-total">₹${d.toFixed(
            2
          )}</h4></div><div class="flex"><a href="#" class="quantity-btn minus"><i class="fa-solid fa-minus"></i></a><h4 class="quantity-value">${
            a.quantity
          }</h4><a href="#" class="quantity-btn plus"><i class="fa-solid fa-plus"></i></a></div>`),
          cartList.appendChild(c);
      }),
        (cartTotal.textContent = `₹${e.toFixed(2)}`),
        (cartValue.textContent = t),
        localStorage.setItem("foodieCart", JSON.stringify(cart));
    };
    const changeQuantity = (e, t) => {
      let a = cart.find((t) => t.id == e);
      a &&
        ("plus" === t
          ? a.quantity++
          : (a.quantity--,
            a.quantity <= 0 && (cart = cart.filter((t) => t.id != e)))),
        updateCart();
    };
    const initApp = () => {
      fetch("get_products.php")
        .then((e) => e.json())
        .then((e) => {
          (productList = e), displayProducts();
        })
        .catch((e) => console.error("Error fetching products:", e));
    };
    initApp();
  });

  initApp();
});
