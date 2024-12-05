<!DOCTYPE html>
<html>

<head>
    <title>{{ $produk['nama_produk'] }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #212529;
        }

        .container {
            max-width: 1000px;
            margin: 32px auto;
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 24px;
            margin-bottom: 32px;
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            color: #1a1a1a;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .product-image {
            width: 25%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product-info {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .info-grid {
            display: grid;
            gap: 24px;
        }

        .info-item {
            padding: 16px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            color: #6c757d;
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #212529;
            font-size: 16px;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            background: #f8f9fa;
            color: #495057;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
            border: 1px solid #dee2e6;
            transition: all 0.2s;
        }

        .badge:hover {
            background: #e9ecef;
        }

        .price-highlight {
            color: #dc3545;
            font-size: 24px;
            font-weight: 700;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            padding-top: 24px;
            border-top: 2px solid #e9ecef;
        }

        @media (max-width: 768px) {
            .image-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .image-grid {
                grid-template-columns: 1fr;
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
            <div class="image-grid">
                @foreach ($produk['gambar'] as $gambar)
                    <img src="{{ public_path('storage/' . $gambar) }}" class="product-image">
                @endforeach
            </div>
        @endif

        <div class="product-info">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Kategori</div>
                    <div class="info-value">
                        <span class="badge">{{ $produk['kategori'] }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Bahan</div>
                    <div class="info-value">
                        @if (isset($produk['bahan']) && !empty($produk['bahan']))
                            @foreach ($produk['bahan'] as $bahan)
                                <span class="badge">{{ $bahan['nama_bahan'] }}</span>
                            @endforeach
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Alat</div>
                    <div class="info-value">
                        @if (isset($produk['alat']) && !empty($produk['alat']))
                            @foreach ($produk['alat'] as $alat)
                                <span class="badge">{{ $alat['nama_alat'] }}</span>
                            @endforeach
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Harga Normal</div>
                    <div class="info-value">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Diskon</div>
                    <div class="info-value">Rp {{ number_format($produk['diskon'], 0, ',', '.') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Harga Akhir</div>
                    <div class="info-value price-highlight">Rp {{ number_format($produk['total_harga'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Stok</div>
                    <div class="info-value">{{ $produk['stok'] }} unit</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value">{!! $produk['deskripsi'] !!}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on {{ now()->format('d F Y') }}</p>
        </div>
    </div>
</body>

</html>
