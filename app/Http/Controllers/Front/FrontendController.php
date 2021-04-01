<?php

namespace App\Http\Controllers\Front;

use App\Front\Blog;
use App\Front\BlogCategory;
use App\Front\Career;
use App\Front\Faq;
use App\Front\FrontAbout;
use App\Front\HomeSetting;
use App\Front\PageSetting;
use App\Front\Service;
use App\Front\Team;
use App\Front\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\VendorRequestMail;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

use function GuzzleHttp\json_decode;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $home_settings = HomeSetting::first();
        $teams = Team::where('status', 'active')->take(4)->get();
        $service = Service::where('status', 'active')->get();
        $blogs = Blog::where('status', 'active')->take(4)->latest()->get();
        $testimonials = Testimonial::where('status', 'active')->get();
        /*dd($testimonials);
        $home_setting = json_decode($home_settings->why_choose_us, true);
        dd($home_setting['Agriculture Leader']);
        $home_setting = explode(',',str_replace('+', ' ', $home_settings->why_choose_us));
        foreach($home_setting as $key => $value){
            list($k, $v) = explode('=', $value);
            $result[ $k ] = $v;
        }*/
        return view('frontcms.index')->with('home_setting', $home_settings)
            ->with('team_members', $teams)
            ->with('services', $service)
            ->with('blogs', $blogs)
            ->with('testimonials', $testimonials);
    }

    public function getAbout()
    {
        $home_settings = HomeSetting::select('welcome_description')->first();
        $about_details = FrontAbout::first();
        return view('frontcms.about_page')->with('about_info', $about_details)
            ->with('about_content', $home_settings->welcome_description);
    }

    public function getBlog()
    {
        $about_details = FrontAbout::first();
        $categories = BlogCategory::with('news')->orderBy('id', 'desc')->get();
        $blogs = Blog::with('category')->orderBy('id', 'desc')->where('status', 'active')->paginate(4);
        return view('frontcms.blog')
            ->with('about_info', $about_details)
            ->with('categories', $categories)
            ->with('blogs', $blogs);
    }

    public function mailRequest(Request $request)
    {
        try{
            Mail::to(Config::get('mail.from.address'))->send(new VendorRequestMail($request));
            $request->session()->flash('sucess', 'Your message has been sent to Freshktm');

        } catch (Exception $ex) {
            $request->session()->flash('error', 'Something went wrong');
        }

        return redirect()->back();
    }



    public function getSingleBlog($slug)
    {
        $about_details = FrontAbout::select('banner_image')->first();
        $categories = BlogCategory::with('news')->orderBy('id', 'desc')->get();
        $blog_single = Blog::where('slug', $slug)->first();
        $blogs = Blog::orderBy('created_at', 'desc')->take(5)->get();
        return view('frontcms.blog_single')
            ->with('about_info', $about_details)
            ->with('blog_single', $blog_single)
            ->with('categories', $categories)
            ->with('blogs', $blogs);
    }

    public function getTeam()
    {
        $about_details = FrontAbout::first();
        $teams = Team::where('status', 'active')->get();
        return view('frontcms.team')
            ->with('about_info', $about_details)
            ->with('teams', $teams);
    }

    public function getFaqs()
    {
        $about_details = FrontAbout::first();
        $faq = HomeSetting::first();
        return view('frontcms.faqs')
            ->with('faq', $faq)
            ->with('about_info', $about_details);
    }

    public function getContact()
    {
        $about_details = FrontAbout::first();
        $home_settings = HomeSetting::first();
        // dd($home_settings);
        return view('frontcms.contact')
            ->with('about_info', $about_details)
            ->with('contact', $home_settings);
    }

    public function getBuyOrSell()
    {
        return view('frontcms.buysell');
    }

    public function getCareers()
    {
        $careers = Career::orderBy('id', 'desc')->where('status', 'active')->get();
        return view('frontcms.careers')->with('careers', $careers);
    }

    public function getPages($slug)
    {
        $about_details = FrontAbout::first();
        $page_info = PageSetting::where('slug', $slug)->first();
        return view('frontcms.page')
            ->with('about_info', $about_details)
            ->with('page_info', $page_info);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
