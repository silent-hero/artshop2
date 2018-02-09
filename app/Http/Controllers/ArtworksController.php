<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Artwork;
use App\Category;
use App\User;

class ArtworksController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth", ["except" => ["index", "show"]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter = "", $id = "")
    {
        $artworksPerPage = 12;
        $firstCategory = Category::orderBy("name")->select("id")->first();
        $firstArtist = User::orderBy("name")->select("id")->first();
        $categories = "";
        $authors = "";
        $artworks = "";

        if($filter == "")
        {
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->orderBy("created_at", "asc")->paginate($artworksPerPage);
        }
        else if($filter == "kind")
        {
            $categories = Category::orderBy("name")->get();
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->where("category", $id)->orderBy("created_at", "asc")->paginate($artworksPerPage);
        }
        else if($filter == "artist")
        {
            $authors = User::orderBy("name")->select("id", "name", "surname")->get();
            $artworks = Artwork::with(["getAuthor" => function($query)
            {
                $query->select('id', 'name', 'surname');
            }])->where("author_id", $id)->orderBy("created_at", "asc")->paginate($artworksPerPage);
        }

        return view("gallery/index")->with(["artworks" => $artworks, "filter" => $filter, "firstCategory" => $firstCategory, "firstArtist" => $firstArtist, "categories" => $categories, "authors" => $authors, "sortId" => $id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $authors = User::all()->where("role", "author");
        $authorNamesAndIds = array();
        foreach($authors as $a)
        {
            $authorNamesAndIds[$a->id] = $a->name . " " . $a->surname;
        }
        $categories = Category::all();
        $categories = $categories->pluck("name", "id");

        return view("gallery/add")->with(["categories" => $categories, "authors" => $authorNamesAndIds]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "title" => "required",
            "description" => "required",
            "year" => "required",
            "smallprice" => "required",
            "mediumprice" => "required",
            "bigprice" => "required",
            "thumbnail" => "required|image|max:10000",
            "picture" => "required|image|max:10000"
        ]);

        $thumbnailNameToStore = "";
        $pictureNameToStore = "";

        if($request->hasFile("thumbnail"))
        {
            $thumbnailNameWithExt = $request->file("thumbnail")->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("thumbnail")->getClientOriginalExtension();
            $thumbnailNameToStore = $thumbnailName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("thumbnail")->storeAs("public/artworks", $thumbnailNameToStore);
        }

        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $pictureNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/artworks", $pictureNameToStore);
        }

        $artwork = new Artwork;
        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->year = $request->input("year");
        $artwork->smallprice = $request->input("smallprice");
        $artwork->mediumprice = $request->input("mediumprice");
        $artwork->bigprice = $request->input("bigprice");
        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        $artwork->save();
        return redirect("/")->with("success", "Artwork added!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artwork = Artwork::find($id);
        return view("gallery/artwork")->with("artwork", $artwork);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $artwork = Artwork::find($id);

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $category = $artwork->getOneCategory->id;
        $author = $artwork->getAuthor->id;
        $authors = User::all()->where("role", "author");
        $authorNamesAndIds = array();
        foreach($authors as $a)
        {
            $authorNamesAndIds[$a->id] = $a->name . " " . $a->surname;
        }
        $categories = Category::all();
        $categories = $categories->pluck("name", "id");

        return view("gallery/edit")->with(
        [
            "artwork" => $artwork,
            "categories" => $categories,
            "category" => $category, 
            "author" => $author, 
            "authors" => $authorNamesAndIds
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $this->validate($request,
        [
            "title" => "required",
            "description" => "required",
            "year" => "required",
            "smallprice" => "required",
            "mediumprice" => "required",
            "bigprice" => "required",
            "thumbnail" => "image|max:10000",
            "picture" => "image|max:10000"
        ]);

        $thumbnailNameToStore = "";
        $pictureNameToStore = "";
        $artwork = Artwork::find($id);

        if($request->hasFile("thumbnail"))
        {
            $thumbnailNameWithExt = $request->file("thumbnail")->getClientOriginalName();
            $thumbnailName = pathinfo($thumbnailNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("thumbnail")->getClientOriginalExtension();
            $thumbnailNameToStore = $thumbnailName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("thumbnail")->storeAs("public/artworks", $thumbnailNameToStore);
            Storage::delete("public/artworks/" . $artwork->thumbnail_name);
        }

        if($request->hasFile("picture"))
        {
            $pictureNameWithExt = $request->file("picture")->getClientOriginalName();
            $pictureName = pathinfo($pictureNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file("picture")->getClientOriginalExtension();
            $pictureNameToStore = $pictureName . "-" . substr(md5(openssl_random_pseudo_bytes(20)),-20) . "." . $extension;
            $path = $request->file("picture")->storeAs("public/artworks", $pictureNameToStore);
            Storage::delete("public/artworks/" . $artwork->picture_name);
        }

        $artwork->title = $request->input("title");
        $artwork->category = $request->input("category");
        $artwork->author_id = $request->input("author");
        $artwork->description = $request->input("description");
        $artwork->year = $request->input("year");
        $artwork->smallprice = $request->input("smallprice");
        $artwork->mediumprice = $request->input("mediumprice");
        $artwork->bigprice = $request->input("bigprice");
        if($thumbnailNameToStore != "")
        {
            $artwork->thumbnail_name = $thumbnailNameToStore;
        }
        if($pictureNameToStore != "")
        {
            $artwork->picture_name = $pictureNameToStore;
        }
        $artwork->save();
        return redirect("/")->with("success", "Artwork updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $artwork = Artwork::find($id);
        Storage::delete("public/artworks/" . $artwork->thumbnail_name);
        Storage::delete("public/artworks/" . $artwork->picture_name);
        $artwork->delete();
        return redirect("/")->with("success", "Artwork removed!");
    }

    public function manage()
    {

        if(auth()->user()->role != "admin")
        {
            return redirect("/")->with("error", "Unauthorized");
        }

        $artworks = Artwork::with(["getAuthor" => function($query)
        {
            $query->select('id', 'name', 'surname');
        }])->orderBy("created_at", "asc")->get();
        return view("gallery/manage")->with("artworks", $artworks);
    }

}
