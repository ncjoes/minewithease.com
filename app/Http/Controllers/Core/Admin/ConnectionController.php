<?php

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Connection;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage(Request $request)
    {
        $search   = (string)$request->get('search', '');
        //$status   = (boolean)$request->get('status', true);
        $per_page = $request->get('per_page', 30);

        $results = Connection::query();//where('is_active', $status);
        $results = strlen((string)$search) ? $results->where('data.email', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Connection::count();
        $result_count = $results->count();
        $results      = $results->orderBy('id', 'desc')->paginate($per_page);

        return view('core-admin.connection.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'statuses'     => [
                1 => 'Yes',
                0 => 'No',
            ],
            'filter'       => [
                'search'   => $search,
                'status'   => 1,//$status,
                'per_page' => $per_page,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

}
