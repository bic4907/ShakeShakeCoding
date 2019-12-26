<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use App\Submission;
use App\SubmissionFile;
use App\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class EvalFileController extends Controller
{
    public function grade($question_num, $submission_num, Request $request) {

        /**
         * 제출된 파일 모델 생성
         */
        $submissionFile = new SubmissionFile();
        $user = Auth::user();
        $submissionFile->uuid = Uuid::uuid4();
        $submissionFile->user_id = $user->user_id;
        $submissionFile->question_id = $question_num;

        /**
         * 전달 받은 블럭 JSON과 Input을 가지고 파이썬 파일 생성
         */
        $filepath = ''; // 파일 경로 생성
        FileTransformController::fileTransform($submissionFile, $filepath, $request->input('blocks'));

        /**
         * 문제에 해당하는 테스트케이스를 가지고 채점시작
         */

        $result = $this->evaluate($submission_num, $filepath);

        $submissionFile->save();

        return $result;
    }


    public function evaluate($submission_num, string $filepath)
    {
        $submission = Submission::where($submission_num,'id')->first();
        $testCases = TestCase::where($submission->question_id, 'question_id')->get();
        $submissionFile = SubmissionFile::where($submission_num, 'submission_id')->first();

        foreach($testCases as $testCase)
        {
            $command = "python ".$filepath.$submissionFile->uuid.'.py';
            $process = new Process($command);
            $process->setInput($testCase->input);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            if($process->getOutput() != $testCase->output)
            {
                $submission->isCorrect = 0;
                $submission->save();
            }
        }

        return;
    }

}
