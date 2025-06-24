=== Display YouTube Playlists ===
Contributors: jorciney
Donate link: https://coff.ee/jorciney
Tags: playlist, gallery, video, responsive, shortcode
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display YouTube channel playlists in beautiful horizontal scrolling galleries with configurable themes and background colors.

== Description ==

Transform your WordPress site with stunning YouTube playlist galleries! **Display YouTube Playlists** creates beautiful, responsive horizontal scrolling layouts that showcase your channel's playlists professionally.

Perfect for content creators, churches, educational sites, businesses, and musicians who want to showcase their YouTube content elegantly on their WordPress websites.

= 🎨 Key Features =

* **Customizable Themes** - Light and dark themes with custom background colors
* **Responsive Design** - Mobile-first design that works perfectly on all devices
* **Easy Configuration** - Visual admin panel with real-time API connection testing
* **Performance Optimized** - Smart caching system and lazy loading for fast page speeds
* **Custom CSS Support** - Advanced styling options for developers and designers
* **One-Click Setup** - Simple shortcode implementation `[display_youtube_playlists]`
* **Color Picker** - Choose any background color to match your brand
* **Cache Management** - Built-in cache system with one-click clearing
* **Debug Mode** - Helpful troubleshooting tools for administrators

= 🎯 Perfect For =

* **Content Creators** - Showcase video portfolios and latest uploads
* **Churches** - Display sermon series and spiritual content
* **Educational Sites** - Organize course content and tutorials
* **Businesses** - Feature product demonstrations and testimonials
* **Musicians** - Share latest releases and music videos
* **Podcasters** - Display video podcast episodes
* **Fitness Instructors** - Showcase workout routines and classes

= 🚀 How It Works =

1. Get your free YouTube API key from Google Cloud Console
2. Find your YouTube Channel ID (we show you exactly how!)
3. Configure the plugin settings in WordPress admin
4. Add `[display_youtube_playlists]` shortcode to any page or post
5. Enjoy beautiful, professional video galleries!

= 📱 Mobile-First Design =

Your video galleries will look amazing on all devices:
* **Touch-friendly scrolling** on mobile and tablets
* **Adaptive layouts** that adjust to any screen size
* **Fast loading** with optimized images and lazy loading
* **Accessible design** with proper ARIA labels and keyboard navigation

= ⚡ Performance Features =

* **Smart Caching** - Playlists cached for 2 hours, videos for 1 hour
* **Lazy Loading** - Images load only when needed
* **Optimized API Calls** - Efficient YouTube Data API usage
* **Minimal Resource Usage** - Lightweight code that won't slow your site

= 🎨 Customization Options =

**Built-in Themes:**
* Light theme - Clean, professional white background
* Dark theme - Modern dark mode with elegant contrast

**Shortcode Options:**
* `[display_youtube_playlists]` - Basic usage with default settings
* `[display_youtube_playlists theme="dark"]` - Force dark theme
* `[display_youtube_playlists background="#ff5733"]` - Custom background color
* `[display_youtube_playlists max_videos="10"]` - Limit videos per playlist
* `[display_youtube_playlists theme="dark" background="#2d2d2d" max_videos="12"]` - Combined options
* `[display_youtube_playlists debug="true"]` - Debug mode (admins only)

**Advanced Styling:**
* Custom CSS editor in admin panel
* Color picker for background colors
* Reset button to restore default colors
* Developer-friendly CSS classes for custom styling

= 🛡️ Security & Privacy =

* **No Data Collection** - Plugin doesn't collect any visitor data
* **Secure API Calls** - All YouTube requests are server-side only
* **Sanitized Inputs** - All user inputs properly sanitized and validated
* **Nonce Protection** - Admin forms protected against CSRF attacks
* **No External Dependencies** - Only uses WordPress core functions and YouTube API

= 🌍 Developer Friendly =

* **Clean Code** - Well-documented, standards-compliant PHP
* **Translation Ready** - Text domain ready for internationalization
* **Hook System** - WordPress hooks for extensibility
* **GitHub Repository** - Open source development
* **Semantic Versioning** - Predictable version numbering

= 📞 Support & Documentation =

* **Complete Documentation** - Step-by-step setup guides
* **Video Tutorials** - Visual walkthroughs for setup
* **GitHub Issues** - Community-driven bug reports and feature requests
* **Email Support** - Direct developer support
* **Regular Updates** - Ongoing improvements and new features

== Installation ==

= Automatic Installation (Recommended) =

1. Go to **Plugins → Add New** in your WordPress admin dashboard
2. Search for "Display YouTube Playlists"
3. Click **Install Now** and then **Activate**
4. Go to **Settings → Display YouTube Playlists** to configure

= Manual Installation =

1. Download the plugin ZIP file from WordPress.org
2. Go to **Plugins → Add New → Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. **Activate** the plugin
5. Go to **Settings → YouTube Playlist Display** to configure

= After Installation =

1. **Get YouTube API Key:**
   - Visit [Google Cloud Console](https://console.developers.google.com)
   - Create a new project or select existing
   - Enable **YouTube Data API v3**
   - Create credentials → **API Key**

2. **Find Your Channel ID:**
   - Go to your YouTube channel
   - Visit [YouTube Advanced Settings](https://www.youtube.com/account_advanced)
   - Copy your **Channel ID**

3. **Configure Plugin:**
   - Enter API Key and Channel ID in plugin settings
   - Choose theme and background color
   - Test connection and save settings

4. **Add to Your Site:**
   - Add `[display_youtube_playlists]` to any page or post
   - Customize with shortcode options as needed

== Frequently Asked Questions ==

= How do I get a YouTube API key? =

1. Visit [Google Cloud Console](https://console.developers.google.com)
2. Create a new project or select an existing one
3. Go to **APIs & Services → Library**
4. Search for and enable **YouTube Data API v3**
5. Go to **APIs & Services → Credentials**
6. Click **Create Credentials → API Key**
7. Copy your API key to the plugin settings

The API key is free and includes 10,000 requests per day, which is plenty for most websites.

= Where do I find my YouTube Channel ID? =

**Method 1 (Easiest):**
1. Go to your YouTube channel
2. Visit [YouTube Advanced Settings](https://www.youtube.com/account_advanced)
3. Copy your **Channel ID** from the page

**Method 2:**
1. Go to your YouTube channel page
2. Right-click and select **View Page Source**
3. Search for "channelId" (Ctrl+F)
4. Copy the ID that appears after "channelId":"

= The plugin shows "No videos found" - what's wrong? =

Check these common issues:

1. **API Key Issues:**
   - Verify your API key is correct (no extra spaces)
   - Make sure YouTube Data API v3 is enabled in Google Cloud Console
   - Check if your API key has any restrictions

2. **Channel ID Issues:**
   - Ensure your Channel ID is correct
   - Make sure your channel has public playlists
   - Private or unlisted playlists won't show

3. **API Quota:**
   - Check if you've exceeded your daily API quota (10,000 requests)
   - Wait 24 hours for quota reset if exceeded

4. **WordPress Issues:**
   - Try clearing the plugin cache using the admin button
   - Check if other plugins are conflicting

= Can I customize the appearance? =

Absolutely! The plugin offers multiple customization options:

**Built-in Options:**
* Light and dark themes
* Custom background colors with color picker
* Reset button to restore defaults

**Advanced Customization:**
* Custom CSS editor in admin panel
* Developer-friendly CSS classes
* WordPress theme integration

**Example Custom CSS:**
```css
.dyp-video-card {
    border: 2px solid #your-color;
    border-radius: 15px;
}
```

= Does it work on mobile devices? =

Yes! The plugin is mobile-first with:
* **Touch-friendly scrolling** - Smooth finger scrolling on mobile
* **Responsive design** - Adapts to any screen size
* **Optimized layouts** - Different layouts for mobile, tablet, and desktop
* **Fast loading** - Lazy loading and optimized images

= How much does it cost? =

The plugin is **completely free**! 

However, you'll need:
* A free Google Cloud account (no cost)
* YouTube Data API v3 enabled (free with 10,000 daily requests)

= What happens if I exceed the API quota? =

If you exceed the 10,000 daily requests:
* Videos will stop loading temporarily
* Cached videos will continue to display
* Quota resets automatically after 24 hours
* You can upgrade your Google Cloud quota if needed

= Can I display videos from multiple channels? =

Currently, the plugin supports one channel per installation. For multiple channels, you would need:
* Separate plugin instances, or
* Multiple API keys for different shortcodes

We're considering multi-channel support for future versions!

= Is it compatible with caching plugins? =

Yes! The plugin works well with caching plugins like:
* WP Rocket
* W3 Total Cache
* WP Super Cache
* LiteSpeed Cache

The plugin has its own smart caching system that works alongside WordPress caching.

= Can I translate the plugin? =

Yes! The plugin is translation-ready with:
* Text domain: `display-youtube-playlists`
* POT file included for translators
* Compatible with translation plugins like WPML and Polylang

= How do I get support? =

We offer multiple support channels:

1. **WordPress.org Support Forum** - Community support
2. **GitHub Issues** - Bug reports and feature requests
3. **Documentation** - Complete guides at jorciney.dev
4. **Email Support** - Direct developer contact

== Screenshots ==

1. **Admin Configuration Panel** - Clean, intuitive settings interface with real-time API testing and helpful instructions
2. **Light Theme Gallery** - Professional light theme perfect for corporate websites and blogs
3. **Dark Theme Gallery** - Modern dark theme ideal for creative portfolios and entertainment sites
4. **Mobile Responsive View** - Seamless mobile experience with touch-friendly scrolling and adaptive layouts
5. **Custom Color Picker** - Easy background color customization with live preview and reset functionality
6. **Video Modal Player** - Elegant full-screen modal window for immersive video playback experience

== Changelog ==

= 2.0.0 (2024-12-20) =
* **New:** Configurable background colors with visual color picker
* **New:** Reset button to restore default theme colors
* **New:** Custom CSS editor for advanced styling options
* **New:** Real-time API connection testing with detailed feedback
* **New:** One-click cache management system
* **New:** Enhanced debug mode with comprehensive information
* **Improved:** Admin interface with better UX and visual design
* **Improved:** Error handling with more helpful user messages
* **Improved:** Mobile responsiveness and touch interactions
* **Improved:** Security enhancements and input validation
* **Fixed:** Edge cases with API responses and caching
* **Fixed:** CSS conflicts with some WordPress themes
* **Updated:** YouTube API integration for better reliability

= 1.0.0 (2024-11-15) =
* **New:** Initial release with core functionality
* **New:** Basic playlist display with horizontal scrolling
* **New:** Light and dark theme options
* **New:** Responsive design for all devices
* **New:** WordPress admin configuration panel
* **New:** Shortcode implementation with basic options
* **New:** YouTube Data API v3 integration
* **New:** Smart caching system for performance

== Upgrade Notice ==

= 2.0.0 =
Major update with configurable background colors, custom CSS editor, and enhanced admin interface. All new features are backward compatible. Backup recommended before upgrading.

= 1.0.0 =
Initial release of Display YouTube Playlists. Install to start showcasing your YouTube playlists beautifully!

== Privacy Policy ==

Display YouTube Playlists respects your privacy and your visitors' privacy:

**Data Collection:**
* This plugin does **NOT** collect any personal data from website visitors
* No tracking, analytics, or user behavior monitoring
* No data is sent to external servers except YouTube API calls

**YouTube API Usage:**
* Plugin makes server-side requests to YouTube Data API v3
* API requests only fetch public playlist and video information
* No personal data is transmitted to YouTube beyond what's publicly available

**Local Data Storage:**
* Plugin settings are stored in your WordPress database
* Cached playlist data is stored locally using WordPress transients
* No external databases or third-party services used

**Cookies:**
* Plugin does not set any cookies
* No user tracking or session management

**Third-Party Services:**
* Only integrates with YouTube Data API v3 (Google)
* No other external services or APIs used
* All communication is server-to-server (not client-side)

For questions about privacy, contact: jorciney@cleverupps.be

== Credits ==

**Developed by:** [Jorciney](https://jorciney.dev)
**Plugin URI:** https://jorciney.dev/wp-plugins/display-youtube-playlists/
**GitHub Repository:** https://github.com/jorciney/display-youtube-playlists

**Special Thanks:**
* WordPress.org community for guidelines and best practices
* Google for providing the YouTube Data API v3
* Font Awesome for beautiful icons
* All beta testers and early users for valuable feedback

**Libraries Used:**
* Font Awesome 6.4.0 (for icons)
* WordPress core functions only (no external dependencies)

Made with ❤️ for the WordPress community.