<?php
namespace App\Http\Controllers;
use Request;
use App\Http\Requests;
use App\Project;
use App\Category;
use App\Location;
use DB;
class MainController extends Controller
{
    public function index()
	{
		$projects = Project::take(4)->orderBy('created_at')->get();
		$locations = Location::all();
        return view('index', compact('projects', 'locations'));
	}
	public function about()
	{
        return view('about', compact('projects'));
	}
	public function offers()
	{
		$projects = Project::orderBy('created_at', 'desc')->paginate(6);
        return view('offers', compact('projects'));
	}
	public function projects()
	{
		$projects = Project::orderBy('created_at', 'desc')->paginate(4);
        return view('projects', compact('projects'));
	}
	public function simple_search()
	{
		$projects = Project::where('title', 'like', '%'.Request::get('search').'%')->paginate(6);
		
		return view('offers', compact('projects'));
	}
	public function search()
	{
		$projects = Project::orderBy('created_at');
		$locations = Location::all();
		if (Request::has('status'))
		{
			$projects->where('category_id', Request::get('status'));
		}
		if (Request::has('city'))
		{
			$projects->where('location_id', Request::get('city'));
		}
		$projects = $projects->paginate(6);
		$projects->appends(Request::except('page'));
        return view('offers', compact('projects', 'locations'));
	}
}