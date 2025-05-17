document.addEventListener('DOMContentLoaded', function () {
    const retailPrice = document.getElementById('retail_price');
    const quantity = document.getElementById('quantity');
    const cost = document.getElementById('cost');

    function updateCost() {
        const price = parseFloat(retailPrice.value) || 0;
        const qty = parseFloat(quantity.value) || 0;
        cost.value = price * qty;
    }

    if (retailPrice && quantity && cost) {
        retailPrice.addEventListener('input', updateCost);
        quantity.addEventListener('input', updateCost);
    }
});