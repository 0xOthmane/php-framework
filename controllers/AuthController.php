<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;

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

        $registerModel = new RegisterModel();
        if ($request->isPost()) {
            $registerModel->loadData($request->getBody());
            if ($registerModel->validate() && $registerModel->register()) {

                return 'Success';
            }

            return $this->render('register', [
                'model' => $registerModel
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
            'model' => $registerModel
        ]);
    }
}
