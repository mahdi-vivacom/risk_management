<?php

namespace App\Console\Commands;

use App\Models\News;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScrapeNews extends Command
{
    protected $signature = 'scrape:news';
    protected $description = 'Scrape news articles from a website and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client();
        $url = env('NEWS_SITE_URL', 'https://www.bbc.com/');

        try {
            $response = $client->request('GET', $url, [
                'verify' => false, // Disable SSL verification (not recommended for production)
            ]);

            $html = $response->getBody()->getContents();
        } catch (\Exception $e) {
            $this->error("Failed to fetch data: " . $e->getMessage());
            return;
        }

        // Load HTML into DOMDocument
        $dom = new DOMDocument();
        @$dom->loadHTML($html); // Suppress warnings from malformed HTML

        // Create a new DOMXPath object
        $xpath = new DOMXPath($dom);

        // Extract news items (adjust this query to be more inclusive)
        $newsItems = $xpath->query("//article | //div[contains(@class, 'Promo')]");

        if ($newsItems->length === 0) {
            $this->info("No news items found.");
            return;
        }

        $currentDate = date('Y-m-d'); // Format current date

        foreach ($newsItems as $newsItem) {
            // Extract the headline
            $headlineNode = $xpath->query(".//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6", $newsItem);
            $headline = $headlineNode->length ? $headlineNode->item(0)->textContent : '';

            // Extract the link to the article
            $linkNode = $xpath->query(".//a", $newsItem);
            $link = $linkNode->length ? $linkNode->item(0)->getAttribute('href') : '';

            if (!filter_var($link, FILTER_VALIDATE_URL)) {
                $link = 'https://www.bbc.com' . $link;
            }

            // Extract the description/summary
            $descriptionNode = $xpath->query(".//p", $newsItem);
            $description = $descriptionNode->length ? $descriptionNode->item(0)->textContent : '';

            // Extract the publication date
            $dateNode = $xpath->query(".//time", $newsItem);
            $publicationDate = $dateNode->length ? $dateNode->item(0)->getAttribute('datetime') : '';

            // Convert to Y-m-d format for comparison
            if ($publicationDate) {
                $publicationDate = date('Y-m-d', strtotime($publicationDate));
            }

            // Extract image URL
            $imageNode = $xpath->query(".//img", $newsItem);
            $imageUrl = $imageNode->length ? $imageNode->item(0)->getAttribute('src') : '';

            // Filter by keywords and date
            $keywords = ['Somalia', 'Lebanon', 'violence', 'security', 'risk', 'danger', 'attack', 'climate', 'bomb', 'injured', 'fire', 'killed', 'troops', 'russia', 'Kamala', 'Israel'];

            if (
                $this->containsKeywords($headline, $description, $keywords)
                //  && $publicationDate === $currentDate
            ) {
                // Save the image
                $imagePath = null;
                if ($imageUrl) {
                    // Get the image content
                    $imageContent = file_get_contents($imageUrl);
                    // Store the image
                    $imagePath = '/storage/news_image/' . uniqid() . '.jpg'; // Generate a unique filename
                    Storage::disk('public')->put($imagePath, $imageContent);
                }

                // Save to database
                News::create([
                    'title' => $headline,
                    'image' => $imagePath,
                    'content' => $description,
                    'link' => $link,
                ]);

                $this->info('News articles have been scraped and stored.');
            } else {
                $this->info("Not saved: either keywords not matched or date does not match.");
            }
        }
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
