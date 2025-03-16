// subsidy_payment_officer_dashboard.js
document.querySelectorAll('.pending-payments button').forEach(button => {
    button.addEventListener('click', (event) => {
        alert('Processing payment for ' + event.target.parentElement.parentElement.cells[0].textContent);
    });
});
