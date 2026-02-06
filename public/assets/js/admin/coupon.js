document.addEventListener('DOMContentLoaded', () => {

    const filterForm = document.getElementById('couponFilterForm');

    /* ===============================
       AUTO SUBMIT SELECT FILTERS
    =============================== */
    document.querySelectorAll('.auto-submit').forEach(el => {
        el.addEventListener('change', () => filterForm.submit());
    });

    /* ===============================
       SEARCH WITH CLEAR BUTTON
    =============================== */
   /* ===============================
   SEARCH WITH AUTO SUBMIT
=============================== */
const searchInput = document.getElementById('couponSearch');
const clearBtn    = document.getElementById('clearCouponSearch');

let searchTimer = null;

if (searchInput && clearBtn) {

    // show clear button if value exists
    if (searchInput.value.trim() !== '') {
        clearBtn.style.display = 'block';
    }

    // 🔍 AUTO SEARCH ON TYPE / DELETE
    searchInput.addEventListener('input', () => {

        clearBtn.style.display =
            searchInput.value.trim() ? 'block' : 'none';

        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {

    const pageInput = filterForm.querySelector('input[name="page"]');
    if (pageInput) pageInput.remove();

    filterForm.submit();

}, 500);

    });

    // ❌ CLEAR + AUTO SEARCH
   clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    clearBtn.style.display = 'none';

    const pageInput = filterForm.querySelector('input[name="page"]');
    if (pageInput) pageInput.remove();

    filterForm.submit();
});

}

    /* ===============================
       BULK DELETE LOGIC
    =============================== */
    const selectAll = document.getElementById('selectAllCoupons');
    const checkboxes = document.querySelectorAll('.coupon-row-checkbox');
    const bulkBtn = document.getElementById('couponBulkDeleteBtn');
    const bulkForm = document.getElementById('couponBulkDeleteForm');

    function toggleBulkBtn() {
        const checked = [...checkboxes].some(cb => cb.checked);
        bulkBtn.disabled = !checked;
    }

    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            toggleBulkBtn();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            selectAll.checked =
                [...checkboxes].every(c => c.checked);
            toggleBulkBtn();
        });
    });

    bulkBtn?.addEventListener('click', () => {
        if (confirm('Delete selected coupons?')) {
            bulkForm.submit();
        }
    });

    /* ===============================
       ADVANCED FILTER SIDEBAR
    =============================== */
    const sidebar = document.getElementById('couponFilterSidebar');
    const openBtn = document.getElementById('openCouponFilterSidebar');
    const closeBtn = document.getElementById('closeCouponFilterSidebar');

    openBtn?.addEventListener('click', () => {
        sidebar.classList.add('open');
    });

    closeBtn?.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });

    /* ===============================
       APPLY ADVANCED FILTER
    =============================== */
    const applyBtn = document.getElementById('applyCouponAdvancedFilter');

    applyBtn?.addEventListener('click', () => {
        const field = document.getElementById('couponAdvField').value;
        const cond  = document.getElementById('couponAdvCondition').value;
        const val   = document.getElementById('couponAdvValue').value;

        if (!val) return;

        const url = new URL(window.location.href);

        url.searchParams.set('adv_field', field);
        url.searchParams.set('adv_condition', cond);
        url.searchParams.set('adv_value', val);

        window.location.href = url.toString();
    });
let bankOfferIndex =
    document.querySelectorAll('.bank-offer-row').length;

document.getElementById('addBankOfferBtn')?.addEventListener('click', () => {

    const wrapper = document.getElementById('bankOffersWrapper');

    const html = `
    <div class="border rounded p-3 mb-3 bank-offer-row">
        <div class="row">

            <div class="col-md-4 mb-2">
                <label class="form-label">Bank *</label>
                <select name="bank_offers[${bankOfferIndex}][bank_id]" class="form-control">
                    <option value="">Select Bank</option>
                    ${window.banks.map(b =>
                        `<option value="${b.id}">${b.name}</option>`
                    ).join('')}
                </select>
            </div>

            <div class="col-md-4 mb-2">
                <label class="form-label">Card Type *</label>
                <select name="bank_offers[${bankOfferIndex}][card_type]" class="form-control">
                    <option value="credit">Credit</option>
                    <option value="debit">Debit</option>
                    <option value="emi">EMI</option>
                </select>
            </div>

            <div class="col-md-4 mb-2">
                <label class="form-label">Discount Type *</label>
                <select name="bank_offers[${bankOfferIndex}][type]" class="form-control">
                    <option value="fixed">Fixed</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>

            <div class="col-md-4 mb-2">
                <label class="form-label">Discount *</label>
                <input type="number"
                       name="bank_offers[${bankOfferIndex}][value]"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label">Offer Start</label>
                <input type="datetime-local"
                       name="bank_offers[${bankOfferIndex}][starts_at]"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label">Offer End</label>
                <input type="datetime-local"
                       name="bank_offers[${bankOfferIndex}][expires_at]"
                       class="form-control">
            </div>

        </div>
    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    bankOfferIndex++; // 🔥 MOST IMPORTANT
});

});
