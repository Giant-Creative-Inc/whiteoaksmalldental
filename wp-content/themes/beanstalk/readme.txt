=== Beanstalk ===
Contributors: giantcreativeinc
Requires at least: 6.6
Tested up to: 7.0
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Beanstalk is a native WordPress block parent theme maintained by Giant
Creative Inc. Client-specific design tokens and one-off customizations belong
in a child theme whose style.css header declares Template: beanstalk.

== Installation ==

1. Install Beanstalk in wp-content/themes/beanstalk.
2. Install or create the client child theme.
3. Activate the child theme under Appearance > Themes.

== Development ==

Reusable colors, typography, spacing, gradients, and widths are defined in
theme.json. Shared sections are native block patterns. The generated
assets/css/theme-tokens.css file must not be edited manually.

Developers changing source CSS or theme tokens should run npm install and
npm run build before packaging a release.

== Changelog ==

= 1.1.0 =
* Cleaned parent/child template inheritance and asset loading.
* Added modular theme setup files and explicit section/block styles.
* Updated responsive Cover image handling and project tooling.
