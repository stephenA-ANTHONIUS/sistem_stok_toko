<?php

namespace App\Http\Controllers;

use App\Models\ShippingLabel;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Smalot\PdfParser\Parser;

class ShippingLabelController extends Controller
{
    public function index()
    {
        $shippingLabels = ShippingLabel::latest()->paginate(15);
        return view('shipping-labels.index', compact('shippingLabels'));
    }

    public function create()
    {
        return view('shipping-labels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file    = $request->file('pdf_file');
        $rawText = '';
        $items   = [];

        try {
            $parser  = new Parser();
            $pdf     = $parser->parseFile($file->getRealPath());
            $rawText = $pdf->getText();
            $items   = $this->parseItems($rawText);
        } catch (\Throwable $e) {
            $rawText = 'Gagal membaca PDF: ' . $e->getMessage();
        }

        $path = $file->store('resi', 'public');
        Session::put('shipping_label_preview', [
            'image_path' => $path,
            'raw_text'   => $rawText,
            'items'      => $items,
        ]);

        return redirect()->route('shipping-labels.preview');
    }

    public function showPreview()
    {
        $data = Session::get('shipping_label_preview');

        if (! $data) {
            return redirect()->route('shipping-labels.create')
                ->with('error', 'Sesi habis atau tidak ada data untuk ditampilkan. Silakan upload ulang.');
        }

        $products = Product::orderBy('nama_produk', 'asc')->get();

        $mappedItems = collect($data['items'])->map(function ($item) use ($products) {
            $scanName = trim($item['produk'] ?? '');
            $matchedProduct = $this->findBestMatchingProduct($scanName, $products);

            return [
                'produk'       => $matchedProduct ? $matchedProduct->nama_produk : $scanName,
                'qty'          => $item['qty'] ?? 1,
                'matched'      => (bool) $matchedProduct,
                'original'     => $scanName,
                'harga_online' => $matchedProduct ? ($matchedProduct->harga_online ?? $matchedProduct->harga ?? 0) : 0,
                'stock'        => $matchedProduct ? (int) $matchedProduct->jumlah_stok : 0,
            ];
        })->toArray();

        return view('shipping-labels.preview', compact('data', 'mappedItems', 'products'));
    }

    public function confirmStore(Request $request)
    {
        $items = [];
        $errors = [];

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $produk = trim($item['produk'] ?? '');
                $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

                if (empty($produk) || $qty < 1) {
                    continue;
                }

                $product = Product::where('nama_produk', $produk)->first();

                if (! $product) {
                    $errors[] = "Produk '{$produk}' belum dipilih atau tidak ditemukan. Silakan pilih produk yang sesuai.";
                    continue;
                }

                if ($qty > $product->jumlah_stok) {
                    $errors[] = "Stok tidak cukup untuk produk '{$product->nama_produk}'. Stok tersedia {$product->jumlah_stok}, permintaan {$qty}.";
                }

                $items[] = [
                    'produk' => $produk,
                    'qty'    => $qty,
                ];
            }
        }

        if (! empty($errors)) {
            return redirect()->route('shipping-labels.preview')
                ->with('error', implode(' ', $errors));
        }

        ShippingLabel::create([
            'image_path' => $request->input('image_path'),
            'raw_text'   => $request->input('raw_text'),
            'items'      => $items,
        ]);

        Session::forget('shipping_label_preview');

        return redirect()->route('shipping-labels.index')
            ->with('success', 'Resi berhasil disimpan dan stok telah diperbarui.');
    }

    /**
     * FUNGSI EDIT YANG SUDAH DIPERBAIKI
     */
    public function edit(ShippingLabel $shippingLabel)
    {
        $products = Product::orderBy('nama_produk', 'asc')->get();

        // PERBAIKAN: Langsung ambil data produk asli dari DB resi, tidak perlu di-fuzzy match lagi!
        $mappedItems = collect($shippingLabel->items ?? [])->map(function ($item) {
            return [
                'produk' => trim($item['produk'] ?? ''),
                'qty'    => isset($item['qty']) ? (int) $item['qty'] : 1,
            ];
        })->toArray();

        $shippingLabel->items = $mappedItems;

        return view('shipping-labels.edit', compact('shippingLabel', 'products'));
    }

    public function update(Request $request, ShippingLabel $shippingLabel)
    {
        $items = [];

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if (! empty($item['produk']) && isset($item['qty']) && $item['qty'] > 0) {
                    $items[] = [
                        'produk' => $item['produk'],
                        'qty'    => (int) $item['qty'],
                    ];
                }
            }
        }

        $shippingLabel->update(['items' => $items]);

        return redirect()->route('shipping-labels.index')
            ->with('success', 'Resi berhasil diperbarui.');
    }

    public function destroy(ShippingLabel $shippingLabel)
    {
        $shippingLabel->delete();
        return back()->with('success', 'Resi berhasil dihapus.');
    }

    private function findBestMatchingProduct(string $scanName, $products)
    {
        $normalizedScan = ShippingLabel::normalizeProductName($scanName);
        $scanLower = mb_strtolower($normalizedScan);
        $scanWords = array_filter(preg_split('/\s+/', $scanLower), fn($word) => $word !== '');

        $bestProduct = null;
        $bestScore = -1;

        foreach ($products as $product) {
            $dbName = ShippingLabel::normalizeProductName(trim($product->nama_produk));
            $dbLower = mb_strtolower($dbName);

            if ($dbLower === $scanLower) {
                return $product;
            }

            $dbWords = array_filter(preg_split('/\s+/', $dbLower), fn($word) => $word !== '');
            $commonWords = count(array_intersect($scanWords, $dbWords));

            if ($commonWords === 0) {
                continue;
            }

            $score = $commonWords * 100 + mb_strlen($dbLower);

            if (stripos($scanLower, $dbLower) !== false) {
                $score += 250;
            }

            if (stripos($dbLower, $scanLower) !== false) {
                $score += 150;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestProduct = $product;
            }
        }

        return $bestProduct;
    }
    
    private function parseItems(string $text): array
    {
        $items = [];
        $text  = preg_replace('/\r\n|\r/', "\n", $text);
        $lines = array_values(array_map('trim', explode("\n", $text)));

        $sections = [];
        foreach ($lines as $index => $line) {
            $cleanLine = trim($line, '",\t ');
            if (stripos($cleanLine, 'nama produk') !== false) {
                $sections[] = $index + 1;
            }
        }

        if (empty($sections)) {
            return [];
        }

        foreach ($sections as $start) {
            $currentItem = null;
            for ($i = $start; $i < count($lines); $i++) {
                $line = trim($lines[$i], '",\t ');
                if ($line === '') {
                    continue;
                }

                if (stripos($line, 'Pesan:') !== false) {
                    if ($currentItem) {
                        $items[] = $currentItem;
                        $currentItem = null;
                    }
                    break;
                }

                if (preg_match('/^\s*(\d+)(.*)$/', $line, $match)) {
                    $numberPart = $match[1];
                    $rest       = trim($match[2]);

                    if ($currentItem && $rest !== '' && !preg_match('/^\d+$/', $rest)) {
                        $items[] = $currentItem;
                        $currentItem = null;
                    }

                    if ($rest === '' || preg_match('/^\d+$/', $rest)) {
                        if ($currentItem && $currentItem['qty'] === null) {
                            $currentItem['qty'] = (int) ($rest !== '' ? $rest : $numberPart);
                        }
                        continue;
                    }

                    if ($currentItem) {
                        $items[] = $currentItem;
                    }

                    $productName = preg_replace('/\s+/', ' ', $rest);
                    $qty = null;
                    $peek = $lines[$i + 1] ?? '';
                    $peek = trim($peek, '",\t ');
                    $isContinuationLine = $peek !== ''
                        && ! preg_match('/^\d+/', $peek)
                        && stripos($peek, 'Pesan:') === false;

                    if (preg_match('/^(.*\D)\s*(\d+)$/', $productName, $qtyMatch)
                        && ! $isContinuationLine
                    ) {
                        $productName = trim($qtyMatch[1]);
                        $qty = (int) $qtyMatch[2];
                    }

                    $currentItem = [
                        'produk' => $productName,
                        'qty'    => $qty,
                    ];
                    continue;
                }

                if ($currentItem) {
                    $currentItem['produk'] .= ' ' . preg_replace('/\s+/', ' ', $line);
                }
            }

            if ($currentItem) {
                $items[] = $currentItem;
            }
        }

        $normalized = [];
        foreach ($items as $item) {
            $productName = ShippingLabel::normalizeProductName($item['produk'] ?? '');
            $qty = isset($item['qty']) ? (int) $item['qty'] : null;

            if ($productName === '' || $qty === null || $qty < 1) {
                continue;
            }

            $upperName = strtoupper($productName);
            if (
                str_contains($upperName, 'BERAT') ||
                str_contains($upperName, 'BATAS KIRIM') ||
                str_contains($upperName, 'KOTA') ||
                str_contains($upperName, 'SKU VARIASI') ||
                str_contains($upperName, 'PESAN:')
            ) {
                continue;
            }

            $key = mb_strtoupper($productName, 'UTF-8');
            if (isset($normalized[$key])) {
                $normalized[$key]['qty'] += $qty;
            } else {
                $normalized[$key] = [
                    'produk' => $productName,
                    'qty'    => $qty,
                ];
            }
        }

        return array_values($normalized);
    }
}