<!DOCTYPE html>
<html>

<head>
    <title>{{ $produk['nama_produk'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
        }

        .header {
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .product-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .info-item {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .info-value {
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            background: #eee;
            border-radius: 4px;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #888;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .product-image {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $produk['nama_produk'] }}</h1><br>
        </div>

        @if (count($produk['gambar']) > 0)
            <div class="image-container">
                @foreach ($produk['gambar'] as $gambar)
                    <img src="{{ public_path('storage/' . $gambar) }}" class="product-image">
                @endforeach
            </div>
        @endif

        <div class="product-info">
            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-value">
                    <span class="badge">{{ $produk->kategori->nama_kategori }}</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Deskripsi</div>
                <div class="info-value">{!! $produk['deskripsi'] !!}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Vendor</div>
                <div class="info-value">{{ $produk['vendor']['name'] }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('d F Y') }}</p>
        </div>
    </div>
</body>

</html>
