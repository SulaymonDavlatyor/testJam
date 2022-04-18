<?php
namespace App\Parser;
use App\interfaces\BookParse;
use Illuminate\Support\Facades\Storage;


class ParseTxt implements BookParse
{

    public function parseBook($filename)
    {
        $cont =   Storage::get('public/folder/'.$filename);

        preg_match_all('/Глава.*/',$cont,$matches);
        $chapters = array_chunk($matches[0],15)[0];

        $index = strpos($cont,$chapters[count($chapters)-1]);
        $cont = substr($cont,$index);

          foreach($chapters as $key=> $chapter){
             if($key != count($chapters) -1) {
                 preg_match_all('/' . $chapter . '.*[\s\S]*' . $chapters[$key + 1] . '/', $cont, $matchesBook);
                 $book[] = [$chapter, $matchesBook[0][0]];
             }
          }
        return $book;

    }
}
