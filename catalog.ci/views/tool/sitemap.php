<?php 
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <?php if($urls): foreach($urls as $url): ?>
        <url>
            <loc>http://priceoye.com/<?php echo $url->slug; ?></loc>
            <changefreq>daily</changefreq>
            <priority>0.80</priority>
        </url>
    <?php endforeach; endif; ?>
</urlset>