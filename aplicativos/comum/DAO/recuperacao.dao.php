<?php
/**
 * Created by PhpStorm.
 * User: dlepera
 * Date: 13/12/16
 * Time: 17:40
 */

namespace Comum\DAO;


trait Recuperacao {
    /**
     * @var int $usuario
     * ID do usuário que está solicitando a recuperação da senha
     */
    protected $usuario;

    /**
     * @var string $hash
     * Hash MD5 identificadora dessa solicitação
     */
    protected $hash;

    /**
     * @return int
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * @param int $usuario
     */
    public function setUsuario($usuario) {
        $this->usuario = filter_var($usuario, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]);
    }

    /**
     * @return string
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * @param string $hash   Hash de identificação da solicitação
     * @param bool   $cripto Define se a $hash deve ser passada em criptografia
     */
    public function setHash($hash, $cripto = false) {
        if ($cripto) {
            $hash = md5($hash);
        } // Fim if

        $this->hash = filter_var($hash, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }
}
