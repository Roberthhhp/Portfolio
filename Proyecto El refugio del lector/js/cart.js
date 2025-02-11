document.addEventListener("DOMContentLoaded", () => {
    const cartButton = document.getElementById("view-cart");
    const modal = document.getElementById("cartModal");
    const closeModal = document.getElementById("closeModal");
    const clearCartButton = document.getElementById("clearCart");
    const cartItemsList = document.getElementById("cartItems");

    let carrito = [];

    const updateCartModal = () => {
      cartItemsList.innerHTML = carrito.length === 0
        ? "<li>El carrito está vacío.</li>"
        : carrito.map(item => `<li>${item}</li>`).join("");
    };

    cartButton.addEventListener("click", () => {
      updateCartModal();
      modal.style.display = "flex";
    });

    closeModal.addEventListener("click", () => {
      modal.style.display = "none";
    });

    clearCartButton.addEventListener("click", () => {
      carrito = [];
      updateCartModal();
    });
  });

//_--
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.querySelector(".menu-toggle");
  const navLinks = document.querySelector(".nav-links");

  // Alternar la clase 'active' al hacer clic en el botón
  menuToggle.addEventListener("click", () => {
    navLinks.classList.toggle("active");
  });
});
