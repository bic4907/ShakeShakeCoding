<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileTransformController extends Controller
{
    static public function fileTransform($submissionFile, $filepath , $blocks)
    {
        if($blocks != null)
            $blockArrays = $blocks;
        else return 0;


        $contents = '';

        /**
         * 사용자에게 현재 틀린 줄을 알려주기 위해 매칭하기 위한 파일을 생성함
         */
        $currentLine = 0;
        $debugInfo = array();

        foreach($blockArrays as $blockArray) // blocks 돌면서 parse
        {
            $content = $blockArray['content'];
            $depth = $blockArray['depth'];
            $type = $blockArray['type'];
            $uuid = $blockArray['uuid'];

            $checkFor = substr($content,0,3); // for type check 용도

            if($type == "end-for" || $type == "begin-for") // 무시할 것들
                continue;




            for($i = 0; $i < $depth; $i++)
            {
                $contents .= "\t";
            }

            $contents .= $content."\n";
            $currentLine += 1; // 파일에서 한 줄 작성되었기 때문에 1증가

            if($checkFor == "for")
            {
                for($i=0;$i<$depth; $i++)
                {
                    $contents .= "\t";
                }
                $contents .= "\tpass\n";
                $currentLine += 1; // 파일에서 한 줄 작성되었기 때문에 1증가

            } // for 문일때, check 후 해당 depth 에 pass 추가

            $debugInfo[$currentLine] = $uuid;
        }

            //dd($contents);

        Storage::disk('local')->put("file/".$submissionFile->uuid.'.py' , $contents); // py 파일 생성
        Storage::disk('local')->put("file/".$submissionFile->uuid.'.debug' , json_encode($debugInfo)); // debug 파일 생성

    }


}
