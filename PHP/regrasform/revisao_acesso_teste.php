<?php

class revisao_acesso_teste extends revisao_de_acesso_aprovacao
{
    public function __construct(){}

    function revisao_acesso_teste($control, $idSolicitacao, $idAtividade="",$fluxo = '', $idMovimentacao="", $post)
    {
        $this->revisao_de_acesso_aprovacao($control, $idSolicitacao, $idAtividade, '', $idMovimentacao, $post);
    }

   /*public function aprovacaoGestorUsuario($post, $idSolicitacao, $idAtividade, $idProximaAtiv, $dataMovimentacao, $idSolicitante, $idUsuario)
    {
        die('Aprovacao Gestor subclasse');
    }*/

    /*public function aprovacaoGestorGrupo($idSolicitacao, $idAtividade, $idUserLogado, $idProximaAtiv, $dataMovimentacao, $idSolicitante, $idUsuario)
    {
        die('Aprovacao Gestor Grupo ssubclasse');
    }*/

    /*public function cancelaSolicitacoes($post, $dataMovimentacao, $codUsuario)
    {
        die('Cancelamento!');
    }*/

    public function aprovacaoFinal($post, $idProximaAtiv, $codUsuario, $idSolicitacao, $idAtividade, $dataMovimentacao, $idMovimentacao, $documentos)
    {
        die('Aprovacao final');
    }
}