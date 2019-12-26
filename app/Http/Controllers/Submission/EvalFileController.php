<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use App\Submission;
use App\SubmissionFile;
use App\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class EvalFileController extends Controller
{
    public function evaluationFile(SubmissionFile $submissionFile)
    {
        $testCases = TestCase::where($submissionFile->question_id, 'question_id')->get();
        $submission = Submission::where($submissionFile->submission_id,'id')->first();

        foreach($testCases as $testCase)
        {
            $command = "python /home/vagrant/code/storage/app/".$submissionFile->uuid.'.py';
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
    }
}
