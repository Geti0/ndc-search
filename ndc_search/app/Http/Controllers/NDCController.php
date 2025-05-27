<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Exports\NDCExport;
use App\Models\NDC;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class NDCController extends Controller{
    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $codes = array_map('trim', explode(',', $request->ndc_codes));
        $results = [];

        $local = NDC::whereIn('ndc_code', $codes)->get()->keyBy('ndc_code');
        $notFound = array_diff($codes, $local->keys()->toArray());

        if (!empty($notFound)) {
            $query = implode('","', $notFound);
            // Here i had to use the api without verification because it was giving some certification errors
            // $response = Http::get("https://api.fda.gov/drug/ndc.json?search=product_ndc:\"$query\"");
            $response = Http::withoutVerifying()->get("https://api.fda.gov/drug/ndc.json?search=product_ndc:\"$query\"");

            if ($response->successful()) {
                foreach ($response['results'] as $item) {
                    $drug = NDC::updateOrCreate(
                        ['ndc_code' => $item['product_ndc']],
                        [
                            'brand_name' => $item['brand_name'] ?? '',
                            'generic_name' => $item['generic_name'] ?? '',
                            'labeler_name' => $item['labeler_name'] ?? '',
                            'product_type' => $item['product_type'] ?? '',
                        ]
                    );
                    $results[] = [...$drug->toArray(), 'source' => 'OpenFDA'];
                }

                $fetched = array_column($response['results'], 'product_ndc');
                $notFound = array_diff($notFound, $fetched);
            }
        }

        foreach ($local as $drug) {
            $results[] = [...$drug->toArray(), 'source' => 'Database'];
        }

        foreach ($notFound as $code) {
            $results[] = ['ndc_code' => $code, 'source' => 'Not Found'];
        }

        session(['last_results' => $results]);

        $resultsCollection = collect($results);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;

        $currentResults = $resultsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedResults = new LengthAwarePaginator(
            $currentResults,
            $resultsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('search', ['results' => $paginatedResults]);
    }

    public function export()
    {
        $data = session('last_results');

        if (!$data) {
            return redirect()->route('search.index')->with('error', 'Nuk ka të dhëna për eksport.');
        }

        return Excel::download(new NDCExport($data), 'ndc_search_results.csv');
    }


    public function delete($id)
    {
        $ndc = NDC::findOrFail($id);
        $ndc->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

}
