    {{-- JavaScript Interaktif --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const typeSelect = document.getElementById('transaction_type');
            const supplierWrapper = document.getElementById('supplier_field_wrapper');
            const cartBody = document.getElementById('cart_body');
            const emptyCartRow = document.getElementById('empty_cart_row');
            const grandTotalText = document.getElementById('grand_total_text');
            const form = document.getElementById('transaction_form');

            // Input form tambah barang
            const partSelect = document.getElementById('part_select');
            const partQty = document.getElementById('part_qty');
            const partPrice = document.getElementById('part_price');
            const btnAddCart = document.getElementById('btn_add_cart');

            let cartItems = [];
            let itemCounter = 0;

            // Load old items jika ada error validasi atau stok kurang
            @if(old('items'))
                @foreach(old('items') as $oldItem)
                    @php
                        $sp = \App\Models\Sparepart::find($oldItem['sparepart_id']);
                    @endphp
                    @if($sp)
                        cartItems.push({
                            id: itemCounter++,
                            sparepart_id: "{{ $oldItem['sparepart_id'] }}",
                            name: "{{ $sp->name }}",
                            qty: {{ (int)$oldItem['qty'] }},
                            price: {{ (float)$oldItem['price_per_unit'] }}
                        });
                    @endif
                @endforeach
                renderCart();
            @endif

            // A. Logika Muncul/Sembunyi Lapangan Supplier
            function toggleSupplierField() {
                if (typeSelect.value === 'in') {
                    supplierWrapper.style.display = 'block';
                } else {
                    supplierWrapper.style.display = 'none';
                    supplierWrapper.querySelector('select').value = '';
                }
            }
            typeSelect.addEventListener('change', toggleSupplierField);
            toggleSupplierField();

            // B. Logika Tambah Barang ke Keranjang
            btnAddCart.addEventListener('click', function() {
                const sparepartId = partSelect.value;
                const sparepartName = partSelect.options[partSelect.selectedIndex]?.dataset.name;
                const qty = parseInt(partQty.value);
                const price = parseFloat(partPrice.value) || 0;

                if (!sparepartId) {
                    alert('Silakan pilih sparepart terlebih dahulu.');
                    return;
                }
                if (!qty || qty < 1) {
                    alert('Jumlah barang harus diisi dan minimal 1.');
                    return;
                }

                // Cek apakah barang sudah ada di keranjang, jika ada tambahkan qty
                const existingItemIndex = cartItems.findIndex(item => item.sparepart_id === sparepartId);
                
                if (existingItemIndex > -1) {
                    cartItems[existingItemIndex].qty += qty;
                    // Update harga jika user input harga baru
                    if (price > 0) cartItems[existingItemIndex].price = price;
                } else {
                    cartItems.push({
                        id: itemCounter++, // internal id
                        sparepart_id: sparepartId,
                        name: sparepartName,
                        qty: qty,
                        price: price
                    });
                }

                // Reset input form
                partSelect.value = '';
                partQty.value = '';
                partPrice.value = '';

                renderCart();
            });

            // C. Fungsi Hapus Barang dari Keranjang
            window.removeCartItem = function(id) {
                cartItems = cartItems.filter(item => item.id !== id);
                renderCart();
            }

            // D. Render UI Keranjang
            function renderCart() {
                // Clear the body
                const existingRows = cartBody.querySelectorAll('.cart-item-row');
                existingRows.forEach(row => row.remove());
                const existingInputs = form.querySelectorAll('.cart-hidden-input');
                existingInputs.forEach(input => input.remove());

                if (cartItems.length === 0) {
                    emptyCartRow.style.display = 'table-row';
                    grandTotalText.innerText = 'Rp 0';
                    return;
                }

                emptyCartRow.style.display = 'none';
                let grandTotal = 0;

                cartItems.forEach((item, index) => {
                    const total = item.qty * item.price;
                    grandTotal += total;

                    // Buat element TR
                    const tr = document.createElement('tr');
                    tr.className = 'cart-item-row';
                    tr.innerHTML = `
                        <td>${item.name}</td>
                        <td class="text-center">${item.qty}</td>
                        <td class="text-end">Rp ${item.price.toLocaleString('id-ID')}</td>
                        <td class="text-end">Rp ${total.toLocaleString('id-ID')}</td>
                        <td>
                            <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="removeCartItem(${item.id})">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    `;
                    cartBody.appendChild(tr);

                    // Buat Hidden Inputs untuk disubmit ke controller
                    form.appendChild(createHiddenInput(`items[${index}][sparepart_id]`, item.sparepart_id));
                    form.appendChild(createHiddenInput(`items[${index}][qty]`, item.qty));
                    form.appendChild(createHiddenInput(`items[${index}][price_per_unit]`, item.price));
                });

                grandTotalText.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            }

            function createHiddenInput(name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                input.className = 'cart-hidden-input';
                return input;
            }

            // E. Validasi sebelum submit
            form.addEventListener('submit', function(e) {
                if (cartItems.length === 0) {
                    e.preventDefault();
                    alert('Keranjang barang tidak boleh kosong. Silakan tambahkan barang terlebih dahulu.');
                }
            });
        });
    </script>