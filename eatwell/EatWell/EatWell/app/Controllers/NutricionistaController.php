<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Nutricionista;

class NutricionistaController extends Controller {
    public function index() {
        $nutricionistaModel = new Nutricionista();
        $nutricionistas = $nutricionistaModel->all();
        
        $this->view('nutricionistas/index', [
            'nutricionistas' => $nutricionistas,
            'title' => 'Gerenciamento de Nutricionistas - EatWell'
        ]);
    }

    public function create() {
        if ($this->isPost()) {
            $nutricionista = new Nutricionista();
            
            $errors = $this->validarDados($_POST);
            if (empty($_POST['senha'])) {
                $errors['senha'] = 'A senha é obrigatória.';
            }

            if (!empty($errors)) {
                $this->view('nutricionistas/create', [
                    'errors' => $errors,
                    'nutricionista' => (object)$_POST,
                    'title' => 'Novo Nutricionista - EatWell'
                ]);
                return;
            }

            $nutricionista->nome = $this->sanitize($_POST['nome']);
            $nutricionista->email = $this->sanitize($_POST['email']);
            $nutricionista->telefone = $this->sanitize($_POST['telefone']);
            $nutricionista->crn = $this->sanitize($_POST['crn']);
            $nutricionista->especialidade = $this->sanitize($_POST['especialidade']);
            $nutricionista->senha = $_POST['senha']; // 🔹 senha será criptografada no model

            if ($nutricionista->save()) {
                $this->setFlash('success', 'Nutricionista cadastrado com sucesso!');
                $this->redirect('/nutricionistas');
            } else {
                $this->setFlash('error', 'Erro ao cadastrar nutricionista.');
                $this->view('nutricionistas/create', [
                    'nutricionista' => $nutricionista,
                    'title' => 'Novo Nutricionista - EatWell'
                ]);
            }
        } else {
            $this->view('nutricionistas/create', [
                'title' => 'Novo Nutricionista - EatWell'
            ]);
        }
    }

    
    public function login() {
        if ($this->isPost()) {
            $email = $this->sanitize($_POST['email']);
            $senha = $_POST['senha'] ?? '';

            $nutricionistaModel = new Nutricionista();
            $user = $nutricionistaModel->findByEmail($email);

            if ($user && password_verify($senha, $user['senha'])) {
                $_SESSION['nutricionista_id'] = $user['id'];
                $_SESSION['nutricionista_nome'] = $user['nome'];

                $this->redirect('/nutricionistas/dashboard/' . $user['id']);
            } else {
                $this->view('nutricionistas/login', [
                    'error' => 'Email ou senha inválidos.',
                    'title' => 'Login - EatWell'
                ]);
            }
        } else {
            $this->view('nutricionistas/login', [
                'title' => 'Login - EatWell'
            ]);
        }
    }


    public function logout() {
        unset($_SESSION['nutricionista_id']);
        unset($_SESSION['nutricionista_nome']);
        session_destroy();
        $this->redirect('/nutricionistas/login');
    }


}