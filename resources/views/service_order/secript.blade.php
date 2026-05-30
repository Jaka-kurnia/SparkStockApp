    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Master Services (Injected from Controller)
            const masterServices = @json($services);
            
            // Cart Elements
            const btnAddService = document.getElementById('btn_add_service');
            const serviceCartBody = document.getElementById('service_cart_body');
            const totalServiceInput = document.getElementById('total_service');
            const totalServiceDisplay = document.getElementById('total_service_display');
            
            // Summary Elements
            const inputTotalPart = document.getElementById('total_part');
            const inputDiscount = document.getElementById('discount');
            const inputTax = document.getElementById('tax');
            const displayGrandTotal = document.getElementById('grand_total');

            let serviceRowIndex = 0;

            function renderServiceOptions() {
                let options = '<option value="" selected disabled>-- Pilih Jasa --</option>';
                masterServices.forEach(srv => {
                    options += `<option value="${srv.id}" data-price="${srv.price}">${srv.complaint_name} (Rp ${parseFloat(srv.price).toLocaleString('id-ID')})</option>`;
                });
                return options;
            }

            btnAddService.addEventListener('click', function() {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <select name="services[${serviceRowIndex}][service_id]" class="form-select service-select" required>
                            ${renderServiceOptions()}
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="services[${serviceRowIndex}][price]" class="form-control service-price" required min="0" value="0">
                        </div>
                    </td>
                    <td>
                        <input type="number" name="services[${serviceRowIndex}][qty]" class="form-control service-qty" required min="1" value="1">
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control service-subtotal bg-light" readonly value="0">
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-service"><i class="ti ti-trash"></i></button>
                    </td>
                `;
                serviceCartBody.appendChild(tr);
                serviceRowIndex++;
                attachRowEvents(tr);
            });

            function attachRowEvents(tr) {
                const select = tr.querySelector('.service-select');
                const priceInput = tr.querySelector('.service-price');
                const qtyInput = tr.querySelector('.service-qty');
                const subtotalInput = tr.querySelector('.service-subtotal');
                const btnRemove = tr.querySelector('.btn-remove-service');

                select.addEventListener('change', function() {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;
                    priceInput.value = price;
                    calculateRowSubtotal();
                });

                priceInput.addEventListener('input', calculateRowSubtotal);
                qtyInput.addEventListener('input', calculateRowSubtotal);

                function calculateRowSubtotal() {
                    const price = parseFloat(priceInput.value) || 0;
                    const qty = parseInt(qtyInput.value) || 0;
                    subtotalInput.value = price * qty;
                    calculateTotalService();
                }

                btnRemove.addEventListener('click', function() {
                    tr.remove();
                    calculateTotalService();
                });
            }

            function calculateTotalService() {
                let total = 0;
                document.querySelectorAll('.service-subtotal').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                totalServiceInput.value = total;
                totalServiceDisplay.value = total.toLocaleString('id-ID');
                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                const service = parseFloat(totalServiceInput.value) || 0;
                const part = parseFloat(inputTotalPart.value) || 0;
                const discount = parseFloat(inputDiscount.value) || 0;
                const tax = parseFloat(inputTax.value) || 0;
                
                const grand = (service + part) - discount + tax;
                displayGrandTotal.value = grand.toLocaleString('id-ID');
            }

            [inputTotalPart, inputDiscount, inputTax].forEach(el => {
                if (el) el.addEventListener('input', calculateGrandTotal);
            });

           
            calculateGrandTotal();
        });
    </script>