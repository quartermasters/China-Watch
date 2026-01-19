# OAuth Setup Instructions for China Watch

China Watch now requires user authentication via Google and Facebook OAuth. Follow these steps to configure the OAuth providers:

## 1. Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **Google+ API** and **OAuth 2.0 API**
4. Go to **Credentials** → **Create Credentials** → **OAuth 2.0 Client IDs**
5. Configure the OAuth consent screen:
   - Application name: `China Watch`
   - Authorized domains: Add your domain
   - Scopes: `email`, `profile`, `openid`
6. Create OAuth 2.0 Client ID:
   - Application type: **Web application**
   - Authorized redirect URIs: 
     - `http://localhost:5000/auth/google/callback` (development)
     - `https://yourdomain.com/auth/google/callback` (production)
7. Copy the **Client ID** and **Client Secret**

## 2. Facebook OAuth Setup

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app → **Consumer** → **None**
3. Add **Facebook Login** product to your app
4. Configure Facebook Login settings:
   - Valid OAuth Redirect URIs:
     - `http://localhost:5000/auth/facebook/callback` (development)
     - `https://yourdomain.com/auth/facebook/callback` (production)
5. Go to **App Settings** → **Basic**
6. Copy the **App ID** and **App Secret**

## 3. Environment Variables

Add the following environment variables to your Replit secrets or `.env` file:

```bash
# Google OAuth
GOOGLE_OAUTH_CLIENT_ID=your_google_client_id_here
GOOGLE_OAUTH_CLIENT_SECRET=your_google_client_secret_here

# Facebook OAuth
FACEBOOK_APP_ID=your_facebook_app_id_here
FACEBOOK_APP_SECRET=your_facebook_app_secret_here
```

## 4. Testing

1. Restart your China Watch server
2. Visit the homepage - you should be redirected to login
3. Try logging in with both Google and Facebook
4. After successful login, you should be redirected back to the homepage
5. Check the admin panel to see new user registrations

## 5. Newsletter Integration

Users who sign up are automatically subscribed to the newsletter. The system now uses the `users` table instead of the old `subscribers` table for better integration.

## 6. Production Deployment

For production deployment:
1. Update the redirect URIs in both Google and Facebook to use your production domain
2. Ensure HTTPS is enabled for security
3. Update the `BASE_URL` environment variable to your production URL

## Notes

- Users must authenticate to access any content on China Watch
- The system automatically creates user accounts on first login
- Email addresses are collected for newsletter delivery
- User data is stored securely in PostgreSQL database