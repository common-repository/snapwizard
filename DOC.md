# SnapWizard Documentation

## Intro
SnapWizard is a WordPress plugin designed to make it easy for the user to embed a feed from one personal Instagram account. The posts will be saved as posts and media into a WordPress website. 
SnapWizard is based on [Instagram Basic Display API](https://developers.facebook.com/docs/instagram-basic-display-api). 

**No coding skills required!**

## Get Started with Instagram Basic Display API
To get started, just follow all the steps here: https://developers.facebook.com/docs/instagram-basic-display-api/getting-started/

Here the main steps you have to follow:
1. Create a Facebook App
2. Configure Instagram Basic Display (check out the OAuth Redirect URL in the Instagram settings tab)
3. Add an Instagram Test User

## Plugin Installation
1. Download, install and activate SnapWizard in your WordPress website
2. Open the SnapWizard settings page 
3. Fill in the App ID, the App Secret (copy them from your Facebook App page)
4. Optionally, change the other settings (read below for additional info about the settings)
5. Save the changes
6. Click "Login with Instagram" and follow the easy procedure on the screen
7. After that, you will be redirected to the SnapWizard Admin page, and you'll see that Instagram is linked correctly
8. Open the link corresponding to "Feed Processor Endpoint" by clicking on the gear icon (to the right of the input text)
9. Create a [cron job](https://crontab.guru/) on your server pointing to the "Feed Processor Endpoint" to make it recurrent

## How it works
``` Warning: you MUST set up permalinks with a different value than PLAIN. In WordPress, the "/wp-json/" endpoint may not work as expected on the "plain" permalink structure due to its reliance on clean and readable URLs for optimal REST API functionality.```
- When you run the SnapWizard engine and initiate the 'Feed Processor Endpoint' either manually or through a Cron Job, SnapWizard will execute the FeedProcessor within the engine. After several checks, if everything is okay, SnapWizard will make a call to the /me/media endpoint on Instagram to query the user media edge. Depending on the 'Limit per page' value, SnapWizard will then proceed to call the next page, if available, until there are no more pages. The higher the 'Limit per page' is set, the fewer calls will be made, preventing exceeding the limit imposed by Meta. For example, if you have an Instagram feed with 150 elements and request everything, using the default value of 75 for the 'Limit per page,' SnapWizard will make 2 calls to retrieve all the elements in your feed. If you set 50, 3 calls will be made, and so on. You can check your limit in your facebook app -> basic-display-rate-limiting
- If you are developing a new website, you must set up **HTTPS (Hypertext Transfer Protocol Secure)** on your development environment. You need the HTTPS in order to allow Instagram to call the "OAuth Redirect URL".   
- You can always debug SnapWizard by setting up WP_DEBUG (wp-config.php) to **true**. In this way SnapWizard will log runtime information in the default WordPress log file in *wp-content/debug.log*.

## Settings documentation
### Instagram Settings
* **Token**: this field can contain a link to the Instagram Login, or, after the user performed the login, the long-lived token (valid for 60 days) and the user will get the message "
  Instagram linked correctly!". The field contains also a link to delete the existing token.
* **Secret Key**: a random key, automatically generated the first time the user save the settings. It will be used to encrypt the long-lived token in the WordPress database, in the options table.
* **App ID**: just the Instagram App ID. The user get this value from the Instagram app page.
* **App Secret**: just the Instagram App Secret. The user get this value from the Instagram app page.
* **Redirect URL**: automatically generated the first time the user save the settings. During the user authentication on Instagram, and upon success, the user will be redirected to the Redirect URL, and an Authorization Code will be appended. This code will be exchanged for a User Access Token, valid for 1 hour, and immediately exchanged for a long-lived token. 
* **Feed Processor Endpoint**: automatically generated the first time the user save the settings. Open this page to run the SnapWizard engine, and embed the Instagram feed into WordPress. Use this URL to create a cron job that can feed your WordPress automatically. 

### Feed Processor Settings
* **Limit per page**: with this setting the user can decide to limit the number of the elements retrieved during the processing. Set up a high number (but less than 99) to limit the API calls to Instagram, avoiding exceeding the limit imposed by Meta.
* **Media type**: with this setting the user can decide which kind of element can be pulled from the feed (images, videos, carousel, or all of them).
* **Exclude (one per line)**: with this setting the user can decide to exclude some elements of the feed (exact name needed!).

### Snap in WordPress
* **Author**: with this setting the user can decide which author will be attached to post and media from the feed.
* **Post Categories**: with this setting the user can decide to attach one or more categories to the posts.
* **Media Categories**: with this setting the user can decide to attach one or more categories to the media.

### Stats
* **Last completed run at**: the date and time of your most recent successful processing run
* **Computations**: the ms used by the feed processor for its computations
* **System Calls**: the ms used by the feed processor spent in system calls

### Contributors
Giuseppe Maccario - [gmaccario](https://www.giuseppemaccario.com/)
