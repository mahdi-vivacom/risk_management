<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\ScrapeTarget;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScrapeNews extends Command
{
    protected $signature = 'scrape:news';
    protected $description = 'Scrape news articles from multiple websites and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client();
        $scrapeTargets = ScrapeTarget::where('status', 1)->get(); // Get all active targets

        if ($scrapeTargets->isEmpty()) {
            $this->error('No scrape targets found in the database.');
            return;
        }
        $currentDate = date('Y-m-d');

        foreach ($scrapeTargets as $scrapeTarget) {
            // Get the URL, keywords, and area name
            $url = $scrapeTarget->url;
            $keywords = json_decode($scrapeTarget->keywords, true);
            $areaName = $scrapeTarget->area_name; // Assume area_name column exists
            $area_id = $scrapeTarget->area_id;

            // Send GET request to fetch the page content
            $response = $client->request('GET', $url, ['verify' => false]);
            $html = $response->getBody()->getContents();

            // Load HTML into DOMDocument
            $dom = new DOMDocument();
            @$dom->loadHTML($html); // Suppress warnings from malformed HTML

            // Create a new DOMXPath object
            $xpath = new DOMXPath($dom);

            // Extract news items (you might need to adjust the XPath based on the site's structure)
            $newsItems = $xpath->query("//div[contains(@class, 'css-1aofmbn-Promo')]");

            foreach ($newsItems as $newsItem) {
                // Extract the headline
                $headlineNode = $xpath->query(".//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6", $newsItem);
                $headline = $headlineNode->length ? $headlineNode->item(0)->textContent : '';

                // Extract the link to the article
                $linkNode = $xpath->query(".//a", $newsItem);
                $link = $linkNode->length ? $linkNode->item(0)->getAttribute('href') : '';

                // Extract the description/summary
                $descriptionNode = $xpath->query(".//p", $newsItem);
                $description = $descriptionNode->length ? $descriptionNode->item(0)->textContent : '';

                // Extract the publication date
                $dateNode = $xpath->query(".//time", $newsItem);
                $publicationDate = $dateNode->length ? $dateNode->item(0)->getAttribute('datetime') : '';
                if ($publicationDate) {
                    $publicationDate = date('Y-m-d', strtotime($publicationDate));
                }

                // Extract the image URL
                $imageNode = $xpath->query(".//img", $newsItem);
                $imageUrl = $imageNode->length ? $imageNode->item(0)->getAttribute('src') : '';

                // Validate image URL
                if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    $imageUrl = rtrim($url, '/') . '/' . ltrim($imageUrl, '/');
                }

                // Add area name to the keywords and filter the news articles
                $keywords[] = $areaName; // Include area name in the search criteria

                if (
                    $this->containsKeywords($headline, $description, $keywords)
                    // && $publicationDate === $currentDate
                ) {
                    // Prevent duplicates by checking if a news article with the same title or link exists
                    $existingNews = News::where('title', $headline)->orWhere('link', $link)->first();
                    if (!$existingNews) {
                        $news = News::create([
                            'title' => $headline,
                            'content' => $description,
                            'link' => $link,
                            'area_id' => $area_id,
                        ]);

                        // Save image if available
                        if ($imageUrl) {
                            $imageDir = 'news_image';
                            Storage::disk('public')->makeDirectory($imageDir);
                            $imagePath = $imageDir . '/' . basename($imageUrl);

                            // Check if the image URL is valid
                            $imageHeaders = @get_headers($imageUrl, 1);
                            if ($imageHeaders && strpos($imageHeaders[0], '200') !== false) {
                                try {
                                    $imageContents = file_get_contents($imageUrl);
                                    Storage::disk('public')->put($imagePath, $imageContents);
                                    $news->image_path = $imagePath; // Save the image path to the database
                                    $news->save();
                                } catch (\Exception $e) {
                                    $this->error("Failed to save image from $imageUrl: " . $e->getMessage());
                                }
                            } else {
                                $this->info("Image not found at $imageUrl, skipping.");
                            }
                        }
                    } else {
                        $this->info("Duplicate news article found: $headline, skipping.");
                    }
                }
            }

            $this->info("Finished scraping from $url for area: $areaName");
        }

        $this->info('All news articles have been scraped and stored.');
    }

    private function containsKeywords($headline, $description, $keywords)
    {
        $content = strtolower($headline . ' ' . $description);
        foreach ($keywords as $keyword) {
            if (strpos($content, strtolower($keyword)) !== false) {
                return true;
            }
        }
        return false;
    }
}
