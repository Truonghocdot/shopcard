<?php

namespace App\Http\Controllers;

use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService) {}

    public function index()
    {
        $newsResult = $this->newsService->getPublishedNews(9);

        if ($newsResult->isError()) {
            abort(500, $newsResult->getMessage());
        }

        $news = $newsResult->getData();

        return view('news.index', compact('news'));
    }

    public function show($slug)
    {
        $newsResult = $this->newsService->getNewsBySlug($slug);

        if ($newsResult->isError()) {
            abort(404, $newsResult->getMessage());
        }

        $news = $newsResult->getData();

        $relatedNewsResult = $this->newsService->getRelatedNews($news->id, 3);
        $relatedNews = $relatedNewsResult->isSuccess() ? $relatedNewsResult->getData() : collect();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
