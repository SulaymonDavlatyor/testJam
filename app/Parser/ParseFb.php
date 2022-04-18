<?php
namespace App\Parser;
use App\Models\Book;
use App\interfaces\BookParse;
use Illuminate\Support\Facades\Storage;

class ParseFb implements BookParse{
    protected $chapters = [];
    protected $book;

    public function parseBook($filename)
    {

      $cont =   Storage::get('public/folder/'.$filename);


        $xml = simplexml_load_string($cont);

        foreach ($xml->body->section->section as $val){
            $chapter = '';
            $tmpStr = '';
            foreach($val->title->p as $str){
                $chapter .= $str;
            }
            foreach($val->p as $str){
                $tmpStr .= $str;
            }
            $this->book[] = [$chapter,$tmpStr];


        }
        return $this->book;
    }
}
