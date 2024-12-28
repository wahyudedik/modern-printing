<!DOCTYPE html>
<html>

<head>
    <title>{{ $produk['nama_produk'] }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            margin: 0;
            padding: 16px;
            background-color: #fafafa;
            color: #333;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .header {
            margin-bottom: 32px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #1a1a1a;
            font-weight: 600;
        }

        .image-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 32px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .image-container img:first-child {
            grid-column: span 3;
            height: 400px;
        }

        .product-image {
            width: 40%;
            height: 20px;
            object-fit: contain;
            border-radius: 8px;
            background: white;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            vertical-align: middle;
            margin: 0 10px;
        }

        .product-info {
            background: #f8f9fa;
            padding: 24px;
            border-radius: 8px;
        }

        .info-item {
            margin-bottom: 24px;
        }

        .info-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a1a1a;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            line-height: 1.6;
            color: #4a4a4a;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            background: #e9ecef;
            color: #495057;
            border-radius: 6px;
            font-size: 0.85em;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .badge:hover {
            background: #dee2e6;
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            color: #6c757d;
            font-size: 0.85em;
            padding-top: 16px;
            border-top: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .container {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .product-info {
                padding: 20px;
            }

            .image-container {
                grid-template-columns: 1fr;
                padding: 10px;
            }

            .image-container img:first-child {
                grid-column: span 1;
                height: 300px;
            }

            .product-image {
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $produk['nama_produk'] }}</h1>
        </div>

        @if (count($produk['gambar']) > 0)
            <div class="image-container">
                @foreach ($produk['gambar'] as $gambar)
                    <img src="{{ public_path('storage/' . $gambar) }}" class="product-image"
                        alt="{{ $produk['nama_produk'] }}">
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
