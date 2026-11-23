<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RAB Penelitian</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── HEADER ─────────────────────────────────────── */
        .header {
            text-align: center;
            border-bottom: 3px solid #1a3c6e;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header .inst-name {
            font-size: 15pt;
            font-weight: bold;
            color: #1a3c6e;
            letter-spacing: 1px;
        }
        .header .inst-sub {
            font-size: 9pt;
            color: #444;
            margin-top: 2px;
        }
        .header .downloaded {
            font-size: 8pt;
            color: #666;
            font-style: italic;
            margin-top: 3px;
        }

        /* ── JUDUL RAB ───────────────────────────────────── */
        .rab-title-box {
            background-color: #1a3c6e;
            color: #fff;
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            padding: 7px 0;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        /* ── INFO PENELITIAN ─────────────────────────────── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .info-table td {
            padding: 5px 8px;
            border: 1px solid #c8d4e8;
            font-size: 9pt;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: bold;
            background-color: #eef2f9;
            width: 22%;
            color: #1a3c6e;
        }

        /* ── TABEL RAB ───────────────────────────────────── */
        .rab-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .rab-table th {
            background-color: #1a3c6e;
            color: #fff;
            padding: 6px 8px;
            font-size: 8.5pt;
            text-align: center;
            border: 1px solid #1a3c6e;
        }
        .rab-table td {
            padding: 5px 8px;
            border: 1px solid #c8d4e8;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .rab-table .col-no     { width: 4%;  text-align: center; }
        .rab-table .col-item   { width: 20%; }
        .rab-table .col-spec   { width: 30%; }
        .rab-table .col-vol    { width: 6%;  text-align: center; }
        .rab-table .col-price  { width: 16%; text-align: right; }
        .rab-table .col-total  { width: 16%; text-align: right; font-weight: bold; }

        /* ── BARIS KATEGORI ──────────────────────────────── */
        .row-category {
            background-color: #e8edf5;
        }
        .row-category td {
            font-weight: bold;
            color: #1a3c6e;
            font-size: 8.5pt;
            padding: 5px 8px;
            border: 1px solid #c8d4e8;
        }
        .category-limit {
            font-size: 7.5pt;
            font-weight: normal;
            color: #666;
            font-style: italic;
        }

        /* ── BARIS SUBTOTAL ──────────────────────────────── */
        .row-subtotal td {
            background-color: #dce6f5;
            font-weight: bold;
            color: #1a3c6e;
            font-size: 8.5pt;
            padding: 5px 8px;
            border: 1px solid #b0c0d8;
        }
        .row-subtotal .subtotal-label { text-align: right; }
        .row-subtotal .subtotal-value { text-align: right; }

        /* ── BARIS ZEBRA ─────────────────────────────────── */
        .row-even { background-color: #f7f9fc; }
        .row-odd  { background-color: #ffffff; }

        /* ── TOTAL AKHIR ─────────────────────────────────── */
        .row-grand-total td {
            background-color: #1a3c6e;
            color: #fff;
            font-weight: bold;
            font-size: 9.5pt;
            padding: 7px 8px;
            border: 1px solid #1a3c6e;
        }
        .row-grand-total .total-label { text-align: right; }
        .row-grand-total .total-value { text-align: right; }

        /* ── FOOTER ──────────────────────────────────────── */
        .footer {
            margin-top: 18px;
            font-size: 7.5pt;
            color: #888;
            text-align: right;
            border-top: 1px solid #c8d4e8;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="inst-name">INSTITUT TEKNOLOGI DEL</div>
        <div class="inst-sub">Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)</div>
        <div class="downloaded">Diunduh pada: {{ $generatedAt }}</div>
    </div>

    {{-- JUDUL --}}
    <div class="rab-title-box">RENCANA ANGGARAN BIAYA (RAB) PENELITIAN</div>

    {{-- INFO PENELITIAN --}}
    @if(!empty($research))
    <table class="info-table">
        <tr>
            <td>Judul Penelitian</td>
            <td>{{ $research['title'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Ketua Peneliti</td>
            <td>{{ $research['leader'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Skema Penelitian</td>
            <td>{{ $research['scheme'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>{{ $research['period'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Fakultas</td>
            <td>{{ $research['faculty'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>{{ $research['study_program'] ?? '-' }}</td>
        </tr>
    </table>
    @endif

    {{-- TABEL RAB --}}
    <table class="rab-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-item">Komponen / Item</th>
                <th class="col-spec">Spesifikasi / Keterangan</th>
                <th class="col-vol">Vol</th>
                <th class="col-price">Harga Satuan (Rp)</th>
                <th class="col-total">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $rowNo = 1; @endphp

            @foreach($groupedItems as $group)

                {{-- Baris Kategori --}}
                <tr class="row-category">
                    <td colspan="5">
                        {{ strtoupper($group['category']['name']) }}
                        @if(!empty($group['category']['budget_limit_min']) || !empty($group['category']['budget_limit_max']))
                            <span class="category-limit">
                                &nbsp;— Limit:
                                Rp {{ number_format($group['category']['budget_limit_min'] ?? 0, 0, ',', '.') }}
                                s/d
                                Rp {{ number_format($group['category']['budget_limit_max'] ?? 0, 0, ',', '.') }}
                            </span>
                        @endif
                    </td>
                    <td style="text-align:right; font-style:italic; font-size:7.5pt; color:#555;">
                        @if(!empty($group['category']['budget_limit_min']) || !empty($group['category']['budget_limit_max']))
                            Rp {{ number_format($group['category']['budget_limit_min'] ?? 0, 0, ',', '.') }}
                            s/d Rp {{ number_format($group['category']['budget_limit_max'] ?? 0, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>

                {{-- Baris Items --}}
                @foreach($group['items'] as $index => $item)
                <tr class="{{ $index % 2 === 0 ? 'row-even' : 'row-odd' }}">
                    <td class="col-no">{{ $rowNo++ }}</td>
                    <td class="col-item">{{ $item['item_name'] ?? '-' }}</td>
                    <td class="col-spec">{{ $item['component'] ?? '-' }}</td>
                    <td class="col-vol">{{ $item['volume'] ?? 0 }}</td>
                    <td class="col-price">
                        Rp {{ number_format((float)($item['unit_price'] ?? 0), 0, ',', '.') }}
                    </td>
                    <td class="col-total">
                        <strong>Rp {{ number_format((float)($item['total'] ?? 0), 0, ',', '.') }}</strong>
                    </td>
                </tr>
                @endforeach

                {{-- Baris Subtotal --}}
                <tr class="row-subtotal">
                    <td colspan="5" class="subtotal-label">
                        Subtotal {{ strtoupper($group['category']['name']) }}
                    </td>
                    <td class="subtotal-value">
                        Rp {{ number_format($group['subTotal'], 0, ',', '.') }}
                    </td>
                </tr>

                {{-- Spacer antar kategori --}}
                <tr><td colspan="6" style="padding:3px; border:none;"></td></tr>

            @endforeach

            {{-- GRAND TOTAL --}}
            <tr class="row-grand-total">
                <td colspan="5" class="total-label">TOTAL ANGGARAN KESELURUHAN</td>
                <td class="total-value">
                    Rp {{ number_format((float)$totalRAB, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem LPPM Institut Teknologi Del &mdash; {{ $generatedAt }}
    </div>

</body>
</html>



