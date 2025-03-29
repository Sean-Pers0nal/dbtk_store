document.addEventListener("DOMContentLoaded", function() {
    const cartIcon = document.getElementById("cart-icon");
    const cartWindow = document.getElementById("cart-window");
    const closeCart = document.querySelector(".close-cart");

    cartIcon.addEventListener("click", function(event) {
        event.preventDefault();
        cartWindow.classList.toggle("show");
    });

    closeCart.addEventListener("click", function() {
        cartWindow.classList.remove("show");
    });

    // Close cart if user clicks outside of it
    document.addEventListener("click", function(event) {
        if (!cartWindow.contains(event.target) && event.target !== cartIcon) {
            cartWindow.classList.remove("show");
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    let dashboard = document.getElementById("dashboard");
    let closeButton = document.getElementById("closeDashboard");

    // Show the dashboard when the page loads
    dashboard.style.display = "block";
    dashboard.style.animation = "fadeIn 0.5s ease-in-out";

    // Hide the dashboard after 3 seconds
    setTimeout(() => {
        dashboard.style.display = "none";
    }, 3000);

    // Close dashboard manually when clicking (X)
    closeButton.addEventListener("click", function() {
        dashboard.style.display = "none";
    });
});
function confirmRemove() {
    return confirm("Are you sure you want to remove this item from your cart?");
}