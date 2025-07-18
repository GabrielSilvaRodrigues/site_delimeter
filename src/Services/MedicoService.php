<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\MedicoRepository;
use Htdocs\Src\Models\Entity\Medico;

class MedicoService {
    private $medicoRepository;

    public function __construct(MedicoRepository $medicoRepository) {
        $this->medicoRepository = $medicoRepository;
    }

    public function getMedicoRepository() {
        return $this->medicoRepository;
    }

    public function criar(Medico $medico) {
        try {
            return $this->medicoRepository->save($medico);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function listar() {
        return $this->medicoRepository->findAll();
    }

    public function login($email, $senha) {
        // Usar UsuarioRepository para login, não MedicoRepository
        $usuarioRepository = new \Htdocs\Src\Models\Repository\UsuarioRepository();
        $usuario = $usuarioRepository->findByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha_usuario'])) {
            // Verificar se o usuário é médico
            $medico = $this->medicoRepository->findById($usuario['id_usuario']);
            if ($medico) {
                unset($usuario['senha_usuario']);
                return array_merge($usuario, $medico);
            }
        }
        return false;
    }

    public function atualizarConta(Medico $medico) {
        try {
            return $this->medicoRepository->update($medico);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function mostrarConta($id_usuario) {
        return $this->medicoRepository->findById($id_usuario);
    }

    public function deletarConta($id_usuario) {
        return $this->medicoRepository->delete($id_usuario);
    }
}
?>