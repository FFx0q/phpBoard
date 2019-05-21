<?php
    namespace App\Controller;

    use App\Base\Controller;
    use App\Base\View;
    use App\Base\Router;
    use App\Base\Request;
    use App\Base\Session;
    use App\Models\Question;
    use App\Models\Post;
    
    
    class QuestionController extends Controller
    {
        public function ShowAction()
        {
            $question = new Question();
            $posts = $question->findQuestionById($this->getRouter()->getParam());

            return View::render('topic/post.twig',
            [
                'posts' => $posts
            ]);
        }

        public function CreateAction()
        {
            return View::render('topic/create.twig');
        }

        public function SaveAction()
        {
            $question = new Question();
            $post = new Post();
            $date = new \DateTime();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $uid = (int)Session::get('user_id');
                $title = isset($_POST['title']) ? trim(htmlspecialchars($_POST['title'])) : " ";
                $content = isset($_POST['post']) ? trim(htmlspecialchars($_POST['post'])) : " ";
                $qid = $question->save($uid, $title, $date->getTimestamp());
                $post->save($uid, $qid, $content, $date->getTimestamp());

                Router::redirect("/question/show/".$qid);
            }
        }
    }