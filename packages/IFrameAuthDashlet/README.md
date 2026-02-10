# IFrame Auth Dashlet for SugarCRM

## Overview
The IFrame Auth Dashlet is a configurable SugarCRM dashlet that securely embeds an external web application or service inside an iframe. It generates a secure token on the server side and passes it to the iframe, allowing for authenticated communication with third-party services.

## Features
- Embeds any external web application via iframe in SugarCRM dashboards.
- Secure token generation using a secret key and user information.
- Admin-configurable Base URL and Secret Key.
- Optionally supports external token generation via API call.
- Defensive error handling and user-friendly configuration guidance.
- CSP (Content Security Policy) guidance to ensure iframe loads correctly.

## Use Cases
- Integrate chat, analytics, or custom web tools directly into SugarCRM.
- Provide secure, user-specific access to external services from within Sugar.
- Enable SSO-like experiences for embedded apps.

## Configuration
1. **Admin Setup:**
   - Go to **Administration > IFrame Auth Dashlet Plugin**.
   - Set the **Base URL** (the external service to embed).
   - Set the **Secret Key** (used for secure token generation).
   - Save the configuration.
2. **CSP Settings:**
   - The Base URL must be present in SugarCRM's CSP (Content Security Policy) settings.
   - If not, the iframe will not load. A link to CSP settings is provided in the config UI.
3. **Dashlet Usage:**
   - Add the "IFrame Auth Dashlet" to any dashboard.
   - If not configured, users will see a clear error message and a link to the configuration page.

## Security
- The secret key is never exposed to the client/browser.
- All tokens are generated server-side.
- Defensive coding ensures no sensitive data is leaked.

## Troubleshooting
- If the iframe does not load, ensure the Base URL is present in CSP settings.
- If you see a configuration error, check that both Base URL and Secret Key are set in the admin panel.

