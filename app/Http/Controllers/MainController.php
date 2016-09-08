<?php

namespace App\Http\Controllers;
use Request;
use App\Http\Requests;
use App\Project;
use App\Press_release;
use App\Novelty;
use App\Category;
use App\City;
use App\Country;
use App\Region;
use App\MainPage;
use App\StaticPage;
use App\Images;
use App\Plan;
use App\Event;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
    public function index()
	{
		$projects = Project::take(4)->where('is_popular', 1)->orderBy('created_at')->get();
		$countries = Country::all();
        $posts = Project::all();

        return view('index', compact('projects', 'countries', 'posts'));
	}

	public function show_page($slug)
	{
		$static_pages =StaticPage::all();
		$static_page = StaticPage::whereSlug($slug)->firstOrFail();
		$main_pages = MainPage::all();

		return view('static_page', compact('static_page', 'static_pages', 'main_pages'));
	}

	public function about()
	{
        $static_pages = StaticPage::all();
        return view('about', compact('static_pages '));
	}

    public function team()
	{
	$static_pages = StaticPage::all();
        return view('team', compact('static_pages'));
	}

    public function message()
	{
	$static_pages = StaticPage::all();
        return view('message', compact('static_pages'));
	}

	public function offers()
	{
		$projects = Project::orderBy('created_at', 'desc')->paginate(6);

        return view('offers', compact('projects'));
	}

	public function projects()
	{
		$projects = Project::orderBy('created_at', 'desc');
		$cities = City::all();
		$countries = Country::all();
		$regions = Region::all();
		$images = Images::all();

		if (Request::has('status'))
		{
			$projects->where('category_id', Request::get('status'));
		}

		$projects = $projects->paginate(4);

        return view('projects', compact('projects', 'cities', 'images', 'countries', 'regions'));
	}

	public function events() 
	{
		$events = Event::orderBy('created_at', 'desc')->paginate(1);

		return view('events', compact('events'));
	}

	public function show_event($slug) 
	{
		$event = Event::whereSlug($slug)->firstOrFail();

		return view('show_event', compact('event'));
	}

	public function releases_and_news()
	{
		$releases = Press_release::take(3)->orderBy('created_at', 'desc')->get();
		$novelties = Novelty::take(2)->orderBy('created_at', 'desc')->get();

		return view('media_center', compact('releases'), compact('novelties'));
	}

	public function releases()
	{
		$releases = Press_release::orderBy('created_at')->paginate(6);

		return view('media_center', compact('releases'));
	}

	public function show_release($slug)
	{
    	$release = Press_release::whereSlug($slug)->first();

	    return view('media_center', compact('release'));
	}

	public function show_new($slug)
	{
    	$novelty = Novelty::whereSlug($slug)->first();

	    return view('media_center', compact('novelty'));
	}

	public function news()
	{
		$novelties = Novelty::orderBy('created_at')->paginate(6);

		return view('media_center', compact('novelties'));
	}

	public function show($slug)
	{
    	$project = Project::whereSlug($slug)->first();
    	$categories = Category::all();
    	$cities = City::all();
    	$images = Images::where('project_id', $project->id)->get();
    	$plans = Plan::where('project_id', $project->id)->get();

    	//$image= explode("|", $project->image);
    	//$floor_plans= explode("|", $project->floor_plans);

	    return view('view', compact('project', 'categories', 'cities', 'images', 'plans'));
	}

	public function simple_search()
	{
		$projects = Project::where('title', 'like', '%'.Request::get('search').'%')->paginate(6);
		
		return view('offers', compact('projects'));
	}

	public function search()
	{
		$projects = Project::orderBy('created_at');
		$countries = Country::all();

		if (Request::has('status'))
		{
			$projects->where('category_id', Request::get('status'));
		}
		if (Request::has('country'))
		{
			$projects->where('country_id', Request::get('country'));
		}
		$projects = $projects->paginate(6);
		$projects->appends(Request::except('page'));
        return view('offers', compact('projects', 'countries'));
	}

	public function complex_search()
	{
		$projects = Project::where('title', 'like', '%'.Request::get('search').'%');
		$cities = City::all();
		$countries = Country::all();
		$regions = Region::all();
		
		if (Request::has('status'))
		{
			$projects->where('category_id', Request::get('status'));
		}
		if (Request::has('country'))
		{
			$projects->where('country_id', Request::get('country'));
		}
		if (Request::has('city'))
		{
			$projects->where('city_id', Request::get('city'));
		}
		if (Request::has('region'))
		{
			$projects->where('region_id', Request::get('region'));
		}
		$projects = $projects->paginate(6);
		$projects->appends(Request::except('page'));
        return view('projects', compact('projects', 'cities', 'countries', 'regions'));
	}

    public function email() 
	{
		$name = Request::get('name');
		$email = Request::get('email');
		$text = Request::get('text');

		if(Request::get('project_id'))
		{
			$id = Request::get('project_id');
		}
		else
		{
			$id = Request::get('event_id');
		}
		

		$data = [
			'name' => $name,
			'email' => $email,
			'text' => $text,
			'id' => $id,
		];

		if(Request::get('project_id'))
		{
			Mail::send( 'mail.email', $data, function ($message){
	            $message->to('storona77@gmail.com')->subject('Запрос на обновления от ' . Request::get('name'));
	        });
		}
		else
		{
			Mail::send( 'mail.email_ev', $data, function ($message){
	            $message->to('storona77@gmail.com')->subject('Запрос на обновления от ' . Request::get('name'));
	        });
		}

	 	

        return redirect()->back()->with('status', 'Сообщение успешно отправлено!');
	}
}