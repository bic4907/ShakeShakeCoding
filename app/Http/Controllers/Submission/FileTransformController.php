<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use App\SubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class FileTransformController extends Controller
{
    public function fileTransform(Request $request)
    {
        $submissionFile = new SubmissionFile();

        $user = Auth::user();
        $submissionFile->uuid = Uuid::uuid4();
        $submissionFile->user_id = $user->user_id;

        $path = 'resources/json_test.json'; // json 갖고오기
        $arrays = json_decode(file_get_contents(base_path($path)), true);

        $contents = '';

        foreach($arrays as $array) // blocks 돌면서 parse
        {
            $content = $array['content'];
            $depth = $array['depth'];
            $type = $array['type'];
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


        Storage::disk('local')->put($submissionFile->uuid.'.py' , $contents); // py 파일 생성

        $submissionFile->save();

        // dd($contents);

    }
}
