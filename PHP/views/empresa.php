<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Manutenção</li>
        <li class="active">Empresa</li>
    </ol>
</div>   

<br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Empresas</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">          
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $this->helper->alertMessage(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalAddEmp" ><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Adicionar Empresa</font></font></button>
                    </div>
                </div>
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>                            
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">#</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">ID Totvs</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Razão Social</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Position: activate to sort column ascending">CNPJ</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Position: activate to sort column ascending">Matriz</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Position: activate to sort column ascending">Ação</th>
                                </thead>
                                <tbody> 
                                    <?php foreach ($dadosEmpresa as $value): ?>
                                        <tr>
                                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><input type="hidden" name="idEmpresa" value="<?php echo $value['idEmpresa'] ?>"><?php echo $value['idEmpresa'] ?></font></font></td>
                                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo utf8_encode($value['idLegEmpresa']) ?></font></font></td>
                                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['razaoSocial'] ?></font></font></td>
                                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['cnpj'] ?></font></font></td>
                                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php
                                                if ($value['matriz'] == 1) {
                                                    echo "Sim";
                                                } else {
                                                    echo "Não";
                                                }
                                                ?></font></font></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-xs btnEditaEmpresa" data-toggle="modal" data-target="#myModal" onclick="editInstancia('<?php echo $value['idEmpresa'] ?>', '<?php echo utf8_encode($value['idLegEmpresa']) ?>', '<?php echo $value['razaoSocial'] ?>', '<?php echo $value['cnpj'] ?>', '<?php echo $value['logo'] ?>')">Editar</button> 
                                                <button 
                                                    type="button" 
                                                    class="btn btn-danger btn-xs" 
                                                    name="excluirEmp" 
                                                    id="excluirEmp"                                 
                                                    onclick="excluiEmpresa('<?php echo $value['idEmpresa'] ?>', '<?php echo $value['razaoSocial'] ?>')">Excluir</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Empresa</h4>
            </div>
            <form method="POST" action="<?php echo URL; ?>/Empresa/editaEmpresa" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row hide" id="divImgLogo">
                        <div class="col-md-12">
                            <div class="col-md-12 center-block">
                                <p class="text-center">Logo cadastrado</p>
                            </div>
                            <img src="" id="imgLogo" class="img-responsive center-block" width="100">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Novo logo</label>
                            <input type="file" class="form-control" name="logo" accept="jpg,png">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>ID Totvs</label>
                            <input type="text" readonly="readonly" name="idTotvs" id="idTotvs" class="form-control">
                            <input type="text" readonly="readonly" name="idEmpresa" id="idEmpresa" class="form-control hide">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Razão Social</label>
                            <input type="text"  name="razaoSocial" id="razaoSocial" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>CNPJ</label>
                            <input type="text"  name="cnpj" id="cnpj" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <hr>
                    <h5><u>Integração TOTVS DATASUL</u></h5>
                    <div class="row">
                        <div class="col-md-9">
                            <label>URL</label>
                            <input type="text" name="execBO[url]" id="execboUrl" class="form-control"  autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>Integra?</label>
                            <select class="form-control" name="execBO[integra]" id="execboIntegra">
                                <option value="true">Sim</option>
                                <option value="false">Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>DevKey</label>
                            <input type="text"  name="execBO[devKey]" id="execboDevKey" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>Password</label>
                            <input type="text"  name="execBO[password]" id="execboPassword" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>UserLogin</label>
                            <input type="text"  name="execBO[userLogin]" id="execboUserLogin" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>LocalEntrega</label>
                            <input type="text"  name="execBO[localEntrega]" id="execboLocalEntrega" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" name="salvarEdit" value="salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModalAddEmp" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cadastrar Empresa</h4>
            </div>
            <form method="POST" action="<?php echo URL; ?>/Empresa/cadastraEmpresa" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Logo</label>
                            <input type="file" class="form-control" name="logo" accept="jpg,png">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>ID Totvs</label>
                            <input type="text" name="AddIdTotvs" id="AddIdTotvs" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Razão Social</label>
                            <input type="text"  name="AddRazaoSocial" id="AddRazaoSocial" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>CNPJ</label>
                            <input type="text"  name="AddCnpj" id="AddCnpj" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <h5><u>Integração TOTVS DATASUL</u></h5>
                    <div class="row">
                        <div class="col-md-9">
                            <label>URL</label>
                            <input type="text" name="execBO[url]" class="form-control"  autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>Integra?</label>
                            <select class="form-control" name="execBO[integra]">
                                <option value="true">Sim</option>
                                <option value="false">Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>DevKey</label>
                            <input type="text"  name="execBO[devKey]" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>Password</label>
                            <input type="text"  name="execBO[password]" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>UserLogin</label>
                            <input type="text"  name="execBO[userLogin]" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label>LocalEntrega</label>
                            <input type="text"  name="execBO[localEntrega]" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" name="addEmpresa" value="addEmpresa">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModalResult" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Voltar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">    
    function editInstancia(idEmpresa,idLegEmpresa,razaoSocial,cnpj,logo){
        $("#idTotvs").val(idLegEmpresa);
        $("#idEmpresa").val(idEmpresa);
        $("#razaoSocial").val(razaoSocial);
        $("#cnpj").val(cnpj);
        
        if(logo != ''){
            $("#imgLogo").attr('src', url+'arquivos/'+logo);
            $("#imgLogo").removeClass('hide');
            $("#divImgLogo").removeClass('hide');
            
        }else{
            $("#imgLogo").addClass('hide');
            $("#divImgLogo").addClass('hide');
        }
    }

    function excluiEmpresa(idEmpresa, razaoSocial){
        var confirma = confirm('Tem certeza que deseja excluir a empresa ' + razaoSocial + '?');
        
        if(confirma === true){
            window.location.href = url + 'Empresa/excluiEmpresa/' + idEmpresa;           
        }
    }

    $(document).ready(function(){
        $('.btnEditaEmpresa').on('click', function(){
            $.ajax({
                type: 'POST',
                url: url+'Empresa/ajaxBuscaIntegrationData',
                data: {
                    idEmpresa: $(this).closest('tr').find('input[name="idEmpresa"]').val()
                },
                dataType: 'JSON',
                success: function (res) {
                    console.log(res)
                    var _res = res.dados;
                    var integra = ((_res.integra == "true") ? 'true' : 'false');
                    
                    $('#execboUrl').val(_res.url);
                    $('#execboDevKey').val(_res.devKey);
                    $('#execboIntegra').val(integra);
                    $('#execboLocalEntrega').val(_res.localEntrega);
                    $('#execboPassword').val(_res.password);
                    $('#execboUserLogin').val(_res.userLogin);                    
                }
            });
        });
        
    });
</script>