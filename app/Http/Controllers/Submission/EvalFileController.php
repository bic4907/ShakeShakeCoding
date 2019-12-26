<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use App\Submission;
use App\SubmissionFile;
use App\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class EvalFileController extends Controller
{
    public function grade($question_num, $submission_num, Request $request)
    {

        /**
         * 제출된 파일 모델 생성
         */
        $submissionFile = SubmissionFile::where('submission_id', $submission_num)->first();
        if ($submissionFile == null) {
            $submissionFile = new SubmissionFile();
            $user = Auth::user();
            $submissionFile->uuid = Uuid::uuid4();
            $submissionFile->user_id = $user->id;
            $submissionFile->submission_id = $submission_num;
            $submissionFile->question_id = $question_num;
        }

        /**
         * 전달 받은 블럭 JSON과 Input을 가지고 파이썬 파일 생성
         */
        $filepath = '/home/vagrant/code/storage/app/file/'; // 파일 경로 생성
        FileTransformController::fileTransform(
            $submissionFile,
            $filepath,
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
        $submission = Submission::where('id', $submission_num)->first();
        $testCases = TestCase::where('question_id', $submission->question_id)->get();
        $submissionFile = SubmissionFile::where('submission_id', $submission_num)->first();

        $gradeResult = array(
            'debug' => array('line' => null, 'message' => null),
            'log' => array()
        );

        foreach ($testCases as $testCase) {
            $command = "python3 " . $filepath . $submissionFile->uuid . '.py';
            $process = new Process($command);
            $process->setInput($testCase->input);
            $process->setTimeout(5);
            $process->run();


            $testCaseOutput = str_replace("\\r", "", $testCase->output);


            if (!$process->isSuccessful()) {
                $error = $process->getErrorOutput();
                $errorArr = explode(PHP_EOL, $error);

                array_push($gradeResult['log'], '문법오류');
                $gradeResult['log'] = array_merge($gradeResult['log'], $errorArr);


                /*
                 * 디버그 정보를 읽어서 라인에 표시를 해주어야함
                 */
                $targetRow = null;
                foreach ($errorArr as $row) {
                    $isFound = false;

                    $searchIndex = strpos($row, "line ");


                    if ($searchIndex != false) {
                        $isFound = true;

                        $iterPos = $searchIndex + 5; // 숫자찾기 시작지점

                        $numStr = '';
                        while ($iterPos <= strlen($row) - 1 and intval($row[$iterPos]) >= 0 and intval($row[$iterPos]) <= 9) {

                            $numStr .= $row[$iterPos];
                            $iterPos++;
                        }
                        $debugInfo = json_decode(Storage::disk('local')->get("file/" . $submissionFile->uuid . '.debug'), true);
                        $gradeResult['debug']['line'] = $debugInfo[intval($numStr)];
                        $gradeResult['debug']['message'] = $this->makeErrorMessage($error);;


                    }

                    if ($isFound) break;
                }

            } else if (trim($process->getOutput()) != trim($testCaseOutput)) {
                $submission->isCorrect = 0;
                $submission->save();

                array_push($gradeResult['log'], '실행은 되었으나 잘못된 값이 나왔습니다.');
                array_push($gradeResult['log'], "올바른 값:\n" . $testCase->output);
                array_push($gradeResult['log'], "프로그램 실행 값:\n" . $process->getOutput());
            } else {
                array_push($gradeResult['log'], '정답입니다!');
            }
        }
        return $gradeResult;
    }


    public function makeErrorMessage($error)
    {
        $errorMsg = '프로그램 실행이 안되는 오류입니다.';

        preg_match("/NameError/", $error, $errorTypeName);
        preg_match("/ZeroDivisionError/", $error, $errorTypeZeroDiv);
        preg_match("/SyntaxError/", $error, $errorTypeSyntax);
        preg_match("/TypeError/", $error, $errorTypeErr);
        preg_match("/AttributeError/", $error, $errorTypeAttrErr);

        if ($errorTypeName) {
            preg_match("/(?)name \'(.*)\'/", $error, $errorName);
            $errorMsg = $errorMsg . $errorName[0] . '변수 선언이 제대로 됐는지 확인하세요.';
        } else if ($errorTypeZeroDiv) {
            $errorMsg = $errorMsg . '0으로 수를 나눌 수 없습니다.';
        } else if ($errorTypeSyntax) {
            $errorMsg = $errorMsg . '문법을 확인하세요.';
        } else if ($errorTypeErr) {
            $errorMsg = $errorMsg . '숫자와 문자를 혼용하여 썼네요. 자료형을 확인해주세요.';
        } else if ($errorTypeAttrErr) {
            $errorMsg = $errorMsg . '배열 속성이름, 모듈 이름이 잘못되었습니다.';
        }

        return $errorMsg;
    }

}
