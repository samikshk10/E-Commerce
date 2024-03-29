<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Image;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function AllBlogCategory(){
        $blogcategories = BlogCategory::latest()->get();
        return view('backend.blog.category.blogcategory_all',compact('blogcategories'));
    } // end method 

    public function AddBlogCategory(){
        return view('backend.blog.category.blogcategory_add');
    } // end method

    public function StoreBlogCategory(Request $request)
    {
        BlogCategory::insert([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-', $request->blog_category_name)),
            'created_at' => Carbon::now(),

        ]);
        $notification = array(
            'message' => 'Blog Category Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.category')->with($notification);
    } // end method

    public function EditBlogCategory($id){
        $blogcategory = BlogCategory::findOrFail($id);
        return view('backend.blog.category.blogcategory_edit', compact('blogcategory'));
    } // end method

    public function UpdateBlogCategory(Request $request){
        $blog_id = $request->id;

        BlogCategory::findOrFail($blog_id)->update([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-', $request->blog_category_name)),

        ]);
        $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.category')->with($notification);
    } // end method

    public function DeleteBlogCategory($id){
        BlogCategory::findOrFail($id)->delete();


        $notification = array(
            'message' => 'Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // end method

    // for blog post method CRUD
    public function AllBlogPost(){
        $blogpost = BlogPost::latest()->get();
        return view('backend.blog.post.blogpost_all',compact('blogpost'));
    } // end method

    public function AddBlogPost(){
        $blogcategory = BlogCategory::latest()->get();
        return view('backend.blog.post.blogpost_add',compact('blogcategory'));
    } // end method

    public function StoreBlogPost(Request $request){
        $image = $request->file('post_image');

        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(1103, 906)->save('upload/blog/' . $name_gen);
        $save_url = 'upload/blog/' . $name_gen;

        BlogPost::insert([
            'category_id' => $request->category_id,
            'post_title' => $request->post_title,
            'post_short_description' => $request->post_short_description,
            'post_long_description' => $request->post_long_description,
            'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),

            'post_image' => $save_url,
            'created_at' => Carbon::now()
        ]);
        $notification = array(
            'message' => 'Blog Post Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.post')->with($notification);
    } // end method

    public function EditBlogPost($id){
        $blogcategory = BlogCategory::latest()->get();
        $blogpost = BlogPost::findOrFail($id);

        return view('backend.blog.post.blogpost_edit',compact('blogcategory','blogpost'));
        
    } // end method

    public function UpdateBlogPost(Request $request){
        $post_id = $request->id;
        $old_img = $request->old_image;

        if ($request->file('post_image')) {
            $image = $request->file('post_image');

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(1103, 906)->save('upload/blog/' . $name_gen);
            $save_url = 'upload/blog/' . $name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }

            BlogPost::findOrFail($post_id)->update([
                'category_id' => $request->category_id,
                'post_title' => $request->post_title,
                'post_short_description' => $request->post_short_description,
                'post_long_description' => $request->post_long_description,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
    
                'post_image' => $save_url,
                'updated_at' => Carbon::now()
            ]);
            $notification = array(
                'message' => 'Blog Post Updated with image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.blog.post')->with($notification);

        } else {
             BlogPost::findOrFail($post_id)->update([
                'category_id' => $request->category_id,
                'post_title' => $request->post_title,
                'post_short_description' => $request->post_short_description,
                'post_long_description' => $request->post_long_description,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
    
                'updated_at' => Carbon::now()
            ]);
            $notification = array(
                'message' => 'Blog Post Updated without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.blog.post')->with($notification);
        } // end else
    } // end method

    public function DeleteBlogPost($id){
        $blogpost = BlogPost::findOrFail($id);
        $img = $blogpost->post_image;

        unlink($img);

        BlogPost::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Blog Post deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method


    // Frontend blog all method
    public function AllBlog(){
        $blogcategories = BlogCategory::latest()->get();
        $blogpost = BlogPost::latest()->get();
        return view('frontend.blog.home_blog',compact('blogcategories','blogpost'));
    } // end method

    public function BlogDetails($id, $slug){
        $blogcategories = BlogCategory::latest()->get();
        $blogdetails = BlogPost::findOrFail($id);

        $breadcat = BlogCategory::where('id',$id)->get();

        return view('frontend.blog.blog_details',compact('blogcategories','blogdetails','breadcat'));
    } // end method

    public function PostCategory($id, $slug){
        $blogcategories = BlogCategory::latest()->get();
        $blogpost = BlogPost::where('category_id',$id)->get();

        $breadcat = BlogCategory::where('id',$id)->get();

        return view('frontend.blog.category_post',compact('blogcategories','blogpost','breadcat'));
    } // end method
}
