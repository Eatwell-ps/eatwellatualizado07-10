<?php
namespace App\Repositories;

use App\Core\Repository;

class NutricionistaRepository extends Repository {
    protected $table = 'nutricionistas';

    public function create(array $data) {
        $sql = "INSERT INTO {$this->table} 
                (nome, email, telefone, crn, especialidade, senha) 
                VALUES (:nome, :email, :telefone, :crn, :especialidade, :senha)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'],
            ':crn' => $data['crn'],
            ':especialidade' => $data['especialidade'],
            ':senha' => $data['senha'] ?? null 
        ]);
    }

    public function update($id, array $data) {
        $sql = "UPDATE {$this->table} SET 
                nome = :nome, 
                email = :email, 
                telefone = :telefone, 
                crn = :crn, 
                especialidade = :especialidade";

    
        if (!empty($data['senha'])) {
            $sql .= ", senha = :senha";
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);

        $params = [
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'],
            ':crn' => $data['crn'],
            ':especialidade' => $data['especialidade'],
            ':id' => $id
        ];

        if (!empty($data['senha'])) {
            $params[':senha'] = $data['senha'];
        }

        return $stmt->execute($params);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    
}