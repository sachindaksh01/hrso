<?php
/**
 * SEO Helper Functions
 */

class SEO {
    private static $pageTitle = '';
    private static $pageDescription = '';
    private static $pageKeywords = '';
    private static $pageImage = '';
    private static $pageUrl = '';
    
    /**
     * Set Page Meta
     */
    public static function setMeta($title, $description, $keywords = '', $image = '') {
        self::$pageTitle = $title . ' - ' . SITE_NAME;
        self::$pageDescription = $description;
        self::$pageKeywords = $keywords;
        self::$pageImage = $image ?: IMAGE_URL . 'og-image.jpg';
        self::$pageUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Output Meta Tags
     */
    public static function renderMeta() {
        ?>
        <!-- Basic Meta Tags -->
        <title><?php echo htmlspecialchars(self::$pageTitle); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars(self::$pageDescription); ?>">
        <?php if (self::$pageKeywords): ?>
            <meta name="keywords" content="<?php echo htmlspecialchars(self::$pageKeywords); ?>">
        <?php endif; ?>
        <meta name="author" content="<?php echo SITE_NAME; ?>">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="<?php echo htmlspecialchars(self::$pageUrl); ?>">
        
        <!-- Open Graph Meta Tags (Facebook) -->
        <meta property="og:title" content="<?php echo htmlspecialchars(self::$pageTitle); ?>">
        <meta property="og:description" content="<?php echo htmlspecialchars(self::$pageDescription); ?>">
        <meta property="og:image" content="<?php echo htmlspecialchars(self::$pageImage); ?>">
        <meta property="og:url" content="<?php echo htmlspecialchars(self::$pageUrl); ?>">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo htmlspecialchars(self::$pageTitle); ?>">
        <meta name="twitter:description" content="<?php echo htmlspecialchars(self::$pageDescription); ?>">
        <meta name="twitter:image" content="<?php echo htmlspecialchars(self::$pageImage); ?>">
        
        <!-- Structured Data (Schema.org) -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "<?php echo SITE_NAME; ?>",
            "url": "<?php echo SITE_URL; ?>",
            "logo": "<?php echo IMAGE_URL; ?>logo.png",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "<?php echo CONTACT_PHONE; ?>",
                "contactType": "Customer Service",
                "email": "<?php echo SITE_EMAIL; ?>"
            },
            "sameAs": [
                "https://facebook.com/yourpage",
                "https://twitter.com/yourpage",
                "https://instagram.com/yourpage"
            ]
        }
        </script>
        <?php
    }
    
    /**
     * Generate Sitemap XML
     */
    public static function generateSitemap($db) {
        $urls = [];
        
        // Static pages
        $staticPages = [
            ['loc' => SITE_URL . 'public/', 'priority' => '1.0'],
            ['loc' => SITE_URL . 'public/about.php', 'priority' => '0.8'],
            ['loc' => SITE_URL . 'public/aims.php', 'priority' => '0.8'],
            ['loc' => SITE_URL . 'public/members.php', 'priority' => '0.9'],
            ['loc' => SITE_URL . 'public/join.php', 'priority' => '0.9'],
            ['loc' => SITE_URL . 'public/donate.php', 'priority' => '0.7'],
            ['loc' => SITE_URL . 'public/contact.php', 'priority' => '0.7'],
        ];
        
        // Dynamic member pages (approved only)
        $members = $db->fetchAll("SELECT member_id, updated_at FROM members WHERE status = 'approved' LIMIT 500");
        foreach ($members as $member) {
            $urls[] = [
                'loc' => SITE_URL . 'public/member-verify.php?id=' . urlencode($member['member_id']),
                'lastmod' => date('Y-m-d', strtotime($member['updated_at'])),
                'priority' => '0.6'
            ];
        }
        
        // Combine all URLs
        $urls = array_merge($staticPages, $urls);
        
        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>weekly</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
}
?>
