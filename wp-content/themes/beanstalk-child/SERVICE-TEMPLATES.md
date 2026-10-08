# Service templates

Services using the default template are selected automatically by their native
parent relationship:

- Top-level services (`post_parent = 0`): `templates/single-pillar-service.html`.
- Child treatments: the existing `templates/single-service.html` hierarchy.

An explicitly assigned custom template takes precedence. No template metadata,
page content, patterns, or parent-theme files are changed by this selection.

Both templates retain the shared header/footer and editable Post Content. The
pillar template supplies the semantic `service-pillar-page` class on its main
Group for future scoped styling; it currently adds no CSS or visual changes.

Selection lives in `inc/service-templates.php`, loaded by `functions.php`. The
`single_template_hierarchy` filter uses `.php` candidate names because WordPress
converts hierarchy candidates to `.html` when resolving block templates.

Local validation: General & Family Dentistry (499) resolves to
`beanstalk-child//single-pillar-service`; Dental Cleaning (423) resolves to
`beanstalk-child//single-service`. Non-service requests retain their hierarchy.
