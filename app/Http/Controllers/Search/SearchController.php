<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Cnsi\Searchable\Handler\Search;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;use Illuminate\View\View;

class SearchController extends Controller
{
    public function search(Request $request): View|RedirectResponse|JsonResponse
    {
        // search parameters
        $term       = $request->get("term") ?? "";
        $entities   = $request->get("entities") ?? [];
        $json       = $request->get("json") ?? false;

        // search results
        $search     = new Search($term, $entities);
        $results    = $search->getResults();
        $nb_results = $search->countResults();

        // returns
        // if json set to true, return a json encoded response
        if($json) {
            return response()->json($search->getFlattenedResults());
        }

        // if none ajax request with single result : redirect
        if($nb_results == 1 && !$request->ajax()) {
            $model = $search->getFirstResult();
            return redirect(route($model->getShowRoute(), $model));
        }

        // otherwise return a view, ajaxified or not
        $view  = $request->ajax() ? 'admin.search.search_ajax' : 'admin.search.search';
        return view($view)
            ->with('term', $term)
            ->with('nb_results', $nb_results)
            ->with('results', $results);
    }
}
