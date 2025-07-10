<?php
namespace Htdocs\Src\Services;

use Htdocs\Src\Models\Repository\UsuarioRepository;
use Htdocs\Src\Models\Entity\Usuario;

class UsuarioService {
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository) {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function getUsuarioRepository() {
        return $this->usuarioRepository;
    }

    public function criar(Usuario $usuario) {
        return $this->usuarioRepository->save($usuario);
    }

    public function listar() {
        return $this->usuarioRepository->findAll();
    }

    public function login($email, $senha) {
        try {
            $usuario = $this->usuarioRepository->findByEmail($email);
            if ($usuario && password_verify($senha, $usuario['senha_usuario'])) {
                unset($usuario['senha_usuario']); // Remove a senha do array retornado
                
                // Log de login bem-sucedido
                error_log("UsuarioService: Login bem-sucedido para usuário ID: " . $usuario['id_usuario']);
                
                return $usuario;
            }
            
            // Log de tentativa de login falhada
            error_log("UsuarioService: Tentativa de login falhada para email: " . $email);
            return false;
        } catch (\Exception $e) {
            error_log("UsuarioService: Erro no login: " . $e->getMessage());
            return false;
        }
    }
}
?>