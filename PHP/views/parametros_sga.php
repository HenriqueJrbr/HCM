<style>
    h3{
        color: #FF880D;
        font-size: 16px;
        padding-left: 10px;
        text-decoration: underline
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Parâmetros do SGA</li>
  </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Parâmetros do SGA</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $this->helper->alertMessage(); ?>
                    </div>
                </div>
                <br>  
                <form method="POST" id="frmUsuario" action="<?= URL; ?>/ConfiguracaoSga/salvaParamGlobal" enctype="multipart/form-data">                    
                    <div class="row">
                        <div class="col-md-9">
                            <label>Host</label>
                            <input type="text" id="host" name="host" class="form-control" value="<?php echo $config['host'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Ambiente</label>
                            <input type="hidden" id="ambiente" name="ambiente" class="form-control" value="<?php echo $config['ambiente'] ?>" required>
                            <input type="text" class="form-control" value="<?php echo $config['ambiente'] ?>" disabled>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <h3>E-MAIL</h3>
                        <div class="col-md-4">
                            <label>Email</label>
                            <input type="text" id="email_email" name="email[email]" class="form-control" value="<?php echo isset($email['email']) ? $email['email'] : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label>Senha</label>
                            <input type="text" id="email_senha" name="email[senha]" class="form-control" value="<?php echo isset($email['senha']) ? $email['senha'] : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Remetente</label>
                            <input type="text" id="email_remetente" name="email[remetente]" class="form-control" value="<?php echo isset($email['remetente']) ? $email['remetente'] : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label>SMTP</label>
                            <input type="text" id="email_smtp" name="email[smtp]" class="form-control" value="<?php echo isset($email['smtp']) ? $email['smtp'] : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Porta</label>
                            <input type="text" id="email_portaSmtp" name="email[portaSmtp]" class="form-control" value="<?php echo isset($email['portaSmtp']) ? $email['portaSmtp'] : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Envia logo empresa</label>
                                <select class="form-control" name="email[envia_logo_instancia]">
                                    <option value="0" <?php echo isset($email['envia_logo_instancia']) && $email['envia_logo_instancia'] == 0 ? 'selected' : ''; ?>>Não</option>
                                    <option value="1" <?php echo isset($email['envia_logo_instancia']) && $email['envia_logo_instancia'] == 1 ? 'selected' : ''; ?>>Sim</option>                                
                                </select>
                            </div>
                        </div>

                    <hr>
                    <div class="row">
                        <h3>LDAP</h3>
                        <div class="col-md-3">
                            <label>IP_AD</label>
                            <input type="text" id="ldap_ip_ad" name="ads[ldap][ip_ad]" class="form-control" value="<?php echo isset($ldap->ip_ad) ? $ldap->ip_ad : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Domínio</label>
                            <input type="text" id="ldap_dominio" name="ads[ldap][dominio]" class="form-control" value="<?php echo isset($ldap->dominio) ? $ldap->dominio : '' ?>">
                        </div>
                        <div class="col-md-3">
                        <label>Integra</label>
                            <select class="form-control" name="ads[ldap][integra]">
                                <option value="false" <?php echo isset($ldap->integra) && $ldap->integra == 'false' ? 'selected' : ''; ?>>Não</option>
                                <option value="true" <?php echo isset($ldap->integra) && $ldap->integra == 'true' ? 'selected' : ''; ?>>Sim</option>                                
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <h3>AZURE</h3>
                        <div class="col-md-3">
                            <label>Return URL</label>
                            <input type="text" id="azure_return_url" name="ads[azure][return_url]" class="form-control" value="<?php echo isset($azure->return_url) ? $azure->return_url : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Directory</label>
                            <input type="text" id="azure_directory" name="ads[azure][directory]" class="form-control" value="<?php echo isset($azure->directory) ? $azure->directory : '' ?>">
                        </div>                        
                        <div class="col-md-3">
                            <label>Client Id</label>
                            <input type="text" id="azure_client_id" name="ads[azure][client_id]" class="form-control" value="<?php echo isset($azure->client_id) ? $azure->client_id : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Client Secret</label>
                            <input type="text" id="azure_client_secret" name="ads[azure][client_secret]" class="form-control" value="<?php echo isset($azure->client_secret) ? $azure->client_secret : '' ?>">
                        </div>                        

                        <div class="col-md-3">
                            <label>Enabled</label>
                            <select class="form-control" name="ads[azure][enabled]">
                                <option value="1" <?php echo isset($azure->enabled) && $azure->enabled == 1 ? 'selected' : ''; ?>>Sim</option>
                                <option value="0" <?php echo isset($azure->enabled) && $azure->enabled == 0 ? 'selected' : ''; ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Integra</label>
                            <select class="form-control" name="ads[azure][integra]">
                                <option value="false" <?php echo isset($azure->integra) && $azure->integra == 'false' ? 'selected' : ''; ?>>Não</option>
                                <option value="true" <?php echo isset($azure->integra) && $azure->integra == 'true' ? 'selected' : ''; ?>>Sim</option>                                
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"><hr></div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success pull-right" value="Salvar">
                            <button type="button" class="btn btn-danger pull-right" onclick="javascript:history.back(-1)">Voltar</button>                            
                        </div>
                    </div>
                </form>
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
                    <p class="msgBody" style='margin: 5px 10px'>Tem certeza que deseja continuar?</p>                                    
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

<script type="text/javascript">
    $(document).ready(function(){   
        $('#btnAtualiza').on('click', function(){
           $("#myModalConfirm").modal('show');
           $(document).on("click", '#myModalConfirm #continue', function(e) {
                $("#myModalConfirm").modal('hide');
                location.href = url + '/ConfiguracaoSga/atualizaSnapshots';
            });     

            // Se botao de cancelar for clicado
            $('#myModalConfirm').find('#cancel').on("click", function(e) {
                $("#myModalConfirm").modal('hide');                
                return false;
            });
        });
    });
</script>