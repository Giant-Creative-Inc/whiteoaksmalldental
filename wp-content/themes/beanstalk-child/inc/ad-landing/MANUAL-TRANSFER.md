# Manual transfer of the three landing pages

Code deployment and content migration are separate. Do not sync or overwrite the production database from staging.

1. Review and merge the landing-only PR, then verify that GridPane deployed that exact commit. No existing page should opt in merely because the template exists.
2. Record production page IDs, current slugs, templates, content, and page-specific SEO/schema settings for rollback. Keep unrelated pages and site settings unchanged.
3. Take fresh source exports from staging: Invisalign page 1058, Wisdom Teeth page 1118, and Emergency page 1169. These are source IDs only; never assume production has matching IDs.
4. Inventory every Image/Cover asset, responsive `mobileImage` variant, gallery image, and logo. Upload missing exact compressed assets, retain descriptive alt text (empty for genuinely decorative images), and record staging attachment ID/URL → production attachment ID/URL. Verify the existing logo path used by the two new template parts.
5. Export the two relevant Gravity Forms definitions (staging forms 2 and 3) without entries, then import or map them to the intended production forms. Record the actual live form IDs. Review notification recipients, confirmations, spam settings, tracking, and integrations before any delivery test; do not overwrite unrelated forms.
6. Copy approved serialized block content one page at a time. Remap Gravity Forms block IDs, attachment IDs, `wp-image-*` classes, Image/Cover URLs and responsive variants. Replace staging/local internal URLs with production URLs. Preserve the editable blocks and native FAQ Details markup.
7. Transfer the intended page-specific SEO title, description, canonical, robots setting, and custom schema if present. Rebase schema URLs and ensure its FAQ content matches the approved visible copy.
8. Select **Ad Landing Page** only on the three intended destination pages. The final slugs must be `invisalign`, `wisdom-teeth`, and `emergency`. Use the original target page's revision/preview workflow if that URL is already occupied; do not rename unrelated pages to make room.
9. Preview before publication, check all sections at 375px and 1440px, then the documented intermediate widths. Check the call/booking anchors, gallery/carousel, keyboard focus, accordion expansion/collapse, form field rendering, and mobile call bar. Clear only relevant caches after each publication and verify the public page.
10. Recheck the homepage and an existing non-landing page to confirm their original header/footer and styling remain intact. Record the deployed commit and the destination page/form/media mapping.

The approved staging design currently retains monthly financing placeholders (`[$XXX]`, `[XX]`) and an emergency-exam fee placeholder. Resolve public-use wording with the owner before publication; do not invent amounts.

No form submissions or notification emails are part of visual QA. Delivery testing needs its own explicit test plan. A rollback can restore the three saved page revisions/templates and, if needed, revert this code PR; it should not require restoring the full production database.
