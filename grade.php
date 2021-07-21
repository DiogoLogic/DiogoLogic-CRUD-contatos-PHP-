<?php
//Função para data em formato Brasileiro
function toBR($data)
{
    $recebe = new Data($data);
    return $recebe->format('d/m/Y H:i:s');
}

?>

<h1>Contatos</h1>
<hr>
<div class="container">
    <table class="table table-bordered table-striped" style="top:40px;">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Data de Criação</th>
                <th>Data de Atualização</th>
                <th><a href="?controller=ContatosController&method=criar" class="btn btn-success btn-sm">Novo</a></th>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($contatos) {
                foreach ($contatos as $contato) {
            ?>
                    <tr>
                        <td><?php echo $contato->nome; ?></td>
                        <td><?php echo $contato->telefone; ?></td>
                        <td><?php echo $contato->email; ?></td>
                        <td><?php echo toBR($contato->data_criacao); ?></td> <!-- Chamando a função ToBR para formato de data -->
                        <td><?php echo $contato->data_atualizacao; ?></td>

                        <td>
                            <a href="?controller=ContatosController&method=editar&id=<?php echo $contato->id; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="?controller=ContatosController&method=excluir&id=<?php echo $contato->id; ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>


                <?php

                }
            } else {
                ?>
                <tr>
                    <td colspan="5">Nenhum registro encontrado</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>