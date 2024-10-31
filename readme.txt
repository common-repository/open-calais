=== Open Calais ===
Contributors: jtroynousky
Tags: tag
Requires at least: 4.6
Tested up to: 5.3.2
Stable tag: 1.2.5
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Send your post content to Refinitiv's Open Calais Intelligent Tagging API to get suggested tags you can use in the CMS.

== Installation ==

Before installation, you will need to register with Open Calais to get a free API key: https://developers.refinitiv.com/open-permid/intelligent-tagging-restful-api

After activating Open Calais on the Plugins page, you can configure it by accessing the Open Calais options page under the Settings submenu.

Here, you can input the API key you've received as well as decide if you want Open Calais to return only tags that exist in your CMS, or if you'd like to receive suggestions for new tags.

== Privacy ==

This plugin relies on the transmission of data to a third party service (Refinitiv), including post content stored in WordPress as well as the API token you've generated as part of the service registration. Specifically, this data is transmitted when a WordPress user with appropriate capabilities (default `manage_categories`) clicks on the "Suggested tags from Open Calais™" link in the post tags meta box. The post content that is sent is determined by the state of the post content on last post update.

Please review Refinitiv's service documentation, terms of use and privacy policy before activating this plugin.

Service Documentation: https://developers.refinitiv.com/open-permid/intelligent-tagging-restful-api
Open Calais Terms of Service: http://solutions.refinitiv.com/TermsOfService_OpenCalais
Terms of Use: https://developers.refinitiv.com/terms-use
Privacy Policy: https://www.refinitiv.com/en/policies/privacy-statement

== Upgrade Notice ==

= 1.2.0 =
* Add block editor compatibility.

= 1.1.0 =
* Update resource URL to new endpoint. See https://developers.refinitiv.com/open-permid/news

= 1.0.1 =
* Update namespacing and settings.

= 1.0.0 =
* Initial plugin version.