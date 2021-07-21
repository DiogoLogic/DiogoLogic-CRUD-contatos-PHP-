<?php

class Contato
{
    public $atributos;

    private $mensagem;

    public function __construct()
    {
    }

    public function __set(string $atributo, $valor)
    {
        $this->atributos[$atributo] = $valor;
        return $this;
    }

    public function __get(string $atributo)
    {
        return $this->atributos[$atributo];
    }

    public function __isset($atributo)
    {
        return isset($this->atributos[$atributo]);
    }

    /**
     * Salvar o contato
     * @return boolean
     */
    public function save()
    {



        $data = new Data();
        $colunas = $this->preparar($this->atributos);



        $select = "SELECT * FROM contatos WHERE email = {$colunas['email']}";
        if ($conexao = Conexao::getInstance()) {
            $stmt = $conexao->prepare($select);
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($stmt->rowCount()) {
                    $this->messageError($result);
                    return false;
                }
            }
        }
                                    //Adicionado ao método Save() uma query de criação e atualização de dados tipo data
        if (!isset($this->id)) {
            $mescla_colunas = array_merge(['data_criacao' => $data->dataCriacao()], $colunas);



            $query = "INSERT INTO contatos (" .

                implode(', ', array_keys($mescla_colunas)) .
                ") VALUES (" .
                implode(', ', array_values($mescla_colunas)) . ");";
        } else {
            $mescla_colunas = array_merge(['data_atualizacao' => $data->dataAtualizacao()], $colunas);

            foreach ($mescla_colunas as $key => $value) {
                if ($key !== 'id') {
                    $definir[] = "{$key}={$value}";
                }
            }
            $query = "UPDATE contatos SET " . implode(', ', $definir) . " WHERE id='{$this->id}';";
        }
        if ($conexao = Conexao::getInstance()) {
            $stmt = $conexao->prepare($query);
            if ($stmt->execute()) {
                return $stmt->rowCount();
            }
        }
        return false;
    }

    /**
     * Tornar valores aceitos para sintaxe SQL
     * @param type $dados
     * @return string
     */
    private function escapar($dados)
    {
        if (is_string($dados) & !empty($dados)) {
            return "'" . addslashes($dados) . "'";
        } elseif (is_bool($dados)) {
            return $dados ? 'TRUE' : 'FALSE';
        } elseif ($dados !== '') {
            return $dados;
        } else {
            return 'NULL';
        }
    }

    /**
     * Verifica se dados são próprios para ser salvos
     * @param array $dados
     * @return array
     */
    private function preparar($dados)
    {
        $resultado = array();
        foreach ($dados as $k => $v) {
            if (is_scalar($v)) {
                $resultado[$k] = $this->escapar($v);
            }
        }
        return $resultado;
    }

    /**
     * Retorna uma lista de contatos
     * @return array/boolean
     */
    public static function all()
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM contatos;");
        $result  = array();
        if ($stmt->execute()) {
            while ($rs = $stmt->fetchObject(Contato::class)) {
                $result[] = $rs;
            }
        }
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }

    /**
     * Retornar o número de registros
     * @return int/boolean
     */
    public static function count()
    {
        $conexao = Conexao::getInstance();
        $count   = $conexao->exec("SELECT count(*) FROM contatos;");
        if ($count) {
            return (int) $count;
        }
        return false;
    }

    /**
     * Encontra um recurso pelo id
     * @param type $id
     * @return type
     */
    public static function find($id)
    {
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM contatos WHERE id='{$id}';");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject('Contato');
                if ($resultado) {
                    return $resultado;
                }
            }
        }
        return false;
    }

    /**
     * Destruir um recurso
     * @param type $id
     * @return boolean
     */
    public static function destroy($id)
    {
        $conexao = Conexao::getInstance();
        if ($conexao->exec("DELETE FROM contatos WHERE id='{$id}';")) {
            return true;
        }
        return false;
    }

    /*
        Função para evitar salvar dados duplicados
    */
    public function messageError(array $colunas)
    {
        $recebColuna = implode(',', $colunas);
        $this->mensagem = "existe um registro igual ao {$recebColuna}";
    }

    public function getMessage()
    {
        return $this->mensagem;
    }
}
