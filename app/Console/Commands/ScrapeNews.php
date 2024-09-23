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
    protected $description = 'Scrape news articles from multiple websites and store them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client();
        $urls = explode(',', env('NEWS_SITE_URL', 'https://www.bbc.com,https://edition.cnn.com/'));

        foreach ($urls as $url) {
            $url = trim($url);

            try {
                $response = $client->request('GET', $url, ['verify' => false]);
                $html = $response->getBody()->getContents();
            } catch (\Exception $e) {
                $this->error("Failed to fetch data from $url: " . $e->getMessage());
                continue;
            }

            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
            $newsItems = $xpath->query("//article | //div[contains(@class, 'Promo')]");

            if ($newsItems->length === 0) {
                $this->info("No news items found on $url.");
                continue;
            }

            $currentDate = date('Y-m-d');

            foreach ($newsItems as $newsItem) {
                // Extract the headline
                $headlineNode = $xpath->query(".//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6", $newsItem);
                $headline = $headlineNode->length ? $headlineNode->item(0)->textContent : '';

                // Extract the link to the article
                $linkNode = $xpath->query(".//a", $newsItem);
                $link = $linkNode->length ? $linkNode->item(0)->getAttribute('href') : '';
                if (!filter_var($link, FILTER_VALIDATE_URL)) {
                    $link = rtrim($url, '/') . '/' . ltrim($link, '/');
                }

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

                // Define keywords for filtering
                $keywords = ['Somalia', 'violence', 'security', 'risk', 'danger', 'attack', 'climate', 'bomb', 'injured', 'killed', 'killing', 'kill', 'fire', 'Lebanon'];

                // Save to database if conditions are met
                if (
                    $this->containsKeywords($headline, $description, $keywords)
                    // && $publicationDate === $currentDate
                ) {
                    $news = News::create([
                        'title' => $headline,
                        'content' => $description,
                        'link' => $link,
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
                    $this->info("Not saved: either keywords not matched or date does not match.");
                }
            }
        }

        $this->info('News articles have been scraped and stored.');
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
