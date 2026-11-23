<?php

namespace App\Helper;

use Illuminate\Support\Str;

class ToolsHelper
{
    public static function setErrorMessage($message)
    {
        session(['error_message' => $message]);
    }

    public static function getErrorMessage()
    {
        return session('error_message', '');
    }

    public static function setAuthToken($authToken)
    {
        session(['auth_token' => $authToken]);
    }

    public static function getAuthToken()
    {
        return session('auth_token', '');
    }

    public static function collectPairArrays(array $data1, array $data2, $default = null)
    {
        $data = [];
        foreach ($data1 as $key => $value) {
            $data[$value] = $data2[$key] ?? $default;
        }

        return collect($data);
    }

    public static function generateRandomString(int $length = 64): string
    {
        // Menghasilkan secret acak berbasis hex yang kuat
        return Str::random($length);
    }

    public static function generateId()
    {
        return Str::uuid()->toString();
    }

    public static function isValidId($data)
    {
        return Str::isUuid($data);
    }

    public static function stringToValidUsername(string $str): string
    {
        // ganti karakter yang bukan huruf/angka/underscore dengan underscore
        $clean = preg_replace('/[^a-zA-Z0-9_]/', '_', $str);

        return strtolower($clean);
    }

    public static function desimalToRomawi($desimal)
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
        ];

        return $romawi[$desimal] ?? '';
    }

    public static function checkRoles($roles, $allowedRoles)
    {
        if (is_array($allowedRoles)) {
            return in_array($roles, $allowedRoles);
        } elseif (is_string($allowedRoles)) {
            return $roles === $allowedRoles;
        }

        return false;
    }

    /**
     * Mendapatkan nilai RAW dari sel Excel (nilai asli yang tersimpan)
     *
     * @param  Worksheet  $worksheet  Worksheet object
     * @param  int  $colIndex  Indeks kolom (1-based: 1 = A, 2 = B, dst)
     * @param  int  $rowIndex  Indeks baris
     * @return string Nilai sel dalam bentuk string yang di-trim
     *
     * Contoh:
     * - Jika sel berisi formula "=SUM(A1:A5)", akan mengembalikan "=SUM(A1:A5)"
     * - Jika sel berisi angka 1000 dengan format currency, akan mengembalikan "1000"
     */
    public static function getValueExcel($worksheet, $colIndex, $rowIndex)
    {
        $columnLetter = is_int($colIndex) ? \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) : $colIndex;
        $cellAddress = $columnLetter.$rowIndex;
        $cell = $worksheet->getCell($cellAddress);

        return trim((string) $cell->getValue());  // Mengambil nilai RAW
    }

    /**
     * Mendapatkan nilai yang sudah DIFORMAT sesuai tampilan di Excel
     *
     * @param  Worksheet  $worksheet  Worksheet object
     * @param  int  $colIndex  Indeks kolom (1-based: 1 = A, 2 = B, dst)
     * @param  int  $rowIndex  Indeks baris
     * @return string Nilai yang sudah diformat dalam bentuk string yang di-trim
     *
     * Contoh:
     * - Jika sel berisi formula "=SUM(A1:A5)", akan mengembalikan hasil perhitungan (misal "1500")
     * - Jika sel berisi angka 1000 dengan format currency, akan mengembalikan "$1,000.00"
     */
    public static function getFormattedValueExcel($worksheet, $colIndex, $rowIndex)
    {
        $columnLetter = is_int($colIndex) ? \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) : $colIndex;
        $cellAddress = $columnLetter.$rowIndex;
        $cell = $worksheet->getCell($cellAddress);

        return trim((string) $cell->getFormattedValue());  // Mengambil nilai yang sudah diformat
    }

    public static function getRegexClearComments($extFile)
    {
        switch ($extFile) {
            case 'java':
            case 'c':
            case 'h':
            case 'js':
            case 'ts':
            case 'php':
            case 'kt':
                return '/\/\/.*$|\/\*[\s\S]*?\*\//gm';
                break;
            case 'py':
            case 'bash':
            case 'rb':
                return '/#.*/gm';
                break;
            case 'sql':
                return '/--.*|\/\*[\s\S]*?\*\//gm';
                break;
            case 'hs':
                return '/--.*|{-[\s\S]*?-}/gm';
                break;
            default:
                return '';
                break;
        }
    }

    public static function checkTestCaseLineByLine($expectOut, $actualOut)
    {
        $hasil = [];

        $dataExpectOut = preg_split('/\r\n|\r|\n/', trim($expectOut));
        $dataActualOut = preg_split('/\r\n|\r|\n/', trim($actualOut));

        $totalExpectedLines = count($dataExpectOut);
        $totalActualLines = count($dataActualOut);
        $totalLinesDiff = $totalActualLines - $totalExpectedLines;

        $totalLinesMatch = 0;
        $totalLinesNotMatch = 0;

        foreach ($dataExpectOut as $key => $expectedData) {
            $actualData = $dataActualOut[$key] ?? '';

            similar_text($expectedData, $actualData, $similar);  // $similar = persentase
            if (! $similar) {
                $similar = $actualData == $expectedData;
            }

            $hasil[] = (object) [
                'baris' => $key + 1,
                'expected' => $expectedData,
                'actual' => $actualData,
                'identik' => $similar == 100,
                'similar' => round($similar, 0),
            ];

            if ($similar == 100) {
                $totalLinesMatch += 1;
            } else {
                $totalLinesNotMatch += 1;
            }
        }

        $percentageMatch = round(max(0, ($totalLinesMatch / ($totalExpectedLines + $totalLinesDiff)) * 100, 0));
        $percentageNotMatch = round(max(0, ($totalLinesNotMatch / $totalExpectedLines + $totalLinesDiff) * 100, 0));

        return (object) [
            'totalLinesMatch' => $totalLinesMatch,
            'totalLinesNotMatch' => $totalLinesNotMatch,
            'totalExpectedLines' => $totalExpectedLines,
            'totalActualLines' => $totalActualLines,
            'totalLinesDiff' => $totalLinesDiff,
            'percentageMatch' => $percentageMatch,
            'percentageNotMatch' => $percentageNotMatch,
            'resultChecking' => $hasil,
        ];
    }

    public static function isValidDomain($domain)
    {
        // Pastikan input adalah string
        if (! is_string($domain)) {
            return false;
        }

        $domain = trim(strtolower($domain));

        // Cek panjang maksimal domain (253 karakter sesuai RFC)
        if (strlen($domain) > 253) {
            return false;
        }

        // Regex yang setara dengan JS:
        // - Tidak boleh dimulai dengan tanda hubung (-)
        // - Setiap label: 1-63 karakter, boleh a-z, 0-9, dan -
        // - Diakhiri dengan TLD: a-z, minimal 2 karakter, maksimal 63
        if (! preg_match('/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i', $domain)) {
            return false;
        }

        // Validasi tambahan: pastikan tidak ada label yang berakhir dengan "-"
        $labels = explode('.', $domain);
        foreach ($labels as $label) {
            if (substr($label, 0, 1) === '-' || substr($label, -1) === '-') {
                return false;
            }
            if (strlen($label) > 63) {
                return false;
            }
        }

        return true;
    }
}



