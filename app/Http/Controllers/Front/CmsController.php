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
use App\Utils\Util;
use Illuminate\Support\Str;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class CmsController extends Controller
{
    protected $setting;
    protected $util;
    protected $about;
    protected $team;
    protected $service;
    protected $blog;

    public function __construct(HomeSetting $settings, Util $util, FrontAbout $frontAbout, Team $team, Service $service, Blog $blog)
    {
        $this->setting = $settings;
        $this->util = $util;
        $this->about = $frontAbout;
        $this->team = $team;
        $this->service = $service;
        $this->blog = $blog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->setting->first();
        if (!$data) {
            return redirect()->route('frontcms-settings.create');
        }
        return view('frontcms.edit_setting')->with('setting', $data);
        // $setting = $this->setting->first();
        // dd(explode(',', $setting->client_images));
        // dd(json_decode($setting->faqs, true));
        // return view('frontcms.edit_setting')->with('setting', $setting);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontcms.create-setting-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $this->util->uploadHomeFile($request->logo_image[0], config('constants.product_img_path') . '/home');
        }
        $data['address'] = $request->address;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;

        $banner_photos = array();
        if ($request->hasFile('banner_images')) {
            foreach ($request->banner_images as $key => $photo) {
                $banner = $this->util->uploadHomeFile($photo, config('constants.product_img_path') . '/home/banner');
                array_push($banner_photos, $banner);
            }
        }
        $data['banner_images'] = implode(",", $banner_photos);

        // $data['why_choose_us'] = http_build_query(array_combine($request->why_title, $request->why_description),'',',');
        $data['why_choose_us'] = json_encode(array_combine($request->why_title, $request->why_description));

        if ($request->hasFile('welcome_image')) {
            $data['welcome_image'] = $this->util->uploadHomeFile($request->welcome_image[0], config('constants.product_img_path') . '/home');
        }
        $data['welcome_description'] = $request->welcome_description;

        if ($request->hasFile('vdo_image')) {
            $data['vdo_image'] = $this->util->uploadHomeFile($request->vdo_image[0], config('constants.product_img_path') . '/home');
        }
        $data['vdo_link'] = $request->video_link;

        $data['faqs'] = json_encode(array_combine($request->faq, $request->faq_ans));
        $data['social_links'] = json_encode(array_combine($request->site, $request->sitelink));


        if ($request->hasFile('call_section_image')) {
            $data['call_section_image'] = $this->util->uploadHomeFile($request->call_section_image[0], config('constants.product_img_path') . '/home');
        }
        if ($request->hasFile('counter_section_image')) {
            $data['counter_section_image'] = $this->util->uploadHomeFile($request->counter_section_image[0], config('constants.product_img_path') . '/home');
        }
        if ($request->hasFile('quote_back_image')) {
            $data['quote_background_image'] = $this->util->uploadHomeFile($request->quote_back_image[0], config('constants.product_img_path') . '/home');
        }
        if ($request->hasFile('quote_front_image')) {
            $data['quote_front_image'] = $this->util->uploadHomeFile($request->quote_front_image[0], config('constants.product_img_path') . '/home');
        }
        $client_photos = array();
        if ($request->hasFile('client_images')) {
            foreach ($request->client_images as $key => $photo) {
                $banner = $this->util->uploadHomeFile($photo, config('constants.product_img_path') . '/home/client');
                array_push($client_photos, $banner);
            }
        }
        $data['client_images'] = implode(",", $client_photos);
        $data['google_map_link'] = $request->google_map_link;
        $data['created_by'] = $request->session()->get('user.id');
        $this->setting->fill($data);
        $status = $this->setting->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Settings Added Successfully!'
            ];
            return redirect()->route('frontcms-settings.index')->with('status', $output);
        }
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
        dd($request->all());
        $this->setting = $this->setting->find($id);
        // dd($this->setting);
        $data['logo_image'] = $request->previous_logo_image;
        if ($request->has('logo_image')) {
            $data['logo_image'] = $this->util->uploadHomeFile($request->logo_image[0], config('constants.product_img_path') . '/home');
        }
        $data['address'] = $request->address;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;

        if ($request->has('previous_banner_images')) {
            $banner_photos = $request->previous_banner_images;
        } else {
            $banner_photos = array();
        }
        if ($request->hasFile('banner_images')) {
            foreach ($request->banner_images as $key => $photo) {
                $banner = $this->util->uploadHomeFile($photo, config('constants.product_img_path') . '/home/banner');
                array_push($banner_photos, $banner);
            }
        }
        $data['banner_images'] = implode(",", $banner_photos);

        // $data['why_choose_us'] = http_build_query(array_combine($request->why_title, $request->why_description),'',',');
        $data['why_choose_us'] = json_encode(array_combine($request->why_title, $request->why_description));

        $data['welcome_image'] = $request->previous_welcome_image;
        if ($request->has('welcome_image')) {
            $data['welcome_image'] = $this->util->uploadHomeFile($request->welcome_image[0], config('constants.product_img_path') . '/home');
        }
        $data['welcome_description'] = $request->welcome_description;

        $data['vdo_image'] = $request->previous_vdo_image;
        if ($request->has('vdo_image')) {
            $data['vdo_image'] = $this->util->uploadHomeFile($request->vdo_image[0], config('constants.product_img_path') . '/home');
        }
        // $data['vdo_image'] = $this->util->uploadHomeFile($request->vdo_image[0], config('constants.product_img_path') . '/home');
        $data['vdo_link'] = $request->video_link;

        $data['faqs'] = json_encode(array_combine($request->faq, $request->faq_ans));
        $data['social_links'] = json_encode(array_combine($request->site, $request->sitelink));

        $data['call_section_image'] = $request->previous_call_section_image;
        if ($request->has('call_section_image')) {
            $data['call_section_image'] = $this->util->uploadHomeFile($request->call_section_image[0], config('constants.product_img_path') . '/home');
        }
        // $data['call_section_image'] = $this->util->uploadHomeFile($request->call_section_image[0], config('constants.product_img_path') . '/home');
        $data['counter_section_image'] = $request->previous_counter_section_image;
        if ($request->has('counter_section_image')) {
            $data['counter_section_image'] = $this->util->uploadHomeFile($request->counter_section_image[0], config('constants.product_img_path') . '/home');
        }
        // $data['counter_section_image'] = $this->util->uploadHomeFile($request->counter_section_image[0], config('constants.product_img_path') . '/home');
        $data['quote_background_image'] = $request->previous_quote_background_image;
        if ($request->has('quote_back_image')) {
            $data['quote_background_image'] = $this->util->uploadHomeFile($request->quote_back_image[0], config('constants.product_img_path') . '/home');
        }
        // $data['quote_background_image'] = $this->util->uploadHomeFile($request->quote_back_image[0], config('constants.product_img_path') . '/home');
        $data['quote_front_image'] = $request->previous_quote_front_image;
        if ($request->has('quote_front_image')) {
            $data['quote_front_image'] = $this->util->uploadHomeFile($request->quote_front_image[0], config('constants.product_img_path') . '/home');
        }
        // $data['quote_front_image'] = $this->util->uploadHomeFile($request->quote_front_image[0], config('constants.product_img_path') . '/home');

        if ($request->has('previous_client_images')) {
            $client_photos = $request->previous_client_images;
        } else {
            $client_photos = array();
        }
        // $client_photos = array();
        if ($request->hasFile('client_images')) {
            foreach ($request->client_images as $key => $photo) {
                $banner = $this->util->uploadHomeFile($photo, config('constants.product_img_path') . '/home/client');
                array_push($client_photos, $banner);
            }
        }
        $data['client_images'] = implode(",", $client_photos);
        $data['google_map_link'] = $request->google_map_link;
        $data['created_by'] = $request->session()->get('user.id');
        $this->setting->fill($data);
        // dd($this->setting);
        $status = $this->setting->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => "Settings Updated Successfully"
            ];
            return redirect()->route('frontcms-settings.index')->with('status', $output);
        }
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

    public function createAbout()
    {
        return view('frontcms.about_form');
    }

    public function storeAbout(Request $request)
    {

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->util->uploadHomeFile($request->banner_image[0], config('constants.product_img_path') . '/home/about');
        }
        $data['what_sub_title'] = $request->what_sub_title;
        $data['what_description'] = $request->what_description;
        if ($request->hasFile('banner_image')) {
            $data['what_image'] = $this->util->uploadHomeFile($request->what_image[0], config('constants.product_img_path') . '/home/about');
        }
        $data['why_sub_title'] = $request->why_sub_title;
        $data['why_description'] = $request->why_description;
        if ($request->hasFile('banner_image')) {
            $data['why_image'] = $this->util->uploadHomeFile($request->why_image[0], config('constants.product_img_path') . '/home/about');
        }
        $data['why_short_points'] = json_encode($request->why_short_points);
        $data['added_by'] = $request->session()->get('user.id');
        $this->about->fill($data);
        $status = $this->about->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'About Settings Added Successfuly'
            ];
            return redirect()->route('frontcms_about_edit')->with('status', $output);
        }
    }

    public function editAbout()
    {
        $data = FrontAbout::first();
        if (!$data) {
            return redirect()->route('frontcms_about_form');
        }
        return view('frontcms.about_edit')->with('about_info', $data);
    }

    public function updateAbout(Request $request)
    {
        $setting_id = $request->setting_id;
        $settings = FrontAbout::where('id', $setting_id)->first();
        $data['banner_image'] = $request->previous_banner_image;
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->util->uploadHomeFile($request->banner_image[0], config('constants.product_img_path') . '/home/about');
        }
        $data['what_sub_title'] = $request->what_sub_title;
        $data['what_description'] = $request->what_description;
        $data['what_image'] = $request->previous_what_image;
        if ($request->hasFile('what_image')) {
            $data['what_image'] = $this->util->uploadHomeFile($request->what_image[0], config('constants.product_img_path') . '/home/about');
        }
        $data['why_sub_title'] = $request->why_sub_title;
        $data['why_description'] = $request->why_description;
        $data['why_image'] = $request->previous_why_image;
        if ($request->hasFile('why_image')) {
            $data['why_image'] = $this->util->uploadHomeFile($request->why_image[0], config('constants.product_img_path') . '/home/about');
        }
        $data['why_short_points'] = json_encode($request->why_short_points);
        $settings->fill($data);
        $settings->save();
        if ($settings) {
            $output = [
                'success' => 1,
                'msg' => 'About Settings Updated Successfuly'
            ];
            return redirect()->route('frontcms_about_edit')->with('status', $output)
                ->with('about_info', $settings);
        }
    }

    public function viewTeam()
    {
        $teams = $this->team->get();
        return view('frontcms.team.index')->with('teams', $teams);
    }

    public function createTeam()
    {
        return view('frontcms.team.team_form');
    }

    public function storeTeam(Request $request)
    {

        $this->team->name = $request->name;
        $this->team->post = $request->post;
        $this->team->social_links = json_encode(array_combine($request->social_name, $request->social_link));
        $this->team->added_by = $request->session()->get('user.id');
        $this->team->member_image = $this->util->uploadHomeFile($request->member_image[0], config('constants.product_img_path') . '/home/team');
        $status = $this->team->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Team Member Added Successfuly'
            ];
            return redirect()->route('cms_team')->with('status', $output);
        }
    }

    public function editTeam(Request $request, $id)
    {
        $team = $this->team = $this->team->find($id);
        return view('frontcms.team.edit')->with('member_info', $team);
    }

    public function updateTeam(Request $request, $id)
    {
        // dd($request->all());
        $this->team = $this->team->find($id);
        $this->team->name = $request->name;
        $this->team->post = $request->post;
        $this->team->social_links = json_encode(array_combine($request->social_name, $request->social_link));
        $this->team->status = $request->status;
        $this->team->member_image = $request->previous_member_image;
        if ($request->hasFile('member_image')) {
            $this->team->member_image = $this->util->uploadHomeFile($request->member_image[0], config('constants.product_img_path') . '/home/team');
        }
        $status = $this->team->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Team Member Updated Successfuly'
            ];
            return redirect()->route('cms_team_edit', $this->team->id)->with('status', $output);
        }
    }

    public function viewServices()
    {
        $services = $this->service->get();
        return view('frontcms.service.index')->with('services', $services);
    }

    public function createServices()
    {
        return view('frontcms.service.service_form');
    }

    public function storeServices(Request $request)
    {
        $data['title'] = $request->title;
        $data['summary'] = $request->summary;
        $data['service_image'] = $this->util->uploadHomeFile($request->service_image[0], config('constants.product_img_path') . '/home/services');
        $data['added_by'] = $request->session()->get('user.id');
        $this->service->fill($data);
        $status = $this->service->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Service Added Successfuly'
            ];
            return redirect()->route('cms_service_form')->with('status', $output);
        }
    }

    public function editServices($id)
    {
        $this->service = $this->service->where('id', $id)->first();
        return view('frontcms.service.service_edit')->with('service_info', $this->service);
    }

    public function updateServices(Request $request, $id)
    {
        $this->service = $this->service->where('id', $id)->first();
        $this->service->title = $request->title;
        $this->service->summary = $request->summary;
        $this->service->status = $request->status;
        $this->service->service_image = $request->previous_service_image;
        // dd($this->service);
        if ($request->hasFile('service_image')) {
            $this->service->service_image = $this->util->uploadHomeFile($request->service_image[0], config('constants.product_img_path') . '/home/services');
        }
        $status = $this->service->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Service Added Successfuly'
            ];
            return redirect()->route('cms_service')->with('status', $output);
        }
    }

    public function viewBlog()
    {
        $blogs = Blog::with('category')->orderBy('id', "desc")->paginate();
        return view('frontcms.blog.index')->with('blogs', $blogs);
    }

    public function createBlog()
    {
        $categories = BlogCategory::orderBy('id', 'desc')->get();
        return view('frontcms.blog.blog_form')->with('categories', $categories);
    }

    public function storeBlog(Request $request)
    {
        // dd($request->all());
        $data['title'] = $request->title;
        $data['slug'] = Str::slug($request->title);
        $data['category_id'] = $request->category_id;
        $data['summary'] = $request->summary;
        $data['description'] = $request->description;
        if ($request->hasFile('blog_image')) {
            $data['image'] = $this->util->uploadHomeFile($request->blog_image[0], config('constants.product_img_path') . '/home/blogs');
        }
        $data['added_by'] = $request->session()->get('user.id');
        $this->blog->fill($data);
        $status = $this->blog->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Blog Added Successfuly'
            ];
            return redirect()->route('cms_blog')->with('status', $output);
        }
    }

    public function editBlog($id)
    {
        $categories = BlogCategory::orderBy('id', 'desc')->get();
        $this->blog = $this->blog->where('id', $id)->first();
        return view('frontcms.blog.blog_edit')->with('blog_info', $this->blog)
            ->with('categories', $categories);
    }

    public function updateBlog(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        // dd($blog);
        $blog->title = $request->title;
        $blog->category_id = $request->category_id;
        $blog->summary = $request->summary;
        $blog->description = $request->description;
        $blog->status = $request->status;
        $blog->image = $request->previous_blog_image;
        if ($request->hasFile('blog_image')) {
            $blog->image = $this->util->uploadHomeFile($request->blog_image[0], config('constants.product_img_path') . '/home/blogs');
        }
        $status = $blog->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Blog Updated Successfuly'
            ];
            return redirect()->route('cms_blog')->with('status', $output);
        }
    }

    public function viewTestimonial()
    {
        $testimonials = Testimonial::get();
        // dd($testimonials);
        return view('frontcms.testimonial.index')->with('testimonials', $testimonials);
    }

    public function createTestimonial()
    {
        return view('frontcms.testimonial.testimonial_form');
    }

    public function storeTestimonial(Request $request)
    {
        $testimonial = new Testimonial();
        $data['name'] = $request->name;
        $data['post'] = $request->post;
        $data['comment'] = $request->comment;
        $data['image'] = $this->util->uploadHomeFile($request->testimonial_image[0], config('constants.product_img_path') . '/home/testimonials');
        $data['added_by'] = $request->session()->get('user.id');
        $testimonial->fill($data);
        $status = $testimonial->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Testimonial Added Successfuly'
            ];
            return redirect()->route('cms_testimonial_form')->with('status', $output);
        }
    }

    public function editTestimonial($id)
    {
        $testimonial = Testimonial::where('id', $id)->first();
        // dd($testimonial->name);
        return view('frontcms.testimonial.edit')->with('testimonial_info', $testimonial);
    }

    public function updateTestimonial($id, Request $request)
    {
        $testimonial = new Testimonial();
        $testimonial = $testimonial->find($id);
        $testimonial->name = $request->name;
        $testimonial->post = $request->post;
        $testimonial->comment = $request->comment;
        $testimonial->status = $request->status;
        $testimonial->image = $request->previous_image;
        // dd($this->service);
        if ($request->hasFile('testimonial_image')) {
            $testimonial->image = $this->util->uploadHomeFile($request->testimonial_image[0], config('constants.product_img_path') . '/home/testimonials');
        }
        // dd($testimonial);
        $status = $testimonial->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Testimonial Updated Successfuly'
            ];
            return redirect()->route('cms_testimonial')->with('status', $output);
        }
    }

    public function viewBlogCat()
    {
        $categories = BlogCategory::orderBy('id', 'desc')->get();
        // dd($categories);
        return view('frontcms.blog.category-form')->with('categories', $categories);
    }

    public function storeBlogCat(Request $request)
    {
        $blog_cat = new BlogCategory();
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $blog_cat->fill($data);
        $status = $blog_cat->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Category Added Successfuly'
            ];
            return redirect()->back()->with('status', $output);
        }

    }
    public function viewPages(){
        $pages = PageSetting::paginate();
        return view('frontcms.pages.index')->with('pages', $pages);
    }
    public function createPages(){
        return view('frontcms.pages.form');
    }
    public function storePages(Request $request){
        $page = new PageSetting();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->body = $request->body;
        $page->added_by = $request->session()->get('user.id');
        $status = $page->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Page Added Successfuly'
            ];
            return redirect()->route('cms_pages')->with('status', $output);
        }
    }
    public function editPages($id){
        $page_setting = PageSetting::findOrFail($id);
        return view('frontcms.pages.edit')->with('page_setting', $page_setting);
    }
    public function updatePages(Request $request, $id){
        $page_setting = PageSetting::findOrFail($id);
        $page_setting->title = $request->title;
        $page_setting->body = $request->body;
        $page_setting->status = $request->status;
        $status = $page_setting->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Page Updated Successfuly'
            ];
            return redirect()->route('cms_pages')->with('status', $output);
        }
    }
    public function deletePages($id){
        $page_setting = PageSetting::find($id);
        if (!$page_setting) {
            $output = [
                'error' => 1,
                'msg' => 'Page does not Found'
            ];
            return redirect()->route('cms_pages')->with('status', $output);
        }
        $status = $page_setting->delete();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Page deleted Successfuly'
            ];
            return redirect()->route('cms_pages')->with('status', $output);
        }
    }
    public function viewCareer(){
        $careers = Career::paginate();
        return view('frontcms.careers.index')->with('careers', $careers);
    }
    public function createCareer(){
        return view('frontcms.careers.form');
    }
    public function storeCareer(Request $request){
        $career = new Career();
        $career->job_title = $request->job_title;
        $career->job_description = $request->job_description;
        $career->form_link = $request->form_link;
        $career->added_by = $request->session()->get('user.id');
        $status = $career->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Career Added Successfuly'
            ];
            return redirect()->route('cms_career')->with('status', $output);
        }
    }
    public function editCareer($id){
        $career_setting = Career::findOrFail($id);
        return view('frontcms.careers.edit')->with('career_setting', $career_setting);
    }
    public function updateCareer(Request $request, $id){
        $career_setting = Career::findOrFail($id);
        $career_setting->job_title = $request->job_title;
        $career_setting->job_description = $request->job_description;
        $career_setting->form_link = $request->form_link;
        $career_setting->status = $request->status;
        $status = $career_setting->save();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Career Updated Successfuly'
            ];
            return redirect()->route('cms_career')->with('status', $output);
        }
    }
    public function deleteCareer($id){
        $career_setting = Career::findOrFail($id);
        if (!$career_setting ) {
            $output = [
                'error' => 1,
                'msg' => 'Career does not Found'
            ];
            return redirect()->route('cms_career')->with('status', $output);
        }
        $status = $career_setting ->delete();
        if ($status) {
            $output = [
                'success' => 1,
                'msg' => 'Career deleted Successfuly'
            ];
            return redirect()->route('cms_career')->with('status', $output);
        }
    }
}
