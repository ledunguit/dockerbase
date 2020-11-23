<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;
use \Application\Frontend\Model\Questions as Questions;
use \Application\Frontend\Model\Attempts as Attempts;

class Result extends Model {
    public function getResultByQuiz($quizId) {
            $list = [];
            $model = $this->select('attempts.id id')
                ->from('attempts, quiz')
                ->where('quiz.id = attempts.quizid')
                ->and('quiz.category = '. $quizId)
                ->execute()
                ->fetchAll();
            if($model){
                $attempt = new Attempts();
                foreach ($model as $key) {
                    array_push($list, $attempt->getSimpleAttemptsByQuiz($key->id));
                }
                var_dump($list);
                return $list;
            }
            return null;
    }
}