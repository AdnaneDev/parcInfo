function openPaymentPopup(id, status) {
    const paymentPopup = document.getElementById('paymentPopup');
    paymentPopup.style.display = 'block';

    const confirmPaymentButton = document.getElementById('confirmPaymentButtonPopup');


    confirmPaymentButton.onclick = function () {
        const paymentOptionFull = document.getElementById('paymentOptionFull');
        if (paymentOptionFull.checked) {
            confirmFullPayment(id);
        } else {
            openPartialPaymentPopup(id);
        }
        paymentPopup.style.display = 'none';
    };

    const cancelPaymentButton = document.getElementById('cancelPaymentButtonPopup');
    cancelPaymentButton.onclick = function () {
        paymentPopup.style.display = 'none';
    };
}
function filterFactures() {
    const filterStatus = document.getElementById('filterStatus').value;

    const factures = document.getElementsByClassName('facture-row');

    for (const facture of factures) {
        const factureStatus = facture.getAttribute('data-status');

        if (filterStatus === 'all' || factureStatus === filterStatus) {
            facture.style.display = 'table-row';
        } else {
            facture.style.display = 'none';
        }
    }
}
function filterFactures2() {
    const selectedStatus = document.getElementById('filterStatus').value;
    const selectedSupplier = document.getElementById('filterSupplier').value;

    const factureRows = document.querySelectorAll('.facture-row');
    factureRows.forEach(row => {
        const status = row.getAttribute('data-status');
        const supplier = row.getAttribute('data-supplier');

        if ((selectedStatus === 'all' || status === selectedStatus) &&
            (selectedSupplier === 'all' || supplier === selectedSupplier)) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}
function filterFactures3() {
    const filterStatus = document.getElementById('filterStatus').value;
    const filterSupplier = document.getElementById('filterSupplier').value;
    const filterMonth = document.getElementById('filterMonth').value;

    const facturesTable = document.getElementById('myDataTable');
    const rows = facturesTable.querySelectorAll('.facture-row');

    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        const supplier = row.getAttribute('data-supplier');
        const month = row.getAttribute('data-month');

        const showRow =
            (filterStatus === 'all' || status === filterStatus) &&
            (filterSupplier === 'all' || supplier === filterSupplier) &&
            (filterMonth === 'all' || month === filterMonth);

        row.style.display = showRow ? 'table-row' : 'none';
    });
}


/* function openPaymentPopup(id,status) {
 
    const paymentPopup = document.getElementById('paymentPopup');
    paymentPopup.style.display = 'block'; 
    const confirmPaymentButton = document.getElementById('confirmPaymentButtonPopup');
    confirmPaymentButton.onclick = function() {
        const paymentOptionFull = document.getElementById('paymentOptionFull');
        if (paymentOptionFull.checked) {
            confirmFullPayment(id);
        } else {
            openPartialPaymentPopup(id);
        }
        paymentPopup.style.display = 'none';
    };

    const cancelPaymentButton = document.getElementById('cancelPaymentButtonPopup');
    cancelPaymentButton.onclick = function() {
        paymentPopup.style.display = 'none';
    };
}

 */


function openPartialPaymentPopup(id) {
    const partialPaymentPopup = document.getElementById('partialPaymentPopup');
    partialPaymentPopup.style.display = 'block';

    const confirmPartialPaymentButton = document.getElementById('confirmPartialPaymentButton');
    confirmPartialPaymentButton.onclick = function () {
        const partialAmountInput = document.getElementById('partialAmountInput');
        const partialAmount = parseFloat(partialAmountInput.value);

        if (isNaN(partialAmount) || partialAmount <= 0) {
            alert("Veuillez entrer un montant valide pour le paiement partiel.");
            return;
        }

        confirmPartialPayment(id, partialAmount);
        partialPaymentPopup.style.display = 'none';
    };

    const cancelPartialPaymentButton = document.getElementById('cancelPartialPaymentButton');
    cancelPartialPaymentButton.onclick = function () {
        partialPaymentPopup.style.display = 'none';
    };
}
