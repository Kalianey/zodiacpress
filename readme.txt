=== ZodiacPress ===
Contributors: isabel104
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=B4ZUZQKG2M58G&lc=US&no_note=1&no_shipping=1&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: zodiacpress, zodiac, astrology, horoscope, natal report, birth report, birth reports, astrology reports, sidereal
Requires at least: 3.7
Tested up to: 4.7.1
Stable tag: 1.5.2
License: GNU GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate astrology birth reports with your custom interpretations.

== Description ==

ZodiacPress is the first WordPress plugin that lets you generate astrology birth reports with your custom interpretations, directly on your site. 

This is **not** an embedded script that pulls astrology data from another astrology site. ZodiacPress turns your site into an astrology report press. Your astrology interpretations reside on your own site, and the reports are created on your own site. The Swiss Ephemeris is included inside. Also includes Sidereal zodiac options.

The birth report includes three parts: 

1. Planets and Points in The Signs
2. Planets and Points in The Houses
3. Aspects

You can choose which planets and aspects to include in the birth report.

You can choose to add a chart wheel drawing to the report.

Tropical zodiac is the default, but you can choose to use the Sidereal Zodiac. Choose from 4 sidereal methods: Hindu/Lahiri, Fagan/Bradley, Raman, or Krishnamurti.

You can set a house system to be used for the report. The default is Placidus, but you can choose from 12 house systems.

You can add an optional Intro and Closing to the birth report.

You have the option to allow people with unknown birth times to generate basic natal reports. These reports with unknown times would omit time-sensitive points (i.e. Moon, Ascendant, etc.) and the Houses section.

The Planets in Houses section of the report will tell you if you have a planet in one house, but conjunct the next house (within 1.5 degrees orb; orb can be modified with a filter).

**Interpretations Are Optional**

Entering your interpretations is not required since you can generate reports without interpretations text. See the [screenshots](https://wordpress.org/plugins/zodiacpress/screenshots/) to see how a basic report **without** interpretations text looks.

**Technical Details**

You get granular control over aspect orbs. It lets you assign different orbs for each planet and each type of aspect.

If birth time is unknown, ZP checks for ingress on that day rather than simply using the planet's noon position. If an ingress occurs at any time on the that day, it lets the person know that the planet changed signs on that day, and from which sign to which it changed.

ZodiacPress gets birth place latitude/longitude coordinates from the GeoNames geographical database which uses the latest revision of World Geodetic System (WGS 84). 

ZP uses the Swiss Ephemeris (under GNU GPLv2) to get the longitude of the planets/celestial bodies.

**Internationalization**

Much effort has been made to internationalize even the digits (numbers, years, and other integers in the plugin). On the birth report form, the month and day fields will switch places according to your date settings. Suggestions regarding i18n are welcome.

**Important Note For Sites That Use Windows Hosting**

If your website uses Windows hosting, you'll need to use the [ZodiacPress Windows Server](https://cosmicplugins.com/downloads/zodiacpress-windows-server/ "ZodiacPress Windows Server") plugin for the birth reports to be generated correctly.

See the full [ZodiacPress documentation](https://cosmicplugins.com/docs/category/zodiacpress/ "ZodiacPress documentation").

**Support**

Need help? I'm glad to help via the plugin's official support forum (link above).

== Installation ==

**Install and Activate**

1. Install and activate the plugin in your WordPress dashboard by going to Plugins –> Add New. 
2. Search for “ZodiacPress” to find the plugin.
3. When you see ZodiacPress, click “Install Now” to install the plugin.
4. Click “Activate” to activate the plugin.

**Quick Setup**

1. In your WordPress dashboard, go to ZodiacPress –> Settings, and click the Misc tab. 
2. Enter your GeoNames Username and click “Save Changes.” You can quickly create a free [GeoNames account here](http://www.geonames.org/login). This is required because the plugin uses GeoNames webservice to get birth place latitude/longitude coordinates and timezone ids for the birth reports. After you create your GeoNames account, you have to enable their free web services by going to their [manage account page](http://www.geonames.org/manageaccount) and click to enable them at the bottom where it says "Free Web Services." (Optional: if you have a Premium GeoNames account, then you should take advantage of [Enhanced GeoNames](https://cosmicplugins.com/downloads/zodiacpress-enhanced-geonames/).)
3. Add the `[birthreport]` shortcode to a page or post. This is where the birth report form will appear. Go to this page on the front of your site to generate a birth report.

That’s it for the Quick Setup. This allows you to generate a basic report which lists the planets in the signs, planets in the houses, and aspects. Interpretations will not be included in the report until you enter your own natal interpretations. 

To enter your interpretations, go to “ZodiacPress” in your dashboard menu. See the [Full Setup Guide](https://cosmicplugins.com/docs/full-setup-guide/ "ZodiacPress Documentation") for important options.

**If your website uses Windows hosting**

If your website is running on a Windows operating system (i.e. using Windows hosting), then you'll need to use the [ZodiacPress Windows Server](https://cosmicplugins.com/downloads/zodiacpress-windows-server/) plugin to make the Ephemeris work on your server. This is because the ephemeris included in ZodiacPress will not run on Windows, by default. Just install and activate the “ZodiacPress Windows Server” plugin, and it will automatically solve this problem.

== Frequently Asked Questions ==

= Why is the birth report not working? =

See these [troubleshooting articles](https://cosmicplugins.com/docs/category/zodiacpress/troubleshooting/ "Troubleshooting ZodiacPress").

= How can I set the house system to be used for the "Planets in Houses" section of the report? =

The Placidus House System is used by default. To change the house system, you can either use the [ZP House Systems](https://cosmicplugins.com/downloads/zodiacpress-house-systems/ "ZP House Systems") extension, or [set the house system](https://cosmicplugins.com/docs/choose-a-house-system/) directly in the shortcode.

= How can I get help or support? =

I'm happy to help via the plugin's official support forum (link above).

= How can I give back? =

Please [rate](https://wordpress.org/support/plugin/zodiacpress/reviews/) the plugin. Thank you.

== Screenshots ==

1. This is how the Planets in Signs part of the report will look with interpretations.
2. This is how the Planets in Signs part of the report looks if you don't enter any interpretations.
3. This is how the Planets in Houses will look with interpretations.
4. This is how the Planets in Houses looks if you don't enter any interpretations.
5. This is how the Aspects section of the report will look with interpretations.
6. This is how the Aspects section looks if you don't enter any interpretations.
7. The ZodiacPress admin page where you enter and save your custom natal interpretations
8. The form to generate a birth report. The month and day fields will switch places according to your local date settings.
 
== Changelog ==

= 1.5.2 =
* Tweak - Escaped the chart drawing image src url.
* Tweak - Sanitized the chart drawing image element in the customizer with wp_kses_post.
* Tweak - Removed the site URL from System Info to make the System Info completely anonymous so that people who need support for this plugin will feel comfortable posting this info into the support forum. This allows for faster and more productive support.

= 1.5.1 = 
* Tweak - Improved form button styles for themes that do not already add cursor:pointer style to submission buttons. Also, the submit button will appear grayed out while it's not ready to be submitted.

= 1.5 =
* New - You can add a chart wheel to the birth report, either above or below the report. See https://cosmicplugins.com/docs/add-chart-wheel-birth-report/. The chart wheel colors can be changed in the WordPress Customizer, with the ability to preview the color changes on a sample chart wheel image right in the customizer.
* New - Added CSS styles for the form input:focus to highlight the input field that is being entered. This makes for a better user experience while filling out the form.
* New - Notify the user if JavaScript is disabled in their browser since the form will not work if Javascript is disabled.
* New - Updated the birth report form to accept a date with the year 2018.
* API - Removed the deprecated ZP_Chart::query_ephemeris method. Use the ZP_Ephemeris class instead.

= 1.4.1 =
* Tweak - Add disabled button CSS styles for themes that may not have any.

= 1.4 =
* New - Improved city field response. The Next/Submit button will be disabled until it is really ready. Previously, clicking Next too early would give a 'Please select a Birth City' error. This is because some things are happening in the background, for example, grabbing the city coordinates and timezone ID. If the background processes are not complete, you get an error. This problem should be greatly reduced now since the Next button will only be clickable when the background processes are complete.
* New - Add support for the Enhanced GeoNames extension which sends requests to GeoNames webservices from the browser rather than from the server side. This extension makes the city field and Next button faster and better.
* Fix - Only the caption should be bold in the report header data box, not the whole header data.
* Fix - Do not show Universal Time (GMT) on report header if birth time is unknown.
* Tweak - Better form styling and form fields alignment.
* API - Added $unknown_time property to ZP_Chart class to tell whether this report request was submitted with an unknown birth time.
* API - Added 3rd parameter to zp_report_header filter. The 3rd parameter is the chart object.
* API - ZP_Chart::cusps property is now public.
* API - The 2 functions, zp_extract_whole_degrees() and zp_extract_whole_minutes() have been merged into one function, zp_extract_degrees_parts(), that returns an array of the whole degrees and whole minutes.

= 1.3 =
* New - Option to use the Sidereal zodiac. Choose from 4 sidereal methods - Hindu/Lahiri, Fagan/Bradley, Raman, or Krishnamurti.
* New - You can now set the house system to be used in the shortcode. 12 house systems are included.
* New - The report header will now show which zodiac is used, whether Tropical or Sidereal.
* New - The report header will now show Universal Time in addition to the local time formats.
* New - If birth time is unknown, check for ingress on that day. Let the person know that the planet changed signs on that day, and from which sign to which it changed.
* New - Add filter to omit name field on form.
* New - Allow Start Over link to be removed with a filter.
* New - Added a Feedback link in the ZP admin.
* New - New ZP_Ephemeris class to query the Swiss Ephemeris to separate this from the ZP_Chart class. The ZP_Chart::query_ephemeris method is deprecated. Use the new ZP_Ephemeris instead
* Fix - the Birth City field was broken and/or missing many cities because urlencode() was breaking the autocomplete cities list.
* Tweak - Update Lilith's label to Black Moon Lilith.
* Tweak - Simplified form no longer shows coordinates.
* Tweak - Force the PHP mktime() function to use UTC when creating the unix timestamp for the chart since mktime() uses whatever zone its server wants. This is to prevent giving bad times in case some server is not using UTC.

= 1.2 =
* New - Added granular control over orbs. Custom orbs can now be set per each type of aspect and per each planet.
* Fix - Birth report was not working on https/SSL/encrypted pages. The free Geonames webservices only serves over http. The call to Geonames is now made from the server side, rather than in the browser, to support https/SSL.
* Fix - Orb setting was stuck on 8 even if a custom orb was set.
* Maintenance - Updated the .pot language file.

= 1.0 =
* Initial public release.

== Upgrade Notice ==

= 1.5.1 =
Improved form button styles.

= 1.5 =
New - You can add a chart wheel to the birth report.

= 1.4.1 =
Improved city field response on the form.

= 1.4 =
Improved city field response on the form.

= 1.3 =
Fixes the Birth City field.

= 1.2 =
NEW - Orb controls. FIX - Now works on https/SSL encrypted pages.

= 1.0 =
* Initial public release.
