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
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDhmz5QxhJnUNl+
TqyM37mCRcB6Wwtl+v+Ya+pzNz9yQriFuZFoqMCe6lN1Li8nf4cmEyX+nZh19TmJ
MeI7+5MGstL6BvDApOtkmRaQ0EApvXiCZ2aAxjb8j8UO7mXwfQ2q2z71cd6l0ur2
u3VzLELidcIXM6b8cGI2X8Gl67Uuz3LlpgzOZngKNnaXG9Mkmxtz2kmUysEQ2AZ3
iUHoOne9solUYiZUMhVxAEJIECn5iXd8ZyHe6XRpFu7OgoSRDGCB7yt0m+kUrEON
Wq3dq54zMQmnZWSq77/QeupBwuBPQtpn/iC2k0pccjJNViB6rnGGRXOEKG+61BcW
CYs2pZwtAgMBAAECggEBAKddLYtqhTiT5+UlpCgVyF72M+YRKJGM9JQ0aobyk2TG
TnEVyuNjXRIHL8acbmrU1oPObL9IVlQbiYVXtkddSCMOAiltgKjJL78/OlhPxWmx
n3bOEmKxqRUP6tY60PDpNHxtjXUz9kH6CEjlpCm+yheJ2GSx8s0O6i0AOH5IL+76
ndWPlf0vSTMWtuaVoFPRy1xIE7DeOsdCScL6awvMDnYxM3f8/ee8C11sEr7KVeFI
CNBYeffyE5Ski5jC3I2Wd8F655dJBBWsHgMd2FSkz0LmU5kxR69I3yq5cHxyvzix
BTLN57OFUoJTdBq1rGLNoVRIO/YOsHt8Tfzcm614C8ECgYEA9P1FtnLLpiVpjnpC
x4RUpkTv5E2JT4dCSSUcdkriKMJWyYzBel9NvUwIbqsNpkaiUHzIQd33uX70z6P/
sn3cS/1i8SBpB6EzJIyy+TN8yI4JzkbkjYjSS361jKdNcFrj+lVj04kUtZ+yGM50
/WPiYBD2LrlOWt7RaGo6aYO8wBUCgYEA67717FOtvgJUe94PC0Kp8EqKpa5zOcyT
+bi5EmSTnkIugmiwAWmY/8qdR45S5QdyZ3/XGUpgkn4VcOl1R//maH4sSLlQomB2
peDMoW61O5ECbFam7+uihdBMdmTkJEqum04+tcPPR89DpBrV2j45tOFGzOpjl+2l
WR78AHD52bkCgYBpzE6kweJsd8+j9eyM2PrtauvmPg3ESHc5u0sVUrVkQLjWBXEw
daPO0dz6XehhHDPQanLY7xCiDM+QNBSicnQGZQCrfQGeh+DcMd7Ae5mxypU1fawJ
60JV0/x3J3DrynSVkPaxTYuh+7sft1kSbGZAclK+Tswltf/dBbMACiIjGQKBgBcu
/2ZPUF3IQuCvlNEprCaqXfovLru3Z7H+PY0WgQHmRkai3vQ0m0xEc7UuxO4rDlAp
XVDqZJbjeV5SRyteeh06k0ZPmyNs0x46/kS962rBNvjKiL49cQ0xz+MwxtLe2U8C
od4kNIU+V8uSrAc7aVdldB4AyuyM/V7HUkcE1T1ZAoGAB2RpakOJcRB3hgMyVU/w
Q+U4ib2Njt8TXahkYgG/ECbKDohgtqyZfmOeyl0FgdGwxrHFVop3KZtUvE6wggQI
0Buc7NVcADBPQXuqLDKcsNay48vZjSzqqA+1GNE9HN5u6H1/Q+jDqq8LTdZiWng6
qhFzPvjU5olo/UNF75FCvZM=
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
