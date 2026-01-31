<?php
declare(strict_types=1);

namespace RedPulse\Services;

use RedPulse\Core\DB;
use Google\Client;
use Google\Service\Oauth2;

class AuthService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();

        // Credential Resolution (CONST > $_ENV > getenv)
        $clientId = defined('GOOGLE_OAUTH_CLIENT_ID') ? GOOGLE_OAUTH_CLIENT_ID : ($_ENV['GOOGLE_OAUTH_CLIENT_ID'] ?? getenv('GOOGLE_OAUTH_CLIENT_ID'));
        $clientSecret = defined('GOOGLE_OAUTH_CLIENT_SECRET') ? GOOGLE_OAUTH_CLIENT_SECRET : ($_ENV['GOOGLE_OAUTH_CLIENT_SECRET'] ?? getenv('GOOGLE_OAUTH_CLIENT_SECRET'));

        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);

        $baseUrl = defined('BASE_URL') ? BASE_URL : ($_ENV['BASE_URL'] ?? getenv('BASE_URL') ?? 'http://localhost:5000');
        // Ensure no trailing slash for callback construction consistency
        $baseUrl = rtrim($baseUrl, '/');
        $this->client->setRedirectUri($baseUrl . '/auth/google/callback');

        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->addScope('openid');
    }

    /**
     * Get the Google Auth URL for redirection
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle the OAuth callback code
     */
    public function loginWithGoogle(string $code): bool
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                error_log("Google Auth Error: " . json_encode($token));
                return false;
            }

            $this->client->setAccessToken($token);
            $google_oauth = new Oauth2($this->client);
            $google_account_info = $google_oauth->userinfo->get();

            return $this->upsertUser([
                'google_id' => $google_account_info->id,
                'email' => $google_account_info->email,
                'name' => $google_account_info->name,
                'avatar' => $google_account_info->picture
            ]);

        } catch (\Exception $e) {
            error_log("Auth Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create or Update user in database and set session
     */
    private function upsertUser(array $userData): bool
    {
        // check if user exists
        $existing = DB::query("SELECT id, role FROM users WHERE email = ? LIMIT 1", [$userData['email']]);

        if (!empty($existing)) {
            // Update
            $userId = $existing[0]['id'];
            DB::query(
                "UPDATE users SET google_id = ?, name = ?, avatar = ?, last_login = NOW() WHERE id = ?",
                [$userData['google_id'], $userData['name'], $userData['avatar'], $userId]
            );
            $role = $existing[0]['role'];
        } else {
            // Insert
            DB::query(
                "INSERT INTO users (google_id, email, name, avatar, last_login) VALUES (?, ?, ?, ?, NOW())",
                [$userData['google_id'], $userData['email'], $userData['name'], $userData['avatar']]
            );
            // Get ID (Assuming DB::query doesn't return ID directly, do a fetch)
            $newUser = DB::query("SELECT id FROM users WHERE email = ? LIMIT 1", [$userData['email']]);
            $userId = $newUser[0]['id'];
            $role = 'user';
        }

        // Set Session
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['user_name'] = $userData['name'];
        $_SESSION['user_avatar'] = $userData['avatar'];
        $_SESSION['user_role'] = $role;
        $_SESSION['logged_in'] = true;

        return true;
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function getUser(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'avatar' => $_SESSION['user_avatar'],
            'role' => $_SESSION['user_role']
        ];
    }

    public static function logout(): void
    {
        session_destroy();
    }
}
