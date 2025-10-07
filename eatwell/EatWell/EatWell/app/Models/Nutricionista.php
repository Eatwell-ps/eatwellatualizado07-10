<?php
namespace App\Models;

use App\Core\Model;
use App\Repositories\NutricionistaRepository;

class Nutricionista extends Model {
    public $id;
    public $nome;
    public $email;
    public $telefone;
    public $crn;
    public $especialidade;
    public $data_cadastro;
    public $senha; 

    public function construct() {
        parent::construct(new NutricionistaRepository());
    }

    public function save() {
        $data = [
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'crn' => $this->crn,
            'especialidade' => $this->especialidade,
            'senha' => password_hash($this->senha, PASSWORD_DEFAULT) 
        ];

        if ($this->id) {
            return $this->repository->update($this->id, $data);
        } else {
            return $this->repository->create($data);
        }
    }

   
    public function findByEmail($email) {
        return $this->repository->findByEmail($email);
    }

    public function getClientes() {
        return $this->repository->getClientesByNutricionista($this->id);
    }

    public function getPlanosAlimentares() {
        return $this->repository->getPlanosByNutricionista($this->id);
    }

    public function getEstatisticas() {
        return $this->repository->getEstatisticas($this->id);
    }
}