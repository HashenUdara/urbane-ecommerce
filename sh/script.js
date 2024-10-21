const increaseBtn = document.getElementById('increase');
const decreaseBtn = document.getElementById('decrease');
const counterValue = document.getElementById('counter-value');
const totalValue = document.getElementById('total-value');
const finalTotal = document.getElementById('final-total');
const applyCouponBtn = document.getElementById('apply-coupon');
const couponInput = document.getElementById('coupon');
const couponMessage = document.getElementById('coupon-message');
const placeOrderBtn = document.getElementById('place-order');
const successMessage = document.getElementById('success-message');

let counter = 0;
const pricePerUnit = 10;
let totalAmount = 0;
let discount = 0;

// Update display for cart and totals
function updateDisplay() {
    counterValue.textContent = counter;
    totalAmount = counter * pricePerUnit;
    totalValue.textContent = totalAmount.toFixed(2);
    finalTotal.textContent = (totalAmount * (1 - discount)).toFixed(2);
}

// Increase quantity
increaseBtn.addEventListener('click', () => {
    counter++;
    updateDisplay();
});

// Decrease quantity
decreaseBtn.addEventListener('click', () => {
    if (counter > 0) {
        counter--;
        updateDisplay();
    }
});

// Apply coupon
applyCouponBtn.addEventListener('click', () => {
    const couponCode = couponInput.value.trim().toUpperCase();
    if (couponCode === 'SAVE10') {
        discount = 0.1; // 10% discount
        couponMessage.textContent = 'Coupon applied: 10% off';
        couponMessage.style.color = 'green';
    } else {
        discount = 0;
        couponMessage.textContent = 'Invalid coupon code';
        couponMessage.style.color = 'red';
    }
    updateDisplay();
});

// Place order
placeOrderBtn.addEventListener('click', () => {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;

    if (name && email && phone && address) {
        successMessage.textContent = 'Order placed successfully!';
        successMessage.style.color = 'green';
    } else {
        successMessage.textContent = 'Please fill in all fields!';
        successMessage.style.color = 'red';
    }
});
