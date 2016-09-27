=== ZodiacPress  ===
Contributors: isabel104
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40isabelcastillo%2ecom
Tags: zodiacpress, zodiac, astrology, natal report, natal reports, birth report, birth reports, astrology reports, horoscope
Requires at least: 3.7
Tested up to: 4.6.1
Stable tag: 1.0
License: GNU GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate astrology birth reports with your custom interpretations.

== Description ==

ZodiacPress is the first WordPress plugin that lets you generate astrology birth reports with your custom interpretations, directly on your site. 

This is **not** an embedded script that pulls astrology data from another astrology site. ZodiacPress turns your site into an astrology report press. Your astrology interpretations reside on your own site, and the reports are created on your own site. The Swiss Ephemeris is included inside.

The birth report includes three parts: 

1. Planets and Points in The Signs
2. Planets and Points in The Houses
3. Aspects

You can choose which planets and aspects to include in the birth report.

You can add an optional Intro and Closing to the birth report.

You have the option to allow people with unknown birth times to generate basic natal reports. These reports with unknown times would omit time-sensitive points (i.e. Moon, Ascendant, etc.) and the Houses section.

The Planets in Houses section of the report will tell you if you have a planet in one house, but conjunct the next house (within 1.5 degrees orb; orb can be modified with a filter).

**Technical Details**

ZodiacPress gets birth place latitude/longitude coordinates from the GeoNames geographical database which uses the latest revision of World Geodetic System (WGS 84). 

ZodiacPress uses the Swiss Ephemeris (under GNU GPLv2) to get the longitude of the planets/celestial bodies.

ZodiacPress uses the tropical zodiac.


**Internationalization**

Much effort has been made to internationalize even the digits (numbers, years, and other integers in the plugin). On the birth report form, the month and day fields will switch places according to your date settings. Suggestions regarding i18n are welcome.

**Important Note For Sites That Use Windows Hosting**

If your website uses Windows hosting, you'll need to use the [ZodiacPress Windows Server](https://cosmicplugins.com/downloads/zodiacpress-windows-server/ "ZodiacPress Windows Server") plugin for the birth reports to be generated correctly.

See the full [ZodiacPress documentation](https://cosmicplugins.com/docs/category/zodiacpress/ "ZodiacPress documentation").

== Installation ==

**Install and Activate**

1. Install and activate the plugin in your WordPress dashboard by going to Plugins –> Add New. 
2. Search for “ZodiacPress” to find the plugin.
3. When you see ZodiacPress, click “Install Now” to install the plugin.
4. Click “Activate” to activate the plugin.

**Quick Setup**

1. In your WordPress dashboard, go to ZodiacPress –> Settings, and click the Misc tab. 
2. Enter your GeoNames Username and click “Save Changes.” You can quickly create a free [GeoNames account here](http://www.geonames.org/login). This is required because the plugin uses Geonames webservice to get birth place latitude/longitude coordinates and timezone ids for the birth reports.
3. Add the `[birthreport]` shortcode to a page or post. This is where the birth report form will appear. Go to this page on the front of your site to generate a birth report.

That’s it for the Quick Setup. This allows you to generate a basic report which lists the planets in the signs, planets in the houses, and aspects. Interpretations will not be included in the report until you enter your own natal interpretations. 

To enter your interpretations, go to “ZodiacPress” in your dashboard menu. See the [Full Setup Guide](https://cosmicplugins.com/docs/full-setup-guide/ "ZodiacPress Documentation") for important options.

**If your website uses Windows hosting**

If your website is running on a Windows operating system (i.e. using Windows hosting), then you’ll need to use the [ZodiacPress Windows Server](https://cosmicplugins.com/downloads/zodiacpress-windows-server/) plugin to make the Ephemeris work on your server. This is because the ephemeris included in ZodiacPress will not run on Windows, by default. Just install and activate the “ZodiacPress Windows Server” plugin, and it will automatically solve this problem.

== Frequently Asked Questions ==

= Why is the birth report not working? =

See these [troubleshooting articles](https://cosmicplugins.com/docs/category/zodiacpress/troubleshooting/ "Troubleshooting ZodiacPress").

= What house system is used for the "Planets in Houses" section of the report? =

The Placidus House System is used. To change the house system, you need the [ZP House Systems](https://cosmicplugins.com/downloads/zodiacpress-house-systems/ "ZP House Systems") extension.

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

= 1.0 =
* Initial public release.

== Upgrade Notice ==

= 1.0 =
* Initial public release.
