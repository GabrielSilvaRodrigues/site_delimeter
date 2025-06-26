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
        $usuario = $this->usuarioRepository->findByEmail($email);
        if ($usuario && password_verify($senha, $usuario['senha_usuario'])) {
            unset($usuario['senha_usuario']); // Remove a senha do array retornado
            return $usuario;
        }
        return false;
    }
}
?>