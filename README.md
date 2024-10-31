# Open Calais

Send your post content to Refinitiv's Open Calais Intelligent Tagging API to get suggested tags you can use in the CMS.

## Installation and Configuration

Before installation, you will need to register with Open Calais to get a free API key: https://developers.refinitiv.com/open-permid/intelligent-tagging-restful-api

After activating Open Calais on the Plugins page, you can configure it by accessing the Open Calais options page under the Settings submenu.

Here, you can input the API key you've received as well as decide if you want Open Calais to return only tags that exist in your CMS, or if you'd like to receive suggestions for new tags.

## Tagging

To query the Open Calais API, you will first need to create a post with content *and save it*. The article content you will send along is pulled from the `post_content` property of the `WP_Post` object, so it's important that you at least have a saved draft of your article before querying the Open Calais API.

Once you've saved your initial draft, look for the *Tags* meta box. You will see an anchor tag with the text "Suggested tags from Open Calais™". Click this to kick off the query.

If your query returned some results, you will see color-coded tag suggestions returned to you:

#### Green

Tags that are highlighted green already exist in your CMS. Note that if you have opted to *match existing tags* on the options page, all tags returned by Open Calais will be vetted against your existing tags, and therefore you will only see green tags.

#### Blue

Tags that are highlighted blue do not exist in your CMS yet. Selecting one of these tags and saving your post will create a new tag. Note that if you will only see blue tags if you have _not_ opted to *match existing tags* on the options page.

## Permissions

By default, this feature is only available to users with the `manage_categories` capability. A filter called `open_calais_capability` has been provided for customization.