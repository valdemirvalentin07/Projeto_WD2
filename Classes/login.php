<?php
class Login {
    public function verificar_credenciais($usuario, $senha) {
        // Simula um banco de dados
        $usuarios = [
            'admin' => ['senha' => 'admin123', 'tipo' => 'admin'],
            'tecnico1' => ['senha' => 'tec123', 'tipo' => 'tecnico']
        ];

        if (isset($usuarios[$usuario]) && $usuarios[$usuario]['senha'] === $senha) {
            return $usuarios[$usuario]['tipo'];
        }

        return false;
    }
}
?>
