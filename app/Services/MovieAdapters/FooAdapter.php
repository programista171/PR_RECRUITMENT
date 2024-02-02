<?php

namespace App\Services\MovieAdapters;

use App\Contracts\MovieTitleFetcherInterface;
use External\Foo\Movies\MovieService;
use External\Foo\Exceptions\ServiceUnavailableException;
use Illuminate\Support\Facades\Cache;

class FooAdapter implements MovieTitleFetcherInterface {
    private $foo;

    public function __construct(MovieService $foo) {
        $this->foo = $foo;
    }

    public function getTitles(): array {
        // Attempt to get cached titles first
        $cacheKey = 'foo_movie_titles';
        $cachedTitles = Cache::get($cacheKey);
        if ($cachedTitles) {
            return $cachedTitles;
        }

        $titles = [];
        $retryAttempts = 3;
        for ($attempt = 0; $attempt < $retryAttempts; $attempt++) {
            try {
                $titles = $this->foo->getTitles(); // Assuming titles are directly returned as an array
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
