<?php

if (!function_exists('encryptId')) {
    /**
     * Encrypt an integer ID to a URL-safe string.
     * Deterministic: same ID always produces the same encrypted value.
     */
    function encryptId(int|string $id): string
    {
        $key = substr(hash('sha256', config('app.key')), 0, 32);
        $iv  = substr(hash('sha256', config('app.key') . '_meal_iv'), 0, 16);

        $encrypted = openssl_encrypt((string) $id, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        // URL-safe base64 (no +, /, or = padding)
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }
}

if (!function_exists('decryptId')) {
    /**
     * Decrypt an encrypted ID back to an integer.
     * Aborts with 404 if the value is invalid or tampered.
     */
    function decryptId(string $hash): int
    {
        $key    = substr(hash('sha256', config('app.key')), 0, 32);
        $iv     = substr(hash('sha256', config('app.key') . '_meal_iv'), 0, 16);
        $padded = strtr($hash, '-_', '+/') . str_repeat('=', (4 - strlen($hash) % 4) % 4);

        $decrypted = openssl_decrypt(base64_decode($padded), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false || !ctype_digit(ltrim((string) $decrypted, '-'))) {
            abort(404, 'Invalid resource identifier.');
        }

        return (int) $decrypted;
    }
}
