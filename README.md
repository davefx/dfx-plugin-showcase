# DFX Plugin Showcase

The "More plugins" tab shared by every DaveFX plugin: a discreet tab in each plugin's own
settings page that lists the author's other plugins. No menu entries, banners or notices.

## How it works

- **The catalog lives at `https://davefx.com/plugins.json`.** Publishing a new plugin there makes
  it appear in every plugin, without releasing any of them. `catalog.json` in this folder is the
  same file: it is both the source to publish and the fallback each plugin bundles.
- **Network:** one GET, with a neutral user agent (WordPress's default carries the site's URL), made only when an administrator opens the tab, cached for a week (12 hours
  when the site cannot be reached, showing the bundled copy meanwhile). The cache is shared by
  every plugin on the site.
- **Newest copy wins.** Every plugin bundles a copy. Each copy announces its version in `load.php`,
  and only the newest copy's class is loaded, as Action Scheduler and the Freemius SDK do. Raise
  the version in `load.php` on every change to the class.
- **The catalog is untrusted input.** Entries without an id, a name or an https URL are dropped,
  icons must be https, and every text is printed as plain text.
- **Texts** come from the catalog's `strings`, by the admin user's language (`es_es`, then `es`),
  with English built in. Plugin descriptions work the same way. There are no gettext calls, so the
  library never needs a text domain of its own.

## Adding it to a plugin

1. Copy this folder into the plugin (for example `libraries/dfx-plugin-showcase/`) with
   `bin/sync.sh <plugin dir>/libraries/dfx-plugin-showcase`. Only `load.php`,
   `class-dfx-plugin-showcase.php` and `catalog.json` are copied.
2. Require it from the main plugin file, in the **free** code (not premium-only):
   `require_once __DIR__ . '/libraries/dfx-plugin-showcase/load.php';`
3. Add a tab to the plugin's settings page, labelled `dfx_plugin_showcase_label()` (escape it),
   whose content is:

   ```php
   dfx_plugin_showcase_render( array( 'exclude' => '<this plugin's catalog id>' ) );
   ```

4. In `readme.txt` (WordPress.org requires it), under `== External services ==`:

   > The "More plugins" tab of the settings page downloads the list of the author's other plugins
   > from davefx.com (https://davefx.com/plugins.json) when an administrator opens it, at most once
   > a week. Nothing about the site or its users is sent. Privacy policy: https://davefx.com/…

Catalog ids: `coupon-audit`, `role-changer`, `leanroles`, `telegram-feed`, `random-user-ids`.

## Catalog format (`schema` 1)

```json
{
	"schema": 1,
	"strings": { "es": { "label": "Más plugins", "intro": "…", "install": "…", "details": "…",
	                     "active": "…", "installed": "…", "empty": "…" } },
	"plugins": [ {
		"id": "coupon-audit",
		"name": "Coupon Audit and Analytics for WooCommerce",
		"description": { "en": "…", "es": "…" },
		"url": "https://wordpress.org/plugins/…/",
		"icon": "https://ps.w.org/…/icon.svg",
		"wporg": "coupon-audit-and-analytics-for-woocommerce",
		"dirs": [ "optional: plugin folder names that mean it is installed" ]
	} ]
}
```

`wporg` (the WordPress.org slug) adds an "Install" button that opens WordPress's own plugin
details window. Without `dirs`, the plugin counts as installed when a folder named `wporg` or
`wporg-premium` exists. A change that breaks this format needs a new `schema` number, which older
copies will ignore, falling back to their bundled catalog.

## Testing

`tests/fixture-plugin` is a plugin that loads the library like a real one and shows the tab under
Settings → Showcase fixture. `.wp-env.json` runs it with `npx @wordpress/env start`
(http://localhost:8897).
