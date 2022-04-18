<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Views;
use App\Parser\ParseFb;
use App\Parser\ParseTxt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Parser\Parser;
use App\Parser\ParserFb;
use App\Parser\ParserTxt;

class BookController extends Controller
{
    public function upload(Request $request)
    {

        if ($request->isMethod('post') && $request->file('userfile')) {

            $file = $request->file('userfile');
            $upload_folder = 'public/folder';
            $filename = $file->getClientOriginalName(); // image.jpg
            try {
                Storage::putFileAs($upload_folder, $file, $filename);
            } catch (\Exception $e) {
                echo $e;
            }

            $book = new Book;
            $book->name = $filename;
            $book->path =  'public/folder' . $filename;
            $book->save();
            return redirect($book->id.'/1');

        }

    }

    public function show($id, $page)
    {

        $book = Book::query()->where('id', $id)->first();
        $viewsObj = Views::query()->where('book_id',$id)->where('chapter',$page-1)->first();
        if(is_null($viewsObj)){
            $views = 0;
            $viewsObj = new Views(['book_id'=>$id,'chapter'=>$page-1,'views'=>1]);
            $viewsObj->save();
        }else{
            $views = $viewsObj->views;
            $viewsObj->views +=1;
            $viewsObj->update();
        }


        $parser = new Parser();

        if (preg_match('/txt/', $book->name)) {
            $parser->setParser(new ParseTxt());

        } else {
            $parser->setParser(new ParseFb());
        }
        $book = $parser->parseBook($book->name);


        return view('book', compact('book','id','page','views'));
    }
}
