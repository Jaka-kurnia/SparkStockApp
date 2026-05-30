    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Master Data (Injected from Controller)
            const masterServices = @json($services);
            const masterSpareparts = @json($spareparts);
            
            // Cart Elements - Services
            const btnAddService = document.getElementById('btn_add_service');
            const serviceCartBody = document.getElementById('service_cart_body');
            const totalServiceInput = document.getElementById('total_service');
            const totalServiceDisplay = document.getElementById('total_service_display');
            
            // Cart Elements - Spareparts
            const btnAddSparepart = document.getElementById('btn_add_sparepart');
            const sparepartCartBody = document.getElementById('sparepart_cart_body');
            const totalPartInput = document.getElementById('total_part');
            const totalPartDisplay = document.getElementById('total_part_display');

            // Summary Elements
            const inputDiscount = document.getElementById('discount');
            const inputTax = document.getElementById('tax');
            const displayGrandTotal = document.getElementById('grand_total');

            let serviceRowIndex = 0;
            let sparepartRowIndex = 0;

            // --- SERVICES LOGIC ---

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
                attachServiceRowEvents(tr);
            });

            function attachServiceRowEvents(tr) {
                const select = tr.querySelector('.service-select');
                const priceInput = tr.querySelector('.service-price');
                const qtyInput = tr.querySelector('.service-qty');
                const subtotalInput = tr.querySelector('.service-subtotal');
                const btnRemove = tr.querySelector('.btn-remove-service');

                select.addEventListener('change', function() {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;
                    priceInput.value = price;
                    calculateServiceSubtotal();
                });

                priceInput.addEventListener('input', calculateServiceSubtotal);
                qtyInput.addEventListener('input', calculateServiceSubtotal);

                function calculateServiceSubtotal() {
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

            // --- SPAREPARTS LOGIC ---

            function renderSparepartOptions() {
                let options = '<option value="" selected disabled>-- Pilih Suku Cadang --</option>';
                masterSpareparts.forEach(part => {
                    options += `<option value="${part.id}" data-price="${part.price}" data-stock="${part.stock}">${part.name} (Stok: ${part.stock} | Rp ${parseFloat(part.price).toLocaleString('id-ID')})</option>`;
                });
                return options;
            }

            btnAddSparepart.addEventListener('click', function() {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <select name="spareparts[${sparepartRowIndex}][sparepart_id]" class="form-select sparepart-select" required>
                            ${renderSparepartOptions()}
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="spareparts[${sparepartRowIndex}][price]" class="form-control sparepart-price" required min="0" value="0">
                        </div>
                    </td>
                    <td>
                        <input type="number" name="spareparts[${sparepartRowIndex}][qty]" class="form-control sparepart-qty" required min="1" value="1">
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control sparepart-subtotal bg-light" readonly value="0">
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-sparepart"><i class="ti ti-trash"></i></button>
                    </td>
                `;
                sparepartCartBody.appendChild(tr);
                sparepartRowIndex++;
                attachSparepartRowEvents(tr);
            });

            function attachSparepartRowEvents(tr) {
                const select = tr.querySelector('.sparepart-select');
                const priceInput = tr.querySelector('.sparepart-price');
                const qtyInput = tr.querySelector('.sparepart-qty');
                const subtotalInput = tr.querySelector('.sparepart-subtotal');
                const btnRemove = tr.querySelector('.btn-remove-sparepart');

                select.addEventListener('change', function() {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;
                    const stock = parseInt(selectedOption.getAttribute('data-stock') || 0);
                    
                    priceInput.value = price;
                    qtyInput.max = stock; // set max quantity based on stock
                    
                    if (parseInt(qtyInput.value) > stock) {
                        qtyInput.value = stock;
                    }
                    
                    calculateSparepartSubtotal();
                });

                priceInput.addEventListener('input', calculateSparepartSubtotal);
                qtyInput.addEventListener('input', calculateSparepartSubtotal);

                function calculateSparepartSubtotal() {
                    const price = parseFloat(priceInput.value) || 0;
                    const qty = parseInt(qtyInput.value) || 0;
                    subtotalInput.value = price * qty;
                    calculateTotalPart();
                }

                btnRemove.addEventListener('click', function() {
                    tr.remove();
                    calculateTotalPart();
                });
            }

            function calculateTotalPart() {
                let total = 0;
                document.querySelectorAll('.sparepart-subtotal').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                totalPartInput.value = total;
                totalPartDisplay.value = total.toLocaleString('id-ID');
                calculateGrandTotal();
            }


            // --- SUMMARY LOGIC ---

            function calculateGrandTotal() {
                const service = parseFloat(totalServiceInput.value) || 0;
                const part = parseFloat(totalPartInput.value) || 0;
                const discount = parseFloat(inputDiscount.value) || 0;
                const tax = parseFloat(inputTax.value) || 0;
                
                const grand = (service + part) - discount + tax;
                displayGrandTotal.value = grand.toLocaleString('id-ID');
            }

            [inputDiscount, inputTax].forEach(el => {
                if (el) el.addEventListener('input', calculateGrandTotal);
            });

            calculateGrandTotal();
        });
    </script>