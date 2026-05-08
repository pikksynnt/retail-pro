<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja - #{{ $transaction->invoice_number }}</title>
    <style>
        /* Pengaturan Ukuran Kertas Thermal */
        @page { 
            size: 80mm auto; /* Standar thermal 80mm */
            margin: 0; 
        }
        
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 72mm; /* Area cetak aman */
            font-size: 12px; 
            color: #000;
            margin: 0 auto;
            padding: 10px;
            line-height: 1.3;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        
        .header h3 { margin: 0; letter-spacing: 1px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        
        .info-table { font-size: 11px; margin-bottom: 5px; width: 100%; }
        .item-table { width: 100%; border-collapse: collapse; }
        .item-name { text-transform: uppercase; font-size: 12px; display: block; margin-top: 4px; }
        .item-detail { font-size: 11px; }
        
        .total-table { width: 100%; margin-top: 5px; }
        .total-table td { padding: 1px 0; }
        
        .footer { margin-top: 15px; font-size: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        
        /* Tombol Navigasi Saat Preview di Browser */
        .no-print { 
            background: #f8fafc; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        @media print { 
            .no-print { display: none; } 
            body { width: 100%; padding: 5px; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print text-center">
        <p style="margin-top:0; color: #64748b;">Pratinjau Struk Belanja</p>
        <button onclick="window.print()" style="padding: 8px 16px; border-radius: 6px; background: #6366f1; color: white; border: none; cursor: pointer; font-weight: bold; box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);">PRINT SEKARANG</button>
        <button onclick="window.location.href='{{ route('pos.index') }}'" style="padding: 8px 16px; border-radius: 6px; background: #fff; color: #334155; border: 1px solid #cbd5e1; cursor: pointer; margin-left: 8px;">KEMBALI KE KASIR</button>
    </div>

    @php 
        // Mengambil data toko dari vendor yang melakukan transaksi
        $shop = $transaction->vendor;
    @endphp

    <div class="header text-center">
        <h3 class="fw-bold">{{ $shop->shop_name ?? 'RETAIL PRO' }}</h3>
        <p>{{ $shop->address ?? 'Gedung Software Engineering' }}</p>
        <p>Telp: {{ $shop->phone ?? '-' }}</p>
    </div>
    
    <div class="line"></div>
    
    <table class="info-table">
        <tr>
            <td>TGL : {{ $transaction->created_at->format('d/m/y H:i') }}</td>
            <td class="text-right">KASIR: {{ strtoupper($transaction->user->name) }}</td>
        </tr>
        <tr>
            <td>INV : #{{ $transaction->invoice_number }}</td>
            @if($transaction->member)
            <td class="text-right">MBR  : {{ strtoupper($transaction->member->name) }}</td>
            @endif
        </tr>
    </table>
    
    <div class="line"></div>

    <table class="item-table">
        @foreach($transaction->details as $detail)
        <tr>
            <td colspan="2"><span class="item-name">{{ $detail->product->name }}</span></td>
        </tr>
        <tr class="item-detail">
            <td style="width: 60%;">{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
            <td class="text-right" style="width: 40%;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table class="total-table">
        <tr>
            <td>TOTAL JUAL</td>
            <td class="text-right fw-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>TUNAI</td>
            <td class="text-right">Rp {{ number_format($transaction->cash, 0, ',', '.') }}</td>
        </tr>
        <tr class="fw-bold">
            <td>KEMBALI</td>
            <td class="text-right">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer text-center">
        <p class="fw-bold" style="margin-bottom: 5px;">*** TERIMA KASIH ***</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
        <p style="margin-top: 8px; font-style: italic; font-size: 9px;">Powered by Retail Pro v1.0</p>
    </div>

    <script>
        // Opsional: Tutup tab otomatis setelah print/cancel (hanya jika dibuka via window.open)
        window.onafterprint = function() {
            // window.close(); 
        };
    </script>
</body>
</html>