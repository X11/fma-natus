<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Serie;
use App\Episode;
use App\User;
use Carbon\Carbon;
use App\Jobs\UpdateSerieAndEpisodes;
use App\Activity;

class AdminController extends Controller
{
    /**
     * undocumented function.
     */
    public function redirectDefault()
    {
        return redirect()->action('AdminController@stats');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        if ($request->input('q')) {
            $users = User::where('name', 'like', '%'.$request->input('q').'%')->paginate(10);
        } else {
            $users = User::paginate(10);
        }

        $users->appends(['q' => $request->input('q')]);

        return view('admin.user')
            ->with('users', $users)
            ->with('role_tags', [
                'User' => 'is-primary',
                'Member' => 'is-info',
                'Moderator' => 'is-success',
                'SuperModerator' => 'is-warning',
                'Admin' => 'is-danger',
                'Owner' => 'is-dark',
            ])
            ->with('breadcrumbs', [[
                'name' => 'Admin',
                'url' => '/admin',
            ], [
                'name' => 'Users',
                'url' => action('AdminController@users'),
            ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stats(Request $request)
    {
        return view('admin.stats')
            ->with('serieCount', Serie::count())
            ->with('episodeCount', Episode::count())
            ->with('userCount', User::count())
            ->with('breadcrumbs', [[
                'name' => 'Admin',
                'url' => '/admin',
            ], [
                'name' => 'Stats',
                'url' => action('AdminController@stats'),
            ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return view('admin.update')
            ->with('breadcrumbs', [[
                'name' => 'Admin',
                'url' => '/admin',
            ], [
                'name' => 'Update',
                'url' => action('AdminController@update'),
            ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        $series = Serie::where([
            ['updated_at', '<', Carbon::parse($request->input('q'))->toDateTimeString()],
        ])->orWhere('updated_at', null)->get();

        if (!$series) {
            return back()
                ->with('status', 'No series selected');
        }

        foreach ($series as $serie) {
            dispatch(new UpdateSerieAndEpisodes($serie));
        }
        $count = $series->count();

        Activity::log('admin.update_series', null, ['count' => $count]);

        return back()
            ->with('status', "$count series updating");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cache(Request $request)
    {
        return view('admin.cache')
            ->with('breadcrumbs', [[
                'name' => 'Admin',
                'url' => '/admin',
            ], [
                'name' => 'Cache',
                'url' => action('AdminController@cache'),
            ]]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postCache(Request $request)
    {
        \Artisan::call('cache:clear');

        Activity::log('admin.remove_cache');

        return back()
            ->with('status', 'Cache cleared');
    }

    /**
     * Get admin activity.
     *
     * @return \Illuminate\Http\Response
     */
    public function activity(Request $request)
    {

        $logs = Activity::where('type', 'admin')
                            ->orderBy('created_at', 'desc')
                            ->limit(20)
                            ->get();

        return view('admin.activity')
            ->with('logs', $logs)
            ->with('breadcrumbs', [[
                'name' => 'Admin',
                'url' => '/admin',
            ], [
                'name' => 'Activity',
                'url' => action('AdminController@activity'),
            ]]);
    }
}
