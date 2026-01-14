<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resources for browsing with filtering and pagination.
     */
    public function browse(Request $request)
    {
        $query = Resource::query();

        // Filter by Category
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('category', $request->categories);
        }

        // Filter by Status
        if ($request->has('statuses') && is_array($request->statuses)) {
            $query->whereIn('status', $request->statuses);
        }

        // Sorting Logic
        $sort = $request->input('sort', 'category_asc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'status_asc':
                $query->orderBy('status', 'asc');
                break;
            case 'category_desc':
                $query->orderBy('category', 'desc')->orderBy('name', 'asc');
                break;
            default: // category_asc
                $query->orderBy('category', 'asc')->orderBy('name', 'asc');
                break;
        }

        $resources = $query->paginate(24)
            ->withQueryString();

        return view('catalog.catalog', compact('resources'));
    }
}