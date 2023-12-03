<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;

class AuthController extends Controller
{

    public function login()
    {
        $this->setLayout('authLayout');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        // $errors = [];

        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'Thanks for registering.');
                Application::$app->response->redirect('/');

                return 'Success';
            }

            return $this->render('register', [
                'model' => $user
            ]);
            // $firstname = $request->getBody()['firstname'];
            // if (!$firstname) {
            //     $errors['firstname'] = 'This field is requiered.';
            // }
            // return $this->render('register', [
            //     'errors' => $errors
            // ]);
        }
        $this->setLayout('authLayout');
        return $this->render('register', [
            'model' => $user
        ]);
    }
}
