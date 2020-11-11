<?php if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])): ?>
    <div class="alert alert-success">
        <strong>Success!</strong> <?php echo $_SESSION['mensagem'] ?>
    </div>
<?php endif;
unset($_SESSION['mensagem']);
?>

<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>              
        <li class="active">Processos</li>
        <li class="active">Central de Tarefas</li>
  </ol>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Central de Tarefas </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->helper->alertMessage(); ?>
                </div>
            </div>

            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
                            Atividade Pendentes
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Solicitações
                            Finalizadas
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">
                            Minhas Solicitações
                        </a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#tab_content4" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">
                            Em andamento
                        </a>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <div class="x_content">
                            <div id="datatable-responsive_wrapper"
                                 class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-6 col-md-12"></div>
                                    <div class="col-sm-6 col-md-12"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                               cellspacing="0" width="100%" role="grid"
                                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0"
                                                    aria-controls="datatable-responsive" rowspan="1" colspan="1"
                                                    style="width: 5px;" aria-sort="ascending"
                                                    aria-label="First name: activate to sort column descending">
                                                    Num.Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Usuario
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Processo
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Data
                                                    Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">
                                                    Desc.Atividade
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">
                                                    Status
                                                </th>
                                            </thead>
                                            <tbody>

                                            <?php foreach ($atividade as $value):
                                                //echo strtotime(date('Y-m-d H:i:s')) .' >= '. strtotime($value['dataSolicitacao'])."<br>";
                                                if($value['idForm'] != 3 || (strtotime(date('Y-m-d H:i:s')) >= strtotime($value['dataSolicitacao']))):                                                    
                                                    $from = $value['url'];
                                                    $url = URL . "/Fluxo/callRegras/$from/".$value['idSolicitacao']."/".$value['idAtividade']."/".'0/'.$value['idMovimentacao'];
                                            ?>                                                
                                                <tr onclick="location.href='<?php echo $url; ?>';loadingPagia()">
                                                    <td><?php echo ucfirst($value['idSolicitacao']) ?></td>
                                                    <td><?php echo ucfirst($value['usuarios']) ?></td>
                                                    <td><?php echo ucfirst($value['descricao']) ?></td>
                                                    <td><?php echo date('d/m/Y H:i:s', strtotime($value['dataSolicitacao'])); ?></td>
                                                    <td><?php echo ucfirst($value['descAtividade']) ?></td>
                                                    <td><?php echo ucfirst($value['HStatus']) ?></td>
                                                </tr>
                                            <?php 
                                                endif;
                                            endforeach;
                                            ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <div class="x_content">
                            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-6 col-md-12"></div>
                                    <div class="col-sm-6 col-md-12"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                               cellspacing="0" width="100%" role="grid"
                                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0"
                                                    aria-controls="datatable-responsive" rowspan="1" colspan="1"
                                                    style="width: 5px;" aria-sort="ascending"
                                                    aria-label="First name: activate to sort column descending">
                                                    Num.Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Usuario
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Processo
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Data
                                                    Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Fim
                                                    Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">
                                                    Status
                                                </th>
                                            </thead>
                                            <tbody>                                                
                                                <?php foreach ($atividadeFIm as $value): ?>
                                                <tr onclick="location.href='<?php echo URL ?>/Fluxo/callRegras/<?php echo $value['url'] ?>/<?php echo $value['idSolicitacao'] ?>/0/0/0/0';loadingPagia()">
                                                    <td><?php echo ucfirst($value['idSolicitacao']) ?></td>
                                                    <td><?php echo ucfirst($value['usuarios']) ?></td>
                                                    <td><?php echo ucfirst($value['descricao']) ?></td>
                                                    <td><?php echo date('d/m/Y H:i:s', strtotime($value['dataSolicitacao'])); ?></td>
                                                    <td><?php echo date('d/m/Y H:i:s', strtotime($value['dataFim'])); ?></td>
                                                    <td><?php echo ucfirst($value['HStatus']) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                        <div class="x_content">
                            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form action="<?= URL; ?>/Fluxo/cancelaSolicitacoes" id="formSolicitacao" method="post">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-12"></div>
                                        <div class="col-sm-6 col-md-12"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                   cellspacing="0" width="100%" role="grid"
                                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                                <thead>
                                                <tr role="row">
                                                    <?php //if($value['idSolicitante'] == $_SESSION['idUsrTotvs']): ?>
                                                    <th><input type="checkbox" id="checkAllSolicitacao"></th>
                                                    <?php //endif; ?>
                                                    <th class="sorting_asc" tabindex="0"
                                                        aria-controls="datatable-responsive" rowspan="1" colspan="1"
                                                        style="width: 5px;" aria-sort="ascending"
                                                        aria-label="First name: activate to sort column descending">
                                                        Num.Solicitação
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Usuario
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">Processo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">Data
                                                        Inicio
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">Atividade
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">
                                                        Responsável
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">
                                                        Status
                                                    </th>
                                                </thead>
                                                <tbody>

                                                <?php foreach ($atividadeSolicitante as $value): ?>
                                                    <?php $url = URL . "/Fluxo/callRegras/".$value['url']."/".$value['idSolicitacao']."/0/0/0"; ?>
                                                    <tr>
                                                        <?php if($value['idSolicitante'] == $_SESSION['idUsrTotvs']): ?>
                                                        <td><input type="checkbox" name="solicitacao[]" class="checkSolicitacao" value="<?= $value['idSolicitacao']; ?>"></td>
                                                        <?php endif; ?>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo ucfirst($value['idSolicitacao']) ?></td>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo ucfirst($value['usuarios']) ?></td>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo ucfirst($value['descricao']) ?></td>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo date('d/m/Y H:i:s', strtotime($value['dataSolicitacao'])); ?></td>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo ucfirst($value['descricaoAtividade']) ?></td>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo ucfirst($value['Responsavel']) ?></td>
                                                        <td onclick="location.href='<?php echo $url; ?>';loadingPagia();"><?php echo ucfirst($value['HStatus']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-danger pull-left" id="btnApagaSolicitacao">Cancelar Selecionados</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ADICIONAR FOREACH AQUI -->
                    <div role="tabpanel" class="tab-pane fade in" id="tab_content4">
                        <div class="x_content">
                            <div id="datatable-responsive_wrapper"
                                 class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-6 col-md-12"></div>
                                    <div class="col-sm-6 col-md-12"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                               cellspacing="0" width="100%" role="grid"
                                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0"
                                                    aria-controls="datatable-responsive" rowspan="1" colspan="1"
                                                    style="width: 5px;" aria-sort="ascending"
                                                    aria-label="First name: activate to sort column descending">
                                                    Num.Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Usuario
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Processo
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">Data
                                                    Solicitação
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">
                                                    Desc.Atividade
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                    rowspan="1" colspan="1" style="width: 10px;"
                                                    aria-label="Last name: activate to sort column ascending">
                                                    Status
                                                </th>
                                            </thead>
                                            <tbody>

                                            <?php foreach ($atividadeEmAndamento as $value):
                                                //echo strtotime(date('Y-m-d H:i:s')) .' >= '. strtotime($value['dataSolicitacao'])."<br>";
                                                if($value['idForm'] != 3 || (strtotime(date('Y-m-d H:i:s')) >= strtotime($value['dataSolicitacao']))):                                                    
                                                    $from = $value['url'];
                                                    $url = URL . "/Fluxo/callRegras/$from/".$value['idSolicitacao']."/".$value['idAtividade']."/".'0/0/0/0'.$value['idMovimentacao'];
                                            ?>                                                
                                                <tr onclick="location.href='<?php echo URL ?>/Fluxo/callRegras/<? echo $value['url'] ?>/<?php echo $value['idSolicitacao']?>/0/0/0/0';loadingPagia()">
                                                    <td><?php echo ucfirst($value['idSolicitacao']) ?></td>
                                                    <td><?php echo ucfirst($value['usuarios']) ?></td>
                                                    <td><?php echo ucfirst($value['descricao']) ?></td>
                                                    <td><?php echo date('d/m/Y H:i:s', strtotime($value['dataSolicitacao'])); ?></td>
                                                    <td><?php echo ucfirst($value['descAtividade']) ?></td>
                                                    <td><?php echo ucfirst($value['HStatus']) ?></td>
                                                </tr>
                                            <?php 
                                                endif;
                                            endforeach;                                         
                                            ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModalResult" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="result_msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Marca ou desmarca checkbox de grupos
        $('#checkAllSolicitacao').on('click', function () {
            $('.checkSolicitacao').not(this).prop('checked', this.checked);
        });
    });

    // Apaga grupos selecionados no datatable
    $('#btnApagaSolicitacao').on('click', function(){
        var solicitacaoDel = [];

        $('.checkSolicitacao').each(function(){
            if($(this).prop('checked')) {
                solicitacaoDel.push($(this).attr('id'));
            }
        });

        // Valida se foi selecionado ao menos uma solicitacao para cancelamento
        if(solicitacaoDel.length == 0){
            return false;
        }

        $('#formSolicitacao').submit();
    });
</script>




