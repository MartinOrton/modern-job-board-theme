<?php
/**
 * The first version of the feature page body content (theme 0.9.0-beta.57).
 *
 * Kept only so the one-off upgrade in inc/conversion.php can recognise body
 * content that is still exactly as the theme wrote it, and replace it with
 * the rewritten version. Pages edited by hand do not match and are left
 * alone. Not loaded on any other request.
 *
 * @package Modern_Job_Board_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Copy for each feature page, keyed by page slug.
 *
 * @return array[]
 */
function mjb_theme_v1_feature_body_copy() {
	return array(
		'frontend-job-submission'          => array(
			'what_q'   => 'What is frontend job submission?',
			'what_a'   => 'Frontend job submission lets employers post, edit and manage their own job listings from a form on your website, without ever opening wp-admin. In Modern Job Board it is included in every plan, including Free, and it powers the Post a Job page that the setup wizard creates for you.',
			'how_q'    => 'How does frontend job submission work?',
			'steps'    => array(
				array( 'Add the form', 'Run the setup wizard, or place the Job Submission Form block or the <code>[mjb_job_form]</code> shortcode on any page.' ),
				array( 'Employers sign up and post', 'Recruiters register with their company name and logo, then fill in the job, its category, location and type.' ),
				array( 'You decide how jobs go live', 'Approve new listings yourself, or turn on auto-approve so employer submissions publish straight away.' ),
				array( 'Listings look after themselves', 'Jobs expire automatically when their listing duration ends, and can be marked filled, scheduled or republished.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Frontend posting and editing', 'Employers create and update listings from your site, with no wp-admin access.' ),
				array( 'Company profiles', 'Every job is linked to the employer\'s company, with its logo, description and links.' ),
				array( 'Featured listings', 'Highlight important jobs so they stand out in search results.' ),
				array( 'Automatic expiry', 'A background task closes each listing when its duration ends.' ),
				array( 'Mark filled, schedule and republish', 'Close a filled role, set a future go-live date or bring an expired job back.' ),
				array( 'On-site or external applications', 'Collect applications on your site or send candidates to an external apply URL.' ),
				array( 'Spam protection', 'A honeypot is always on, with optional reCAPTCHA v2 and rate limits on registrations and applications.' ),
				array( 'Custom job fields (Pro)', 'Add your own fields to the form with the <a href="{f:custom-fields-imports-pro}">custom fields builder</a>.' ),
			),
			'plan_q'   => 'Is frontend job submission free?',
			'plan_a'   => 'Yes. Frontend job submission is part of the Free plan, which allows up to 100 active job listings. Pro ($149 a year) removes that limit and adds paid posting through WooCommerce, so you can charge per post or sell job credit packages. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Do employers need access to wp-admin to post a job?', 'No. Employers post and edit jobs from the Job Submission Form on your website and manage them in their recruiter dashboard. They never see the WordPress admin.' ),
				array( 'Can I review jobs before they are published?', 'Yes. New listings can wait for your approval, or you can turn on auto-approve for employer submissions so they publish immediately.' ),
				array( 'Can I charge employers to post jobs?', 'Yes, on the Pro and Business plans. WooCommerce handles pay-per-post and job credit packages through any payment gateway it supports, and you keep the revenue. See <a href="{f:woocommerce-monetization-pro}">WooCommerce monetization</a>.' ),
				array( 'What happens when a job expires?', 'It closes automatically when its listing duration ends. The employer can republish it whenever the role opens again.' ),
			),
		),
		'predictive-search-pretty-urls'    => array(
			'what_q'   => 'What is predictive job search?',
			'what_a'   => 'Predictive job search suggests matching keywords, locations, categories, job types and companies as a candidate types, and updates the results without reloading the page. Modern Job Board pairs it with pretty URLs, so every search has a clean address under /jobs/ that can be bookmarked, shared and indexed. Both are included in every plan.',
			'how_q'    => 'How does predictive search work?',
			'steps'    => array(
				array( 'Start typing', 'Suggestions appear for keywords, locations, categories, job types and companies.' ),
				array( 'Narrow it down', 'Filters apply in place, and filter chips show what is active and remove it in a tap.' ),
				array( 'Share the result', 'The address becomes a readable path such as <code>/jobs/in/cape-town/category/engineering/</code>.' ),
				array( 'Get alerts', 'Candidates subscribe to the search and receive new matching jobs by email.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Typeahead suggestions', 'Autocomplete for keyword, location, category, type and company.' ),
				array( 'AJAX filtering', 'Results refresh in place, with paging, without a full page reload.' ),
				array( 'Filter chips', 'Active filters show as chips that can be removed one at a time.' ),
				array( 'Pretty search URLs', 'Location, category, type and keyword become readable /jobs/ path segments.' ),
				array( 'Subscribe to this search', 'A Get Email Alerts box under the search form turns any search into a <a href="{f:job-alerts-saved-jobs-talent}">job alert</a>.' ),
				array( 'The same routes in the API (Business)', 'The REST API search route follows the same path pattern.' ),
			),
			'plan_q'   => 'Is predictive search included in the free plan?',
			'plan_a'   => 'Yes. Predictive search, filter chips, pretty /jobs/ URLs and search alerts are part of every plan, including Free. Searching jobs from other systems through the REST API is part of the Business plan. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Why do pretty search URLs matter for SEO?', 'A readable path such as /jobs/in/london/category/design/ describes the page in words that people and search engines understand. It is the same every time, so it can be linked to, shared and indexed rather than being a one-off query string.' ),
				array( 'Which fields can candidates search by?', 'Keyword, location, category, job type and company. Each field suggests matches as the candidate types.' ),
				array( 'Can candidates save a search?', 'Yes. They can subscribe to any search and receive a daily or weekly email when new jobs match it. See <a href="{f:job-alerts-saved-jobs-talent}">job alerts and saved jobs</a>.' ),
				array( 'Does search work with my theme?', 'Yes. Search and listings are a block and a shortcode, so they work on any WordPress page and with any theme.' ),
			),
		),
		'employer-candidate-accounts'      => array(
			'what_q'   => 'What accounts does Modern Job Board include?',
			'what_a'   => 'Modern Job Board gives employers and candidates their own accounts and dashboards on your WordPress site. Candidates manage their profile, CVs, saved jobs and applications. Recruiters manage their company, their jobs and every applicant through a five-stage pipeline. Both are included in every plan.',
			'how_q'    => 'How does sign-up work?',
			'steps'    => array(
				array( 'Candidates register with a CV', 'A CV is required at sign-up; a profile photo is optional and cropped square automatically.' ),
				array( 'Recruiters register with their company', 'They add their name, a required company name, an optional logo and their login details.' ),
				array( 'You approve, if you want to', 'Turn on approval for candidates, recruiters or both, and new accounts wait until you accept them.' ),
				array( 'Applications move through the pipeline', 'Recruiters move each application from New to Reviewed, Shortlisted, Rejected or Hired.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Candidate dashboard', 'Profile, saved jobs, applications and email preferences in one place.' ),
				array( 'CV library', 'Candidates keep up to eight CVs on file.' ),
				array( 'Email preferences', 'Candidates choose which emails they get: applications, recruiter messages and tips.' ),
				array( 'Recruiter dashboard', 'Tabs for Overview, Company, Jobs, Applications and Packages.' ),
				array( 'Application pipeline', 'Five stages: New, Reviewed, Shortlisted, Rejected and Hired.' ),
				array( 'Company profile', 'Name, tagline, description, website, LinkedIn, X, Facebook and logo.' ),
				array( 'Employer analytics', 'Views, applications and conversion rate for each job.' ),
				array( 'Messaging', 'Employers and candidates message each other on your site.' ),
			),
			'plan_q'   => 'Are employer and candidate accounts free?',
			'plan_a'   => 'Yes. Registration, both dashboards, the application pipeline and messaging are part of every plan, including Free. Pro adds paid features around them, such as paid job posting and paid CV access. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Do candidates have to upload a CV?', 'Yes. A CV is required to create a candidate account, so every applicant arrives with one. Candidates can keep up to eight CVs and update them at any time.' ),
				array( 'Which stages can a recruiter move an application through?', 'New, Reviewed, Shortlisted, Rejected and Hired. Recruiters change the stage from the Applications tab of their dashboard.' ),
				array( 'Can I approve accounts before they become active?', 'Yes. Turn on approval for candidates, recruiters or both, and new accounts stay pending until you approve them.' ),
				array( 'Where are candidates\' CVs stored?', 'In a private folder on your own server, not in the Media Library. See <a href="{f:private-resumes-cvs}">private resumes and CVs</a>.' ),
			),
		),
		'private-resumes-cvs'              => array(
			'what_q'   => 'How does Modern Job Board keep CVs private?',
			'what_a'   => 'Modern Job Board stores every uploaded CV in a private folder on your own server, wp-content/mjb-private/, instead of the public Media Library. A CV can only be downloaded through a signed-in, token-checked request, and a recruiter sees a candidate\'s CV only when the candidate has shared it with them. This is included in every plan.',
			'how_q'    => 'How does CV access work?',
			'steps'    => array(
				array( 'A candidate uploads a CV', 'It is saved to the private folder, outside the Media Library.' ),
				array( 'They apply', 'A copy of the CV is kept with the application, so later edits do not change what the employer received.' ),
				array( 'A recruiter opens it', 'The download is checked against the recruiter\'s account and a security token before the file is sent.' ),
				array( 'You set the wider rules', 'Choose who may browse the talent pool and whether surnames are hidden.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Private storage', 'CVs live in wp-content/mjb-private/, never in the Media Library.' ),
				array( 'Authenticated downloads', 'Every download needs a signed-in account and a valid security token.' ),
				array( 'Shared-only access', 'Recruiters see a CV only when the candidate has shared it with them.' ),
				array( 'Five access levels', 'Open the talent pool to everyone, registered members, recruiters, verified recruiters or paid CV access only.' ),
				array( 'Candidate anonymizer', 'Optionally hide candidates\' surnames from recruiters browsing the talent pool.' ),
				array( 'Paid CV access (Pro)', 'Sell per-application unlocks or time-based access passes through <a href="{f:woocommerce-monetization-pro}">WooCommerce</a>.' ),
			),
			'plan_q'   => 'Is private CV storage included in the free plan?',
			'plan_a'   => 'Yes. Private storage, authenticated downloads, the access levels and the anonymizer are part of every plan. Selling CV access, through per-application unlocks or time-based passes, needs WooCommerce on Pro or Business. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Why not store CVs in the Media Library?', 'Files in the Media Library are public: anyone with the link can open them. CVs hold personal data, so Modern Job Board keeps them in a private folder and serves them only to signed-in users who are allowed to see them.' ),
				array( 'Do I need to change my server settings?', 'Make sure your web server refuses direct requests to wp-content/mjb-private/. On nginx that takes one rule, which is covered in the plugin\'s deployment guide.' ),
				array( 'Can I hide candidates\' surnames?', 'Yes. Turn on the candidate anonymizer and recruiters browsing the talent pool see candidates without their surname.' ),
				array( 'Can I charge recruiters for CV access?', 'Yes, on Pro and Business. Set the talent pool to paid CV access and sell per-application unlocks or time-based passes through WooCommerce.' ),
			),
		),
		'woocommerce-monetization-pro'     => array(
			'what_q'   => 'How do you make money with a WordPress job board?',
			'what_a'   => 'With Modern Job Board Pro you charge through WooCommerce: per job post, in packages of job credits, for featured listings, for access to candidate CVs, or for extra job alerts. Payments go through the WooCommerce payment gateway you choose, straight to you, with no commission to Modern Job Board. WooCommerce monetization is part of the Pro and Business plans.',
			'how_q'    => 'How does paid posting work?',
			'steps'    => array(
				array( 'Create WooCommerce products', 'Add products for a single post, a credit package or a CV access pass, and set the quantity or duration.' ),
				array( 'Employers buy from your board', 'They check out through your WooCommerce store and gateway.' ),
				array( 'Jobs go live on payment', 'A pay-per-post job publishes when its order is paid, and credits are spent as jobs are posted or featured.' ),
				array( 'Refunds are handled', 'Refunds, cancellations and expiry are reflected in the employer\'s credits and access.' ),
			),
			'incl_q'   => 'What can you charge for?',
			'included' => array(
				array( 'Pay-per-post', 'A job is published as soon as its order is paid.' ),
				array( 'Job credit packages', 'Sell credits in bulk; one balance covers posting and featuring.' ),
				array( 'Feature this job', 'Employers pay to feature a single listing, à la carte.' ),
				array( 'Paid CV unlocks', 'Recruiters pay to open an individual application\'s CV.' ),
				array( 'Time-based CV access', 'Passes that give unlimited CV access for a set number of days.' ),
				array( 'Candidate alert packages', 'Sell extra job alert slots to candidates.' ),
				array( 'Credit balance in the dashboard', 'Employers see their remaining credits in the recruiter dashboard.' ),
				array( 'Any WooCommerce gateway', 'PayPal, Stripe, Square, Paystack, Payfast, Yoco, bank transfer and more.' ),
			),
			'plan_q'   => 'Which plan do I need to charge employers?',
			'plan_a'   => 'Pro or Business. WooCommerce monetization is not part of the Free plan. Pro costs $149 a year and also removes the 100-job limit; Business costs $299 a year and adds the <a href="{f:rest-api-feeds-webhooks-business}">REST API, feeds and webhooks</a>. <a href="{pricing}">See pricing</a>.',
			'faq'      => array(
				array( 'Does Modern Job Board take a commission on employer payments?', 'No. Employer payments go through your own WooCommerce store and gateway, so the revenue is yours. Only your payment gateway\'s fees apply.' ),
				array( 'Which payment gateways work?', 'Any gateway that works with WooCommerce, for example PayPal, Stripe, Square, Paystack, Payfast or Yoco, as well as manual bank transfer. Availability depends on your country and the provider.' ),
				array( 'Can a credit be used to feature a job?', 'Yes. Credits come from one balance that covers both posting and featuring jobs.' ),
				array( 'What happens when an order is refunded?', 'Refunds, cancellations and expiry are handled, so the employer\'s credits and access follow the order\'s current state.' ),
			),
		),
		'gutenberg-blocks'                 => array(
			'what_q'   => 'Which blocks does Modern Job Board add to the editor?',
			'what_a'   => 'Modern Job Board adds eight blocks to the WordPress block editor: Job Listings, Job Submission Form, Recruiter Dashboard, Recruiter Registration, Recruiter Login, Candidate Registration, Candidate Login and Candidate Dashboard. They sit under Modern Job Board in the inserter and work like any other block. All eight are included in every plan.',
			'how_q'    => 'How do you build a job board page?',
			'steps'    => array(
				array( 'Run the setup wizard', 'Modern Job Board → Setup creates any missing board pages in one click.' ),
				array( 'Or place blocks yourself', 'Open any page, search the inserter for Modern Job Board and add the block you need.' ),
				array( 'Design around it', 'Add headings, images, columns and patterns around the block, as on any page.' ),
				array( 'Use shortcodes elsewhere', 'Every block has a matching shortcode for widgets, page builders and templates.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Job Listings', 'Searchable listings with AJAX filters and a set number per page.' ),
				array( 'Job Submission Form', 'The front-end form for recruiters to post jobs.' ),
				array( 'Recruiter and Candidate Dashboards', 'Everything each side manages after signing in.' ),
				array( 'Registration and Login', 'Separate sign-up and login blocks for recruiters and candidates.' ),
				array( 'Works with any theme', 'Front-end styles are included, plus templates for job archives and single jobs.' ),
				array( 'Extra shortcodes', 'A jobs map, ad banners, and header buttons such as <code>[mjb_job_seeker]</code>, <code>[mjb_recruiter]</code> and <code>[mjb_post_job]</code>.' ),
			),
			'plan_q'   => 'Are the blocks available on the free plan?',
			'plan_a'   => 'Yes. All eight blocks and their shortcodes are part of every plan, including Free. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Do the blocks work with my theme?', 'Yes. They work with any WordPress theme: the plugin ships its own front-end styles plus templates for job archives and single job pages.' ),
				array( 'Can I use shortcodes instead of blocks?', 'Yes. Every block has an equivalent shortcode, such as <code>[mjb_jobs]</code> for listings and <code>[mjb_job_form]</code> for the submission form, for classic content, widgets or page builders.' ),
				array( 'Which pages does the setup wizard create?', 'Jobs, Post a Job, Employer Dashboard, Candidate Dashboard, Employer Registration and Candidate Registration, each with its matching block or shortcode in place.' ),
				array( 'Do I need a page builder?', 'No. The blocks run in the standard WordPress editor. If you prefer a page builder, use the shortcodes.' ),
			),
		),
		'seo-ready'                        => array(
			'what_q'   => 'Is Modern Job Board good for SEO?',
			'what_a'   => 'Yes. Every published job carries Schema.org JobPosting structured data, the format Google for Jobs reads, and a field mapper matches your job types to Google\'s employment types without code. Readable /jobs/ URLs, related jobs on every listing and an indexing health check are included too, in every plan.',
			'how_q'    => 'How do jobs reach Google for Jobs?',
			'steps'    => array(
				array( 'Publish a job', 'JobPosting JSON-LD is added to the job page automatically.' ),
				array( 'Map your fields', 'Match your job types to Google\'s employment types and set any fixed values once.' ),
				array( 'Preview the markup', 'Check a job\'s structured data in the admin before Google sees it.' ),
				array( 'Keep the site indexable', 'The dashboard warns you if search engines are being discouraged from indexing the site.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'JobPosting structured data', 'JSON-LD on every job page.' ),
				array( 'Google for Jobs mapper', 'Map job types to Google\'s employment types and set static fields.' ),
				array( 'Markup preview', 'See a job\'s structured data inside the admin.' ),
				array( 'Pretty URLs', 'Readable /jobs/ paths for searches; see <a href="{f:predictive-search-pretty-urls}">predictive search</a>.' ),
				array( 'Related jobs', 'Each job page links to similar openings, so visitors keep browsing.' ),
				array( 'Archive titles and descriptions', 'Stronger titles and meta descriptions for category, city and company archives.' ),
				array( 'Health checks', 'The dashboard flags missing pages, inactive structured data and blocked indexing.' ),
				array( 'Maps on job pages', 'An optional Google Map on each job, with your own API key.' ),
			),
			'plan_q'   => 'Is JobPosting schema included in the free plan?',
			'plan_a'   => 'Yes. Structured data, the Google for Jobs mapper, pretty URLs and the health checks are part of every plan. The XML job feed for aggregators is part of the Business plan; see <a href="{f:rest-api-feeds-webhooks-business}">REST API, feeds and webhooks</a>.',
			'faq'      => array(
				array( 'What is JobPosting schema?', 'JobPosting is the Schema.org vocabulary for job adverts. Search engines read it to show jobs in dedicated results such as Google for Jobs, with the title, employer, location, salary and dates.' ),
				array( 'Will my jobs definitely appear in Google for Jobs?', 'Structured data makes a job eligible, but Google decides what it shows. Keep jobs current, fill in salary and location, and close filled roles so your listings stay trustworthy.' ),
				array( 'Do I still need an SEO plugin?', 'Not for jobs: Modern Job Board adds the job markup and archive titles itself. An SEO plugin is still useful for the rest of your site.' ),
				array( 'How can I check the markup?', 'Use the preview in the admin, or test any job URL with Google\'s Rich Results Test.' ),
			),
		),
		'custom-fields-imports-pro'        => array(
			'what_q'   => 'What are custom fields and imports?',
			'what_a'   => 'Custom fields let you add your own fields to job listings and job applications, built in the admin with no code. Imports bring jobs in from CSV or XML files or from remote XML feeds, once or on a schedule, and exports take jobs, applications or the whole board out again. Both are part of the Pro and Business plans.',
			'how_q'    => 'How do you set them up?',
			'steps'    => array(
				array( 'Add fields', 'In Modern Job Board → Custom Fields, add job or application fields: text, textarea, number, select or checkbox.' ),
				array( 'People fill them in', 'Job fields appear on the job submission form; application fields appear when candidates apply.' ),
				array( 'Import jobs', 'Under Tools, import from a CSV file, an XML file or a remote XML feed URL.' ),
				array( 'Keep feeds in sync', 'Add a remote feed to the scheduled imports and it is fetched automatically.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Custom fields builder', 'Job and application fields without code.' ),
				array( 'Five field types', 'Text, textarea, number, select and checkbox.' ),
				array( 'CSV import', 'Bring jobs in from a spreadsheet.' ),
				array( 'XML import', 'Upload a file or import from a remote URL.' ),
				array( 'Scheduled imports', 'Fetch remote XML feeds on a schedule.' ),
				array( 'CSV export', 'Export jobs and applications.' ),
				array( 'Full board XML backup', 'Download your board as one XML file.' ),
			),
			'plan_q'   => 'Which plan includes custom fields and imports?',
			'plan_a'   => 'Pro ($149 a year) and Business ($299 a year). They are not part of the Free plan. <a href="{pricing}">See pricing</a>.',
			'faq'      => array(
				array( 'Which field types can I add?', 'Text, textarea, number, select and checkbox, for both job listings and applications.' ),
				array( 'Can I import jobs from another job board?', 'Yes, if it can export CSV or XML or publish an XML feed. Upload the file or add the feed URL under Tools.' ),
				array( 'Can imports run automatically?', 'Yes. Add a remote XML feed to the scheduled imports and Modern Job Board fetches it on a schedule, so the board stays current without manual uploads.' ),
				array( 'How do I back up my job board?', 'Download the Full Board XML Backup under Tools. Keep your normal WordPress backups as well.' ),
			),
		),
		'rest-api-feeds-webhooks-business' => array(
			'what_q'   => 'Does Modern Job Board have a REST API?',
			'what_a'   => 'Yes. On the Business plan, Modern Job Board provides a public job search API (v1), an authenticated API for employers and candidates (v2), an XML job feed for aggregators, and outbound webhooks signed with HMAC-SHA256. Together they connect your board to an ATS, a CRM, a mobile app or job aggregators.',
			'how_q'    => 'How do webhooks work?',
			'steps'    => array(
				array( 'Add an endpoint', 'Under Settings → Integrations, enter the URL and an optional signing secret.' ),
				array( 'Something happens', 'An application is submitted or its status changes, or a job is submitted.' ),
				array( 'Your board sends it', 'The payload is signed with HMAC-SHA256 in the <code>X-MJB-Signature</code> header.' ),
				array( 'Failures are retried', 'A failed delivery is retried up to five times with exponential backoff.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Public job search API (v1)', '<code>GET /wp-json/mjb/v1/jobs</code> and a path-based search route, with paging headers.' ),
				array( 'Authenticated API (v2)', 'Applications, application status, analytics and the candidate profile under <code>/wp-json/mjb/v2/</code>.' ),
				array( 'XML job feed', 'Syndicate listings to aggregators from <code>/feed/job-listings</code>.' ),
				array( 'Webhooks', '<code>application.submitted</code>, <code>application.status_updated</code> and <code>job.submitted</code> events.' ),
				array( 'Signed and retried', 'HMAC-SHA256 signatures and a retry queue.' ),
				array( 'Recruiter MCP', 'AI assistant tools for employers; see <a href="{f:recruiter-mcp-business}">recruiter MCP</a>.' ),
				array( 'WordPress hooks', '<code>mjb_*</code> actions and filters for custom code, on every plan.' ),
			),
			'plan_q'   => 'Which plan includes the API and webhooks?',
			'plan_a'   => 'The Business plan, at $299 a year. WordPress actions and filters are available on every plan, so developers can extend the board with custom code on Free and Pro too. <a href="{pricing}">See pricing</a>.',
			'faq'      => array(
				array( 'How do I authenticate with the v2 API?', 'Use WordPress application passwords for the employer or candidate account. Employer routes accept the employer role or an administrator.' ),
				array( 'Can I send my jobs to aggregators?', 'Yes. Point the aggregator at the XML feed at /feed/job-listings.' ),
				array( 'How do I verify that a webhook came from my board?', 'Compute an HMAC-SHA256 of the raw request body with your signing secret and compare it with the X-MJB-Signature header.' ),
				array( 'What happens if my endpoint is down?', 'The delivery is queued and retried up to five times with exponential backoff.' ),
			),
		),
		'job-alerts-saved-jobs-talent'     => array(
			'what_q'   => 'What are job alerts?',
			'what_a'   => 'Job alerts email candidates when new jobs match a search they care about, as a daily or weekly digest. In Modern Job Board, candidates can also save jobs to come back to, and recruiters can browse a talent pool of candidates who chose to make their profiles public. All three are part of every plan.',
			'how_q'    => 'How do candidates set up an alert?',
			'steps'    => array(
				array( 'Search', 'Use the job search with any keywords and filters.' ),
				array( 'Subscribe', 'Choose Get Email Alerts under the search form.' ),
				array( 'Pick how often', 'Daily or weekly.' ),
				array( 'Come back', 'Each digest lists the new matching jobs, ready to open and apply.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Subscribe to any search', 'Turn the current filters into an alert in one step.' ),
				array( 'Daily or weekly digests', 'One email with every new match, not one email per job.' ),
				array( 'Saved jobs', 'Candidates bookmark jobs and find them in their dashboard.' ),
				array( 'Talent pool', 'Recruiters browse candidates who opted to make their profile public.' ),
				array( 'Access control', 'Choose who may browse the pool, from everyone to verified or paying recruiters.' ),
				array( 'Alert packages (Pro)', 'Sell extra alert slots to candidates through WooCommerce.' ),
			),
			'plan_q'   => 'Are job alerts free?',
			'plan_a'   => 'Yes. Alerts, saved jobs and the talent pool are part of every plan. Selling extra alert slots or paid talent access uses WooCommerce on Pro or Business. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'How often are job alerts sent?', 'Daily or weekly, as the candidate chooses. Each digest groups all new matching jobs into one email.' ),
				array( 'Do candidates need an account to save jobs?', 'Yes. Saved jobs live in the candidate dashboard, so candidates sign in to save them and find them again on any device.' ),
				array( 'Which candidates appear in the talent pool?', 'Only candidates who choose to make their profile public.' ),
				array( 'Can I limit who browses the talent pool?', 'Yes. Open it to everyone, registered members, recruiters, verified recruiters who have published a job, or recruiters with paid CV access only.' ),
			),
		),
		'jobs-map-promotions'              => array(
			'what_q'   => 'What is the jobs map?',
			'what_a'   => 'The jobs map shows your open jobs as markers on a Google Map, so candidates can see where the work is at a glance. Alongside it, Modern Job Board lets you showcase featured companies and place ad banners between job listings, and every job can be shared or applied for over WhatsApp. These features are part of every plan.',
			'how_q'    => 'How do you add the jobs map?',
			'steps'    => array(
				array( 'Add a Google Maps API key', 'Enter it under Modern Job Board → Settings.' ),
				array( 'Place the map', 'Add the <code>[mjb_jobs_map]</code> shortcode to any page.' ),
				array( 'Set a limit', 'Choose the maximum number of markers to show.' ),
				array( 'Add promotions', 'Set up ad banners and featured companies in the admin.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Multi-marker jobs map', 'Your published jobs on one map.' ),
				array( 'Maps on job pages', 'An optional map on each job page.' ),
				array( 'Featured companies', 'Showcase selected employers.' ),
				array( 'Ad banners', 'Place banners above the job list or between job cards, at positions you choose.' ),
				array( 'WhatsApp apply', 'Candidates can apply through WhatsApp.' ),
				array( 'Social sharing', 'Share options on every job.' ),
			),
			'plan_q'   => 'Is the jobs map free?',
			'plan_a'   => 'Yes. The map, featured companies, ad banners, WhatsApp apply and sharing are part of every plan. You need your own Google Maps API key, and Google may charge for map usage beyond its free allowance. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Do I need a Google Maps API key?', 'Yes. The map uses Google Maps, so add your own API key in the settings. Without one, the map shows a reminder to configure it.' ),
				array( 'Where can ad banners appear?', 'Above the job list or between job cards, at the positions you choose.' ),
				array( 'Can I sell banner and featured spots to sponsors?', 'Yes. You decide who appears in banners and featured company spots, so you can sell them directly to sponsors.' ),
				array( 'Can candidates share jobs?', 'Yes. Every job has share options, so candidates can pass it on to friends or post it on social media.' ),
			),
		),
		'admin-dashboard-jobs-console'     => array(
			'what_q'   => 'What does the Modern Job Board admin dashboard show?',
			'what_a'   => 'The Modern Job Board dashboard is the first screen in its wp-admin area. It shows the health of your board, the items that need your attention and all-time views across your listings, and it flags jobs nobody has viewed. The jobs console beside it lets you search, filter, act on and export listings in bulk. Both are part of every plan.',
			'how_q'    => 'How do you manage jobs in the console?',
			'steps'    => array(
				array( 'Find', 'Predictive search finds a job as you type.' ),
				array( 'Filter', 'Narrow the list with filters.' ),
				array( 'Act in bulk', 'Select jobs and apply a bulk action to all of them.' ),
				array( 'Export', 'Download the current view or your selection as CSV.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Board health checks', 'Confirms your board pages exist, structured data is active, a Maps key is set and search engines can index the site.' ),
				array( 'Open items', 'What is waiting for you, in one list.' ),
				array( 'All-time listing views', 'Total views, top jobs, and jobs with no views flagged.' ),
				array( 'One tabbed admin', 'Dashboard, Jobs, Applications, Companies, Resumes, Settings, Setup, Custom Fields and Tools.' ),
				array( 'Fast tabs', 'Each tab loads on demand, with paging on every data grid.' ),
				array( 'Setup wizard', 'Creates any missing board pages in one click.' ),
			),
			'plan_q'   => 'Is the admin dashboard included in the free plan?',
			'plan_a'   => 'Yes. The dashboard, the jobs console and its CSV export are in every plan. Custom fields and the import and export tools need Pro; see <a href="{f:custom-fields-imports-pro}">custom fields and imports</a>.',
			'faq'      => array(
				array( 'Why are views shown as all-time totals?', 'Each job keeps a running view count rather than a history, so the dashboard reports all-time views and flags jobs nobody has opened.' ),
				array( 'What does the health check look at?', 'Whether the job search, employer dashboard and post-a-job pages are published, whether JobPosting structured data is active, whether a Google Maps key is set and whether search engines may index the site.' ),
				array( 'Can I export jobs to CSV?', 'Yes. Export the current view or selected jobs from the jobs console.' ),
				array( 'How many jobs can I run on the free plan?', 'Up to 100 active listings; the dashboard shows how many you are using. Pro and Business are unlimited.' ),
			),
		),
		'company-pages-careers-mode'       => array(
			'what_q'   => 'What is careers mode?',
			'what_a'   => 'Careers mode turns Modern Job Board from a multi-employer job board into a single company\'s careers site. Candidates still browse, apply, save jobs and set alerts, while public recruiter sign-up, the talent pool and paid listings are switched off. In job board mode, every employer gets a public company page instead. Both modes are part of every plan.',
			'how_q'    => 'How do company pages work?',
			'steps'    => array(
				array( 'A recruiter adds their company', 'Name, tagline, description, website, social links and logo.' ),
				array( 'They publish a job', 'Every company with a published job appears in the directory at /jobs/companies/.' ),
				array( 'Candidates explore', 'A hover preview on job cards shows the company, and its page lists all of its open jobs.' ),
				array( 'Or switch to careers mode', 'One setting runs the site for a single company; switching back restores your earlier settings.' ),
			),
			'incl_q'   => 'What is included?',
			'included' => array(
				array( 'Company directory', '/jobs/companies/ lists every employer with a published job.' ),
				array( 'Company pages', 'A profile and the open jobs for each employer.' ),
				array( 'Hover preview', 'Company details on job cards without leaving the list.' ),
				array( 'Careers mode', 'A single-company careers site with one setting.' ),
				array( 'Recruiters by invitation', 'In careers mode, admins add recruiter accounts in WordPress; there is no public sign-up.' ),
				array( 'Reversible', 'Paid features\' settings are kept, so switching back restores them unchanged.' ),
			),
			'plan_q'   => 'Is careers mode free?',
			'plan_a'   => 'Yes. Company pages and careers mode are part of every plan, including Free. <a href="{pricing}">Compare plans</a>.',
			'faq'      => array(
				array( 'Can I use Modern Job Board as my company\'s careers page?', 'Yes. Switch the board type to careers site. Company names are hidden on job cards because every job is yours, and the company directory sends visitors to the jobs list.' ),
				array( 'What do candidates keep in careers mode?', 'Browsing, applying, saving jobs and job alerts all work as before.' ),
				array( 'Can recruiters still post jobs in careers mode?', 'Yes. Signed-in recruiters keep their dashboard and the post-a-job form; admins create their accounts in WordPress.' ),
				array( 'When does a company appear in the directory?', 'As soon as it has at least one published job.' ),
			),
		),
		'recruiter-mcp-business'           => array(
			'what_q'   => 'What is the recruiter MCP?',
			'what_a'   => 'The recruiter MCP is a Model Context Protocol endpoint, at POST /wp-json/mjb/v1/mcp, that lets a signed-in employer manage hiring through an AI assistant. The assistant can list and edit that employer\'s jobs, review and move their applications, update their company profile and check remaining credits, and nothing beyond that employer\'s own records. It is part of the Business plan.',
			'how_q'    => 'How does the recruiter MCP work?',
			'steps'    => array(
				array( 'The employer authenticates', 'Every call runs as a WordPress user with the employer role.' ),
				array( 'The assistant connects', 'It speaks JSON-RPC to the endpoint: <code>initialize</code>, <code>tools/list</code> and <code>tools/call</code>.' ),
				array( 'It calls tools', 'List jobs, read applications, set stages, add notes, draft outreach.' ),
				array( 'Limits are enforced', 'Other employers\' records, candidate logins and checkout are never available.' ),
			),
			'incl_q'   => 'Which tools are included?',
			'included' => array(
				array( 'Jobs', 'List, read, create, update, close and reopen the employer\'s own jobs.' ),
				array( 'Applications', 'List and read applications, set stages one at a time or in bulk, and add private notes.' ),
				array( 'Company and credits', 'Read and update the company profile; check the plan and remaining credits.' ),
				array( 'Analytics', 'All-time views, applications and apply rate per job, plus counts by stage.' ),
				array( 'Screening help', 'Score candidates by keyword overlap and analyze a job description.' ),
				array( 'Outreach and email', 'Draft outreach, and send interview, rejection or status emails that respect each candidate\'s email preferences.' ),
				array( 'Featuring', 'Feature a job by spending one credit.' ),
			),
			'plan_q'   => 'Which plan includes the recruiter MCP?',
			'plan_a'   => 'The Business plan, at $299 a year, together with the <a href="{f:rest-api-feeds-webhooks-business}">REST API, XML feed and webhooks</a>. <a href="{pricing}">See pricing</a>.',
			'faq'      => array(
				array( 'What is MCP?', 'The Model Context Protocol is an open standard that lets AI assistants call tools in other systems. Modern Job Board offers recruiter tools over it, so an assistant can act on an employer\'s behalf.' ),
				array( 'Can the assistant see other employers\' data?', 'No. Every tool is limited to the signed-in employer\'s jobs, the applications on those jobs and their company. Bulk changes skip other employers\' records.' ),
				array( 'Can the assistant read CVs?', 'Only CVs the candidate has shared, and only as plain text; PDF and Word files are not extracted.' ),
				array( 'Does it send emails on its own?', 'Outreach tools create drafts only. The email tool sends interview, rejection or status emails when it is called, and honors each candidate\'s email opt-out.' ),
			),
		),
	);
}

/**
 * Copy for the Features page: an answer before the grid, plans and FAQ after.
 *
 * @return array
 */
function mjb_theme_v1_features_index_copy() {
	return array(
		'what_q' => 'What is Modern Job Board?',
		'what_a' => 'Modern Job Board is a freemium WordPress job board plugin. It runs on your own WordPress site, keeps listings, applications and CVs on your own server, and covers the whole hiring flow: job posting, search, applications, employer and candidate dashboards, payments and integrations. Each feature below links to a page with the details.',
		'plan_q' => 'Which features are in each plan?',
		'plans'  => array(
			array( 'Plan', 'Price', 'What it includes' ),
			array( 'Free', '$0', 'The core board: posting, predictive search, accounts and dashboards, private CVs, alerts, maps, company pages, blocks, SEO and analytics. Up to 100 active jobs.' ),
			array( 'Pro', '$149 a year', 'Everything in Free with unlimited jobs, plus WooCommerce monetization, custom fields and CSV/XML import and export.' ),
			array( 'Business', '$299 a year', 'Everything in Pro, plus the REST API, XML job feed, webhooks and recruiter MCP.' ),
			array( 'Complete Site', '$1,199–1,999 setup', 'A full WordPress job board site built and configured for you, with one year of Business included, then $299 a year.' ),
		),
		'faq'    => array(
			array( 'Is Modern Job Board free?', 'Yes. The Free plan includes the core job board with up to 100 active jobs. Pro and Business are paid yearly plans that add monetization, data tools and integrations.' ),
			array( 'Do I need WooCommerce?', 'Only to take payments from employers or candidates, which is part of Pro and Business. Everything else works without it.' ),
			array( 'Where is my job board data stored?', 'On your own WordPress site and server. Listings, applications and CVs are not held on a third-party platform.' ),
			array( 'What do I need to run it?', 'WordPress 6.4 or later and PHP 7.4 or later. WooCommerce is optional.' ),
		),
	);
}

/**
 * Serialized core blocks used by the body sections.
 */
function mjb_theme_v1b_heading( $text, $anchor = '', $level = 2 ) {
	$tag   = 'h' . (int) $level;
	$attrs = 2 === (int) $level ? '' : ' {"level":' . (int) $level . '}';
	$id    = '' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

	return "<!-- wp:heading{$attrs} -->\n<{$tag} class=\"wp-block-heading\"{$id}>{$text}</{$tag}>\n<!-- /wp:heading -->";
}

function mjb_theme_v1b_paragraph( $html, $class = '' ) {
	if ( '' === $class ) {
		return "<!-- wp:paragraph -->\n<p>{$html}</p>\n<!-- /wp:paragraph -->";
	}

	return "<!-- wp:paragraph {\"className\":\"{$class}\"} -->\n<p class=\"{$class}\">{$html}</p>\n<!-- /wp:paragraph -->";
}

function mjb_theme_v1b_list( $items, $ordered, $class ) {
	$tag   = $ordered ? 'ol' : 'ul';
	$attrs = $ordered ? '{"ordered":true,"className":"' . $class . '"}' : '{"className":"' . $class . '"}';
	$lis   = array();

	foreach ( $items as $item ) {
		$lis[] = "<!-- wp:list-item -->\n<li><strong>{$item[0]}</strong> {$item[1]}</li>\n<!-- /wp:list-item -->";
	}

	return "<!-- wp:list {$attrs} -->\n<{$tag} class=\"wp-block-list {$class}\">" . implode( "\n\n", $lis ) . "</{$tag}>\n<!-- /wp:list -->";
}

function mjb_theme_v1b_details( $question, $answer ) {
	return "<!-- wp:details {\"className\":\"mjb-fb-faq__item\"} -->\n<details class=\"wp-block-details mjb-fb-faq__item\"><summary>{$question}</summary><!-- wp:paragraph -->\n<p>{$answer}</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->";
}

function mjb_theme_v1b_group( $class, $inner ) {
	return "<!-- wp:group {\"className\":\"{$class}\"} -->\n<div class=\"wp-block-group {$class}\">" . $inner . "</div>\n<!-- /wp:group -->";
}

function mjb_theme_v1b_table( $rows, $class ) {
	$head = '<thead><tr>' . implode( '', array_map( static function ( $cell ) {
		return '<th>' . $cell . '</th>';
	}, $rows[0] ) ) . '</tr></thead>';
	$body = '';

	foreach ( array_slice( $rows, 1 ) as $row ) {
		$body .= '<tr>' . implode( '', array_map( static function ( $cell ) {
			return '<td>' . $cell . '</td>';
		}, $row ) ) . '</tr>';
	}

	return "<!-- wp:table {\"className\":\"{$class}\"} -->\n<figure class=\"wp-block-table {$class}\"><table class=\"has-fixed-layout\">{$head}<tbody>{$body}</tbody></table></figure>\n<!-- /wp:table -->";
}

/**
 * The answer box that opens a page: question heading plus a direct answer.
 */
function mjb_theme_v1b_answer( $question, $answer ) {
	return mjb_theme_v1b_group(
		'mjb-fb-answer',
		mjb_theme_v1b_heading( $question, 'overview' ) . "\n\n" . mjb_theme_v1b_paragraph( $answer )
	);
}

function mjb_theme_v1b_faq( $items ) {
	$inner = mjb_theme_v1b_heading( 'Frequently asked questions', 'faq' );

	foreach ( $items as $item ) {
		$inner .= "\n\n" . mjb_theme_v1b_details( $item[0], $item[1] );
	}

	return mjb_theme_v1b_group( 'mjb-fb-section mjb-fb-faq', $inner );
}

/**
 * Body blocks for a feature page ('' when there is no copy for the slug).
 *
 * @param string $slug Feature slug.
 * @return string
 */
function mjb_theme_v1_feature_body_blocks( $slug ) {
	$all = mjb_theme_v1_feature_body_copy();

	if ( ! isset( $all[ $slug ] ) ) {
		return '';
	}

	$c        = $all[ $slug ];
	$sections = array(
		mjb_theme_v1b_answer( $c['what_q'], $c['what_a'] ),
		mjb_theme_v1b_group( 'mjb-fb-section', mjb_theme_v1b_heading( $c['how_q'], 'how-it-works' ) . "\n\n" . mjb_theme_v1b_list( $c['steps'], true, 'mjb-fb-steps' ) ),
		mjb_theme_v1b_group( 'mjb-fb-section', mjb_theme_v1b_heading( $c['incl_q'], 'whats-included' ) . "\n\n" . mjb_theme_v1b_list( $c['included'], false, 'mjb-fb-checklist' ) ),
		mjb_theme_v1b_group( 'mjb-fb-section mjb-fb-plan', mjb_theme_v1b_heading( $c['plan_q'], 'plans' ) . "\n\n" . mjb_theme_v1b_paragraph( $c['plan_a'] ) ),
		mjb_theme_v1b_faq( $c['faq'] ),
	);

	return mjb_theme_body_links( mjb_theme_v1b_group( 'mjb-feature-body', implode( "\n\n", $sections ) ) );
}

/**
 * Features page: the answer that goes before the grid.
 *
 * @return string
 */
function mjb_theme_v1_features_index_intro_blocks() {
	$c = mjb_theme_v1_features_index_copy();

	return mjb_theme_body_links( mjb_theme_v1b_group( 'mjb-feature-body mjb-feature-body--intro', mjb_theme_v1b_answer( $c['what_q'], $c['what_a'] ) ) );
}

/**
 * Features page: plans table and FAQ, after the grid.
 *
 * @return string
 */
function mjb_theme_v1_features_index_after_blocks() {
	$c     = mjb_theme_v1_features_index_copy();
	$plans = mjb_theme_v1b_group(
		'mjb-fb-section mjb-fb-plans',
		mjb_theme_v1b_heading( $c['plan_q'], 'plans' ) . "\n\n" . mjb_theme_v1b_table( $c['plans'], 'mjb-fb-table' ) . "\n\n" . mjb_theme_v1b_paragraph( '<a href="{pricing}">See full pricing</a>' )
	);

	return mjb_theme_body_links( mjb_theme_v1b_group( 'mjb-feature-body mjb-feature-body--after', $plans . "\n\n" . mjb_theme_v1b_faq( $c['faq'] ) ) );
}
