<style type="text/css">
    #user-list,
    #group-list {
        float: left;
        list-style: none;
        margin-top: -10px;
        margin-left: 0;
        padding: 0;
        height: 200px;
        width: 95%;
        position: absolute;
        z-index: 999;
        overflow: auto;
    }

    #user-list li,
    #group-list li {
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #e6e6e6 1px solid;
        /*border-radius: 5px;*/
        border-left: #e6e6e6 1px solid;
        border-right: #e6e6e6 1px solid;
    }

    #user-list li:hover,
    #group-list li:hover {
        background: #ece3d2;
        cursor: pointer;
    }

    #search-box {
        padding: 10px;
        border: #a8d4b1 1px solid;
        border-radius: 4px;
    }

    .panel-title {
        cursor: pointer
    }

    .list-user,
    .list-grupo {
        list-style: none;
        margin-left: 0;
        padding-left: 0;
    }

    .list-user li,
    .list-grupo li {
        border-bottom: solid 1px #e6e6e6;
        padding: 6px;
    }

    .list-user li .fa-remove,
    .list-grupo li .fa-remove {
        cursor: pointer;
    }
</style>
<div class="col-md-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>/">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                    <font style="vertical-align: inherit;">Dashboard</font>
                </font>
            </a>
        </li>
        <li class="active">
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">Agendamento de Revisão de Acesso</font>
            </font>
        </li>
    </ol>
</div>

    <div class="x_panel">
        <div class="x_title">
            <h2>Agendamento de Revisão de Acesso</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
        </div>
        <form action="<?php echo URL; ?>/Agendamento/add_agenda_revisao_acesso" id="frmAgendamento" method="POST">
            <div class="row">
                <div class="col-md-11"></div>
                <div class="col-md-1"><input type="submit" name="enviar" id="enviar" value="Enviar" class="btn btn-success"></div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>Empresa</label>
                    <select class="form-control select2" name="empresa" id="selEmpresa" required>
                        <?php foreach($empresa as $val): ?>
                            <option value="<?php echo $val['idEmpresa']; ?>" <?php ($val['idEmpresa'] == $_SESSION['empresaid']) ? 'selected' : '' ?>><?php echo $val['razaoSocial']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>Data Inicial</label>
                    <input type="date" name="dataInicio" id="inicio" class="form-control" min="<?php echo date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')); ?>" required>
                    <br>
                    <label>Hora Inicial</label>
                    <input type="time" name="horaInicio" id="horaInicio" class="form-control" style="width:92px" required>
                </div>                
                <div class="col-md-2">
                    <label>Data Final</label>
                    <input type="date" name="dataFim" id="fim" class="form-control" min="<?php echo date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')); ?>" required>
                </div>  
                <div class="col-md-6">
                    <label>Usuário</label>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-info" id="addTodos">Todos</button>
                        </div>
                        <input type="text" name="usuario" id="nomeUsuario" class="form-control" autocomplete="off">
                        <input type="text" name="idUsuario" id="idUsuario" class="form-control hide">
                        <input type="text" name="idEmpresaHide" id="idEmpresaHide" class="form-control hide">
                        <input type="text" name="razaoSocial" id="razaoSocial" class="form-control hide">
                    </div>

                    <div id="suggesstion-user-box">
                        <ul id="user-list"></ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label><br>
                    <button type="button" class="btn btn btn-success pull-left" id="addUsuario">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Usuários adicionados</h4></div>
                <div class="panel-body">
                    <ul class="list-user"></ul>
                </div>
            </div>
        </form>
        <br>
        
        <div class="row">           
            <div class="col-md-12">
                <div role="tabpane2" class="tab-pane fade active in" id="tab_content20" aria-labelledby="profile-tab20"><!--Inicio Tabela 1 -->
                    <div class="" role="tabpane2" data-example-id="togglable-tabs2">
                        <ul id="myTab2" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content4" id="profile-tab4" role="tab" data-toggle="tab" aria-expanded="true">Agendamentos Planejados</a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_content5" id="profile-tab5" role="tab" data-toggle="tab" aria-expanded="true">Agendamentos que não ocorreram</a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_content6" id="profile-tab6" role="tab" data-toggle="tab" aria-expanded="true">Agendamentos executados</a>
                            </li>
                        </ul>
                        <div id="myTabContent2" class="tab-content">
                            <div role="tabpane2" class="tab-pane fade active in" id="tab_content4" aria-labelledby="profile-tab4"><!--Inicio Tabela 1 -->
                                <form action="<?php echo URL ?>/Agendamento/apagaAgendamentos" method="post">
                                    <div id="datatable-responsive_wrapper"
                                         class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="col-sm-6"></div>
                                            <div class="col-sm-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                       cellspacing="0" width="100%" role="grid"
                                                       aria-describedby="datatable-responsive_info"
                                                       style="width: 100%;" id="table">
                                                    <thead>
                                                    <tr role="row">
                                                        <th ><input type="checkbox" id="checkAllAgendamento"></th>
                                                        <th>Data inicial</th>
                                                        <th>Data final</th>
                                                        <th>Usuários</th>
                                                        <th>Empresa</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <button type="submit" id="btnApagaAgendamento" class="btn btn-danger">Excluir</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpane2" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab5"><!--Inicio Tabela 1 -->
                                <div id="datatable-responsive_wrapper"
                                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                   cellspacing="0" width="100%" role="grid"
                                                   aria-describedby="datatable-responsive_info"
                                                   style="width: 100%;" id="tableInativo">
                                                <thead>
                                                <tr role="row">                                                   
                                                    <th>Data inicial</th>
                                                    <th>Data final</th>
                                                    <th>Usuários</th>
                                                    <th>Empresa</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpane2" class="tab-pane fade" id="tab_content6" aria-labelledby="profile-tab6"><!--Inicio Tabela 1 -->
                                <div id="datatable-responsive_wrapper"
                                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                   cellspacing="0" width="100%" role="grid"
                                                   aria-describedby="datatable-responsive_info"
                                                   style="width: 100%;" id="tableFinalizado">
                                                <thead>
                                                <tr role="row">                                                   
                                                    <th>Data inicial</th>
                                                    <th>Data final</th>
                                                    <th>Usuários</th>
                                                    <th>Empresa</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
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
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-exclamation"></i> &nbsp;</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="result_msg"></p>
                        <div class="row">
                            <div class="col-sm-12" style="height: 350px; overflow-y: auto; display: none" id="tblExcluiAgendamentoTodos">
                                <form action="<?php echo URL ?>/Agendamento/apagaAgendamentos" method="post" id="frmExcluiAgendaTodos"> 
                                    <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                        cellspacing="0" width="100%" role="grid"
                                        aria-describedby="datatable-responsive_info"
                                        style="width: 100%;">
                                        <thead>
                                        <tr role="row">                                                                     
                                            <th>Usuário</th>
                                            <th>Data inicial</th>
                                            <th>Data final</th>
                                            <th>Solicitante</th>
                                            <th>Empresa</th>
                                        </tr>
                                        </thead>
                                        <tbody id="bodyTblExcluiAgendaTodos"></tbody>
                                    </table>
                                </form>
                            </div>
                            <div class="col-md-12" id="btnTblExcluiAgendaTodos" style="display:none">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Excluir todos</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Voltar</button>
            </div>
        </div>
    </div>
</div>

<!-- set up the modal to start hidden and fade in and out -->
<div id="myModalConfirm" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body">
                <div class="row">
                    <p class="msgBody" style='margin: 5px 10px'></p>
                    <div class="col-sm-12" style="height: 350px; overflow-y: auto; display: none" id="tblAgendamentoPosterior">                        
                        <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                            cellspacing="0" width="100%" role="grid"
                            aria-describedby="datatable-responsive_info"
                            style="width: 100%;">
                            <thead>
                            <tr role="row">                                                                     
                                <th>Usuário</th>
                                <th>Data inicial</th>
                                <th>Data final</th>
                                <th>Solicitante</th>
                                <th>Empresa</th>
                            </tr>
                            </thead>
                            <tbody id="bodyTblAgendamentoPosterior"></tbody>
                        </table>                        
                    </div>                    
                </div>
            </div>
            <!-- dialog buttons -->
            <div class="modal-footer">
                <button type="button" id="cancel" class="btn btn-danger">Cancelar</button>
                <button type="button" id="continue" class="btn btn-success">Continuar</button>
            </div>
        </div>
    </div>
</div>

<?php $this->helper->scriptDataTable('table', 'Agendamento/ajaxDatatableAgendamento', 'POST', 'false'); ?>
<?php $this->helper->scriptDataTable('tableInativo', 'Agendamento/ajaxDatatableAgendamentoInativo', 'POST', 'false'); ?>
<?php $this->helper->scriptDataTable('tableFinalizado', 'Agendamento/ajaxDatatableAgendamentoFinalizado', 'POST', 'false'); ?>

<script type="text/javascript">

    $(document).ready(function () {

        // Valida data final. Não pode ser menor que data inicial
        $('#inicio').on('change', function(){
            var dataInicial = $(this).val();                                                        
            $('#fim').attr('min', dataInicial);
        });

        $("#nomeUsuario").keyup(function () {

            // Se for digitado * não busca registros. Caracter usado para inclusão de todos
            if ($(this).val() == '*') {
                return false;
            }

            if ($(this).val().length == 0) {
                $("#idUsuario").val("");
            }

            var idUsr = $(this).val();
            $.ajax({
                type: "POST",
                url: url + "Agendamento/ajaxCarregaUsuarios",
                data: {
                    idUsr: idUsr,
                    idEmpresa: $('#selEmpresa').val()
                },
                beforeSend: function () {
                    $("#usr").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-user-box").show();
                    $("#user-list").html(data);
                }
            });
        });

        // Adiciona o usuário que estiver no campo usuário
        $('#addUsuario').on('click', function (ev) {
            ev.preventDefault();

            // Verifica se foi selecionado uma data
            if((typeof $('#inicio').val() == 'undefined' || $('#inicio').val() == '') || (typeof $('#horaInicio').val() == 'undefined' || $('#horaInicio').val() == '') || (typeof $('#fim').val() == 'undefined' || $('#fim').val() == '')){
                $('.modal-title').text('');
                $('#result_msg').text('Favor selecione uma data e hora inicial e final!');
                $('#myModalResult').modal('show'); 
                return false;
            }

            if (($("#nomeUsuario").val() == '') || $("#idUsuario").val() == '') {
                return false;
            }

            var idUser = $("#idUsuario").val();
            var user = $("#nomeUsuario").val();
            var idEmpresa = $('#idEmpresaHide').val();
            var usrExists = false;
            var dataSup = false;                       

            // percorre os usuarios adicionados e valida se existe usuario com mesmo id
            $('.list-user li input').each(function () {
                
                var id = $(this).val();
                var idSpl = id.split(' - ');
                console.log(idSpl);                
                
                // Validar se já existe um agendamento com datas superiores à data selecionada.                
                if((idSpl[0] === $('#idUsuario').val() || idSpl[0] === '*') && (idSpl[2] > $('#fim').val().replace(/\-/g, '/')) ){
                    dataSup = true;                    
                }
                
                //if(idSpl[0] === $('#idUsuario').val() && (idSpl[2] === $('#inicio').val().replace(/\-/g, '/') || idSpl[2] === $('#fim').val().replace(/\-/g, '/')) || (idSpl[3] === $('#inicio').val().replace(/\-/g, '/') || idSpl[3] === $('#fim').val().replace(/\-/g, '/'))){
                if((idSpl[0] === $('#idUsuario').val() || idSpl[0] === '*') && ($('#inicio').val().replace(/\-/g, '/') >= idSpl[2] && $('#fim').val().replace(/\-/g, '/') <= idSpl[3])/* || (idSpl[3] === $('#inicio').val().replace(/\-/g, '/') || idSpl[3] === $('#fim').val().replace(/\-/g, '/'))*/){
                    $('.modal-title').text('');
                    $('#result_msg').text('Agendamento já adicionado para o usuário com as datas selecionadas!');
                    $('#myModalResult').modal('show');
                    usrExists = true;
                    return false;
                }
                
                // Verifica se o TODOS já está adicionado
                if (id == '*') {
                    $('.modal-title').text('');
                    $('#result_msg').text('A opção TODOS abrange todos os usuários!');
                    $('#myModalResult').modal('show');
                    usrExists = true;                    
                    return false;
                }
            });
          
            if (usrExists) {
                return false;
            }
                                    
            // Valida se já existe agendamento para o mesmo período e usuário na tela
            var dataInicioSpl = $('#inicio').val().split('-');
            var dataInicioAgenda = dataInicioSpl[2] + '/' + dataInicioSpl[1] + '/' + dataInicioSpl[0];
            var dataFimSpl = $('#fim').val().split('-');
            var dataFimAgenda = dataFimSpl[2] + '/' + dataFimSpl[1] + '/' + dataFimSpl[0];
            var htmluser = "";

            htmluser = "<li><input type=\"hidden\" name=\"usuarios[]\" value=\"" + idUser + ' - ' + $('#idEmpresaHide').val() + ' - ' + $('#inicio').val().replace(/-/g,'/') + ' ' + $('#horaInicio').val() + ' - ' + $('#fim').val().replace(/-/g,'/') +  "\"> <strong>" + dataInicioAgenda + ' ' + $('#horaInicio').val() + ' - ' + dataFimAgenda + ' - ' + user + '</strong> - <span style="font-size:10px">' + $('#razaoSocial').val() + "</span>  <i class=\"fa fa-remove pull-right\"></i></li>";

            $.ajax({
                type: 'POST',
                url: url + 'Agendamento/ajaxValidaAgendaExistente',
                data: {
                    dataInicio: $("#inicio").val(),
                    dataFim: $("#fim").val(),
                    usuarios: $("#idUsuario").val(),
                    idEmpresa: $('#idEmpresaHide').val()
                },
                success: function(res){
                    res = JSON.parse(res);
                    $('#tblExcluiAgendamentoTodos').css('display', 'none');
                    $('#btnTblExcluiAgendaTodos').css('display', 'none');
                    $('#tblAgendamentoPosterior').css('display', 'none');                    
                    $('#bodyTblExcluiAgendaTodos').html('');
                    $('#bodyTblAgendamentoPosterior').html('');
                    
                    
                    if(res.result === 'revAberta'){
                        $('.modal-title').text('');
                        $('#result_msg').text('Já existe revisão aberta dentro do período selecionado para o usuário!');
                        $('#tblExcluiAgendamentoTodos').css('display', 'block');
                        $('#bodyTblExcluiAgendaTodos').html(res.data);
                        $('#myModalResult').modal('show');
                        //usrExists = true;
                        return false;
                    }else if(res.result === 'existente'){
                        $('.modal-title').text('');
                        $('#result_msg').text('Agendamento já existente para usuário dentro do período selecionado!');
                        $('#tblExcluiAgendamentoTodos').css('display', 'block');
                        $('#bodyTblExcluiAgendaTodos').html(res.data);
                        $('#myModalResult').modal('show');
                        //usrExists = true;
                        return false;
                    }else if(res.result === 'posterior'){                                                
                        $('#myModalConfirm .modal-body .msgBody').html('Já existe agendamento adicionado para esse usuário superior ao período de <strong>'+ $("#inicio").val().substr(0, 10).split('-').reverse().join('/') +'</strong> à <strong>' + $("#fim").val().substr(0, 10).split('-').reverse().join('/') +'</strong>. Deseja continuar?');
                        $('#tblAgendamentoPosterior').css('display', 'block');
                        $('#bodyTblAgendamentoPosterior').html(res.data);
                        $("#myModalConfirm").modal('show');

                        var continua = new Promise(function(resolve, reject) {
                            $(document).on("click", '#myModalConfirm #continue', function(e) {
                                $("#myModalConfirm").modal('hide');
                                resolve(1);
                            });     

                            // Se botao de cancelar for clicado
                            $('#myModalConfirm').find('#cancel').on("click", function(e) {
                                $("#myModalConfirm").modal('hide');
                                reject (0);
                            });
                        });                            
                        // Se botao de continuar for clicado
                        continua.then(function(value){
                            $(".list-user").append(htmluser);
                            $("#nomeUsuario").val('');
                            $("#idUsuario").val('');
                            $('#idEmpresaHide').val('');
                            $("#razaoSocial").val('');                                
                        });                        
                    }else if(res.result === 'erro'){
                        $('.modal-title').text('');
                        $('#result_msg').text('Erro ao validar usuário!');
                        $('#myModalResult').modal('show');
                        //usrExists = true;
                        return false;
                    }else{                        
                        if(dataSup){
                            $('#myModalConfirm .modal-body').html('<h5>Já existe agendamento adicionado para esse usuário com data superior à data escolhida. Deseja continuar?</h5>');
                            $('#tblAgendamentoPosterior').css('display', 'block');
                            $('#bodyTblAgendamentoPosterior').html(res.data);
                            $("#myModalConfirm").modal('show');

                            var continua = new Promise(function(resolve, reject) {
                                $(document).on("click", '#myModalConfirm #continue', function(e) {
                                    $("#myModalConfirm").modal('hide');
                                    resolve(1);
                                });     
                                
                                // Se botao de cancelar for clicado
                                $('#myModalConfirm').find('#cancel').on("click", function(e) {
                                    $("#myModalConfirm").modal('hide');
                                    reject (0);
                                });
                            });                            
                            // Se botao de continuar for clicado
                            continua.then(function(value){
                                $(".list-user").append(htmluser);
                                $("#nomeUsuario").val('');
                                $("#idUsuario").val('');
                                $('#idEmpresaHide').val('');
                                $("#razaoSocial").val('');                                
                            });                                                                                        
                        }else{
                            $(".list-user").append(htmluser);                                
                            $("#nomeUsuario").val('');
                            $("#idUsuario").val('');
                            $('#idEmpresaHide').val('');
                            $("#razaoSocial").val('');                            
                        }
                    }
                }
            });                                                           
        });
    });
    
    //Remove a linha do grupo
    $(document).on('click', '.fa-remove', function () {
        $(this).closest('li').remove();
    });

    // Adiciona todos usuários. Obs. Limpa todos os usuários ja adicionados
    $(document).on('click', '#addTodos', function(){
        if($('#inicio').val() == '' || $('#fim').val() == '' || (typeof $('#horaInicio').val() == 'undefined' || $('#horaInicio').val() == '')){
            $('.modal-title').text('Atenção');
            $('#result_msg').text('Favor preencher as datas corretamente!');
            $('#myModalResult').modal('show');
            return false;
        }
        
        // Valida se já existe agendamento para o mesmo período e usuário na tela
        var usrExists = false;
        var dataSup = false;     
        var dataInicioSpl = $('#inicio').val().split('-');
        var dataInicioAgenda = dataInicioSpl[2] + '/' + dataInicioSpl[1] + '/' + dataInicioSpl[0];
        var dataFimSpl = $('#fim').val().split('-');
        var dataFimAgenda = dataFimSpl[2] + '/' + dataFimSpl[1] + '/' + dataFimSpl[0];
        
        var dataspl = $('#inicio').val().split('-');
        var dataAgenda = dataspl[2] + '/' + dataspl[1] + '/' + dataspl[0];
        var htmluser = "";

        htmluser = "<li><input type=\"hidden\" name=\"usuarios[]\" value=\"* - " + $("#selEmpresa").val() + ' - ' + $('#inicio').val().replace(/-/g,'/') + ' ' + $('#horaInicio').val() + ' - ' + $('#fim').val().replace(/-/g,'/') +"\"><strong>" + dataAgenda + ' ' + $('#horaInicio').val() + "</strong> - <strong>" + $('#fim').val().substr(0, 10).split('-').reverse().join('/') +"</strong>  - TODOS - <strong>" + $('#selEmpresa option:selected').text() + "</strong></span> <i class=\"fa fa-remove pull-right\"></i></li>";
        //htmluser = "<li><input type=\"hidden\" name=\"usuarios[]\" value=\"" + idUser + ' - ' + $('#idEmpresaHide').val() + ' - ' + $('#inicio').val().replace(/-/g,'/') + ' - ' + $('#fim').val().replace(/-/g,'/') +  "\"> <strong>" + dataInicioAgenda + ' - ' + dataFimAgenda + ' - ' + user + '</strong> - <span style="font-size:10px">' + $('#razaoSocial').val() + "</span>  <i class=\"fa fa-remove pull-right\"></i></li>";

        // percorre os usuarios adicionados e valida se existe usuario com mesmo id
        $('.list-user li input').each(function () {

            var id = $(this).val();
            var idSpl = id.split(' - ');
            console.log(idSpl);                
            console.log(idSpl[2] + ' = ' + $('#fim').val().replace(/\-/g, '/'));
            // Validar se já existe um agendamento com datas superiores à data selecionada.             
            if(idSpl[0] === '*' && (idSpl[2] > $('#fim').val().replace(/\-/g, '/')) ){  
                dataSup = true;                    
            }
            
            //if(idSpl[0] === $('#idUsuario').val() && (idSpl[2] === $('#inicio').val().replace(/\-/g, '/') || idSpl[2] === $('#fim').val().replace(/\-/g, '/')) || (idSpl[3] === $('#inicio').val().replace(/\-/g, '/') || idSpl[3] === $('#fim').val().replace(/\-/g, '/'))){
            if(($('#inicio').val().replace(/\-/g, '/') >= idSpl[2] && $('#fim').val().replace(/\-/g, '/') <= idSpl[3])/* || (idSpl[3] === $('#inicio').val().replace(/\-/g, '/') || idSpl[3] === $('#fim').val().replace(/\-/g, '/'))*/){
                $('.modal-title').text('');
                $('#result_msg').text('Agendamento já adicionado para usuários com o período selecionado!');
                $('#myModalResult').modal('show');
                usrExists = true;
                return false;
            }

            // Verifica se o TODOS já está adicionado
            if (id == '*') {
                $('.modal-title').text('');
                $('#result_msg').text('A opção TODOS abrange todos os usuários!');
                $('#myModalResult').modal('show');
                usrExists = true;                    
                return false;
            }
        });

        if (usrExists) {
            return false;
        }

        $.ajax({
            type: 'POST',
            url: url + 'Agendamento/ajaxValidaAgendaExistente',
            data: {
                dataInicio: $("#inicio").val(),
                dataFim: $("#fim").val(),
                usuarios: '',
                idEmpresa: $('#selEmpresa').val()
            },
            success: function(res){
                res = JSON.parse(res);
                $('#tblExcluiAgendamentoTodos').css('display', 'none');
                $('#btnTblExcluiAgendaTodos').css('display', 'none');
                $('#tblAgendamentoPosterior').css('display', 'none');                    
                $('#bodyTblExcluiAgendaTodos').html('');
                $('#bodyTblAgendamentoPosterior').html('');
                
                if(res.result === 'revAberta'){
                    $('.modal-title').text('');
                    $('#result_msg').text('Já existe revisão aberta dentro do período selecionado para os seguintes usuários!');   
                    $('#tblExcluiAgendamentoTodos').css('display', 'block');
                    $('#bodyTblExcluiAgendaTodos').html(res.data);
                    $('#myModalResult').modal('show');
                    //usrExists = true;
                    return false;
                }else if(res.result === 'existente'){
                    $('.modal-title').text('');
                    $('#result_msg').html('<p>Agendamento já existente para usuários dentro do período selecionado! Para criar agendamento para todos os usuários, excluir agendamentos existentes!</p>');
                    $('#tblExcluiAgendamentoTodos').css('display', 'block');
                    $('#btnTblExcluiAgendaTodos').css('display', 'block');
                    $('#bodyTblExcluiAgendaTodos').html(res.data);                    
                    $('#myModalResult').modal('show');
                    //usrExists = true;
                    return false;
                }else if(res.result === 'posterior'){                                                
                    $('#myModalConfirm .modal-body .msgBody').html('Já existe agendamento adicionado para esse usuário superior ao período de <strong>'+ $("#inicio").val().substr(0, 10).split('-').reverse().join('/') +'</strong> à <strong>' + $("#fim").val().substr(0, 10).split('-').reverse().join('/') +'</strong>. Deseja continuar?');
                    $('#tblAgendamentoPosterior').css('display', 'block');
                    $('#bodyTblAgendamentoPosterior').html(res.data);
                    $("#myModalConfirm").modal('show');

                    var continua = new Promise(function(resolve, reject) {
                        $(document).on("click", '#myModalConfirm #continue', function(e){
                            $("#myModalConfirm").modal('hide');
                            resolve(1);
                        });     

                        // Se botao de cancelar for clicado
                        $('#myModalConfirm').find('#cancel').on("click", function(e) {
                            $("#myModalConfirm").modal('hide');
                            reject (0);
                        });
                    });                            
                    // Se botao de continuar for clicado
                    continua.then(function(value){
                        $(".list-user").append(htmluser);
                        $("#nomeUsuario").val('');
                        $("#idUsuario").val('');
                        $('#idEmpresaHide').val('');
                        $("#razaoSocial").val('');                                
                    });                        
                }else if(res.result === 'erro'){
                    $('.modal-title').text('');
                    $('#result_msg').text('Erro ao validar usuário!');
                    $('#myModalResult').modal('show');
                    //usrExists = true;
                    return false;
                }else{                        
                    if(dataSup){
                        $('#myModalConfirm .modal-body').html('<h5>Já existe agendamento adicionado para esse usuário com data superior à data escolhida. Deseja continuar?</h5>');
                        $("#myModalConfirm").modal('show');

                        var continua = new Promise(function(resolve, reject) {
                            $(document).on("click", '#myModalConfirm #continue', function(e) {
                                $("#myModalConfirm").modal('hide');
                                resolve(1);
                            });     

                            // Se botao de cancelar for clicado
                            $('#myModalConfirm').find('#cancel').on("click", function(e) {
                                $("#myModalConfirm").modal('hide');
                                reject (0);
                            });
                        });                            
                        // Se botao de continuar for clicado
                        continua.then(function(value){
                            $(".list-user").append(htmluser);
                            $("#nomeUsuario").val('');
                            $("#idUsuario").val('');
                            $('#idEmpresaHide').val('');
                            $("#razaoSocial").val('');                                
                        });                                                                                        
                    }else{
                        $(".list-user").append(htmluser);                                
                        $("#nomeUsuario").val('');
                        $("#idUsuario").val('');
                        $('#idEmpresaHide').val('');
                        $("#razaoSocial").val('');                            
                    }
                }
            }
        });
        
        $(document).on('click', '#btnTblExcluiAgendaTodos', function(){
            $('#tblAgendamentoPosterior').css('display', 'none');
            $('#myModalConfirm .modal-body').html('<h5>Tem certeza que deseja excluir os agendamentos existentes? Esta alteração será irreversível. Deseja continuar?</h5>');
            $("#myModalConfirm").modal('show');

            var continua = new Promise(function(resolve, reject) {
                $(document).on("click", '#myModalConfirm #continue', function(e) {
                    $("#myModalConfirm").modal('hide');
                    resolve(1);
                });     

                // Se botao de cancelar for clicado
                $('#myModalConfirm').find('#cancel').on("click", function(e) {
                    $("#myModalConfirm").modal('hide');
                    reject (0);
                });
            });                            
            // Se botao de continuar for clicado
            continua.then(function(value){
                $('#frmExcluiAgendaTodos').submit();
            });
        });
        
        
        var dataspl = $('#inicio').val().split('-');
        var dataAgenda = dataspl[2] + '/' + dataspl[1] + '/' + dataspl[0];
        
        
        
        //htmluser = "<li><input type=\"hidden\" name=\"usuarios[]\" value=\"* - " + $("#selEmpresa").val() + ' - ' + $('#inicio').val().replace(/-/g,'/') + ' - ' + $('#fim').val().replace(/-/g,'/') +"\"><strong>" + dataAgenda + "</strong> - <strong>" + $('#fim').val().substr(0, 10).split('-').reverse().join('/') +"</strong>  - TODOS</span> <i class=\"fa fa-remove pull-right\"></i>  </li>";
        //$(".list-user").html(htmluser);
    });

    // Marca ou desmarca checkbox de agendamentos
    $('#checkAllAgendamento').on('click', function(){
        $('.checkAgenda').not(this).prop('checked', this.checked);
    });

    function carregaDadosUserAgenda(nomeUsr, idUsuario, idEmpresa, razaoSocial) {
        $("#nomeUsuario").val(nomeUsr);
        $("#idUsuario").val(idUsuario);
        $("#idEmpresaHide").val(idEmpresa);
        $("#razaoSocial").val(razaoSocial);
        $("#suggesstion-user-box").hide();
    }

    //Submete o formulário se houver ao menos um usuário adicionado
    $('#enviar').on('click', function(){
        if($('.list-user li input').length > 0){
            $('#frmAgendamento').submit();
        }else{

        }
    });

    // Apaga os dados dos usuário pesquisado. Para não adicionar usuarios com dados divergentes
    $('#selEmpresa').on('change', function(){
        $("#nomeUsuario").val('');
        $("#idUsuario").val('');
        $("#idEmpresaHide").val('');
        $("#razaoSocial").val('');
    });

</script>
