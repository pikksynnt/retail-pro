@extends('layouts.app')

@section('content')
<style>
    /* Custom Styling untuk POS Modern */
    .pos-card { border: none; border-radius: 24px; height: calc(100vh - 120px); display: flex; flex-direction: column; background: white; overflow: hidden; }
    .cart-table { flex-grow: 1; overflow-y: auto; background: #fff; }
    .total-section { 
        background: #1e293b; 
        color: white; 
        border-radius: 24px; 
        padding: 30px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .btn-pay { 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
        border: none; 
        font-weight: 700; 
        color: white;
        transition: 0.3s; 
    }
    .btn-pay:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); color: white; }
    .form-control-dark { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white !important; }
    .form-control-dark:focus { background: rgba(255,255,255,0.15); border-color: #10b981; box-shadow: none; }
    .table thead th { position: sticky; top: 0; background: #f8fafc; z-index: 10; border-top: none; }
    
    /* Chrome, Safari, Edge, Opera - hilangkan spin wheel number */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="container-fluid px-3">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card pos-card shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">🛒 Kasir {{ Auth::user()->vendor->shop_name }}</h4>
                        <small class="text-muted">Kasir: {{ Auth::user()->name }}</small>
                    </div>
                    <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm" id="item-count">0 Items</span>
                </div>

                <div class="input-group mb-4 shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text bg-white border-0 ps-4 text-primary">🔍</span>
                    <input type="text" id="barcode-input" class="form-control form-control-lg border-0 py-3" placeholder="Scan Barcode di sini..." autofocus>
                </div>

                <div class="cart-table rounded-4 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-muted small fw-bold">
                                <th class="ps-4 py-3">PRODUK</th>
                                <th class="py-3">HARGA</th>
                                <th class="py-3 text-center" width="120">QTY</th>
                                <th class="py-3">SUBTOTAL</th>
                                <th class="py-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            </tbody>
                    </table>
                </div>
                
                <div class="mt-3 d-flex justify-content-between">
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="clearCart()">🗑️ Reset Keranjang</button>
                    <small class="text-muted">Shortcuts: <b>Enter</b> (Tambah) | <b>F2</b> (Bayar)</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="total-section shadow-lg">
                <div>
                    <p class="text-uppercase small opacity-50 mb-1 fw-bold tracking-wider">Total Pembayaran</p>
                    <h1 class="display-5 fw-bold mb-4 text-warning" id="grand-total">Rp 0</h1>
                    
                    <div class="mb-3">
                        <label class="small opacity-75 mb-1 fw-bold">SISTEM MEMBER</label>
                        <div class="input-group">
                            <input type="text" id="member-input" class="form-control form-control-dark border-0 rounded-start-3" placeholder="Kode Member...">
                            <button class="btn btn-warning fw-bold px-3 rounded-end-3" type="button" onclick="applyMember()">CEK</button>
                        </div>
                        <div id="member-info" class="mt-2"></div>
                    </div>

                    <hr class="opacity-10 my-4">

                    <div class="mb-4">
                        <label class="small opacity-75 mb-1 fw-bold text-info">TUNAI (F2)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text form-control-dark border-0">Rp</span>
                            <input type="number" id="cash-input" class="form-control form-control-dark fw-bold border-0 fs-2" placeholder="0">
                        </div>
                    </div>
                    
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 mb-4 border border-white border-opacity-10 shadow-inner">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small opacity-75">Kembalian:</span>
                            <h2 class="fw-bold mb-0 text-info" id="change-display">Rp 0</h2>
                        </div>
                    </div>
                </div>

                <button type="button" id="btn-process" class="btn btn-pay w-100 py-4 btn-lg rounded-4 shadow-lg fs-4" onclick="processTransaction()">
                    ✅ BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>
</div>

<audio id="beep" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3"></audio>

<script>
    let cart = [];
    let discountPercent = 0;
    let selectedMemberId = null;

    const barcodeInput = document.getElementById('barcode-input');
    const cartTable = document.getElementById('cart-items');
    const grandTotalDisplay = document.getElementById('grand-total');
    const cashInput = document.getElementById('cash-input');
    const changeDisplay = document.getElementById('change-display');
    const beep = document.getElementById('beep');

    // 1. Scan Barcode (Find Product)
    barcodeInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            let barcode = this.value.trim();
            if(!barcode) return;

            fetch(`/pos/find-product/${barcode}`)
                .then(response => {
                    if (!response.ok) throw new Error('Produk tidak ditemukan');
                    return response.json();
                })
                .then(res => {
                    if (res.success) {
                        beep.play();
                        addToCart(res.data);
                        this.value = '';
                    }
                })
                .catch(err => {
                    alert('Barang tidak ditemukan di Toko Anda!');
                    this.value = '';
                });
        }
    });

    // 2. Add to Cart Logic
    function addToCart(product) {
        let existing = cart.find(item => item.id === product.id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                qty: 1
            });
        }
        renderTable();
    }

    // 3. Render Table UI
    function renderTable() {
        cartTable.innerHTML = '';
        let subtotalAll = 0;

        if (cart.length === 0) {
            cartTable.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">Scan produk untuk mulai belanja</td></tr>';
        }

        cart.forEach((item, index) => {
            let subtotal = item.price * item.qty;
            subtotalAll += subtotal;

            cartTable.innerHTML += `
                <tr class="animate__animated animate__fadeIn">
                    <td class="ps-4 fw-bold text-dark font-monospace">${item.name}</td>
                    <td class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</td>
                    <td>
                        <div class="input-group input-group-sm rounded-pill overflow-hidden border">
                            <button class="btn btn-light" onclick="updateQty(${index}, ${item.qty - 1})">-</button>
                            <input type="number" class="form-control border-0 text-center fw-bold" value="${item.qty}" 
                            onchange="updateQty(${index}, this.value)">
                            <button class="btn btn-light" onclick="updateQty(${index}, ${item.qty + 1})">+</button>
                        </div>
                    </td>
                    <td class="fw-bold text-primary">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeItem(${index})">✕</button>
                    </td>
                </tr>
            `;
        });

        // Hitung Diskon Member
        let discountAmount = (subtotalAll * discountPercent) / 100;
        let finalTotal = subtotalAll - discountAmount;

        document.getElementById('item-count').innerText = `${cart.length} Items`;
        grandTotalDisplay.innerText = `Rp ${finalTotal.toLocaleString('id-ID')}`;
        calculateChange();
    }

    // 4. Update Qty & Remove
    window.updateQty = (index, val) => {
        if(val < 1) return removeItem(index);
        cart[index].qty = parseInt(val);
        renderTable();
    };

    window.removeItem = (index) => {
        if(confirm('Hapus item dari keranjang?')) {
            cart.splice(index, 1);
            renderTable();
        }
    };

    window.clearCart = () => {
        if(confirm('Kosongkan semua item?')) {
            cart = [];
            renderTable();
        }
    };

    // 5. Apply Member Logic
    function applyMember() {
        let code = document.getElementById('member-input').value.trim();
        if(!code) return alert('Masukkan kode member!');

        fetch(`/pos/find-member/${code}`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    discountPercent = res.data.discount_percent;
                    selectedMemberId = res.data.id;
                    document.getElementById('member-info').innerHTML = `
                        <div class="alert alert-success py-2 px-3 border-0 small">
                            ✅ <b>${res.data.name}</b> (Diskon ${discountPercent}%)
                        </div>`;
                    renderTable();
                } else {
                    alert("Member tidak ditemukan!");
                    resetMember();
                }
            });
    }

    function resetMember() {
        discountPercent = 0;
        selectedMemberId = null;
        document.getElementById('member-info').innerHTML = "";
        renderTable();
    }

    // 6. Real-time Kembalian
    cashInput.addEventListener('input', calculateChange);
    function calculateChange() {
        let total = parseFloat(grandTotalDisplay.innerText.replace('Rp ', '').replace(/\./g, '')) || 0;
        let cash = parseFloat(cashInput.value) || 0;
        let change = cash - total;

        if (change < 0) {
            changeDisplay.innerText = "- Rp " + Math.abs(change).toLocaleString('id-ID');
            changeDisplay.className = "fw-bold mb-0 text-danger";
        } else {
            changeDisplay.innerText = "Rp " + change.toLocaleString('id-ID');
            changeDisplay.className = "fw-bold mb-0 text-info";
        }
    }

    // 7. Store Transaction (AJAX to Controller)
    function processTransaction() {
        if (cart.length === 0) return alert("Keranjang masih kosong bro!");
        
        let total = parseFloat(grandTotalDisplay.innerText.replace('Rp ', '').replace(/\./g, ''));
        let cash = parseFloat(cashInput.value) || 0;
        
        if (cash < total) return alert("Uang pembayaran belum cukup!");

        const btn = document.getElementById('btn-process');
        btn.disabled = true;
        btn.innerText = "Processing...";

        fetch('/pos/store', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({
                items: cart,
                total_price: total,
                cash: cash,
                member_id: selectedMemberId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ " + data.message);
                // Cetak Struk (Buka Tab Baru)
                window.open(`/pos/print/${data.transaction_id}`, '_blank');
                location.reload();
            } else {
                alert("❌ " + data.message);
                btn.disabled = false;
                btn.innerText = "✅ SELESAIKAN TRANSAKSI";
            }
        })
        .catch(err => {
            alert("Terjadi kesalahan sistem!");
            btn.disabled = false;
        });
    }

    // Hotkeys
    document.addEventListener('keydown', (e) => {
        if (e.key === 'F2') {
            e.preventDefault();
            cashInput.focus();
        }
        if (e.key === 'Escape') {
            barcodeInput.focus();
        }
    });

    renderTable();
</script>
@endsection