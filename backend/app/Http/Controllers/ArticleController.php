<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function personalizedFeed(Request $request)
    {
        $preferences = UserPreference::where('user_id', Auth::id())->first();

        if (!$preferences) {
            return response()->json(['message' => 'No preferences found'], 200);
        }

        // Build the query based on user preferences
        $query = Article::query();

        if ($preferences->preferred_sources) {
            $query->whereIn('source_id', $preferences->preferred_sources);
        }

        if ($preferences->preferred_categories) {
            $query->whereIn('category_id', $preferences->preferred_categories);
        }

        if ($preferences->preferred_authors) {
            $query->whereIn('author_id', $preferences->preferred_authors);
        }

        // Fetch paginated articles
        $articles = $query->with(['category', 'author', 'source'])
                          ->orderBy('published_at', 'desc')
                          ->paginate(10);

        return response()->json($articles);
    }


    public function search(Request $request)
    {
        // Get filter inputs from the request
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category_id');
        $authorId = $request->input('author_id');
        $sourceId = $request->input('source_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $perPage = $request->input('per_page', 10);  // Default to 10 articles per page

        // Start building the query
        $query = Article::query();

        // Search by keyword in title or description
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter by category, author, and source
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($authorId) {
            $query->where('author_id', $authorId);
        }

        if ($sourceId) {
            $query->where('source_id', $sourceId);
        }

        // Filter by date range
        if ($dateFrom) {
            $query->whereDate('published_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('published_at', '<=', $dateTo);
        }

        // Get paginated result
        $articles = $query->with(['category', 'author', 'source'])
                          ->orderBy('published_at', 'desc')
                          ->paginate($perPage);

        // Return the paginated response
        return response()->json($articles);
    }

    public function getSources(){
        $sources = Source::all();
        return response()->json($sources);
    }

    public function getAuthors(){
        $authors = Author::all();
        return response()->json($authors);
    }

    public function getCategories(){
        $category = Category::all();
        return response()->json($category);
    }
}
