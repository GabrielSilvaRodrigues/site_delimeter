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
            error_log("UsuarioService: Iniciando login para email: $email");
            
            // Verificar se a conexão está funcionando
            if (!$this->usuarioRepository->isConnected()) {
                error_log("UsuarioService: Erro - repositório não conectado");
                throw new \Exception("Erro de conexão com banco de dados");
            }
            
            $usuario = $this->usuarioRepository->findByEmail($email);
            error_log("UsuarioService: Usuario encontrado: " . ($usuario ? 'sim' : 'não'));
            
            if ($usuario) {
                error_log("UsuarioService: Verificando senha para usuário ID: " . $usuario['id_usuario']);
                error_log("UsuarioService: Hash da senha no banco: " . substr($usuario['senha_usuario'], 0, 20) . "...");
                
                $senhaValida = password_verify($senha, $usuario['senha_usuario']);
                error_log("UsuarioService: Verificação de senha: " . ($senhaValida ? 'válida' : 'inválida'));
                
                if ($senhaValida) {
                    unset($usuario['senha_usuario']); // Remove a senha do array retornado
                    
                    // Log de login bem-sucedido
                    error_log("UsuarioService: Login bem-sucedido para usuário ID: " . $usuario['id_usuario']);
                    
                    return $usuario;
                }
                
                error_log("UsuarioService: Senha incorreta para email: $email");
            } else {
                error_log("UsuarioService: Usuário não encontrado para email: $email");
            }
            
            // Log de tentativa de login falhada
            error_log("UsuarioService: Tentativa de login falhada para email: " . $email);
            return false;
        } catch (\Exception $e) {
            error_log("UsuarioService: Erro no login: " . $e->getMessage());
            error_log("UsuarioService: Stack trace: " . $e->getTraceAsString());
            throw $e; // Re-throw para que o controller possa capturar
        }
    }
}
?>