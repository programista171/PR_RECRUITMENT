<php

namespace App\Contracts;

interface MovieTitleFetcherInterface {
    public function getTitles(): array; // Returns an array of movie titles
}
