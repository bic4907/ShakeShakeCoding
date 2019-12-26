<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileTransformController extends Controller
{
    static public function fileTransform($submissionFile, $filepath, $blocks)
    {
        //$path = $request->input('blocks'); // json 갖고오기
        $blockArrays = json_decode($blocks, true);

        $contents = '';

        foreach($blockArrays as $blockArray) // blocks 돌면서 parse
        {
            $content = $blockArray['content'];
            $depth = $blockArray['depth'];
            $type = $blockArray['type'];
            $checkFor = substr($content,0,3); // for type check 용도

            if($type == "end-for" || $type == "begin-for") // 무시할 것들
                continue;

            $pattern_match = '/\[\[input:(.*)\]\]/U';
            preg_match_all($pattern_match, $content, $matches); // input 들 찾아서 저장

            $pattern_replace = "!\[\[input:(.*?)\]\]!is";
            for($i=0;$i<count($matches[1]);$i++)
            {
                $content = preg_replace($pattern_replace, $matches[1][$i], $content);
            } // content parse 하기

            for($i = 0; $i < $depth; $i++)
            {
                $contents .= "\t";
            }

            $contents .= $content."\n";
            if($checkFor == "for")
            {
                for($i=0;$i<$depth; $i++)
                {
                    $contents .= "\t";
                }
                $contents .= "\tpass\n";
            } // for 문일때, check 후 해당 depth 에 pass 추가
        }

        Storage::disk('local')->put($filepath.$submissionFile->uuid.'.py' , $contents); // py 파일 생성

    }
}
