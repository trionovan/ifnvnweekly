<?php

    /**
     * Encode data biner/string biasa menjadi format Base32.
     * Base32 dipakai karena secret key TOTP harus dalam bentuk ini
     * supaya bisa dibaca oleh app Google Authenticator.
     *
     * @param string $data  String asli yang mau di-encode
     * @return string       Hasil string dalam format Base32
     */
    function base32Encode($data) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $binaryString = '';
        foreach (str_split($data) as $char) {
            $binaryString .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }
        $encoded = '';
        foreach (str_split($binaryString, 5) as $chunk) {
            $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            $encoded .= $alphabet[bindec($chunk)];
        }
        return $encoded;
    }

    /**
     * Kebalikan dari base32Encode(): mengubah string Base32
     * balik lagi jadi bytes asli (biner). Dipakai buat decode
     * secret key yang tersimpan di database sebelum dipakai
     * generate kode OTP.
     *
     * @param string $data  String dalam format Base32
     * @return string       Bytes/binary asli hasil decode
     */
    function base32Decode($data) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $binaryString = '';
        foreach (str_split(strtoupper($data)) as $char) {
            $pos = strpos($alphabet, $char);
            if ($pos === false) continue;
            $binaryString .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }
        $bytes = '';
        foreach (str_split($binaryString, 8) as $byte) {
            if (strlen($byte) < 8) continue;
            $bytes .= chr(bindec($byte));
        }
        return $bytes;
    }

    /**
     * Generate secret key acak (random) buat user baru.
     * Secret key inilah yang disimpan di kolom mfa_secret
     * dan jadi "kunci rahasia" yang menghubungkan akun user
     * dengan app Google Authenticator di HP-nya.
     *
     * @param int $length  Panjang karakter secret (default 16)
     * @return string      Secret key acak format Base32
     */
    function generateSecret($length = 16) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        return $secret;
    }

    /**
     * Inti dari algoritma TOTP: menghasilkan kode 6 digit
     * berdasarkan secret key + waktu saat ini (dibagi per 30 detik).
     * Ini persis fungsi yang juga dijalankan di dalam app
     * Google Authenticator, makanya hasilnya bisa sama/cocok.
     *
     * @param string   $secret     Secret key milik user (Base32)
     * @param int|null $timeSlice  Slot waktu (opsional, default waktu sekarang / 30)
     * @return string              Kode OTP 6 digit
     */
    function getTOTPCode($secret, $timeSlice = null) {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }
        $secretKey = base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset]) & 0x7f) << 24) |
            ((ord($hash[$offset + 1]) & 0xff) << 16) |
            ((ord($hash[$offset + 2]) & 0xff) << 8) |
            (ord($hash[$offset + 3]) & 0xff)
        ) % 1000000;
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }

/**
 * Mencocokkan kode yang diinput user dengan kode yang seharusnya
 * valid saat ini. Pakai "window" toleransi (+/- beberapa slot waktu)
 * supaya tetap valid walau ada sedikit selisih jam antara server
 * dan HP user (jam tidak 100% presisi sama).
 *
 * @param string $secret  Secret key milik user
 * @param string $code    Kode 6 digit yang diinput user
 * @param int    $window  Toleransi jumlah slot waktu sebelum/sesudah (default 2 = ±60 detik)
 * @return bool           true kalau kode cocok, false kalau tidak
 */
function verifyTOTP($secret, $code, $window = 2) {
    $currentSlice = floor(time() / 30);
    for ($i = -$window; $i <= $window; $i++) {
        if (getTOTPCode($secret, $currentSlice + $i) === $code) {
            return true;
        }
    }
    return false;
}

/**
 * Membuat URL gambar QR code yang berisi data setup MFA
 * (secret key + nama akun + nama aplikasi). QR ini yang
 * di-scan user pertama kali pakai Google Authenticator
 * supaya app tersebut tersinkron dengan secret key di server.
 *
 * @param string $secret    Secret key milik user
 * @param string $username  Username yang login (ditampilkan di app Authenticator)
 * @param string $issuer    Nama aplikasi/perusahaan (default 'IFNVNWeekly')
 * @return string           URL gambar QR code
 */
function getQRCodeUrl($secret, $username, $issuer = 'IFNVNWeekly') {
    $otpauth = "otpauth://totp/" . urlencode($issuer . ':' . $username) .
               "?secret=" . $secret . "&issuer=" . urlencode($issuer);
    return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($otpauth);
}