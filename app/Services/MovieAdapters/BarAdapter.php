<?php

namespace App\Services\MovieAdapters;

use App\Contracts\MovieTitleFetcherInterface;
use External\Bar\Movies\MovieService;
use External\Bar\Exceptions\ServiceUnavailableException;
use Illuminate\Support\Facades\Cache;

class BarAdapter implements MovieTitleFetcherInterface {
    private $bar;

    public function __construct(MovieService $bar) {
        $this->bar = $bar;
    }

    public function getTitles(): array {
        // Attempt to get cached titles first
        $cacheKey = 'bar_movie_titles';
        $cachedTitles = Cache::get($cacheKey);
        if ($cachedTitles) {
            return $cachedTitles;
        }

        $titles = [];
        $retryAttempts = 3;
        for ($attempt = 0; $attempt < $retryAttempts; $attempt++) {
            try {
                $movies = $this->bar->getTitles();
                foreach ($movies['titles'] as $movie) {
                    $titles[] = $movie['title']; // Correctly accessing 'title' within each 'titles' array element
                }
                // Cache the titles after successful fetch
                Cache::put($cacheKey, $titles, 3600); // Cache for 1 hour
                break; // Break out of the loop on successful fetch
            } catch (ServiceUnavailableException $e) {
                // Log the exception or handle it as required
                if ($attempt === $retryAttempts - 1) {
                    // Optionally handle the final failure differently
                    throw $e; // Re-throw the exception if all retry attempts fail
                }
            }
        }

        return $titles;
    }
}
