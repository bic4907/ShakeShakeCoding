<?php

use App\Enums\UserType;
use App\Submission;
use App\User;
use Illuminate\Database\Seeder;
use App\Question;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class, 40)->create();

        foreach($users as $user){
            if($user->usertype == "Professor"){
                $questions = factory(Question::class, 1)->create(['professor_id'=>$user->id]);

                $students = User::where('usertype',"Student")->get();
                foreach($questions as $question){
                    foreach($students as $student){
                        factory(Submission::class, 1)->create(['question_id'=>$question->id, 'student_id'=> $student->id]);
                    }
                }
            }
        }

    }
}
