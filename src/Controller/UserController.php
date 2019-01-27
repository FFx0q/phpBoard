<?php
    namespace App\Controller;

    use App\Base\Controller;
    use App\Entity\User;
    use App\Base\Route;
    
    class UserController extends Controller
    {
        public function user() 
        {
            $users = $this->getManager()->getRepository(User::class)->findAll();
            
            return $this->render('user/user.twig', 
            [
                'users'=>$users
            ]);
        }

        public function create($username, $password, $email, $created)
        {
            $em = $this->getManager();
            $user = new User();
			$user->setName($username);
			$user->setPassword($password);
			$user->setEmail($email);
			$user->setJoinDate($created);
			$em->persist($user);
			$em->flush();

			Route::redirect('index/index');
        }

        public function usernameExists($username)
        {
            if($this->getManager()->getRepository('App\Entity\User')->findOneBy(['name' => $username]))
            {
                Route::redirect('user/register');
                return true;
            }
            return false;
        }

        public function userExists($username, $password)
        { 
            $user = $this->getManager()->getRepository('App\Entity\User')->findOneBy(['name' => $username]);

            if($user && password_verify($password, $user->getPassword()))
                return true;
            else 
                return false;
        }

        public function logged($username)
        {
            $id = $this->getManager()->getRepository(User::class)->findBy(['name'=> $username])[0]->getId();

            $_SESSION['login'] = $id.'-'.$username;

            Route::redirect('index/index');
        }

        public function register()
        {
            return $this->render('user/register.twig', [
                'link' => '/register.php'
            ]);
        }

        public function login()
        {
            return $this->render('user/login.twig', [
                'link' => '/login.php'
            ]);      
        }

        public function logout()
        {
            unset($_SESSION["login"]);
            Route::redirect("/index/index");
        }
        public function profile()
        {
            $route = $this->containerBuild()->get('App\Base\Route');

            $id = explode('-', ltrim($route->getParam(), '-'))[0];
            $builder = $this->getManager()->createQueryBuilder();

            $user = $builder 
            ->select('u.name, u.join_date, u.avatar_url')
            ->addSelect('(SELECT count(p.id) 
                FROM App\Entity\Post p
                WHERE p.author = u.id) posts
            ')
            ->from('App\Entity\User', 'u')
            ->where('u.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->execute();
            return $this->render('user/profile.twig',
            [
                'user' => $user
            ]);
        }
    }
