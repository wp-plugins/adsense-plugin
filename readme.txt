=== Google AdSense by BestWebSoft ===
Contributors: bestwebsoft
Donate link: http://bestwebsoft.com/donate/
Tags: google, adsense, bestwebsoft, google adsense, google plugin, adsense plugin, ads plugin, gogle, ad, ads, adds, ad banner, ad block, ad color, ads display, ad format, ads in widgets, ad links block, add several adds, ads on website, ad parameters, ad type, advertisements, Google ads, Google AdSense, Google AddSense, Goggle AdSense, Gogle AdSense, image, insert ads, insert ads automatically, insert Google ads, text ads, text and image ads. 
Requires at least: 3.3
Tested up to: 4.2.4
Stable tag: 1.36
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows Google AdSense implementation into your website.

== Description ==

Google AdSense Plugin creates blocks to display ads on your website. It allows to customize the ads displaying, such as format (text ad, image, text with an image or link), size, color of the elements in the ad block, rounded corners and the ad block position on the website. It provides possibility to make ads unique and original.

http://www.youtube.com/watch?v=Z4KUyT4puSo

<a href="http://www.youtube.com/watch?v=Nkp267vxZ84" target="_blank">Video instruction on Installation</a>

<a href="http://wordpress.org/plugins/adsense-plugin/faq/" target="_blank">FAQ</a>

<a href="http://support.bestwebsoft.com" target="_blank">Support</a>

<a href="http://bestwebsoft.com/products/google-adsense/?k=b68fe7a44579f45545bd6e7556143e9a" target="_blank">Upgrade to Pro Version</a>

= Features =

* Display: Customize the way the ads look, choose a color scheme, layout, number of ad units per page.
* Display: Insert Google ads into the widget.
* Actions: Insert Google ads into the website automatically.

= Recommended Plugins =

The author of the Google AdSense also recommends the following plugins:

* <a href="http://wordpress.org/plugins/updater/">Updater</a> - This plugin updates WordPress core and the plugins to the recent versions. You can also use the auto mode or manual mode for updating and set email notifications.
There is also a premium version of the plugin <a href="http://bestwebsoft.com/products/updater-pro/?k=9bfbc38d14047beca03dbc74f96cc135">Updater Pro</a> with more useful features available. It can make backup of all your files and database before updating. Also it can forbid some plugins or WordPress Core update.

= Translation =

* Russian (ru_RU)
* Ukrainian (uk)

If you create your own language pack or update the existing one, you can send <a href="http://codex.wordpress.org/Translating_WordPress" target="_blank">the text in PO and MO files</a> for <a href="http://support.bestwebsoft.com" target="_blank">BestWebSoft</a> and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO files <a href="http://www.poedit.net/download.php" target="_blank">Poedit</a>.

= Technical support =

Dear users, our plugins are available for free download. If you have any questions or recommendations regarding the functionality of our plugins (existing options, new options, current issues), please feel free to contact us. Please note that we accept requests in English only. All messages in another languages won't be accepted.

If you notice any bugs in the plugins, you can notify us about it and we'll investigate and fix the issue then. Your request should contain URL of the website, issues description and WordPress admin panel credentials.
Moreover we can customize the plugin according to your requirements. It's a paid service (as a rule it costs $40, but the price can vary depending on the amount of the necessary changes and their complexity). Please note that we could also include this or that feature (developed for you) in the next release and share with the other users then. 
We can fix some things for free for the users who provide translation of our plugin into their native language (this should be a new translation of a certain plugin, you can check available translations on the official plugin page).

== Installation ==

1. Upload the folder `adsense-plugin` to the directory `/wp-content/plugins/`.
2. Activate the plugin via the 'Plugins' menu in WordPress.
3. The plugin settings are located in "BWS Plugins"->"AdSense".

<a href="https://docs.google.com/document/d/1P-Jb5oYadIAsJz63wbsppxhOnCX-Z27S3XzE6HNcrbI/edit" target="_blank">View a Step-by-step Instruction on AdSense Installation</a>.

http://www.youtube.com/watch?v=Nkp267vxZ84

== Frequently Asked Questions ==

= How many ad blocks can be added to the page? =

The maximum number of ad blocks on the page cannot be more than 3 - https://support.google.com/adsense/answer/1346295?hl=en#Ad_limit_per_page.

= Why I cannot choose more than one ad block in the widget tab? =

This limitation is caused by the maximum allowable number of ad blocks to be displayed on the page. Ad blocks display can only be set in the post; in this case, it will not be displayed on the page. However, a widget is usually displayed in every post and every page.

= Ads are not displayed =

1. Please make sure Adblocker (or some other similar extensions that block ads) is disabled in the browser
2. Please make sure that your theme contains the hooks 'the_content' for the ads displaying. The plugin will not wok without such hooks.
If you do not know how to do it please install a standard WordPress theme and check if the ads will be displayed or not, if yes it means that there aren't the necessary hooks in your theme.
3. Probably you did not set up 'google ads' account

= What should i do if the plugin is not displayed in the plugins list or the tab with the settings page is not displayed either? =

If you have an extention in your browser that hides ads (e.g. AdBlock or something like that) - it can hide AdSense displaying on this page, as it is set to search by words like AdSense and similar. 
Please make sure Adblocker (or some other similar extensions that block ads) is disabled in the browser

= I have some problems with the plugin's work. What Information should I provide to receive proper support? =

Please make sure that the problem hasn't been discussed yet on our forum (<a href="http://support.bestwebsoft.com" target="_blank">http://support.bestwebsoft.com</a>). If no, please provide the following data along with your problem's description:

1. the link to the page where the problem occurs
2. the name of the plugin and its version. If you are using a pro version - your order number.
3. the version of your WordPress installation
4. copy and paste into the message your system status report. Please read more here: <a href="https://docs.google.com/document/d/1Wi2X8RdRGXk9kMszQy1xItJrpN0ncXgioH935MaBKtc/edit" target="_blank">Instuction on System Status</a>

== Screenshots ==

1. Google AdSense Settings page (Unauthorized in Google AdSense).
2. Google AdSense Settings page (Authorized in Google AdSense).

== Changelog ==

= V1.36 - 14.08.2015 =
* Bugfix : We fixed error when getting ad blocks from Google AdSense.
* Update : BWS plugins section is updated.

= V1.35 - 10.07.2015 =
* Update : We updated the plugin to use Google AdSense API.

= V1.34 - 08.06.2015 =
* NEW : We added functionality for the remote reception of Publisher ID from the Google AdSense.

= V1.33 - 29.04.2015 =
* Bugfix : Plugin optimization is done.
* Update : We updated all functionality for wordpress 4.2.1.

= V1.32 - 24.02.2015 =
* Update : BWS plugins section is updated.
* Update : We updated all functionality for wordpress 4.1.1.

= V1.31 - 26.12.2014 =
* Update : The Hebrew, Turkish, Dutch and Brazilian Portuguese language files are updated.

= V1.30 - 08.08.2014 =
* Bugfix : Security Exploit was fixed.

= V1.29 - 13.05.2014 =
* Update : The Ukrainian language is updated in the plugin.
* Update : We updated all functionality for wordpress 3.9.1.
* Bugfix: Problem with amount of ads on settings page is fixed.

= V1.28 - 11.04.2014 =
* Update : BWS plugins section is updated.
* Update : The Italian language file is added to the plugin.
* Update : We updated all functionality for wordpress 3.8.2.
* Bugfix : Plugin optimization is done.

= V1.27 - 07.02.2014 =
* Update : Screenshots are updated.
* Update : BWS plugins section is updated.
* Update : We updated all functionality for wordpress 3.8.1.

= V1.26 - 25.12.2013 =
* Update : BWS plugins section is updated.
* Update : We updated all functionality for wordpress 3.8.
* Update : The Indonesian language file is added to the plugin.

= V1.25 - 12.11.2013 =
* NEW : Add checking installed wordpress version.
* Update : We updated all functionality for wordpress 3.7.1.
* Bugfix : Bug of displaying missing global $adsns_count in adsns_end_footer_ad() function is fixed.

= V1.24 - 11.10.2013 =
* NEW : The Turkish language file is added to the plugin.
* NEW : Added an ability to use plugin when ad client id doesn't start with 'pub-'.

= V1.23 - 02.10.2013 =
* Update : We updated all functionality for wordpress 3.6.1.
* NEW : The Ukrainian language file is added to the plugin.

= V1.22 - 04.09.2013 =
* Update : We updated all functionality for wordpress 3.6.
* Update : Function for displaying BWS plugins section placed in a separate file and has own language files.

= V1.21 - 24.07.2013 =
* Bugfix : Bugs of not displaying ads on costum frontend page is fixed.

= V1.20 - 18.07.2013 =
* NEW : Added an ability to view and send system information by mail.
* Update : We updated all functionality for wordpress 3.5.2.

= V1.19 - 29.05.2013 =
* Update : BWS plugins section is updated. 

= V1.18 - 25.04.2013 =
* Update : The French language is updated in the plugin.

= V1.17 - 17.04.2013 =
* Update : The English language is updated in the plugin.

= V1.16 - 25.02.2013 =
* Update : The German language file is updated in the plugin.

= V1.15 - 18.02.2013 =
* Update : The Spanish language file is updated in the plugin.

= V1.14 - 08.02.2013 =
* NEW : We added explanations at the settings page of the plugin.

= V1.13 - 31.01.2013 =
* Bugfix : Bugs in admin menu were fixed.
* Update : We updated all functionality for wordpress 3.5.1.

= V1.12 - 21.12.2012 =
* Bugfix : Ads displaying on the frontend with Single page option was fixed.
* Update : We updated all functionality for wordpress 3.5.

= V1.11 - 26.11.2012 =
* NEW : The option for the displaying of google ads has been implemented using the widget.

= V1.10 - 03.08.2012 =
* NEW : Polish and Spanish language files are added to the plugin.

= V1.9 - 24.07.2012 =
* Bugfix : Cross Site Request Forgery bug was fixed. 

= V1.8 - 09.07.2012 =
* NEW : The Hebrew language file is added to the plugin.
* Bugfix : Ads displaying on the frontend with Single page and Home page option was fixed. 

= V1.7 - 27.06.2012 =
* Update : We updated all functionality for wordpress 3.4.

= V1.6 - 11.06.2012 =
* NEW : The Brazilian Portuguese language file is added to the plugin.

= V1.5 - 12.03.2012 =
* Change :  The name of a variable for plugin options is replaced with unique name so that the name of this variable is not in common with names of variables of other plugins and wordpress.

= V1.4 - 24.02.2012 =
* Change : Code that is used to connect styles and scripts is added to the plugin for correct SSL verification.

= V1.3 - 31.01.2012 =
* NEW : The Dutch language file is added.

= V1.2 - 27.01.2012 =
* Bugfix : Settings and ads displaying are saved on the frontend. 

= V1.1 - 29.12.2011 =
* Changed : BWS plugin's section. 
* Changed : Default plugin's settings.

= V1.0 - 27.12.2011 =
* NEW : All words are added to the language file.

= V0.53 - 24.11.2011 =
* NEW : Settigns for color shift of the elements at the ad block are added.

= V0.52 =
* NEW : Parameters of ads display are added.

= V0.51 =
* Changed : Usability at the settings page of the plugin was improved.

== Upgrade Notice ==

= V1.36 =
We fixed error when getting ad blocks from Google AdSense. BWS plugins section is updated.

= V1.35 =
We updated the plugin to use Google AdSense API.

= V1.34 =
We added functionality for the remote reception of Publisher ID from the Google AdSense.

= V1.33 =
Plugin optimization is done. We updated all functionality for wordpress 4.2.1.

= V1.32 =
BWS plugins section is updated. We updated all functionality for wordpress 4.1.1.

= V1.31 =
The Hebrew, Turkish, Dutch and Brazilian Portuguese language files are updated.

= V1.30 =
Security Exploit was fixed.

= V1.29 =
The Ukrainian language is updated in the plugin. We updated all functionality for wordpress 3.9.1. Problem with amount of ads on settings page is fixed.

= V1.28 =
BWS plugins section is updated. The Italian language file is added to the plugin. We updated all functionality for wordpress 3.8.2. Plugin optimization is done.

= V1.27 =
Screenshots are updated. BWS plugins section is updated. We updated all functionality for wordpress 3.8.1.

= V1.26 =
BWS plugins section is updated. We updated all functionality for wordpress 3.8. The Indonesian language file is added to the plugin.

= V1.25 =
Add checking installed wordpress version. We updated all functionality for wordpress 3.7.1. Bug missing global $adsns_count in adsns_end_footer_ad() function is fixed.

= V1.24 =
The Turkish language file is added to the plugin. Added an ability to use plugin when ad client id doesn't start with 'pub-'.

= V1.23 =
We updated all functionality for wordpress 3.6.1. The Ukrainian language file is added to the plugin.

= V1.22 =
We updated all functionality for wordpress 3.6. Function for displaying BWS plugins section placed in a separate file and has own language files.

= V1.21 =
Bugs of not displaying ads on costum frontend page is fixed.

= V1.20 =
Added an ability to view and send system information by mail. We updated all functionality for wordpress 3.5.2.

= V1.19 =
BWS plugins section is updated.

= V1.18 =
The French language is updated in the plugin.

= V1.17 =
The English language is updated in the plugin.

= V1.16 =
The German language file is updated in the plugin.

= V1.15 =
The Spanish language file is updated in the plugin.

= V1.14 =
We added explanations at the settings page of the plugin.

= V1.13 =
Bugs in admin menu were fixed. We updated all functionality for wordpress 3.5.1.

= V1.12 =
Ads displaying on the frontend with Single page option was fixed. We updated all functionality for wordpress 3.5.

= V1.11 =
The option for the displaying of google ads has been implemented using the widget.

= V1.10 =
Polish and Spanish language files was added to the plugin.

= V1.9 - 24.07.2012 =
Cross Site Request Forgery bug was fixed. 

= V1.8 =
The Hebrew language file is added to the plugin. Ads displaying on the frontend with Single page and Home page option was fixed. 

= V1.7 =
We updated all functionality for wordpress 3.4.

= V1.6 =
The Brazilian Portuguese language file is added to the plugin.

= V1.5 =
The name of a variable for plugin options is replaced with unique name so that the name of this variable is not in common with the names of the variables of other plugins and wordpress.

= V1.4 =
Code that is used to connect styles and scripts is added to the plugin for correct SSL verification.

= V1.3 =
The Dutch language file is added.

= V1.2 =
A bug with saving settings and ads displaying on the frontend was fixed in this version. Please upgrade plugin immediately. Thank you

= V1.1 - 29.12.2011 =
BWS plugin's section was changed. Default plugin's settings were changed. 

= V1.0 - 27.12.2011 =
All words are added in language file.

= V0.53 =
Settigns for color shift of the elements at the ad block are added.

= V0.52 =
Ads displaying parameters are added.

= V0.51 =
Usability of the plugin's settings page was improved.
