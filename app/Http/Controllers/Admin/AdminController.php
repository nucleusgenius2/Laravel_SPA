<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;



class AdminController {

    public function authorization($request){
        if ($request->user('sanctum')) {
            return "auth";
        }
        else {
            return "false";
        }
    }

    //delete post
    public function deletePost($id){
        $array_news = News::where('id', '=', $id)->delete();
        return 'success';
    }



    //save or update post
    public function saveAndUpdatePost(Request $req){

        $id = $req->input('id');

        //update or new img
        if( gettype($req->img) == 'object' ){

            $validation = $req->validate([
                'img' => 'required|image|mimes:png,jpg,jpeg'
            ]);

            //upload img in dir
            $imageName = time().'.'.$req->img->extension();
            $req->img->move(public_path('images'), $imageName);

            //name img for bd
            $imageName = '/images/'.$imageName;
        }
        //the image does not change
        else {
            if ( $req->img ){
                $imageName = $req->img;
            }
            else {
                $imageName = '';
            }
        }

        //validation field name
        $validation = $req->validate([
            'name' =>'required|min:3',
        ] );


        //add post
        if ( $id =='add' ){
            $array_save_new = [
                'name' => $req->input('name'),
                'content' => $req->input('content'),
                'short_description' => $req->input('short_description') ?? '',
                'seo_title' => $req->input('seo_title', '') ?? '',
                'seo_discription' => $req->input('seo_discription') ?? '',
                'img' => $imageName,
                'id_category' => $req->input('id_category') ?? '',
                'autor' => $req->input('autor') ?? '',
            ];
            $flight= News::create($array_save_new);

            return ["status" => "save_success", "redirect" => "true", "id" => $flight['id'] ];

        }
        //update post
        else {
            $array_save_new = News::where('id', '=', $id)->update([
                'name' => $req->input('name'),
                'content' => $req->input('content'),
                'short_description' => $req->input('short_description') ?? '',
                'seo_title' => $req->input('seo_title', '') ?? '',
                'seo_discription' => $req->input('seo_discription') ?? '',
                'img' => $imageName,
                'id_category' => $req->input('id_category') ?? '',
           ]);
            return ["status" => "save_success", "redirect" => "false" ];
        }


    }


}


