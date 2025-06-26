<?php

namespace Htdocs\Src\Models\Entity;

class Usuario
{
    private int $id_usuario;
    private string $nome_usuario;
    private string $email_usuario;
    private ?string $senha_usuario;

    public function __construct(?int $id_usuario, string $nome_usuario, string $email_usuario, ?string $senha_usuario = null)
    {
        $this->id_usuario = $id_usuario ?? 0;
        $this->nome_usuario = $nome_usuario;
        $this->email_usuario = $email_usuario;
        $this->senha_usuario = $senha_usuario;
    }

    public function getId(): int
    {
        return $this->id_usuario;
    }

    public function getNome(): string
    {
        return $this->nome_usuario;
    }

    public function setNome(string $nome_usuario): void
    {
        $this->nome_usuario = $nome_usuario;
    }

    public function getEmail(): string
    {
        return $this->email_usuario;
    }

    public function setEmail(string $email_usuario): void
    {
        $this->email_usuario = $email_usuario;
    }

    public function getSenha(): ?string
    {
        return $this->senha_usuario;
    }

    public function setSenha(?string $senha_usuario): void
    {
        $this->senha_usuario = $senha_usuario;
    }
}