<?php

namespace App\Parser;
use App\interfaces\BookParse;

class Parser{


        protected $parser;


        public function setParser(BookParse $parser){
            $this->parser = $parser;

        }
        public function parseBook($book){

           $result =  $this->parser->parseBook($book);
           return $result;
        }



}
