<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class TinyDrive
{


    public static function generateToken($user)
    {
        $privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC2EaKTJqyLBCDg
6vcqrw5x4CfiN1ToArL0/eb7JyeZCeMD4hIFnkkrH7Pz7rjOUcg54ARSntJgq3qQ
uXEpGQOW4pjAzsOYxzA1zjGs2VkEWnjpob7JcOJhBynrB00swwsq0Z1wVH/dPShs
Pgx1XAjqeOAO0mw5TksQT/AAMf+48XhP0OnrgItXMDie0Sy0A3W4Au/9+AyTDhhx
la6e0Z03AXEWDn63gZI4KDLG7ggONcx1LlShbOLk4oAnLseaud7dJvktQ3blpRzJ
hl1RgwyUQpeWddShVHXw1N8fQ0xYcLGiTM3Fa2NI1/bGdWOa+tCNFZbnqvaY/N+a
nq0OKRivAgMBAAECggEBALC7bZRpdhN7bm7lpgdI5jyj2hM8oq6T8CLgU9Z3q3hi
oHqkd9n8TAoQPB4NHFcqC7IiQg/xG0W7Lr5NRAsgvNs6Gg0Op88liyavgIjIciBH
shcXapsfeJ+21HnQHJnWuoBV+P2RCI05UIPBt1Y0gHMZ4mSA3YzC8xpifhWycwiR
t0Xtj7HbkrboZ3U0NeQ7txP8J7h/y7BFLAaAFrvIH2MxuixEkI4G/X7ZNUyC0+L5
OKfwmopwUt20SbwSWS7sJM2fMa8EO5eECvTIXPdjLc3a8UyDj+LLWUeviz0OiYBG
+eYhUQ5z1SmyCiV8giF4HJsiBxhf8uUJoG6HY7cKBAECgYEA78bDcaiHj+B/isEg
GGdT/RZcSLvNsZsjNkhFuWOdO31XKfPnfL2bLQsE+7IiMlgoWJigUNb7tFclPZbC
F/rL8e5E9dkAZvbwNlJsM3/9TT2ludRGCRoMEjpMJOxckFaNkQaf5gmkIqSkgTbO
dtu4EZpsSAagL7+3qB1h08itrQUCgYEAwmNNgvO7aRqCMuXSYwKSAEtsuRMiGNfp
/zeOrmxFhycNknk1L3yjYxergRo8gl81ANznX38x9x//CGv/Zrv6+x+K9yrWdtbB
o4hHrWzSWKNTeCB+O2gCnEvf9YMm1jel2uOttpuQxGLBamRbj+kBmD0Y8e2BM8Uy
IurKrshjfSMCgYEAqbxBtJ7BwCcDAn1uM/hJa1q93zsbBEQ3LAEDYpZSwr8ayfcA
ArzSMZOgZnCWZp8jiHwa2L8nFWWvVib3pTQD1XJ/XK3N7BWYvEuijt5muJwQSjkr
jtviebDNIEI10HOE6YoP/S8MAv4b+eCLTXaCmeuABYizMrn7z8Vk8CAaWeUCgYEA
rzoe1C3rwbnwpfr/5NqbTAKxtj/mG6j+uV6Wkurs3gE5PdFfPThIfHoI0u8Ynpk+
SVHYjAA3xUIlfq0D5NMNUrRDa71E6Il8snKV0mJnVxmbk40O3XRL7iKDd2hLaQ1u
x9s3a4XeQPmYytQ+qYJsOpTQ2Ly1HDrlkA5AuL/Q1ysCgYAllPRmdpgyy/tz3FMY
IchjrSYDRhNivTbGicVgJFGwzMzkKKkUyJ5j9Ey+HSvzPUjeEOTeZTw0kwnN+RjE
iYyAh2+5NFi9RlpdTenT03eu1RwjC1oH4BProGp1E4fCWYiYXKCw+BzpTELRvMc0
yqtp53xQlvKsy5Razb2PN2CeTw==
-----END PRIVATE KEY-----
EOD;
        $payload = array(
            "sub" => (string) $user->id, // unique user id string
            "name" => $user->name, // full name of user

            // Optional custom user root path
            "https://claims.tiny.cloud/drive/root" => "/" . $user->id,

            "exp" => time() + 60 * 60 * 5 // 10 minute expiration
        );
        return JWT::encode($payload, $privateKey, 'RS256');
    }
}
