<?php

namespace App\Http\Controllers;

use App\Contracts\MovieTitleFetcherInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class MovieController extends Controller
{
    private $fooAdapter;
    private $barAdapter;
    private $bazAdapter;

    public function __construct(MovieTitleFetcherInterface $fooAdapter, MovieTitleFetcherInterface $barAdapter, MovieTitleFetcherInterface $bazAdapter)
    {
        $this->fooAdapter = $fooAdapter;
        $this->barAdapter = $barAdapter;
        $this->bazAdapter = $bazAdapter;
    }

    public function getTitles(Request $request): JsonResponse
    {
        $fooTitles = $this->fooAdapter->getTitles();
        $barTitles = $this->barAdapter->getTitles();
        $bazTitles = $this->bazAdapter->getTitles();

        $allTitles = array_merge($fooTitles, $barTitles, $bazTitles);

        return response()->json($allTitles);
    }
}
