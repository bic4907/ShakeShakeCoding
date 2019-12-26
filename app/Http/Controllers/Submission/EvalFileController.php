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
    public function grade($question_num, $submission_num , Request $request) {

        /**
         * 제출된 파일 모델 생성
         */
        $submissionFile = new SubmissionFile();
        $user = Auth::user();
        $submissionFile->uuid = Uuid::uuid4();
        $submissionFile->user_id = $user->id;
        $submissionFile->submission_id = $submission_num;
        $submissionFile->question_id = $question_num;

        /**
         * 전달 받은 블럭 JSON과 Input을 가지고 파이썬 파일 생성
         */
        $filepath = '/home/vagrant/code/storage/app/'; // 파일 경로 생성
        FileTransformController::fileTransform(
            $submissionFile,
            $filepath ,
            $request->input('blocks')
        );

        /**
         * 문제에 해당하는 테스트케이스를 가지고 채점시작
         */
        $submissionFile->save();
        $result = $this->evaluate($submission_num, $filepath);

        return $result;
    }


    public function evaluate($submission_num, string $filepath)
    {
        $submission = Submission::where('id',$submission_num)->first();
        $testCases = TestCase::where( 'question_id',$submission->question_id)->get();
        $submissionFile = SubmissionFile::where('submission_id',$submission_num)->first();
        $errorMsg = array();

        foreach($testCases as $testCase)
        {
            $command = "python ".$filepath.$submissionFile->uuid.'.py';
            $process = new Process($command);
            $process->setInput($testCase->input);
            $process->run();

            if (!$process->isSuccessful()) {
                $error = $process->getErrorOutput();
                array_push($errorMsg, -1);
                array_push($errorMsg, $error);
                return $errorMsg;
            }

            if($process->getOutput() != $testCase->output)
            {
                $submission->isCorrect = 0;
                $submission->save();
                array_push($errorMsg,0);
                array_push($errorMsg, "Correct Output:\n".$testCase->output);
                array_push($errorMsg, "User Output:\n".$process->getOutput());
                return $errorMsg;
            }
        }

        return 1;
    }

}
