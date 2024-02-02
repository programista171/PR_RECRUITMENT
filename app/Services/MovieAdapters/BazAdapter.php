<?php

namespace App\Services\MovieAdapters;

use App\Contracts\MovieTitleFetcherInterface;
use External\Baz\Movies\MovieService;
use External\Baz\Exceptions\ServiceUnavailableException;
use Illuminate\Support\Facades\Cache;

class BazAdapter implements MovieTitleFetcherInterface {
    private $baz;

    public function __construct(MovieService $baz) {
        $this->baz = $baz;
    }

    public function getTitles(): array {
        // Attempt to get cached titles first
        $cacheKey = 'baz_movie_titles';
        $cachedTitles = Cache::get($cacheKey);
        if ($cachedTitles) {
            return $cachedTitles;
        }

        $titles = [];
        $retryAttempts = 3;
        for ($attempt = 0; $attempt < $retryAttempts; $attempt++) {
            try {
                $movies = $this->baz->getTitles();
                foreach ($movies['titles'] as $title) {
                    $titles[] = $title;
                }
                // Cache the titles after successful fetch
                Cache::put($cacheKey, $titles, 3600); // Cache for 1 hour
                break; // Break out of the loop on successful fetch
            } catch (ServiceUnavailableException $e) {
                // Log the exception or handle it as required
                if ($attempt === $retryAttempts - 1) {
                    throw $e; // Re-throw the exception if all retry attempts fail
                }
            }
        }

        return $titles;
    }
}
